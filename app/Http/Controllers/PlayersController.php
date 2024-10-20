<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use App\Models\Speletajs;
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
 $player = $teams->speletajs()->get();

 return view('players', ['teams' => $teams, 'player' =>
$player]);

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
    public function store(Request $request)
    {
        $player = new Speletajs();
        $player->vards= $request->input('vards');
        $player ->uzvards= $request->input('uzvards');
        $player -> komanda_id= $request->input('komanda_id');
        $player ->nepamekletieTrenini= $request->input('nepamekletieTrenini');
        $player->speles= $request->input('speles');
        $player ->varti= $request->input('varti');
        $player ->piespeles= $request->input('piespeles');
        $player->save();

        $teams = Komanda::findOrFail($request->komanda_id);
 $action = action([PlayersController::class, 'index'], ['teamslug' =>
$teams->vecums]);
 return redirect($action);
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
        $player = Speletajs::findOrFail($id);
        $player->vards= $request->input('vards');
        $player ->uzvards= $request->input('uzvards');
        $player ->nepamekletieTrenini= $request->input('nepamekletieTrenini');
        $player->speles= $request->input('speles');
        $player ->varti= $request->input('varti');
        $player ->piespeles= $request->input('piespeles');
        $player->save();

        return redirect(action([PlayersController::class, 'show'], ['id' => $player->id]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        Speletajs::findOrfail($id)->delete();
        return redirect('{teamslug}/players/');       
    }
}
