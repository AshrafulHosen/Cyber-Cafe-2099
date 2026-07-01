@extends('layouts.app')

@section('title', 'Music Lounge — Cyber Café 2099')

@section('content')
<div class="full-section alt">
<section style="padding-top: 150px;">
  <div class="section-label">// audio_engine.sys</div>
  <h2 class="section-title">Synthwave <span style="color:var(--purple)">Music Lounge</span></h2>
  <p class="section-sub">Reactive neon audio visualizer. Choose your frequency and let the city soundtrack your session.</p>
  
  <div class="visualizer-wrap" style="margin-top: 50px;">
    <canvas id="viz-canvas" style="min-height: 250px; background: rgba(0,0,0,0.5); border: 1px solid rgba(138,43,226,0.3); border-radius: 4px;"></canvas>
    
    <div class="music-controls" style="margin-top: 30px;">
      <button class="mode-btn active" data-mode="lofi">Lo-Fi Beats</button>
      <button class="mode-btn" data-mode="synthwave">Synthwave</button>
      <button class="mode-btn" data-mode="rain">Rain Ambience</button>
      <button class="mode-btn" data-mode="cafe">Café Sounds</button>
      <button class="mode-btn" data-mode="drive">Night Drive</button>
    </div>
  </div>
  
  <div style="text-align: center; margin-top: 60px;">
      <p style="color: var(--text-dim); font-family: var(--font-mono); font-size: 0.8rem;">[AUDIO DATABASE PENDING SYNCHRONIZATION]</p>
  </div>
</section>
</div>
@endsection
