<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Varti;
use App\Models\Speles;
use App\Models\Speletajs;
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
            'player_id' => 'required|exists:speletajs,id',
            'assist_id' => 'nullable|exists:speletajs,id',
            'minute' => 'required|integer',
        ]);

        if ($validated['event_type'] === 'goal') {
            $data['speles_id'] = $validated['speles_id'];
            $data['minute'] = $validated['minute'];
            $data['vartuGuveja_id'] = $validated['player_id'];
            $data['assist_id'] = $validated['assist_id'] ?? null;
            // Set goal record
            \App\Models\Varti::create($data);
            // Increment goal count for goal scorer
            \DB::table('speletajs')
                ->where('id', $validated['player_id'])
                ->increment('varti');

            // Increment assist count if assist exists
            if ($validated['assist_id']) {
                \DB::table('speletajs')
                    ->where('id', $validated['assist_id'])
                    ->increment('piespeles');
            }
        } elseif ($validated['event_type'] === 'yellow') {
            $data['speles_id'] = $validated['speles_id'];
            $data['minute'] = $validated['minute'];
            $data['dzeltena_id'] = $validated['player_id'];
            \App\Models\Varti::create($data);
            \DB::table('speletajs')
                ->where('id', $validated['player_id'])
                ->increment('dzeltenas');
        } elseif ($validated['event_type'] === 'red') {
            $data['speles_id'] = $validated['speles_id'];
            $data['minute'] = $validated['minute'];
            $data['sarkana_id'] = $validated['player_id'];
            \App\Models\Varti::create($data);
            \DB::table('speletajs')
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
        if (Gate::denies('is-coach-or-owner')) {
            abort(403);
        }
        Varti::findOrfail($id)->delete();
        return redirect()->route('players', ['teamslug' => $teamslug]);
    }
}
