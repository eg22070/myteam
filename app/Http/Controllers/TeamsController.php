<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use Illuminate\Support\Facades\Gate;
class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Komanda::all();
 return view('teams', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        if (Gate::denies('is-owner')){
            abort(403);
        }
        return view('test_team');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $team= new Komanda();
        $team->vecums = $request->input('vecums');
        $team->nakosaisTrenins = $request->input('nakosaisTrenins');
        $team->coach_id = $request->input('coach_id');
        $team->save();
        $action = action([TeamsController::class, 'index']);
        return redirect($action);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        $teams = Komanda::findOrFail($id);
        return view('teams_edit', compact('teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $team= Komanda::findOrFail($id);
        $team->vecums = $request->input('vecums');
        $team->nakosaisTrenins = $request->input('nakosaisTrenins');
        $team->coach_id = $request->input('coach_id');
        $team->save();
        $action = action([TeamsController::class, 'index']);
        return redirect($action);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Gate::denies('is-owner')){
            abort(403);
        }
        Komanda::findOrfail($id)->delete();
        return redirect('teams/');
    }
}
