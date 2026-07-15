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

        $tables = StudyTable::select('id', 'name', 'color', 'activity', 'password')
            ->whereNull('user_id')
            ->orWhere('user_id', auth()->id())
            ->withCount('activeSessions as user_count')
            ->get();

        return view('study', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'activity' => 'required|string',
            'password' => 'nullable|string'
        ]);

        $colors = ['blue', 'purple', 'red'];
        $color = $colors[array_rand($colors)];

        $table = StudyTable::create([
            'name' => $request->name,
            'color' => $color,
            'activity' => $request->activity,
            'user_id' => auth()->id(),
            'password' => $request->password ?: null,
        ]);

        return redirect()->route('study.show', $table->id);
    }

    public function show($id)
    {
        $table = StudyTable::findOrFail($id);
        
        // If table is locked and user is not owner, check session auth
        if ($table->password && $table->user_id !== auth()->id()) {
            if (!session('table_password_auth_'.$id)) {
                return view('study-password', compact('table'));
            }
        }
        
        // Store the active table in session so they stay at this table until explicitly leaving
        session(['active_table_id' => $id]);

        return view('study-room', compact('table'));
    }

    public function processPassword(Request $request, $id)
    {
        $table = StudyTable::findOrFail($id);
        if ($request->password === $table->password) {
            session(['table_password_auth_'.$id => true]);
            return redirect()->route('study.show', $id);
        }
        return back()->with('error', 'INCORRECT PASSCODE. ACCESS DENIED.');
    }

    public function leave()
    {
        session()->forget('active_table_id');
        return redirect()->route('study.index');
    }

    public function destroy($id)
    {
        $table = StudyTable::findOrFail($id);
        if ($table->user_id === auth()->id()) {
            $table->delete();
            session()->forget('active_table_id');
        }
        return redirect()->route('study.index');
    }
}