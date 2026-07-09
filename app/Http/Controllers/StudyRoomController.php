<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyTable;

class StudyRoomController extends Controller
{
    public function index()
    {
        $tables = StudyTable::select('id', 'name', 'color', 'activity')
            ->withCount('activeSessions as user_count')
            ->get();

        return view('study', compact('tables'));
    }

    public function show($id)
    {
        $table = StudyTable::findOrFail($id);

        return view('study-room', compact('table'));
    }
}