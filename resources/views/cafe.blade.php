@extends('layouts.app')

@section('title', 'Café Room — Cyber Café 2099')

@section('content')
<div class="full-section">
<section style="padding-top: 150px;">
  <div class="section-label">// public_lounge</div>
  <h2 class="section-title">Café <span style="color:var(--pink)">Room</span></h2>
  <p class="section-sub">A vibrant, neon-drenched lounge where digital travelers converge. Grab a seat, catch the global chatter, and soak in the ambiance.</p>
  
  <div style="margin-top: 40px; text-align: center;">
    <div style="padding: 60px; border: 1px dashed var(--cyan); background: rgba(0,255,255,0.02);">
      <h3 style="color:var(--cyan); font-family: var(--font-mono); text-transform: uppercase;">System Offline</h3>
      <p style="color:var(--text-dim); margin-top: 15px;">The global chat interface is currently undergoing maintenance. Database link is not active.</p>
      
      @auth
        <a href="{{ route('chat.index') }}" class="btn btn-cyan" style="margin-top: 30px;">Access Secure Channel</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-purple" style="margin-top: 30px;">Authenticate to Proceed</a>
      @endauth
    </div>
  </div>
</section>
</div>
@endsection
