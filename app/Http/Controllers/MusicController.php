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
            'q' => $query, // Removed append for better search results
            'type' => 'video',
            'videoEmbeddable' => 'true', 
            'maxResults' => 15,
            'key' => $apiKey
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        }

        $errorDetail = $response->json();
        $message = $errorDetail['error']['message'] ?? 'Unknown YouTube API Error. Make sure your API key is valid and the YouTube Data API v3 service is enabled in Google Cloud Console.';

        return response()->json(['error' => 'API Error: ' . $message], 400);
    }

    public function getMixtapes(Request $request)
    {
        return response()->json($request->user()->mixtapes);
    }

    public function saveMixtape(Request $request)
    {
        $request->validate([
            'video_id' => 'required|string',
            'title' => 'required|string',
            'channel_title' => 'nullable|string',
            'thumbnail_url' => 'nullable|string',
        ]);

        // Prevent duplicate saves for the same video_id
        $exists = $request->user()->mixtapes()->where('video_id', $request->video_id)->exists();
        if ($exists) {
            return response()->json(['message' => 'Already saved to mixtape'], 200);
        }

        $mixtape = $request->user()->mixtapes()->create($request->only('video_id', 'title', 'channel_title', 'thumbnail_url'));

        return response()->json(['message' => 'Saved to mixtape', 'mixtape' => $mixtape]);
    }

    public function deleteMixtape(Request $request, $id)
    {
        $mixtape = $request->user()->mixtapes()->findOrFail($id);
        $mixtape->delete();

        return response()->json(['message' => 'Removed from mixtape']);
    }
}
