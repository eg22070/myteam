<?php

namespace App\Http\Controllers;

use App\Events\ZinasSutit;
use App\Http\Controllers\Controller;
use App\Models\Zinas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(User $user)
    {
        $zinas = Zinas::with(['sutitajs', 'sanemejs'])
            ->whereIn('sutitaja_id', [Auth::id(), $user->id])
            ->whereIn('sanemeja_id', [Auth::id(), $user->id])
            ->get();
        return response()->json($zinas);
    }

    public function store(User $user, Request $request)
    {
        $request->validate([
            'sanemeja_id' => 'required|exists:users,id',
            'zinas_saturs' => 'required|string|max:1000',
        ]);

        $zinas = new Zinas();
        $zinas->sutitaja_id=auth()->id();
        $zinas->sanemeja_id=$request->input('sanemeja_id');
        $zinas->zinas_saturs=$request->input('zinas_saturs');

        broadcast(new ZinasSutit($user, $zinas))->toOthers();
        return response()->json($zinas);
    }
}