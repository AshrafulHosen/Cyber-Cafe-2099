@extends('layouts.app')

@section('title', 'Register — Cyber Café 2099')

@section('content')

<section style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:0 8%;">

  <div style="width:100%; max-width:480px;
              background:rgba(15,20,40,0.65);
              border:1px solid rgba(255,255,255,0.08);
              border-radius:25px;
              padding:40px;
              box-shadow:0 0 40px rgba(0,229,255,0.15);
              backdrop-filter:blur(20px);">

    <h2 style="font-family:'Orbitron',sans-serif; color:#00e5ff;
               text-align:center; margin-bottom:30px; font-size:28px;">
      Join the Café
    </h2>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      {{-- Name --}}
      <div style="margin-bottom:20px;">
        <label style="color:#9ab0c9; font-size:16px; display:block; margin-bottom:8px;">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required autofocus
          style="width:100%; padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.08);
                 background:rgba(255,255,255,0.08); color:white; font-size:16px;
                 font-family:'Rajdhani',sans-serif; outline:none;">
        @error('name')
          <p style="color:#ff2fd1; margin-top:6px; font-size:14px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Email --}}
      <div style="margin-bottom:20px;">
        <label style="color:#9ab0c9; font-size:16px; display:block; margin-bottom:8px;">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
          style="width:100%; padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.08);
                 background:rgba(255,255,255,0.08); color:white; font-size:16px;
                 font-family:'Rajdhani',sans-serif; outline:none;">
        @error('email')
          <p style="color:#ff2fd1; margin-top:6px; font-size:14px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Password --}}
      <div style="margin-bottom:20px;">
        <label style="color:#9ab0c9; font-size:16px; display:block; margin-bottom:8px;">Password</label>
        <input type="password" name="password" required
          style="width:100%; padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.08);
                 background:rgba(255,255,255,0.08); color:white; font-size:16px;
                 font-family:'Rajdhani',sans-serif; outline:none;">
        @error('password')
          <p style="color:#ff2fd1; margin-top:6px; font-size:14px;">{{ $message }}</p>
        @enderror
      </div>

      {{-- Confirm Password --}}
      <div style="margin-bottom:25px;">
        <label style="color:#9ab0c9; font-size:16px; display:block; margin-bottom:8px;">
          Confirm Password
        </label>
        <input type="password" name="password_confirmation" required
          style="width:100%; padding:15px; border-radius:12px; border:1px solid rgba(255,255,255,0.08);
                 background:rgba(255,255,255,0.08); color:white; font-size:16px;
                 font-family:'Rajdhani',sans-serif; outline:none;">
      </div>

      {{-- Submit --}}
      <button type="submit"
        style="width:100%; padding:15px; border-radius:12px; border:none; cursor:pointer;
               background:linear-gradient(135deg,#00e5ff,#8c52ff); color:black;
               font-size:18px; font-weight:700; font-family:'Rajdhani',sans-serif;
               transition:0.3s;">
        Create Account 🚀
      </button>
    </form>

    <div style="text-align:center; margin-top:25px;">
      <a href="{{ route('login') }}"
         style="color:#00e5ff; font-size:16px; text-decoration:none;">
        Already have an account? Login
      </a>
    </div>

  </div>
</section>

@endsection