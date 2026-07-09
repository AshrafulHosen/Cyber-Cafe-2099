@extends('layouts.app')

@section('title', 'Music Lounge — Cyber Café 2099')

@section('content')
<div class="full-section alt">
<section style="padding-top: 150px;">
  <div class="section-label">// audio_engine.sys</div>
  <h2 class="section-title">Synthwave <span style="color:var(--purple)">Music Lounge</span></h2>
  <p class="section-sub">Reactive neon audio visualizer. Choose your frequency and let the city soundtrack your session.</p>
  
  <!-- Search Interface -->
  <div style="margin-top: 30px; text-align: center;">
      <input type="text" id="yt-search-input" placeholder="Search YouTube for any track..." style="padding: 10px; width: 60%; max-width: 400px; background: rgba(0,0,0,0.6); border: 1px solid var(--cyan); color: var(--white); border-radius: 4px; font-family: var(--font-main);">
      <button id="yt-search-btn" class="btn btn-cyan" style="padding: 10px 20px; margin-left: 10px;">Search</button>
  </div>
  
  <!-- Search Results Container -->
  <div id="yt-search-results" style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px; max-width: 600px; margin-left: auto; margin-right: auto; max-height: 250px; overflow-y: auto;"></div>
  
  <div class="visualizer-wrap" style="margin-top: 50px; position: relative;">
    <!-- Visible YouTube Player -->
    <div style="width: 100%; aspect-ratio: 16/9; background: rgba(0,0,0,0.5); border: 1px solid rgba(138,43,226,0.5); border-radius: 8px; overflow: hidden; box-shadow: 0 0 20px rgba(138,43,226,0.2);">
        <div id="yt-player" style="width: 100%; height: 100%;"></div>
    </div>
    
    <div class="music-controls" style="margin-top: 30px; display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
      <button class="mode-btn" data-vid="jfKfPfyJRdk" data-mode="lofi">Lo-Fi Beats</button>
      <button class="mode-btn" data-vid="4xDzrUhVKcg" data-mode="synthwave">Synthwave</button>
      <button class="mode-btn" data-vid="mPZkdNFkNps" data-mode="rain">Rain Ambience</button>
      <button class="mode-btn" data-vid="GA9AwGLyKqM" data-mode="cafe">Café Sounds</button>
      <button class="mode-btn" data-vid="1ueGZ3I6XKA" data-mode="drive">Night Drive</button>
    </div>

    <!-- Playback Controls -->
    <div style="margin-top: 20px; text-align: center; display: flex; gap: 15px; justify-content: center; align-items: center;">
        <button id="yt-toggle" class="btn btn-purple" style="padding: 8px 20px; font-size: 0.9rem;" disabled>Connecting to Satellite...</button>
        <div style="color: var(--cyan); font-family: var(--font-mono); font-size: 0.8rem;">
            VOL <input type="range" id="yt-volume" min="0" max="100" value="50" style="vertical-align: middle; width: 100px;">
        </div>
    </div>
  </div>
  
  <div style="text-align: center; margin-top: 60px;">
      <p id="yt-status" style="color: var(--text-dim); font-family: var(--font-mono); font-size: 0.8rem;">[AUDIO DATABASE CONNECTED: SELECT FREQUENCY]</p>
  </div>
</section>
</div>

