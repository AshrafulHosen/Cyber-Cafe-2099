<nav>
  <a href="{{ url('/') }}" class="nav-logo">CYBER<span>CAFÉ</span> 2099</a>
  <ul class="nav-links">
    <li><a href="{{ url('/cafe') }}">Café Room</a></li>
    <li><a href="{{ route('study.index') }}">Study Tables</a></li>
    <li><a href="{{ url('/music') }}">Music Lounge</a></li>
    <li><a href="{{ url('/barista') }}">AI Barista</a></li>
    <li><a href="{{ url('/profile') }}">Profile</a></li>
  </ul>
  <div class="nav-actions">
    <div class="online-dot">2,847 online</div>
    @auth
      <span style="color: var(--cyan); font-weight: 700; margin-right: 10px; font-family: var(--font-mono); font-size: 0.7rem; text-transform: uppercase;">{{ Auth::user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-cyan" style="padding:8px 18px;font-size:0.65rem">Logout</button>
      </form>
    @else
      <a href="{{ route('login') }}" class="btn btn-cyan" style="padding:8px 18px;font-size:0.65rem">Enter</a>
    @endauth
  </div>
</nav>