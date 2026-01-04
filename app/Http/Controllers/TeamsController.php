<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use App\Models\User;
use App\Http\Controllers\TeamsController;
use Illuminate\Support\Facades\Gate;
class TeamsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coaches = User::where('role', 'Coach')->get();
        $teams = Komanda::all();
 return view('teams', compact('coaches', 'teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vecums'    => 'required|string|max:255',
            'apraksts'  => 'nullable|string',
            'coach_id'  => 'nullable|exists:users,id',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB
        ]);

        $team = new Komanda();
        $team->vecums   = $validated['vecums'];
        $team->apraksts = $validated['apraksts'];
        $team->coach_id = $validated['coach_id'];

        if ($request->hasFile('image')) {
            $photoPath = $request->file('image')->store('photos', 'public');
            $team->bilde = $photoPath;
        }

        $team->save();

        return redirect()->route('teams')->with('success', 'Team added successfully.');
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
        $validated = $request->validate([
            'vecums'    => 'required|string|max:255',
            'apraksts'  => 'nullable|string',
            'coach_id'  => 'nullable|exists:users,id',
            'image'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // 2MB
        ]);
        $team= Komanda::findOrFail($id);
        $team->vecums = $validated['vecums'];
        $team->apraksts = $validated['apraksts'];
        $team->coach_id = $validated['coach_id'];
        if ($request->hasFile('image')) {
            $photoPath = $request->file('image')->store('photos', 'public');
            $team->bilde = $photoPath;
        }
        $team->save();
        $action = action([TeamsController::class, 'index']);
        return redirect($action)->with('success', 'Team updated successfully.');
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
        return redirect('teams/')->with('success', 'Team deleted successfully.');
    }
}
