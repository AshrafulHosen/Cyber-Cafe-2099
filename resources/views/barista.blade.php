@extends('layouts.app')

@section('title', 'AI Barista — Cyber Café 2099')

@section('content')
<div class="full-section">
<section style="padding-top: 150px;">
  <div class="section-label">// nexus-7.ai</div>
  <h2 class="section-title">AI <span style="color:var(--cyan)">Barista</span></h2>
  <p class="section-sub">Your personal cyberpunk companion. Powered by neural networks, fueled by espresso.</p>

  <div style="display:grid;grid-template-columns:1fr;gap:40px;margin-top:60px;align-items:center; max-width: 800px; margin-inline: auto;">
    <div class="chat-preview">
      <div class="chat-header">
        <div class="ai-avatar">🤖</div>
        <div>
          <div class="ai-name">NEXUS-7 BARISTA</div>
          <div class="ai-status">● Online — Neural Link Active</div>
        </div>
      </div>
      
      <div class="chat-messages" id="demo-chat" style="min-height: 400px; display: flex; flex-direction: column; justify-content: flex-end;">
        <div style="text-align: center; margin-bottom: 20px; color: var(--text-dim); font-family: var(--font-mono); font-size: 0.8rem;">
            [DATABASE CONNECTION PENDING]<br>
            Neural core is currently isolated from the main servers.
        </div>
      
        <div class="msg ai">
          <div class="msg-bubble">Welcome back, traveler. My memory banks are temporarily offline, but I'm still brewing the finest synthetic espresso in Sector 7.</div>
        </div>
      </div>
      
      <div class="chat-input-row">
        <input class="chat-inp" id="demo-inp" placeholder="System offline..." disabled style="cursor: not-allowed; opacity: 0.5;"/>
        <button class="chat-send" id="demo-send" disabled style="cursor: not-allowed; opacity: 0.5;">SEND</button>
      </div>
    </div>
  </div>
</section>
</div>
@endsection
