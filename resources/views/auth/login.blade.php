@extends('layouts.app')

@section('title', 'Login — Cyber Café 2099')

@section('content')

<section style="min-height: 100vh; display:flex; align-items:center; justify-content:center; padding: 0 8%;">

  <div style="width: 100%; max-width: 480px;
              background: rgba(15,20,40,0.65);
              border: 1px solid rgba(255,255,255,0.08);
              border-radius: 25px;
              padding: 40px;
              box-shadow: 0 0 40px rgba(0,229,255,0.15);
              backdrop-filter: blur(20px);">

    <h2 style="font-family:'Orbitron',sans-serif; color:#00e5ff;
               text-align:center; margin-bottom:30px; font-size:28px;">
      Login to Café
    </h2>

    {{-- Session Status --}}
    @if (session('status'))
      <div class="alert alert-success" style="margin-bottom:20px;">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      {{-- Email --}}
      <div style="margin-bottom: 20px;">
        <label style="color:#9ab0c9; font-size:16px; display:block; margin-bottom:8px;">
          Email
        </label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
          style="width:100%; padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.08);
                 background:rgba(255,255,255,0.08); color:white; font-size:16px;
                 font-family:'Rajdhani',sans-serif; outline:none;">
        @error('email')
          <p style="color:#ff2fd1; margin-top:6px; font-size:14px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Password --}}
      <div style="margin-bottom: 20px;">
        <label style="color:#9ab0c9; font-size:16px; display:block; margin-bottom:8px;">
          Password
        </label>
        <input type="password" name="password" required
          style="width:100%; padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.08);
                 background:rgba(255,255,255,0.08); color:white; font-size:16px;
                 font-family:'Rajdhani',sans-serif; outline:none;">
        @error('password')
          <p style="color:#ff2fd1; margin-top:6px; font-size:14px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Remember Me --}}
      <div style="margin-bottom: 25px; display:flex; align-items:center; gap:10px;">
        <input type="checkbox" name="remember" id="remember"
               style="width:16px; height:16px; accent-color:#00e5ff;">
        <label for="remember" style="color:#9ab0c9; font-size:15px;">Remember me</label>
      </div>

      {{-- Submit --}}
      <button type="submit"
        style="width:100%; padding:15px; border-radius:12px; border:none; cursor:pointer;
               background:linear-gradient(135deg,#00e5ff,#8c52ff); color:black;
               font-size:18px; font-weight:700; font-family:'Rajdhani',sans-serif;
               transition:0.3s;">
        Enter the Café ☕
      </button>
    </form>

    {{-- Links --}}
    <div style="text-align:center; margin-top:25px;">
      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}"
           style="color:#9ab0c9; font-size:15px; display:block; margin-bottom:10px; text-decoration:none;">
          Forgot your password?
        </a>
      @endif
      <a href="{{ route('register') }}"
         style="color:#00e5ff; font-size:16px; text-decoration:none;">
        No account? Register here
      </a>
    </div>

  </div>
</section>

@endsection