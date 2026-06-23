<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // These will later come from the database.
        // For now we pass static data so the blade loops work.
        $features = [
            [
                'title'       => 'Real-Time Chat',
                'description' => 'Connect with people around the world in holographic-style live chat rooms.',
            ],
            [
                'title'       => 'AI Barista',
                'description' => 'Ask the futuristic café assistant for study motivation, playlists, or coffee recommendations.',
            ],
            [
                'title'       => 'Virtual Study Tables',
                'description' => 'Join digital study tables with focus modes, timers, and collaborative vibes.',
            ],
            [
                'title'       => 'Lo-fi Atmosphere',
                'description' => 'Immerse yourself in neon rain, synthwave music, and cinematic cyberpunk ambience.',
            ],
        ];

        return view('home', compact('features'));
    }
}