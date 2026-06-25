@extends('layouts.app')

@section('content')
<!-- HERO -->
<section id="hero">
  <canvas id="city-canvas"></canvas>
  <canvas id="rain-canvas"></canvas>
  <div class="fog-layer"></div>
  <div class="particle-field" id="particle-field"></div>

  <!-- Neon signs -->
  <div class="neon-sign" style="top:22%;left:8%;color:var(--pink);transform:rotate(-3deg)">RAMEN BAR</div>
  <div class="neon-sign" style="top:30%;right:10%;color:var(--cyan);transform:rotate(2deg)">OPEN 24HRS</div>
  <div class="neon-sign" style="bottom:35%;left:5%;color:var(--purple)">CYBER LOUNGE</div>

  <div class="hero-content">
    <div class="hero-badge">⬡ TOKYO NODE — SECTOR 7 — EST. 2099</div>
    <h1 class="hero-title">
      <span class="line1">WELCOME TO</span>
      <span class="line2 glitch" data-text="CYBER CAFÉ 2099">CYBER CAFÉ 2099</span>
    </h1>
    <p class="hero-sub">Study · Chill · Exist in Neon</p>
    <div class="hero-btns">
      <a href="{{ url('/cafe') }}" class="btn btn-solid">⬡ Enter Café</a>
      <a href="{{ route('study.index') }}" class="btn btn-cyan">◈ Join Study Room</a>
      <a href="{{ url('/barista') }}" class="btn btn-purple">◆ Talk to AI Barista</a>
    </div>
  </div>
</section>

<!-- STATS BAR -->
<div class="full-section">
  <div class="stats-bar">
    <div class="stat-item">
      <div class="stat-glow" style="background:var(--cyan)"></div>
      <div class="stat-num" id="st1">0</div>
      <div class="stat-label">Users Online</div>
    </div>
    <div class="stat-item">
      <div class="stat-glow" style="background:var(--purple)"></div>
      <div class="stat-num p" id="st2">0</div>
      <div class="stat-label">Study Sessions Today</div>
    </div>
    <div class="stat-item">
      <div class="stat-glow" style="background:var(--pink)"></div>
      <div class="stat-num" id="st3">0</div>
      <div class="stat-label">Hours of Focus</div>
    </div>
    <div class="stat-item">
      <div class="stat-glow" style="background:var(--blue)"></div>
      <div class="stat-num p" id="st4">0</div>
      <div class="stat-label">Credits Earned</div>
    </div>
  </div>
</div>

<!-- FEATURES -->
<div class="full-section alt">
<section>
  <div class="section-label">// core_modules.exe</div>
  <h2 class="section-title">Your Digital Escape<br><span style="color:var(--cyan)">Engineered for 2099</span></h2>
  <p class="section-sub">Four interconnected systems that transform studying into an immersive neo-Tokyo experience.</p>
  <div class="features-grid">
    <a href="{{ route('study.index') }}" class="feature-card">
      <span class="fc-icon">◈</span>
      <div class="fc-title">Virtual Study Tables</div>
      <div class="fc-desc">Join global focus rooms with Pomodoro timers, shared notes, and ambient table lighting that shifts with your session state.</div>
      <div class="fc-tag c">Live Now</div>
    </a>
    <a href="{{ url('/barista') }}" class="feature-card purple">
      <span class="fc-icon">◆</span>
      <div class="fc-title">AI Barista</div>
      <div class="fc-desc">NEXUS-7 remembers your orders, recommends music for your mood, drops cyberpunk wisdom, and keeps you motivated.</div>
      <div class="fc-tag p">Neural AI</div>
    </a>
    <a href="{{ url('/cafe') }}" class="feature-card pink">
      <span class="fc-icon">◉</span>
      <div class="fc-title">Global Chat</div>
      <div class="fc-desc">Holographic messages drift across the café. Global lounge, table chat, private DMs — all with neon visual effects.</div>
      <div class="fc-tag pk">Real-time</div>
    </a>
    <a href="{{ url('/music') }}" class="feature-card">
      <span class="fc-icon">▷</span>
      <div class="fc-title">Music Lounge</div>
      <div class="fc-desc">Lo-fi, synthwave, rain ambience, cyberpunk drive — reactive audio waves that pulse with the beat of the city.</div>
      <div class="fc-tag c">5 Channels</div>
    </a>
    <a href="{{ url('/profile') }}" class="feature-card purple">
      <span class="fc-icon">⬡</span>
      <div class="fc-title">Digital Economy</div>
      <div class="fc-desc">Earn credits by studying. Unlock café themes, holographic avatars, cyber pets, and exclusive room effects.</div>
      <div class="fc-tag p">Credits System</div>
    </a>
    <a href="{{ url('/cafe') }}" class="feature-card pink">
      <span class="fc-icon">⬟</span>
      <div class="fc-title">Dynamic Weather</div>
      <div class="fc-desc">The café atmosphere mirrors real-world weather. Rainy nights intensify neon reflections and storm ambience.</div>
      <div class="fc-tag pk">Live API</div>
    </a>
  </div>
