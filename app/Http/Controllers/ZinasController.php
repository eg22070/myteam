<?php

namespace App\Http\Controllers;

use App\Events\ZinasBroadcast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ZinasController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $zinas = Zinas::where('sutitaja_id', $userId)->orWhere('sanemeja_id', $userId)->get();
        $users = User::where('id', '!=', $userId)->get();
        return view('message', compact('zinas', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sanemeja_id' => 'required|exists:users,id',
            'zinas_saturs' => 'required|string|max:1000',
        ]);

        $zina = new Zinas();
        $zina->sutitaja_id=auth()->id();
        $zina->sanemeja_id=$request->input('sanemeja_id');
        $zina->zinas_saturs=$request->input('zinas_saturs');

        return back();
    }

    public function show(User $users)
    {
        $userId = auth()->id();
        $zinas = Zinas::where(function($query) use ($userId, $user) {
            $query->where('sutitaja_id', $userId)->where('sanemeja_id', $user->id);
        })->orWhere(function($query) use ($userId, $user) {
            $query->where('sutitaja_id', $user->id)->where('sanemeja_id', $userId);
        })->get();

        return view('message', compact('zinas', 'users'));
    }
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);  
        $message->delete();
        return back()->with('success', 'Message deleted.');
    }
}
