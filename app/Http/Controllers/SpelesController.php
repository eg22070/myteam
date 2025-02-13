<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use App\Models\Speles;
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
        $speles = new Speles();
        $speles->komanda_id=$request->input('komanda_id');
        $speles->pretinieks=$request->input('pretinieks');
        $speles->rezultats=$request->input('rezultats');
        $speles->save();

        $team = Komanda::findOrFail($request->komanda_id);
        return redirect()->route('players', ['teamslug' => $teamslug]);
    }

    /**
     * Display the specified resource.
     */
    public function show($teamslug, string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $teamslug, string $id)
    {
        $speles = Speles::findOrFail($id);
        $speles->komanda_id=$request->input('komanda_id');
        $speles->pretinieks=$request->input('pretinieks');
        $speles->rezultats=$request->input('rezultats');
        $speles->save();
        return redirect()->route('players', ['teamslug' => $teamslug])
                     ->with('success', 'Game has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($teamslug, string $id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        Speles::findOrfail($id)->delete();
        return redirect()->route('players', ['teamslug' => $teamslug]);
    }
}
