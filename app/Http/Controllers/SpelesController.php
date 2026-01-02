<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use App\Models\Speles;
use App\Models\Varti;
use App\Models\User;
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
        $request->validate([
            'komanda_id' => 'required|exists:komandas,id',
            'pretinieks' => 'required|string|max:255',
            'rezultats'  => 'nullable|string|max:255',
            'datums'     => 'required|date',
            'laiks'      => 'required|date_format:H:i',
            'vieta'      => 'required|string|max:255',
        ]);
        $speles = new Speles();
        $speles->komanda_id = $request->komanda_id;
        $speles->pretinieks = $request->pretinieks;
        $speles->rezultats  = $request->rezultats;
        $speles->datums     = $request->datums;
        $speles->laiks      = $request->laiks;
        $speles->vieta      = $request->vieta;
        $speles->save();

        $team = Komanda::findOrFail($request->komanda_id);
        return redirect()->route('players', ['teamslug' => $teamslug])->with('success', 'Game added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $teamslug, string $id)
    {
        $request->validate([
            'komanda_id' => 'required|exists:komandas,id',
            'pretinieks' => 'required|string|max:255',
            'rezultats'  => 'nullable|string|max:255',
            'datums'     => 'required|date',
            'laiks'      => 'required|date_format:H:i',
            'vieta'      => 'required|string|max:255',
        ]);
        $speles = Speles::findOrFail($id);
        $speles->komanda_id = $request->komanda_id;
        $speles->pretinieks = $request->pretinieks;
        $speles->rezultats  = $request->rezultats;
        $speles->datums     = $request->datums;
        $speles->laiks      = $request->laiks;
        $speles->vieta      = $request->vieta;
        $speles->save();

        return redirect()
            ->route('players', ['teamslug' => $teamslug])
            ->with('success', 'Game updated successfully.');
    }

    public function updatePlayers(Request $request, $id, $teamslug)
    {
        if (Gate::denies('is-coach')) {
            abort(403);
        }

        $game = Speles::findOrFail($id);
        $selectedPlayers = $request->input('players', []);
        if (empty($selectedPlayers)) {
            return redirect($request->input('redirect_url'))
            ->with('error', 'No players have been selected.');
        }
        // Increment the 'speles' count for selected players
        User::whereIn('id', $selectedPlayers)->increment('speles');

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
        if (Gate::denies('is-coach')) {
            abort(403);
        }
        $game = Speles::findOrfail($id);

        $events = Varti::where('speles_id', $id)->get();
        foreach ($events as $event) {
            // Decrement goal count and assist count
            if ($event->vartuGuveja_id) {
                User::where('id', $event->vartuGuveja_id)->decrement(
                    'varti'
                );
                if ($event->assist_id) {
                    User::where('id', $event->assist_id)->decrement(
                        'piespeles'
                    );
                }
            }

            // Decrement yellow card count
            if ($event->dzeltena_id) {
                User::where('id', $event->dzeltena_id)->decrement(
                    'dzeltenas'
                );
            }

            // Decrement red card count
            if ($event->sarkana_id) {
                User::where('id', $event->sarkana_id)->decrement(
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
