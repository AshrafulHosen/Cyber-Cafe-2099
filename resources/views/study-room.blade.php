@extends('layouts.app')

@section('title', 'Study Table — ' . $table['name'])

@push('styles')
<style>
    .study-room-layout {
        display: grid;
        grid-template-columns: 3fr 1fr;
        gap: 30px;
        margin-top: 50px;
    }
    
    .focus-mode-active .study-sidebar {
        display: none;
    }
    .focus-mode-active .study-room-layout {
        grid-template-columns: 1fr;
    }
    
    .timer-display {
        font-family: var(--font-mono);
        font-size: 5rem;
        color: var(--cyan);
        text-align: center;
        text-shadow: 0 0 20px rgba(0, 255, 255, 0.5);
        margin: 40px 0;
    }
    
    .timer-controls {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .room-glow-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        pointer-events: none;
        z-index: 1000;
        mix-blend-mode: screen;
        opacity: 0.15;
        transition: background 1s ease;
    }

    .room-blue { background: radial-gradient(circle at center, transparent, #1a6aff 80%); }
    .room-purple { background: radial-gradient(circle at center, transparent, var(--purple) 80%); }
    .room-red { background: radial-gradient(circle at center, transparent, var(--pink) 80%); }

    .panel {
        background: rgba(0,0,0,0.6);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 25px;
        border-radius: 8px;
    }
    
    .panel-title {
        font-family: var(--font-mono);
        font-size: 0.9rem;
        color: var(--text-dim);
        text-transform: uppercase;
        margin-bottom: 20px;
        letter-spacing: 0.1em;
        border-bottom: 1px dashed rgba(255,255,255,0.1);
        padding-bottom: 10px;
    }
</style>
@endpush

@section('content')

@php
    $glowClass = 'room-blue';
    if ($table['color'] === 'purple') $glowClass = 'room-purple';
    if ($table['color'] === 'red') $glowClass = 'room-red';
@endphp

<!-- Dynamic Glow based on table activity -->
<div class="room-glow-overlay {{ $glowClass }}" id="room-glow"></div>

<div class="full-section">
<section style="padding-top: 130px; max-width: 1200px; margin: 0 auto;">
    
    <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 20px;">
        <div>

            <h2 class="section-title" style="margin-bottom: 0;">{{ $table['name'] }}</h2>
            <div style="margin-top: 10px; font-family: var(--font-mono); font-size: 0.8rem;">
                Status: <span style="color: var(--cyan);">{{ strtoupper($table['activity']) }}</span> 
                | Room Code: <span style="color: var(--pink); font-weight: bold; background: rgba(255,45,120,0.1); padding: 2px 5px; border-radius: 2px;">{{ $table['room_code'] ?? 'N/A' }}</span>
                | Users: {{ \App\Models\StudySession::where('study_table_id', $table['id'])->where('active', true)->count() }} Connected
            </div>
        </div>
        
        <div style="display: flex; gap: 10px; align-items: center;">
            @if(Auth::check() && $table->user_id === Auth::id())
                <form action="{{ route('study.destroy', $table->id) }}" method="POST" style="display:inline; margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-pink" onclick="return confirm('Are you sure you want to permanently disband this table?')">[ DISBAND TABLE ]</button>
                </form>
            @endif
            <button id="focusToggle" class="btn btn-purple">◈ Enable Focus Mode</button>
            <a href="{{ route('study.leave') }}" class="btn btn-solid">Leave Table</a>
        </div>
    </div>

    <div class="study-room-layout" id="main-layout">
        
        <!-- MAIN AREA: Timer & Tools -->
        <div class="study-main">
            <div class="panel" style="text-align: center; border-top: 3px solid var(--cyan);">
                <div class="panel-title">Pomodoro System</div>
                
                <div class="timer-display" id="timer">25:00</div>
                
                <div class="timer-controls">
                    <button id="startTimer" class="btn btn-cyan">Start Session</button>
                    <button id="pauseTimer" class="btn btn-solid">Pause</button>
                    <button id="resetTimer" class="btn btn-purple">Reset</button>
                </div>
                

            </div>
            
            <div class="panel" style="margin-top: 30px;">
                <div class="panel-title" style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Shared Documents</span>
                    <label for="file-upload" class="btn btn-solid" style="padding: 5px 10px; font-size: 0.8rem; cursor: pointer;">Upload File</label>
                    <input type="file" id="file-upload" style="display: none;">
                </div>
                
                <div id="upload-progress" style="display: none; color: var(--cyan); font-family: var(--font-mono); font-size: 0.8rem; margin-bottom: 10px;">Uploading...</div>
                
                <ul id="file-list" style="list-style: none; padding: 0; margin: 0; min-height: 150px; display: flex; flex-direction: column; gap: 10px;">
                    <li style="color: var(--text-dim); font-style: italic; text-align: center; margin-top: 20px;">
                        Loading documents...
                    </li>
                </ul>
            </div>
        </div>

        <!-- SIDEBAR: Users & Chat -->
        <div class="study-sidebar" id="sidebar">
            <div class="panel" style="margin-bottom: 30px;">
                <div class="panel-title">Neural Links (Connected)</div>
                <ul id="active-users-list" style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:12px;">
                    <li style="color:var(--text-dim); font-style:italic; font-size: 0.8rem; text-align: center;">Scanning signals...</li>
                </ul>
            </div>
            
            <div class="panel">
                <div class="panel-title">Table Comms</div>
                <div id="table-chat-box" style="height: 250px; background: rgba(0,0,0,0.4); border: 1px solid rgba(0,255,255,0.1); border-radius: 4px; padding: 15px; margin-bottom: 15px; display: flex; flex-direction: column; overflow-y: auto;">
                    <div style="color:var(--text-dim); font-size:0.8rem; margin-bottom:10px; text-align: center;">[System] You joined the table.</div>
                </div>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="table-chat-input" placeholder="Transmit message..." style="flex: 1; padding:10px; background:rgba(0,0,0,0.5); border:1px solid rgba(255,255,255,0.2); color:white; font-family:var(--font-mono);">
                    <button id="table-chat-send" class="btn btn-cyan">Send</button>
                </div>
            </div>
        </div>
        
    </div>
</section>
</div>

@push('scripts')
<script>
(function() {
    // Sync with Global Pomodoro Timer
    function updateDisplay() {
        const display = document.getElementById('timer');
        if (!display) return;
        let m = Math.floor(window.globalSecondsLeft / 60);
        let s = window.globalSecondsLeft % 60;
        display.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
    }
    
    // Initial sync on page load
    updateDisplay();
    
    // Listen for ticks broadcasted from the global timer
    document.addEventListener('GlobalTimerTick', updateDisplay);
    
    // Sync buttons to global functions
    document.getElementById('startTimer').addEventListener('click', () => {
        if (window.startGlobalTimer) window.startGlobalTimer();
    });
    
    document.getElementById('pauseTimer').addEventListener('click', () => {
        if (window.pauseGlobalTimer) window.pauseGlobalTimer();
    });
    
    document.getElementById('resetTimer').addEventListener('click', () => {
        if (window.resetGlobalTimer) window.resetGlobalTimer();
    });

    // Cleanup listeners if Swup navigates away (to prevent duplicate listeners on return)
    window.swup.hooks.once('page:view', () => {
        document.removeEventListener('GlobalTimerTick', updateDisplay);
    });

    // Focus Mode Toggle
    let focusMode = false;
    document.getElementById('focusToggle').addEventListener('click', (e) => {
        focusMode = !focusMode;
        if (focusMode) {
            document.body.classList.add('focus-mode-active');
            e.target.textContent = '◈ Disable Focus Mode';
            e.target.classList.replace('btn-purple', 'btn-cyan');
            document.getElementById('room-glow').style.opacity = '0.05'; // dim lights further
        } else {
            document.body.classList.remove('focus-mode-active');
            e.target.textContent = '◈ Enable Focus Mode';
            e.target.classList.replace('btn-cyan', 'btn-purple');
            document.getElementById('room-glow').style.opacity = '0.15';
        }
    });

    // --- File Sharing Logic ---
    const tableId = {{ $table->id }};
    const fileList = document.getElementById('file-list');
    const fileUpload = document.getElementById('file-upload');
    const uploadProgress = document.getElementById('upload-progress');
    
    // Fetch and render files
    async function fetchFiles() {
        try {
            const res = await fetch(`/study/${tableId}/files`);
            const files = await res.json();
            
            if (files.length === 0) {
                fileList.innerHTML = '<li style="color: var(--text-dim); font-style: italic; text-align: center; margin-top: 20px;">No documents shared yet.</li>';
                return;
            }
            
            let html = '';
            files.forEach(file => {
                const sizeKB = (file.file_size / 1024).toFixed(1);
                html += `
                    <li style="display: flex; align-items: center; justify-content: space-between; padding: 12px; background: rgba(255,255,255,0.02); border-radius: 4px; border-left: 3px solid var(--cyan);">
                        <div style="display: flex; flex-direction: column;">
                            <a href="${file.file_path}" target="_blank" style="font-family: var(--font-mono); color: var(--cyan); text-decoration: none; font-weight: bold; margin-bottom: 5px;">${file.file_name}</a>
                            <span style="font-size: 0.75rem; color: var(--text-dim); font-family: var(--font-mono);">Uploaded by ${file.user ? file.user.name : 'Unknown'} | ${sizeKB} KB</span>
                        </div>
                        <a href="${file.file_path}" download class="btn btn-solid" style="padding: 5px 10px; font-size: 0.8rem;">↓ Download</a>
                    </li>
                `;
            });
            
            if (fileList.dataset.htmlCache !== html) {
                fileList.innerHTML = html;
                fileList.dataset.htmlCache = html;
            }
        } catch (e) {
            console.error('Error fetching files', e);
        }
    }

    // Handle file upload
    fileUpload.addEventListener('change', async function() {
        const file = this.files[0];
        if (!file) return;

        if (file.size > 10 * 1024 * 1024) {
            alert('File is too large! Maximum 10MB allowed.');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        uploadProgress.style.display = 'block';
        
        try {
            const res = await fetch(`/study/${tableId}/files`, {
                method: 'POST',
                body: formData
            });
            
            if (res.ok) {
                fetchFiles();
            } else {
                alert('File upload failed.');
            }
        } catch (e) {
            alert('File upload failed.');
        } finally {
            uploadProgress.style.display = 'none';
            this.value = ''; // Reset input
        }
    });

    // Start polling every 3 seconds for synchronization
    fetchFiles();
    const filePollInterval = setInterval(fetchFiles, 3000);

    // --- Table Comms Chat Logic ---
    const chatBox = document.getElementById('table-chat-box');
    const chatInput = document.getElementById('table-chat-input');
    const chatSendBtn = document.getElementById('table-chat-send');
    
    async function fetchChat() {
        try {
            const res = await fetch(`/study/${tableId}/messages`);
            const messages = await res.json();
            
            let html = '<div style="color:var(--text-dim); font-size:0.8rem; margin-bottom:10px; text-align: center;">[System] Table Comms initialized.</div>';
            messages.forEach(msg => {
                const color = msg.user && msg.user.theme_color === 'pink' ? 'var(--pink)' : (msg.user && msg.user.theme_color === 'purple' ? 'var(--purple)' : 'var(--cyan)');
                const userName = msg.user ? msg.user.name : 'Unknown';
                html += `<div style="font-size:0.85rem; margin-bottom:8px; line-height: 1.4;"><strong style="color:${color}">${userName}:</strong> ${msg.content}</div>`;
            });
            
            if (chatBox.dataset.htmlCache !== html) {
                const isScrolledToBottom = chatBox.scrollHeight - chatBox.clientHeight <= chatBox.scrollTop + 20;
                chatBox.innerHTML = html;
                chatBox.dataset.htmlCache = html;
                if (isScrolledToBottom) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            }
        } catch(e) {}
    }

    chatSendBtn.addEventListener('click', async () => {
        const text = chatInput.value.trim();
        if (!text) return;
        
        chatInput.value = '';
        try {
            const response = await fetch("{{ route('cafe.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ content: text, room: 'table_' + tableId })
            });
            if (response.status === 429) {
                alert("RATE LIMIT EXCEEDED: Please slow down your messages.");
                return;
            }
            fetchChat();
            setTimeout(() => chatBox.scrollTop = chatBox.scrollHeight, 100);
        } catch(e) {}
    });

    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') chatSendBtn.click();
    });

    fetchChat();
    const chatPollInterval = setInterval(fetchChat, 2000);

    // --- Active Users Logic ---
    const activeUsersList = document.getElementById('active-users-list');
    async function fetchUsers() {
        try {
            const res = await fetch(`/study/${tableId}/users`);
            const users = await res.json();
            
            let html = '';
            users.forEach(user => {
                const color = user.theme_color === 'pink' ? 'var(--pink)' : (user.theme_color === 'purple' ? 'var(--purple)' : 'var(--cyan)');
                html += `
                    <li style="display:flex; align-items:center; gap:10px;">
                        <div style="width:10px;height:10px;border-radius:50%;background:${color};box-shadow:0 0 5px ${color};"></div>
                        <span style="font-family:var(--font-mono); font-size:0.9rem; ${user.id === {{ Auth::id() ?? 0 }} ? 'font-weight:bold;' : ''}">${user.name}</span>
                    </li>
                `;
            });
            
            if (activeUsersList.dataset.htmlCache !== html) {
                activeUsersList.innerHTML = html;
                activeUsersList.dataset.htmlCache = html;
            }
        } catch(e) {}
    }
    
    fetchUsers();
    const userPollInterval = setInterval(fetchUsers, 5000);

    // Cleanup intervals on page leave
    window.swup.hooks.once('page:view', () => {
        clearInterval(filePollInterval);
        clearInterval(chatPollInterval);
        clearInterval(userPollInterval);
    });
})();
</script>
@endpush
@endsection
