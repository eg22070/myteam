<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use App\Models\Speletajs;
use App\Models\VizualaisMaterials;
use App\Models\User;
use App\Http\Controllers\PlayersController;
use Illuminate\Support\Facades\Gate;
class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($teamslug)
    {
       
        $teams = Komanda::where('vecums','=', $teamslug)->first();
        $coaches = User::where('role', 'Coach')->get();
        $teamcoach = User::where('role', 'Coach')->where('id', $teams->coach_id)->first();
        $players = User::where('role', 'Player')
                       ->where('komandas_id', $teams->id) // Player must belong to this team
                       ->get();
        $availablePlayers = User::where('role', 'Player')->whereNull('komandas_id')->get();
        $comments = VizualaisMaterials::where('komandas_id', $teams->id)
                                    ->orderBy('created_at', 'desc') 
                                    ->get();
        $games = $teams->speles()->with(['varti.vartuGuvejs', 'varti.assist'])->get();

        return view('players', compact('coaches', 'teams', 'players', 'comments', 'games', 'availablePlayers', 'teamcoach'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($teamslug)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $teamslug)
    {
        $team = Komanda::where('vecums', $teamslug)->firstOrFail();
        $playerIds = $request->input('player_ids', []);

        if (!$playerIds) {
            return redirect()->back()->with('error', 'No players have been selected.');
        }

        // Update each player's komanda_id to assign to the team
        \DB::table('users')->whereIn('id', $playerIds)->update(['komandas_id' => $team->id]);

        return redirect()->back()->with('success', 'Players added successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $player = User::findOrFail($id);
        $teams = \App\Models\Komanda::all();
        $playersteam = Komanda::find($player->komandas_id);

        return view('playerProfile', ['player' => $player, 'teams' => $teams, 'playersteam' => $playersteam]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $player = User::findOrFail($id);
        $oldTeamId = $player->komandas_id;

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'surname'               => 'required|string|max:255',
            'komandas_id'           => 'required|exists:komandas,id',
            'dzimsanas_datums'      => 'required|date',
            'numurs'                => 'nullable|integer|min:0|max:99',
            'neapmekletie_trenini'  => 'required|integer|min:0',
            'speles'                => 'required|integer|min:0',
            'varti'                 => 'required|integer|min:0',
            'piespeles'             => 'required|integer|min:0',
            'dzeltenas'             => 'required|integer|min:0',
            'sarkanas'              => 'required|integer|min:0',
            'bilde'                 => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $player->name                 = $validated['name'];
        $player->surname              = $validated['surname'];
        $player->komandas_id          = $validated['komandas_id'];
        $player->dzimsanas_datums     = $validated['dzimsanas_datums'] ?? null;
        $player->numurs               = $validated['numurs'] ?? null;
        $player->neapmekletie_trenini = $validated['neapmekletie_trenini'] ?? 0;
        $player->speles               = $validated['speles'];
        $player->varti                = $validated['varti'];
        $player->piespeles            = $validated['piespeles'];
        $player->dzeltenas            = $validated['dzeltenas'] ?? 0;
        $player->sarkanas             = $validated['sarkanas'] ?? 0;

        if ($request->hasFile('bilde')) {
            $photoPath = $request->file('bilde')->store('photos', 'public');
            $player->bilde = $photoPath;
        }
        $player->save();
        if ($validated['komandas_id'] != $oldTeamId) {
            $newTeam = Komanda::find($validated['komandas_id']);
            if ($newTeam) {
                return redirect()
                    ->route('players', ['teamslug' => $newTeam->vecums])
                    ->with('success', 'Player updated successfully!');
            }
        }
        return redirect()
            ->route('players.show', ['id' => $player->id])
            ->with('success', 'Player updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($teamslug, string $id)
    {
        $user = User::findOrFail($id);
        $user->komandas_id = null;
        $user->save();
        return redirect()->route('players', ['teamslug' => $teamslug])->with('success', 'Player deleted successfully.');     
    }
}
