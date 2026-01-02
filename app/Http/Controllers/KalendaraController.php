<?php

namespace App\Http\Controllers;

use App\Models\Kalendars;
use App\Models\Komanda;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class KalendaraController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = Kalendars::query();
       
            if ($request->has('start') && $request->has('end')) {
                $query->whereDate('sakuma_datums', '>=', $request->start)
                      ->whereDate('beigu_datums', '<=', $request->end);
            }
            if ($request->has('komandas_id') && $request->komandas_id != '') {
                $query->where('komandas_id', $request->komandas_id);
            }
            $events = $query->get(['id', 'apraksts', 'laiks', 'vieta', 'komandas_id', 'sakuma_datums', 'beigu_datums']);
            $formattedEvents = $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->vieta,
                    'start' => $event->sakuma_datums,
                    'end' => $event->beigu_datums ?? $event->sakuma_datums,
                    'allDay' => true, // or false if you handle times
                    'extendedProps' => [
                        'laiks' => $event->laiks,
                        'apraksts' => $event->apraksts,
                        'vieta' => $event->vieta,
                        'komandas_id' => $event->komandas_id,
                    ],
                ];
            });

            return response()->json($formattedEvents);
        }
        $komandas = \App\Models\Komanda::all();
        $players = User::all();
  
        return view('calendar', compact('komandas', 'players'));
    }
 
    public function ajax(Request $request)
    {
        // Validate incoming request data based on type
        switch ($request->type) {
            case 'add':
            case 'update':
                // Common validation for add and update
                $validatedData = $request->validate([
                    'apraksts' => 'nullable|string|max:500',
                    'laiks' => 'required|date_format:H:i',
                    'vieta' => 'required|string|max:255',
                    'komandas_id' => 'required|exists:komandas,id',
                    'sakuma_datums' => 'required|date',
                    'beigu_datums' => 'nullable|date',
                ]);
                break;

            // No validation needed for delete (just ID)
            case 'delete':
                $validatedData = $request->validate([
                    'id' => 'required|exists:trenini,id',
                ]);
                break;

            default:
                return response()->json(['error' => 'Invalid request type'], 400);
        }

        switch ($request->type) {
            case 'add':
                $startDate = Carbon::parse($request->sakuma_datums);
                $endDate = $startDate->copy()->addDay();

                $event = Kalendars::create([
                    'apraksts' => $request->apraksts,
                    'laiks' => $request->laiks,
                    'vieta' => $request->vieta,
                    'komandas_id' => $request->komandas_id,
                    'sakuma_datums' => $startDate,
                    'beigu_datums' => $endDate,
                ]);

                return response()->json($event);
                break;

            case 'update':
                $event = Kalendars::findOrFail($request->id);

                $startDate = Carbon::parse($request->sakuma_datums);
                $endDate = $startDate->copy()->addDay();

                $event->update([
                    'apraksts' => $request->apraksts,
                    'laiks' => $request->laiks,
                    'vieta' => $request->vieta,
                    'komandas_id' => $request->komandas_id,
                    'sakuma_datums' => $startDate,
                    'beigu_datums' => $endDate,
                ]);

                return response()->json($event);
                break;

            case 'delete':
                $event = Kalendars::find($request->id)->delete();
                return response()->json(['success' => true]);
                break;
            case 'markAttendance':
                $event = Kalendars::findOrFail($request->eventId);
                $teamId = $event->komandas_id;

                $presentIds = collect($request->attendanceList ?? [])
                ->filter(fn($r) => !empty($r['attended']))
                ->pluck('playerId')
                ->all();
                User::where('komandas_id', $teamId)
                ->when(!empty($presentIds), fn($q) => $q->whereNotIn('id', $presentIds))
                ->increment('neapmekletie_trenini');
                return response()->json(['success' => true]);
            default:
                return response()->json(['error' => 'Invalid request'], 400);
        }
    }
    public function savePlayers(Request $request, $teamslug)
{
    if (Gate::denies('is-coach')) {
        abort(403);
    }

    $event = Kalendars::findOrFail($request->input('event_id'));
    $teamId = $event->komandas_id;

    $presentIds = $request->input('attendance', []); // checked = attended

    $affected = User::where('komandas_id', $teamId)
        ->when(!empty($presentIds), fn($q) => $q->whereNotIn('id', $presentIds))
        ->increment('neapmekletie_trenini');

    return redirect($request->input('redirect_url'))
        ->with('success', 'Attendance updated successfully.');
}
}