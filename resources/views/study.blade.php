@extends('layouts.app')

@section('title', 'Study Rooms — Cyber Café 2099')

@section('content')

  <section id="study">
    <div class="section-title">
      <h2>Virtual Study Tables</h2>
      <p>Choose your digital workspace.</p>
    </div>

    <div class="study-room">

      {{-- Loop through tables passed from the controller --}}
      @foreach ($tables as $table)
        <div class="table-card">
          <h3>
            <span class="status {{ $table['color'] }}"></span>
            {{ $table['name'] }}
          </h3>
          <p>{{ $table['user_count'] }} users {{ $table['activity'] }}</p>

          {{-- Join button — only for logged-in users --}}
          @auth
            <a href="#" class="btn primary"
               style="margin-top: 15px; display:inline-block; font-size:15px; padding:10px 20px;">
              Join Table
            </a>
          @else
            <a href="{{ route('login') }}" class="btn secondary"
               style="margin-top: 15px; display:inline-block; font-size:15px; padding:10px 20px;">
              Login to Join
            </a>
          @endauth
        </div>
      @endforeach

    </div>
  </section>

@endsection