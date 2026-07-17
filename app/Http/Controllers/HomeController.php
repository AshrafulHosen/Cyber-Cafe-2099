<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $usersOnline = \App\Models\User::where('last_seen_at', '>=', now()->subMinutes(5))->count();

        if (auth()->check() && !auth()->user()->is_admin) {
            $user = auth()->user();
            
            $sessionsToday = \App\Models\StudySession::where('user_id', $user->id)
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->count();
                
            $spentCredits = \Illuminate\Support\Facades\DB::table('user_inventories')
                ->join('inventory_items', 'user_inventories.inventory_item_id', '=', 'inventory_items.id')
                ->where('user_inventories.user_id', $user->id)
                ->sum('inventory_items.price');
                
            $totalCredits = $user->cyber_credits + $spentCredits;
            $hoursOfFocus = floor(($totalCredits / 10) / 60);
            
            $stats = [
                'users_online' => $usersOnline,
                'sessions_today' => $sessionsToday,
                'hours_focus' => $hoursOfFocus,
                'credits_earned' => $totalCredits
            ];
        } else {
            $currentCredits = \App\Models\User::sum('cyber_credits');
            $spentCredits = \Illuminate\Support\Facades\DB::table('user_inventories')
                ->join('inventory_items', 'user_inventories.inventory_item_id', '=', 'inventory_items.id')
                ->sum('inventory_items.price');
            $totalCredits = $currentCredits + $spentCredits;
            $hoursOfFocus = floor(($totalCredits / 10) / 60);

            $stats = [
                'users_online' => $usersOnline,
                'sessions_today' => \App\Models\StudySession::whereDate('created_at', \Carbon\Carbon::today())->count(),
                'hours_focus' => $hoursOfFocus,
                'credits_earned' => $totalCredits
            ];
        }

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

        $globalTables = \App\Models\StudyTable::whereNull('user_id')->take(3)->get();

        return view('home', compact('features', 'stats', 'globalTables'));
    }
}