<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use App\Models\Speles;
use App\Models\Varti;
use App\Models\Speletajs;
use Illuminate\Support\Facades\Gate;

class SpelesController extends Controller
{
    public function index($id)
    {
        $team = Komanda::findOrFail($id);
        $games = $team->Speles()->get();

        return view('players', compact('games'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $teamslug)
    {
        $speles = new Speles();
        $speles->komanda_id = $request->input('komanda_id');
        $speles->pretinieks = $request->input('pretinieks');
        $speles->rezultats = $request->input('rezultats');
        $speles->save();

        $team = Komanda::findOrFail($request->komanda_id);
        return redirect()->route('players', ['teamslug' => $teamslug]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $teamslug, string $id)
    {
        $speles = Speles::findOrFail($id);
        $speles->komanda_id = $request->input('komanda_id');
        $speles->pretinieks = $request->input('pretinieks');
        $speles->rezultats = $request->input('rezultats');
        $speles->save();
        return redirect()
            ->route('players', ['teamslug' => $teamslug])
            ->with('success', 'Game has been updated successfully.');
    }

    public function updatePlayers(Request $request, $id, $teamslug)
    {
        if (Gate::denies('is-coach-or-owner')) {
            abort(403);
        }

        $game = Speles::findOrFail($id);
        $selectedPlayers = $request->input('players', []);

        // Increment the 'speles' count for selected players
        Speletajs::whereIn('id', $selectedPlayers)->increment('speles');

        return redirect($request->input('redirect_url'))->with(
            'success',
            'Player participation updated successfully.'
        );
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($teamslug, string $id)
    {
        if (Gate::denies('is-coach-or-owner')) {
            abort(403);
        }
        $game = Speles::findOrfail($id);

        $events = Varti::where('speles_id', $id)->get();
        foreach ($events as $event) {
            // Decrement goal count and assist count
            if ($event->vartuGuveja_id) {
                Speletajs::where('id', $event->vartuGuveja_id)->decrement(
                    'varti'
                );
                if ($event->assist_id) {
                    Speletajs::where('id', $event->assist_id)->decrement(
                        'piespeles'
                    );
                }
            }

            // Decrement yellow card count
            if ($event->dzeltena_id) {
                Speletajs::where('id', $event->dzeltena_id)->decrement(
                    'dzeltenas'
                );
            }

            // Decrement red card count
            if ($event->sarkana_id) {
                Speletajs::where('id', $event->sarkana_id)->decrement(
                    'sarkanas'
                );
            }

            // Delete the event
            $event->delete();
        }
        $game->delete();
        return redirect()
            ->route('players', ['teamslug' => $teamslug])
            ->with(
                'success',
                'Game and associated events have been deleted successfully.'
            );
    }
}