</section>
</div>

<!-- LIVE TABLES -->
<div class="full-section">
<section>
  <div class="section-label">// active_sessions</div>
  <h2 class="section-title">Live Study Tables</h2>
  <p class="section-sub">Join an active table or create your own focus space. Table lights reflect the energy inside.</p>
  <div class="tables-preview">
    <div class="table-card blue">
      <div class="tc-glow" style="background:#1a6aff"></div>
      <div class="tc-status"><div class="tc-dot" style="background:#1a6aff;box-shadow:0 0 6px #1a6aff"></div><span style="color:#1a6aff">STUDYING</span></div>
      <div class="tc-name">DEEP FOCUS — NODE 01</div>
      <div class="tc-users">8 / 12 users · Pomodoro 18:42</div>
      <div class="tc-bar"><div class="tc-bar-fill" style="width:67%;background:#1a6aff"></div></div>
    </div>
    <div class="table-card violet">
      <div class="tc-glow" style="background:var(--purple)"></div>
      <div class="tc-status"><div class="tc-dot" style="background:var(--purple);box-shadow:0 0 6px var(--purple)"></div><span style="color:var(--purple)">CHATTING</span></div>
      <div class="tc-name">CHILL ZONE — NODE 04</div>
      <div class="tc-users">5 / 8 users · Open session</div>
      <div class="tc-bar"><div class="tc-bar-fill" style="width:62%;background:var(--purple)"></div></div>
    </div>
    <div class="table-card green">
      <div class="tc-glow" style="background:#00ff88"></div>
      <div class="tc-status"><div class="tc-dot" style="background:#00ff88;box-shadow:0 0 6px #00ff88"></div><span style="color:#00ff88">ACTIVE</span></div>
      <div class="tc-name">CODE DOJO — NODE 07</div>
      <div class="tc-users">12 / 16 users · Sprint 2</div>
      <div class="tc-bar"><div class="tc-bar-fill" style="width:75%;background:#00ff88"></div></div>
    </div>
    <div class="table-card red">
      <div class="tc-glow" style="background:var(--pink)"></div>
      <div class="tc-status"><div class="tc-dot" style="background:var(--pink);box-shadow:0 0 6px var(--pink)"></div><span style="color:var(--pink)">AFK</span></div>
      <div class="tc-name">NIGHT OWL — NODE 11</div>
      <div class="tc-users">2 / 10 users · Break time</div>
      <div class="tc-bar"><div class="tc-bar-fill" style="width:20%;background:var(--pink)"></div></div>
    </div>
    <div class="table-card blue" style="border-style:dashed;opacity:0.5;cursor:pointer;" onclick="location.href='{{ route('study.index') }}'">
      <div style="height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;min-height:100px">
        <div style="font-size:1.5rem;color:var(--cyan)">+</div>
        <div style="font-family:var(--font-mono);font-size:0.65rem;color:var(--text-dim);letter-spacing:0.1em">CREATE TABLE</div>
      </div>
    </div>
  </div>
