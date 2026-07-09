<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache the features list for 1 hour
        $features = Cache::remember('home_features', 3600, function () {
            return [
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
        });

        return view('home', compact('features'));
    }
}