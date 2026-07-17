<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\StudyTable;
use App\Models\TableFile;

class StudyRoomController extends Controller
{
    public function index()
    {
        // Persistent table session logic
        if (session()->has('active_table_id')) {
            return redirect()->route('study.show', session('active_table_id'));
        }

        $tables = StudyTable::select('id', 'name', 'color', 'activity', 'password', 'room_code')
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
            'room_code' => strtoupper(Str::random(4))
        ]);

        return redirect()->route('study.show', $table->id);
    }

    public function show($id)
    {
        $table = StudyTable::findOrFail($id);

        
        // Store the active table in session so they stay at this table until explicitly leaving
        session(['active_table_id' => $id]);
        
        if (auth()->check()) {
            $session = \App\Models\StudySession::firstOrNew(['user_id' => auth()->id()]);
            if (!$session->active || $session->study_table_id != $id) {
                $session->study_table_id = $id;
                $session->active = true;
                $session->started_at = now();
                $session->save();
            }
        }

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
        if (auth()->check()) {
            $session = \App\Models\StudySession::where('user_id', auth()->id())->first();
            if ($session && $session->active) {
                $creditsEarned = 0;
                if ($session->started_at) {
                    $minutes = (int) abs(now()->diffInMinutes($session->started_at));
                    $creditsEarned = $minutes * 10;
                    if ($creditsEarned > 0) {
                        $user = auth()->user();
                        $user->cyber_credits += $creditsEarned;
                        $user->save();
                    }
                }
                $session->update(['active' => false, 'started_at' => null]);
                
                if ($creditsEarned > 0) {
                    return redirect()->route('study.index')->with('success', 'Session ended. You earned ' . $creditsEarned . ' CC!');
                } else {
                    return redirect()->route('study.index')->with('error', 'Session ended. You left before earning any credits! Stay at least 1 minute.');
                }
            }
        }
        return redirect()->route('study.index');
    }

    public function destroy($id)
    {
        $table = StudyTable::findOrFail($id);
        if ($table->user_id === auth()->id()) {
            session()->forget('active_table_id');
            
            // Process credits for all active users before table is deleted
            $sessions = \App\Models\StudySession::where('study_table_id', $id)->where('active', true)->with('user')->get();
            $ownerEarned = 0;
            foreach($sessions as $session) {
                if ($session->started_at) {
                    $minutes = (int) abs(now()->diffInMinutes($session->started_at));
                    $creditsEarned = $minutes * 10;
                    if ($creditsEarned > 0 && $session->user) {
                        $session->user->cyber_credits += $creditsEarned;
                        $session->user->save();
                        
                        if ($session->user_id === auth()->id()) {
                            $ownerEarned = $creditsEarned;
                        }
                    }
                }
            }
            
            $table->delete();
            
            if ($ownerEarned > 0) {
                return redirect()->route('study.index')->with('success', 'Table disbanded. You earned ' . $ownerEarned . ' CC!');
            }
        }
        return redirect()->route('study.index');
    }

    public function search(Request $request)
    {
        $request->validate(['room_code' => 'required|string']);
        
        $table = StudyTable::where('room_code', strtoupper($request->room_code))->first();
        if ($table) {
            return redirect()->route('study.show', $table->id);
        }
        
        return back()->with('error', 'TABLE NOT FOUND.');
    }

    public function getMessages($id)
    {
        $room = 'table_' . $id;
        $messages = \App\Models\Message::with('user')->where('room', $room)->latest()->take(30)->get()->reverse()->values();
        return response()->json($messages);
    }

    public function getUsers($id)
    {
        $users = \App\Models\StudySession::with('user:id,name,theme_color')
                    ->where('study_table_id', $id)
                    ->where('active', true)
                    ->get()
                    ->pluck('user')
                    ->filter();
        return response()->json($users);
    }

    public function getFiles($id)
    {
        $files = TableFile::with('user:id,name')->where('study_table_id', $id)->latest()->get();
        return response()->json($files);
    }

    public function uploadFile(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // max 10MB
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();
        
        $path = $file->storeAs('table_files/' . $id, time() . '_' . Str::slug($fileName) . '.' . $file->getClientOriginalExtension(), 'public');

        $tableFile = TableFile::create([
            'study_table_id' => $id,
            'user_id' => auth()->id(),
            'file_name' => $fileName,
            'file_path' => '/storage/' . $path,
            'file_size' => $fileSize
        ]);

        $tableFile->load('user:id,name');
        return response()->json($tableFile);
    }
}