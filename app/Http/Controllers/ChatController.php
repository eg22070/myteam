<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\ChatMessages;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index(User $user)
    {
        $messages = ChatMessages::with(['sender', 'receiver'])
            ->whereIn('sender_id', [Auth::id(), $user->id])
            ->whereIn('receiver_id', [Auth::id(), $user->id])
            ->get();
        return response()->json($messages);
    }

    public function store(User $user, Request $request)
    {
        //$request->validate([
        //    'sanemeja_id' => 'required|exists:users,id',
        //    'zinas_saturs' => 'required|string|max:1000',
        //]);

        $message = ChatMessages::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'text' => $request->message,
        ]);
        broadcast(new MessageSent($user, $message))->toOthers();
        return response()->json($message);
    }
}