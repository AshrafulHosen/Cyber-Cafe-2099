@extends('layouts.app')

@section('title', 'Study Table — ' . $table['name'])

@push('styles')
<style>
    .study-room-layout {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 30px;
        margin-top: 50px;
    }
    
    .focus-mode-active .study-sidebar {
        display: none;
    }
    .focus-mode-active .study-room-layout {
        grid-template-columns: 1fr;
    }
    
    .timer-display {
        font-family: var(--font-mono);
        font-size: 5rem;
        color: var(--cyan);
        text-align: center;
        text-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
        margin: 40px 0;
    }
    
    .timer-controls {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .room-glow-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        pointer-events: none;
        z-index: 1000;
        mix-blend-mode: screen;
        opacity: 0.15;
        transition: background 1s ease;
    }

    .room-blue { background: radial-gradient(circle at center, transparent, #1a6aff 80%); }
    .room-purple { background: radial-gradient(circle at center, transparent, var(--purple) 80%); }
    .room-red { background: radial-gradient(circle at center, transparent, var(--pink) 80%); }

    .panel {
        background: rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 25px;
        border-radius: 8px;
    }
    
    .panel-title {
        font-family: var(--font-mono);
        font-size: 0.9rem;
        color: var(--text-dim);
        text-transform: uppercase;
        margin-bottom: 20px;
        letter-spacing: 0.1em;
        border-bottom: 1px dashed rgba(255,255,255,0.1);
        padding-bottom: 10px;
    }
</style>
@endpush

@section('content')

@php
    $glowClass = 'room-blue';
    if ($table['color'] === 'purple') $glowClass = 'room-purple';
    if ($table['color'] === 'red') $glowClass = 'room-red';
@endphp

<!-- Dynamic Glow based on table activity -->
<div class="room-glow-overlay {{ $glowClass }}" id="room-glow"></div>

<div class="full-section">
<section style="padding-top: 130px; max-width: 1200px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px;">
        <div>
            <div class="section-label" style="margin-bottom: 10px;">// node_{{ $table['id'] }}</div>
            <h2 class="section-title" style="margin-bottom: 0;">{{ $table['name'] }}</h2>
            <div style="margin-top: 10px; font-family: var(--font-mono); font-size: 0.8rem;">
                Status: <span style="color: var(--cyan);">{{ strtoupper($table['activity']) }}</span> 
                | Users: {{ rand(3, 12) }} Connected
            </div>
        </div>
        
        <div>
            <button id="focusToggle" class="btn btn-purple">◈ Enable Focus Mode</button>
            <a href="{{ route('study.leave') }}" class="btn btn-solid" style="margin-left: 10px;">Leave Table</a>
        </div>
    </div>

    <div class="study-room-layout" id="main-layout">
        
        <!-- MAIN AREA: Timer & Tools -->
        <div class="study-main">
            <div class="panel" style="text-align: center; border-top: 3px solid var(--cyan);">
                <div class="panel-title">Pomodoro System</div>
                
                <div class="timer-display" id="timer">25:00</div>
                
                <div class="timer-controls">
                    <button id="startTimer" class="btn btn-cyan">Start Session</button>
                    <button id="pauseTimer" class="btn btn-solid">Pause</button>
                    <button id="resetTimer" class="btn btn-purple">Reset</button>
                </div>
                
                <div style="display: flex; gap: 20px; justify-content: center; margin-top: 40px; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 30px;">
                    <button class="btn btn-solid" onclick="alert('Share Screen UI will be mounted here in Phase 2')">
                        <span style="color:var(--cyan)">▣</span> Share Screen
                    </button>
                    <button class="btn btn-solid" onclick="alert('Collab Notes UI will be mounted here in Phase 2')">
                        <span style="color:var(--pink)">📝</span> Share Notes
                    </button>
                </div>
            </div>
            
            <div class="panel" style="margin-top: 30px;">
                <div class="panel-title">Shared Notes Feed</div>
                <div style="min-height: 150px; color: var(--text-dim); font-style: italic; display: flex; align-items: center; justify-content: center;">
                    No notes shared yet. Click "Share Notes" to begin syncing...
                </div>
            </div>
        </div>

        <!-- SIDEBAR: Users & Chat -->
        <div class="study-sidebar" id="sidebar">
            <div class="panel" style="margin-bottom: 30px;">
                <div class="panel-title">Neural Links (Connected)</div>
                <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:12px;">
                    <li style="display:flex; align-items:center; gap:10px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--cyan);box-shadow:0 0 5px var(--cyan);"></div>
                        <span style="font-family:var(--font-mono); font-size:0.9rem;">{{ Auth::check() ? Auth::user()->name : 'Guest' }} (You)</span>
                    </li>
                    <li style="display:flex; align-items:center; gap:10px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--cyan);box-shadow:0 0 5px var(--cyan);"></div>
                        <span style="font-family:var(--font-mono); font-size:0.9rem;">CyberNinja99</span>
                    </li>
                    <li style="display:flex; align-items:center; gap:10px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--purple);box-shadow:0 0 5px var(--purple);"></div>
                        <span style="font-family:var(--font-mono); font-size:0.9rem;">NeonGhost</span>
                    </li>
                    <li style="display:flex; align-items:center; gap:10px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--pink);box-shadow:0 0 5px var(--pink);"></div>
                        <span style="font-family:var(--font-mono); font-size:0.9rem; color:var(--text-dim)">AFK_Glitch</span>
                    </li>
                </ul>
            </div>
            
            <div class="panel">
                <div class="panel-title">Table Comms</div>
                <div style="height: 250px; background: rgba(0,0,0,0.4); border: 1px solid rgba(0,255,255,0.1); border-radius: 4px; padding: 15px; margin-bottom: 15px; display: flex; flex-direction: column; justify-content: flex-end;">
                    <div style="color:var(--text-dim); font-size:0.8rem; margin-bottom:10px;">[System] You joined the table.</div>
                    <div style="font-size:0.85rem; margin-bottom:8px;"><strong style="color:var(--purple)">NeonGhost:</strong> let's focus for 25 mins</div>
                </div>
                <input type="text" placeholder="Transmit message..." style="width:100%; padding:10px; background:transparent; border:1px solid rgba(255,255,255,0.2); color:white; font-family:var(--font-mono);" disabled>
            </div>
        </div>
        
    </div>
</section>
</div>

@push('scripts')
<script>
(function() {
    // Sync with Global Pomodoro Timer
    function updateDisplay() {
        const display = document.getElementById('timer');
        if (!display) return;
        let m = Math.floor(window.globalSecondsLeft / 60);
        let s = window.globalSecondsLeft % 60;
        display.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }
    
    // Initial sync on page load
    updateDisplay();
    
    // Listen for ticks broadcasted from the global timer
    document.addEventListener('GlobalTimerTick', updateDisplay);
    
    // Sync buttons to global functions
    document.getElementById('startTimer').addEventListener('click', () => {
        if (window.startGlobalTimer) window.startGlobalTimer();
    });
    
    document.getElementById('pauseTimer').addEventListener('click', () => {
        if (window.pauseGlobalTimer) window.pauseGlobalTimer();
    });
    
    document.getElementById('resetTimer').addEventListener('click', () => {
        if (window.resetGlobalTimer) window.resetGlobalTimer();
    });

    // Cleanup listeners if Swup navigates away (to prevent duplicate listeners on return)
    window.swup.hooks.once('page:view', () => {
        document.removeEventListener('GlobalTimerTick', updateDisplay);
    });

    // Focus Mode Toggle
    let focusMode = false;
    document.getElementById('focusToggle').addEventListener('click', (e) => {
        focusMode = !focusMode;
        if (focusMode) {
            document.body.classList.add('focus-mode-active');
            e.target.textContent = '◈ Disable Focus Mode';
            e.target.classList.replace('btn-purple', 'btn-cyan');
            document.getElementById('room-glow').style.opacity = '0.05'; // dim lights further
        } else {
            document.body.classList.remove('focus-mode-active');
            e.target.textContent = '◈ Enable Focus Mode';
            e.target.classList.replace('btn-cyan', 'btn-purple');
            document.getElementById('room-glow').style.opacity = '0.15';
        }
    });
})();
</script>
@endpush
@endsection
