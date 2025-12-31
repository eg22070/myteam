<?php

namespace App\Http\Controllers;

use App\Models\Kalendars;
use App\Models\Komanda;
use App\Models\Speletajs;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
                    'title' => $event->apraksts,
                    'start' => $event->sakuma_datums,
                    'end' => $event->beigu_datums ?? $event->sakuma_datums,
                    'allDay' => true, // or false if you handle times
                    'extendedProps' => [
                        'laiks' => $event->laiks,
                        'vieta' => $event->vieta,
                        'komandas_id' => $event->komandas_id,
                    ],
                ];
            });

            return response()->json($formattedEvents);
        }
        $komandas = \App\Models\Komanda::all();
        $players = Speletajs::all();
  
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
                    'apraksts' => 'required|string|max:255',
                    'laiks' => 'nullable|string|max:50',
                    'vieta' => 'nullable|string|max:255',
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
                $eventId = $request->eventId;
                $attendanceList = $request->attendanceList;
               // Get the event model and team, from the event.
            $event = Kalendars::where('id', $request->eventId)->first();
            $teamId = $event->komandas_id; // Get the team model, based on ID
            $players = Speletajs::where('team_id', $teamId)->get(['id','name']); // Get the ID and Name of people

                // Loop through each player, to check if they attended, if not add neapmekletieTrenini 
            foreach ($players as $player) {
                    $attended = false;
                foreach($attendanceList as $attendaceDetails){
                // If a user is present in attendance list, then set variable to true.
                if($attendaceDetails["playerId"] == $player->id){
                    $attended = $attendaceDetails["attended"];
                  }
                }
                    if (!$attended) {
                        $target = Speletajs::where('id', $player->id)->first();
                       $target->neapmekletieTrenini++;
                         $target->save(); // Save it.
                    }
                }

                return response()->json(['success' => true]);
                break;
            default:
                return response()->json(['error' => 'Invalid request'], 400);
        }
    }
    public function savePlayers(Request $request, $teamslug)
    {
        if (Gate::denies('is-coach-or-owner')) {
            abort(403);
        }
        $selectedPlayers = $request->input('attendance', []);

        $affectedRows = DB::table('speletajs')->whereIn('id', $selectedPlayers)->increment('nepamekletieTrenini');
\Log::info('Affected rows: ' . $affectedRows);

        return redirect($request->input('redirect_url'))->with(
            'success',
            'Attendance updated successfully.'
        );
    }
}
