<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\StudyTable; // Uncomment in Phase 4 when DB is ready

class StudyRoomController extends Controller
{
    public function index()
    {
        // Static data for now — replace with DB query in Phase 4:
        // $tables = StudyTable::withCount('activeSessions')->get();
        $tables = [
            [
                'name'       => 'Blue Focus Table',
                'color'      => 'blue',
                'user_count' => 12,
                'activity'   => 'studying',
            ],
            [
                'name'       => 'Purple Chill Table',
                'color'      => 'purple',
                'user_count' => 8,
                'activity'   => 'chatting',
            ],
            [
                'name'       => 'AFK Neon Lounge',
                'color'      => 'red',
                'user_count' => 5,
                'activity'   => 'inactive',
            ],
        ];

        return view('study', compact('tables'));
    }

    public function show($id)
    {
        // Mock data for a specific table
        $table = [
            'id' => $id,
            'name' => 'Cyber Focus ' . $id,
            'color' => 'blue', // Defaulting to studying (blue)
            'activity' => 'studying',
        ];

        return view('study-room', compact('table'));
    }
}