<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Speletajs;
use App\Models\VizMat;
use Illuminate\Support\Facades\Gate;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $player = Speletajs::where('id','=', $id)->first();
 $comments = $player->vizMat()->get();

 return view('comments', ['player' => $player, 'comments' =>
$comments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        $player = Speletajs::where('id','=', $id)->first();
        return view('comment_new', compact('player'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $comments = new VizMat();
        $comments->coach_id=$request->input('coach_id');
        $comments->speletajs_id=$request->input('speletajs_id');
        $comments->komentars=$request->input('komentars');
        $comments->virsraksts=$request->input('virsraksts');
        $comments->datums=$request->input('datums');
        $comments->save();

        $player = Speletajs::findOrFail($request->speletajs_id);
 $action = action([CommentsController::class, 'index'], ['id' =>
$player->id]);
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
        VizMat::findOrfail($id)->delete();
        return redirect('players/{id}/comments/');
    }
}
