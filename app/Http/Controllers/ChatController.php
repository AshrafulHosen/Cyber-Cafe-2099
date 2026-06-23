<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Message; // Uncomment in Phase 4 when DB is ready

class ChatController extends Controller
{
    // AI Barista replies pool
    private array $baristaReplies = [
        'The neon city never sleeps 🌃',
        'Focus mode activated ⚡',
        'Coffee recommendation: Cyber Espresso ☕',
        'Lo-fi rain playlist updated 🎵',
        'Stay hydrated, traveler 💧',
        'The code compiles when you believe in yourself 🚀',
        'Synthwave activated. Deep focus in 3... 2... 1... 🎶',
    ];

    public function index()
    {
        // Static seed messages for now — replace with DB in Phase 4:
        // $messages = Message::with('user')->latest()->take(30)->get()->reverse();
        $messages = collect([]);  // empty collection, blade uses @forelse fallback

        return view('chat', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        // In Phase 4, save to DB:
        // Message::create([
        //     'user_id' => auth()->id(),
        //     'content' => $request->content,
        //     'room'    => 'global',
        // ]);

        // Return a random AI Barista reply
        $aiReply = $this->baristaReplies[array_rand($this->baristaReplies)];

        return response()->json([
            'success'  => true,
            'ai_reply' => $aiReply,
        ]);
    }
}