<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pazinojums;
use App\Http\Controllers\PazinojumiController;
use Illuminate\Support\Facades\Gate;

class PazinojumiController extends Controller
{
    public function index()
    {
        $pazinojumi = Pazinojums::all();
 return view('notifications', compact('pazinojumi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pazinojums= new Pazinojums();
        $pazinojums->virsraksts = $request->input('virsraksts');
        $pazinojums->owner_id = auth()->id();
        $pazinojums->pazinojums = $request->input('pazinojums');
        $pazinojums->datums = $request->input('datums');
        $pazinojums->save();
        $action = action([PazinojumiController::class, 'index']);
        return redirect()->route('notifications')->with('success', 'Notification added successfully.');
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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $team= Pazinojums::findOrFail($id);
        $pazinojums->virsraksts = $request->input('virsraksts');
        $pazinojums->coach_id = auth()->id()('owner_id');
        $pazinojums->pazinojums = $request->input('pazinojums');
        $pazinojums->datums = $request->input('datums');
        $pazinojums->save();
        $action = action([PazinojumiController::class, 'index']);
        return redirect()->route('notifications')->with('success', 'Notification has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Gate::denies('is-owner')){
            abort(403);
        }
        Pazinojums::findOrfail($id)->delete();
        return redirect('notifications/');
    }
}
