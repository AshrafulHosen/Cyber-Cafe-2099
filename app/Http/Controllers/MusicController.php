<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MusicController extends Controller
{
    public function searchMusic(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(['error' => 'No query provided'], 400);
        }

        $apiKey = config('services.youtube.key');
        
        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'q' => $query,
            'type' => 'video',
            'videoEmbeddable' => 'true', // Only return videos we can actually play!
            'videoCategoryId' => '10', // Music category
            'maxResults' => 8,
            'key' => $apiKey
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        return response()->json(['error' => 'Failed to fetch from YouTube API'], 500);
    }
}
