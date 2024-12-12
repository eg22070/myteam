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
    public function index($teamslug)
    {
        
        $team = Komanda::where('vecums', '=', $teamslug)
               ->where('id', '=', $id)
               ->first();
 $comments = $team->vizualieMateriali()->get();

 return view('comments', ['team' => $team, 'comments' =>
$comments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($teamslug)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        $team = Komanda::where('vecums','=', $teamslug)->first();
        return view('comment_new', compact('team'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $comments = new VizualaisMaterials();
        $comments->coach_id=auth()->id();
        $comments->komandas_id=$request->input('komandas_id');
        $comments->komentars=$request->input('komentars');
        $comments->save();

        $team = Komanda::findOrFail($request->komandas_id);
 $action = action([CommentsController::class, 'index'], ['id' =>
$team->id]);
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
    public function destroy(string $id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        VizualaisMaterials::findOrfail($id)->delete();
        return redirect('{teamslug}/players/comments');
    }
}
