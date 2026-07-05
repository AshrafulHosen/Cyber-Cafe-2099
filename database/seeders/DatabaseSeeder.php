<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StudyTable;
use App\Models\InventoryItem;
use App\Models\Message;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Demo User
        $user = User::create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'cyber_credits' => 4200,
            'focus_hours' => 36,
            'sessions_count' => 12,
        ]);

        // 2. Create Study Tables
        StudyTable::insert([
            ['name' => 'Blue Focus Table', 'color' => 'blue', 'activity' => 'studying'],
            ['name' => 'Purple Chill Table', 'color' => 'purple', 'activity' => 'chatting'],
            ['name' => 'AFK Neon Lounge', 'color' => 'red', 'activity' => 'inactive'],
        ]);

        // 3. Create Inventory Items
        $item1 = InventoryItem::create(['name' => 'Synthwave Audio Pack', 'type' => 'audio', 'icon' => '🎧', 'price' => 500]);
        $item2 = InventoryItem::create(['name' => 'Cyber Cat Hologram', 'type' => 'hologram', 'icon' => '🐈', 'price' => 1200]);
        $item3 = InventoryItem::create(['name' => 'Rain Ambience Room', 'type' => 'room', 'icon' => '🌧️', 'price' => 2000]);

        // 4. Give user some inventory
        $user->inventoryItems()->attach([
            $item1->id => ['status' => 'EQUIPPED'],
            $item2->id => ['status' => 'IDLE'],
        ]);

        // 5. Activity Logs
        ActivityLog::insert([
            ['user_id' => $user->id, 'type' => 'STUDY', 'message' => 'Completed 25m Pomodoro at Node_04', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'type' => 'CHAT', 'message' => 'Joined Global Lounge', 'created_at' => now()->subDay(), 'updated_at' => now()->subDay()],
            ['user_id' => $user->id, 'type' => 'STORE', 'message' => 'Purchased Cyber Cat Hologram', 'created_at' => now()->subDays(3), 'updated_at' => now()->subDays(3)],
        ]);

        // 6. Global Chat Messages (Mock Barista msg)
        $aiUser = User::create([
            'name' => 'AI Barista',
            'email' => 'nexus7@cybercafe.net',
            'password' => Hash::make('secret'),
        ]);

        Message::create([
            'user_id' => $aiUser->id,
            'content' => 'The café is quiet... be the first to speak ☕',
            'room' => 'global'
        ]);
    }
}
