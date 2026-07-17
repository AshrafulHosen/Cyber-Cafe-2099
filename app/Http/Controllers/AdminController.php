<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_tables' => \App\Models\StudyTable::count(),
            'active_sessions' => \App\Models\StudySession::where('active', true)->count(),
        ];

        $users = \App\Models\User::orderBy('created_at', 'desc')->get();
        $tables = \App\Models\StudyTable::with('owner')->get();

        return view('admin', compact('stats', 'users', 'tables'));
    }

    public function toggleBan($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Prevent self-banning
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot ban yourself.');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'BANNED' : 'UNBANNED';
        return back()->with('success', "User {$user->name} has been {$status}.");
    }

    public function storeTable(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'activity' => 'required|string',
            'color' => 'required|string',
        ]);

        \App\Models\StudyTable::create([
            'name' => $request->name,
            'color' => $request->color,
            'activity' => $request->activity,
            'user_id' => null, // Global table
            'password' => null,
            'room_code' => null,
        ]);

        return back()->with('success', 'Global table created.');
    }

    public function updateTable(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'activity' => 'required|string',
            'color' => 'required|string',
        ]);

        $table = \App\Models\StudyTable::findOrFail($id);
        $table->update([
            'name' => $request->name,
            'color' => $request->color,
            'activity' => $request->activity,
        ]);

        return back()->with('success', 'Table updated successfully.');
    }

    public function deleteTable($id)
    {
        $table = \App\Models\StudyTable::findOrFail($id);
        
        // Handle active sessions (disconnect users)
        $sessions = \App\Models\StudySession::where('study_table_id', $id)->where('active', true)->get();
        foreach($sessions as $session) {
            $session->update(['active' => false, 'started_at' => null]);
        }
        
        $table->delete();
        return back()->with('success', 'Table deleted.');
    }
}
