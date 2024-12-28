<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Varti;
use App\Models\Speles;
use Illuminate\Support\Facades\Gate;


class VartiController extends Controller
{
    public function index($id)
    {
        $team = Komanda::findOrFail($id);
        $games = $team->Speles()->with(['varti.VartuGuvejs', 'varti.assist'])->get();

    return view('players', compact('games'));
        
    }
    public function store(Request $request, $teamslug)
    {
        $request->validate([
            'speles_id' => 'required|exists:speles,id',
            'VartuGuveja_id' => 'required|integer|exists:speletajs,id',
            'assist_id' => 'nullable|integer|exists:speletajs,id',
            'minute' => 'required|integer',
        ]);
        
        $varti = new Varti();
        $varti->speles_id=$request->input('speles_id');
        $varti->vartuGuveja_id=$request->input('vartuGuveja_id');
        $varti->assist_id=$request->input('assist_id');
        $varti->minute=$request->input('minute');
        $varti->save();

        $spele = Speles::findOrFail($request->speles_id);
        return redirect()->route('players', ['teamslug' => $teamslug]);
    }
    public function destroy($teamslug, string $id)
    {
        if (Gate::denies('is-coach-or-owner')){
            abort(403);
        }
        Speles::findOrfail($id)->delete();
        return redirect()->route('players', ['teamslug' => $teamslug]);
    }
}
