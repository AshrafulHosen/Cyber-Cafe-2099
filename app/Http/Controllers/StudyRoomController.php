<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyTable;

class StudyRoomController extends Controller
{
    public function index()
    {
        // Persistent table session logic
        if (session()->has('active_table_id')) {
            return redirect()->route('study.show', session('active_table_id'));
        }

        $tables = StudyTable::select('id', 'name', 'color', 'activity')
            ->withCount('activeSessions as user_count')
            ->get();

        return view('study', compact('tables'));
    }

    public function show($id)
    {
        $table = StudyTable::findOrFail($id);
        
        // Store the active table in session so they stay at this table until explicitly leaving
        session(['active_table_id' => $id]);

        return view('study-room', compact('table'));
    }

    public function leave()
    {
        session()->forget('active_table_id');
        return redirect()->route('study.index');
    }
}