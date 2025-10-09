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
        $players = $teams->speletajs()->with('user')->get();
        $availablePlayers = User::where('role', 'Player')
                            ->whereHas('speletajs', function ($query) {
                                $query->whereNull('komanda_id');
                            })->with('speletajs')->get();

        $comments = $teams->vizualieMateriali()->get();
        $games = $teams->speles()->with(['varti.vartuGuvejs', 'varti.assist'])->get();

        return view('players', compact('coaches', 'teams', 'players', 'comments', 'games', 'availablePlayers'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($teamslug)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        $teams = Komanda::where('vecums','=', $teamslug)->first();
        return view('players_new', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $teamslug)
    {
        $team = Komanda::where('vecums', $teamslug)->firstOrFail();
        $playerIds = $request->input('player_ids', []);

        if (!$playerIds) {
            return redirect()->back()->with('error', 'No players selected.');
        }

        // Update each player's komanda_id to assign to the team
        \DB::table('speletajs')->whereIn('id', $playerIds)->update(['komanda_id' => $team->id]);

        return redirect()->back()->with('success', 'Players added successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $player = Speletajs::findOrFail($id);
        return view('playerProfile', ['player' => $player]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        $player = Speletajs::findOrFail($id);
        return view('players_edit', compact('player'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'vards' => 'required|string|max:255',
            'uzvards' => 'required|string|max:255',
            'komanda_id' => 'required|exists:komandas,id',
            'nepamekletieTrenini' => 'nullable|integer',
            'speles' => 'required|integer',
            'varti' => 'required|integer',
            'piespeles' => 'required|integer',
            'bilde' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $photoPath = null;
    if ($request->hasFile('bilde')) {
        $photoPath = $request->file('bilde')->store('photos', 'public'); // Store in storage/app/public/photos directory
    }
        $player= Speletajs::findOrFail($id);
        $player->vards= $request->input('vards');
        $player ->uzvards= $request->input('uzvards');
        $player -> komanda_id= $request->input('komanda_id');
        $player ->nepamekletieTrenini= $request->input('nepamekletieTrenini');
        $player->speles= $request->input('speles');
        $player ->varti= $request->input('varti');
        $player ->piespeles= $request->input('piespeles');
        $player->bilde=$photoPath;
        $player->save();

        return redirect(action([PlayersController::class, 'show'], ['id' => $player->id]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($teamslug, string $id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        Speletajs::findOrfail($id)->delete();
        return redirect()->route('players', ['teamslug' => $teamslug])->with('success', 'Player deleted successfully.');     
    }
}
