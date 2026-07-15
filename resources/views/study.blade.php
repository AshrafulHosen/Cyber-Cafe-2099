@extends('layouts.app')

@section('title', 'Study Rooms — Cyber Café 2099')

@section('content')

<!-- LIVE TABLES -->
<div class="full-section">
<section style="padding-top: 150px;">
  <div class="section-label">// active_sessions</div>
  <h2 class="section-title">Live Study Tables</h2>
  <p class="section-sub">Join an active table or create your own focus space. Table lights reflect the energy inside.</p>
  <div class="tables-preview">
    
    @foreach ($tables as $index => $table)
      @php
        $activity = strtolower($table['activity']);
        
        // Map activities to specific distinct styles
        $theme = match($activity) {
            'studying' => ['hex' => 'var(--cyan)', 'icon' => '📚', 'bg' => 'linear-gradient(135deg, rgba(0, 255, 255, 0.1) 0%, rgba(0,0,0,0.8) 100%)'],
            'chatting' => ['hex' => 'var(--purple)', 'icon' => '💬', 'bg' => 'linear-gradient(135deg, rgba(138, 43, 226, 0.1) 0%, rgba(0,0,0,0.8) 100%)'],
            'coding' => ['hex' => '#00ffcc', 'icon' => '💻', 'bg' => 'linear-gradient(135deg, rgba(0, 255, 204, 0.1) 0%, rgba(0,0,0,0.8) 100%)'],
            'gaming' => ['hex' => '#ff3366', 'icon' => '🎮', 'bg' => 'linear-gradient(135deg, rgba(255, 51, 102, 0.1) 0%, rgba(0,0,0,0.8) 100%)'],
            'inactive' => ['hex' => 'var(--pink)', 'icon' => '💤', 'bg' => 'linear-gradient(135deg, rgba(255, 0, 128, 0.05) 0%, rgba(0,0,0,0.9) 100%)'],
            default => ['hex' => '#1a6aff', 'icon' => '🌐', 'bg' => 'rgba(0,0,0,0.8)']
        };
        
        $hex = $theme['hex'];
      @endphp
      <div class="table-card" style="background: {{ $theme['bg'] }}; border: 1px solid {{ $hex }}; box-shadow: 0 4px 15px rgba(0,0,0,0.5); position: relative;">
        @if($table['password'])
            <div style="position: absolute; top: 10px; right: 10px; font-size: 1.2rem;" title="Password Protected">🔒</div>
        @endif
        <div class="tc-glow" style="background:{{ $hex }}"></div>
        <div class="tc-status"><div class="tc-dot" style="background:{{ $hex }};box-shadow:0 0 6px {{ $hex }}"></div><span style="color:{{ $hex }}">{{ strtoupper($table['activity']) }}</span></div>
        <div class="tc-name" style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 1.2rem;">{{ $theme['icon'] }}</span> 
            {{ $table['name'] }}
        </div>
        <div class="tc-users">{{ $table['user_count'] }} users · {{ ucfirst($table['activity']) }}</div>
        <div class="tc-bar"><div class="tc-bar-fill" style="width:{{ rand(40, 90) }}%;background:{{ $hex }}"></div></div>
        
        @auth
          <a href="{{ route('study.show', ['id' => $table['id']]) }}" class="btn btn-cyan" style="margin-top: 15px; width: 100%; text-align: center; justify-content: center;">Join Table</a>
        @else
          <a href="{{ route('login') }}" class="btn btn-purple" style="margin-top: 15px; width: 100%; text-align: center; justify-content: center;">Login to Join</a>
        @endauth
      </div>
    @endforeach

    @auth
    <!-- CREATE TABLE MODAL TOGGLE -->
    <div class="table-card blue" style="border-style:dashed;opacity:0.5;cursor:pointer;" onclick="document.getElementById('create-room-modal').style.display='flex'">
      <div style="height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;min-height:100px">
        <div style="font-size:1.5rem;color:var(--cyan)">+</div>
        <div style="font-family:var(--font-mono);font-size:0.65rem;color:var(--text-dim);letter-spacing:0.1em">CREATE TABLE</div>
      </div>
    </div>
    @endauth

  </div>
</section>
</div>

<!-- CREATE ROOM MODAL -->
<div id="create-room-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center; backdrop-filter:blur(5px);">
    <div style="background:var(--bg2); border:1px solid var(--cyan); padding:30px; border-radius:8px; width:100%; max-width:400px; box-shadow: 0 0 30px rgba(0,255,255,0.2);">
        <h3 style="color:var(--cyan); font-family:var(--font-mono); margin-bottom:20px; border-bottom:1px dashed rgba(255,255,255,0.1); padding-bottom:10px;">// INITIALIZE NEW NODE</h3>
        
        <form action="{{ route('study.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="display:block; color:var(--text-dim); font-family:var(--font-mono); font-size:0.8rem; margin-bottom:5px;">ROOM NAME (MAX 50)</label>
                <input type="text" name="name" required style="width:100%; padding:10px; background:rgba(0,0,0,0.5); border:1px solid var(--purple); color:white;" placeholder="e.g. Late Night Coding">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label style="display:block; color:var(--text-dim); font-family:var(--font-mono); font-size:0.8rem; margin-bottom:5px;">ACTIVITY</label>
                <select name="activity" required style="width:100%; padding:10px; background:rgba(0,0,0,0.5); border:1px solid var(--purple); color:white;">
                    <option value="studying">Studying</option>
                    <option value="coding">Coding</option>
                    <option value="gaming">Gaming</option>
                    <option value="chatting">Chatting</option>
                </select>
            </div>
            
            <div style="margin-bottom: 25px;">
                <label style="display:block; color:var(--text-dim); font-family:var(--font-mono); font-size:0.8rem; margin-bottom:5px;">PASSCODE (OPTIONAL)</label>
                <input type="password" name="password" style="width:100%; padding:10px; background:rgba(0,0,0,0.5); border:1px solid var(--purple); color:white;" placeholder="Leave blank for public access">
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-solid" onclick="document.getElementById('create-room-modal').style.display='none'">CANCEL</button>
                <button type="submit" class="btn btn-cyan">CREATE</button>
            </div>
        </form>
    </div>
</div>

@endsection