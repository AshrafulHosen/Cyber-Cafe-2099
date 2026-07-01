<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'CYBER CAFÉ 2099 — Where Neon Meets Silence')</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600&family=Share+Tech+Mono&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('styles')
</head>
<body>

  <!-- PRELOADER -->
  @if(!session()->has('preloader_shown'))
    <div id="preloader">
      <div class="pre-logo">CYBER CAFÉ 2099</div>
      <div class="pre-bar"><div class="pre-fill"></div></div>
      <div class="pre-text" id="pre-text">INITIALIZING NEURAL LINK...</div>
    </div>
    @php session()->put('preloader_shown', true); @endphp
  @endif

  <div id="scanlines"></div>
  <div id="noise"></div>

  @include('partials.navbar')

  @yield('content')

  @include('partials.footer')

  @stack('scripts')
</body>
</html>
