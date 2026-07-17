@extends('layouts.app')

@section('title', 'AI Barista — Cyber Café 2099')

@section('content')
    <div class="full-section">
        <section style="padding-top: 150px;">
            <div class="section-label">nexus-7.ai</div>
            <h2 class="section-title">AI <span style="color:var(--cyan)">Barista</span></h2>
            <p class="section-sub">Your personal cyberpunk companion. Powered by neural networks, fueled by espresso.</p>

            <div
                style="display:grid;grid-template-columns:1fr;gap:40px;margin-top:0px;align-items:center; max-width: 800px; margin-inline: auto; transform: translateX(90px);">
                <div class="chat-preview">
                    <div class="chat-header">
                        <div class="ai-avatar">🤖</div>
                        <div>
                            <div class="ai-name">NEXUS-7 BARISTA</div>
                            <div class="ai-status">● Online — Neural Link Active</div>
                        </div>
                    </div>

                    <div class="chat-messages" id="barista-chat"
                        style="min-height: 400px; display: flex; flex-direction: column; overflow-y: auto;">
                        <div
                            style="text-align: center; margin-bottom: 20px; color: var(--text-dim); font-family: var(--font-mono); font-size: 0.8rem;">
                            [DATABASE CONNECTION ESTABLISHED]<br>
                            Neural core is online. Private session started.
                        </div>

                        @if($messages->isEmpty())
                            <div class="chat-msg other">
                                <div class="msg-author" style="color:var(--pink)">Nexus-7</div>
                                <div class="msg-bubble">Welcome back, traveler. I'm brewing the finest synthetic espresso in
                                    Sector 7. What's on your mind?</div>
                            </div>
                        @else
                            @foreach($messages as $msg)
                                <div class="chat-msg {{ $msg->user_id === auth()->id() ? 'self' : 'other' }}">
                                    <div class="msg-author"
                                        style="color: {{ $msg->user_id === auth()->id() ? 'var(--cyan)' : 'var(--pink)' }}">
                                        {{ $msg->user->name ?? 'Nexus-7' }}
                                    </div>
                                    <div class="msg-bubble">{{ $msg->content }}</div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="chat-input-row">
                        <input type="text" class="chat-inp" id="barista-inp" placeholder="Transmit a secure message..." />
                        <button class="chat-send" id="barista-send">SEND</button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            (function () {
                const messagesDiv = document.getElementById('barista-chat');
                const input = document.getElementById('barista-inp');
                const sendBtn = document.getElementById('barista-send');
                const userId = {{ auth()->id() }};
                const userName = "{{ auth()->user()->name }}";
                const roomName = 'barista_' + userId;

                // Auto-scroll to bottom initially
                if (messagesDiv) {
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                }

                async function sendMessage() {
                    const text = input.value.trim();
                    if (!text) return;

                    // Optimistic render for user
                    const myMsg = document.createElement('div');
                    myMsg.className = 'chat-msg self';
                    myMsg.innerHTML = `<div class="msg-author" style="color:var(--cyan)">${userName}</div><div class="msg-bubble">${text}</div>`;
                    messagesDiv.appendChild(myMsg);
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    input.value = '';

                    // Optimistic typing indicator for AI
                    let typingDiv = document.createElement('div');
                    typingDiv.className = 'chat-msg other';
                    typingDiv.innerHTML = `<div class="msg-author" style="color:var(--pink)">Nexus-7</div><div class="msg-bubble" style="font-style: italic; color:var(--text-dim);">[Processing neural response...]</div>`;
                    messagesDiv.appendChild(typingDiv);
                    messagesDiv.scrollTop = messagesDiv.scrollHeight;

                    try {
                        const response = await fetch("{{ route('cafe.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Accept": "application/json"
                            },
                            body: JSON.stringify({
                                content: text,
                                room: roomName
                            })
                        });

                        if (response.status === 429) {
                            if (typingDiv) typingDiv.remove();
                            alert("RATE LIMIT EXCEEDED: Please slow down your messages.");
                            return;
                        }

                        const data = await response.json();

                        if (typingDiv) {
                            typingDiv.remove();
                        }

                        if (data.success && data.ai_reply) {
                            const aiMsg = document.createElement('div');
                            aiMsg.className = 'chat-msg other';
                            aiMsg.innerHTML = `<div class="msg-author" style="color:var(--pink)">Nexus-7</div><div class="msg-bubble">${data.ai_reply.content}</div>`;
                            messagesDiv.appendChild(aiMsg);
                            messagesDiv.scrollTop = messagesDiv.scrollHeight;
                        }
                    } catch (err) {
                        console.error('Chat error:', err);
                        if (typingDiv) typingDiv.remove();
                    }
                }

                if (sendBtn) {
                    sendBtn.addEventListener('click', sendMessage);
                }

                if (input) {
                    input.addEventListener('keypress', function (e) {
                        if (e.key === 'Enter') sendMessage();
                    });
                }
            })();
        </script>
    @endpush
@endsection