@push('scripts')
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    var player;
    var isPlaying = false;
    var currentVid = null;

    // Called automatically by YouTube Iframe API when loaded
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('yt-player', {
            height: '100%',
            width: '100%',
            videoId: '', // start empty
            playerVars: { 'autoplay': 1, 'controls': 1, 'disablekb': 1, 'fs': 0, 'playsinline': 1 },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange,
                'onError': onPlayerError
            }
        });
    }

    function onPlayerReady(event) {
        player.setVolume(50);
        document.getElementById('yt-toggle').innerText = 'PAUSED';
        document.getElementById('yt-toggle').disabled = false;
    }

    function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING) {
            isPlaying = true;
            document.getElementById('yt-toggle').innerText = 'PAUSE';
            document.getElementById('yt-status').innerText = '[STREAMING FROM SATELLITE: ACTIVE]';
        } else if (event.data == YT.PlayerState.PAUSED) {
            isPlaying = false;
            document.getElementById('yt-toggle').innerText = 'PLAY';
            document.getElementById('yt-status').innerText = '[STREAMING PAUSED]';
        }
    }

    function onPlayerError(event) {
        document.getElementById('yt-status').innerText = '[ERROR: FREQUENCY BLOCKED. TRY ANOTHER NODE]';
    }

    // Toggle Play/Pause Button
    document.getElementById('yt-toggle').addEventListener('click', function() {
        if (!player || !currentVid) return;
        if (isPlaying) {
            player.pauseVideo();
        } else {
            player.playVideo();
        }
    });

    // Volume Slider
    document.getElementById('yt-volume').addEventListener('input', function(e) {
        if (player) {
            player.setVolume(e.target.value);
        }
    });

    // Mode Buttons (Lofi, Synthwave, etc)
    document.querySelectorAll('.mode-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // UI Update handled mostly by app.js, but let's force it here too
            document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            var vid = this.getAttribute('data-vid');
            if (vid !== currentVid) {
                currentVid = vid;
                document.getElementById('yt-status').innerText = '[BUFFERING FREQUENCY...]';
                if (player && typeof player.loadVideoById === 'function') {
                    player.loadVideoById(vid);
                }
            } else {
                // If clicking the same one, just make sure it's playing
                if (!isPlaying && player) {
                    player.playVideo();
                }
            }
        });
    });

    // YouTube Search Feature
    document.getElementById('yt-search-btn').addEventListener('click', function() {
        const query = document.getElementById('yt-search-input').value;
        const resultsContainer = document.getElementById('yt-search-results');
        
        if (!query) return;
        
        resultsContainer.innerHTML = '<div style="color: var(--cyan); font-family: var(--font-mono);">[Searching Databanks...]</div>';
        
        fetch(`/music/search?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = '';
                if (data.error) {
                    resultsContainer.innerHTML = `<div style="color: var(--pink); font-family: var(--font-mono);">${data.error}</div>`;
                    return;
                }
                if (data.items && data.items.length > 0) {
                    data.items.forEach(item => {
                        const vid = item.id.videoId;
                        const title = item.snippet.title;
                        const channel = item.snippet.channelTitle;
                        const thumb = item.snippet.thumbnails.default.url;
                        
                        const resultDiv = document.createElement('div');
                        resultDiv.style.cssText = 'display: flex; gap: 15px; align-items: center; background: rgba(255,255,255,0.05); padding: 10px; border-radius: 4px; cursor: pointer; border-left: 3px solid var(--purple); transition: 0.3s;';
                        resultDiv.innerHTML = `
                            <img src="${thumb}" alt="thumbnail" style="width: 120px; border-radius: 4px;">
                            <div style="text-align: left;">
                                <div style="color: var(--white); font-weight: bold; font-size: 0.9rem;">${title}</div>
                                <div style="color: var(--text-dim); font-size: 0.8rem; font-family: var(--font-mono); margin-top: 5px;">${channel}</div>
                            </div>
                        `;
                        
                        resultDiv.addEventListener('mouseenter', () => resultDiv.style.background = 'rgba(255,255,255,0.1)');
                        resultDiv.addEventListener('mouseleave', () => resultDiv.style.background = 'rgba(255,255,255,0.05)');
                        
                        resultDiv.addEventListener('click', () => {
                            document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
                            currentVid = vid;
                            document.getElementById('yt-status').innerText = `[BUFFERING: ${title}]`;
                            if (player && typeof player.loadVideoById === 'function') {
                                player.loadVideoById(vid);
                            }
                        });
                        
                        resultsContainer.appendChild(resultDiv);
                    });
                } else {
                    resultsContainer.innerHTML = '<div style="color: var(--text-dim); font-family: var(--font-mono);">[No results found in databank]</div>';
                }
            })
            .catch(err => {
                resultsContainer.innerHTML = '<div style="color: var(--pink); font-family: var(--font-mono);">[Connection error. Ensure API Key is configured in .env]</div>';
            });
    });
</script>
@endpush
@endsection
