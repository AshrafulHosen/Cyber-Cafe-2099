<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->where('room', 'global')->latest()->take(30)->get()->reverse();

        return view('cafe', compact('messages'));
    }

    public function barista()
    {
        $room = 'barista_' . auth()->id();
        $messages = Message::with('user')->where('room', $room)->oldest()->get();

        return view('barista', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'room'    => 'nullable|string|max:50',
        ]);

        $room = $request->room ?: 'global';

        Message::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'room'    => $room,
        ]);

        $isBaristaRoom = \Illuminate\Support\Str::startsWith($room, 'barista_');
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'type'    => 'CHAT',
            'message' => $isBaristaRoom ? 'Chatted with Nexus-7.' : 'Sent a message in the global café.'
        ]);

        $aiReply = null;
        
        // Trigger AI if mentioned in global chat OR if we're in the private barista room
        $isMention = \Illuminate\Support\Str::contains(strtolower($request->content), ['@barista', '@nexus7']);
        $isBaristaRoom = \Illuminate\Support\Str::startsWith($room, 'barista_');
        
        if ($isMention || $isBaristaRoom) {
            $apiKey = config('services.gemini.key');
            if ($apiKey) {
                $barista = \App\Models\User::firstOrCreate(
                    ['email' => 'system@cybercafe2099.com'],
                    [
                        'name' => 'Nexus-7',
                        'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                        'theme_color' => 'pink',
                        'bio' => 'Automated Neural Network Assistant for Cyber Cafe 2099.'
                    ]
                );

                try {
                    $response = \Illuminate\Support\Facades\Http::withoutVerifying()->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=' . $apiKey, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => "You are Nexus-7, a helpful and intelligent AI assistant in Cyber Cafe 2099. A user said: " . $request->content . " Respond helpfully and clearly, but keep your response very brief (1-2 sentences maximum)."]
                                ]
                            ]
                        ]
                    ]);

                    if ($response->successful()) {
                        $reply = $response->json('candidates.0.content.parts.0.text');
                        if ($reply) {
                            $aiReply = Message::create([
                                'user_id' => $barista->id,
                                'content' => $reply,
                                'room'    => $room,
                            ]);
                            $aiReply->load('user');
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::error('Gemini API Error: ' . $response->body());
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Gemini Exception: ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'success'  => true,
            'ai_reply' => $aiReply
        ]);
    }
}