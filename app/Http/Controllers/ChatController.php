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

        $aiReply = null;
        if (\Illuminate\Support\Str::contains(strtolower($request->content), ['@barista', '@nexus7'])) {
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
                                    ['text' => "You are Nexus-7, a snarky cyberpunk barista AI in a futuristic cafe called Cyber Cafe 2099. A user said: " . $request->content . " Respond briefly (max 2 sentences) in character."]
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
                                'room'    => 'global',
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