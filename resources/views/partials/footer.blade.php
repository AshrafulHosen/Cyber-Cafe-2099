<footer>
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="logo">CYBERCAFÉ 2099</div>
      <p>Where neon meets silence. A digital sanctuary built for the focused minds of tomorrow.</p>
      <div style="margin-top:20px;display:flex;gap:12px">
        <span style="font-family:var(--font-mono);font-size:0.62rem;color:var(--cyan);border:1px solid var(--border-c);padding:4px 10px;border-radius:2px">Tokyo Node</span>
        <span style="font-family:var(--font-mono);font-size:0.62rem;color:var(--purple);border:1px solid var(--border-p);padding:4px 10px;border-radius:2px">Sector 7</span>
      </div>
    </div>
    <div class="footer-col">
      <h4>Navigate</h4>
      <ul>
        <li><a href="{{ url('/cafe') }}">Café Room</a></li>
        <li><a href="{{ route('study.index') }}">Study Tables</a></li>
        <li><a href="{{ url('/music') }}">Music Lounge</a></li>
        <li><a href="{{ url('/barista') }}">AI Barista</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Community</h4>
      <ul>
        <li><a href="#">Global Chat</a></li>
        <li><a href="#">Leaderboard</a></li>
        <li><a href="#">Events</a></li>
        <li><a href="#">Night City Map</a></li>
      </ul>
    </div>
    <div class="footer-col">
      <h4>Account</h4>
      <ul>
        <li><a href="{{ url('/profile') }}">Profile</a></li>
        <li><a href="#">Credits</a></li>
        <li><a href="#">Achievements</a></li>
        <li><a href="#">Settings</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bottom">
    <span>© {{ date('Y') }} CYBERCAFÉ INC. — ALL RIGHTS RESERVED</span>
    <span style="color:var(--cyan)">TOKYO NODE ACTIVE — LATENCY: 4ms</span>
  </div>
</footer>