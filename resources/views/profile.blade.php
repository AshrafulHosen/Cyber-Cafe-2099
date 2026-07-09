@extends('layouts.app')

@section('title', 'Profile — Cyber Café 2099')

@section('content')
<div class="full-section">
<section style="padding-top: 150px; max-width: 800px; margin: 0 auto;">
  <div class="section-label">// user_profile.sys</div>
  <h2 class="section-title">Digital <span style="color:var(--purple)">Identity</span></h2>
  <p class="section-sub">Manage your neural link settings and digital footprint.</p>

  <div style="margin-top: 50px;">
    
    @auth
      <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
          
          <!-- LEFT COL: IDENTITY -->
          <div>
              <div style="padding: 40px; border: 1px solid var(--purple); background: rgba(138, 43, 226, 0.05); border-radius: 8px; text-align: center;">
                  <div style="width: 120px; height: 120px; background: var(--dark-accent); border: 2px solid var(--purple); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: var(--purple); margin: 0 auto 20px; box-shadow: 0 0 20px rgba(138,43,226,0.3);">
                      {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                  </div>
                  <h3 style="color: var(--white); font-size: 1.5rem; margin: 0;">{{ Auth::user()->name }}</h3>
                  <div style="color: var(--cyan); font-family: var(--font-mono); font-size: 0.85rem; margin-top: 5px;">{{ Auth::user()->email }}</div>
                  
                  <div style="margin-top: 30px; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 20px;">
                      <div style="color: var(--text-dim); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 5px;">Neural Link Status</div>
                      <div style="color: var(--cyan); font-family: var(--font-mono); display: flex; justify-content: center; align-items: center; gap: 8px;">
                          <div style="width: 8px; height: 8px; background: var(--cyan); border-radius: 50%; box-shadow: 0 0 8px var(--cyan);"></div>
                          STABLE CONNECTION
                      </div>
                  </div>
                  
                  <a href="{{ route('profile.edit') }}" class="btn btn-purple" style="margin-top: 30px; width: 100%; justify-content: center;">System Settings</a>
              </div>
          </div>
          
          <!-- RIGHT COL: STATS & ECONOMY -->
          <div style="display: flex; flex-direction: column; gap: 30px;">
              
              <!-- STATS GRID -->
              <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px;">
                  <div style="padding: 20px; border: 1px solid rgba(0,255,255,0.2); background: rgba(0,255,255,0.02); border-radius: 8px;">
                      <div style="font-family: var(--font-mono); color: var(--cyan); font-size: 0.8rem; text-transform: uppercase;">Cyber Credits</div>
                      <div style="font-size: 2rem; color: var(--white); margin-top: 10px;">{{ Auth::user()->cyber_credits ?? 0 }} <span style="font-size:1rem; color:var(--text-dim)">¥</span></div>
                  </div>
                  <div style="padding: 20px; border: 1px solid rgba(255,0,255,0.2); background: rgba(255,0,255,0.02); border-radius: 8px;">
                      <div style="font-family: var(--font-mono); color: var(--pink); font-size: 0.8rem; text-transform: uppercase;">Focus Hours</div>
                      <div style="font-size: 2rem; color: var(--white); margin-top: 10px;">{{ Auth::user()->focus_hours ?? 0 }}<span style="font-size:1rem; color:var(--text-dim)">h</span></div>
                  </div>
                  <div style="padding: 20px; border: 1px solid rgba(138,43,226,0.2); background: rgba(138,43,226,0.02); border-radius: 8px;">
                      <div style="font-family: var(--font-mono); color: var(--purple); font-size: 0.8rem; text-transform: uppercase;">Sessions</div>
                      <div style="font-size: 2rem; color: var(--white); margin-top: 10px;">{{ Auth::user()->sessions_count ?? 0 }}</div>
                  </div>
              </div>
              
              <!-- INVENTORY -->
              <div style="padding: 30px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.4); border-radius: 8px;">
                  <h4 style="color: var(--white); margin-bottom: 20px; font-family: var(--font-mono); border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 10px;">// Digital Inventory</h4>
                  
                  <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                      @forelse($user->inventoryItems as $item)
                          <div style="display: flex; gap: 15px; align-items: center; padding: 15px; background: rgba(255,255,255,0.02); border-radius: 4px; border-left: 3px solid {{ $item->pivot->status === 'EQUIPPED' ? 'var(--cyan)' : 'var(--purple)' }};">
                              <div style="font-size: 2rem;">{{ $item->icon }}</div>
                              <div>
                                  <div style="color: var(--white); font-size: 0.9rem;">{{ $item->name }}</div>
                                  <div style="color: var(--text-dim); font-size: 0.75rem; font-family: var(--font-mono);">{{ $item->pivot->status }}</div>
                              </div>
                          </div>
                      @empty
                          <div style="color: var(--text-dim); font-style: italic;">No items in inventory.</div>
                      @endforelse
                  </div>
              </div>
              
              <!-- RECENT ACTIVITY -->
              <div style="padding: 30px; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.4); border-radius: 8px;">
                  <h4 style="color: var(--white); margin-bottom: 20px; font-family: var(--font-mono); border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 10px;">// Recent Activity</h4>
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
                          <li style="color: var(--text-dim); font-style: italic;">No recent activity found.</li>
                      @endforelse
                  </ul>
                  <div style="margin-top: 20px;">
                      {{ $logs->links() }}
                  </div>
              </div>
          </div>
      </div>
    @else
      <div style="padding: 60px; text-align: center; border: 1px dashed var(--pink); background: rgba(255,0,255,0.02); border-radius: 8px;">
        <h3 style="color:var(--pink); font-family: var(--font-mono); text-transform: uppercase;">Unidentified User</h3>
        <p style="color:var(--text-dim); margin-top: 15px;">You must establish a neural link to access profile data.</p>
        
        <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center;">
            <a href="{{ route('login') }}" class="btn btn-cyan">Login</a>
            <a href="{{ route('register') }}" class="btn btn-purple">Register</a>
        </div>
      </div>
    @endauth

  </div>
</section>
</div>
@endsection
