<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->latest()->take(30)->get()->reverse();

        return view('cafe', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Message::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'room'    => 'global',
        ]);

        return response()->json([
            'success'  => true,
        ]);
    }
}