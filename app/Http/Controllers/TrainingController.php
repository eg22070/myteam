<?php

namespace App\Http\Controllers;

use App\Models\Trenins;
use App\Models\Komanda;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        $teams = Komanda::all();
        return view('calendar', compact('teams'));
    }

    public function fetchEvents()
    {
        return Trenins::all();
    }

    public function storeEvent(Request $request)
    {
        $training = new Trenins();
        $training->apraksts = $request->apraksts;
        $training->laiks = $request->laiks;
        $training->vieta = $request->vieta;
        $training->komandas_id = $request->komandas_id; // Set team_id
        $training->save();

        return response()->json($training);
    }

    public function deleteEvent(Request $request, $id)
    {
        Trenins::destroy($id);
        return response()->json(['success' => true]);
    }
}
