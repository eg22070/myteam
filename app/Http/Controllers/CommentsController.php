<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use App\Models\VizualaisMaterials;
use Illuminate\Support\Facades\Gate;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
    
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
        $comments = new VizualaisMaterials();
        $comments->coach_id=auth()->id();
        $comments->komandas_id=$request->input('komandas_id');
        $comments->komentars=$request->input('komentars');
        $comments->save();

        $team = Komanda::findOrFail($request->komandas_id);
        return redirect()->route('players', ['teamslug' => $teamslug]);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($teamslug, string $id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        VizualaisMaterials::findOrfail($id)->delete();
        return redirect()->route('players', ['teamslug' => $teamslug]);
    }
}
