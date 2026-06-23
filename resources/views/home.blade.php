@extends('layouts.app')

@section('title', 'Cyber Café 2099 — Welcome')

@section('content')

  {{-- ======================== HERO SECTION ======================== --}}
  <section class="hero">
    <div class="hero-text">
      <h1>
        Welcome To <span class="glow">Cyber Café 2099</span>
      </h1>
      <p>
        A futuristic virtual café where students, creators, and dreamers
        relax together under synthetic rain and neon lights.
      </p>
      <div class="btn-group">
        <a href="{{ route('study.index') }}" class="btn primary">Enter Café</a>
        <a href="{{ route('chat.index') }}" class="btn secondary">Join Study Room</a>
      </div>
    </div>

    <div class="hero-card">
      <h3>Now Playing</h3>
      <div class="music-box">
        <h2>Tokyo Rain Synthwave</h2>
        <p>Lo-fi • Ambient • Night Drive</p>
        <div class="music-wave">
          <span></span><span></span><span></span><span></span><span></span>
        </div>
      </div>
      <div class="music-box">
        <h2>AI Barista Status</h2>
        <p>"Good evening traveler. Coffee or motivation today?"</p>
      </div>
    </div>
  </section>

  {{-- ======================== FEATURES SECTION ======================== --}}
  <section id="features">
    <div class="section-title">
      <h2>Futuristic Features</h2>
      <p>Experience the next generation digital café.</p>
    </div>

    <div class="features">
      @foreach ($features as $feature)
        <div class="feature-card">
          <h3>{{ $feature['title'] }}</h3>
          <p>{{ $feature['description'] }}</p>
        </div>
      @endforeach
    </div>
  </section>

  {{-- ======================== MUSIC / AMBIENT SECTION ======================== --}}
  <section id="music">
    <div class="section-title">
      <h2>Ambient Experience</h2>
      <p>Rain. Neon. Coffee. Music.</p>
    </div>

    <div class="features">
      <div class="feature-card">
        <h3>Rain Ambience</h3>
        <p>Dynamic rain effects create a relaxing futuristic atmosphere.</p>
      </div>
      <div class="feature-card">
        <h3>Synthwave Nights</h3>
        <p>Enjoy cyberpunk-inspired soundtracks while coding or studying.</p>
      </div>
      <div class="feature-card">
        <h3>Digital Coffee Mood</h3>
        <p>Café lighting changes depending on time and mood.</p>
      </div>
    </div>
  </section>

@endsection