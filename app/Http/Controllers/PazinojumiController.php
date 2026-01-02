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
        $pazinojumi = Pazinojums::with('user')->orderBy('datums', 'desc')->get();
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
         $validatedData = $request->validate([
            'virsraksts' => 'required|string|max:255',
            'pazinojums' => 'required|string|max:500',
            'datums' => 'required|date', // ensure date format
        ]);
        $pazinojums= new Pazinojums();
        $pazinojums->virsraksts = $validatedData['virsraksts'];
        $pazinojums->owner_id = auth()->id();
        $pazinojums->pazinojums = $validatedData['pazinojums'];
        $pazinojums->datums = $validatedData['datums'];
        $pazinojums->save();

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
        $validatedData = $request->validate([
            'virsraksts' => 'required|string|max:255',
            'pazinojums' => 'required|string|max:500',
            'datums' => 'required|date', // ensure date format
        ]);
        $pazinojums= Pazinojums::findOrFail($id);
        $pazinojums->virsraksts = $validatedData['virsraksts'];
        $pazinojums->owner_id = auth()->id();
        $pazinojums->pazinojums = $validatedData['pazinojums'];
        $pazinojums->datums = $validatedData['datums'];
        $pazinojums->save();
        $action = action([PazinojumiController::class, 'index']);
        return redirect()->route('notifications')->with('success', 'Notification  updated successfully.');
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
        return redirect('notifications/')->with('success', 'Notification deleted successfully.');
    }
}
