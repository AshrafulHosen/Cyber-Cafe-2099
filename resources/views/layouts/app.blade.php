<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'CYBER CAFÉ 2099 — Where Neon Meets Silence')</title>
  
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
        window.globalPlayer = new YT.Player('global-yt-player', {
            height: '100%',
            width: '100%',
            videoId: 'jfKfPfyJRdk', // Provide valid initial video to prevent silent crash
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
