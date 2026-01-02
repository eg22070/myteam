<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komanda;
use App\Models\VizualaisMaterials;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'komentars' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'], // 'image' is the input name for the file
            'komandas_id' => ['required', 'exists:komandas,id'], // Ensure the team exists
        ]);

        $imagePath = null;
        // 2. Handle image upload if a file is present
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('materials', 'public'); // Store in storage/app/public/materials
        }

        // 3. Create the new VizualaisMaterials record
        VizualaisMaterials::create([
            'coach_id' => auth()->id(), // Assign the authenticated user's ID
            'komandas_id' => $validatedData['komandas_id'],
            'komentars' => $validatedData['komentars'],
            'bilde' => $imagePath, // Save the path to the 'bilde' column
        ]);

        // Redirect back to the players page for the given team with a success message
        return redirect()->route('players', ['teamslug' => $teamslug])->with('success', 'Tactical material added successfully.');
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
    public function update(Request $request, string $teamslug, string $id) // $teamslug from route parameter
    {
        $material = VizualaisMaterials::findOrFail($id);

        // Ensure authorization (only coach/owner can update)
        if (Gate::denies('is-coach-or-owner')) {
            abort(403);
        }

        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'komentars' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'], // 'image' is the input name for the file
        ]);

        // 2. Update the text fields
        $material->komentars = $validatedData['komentars'];

        // 3. Handle image update if a new file is uploaded
        if ($request->hasFile('image')) {
            // Delete the old image file if it exists
            if ($material->bilde && Storage::disk('public')->exists($material->bilde)) {
                Storage::disk('public')->delete($material->bilde);
            }
            // Store the new image file
            $material->bilde = $request->file('image')->store('materials', 'public');
        }
        // If no new image is uploaded, the existing 'bilde' path remains unchanged.
        // If you wanted to allow deleting the image without replacing it, you'd need an explicit checkbox/option.

        // 4. Save the updated material
        $material->save();

        // Redirect back to the players page for the given team with a success message
        return redirect()->route('players', ['teamslug' => $teamslug])->with('success', 'Tactical material updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($teamslug, string $id)
    {
        // Ensure authorization (only coach/owner can delete)
        if (Gate::denies('is-coach')) {
            abort(403);
        }

        $material = VizualaisMaterials::findOrFail($id);

        // 1. Delete the associated image file from storage if it exists
        if ($material->bilde && Storage::disk('public')->exists($material->bilde)) {
            Storage::disk('public')->delete($material->bilde);
        }

        // 2. Delete the database record
        $material->delete();

        // Redirect back to the players page for the given team with a success message
        return redirect()->route('players', ['teamslug' => $teamslug])->with('success', 'Tactical material deleted successfully.');
    }
}
