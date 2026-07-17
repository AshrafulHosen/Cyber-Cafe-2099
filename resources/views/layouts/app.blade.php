<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'CYBER CAFÉ 2099 — Where Neon Meets Silence')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Rajdhani:wght@300;400;500;600&family=Share+Tech+Mono&display=swap" rel="stylesheet">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* Swup Transition Styles */
    .transition-fade {
      transition: 0.3s;
      opacity: 1;
    }
    html.is-animating .transition-fade {
      opacity: 0;
      transform: translateY(10px);
    }
    /* Global Mini Player Styles (PiP) */
    #global-player-wrapper {
      background: rgba(0,0,0,0.9);
      border: 1px solid var(--purple);
      border-radius: 8px;
      overflow: hidden;
      display: none; /* Hidden until music starts */
      transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
      box-shadow: 0 0 20px rgba(138,43,226,0.4);
    }
    #global-player-wrapper.mini-mode {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 280px;
      height: 157px; /* 16:9 aspect ratio */
      z-index: 9999;
    }
    #global-player-wrapper.mini-mode:hover {
      transform: scale(1.02);
      border-color: var(--cyan);
    }
    #global-player-wrapper.full-mode {
      position: absolute;
      z-index: 40;
    }
  </style>

  @auth
    @if(Auth::user()->theme_color === 'pink')
      <style>
        :root {
          --cyan: #ff2d78;
          --cyan-dim: #b01e53;
          --glow-c: 0 0 20px rgba(255,45,120,0.5), 0 0 60px rgba(255,45,120,0.2);
        }
      </style>
    @elseif(Auth::user()->theme_color === 'purple')
      <style>
        :root {
          --cyan: #b44fff;
          --cyan-dim: #7a2eb8;
          --glow-c: 0 0 20px rgba(180,79,255,0.5), 0 0 60px rgba(180,79,255,0.2);
        }
      </style>
    @endif
    @endauth
  </style>

  @auth
    @php
      $activeItems = Auth::user()->inventoryItems()->wherePivot('status', 'EQUIPPED')->pluck('name')->toArray();
    @endphp
  @else
    @php
      $activeItems = [];
    @endphp
  @endauth
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

  @if(in_array('Rain Ambience Room', $activeItems))
    <div id="rain-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9000; background: url('data:image/svg+xml;utf8,<svg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'><line x1=\'10\' y1=\'0\' x2=\'5\' y2=\'20\' stroke=\'rgba(0, 255, 255, 0.4)\' stroke-width=\'1\'/><line x1=\'50\' y1=\'5\' x2=\'45\' y2=\'30\' stroke=\'rgba(0, 255, 255, 0.4)\' stroke-width=\'1\'/><line x1=\'150\' y1=\'15\' x2=\'140\' y2=\'40\' stroke=\'rgba(0, 255, 255, 0.4)\' stroke-width=\'1\'/></svg>') repeat; opacity: 0.3; animation: rain 0.3s linear infinite;"></div>
    <style>
      @keyframes rain {
        from { background-position: 0 0; }
        to { background-position: -20px 100px; }
      }
    </style>
  @endif

  @if(in_array('Cyber Cat Hologram', $activeItems))
    <div id="cyber-cat-hologram" style="position: fixed; bottom: 20px; left: 20px; font-size: 3rem; z-index: 9999; animation: float 3s ease-in-out infinite, pulse-glow 2s infinite alternate; filter: drop-shadow(0 0 10px var(--cyan)); pointer-events: none;">
      🐈
    </div>
    <style>
      @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
      }
      @keyframes pulse-glow {
        from { opacity: 0.6; }
        to { opacity: 1; filter: drop-shadow(0 0 20px var(--pink)); }
      }
    </style>
  @endif

  @include('partials.navbar')

  <!-- Global YouTube Mini Player -->
  <div id="global-player-wrapper" class="mini-mode">
    <div id="global-yt-player" style="width: 100%; height: 100%;"></div>
  </div>

  <main id="swup" class="transition-fade">
    @stack('styles')
    @yield('content')
    
    <!-- Page Specific Scripts that reload on Swup -->
    <div id="swup-scripts" style="display: none;">
      @stack('scripts')
    </div>
  </main>

  @include('partials.footer')

  <!-- Swup & YouTube Scripts -->
  <script src="https://unpkg.com/swup@4"></script>
  <script>
    // Global YouTube Player Logic
    window.globalPlayer = null;
    window.globalIsPlaying = false;
    window.globalCurrentVid = null;

    function onYouTubeIframeAPIReady() {
        @php
           $defaultVideo = in_array('Synthwave Audio Pack', $activeItems) ? 'MV_3Dpw-BRY' : 'jfKfPfyJRdk';
        @endphp
        window.globalPlayer = new YT.Player('global-yt-player', {
            height: '100%',
            width: '100%',
            videoId: '{{ $defaultVideo }}', // Provide valid initial video to prevent silent crash
            playerVars: { 'autoplay': 0, 'controls': 1, 'disablekb': 1, 'fs': 0, 'playsinline': 1 },
            events: {
                'onReady': (e) => {
                    e.target.setVolume(50);
                    document.dispatchEvent(new Event('GlobalPlayerReady'));
                },
                'onStateChange': (e) => {
                    window.globalIsPlaying = (e.data == YT.PlayerState.PLAYING);
                    if (window.globalIsPlaying) {
                        document.getElementById('global-player-wrapper').style.display = 'block';
                        window.updatePiPState();
                    }
                    document.dispatchEvent(new CustomEvent('GlobalPlayerStateChange', { detail: e.data }));
                },
                'onError': (e) => {
                    document.dispatchEvent(new Event('GlobalPlayerError'));
                }
            }
        });
    }

    window.playGlobalMusic = function(vid) {
        if (window.globalPlayer && typeof window.globalPlayer.loadVideoById === 'function') {
            window.globalCurrentVid = vid;
            window.globalPlayer.loadVideoById(vid);
            document.getElementById('global-player-wrapper').style.display = 'block';
            window.updatePiPState();
        }
    };

    // Global Pomodoro Timer Logic
    window.globalSecondsLeft = 25 * 60;
    window.globalTimerRunning = false;
    window.globalTimerInterval = null;

    window.startGlobalTimer = function() {
        if (window.globalTimerRunning) return;
        window.globalTimerRunning = true;
        window.globalTimerInterval = setInterval(() => {
            if (window.globalSecondsLeft > 0) {
                window.globalSecondsLeft--;
                document.dispatchEvent(new Event('GlobalTimerTick'));
            } else {
                clearInterval(window.globalTimerInterval);
                window.globalTimerRunning = false;
                document.dispatchEvent(new Event('GlobalTimerComplete'));
                alert("Session complete! Time for a neon break.");
            }
        }, 1000);
    };

    window.pauseGlobalTimer = function() {
        clearInterval(window.globalTimerInterval);
        window.globalTimerRunning = false;
        document.dispatchEvent(new Event('GlobalTimerTick'));
    };

    window.resetGlobalTimer = function() {
        clearInterval(window.globalTimerInterval);
        window.globalTimerRunning = false;
        window.globalSecondsLeft = 25 * 60;
        document.dispatchEvent(new Event('GlobalTimerTick'));
    };

    // PiP State Management
    window.updatePiPState = function() {
        const wrapper = document.getElementById('global-player-wrapper');
        const placeholder = document.getElementById('music-video-placeholder');
        
        if (placeholder) {
            // We are on the music page. Attach to placeholder!
            const rect = placeholder.getBoundingClientRect();
            wrapper.classList.remove('mini-mode');
            wrapper.classList.add('full-mode');
            wrapper.style.top = (window.scrollY + rect.top) + 'px';
            wrapper.style.left = (window.scrollX + rect.left) + 'px';
            wrapper.style.width = rect.width + 'px';
            wrapper.style.height = rect.height + 'px';
        } else {
            // Not on music page. Snap to corner PiP
            wrapper.classList.remove('full-mode');
            wrapper.classList.add('mini-mode');
            wrapper.style.top = '';
            wrapper.style.left = '';
            wrapper.style.width = '';
            wrapper.style.height = '';
        }
    };

    window.addEventListener('resize', () => {
        const wrapper = document.getElementById('global-player-wrapper');
        if (wrapper && wrapper.classList.contains('full-mode')) {
            window.updatePiPState();
        }
    });

    // Failsafe for Adblockers or network issues blocking the YouTube API
    setTimeout(() => {
        if (!window.globalPlayer) {
            const status = document.getElementById('yt-status');
            const toggleBtn = document.getElementById('yt-toggle');
            if (status) status.innerText = "[ERROR: YOUTUBE SCRIPT BLOCKED. PLEASE DISABLE ADBLOCKER]";
            if (toggleBtn) toggleBtn.innerText = "API BLOCKED";
        }
    }, 4000);
  </script>
  <!-- Load iframe API AFTER defining the callback -->
  <script src="https://www.youtube.com/iframe_api"></script>

  <script>
    // Initialize SPA Router safely
    try {
        if (typeof Swup !== 'undefined') {
            window.swup = new Swup({
                cache: false // Disable cache so server redirects (like session seat tracking) always work
            });
            
            // Handle Swup page transitions
            window.swup.hooks.on('page:view', () => {
                window.updatePiPState();
                
                const scripts = document.querySelectorAll('#swup-scripts script');
                scripts.forEach(script => {
                    const newScript = document.createElement('script');
                    Array.from(script.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                    newScript.appendChild(document.createTextNode(script.innerHTML));
                    script.parentNode.replaceChild(newScript, script);
                });
            });
        } else {
            console.warn("Swup CDN failed to load. Falling back to traditional navigation.");
        }
    } catch(e) {
        console.error("Swup initialization error:", e);
    }
  </script>
</body>
</html>
