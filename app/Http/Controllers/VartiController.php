<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Varti;
use App\Models\Speles;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class VartiController extends Controller
{
    public function index($id)
    {
        $team = Komanda::findOrFail($id);
        $games = $team
            ->Speles()
            ->with(['varti.VartuGuvejs', 'varti.assist'])
            ->get();

        return view('players', compact('games'));
    }
    public function store(Request $request, $teamslug)
    {
        $validated = $request->validate([
            'event_type' => 'required|in:goal,yellow,red',
            'speles_id' => 'required|exists:speles,id',
            'player_id' => 'required|exists:users,id',
            'assist_id' => 'nullable|exists:users,id',
            'minute' => 'required|integer|min:1|max:90',
        ]);

        if ($validated['event_type'] === 'goal') {
            $data['speles_id'] = $validated['speles_id'];
            $data['minute'] = $validated['minute'];
            $data['vartuGuveja_id'] = $validated['player_id'];
            $data['assist_id'] = $validated['assist_id'] ?? null;
            // Set goal record
            \App\Models\Varti::create($data);
            // Increment goal count for goal scorer
            \DB::table('users')
                ->where('id', $validated['player_id'])
                ->increment('varti');

            // Increment assist count if assist exists
            if ($validated['assist_id']) {
                \DB::table('users')
                    ->where('id', $validated['assist_id'])
                    ->increment('piespeles');
            }
        } elseif ($validated['event_type'] === 'yellow') {
            $data['speles_id'] = $validated['speles_id'];
            $data['minute'] = $validated['minute'];
            $data['dzeltena_id'] = $validated['player_id'];
            \App\Models\Varti::create($data);
            \DB::table('users')
                ->where('id', $validated['player_id'])
                ->increment('dzeltenas');
        } elseif ($validated['event_type'] === 'red') {
            $data['speles_id'] = $validated['speles_id'];
            $data['minute'] = $validated['minute'];
            $data['sarkana_id'] = $validated['player_id'];
            \App\Models\Varti::create($data);
            \DB::table('users')
                ->where('id', $validated['player_id'])
                ->increment('sarkanas');
        }

        return redirect()
            ->route('players', ['teamslug' => $teamslug])
            ->with('success', 'Event added and stats updated successfully.');

        //return redirect()->route('players', ['teamslug' => $teamslug])->with('success', 'Goal added successfully.');
    }
    public function destroy($teamslug, string $id)
    {
        if (Gate::denies('is-coach')) {
            abort(403);
        }
        $event = Varti::findOrFail($id);

        // Goal + assist
        if ($event->vartuGuveja_id) {
            User::where('id', $event->vartuGuveja_id)->decrement('varti');

            if ($event->assist_id) {
                User::where('id', $event->assist_id)->decrement('piespeles');
            }
        }

        // Yellow card
        if ($event->dzeltena_id) {
            User::where('id', $event->dzeltena_id)->decrement('dzeltenas');
        }

        // Red card
        if ($event->sarkana_id) {
            User::where('id', $event->sarkana_id)->decrement('sarkanas');
        }

        $event->delete();
        return redirect()->route('players', ['teamslug' => $teamslug])->with('success', 'Event deleted succesfully.');
    }
}
