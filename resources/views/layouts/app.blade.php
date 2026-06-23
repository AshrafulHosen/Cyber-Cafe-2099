<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Cyber Café 2099')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Rajdhani:wght@300;400;500;700&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css'])
  @stack('styles')
</head>
<body>

  <div class="rain" id="rain"></div>

  @include('partials.navbar')

  @yield('content')

  @include('partials.footer')

  @vite(['resources/js/app.js'])
  @stack('scripts')

</body>
</html>