</section>
</div>

<!-- MUSIC VISUALIZER -->
<div class="full-section alt">
<section>
  <div class="section-label">// audio_engine.sys</div>
  <h2 class="section-title">Synthwave <span style="color:var(--purple)">Music Lounge</span></h2>
  <p class="section-sub">Reactive neon audio visualizer. Choose your frequency and let the city soundtrack your session.</p>
  <div class="visualizer-wrap">
    <canvas id="viz-canvas"></canvas>
    <div class="music-controls">
      <button class="mode-btn active" data-mode="lofi">Lo-Fi Beats</button>
      <button class="mode-btn" data-mode="synthwave">Synthwave</button>
      <button class="mode-btn" data-mode="rain">Rain Ambience</button>
      <button class="mode-btn" data-mode="cafe">Café Sounds</button>
      <button class="mode-btn" data-mode="drive">Night Drive</button>
    </div>
  </div>
</section>
</div>

<!-- AI BARISTA PREVIEW -->
<div class="full-section">
<section>
  <div class="section-label">// nexus-7.ai</div>
  <h2 class="section-title">Meet <span style="color:var(--cyan)">NEXUS-7</span></h2>
  <p class="section-sub">Your AI barista, motivation engine, and cyberpunk companion. Powered by neural networks, fueled by espresso.</p>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:60px;align-items:center;">
    <div>
      <div style="margin-bottom:24px">
        <div style="font-family:var(--font-mono);font-size:0.62rem;letter-spacing:0.15em;color:var(--text-dim);margin-bottom:8px">CAPABILITIES</div>
        <ul style="list-style:none;display:flex;flex-direction:column;gap:10px">
          <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem"><span style="color:var(--cyan);font-size:0.8rem">►</span> Music recommendations by mood</li>
          <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem"><span style="color:var(--purple);font-size:0.8rem">►</span> Personalized motivation phrases</li>
          <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem"><span style="color:var(--pink);font-size:0.8rem">►</span> Cyberpunk quote generator</li>
          <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem"><span style="color:var(--cyan);font-size:0.8rem">►</span> Coffee suggestions by mood</li>
          <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem"><span style="color:var(--purple);font-size:0.8rem">►</span> Study session planning</li>
          <li style="display:flex;align-items:center;gap:10px;font-size:0.88rem"><span style="color:var(--pink);font-size:0.8rem">►</span> Remembers your preferences</li>
        </ul>
      </div>
      <a href="{{ url('/barista') }}" class="btn btn-cyan">Open Full Chat Interface →</a>
    </div>
    <div class="chat-preview">
      <div class="chat-header">
        <div class="ai-avatar">🤖</div>
        <div>
          <div class="ai-name">NEXUS-7 BARISTA</div>
          <div class="ai-status">● Online — Neural Link Active</div>
        </div>
      </div>
      <div class="chat-messages" id="demo-chat">
        <div class="msg ai">
          <div class="msg-bubble">Welcome to Cyber Café 2099. I'm NEXUS-7. What can I brew for your mind tonight?</div>
        </div>
        <div class="msg user">
          <div class="msg-bubble">Recommend music for late night coding</div>
        </div>
        <div class="msg ai">
          <div class="msg-bubble">Initiating neural sync... I'd suggest <span style="color:var(--cyan)">Synthwave Drive</span> — 80 BPM, sub-bass resonance, perfect for flow state. Shall I queue it?</div>
        </div>
      </div>
      <div class="chat-input-row">
        <input class="chat-inp" id="demo-inp" placeholder="Ask NEXUS-7 anything..." />
        <button class="chat-send" id="demo-send">SEND</button>
      </div>
    </div>
  </div>
</section>
</div>
@endsection