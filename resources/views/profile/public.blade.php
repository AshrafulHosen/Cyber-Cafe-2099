@extends('layouts.app')

@section('title', $user->name . ' - Dossier — Cyber Café 2099')

@section('content')
<div class="full-section">
<section style="padding-top: 150px; max-width: 800px; margin: 0 auto;">
  <div class="section-label" style="color: var(--{{ $user->theme_color ?? 'cyan' }})">// public_dossier</div>
  <h2 class="section-title">User <span style="color:var(--{{ $user->theme_color ?? 'cyan' }})">Identity</span></h2>
  
  <div style="margin-top: 50px;">
      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
          
          <!-- LEFT COL: IDENTITY -->
          <div>
              <div style="padding: 40px; border: 1px solid var(--{{ $user->theme_color ?? 'cyan' }}); background: rgba(0, 0, 0, 0.4); border-radius: 8px; text-align: center; box-shadow: 0 0 20px rgba(0,0,0,0.5);">
                  <div style="width: 120px; height: 120px; background: var(--dark-accent); border: 2px solid var(--{{ $user->theme_color ?? 'cyan' }}); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--{{ $user->theme_color ?? 'cyan' }}); margin: 0 auto 20px; box-shadow: 0 0 20px var(--{{ $user->theme_color ?? 'cyan' }}); opacity: 0.8;">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                  </div>
                  <h3 style="color: var(--white); font-size: 1.5rem; margin: 0;">{{ $user->name }}</h3>
                  
                  @if($user->bio)
                  <div style="margin-top: 15px; padding: 10px; border-left: 2px solid var(--{{ $user->theme_color ?? 'cyan' }}); background: rgba(255,255,255,0.02); text-align: left; font-size: 0.9rem; font-style: italic; color: var(--text-dim);">
                      "{{ $user->bio }}"
                  </div>
                  @endif
                  
                  <div style="margin-top: 30px; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 20px;">
                      <div style="color: var(--text-dim); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 5px;">Status</div>
                      <div style="color: var(--{{ $user->theme_color ?? 'cyan' }}); font-family: var(--font-mono); display: flex; justify-content: center; align-items: center; gap: 8px;">
                          <div style="width: 8px; height: 8px; background: var(--{{ $user->theme_color ?? 'cyan' }}); border-radius: 50%; box-shadow: 0 0 8px var(--{{ $user->theme_color ?? 'cyan' }});"></div>
                          REGISTERED TRAVELER
                      </div>
                  </div>
                  
                  @auth
                      @if(Auth::id() !== $user->id)
                      <a href="#" class="btn btn-{{ $user->theme_color ?? 'cyan' }}" style="margin-top: 30px; width: 100%; justify-content: center;" onclick="alert('Direct messaging system is currently offline.')">Send Message</a>
                      @endif
                  @endauth
              </div>
          </div>
          
          <!-- RIGHT COL: STATS & ECONOMY -->
          <div style="display: flex; flex-direction: column; gap: 30px;">
              
              <!-- STATS GRID -->
              <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                  <div style="padding: 20px; border: 1px solid rgba(0,255,255,0.2); background: rgba(0,255,255,0.02); border-radius: 8px;">
                      <div style="font-family: var(--font-mono); color: var(--cyan); font-size: 0.8rem; text-transform: uppercase;">Credits</div>
                      <div style="font-size: 2rem; color: var(--white); margin-top: 10px;">{{ $user->cyber_credits ?? 0 }} <span style="font-size:1rem; color:var(--text-dim)">¥</span></div>
                  </div>
                  <div style="padding: 20px; border: 1px solid rgba(255,0,255,0.2); background: rgba(255,0,255,0.02); border-radius: 8px;">
                      <div style="font-family: var(--font-mono); color: var(--pink); font-size: 0.8rem; text-transform: uppercase;">Focus Hours</div>
                      <div style="font-size: 2rem; color: var(--white); margin-top: 10px;">{{ $user->focus_hours ?? 0 }}<span style="font-size:1rem; color:var(--text-dim)">h</span></div>
                  </div>
                  <div style="padding: 20px; border: 1px solid rgba(138,43,226,0.2); background: rgba(138,43,226,0.02); border-radius: 8px;">
                      <div style="font-family: var(--font-mono); color: var(--purple); font-size: 0.8rem; text-transform: uppercase;">Sessions</div>
                      <div style="font-size: 2rem; color: var(--white); margin-top: 10px;">{{ $user->sessions_count ?? 0 }}</div>
                  </div>
              </div>
              
              <!-- RECENT ACTIVITY -->
              <div style="padding: 30px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.4); border-radius: 8px;">
                  <h4 style="color: var(--white); margin-bottom: 20px; font-family: var(--font-mono); border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 10px;">// Recent Public Activity</h4>
                  <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px;">
                      @forelse($logs as $log)
                          <li style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                              <div>
                                  <span style="color: {{ $log->type === 'STUDY' ? 'var(--cyan)' : ($log->type === 'CHAT' ? 'var(--purple)' : 'var(--pink)') }};">
                                      [{{ $log->type }}]
                                  </span> 
                                  {{ $log->message }}
                              </div>
                              <div style="color: var(--text-dim); font-family: var(--font-mono);">
                                  {{ $log->created_at ? $log->created_at->diffForHumans() : 'Unknown time' }}
                              </div>
                          </li>
                      @empty
                          <li style="color: var(--text-dim); font-style: italic;">No recent public activity found for this user.</li>
                      @endforelse
                  </ul>
              </div>
          </div>
      </div>
  </div>
</section>
</div>
@endsection
