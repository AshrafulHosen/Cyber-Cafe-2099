<nav>
  <div class="logo">Cyber Café 2099</div>

  <div class="nav-links">
    <a href="{{ url('/#features') }}">Features</a>
    <a href="{{ route('study.index') }}">Study Rooms</a>
    <a href="{{ route('chat.index') }}">Chat</a>
    <a href="{{ url('/#music') }}">Music</a>

    @auth
      {{-- Show username and logout when logged in --}}
      <span style="color: var(--cyan); font-weight: 700;">{{ Auth::user()->name }}</span>
      <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit"
          style="background:none; border:none; color:var(--soft);
                 cursor:pointer; font-size:18px; font-family:'Rajdhani',sans-serif;">
          Logout
        </button>
      </form>
    @else
      {{-- Show login/register when guest --}}
      <a href="{{ route('login') }}">Login</a>
      <a href="{{ route('register') }}">Register</a>
    @endauth
  </div>
</nav>