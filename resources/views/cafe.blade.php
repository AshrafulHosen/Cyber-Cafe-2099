@extends('layouts.app')

@section('title', 'Global Café Chat — Cyber Café 2099')

@push('styles')
<style>
    .cafe-chat-container {
        display: flex;
        flex-direction: column;
        height: 60vh;
        max-height: 600px;
        background: rgba(0,0,0,0.6);
        border: 1px solid var(--purple);
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(138,43,226,0.2);
        overflow: hidden;
        margin-top: 30px;
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .chat-messages::-webkit-scrollbar {
        width: 8px;
    }
    .chat-messages::-webkit-scrollbar-thumb {
        background: var(--purple);
        border-radius: 4px;
    }
    .chat-msg {
        display: flex;
        flex-direction: column;
        max-width: 80%;
    }
    .chat-msg.other {
        align-self: flex-start;
    }
    .chat-msg.self {
        align-self: flex-end;
        align-items: flex-end;
    }
    .msg-author {
        font-family: var(--font-mono);
        font-size: 0.75rem;
        color: var(--text-dim);
        margin-bottom: 5px;
    }
    .chat-msg.other .msg-author {
        color: var(--cyan);
    }
    .chat-msg.self .msg-author {
        color: var(--pink);
    }
    .msg-bubble {
        padding: 12px 18px;
        border-radius: 8px;
        background: rgba(255,255,255,0.03);
        border-left: 2px solid transparent;
        font-size: 0.9rem;
        line-height: 1.4;
    }
    .chat-msg.other .msg-bubble {
        border-left-color: var(--cyan);
    }
    .chat-msg.self .msg-bubble {
        border-left: none;
        border-right: 2px solid var(--pink);
        background: rgba(255, 0, 128, 0.05);
    }
    .chat-input-area {
        display: flex;
        padding: 20px;
        background: rgba(0,0,0,0.8);
        border-top: 1px solid rgba(255,255,255,0.1);
        gap: 15px;
    }
    .chat-input-area input {
        flex: 1;
        background: transparent;
        border: 1px dashed var(--cyan);
        color: white;
        padding: 12px 20px;
        font-family: var(--font-mono);
        border-radius: 4px;
        outline: none;
        transition: border 0.3s;
    }
    .chat-input-area input:focus {
        border-color: var(--pink);
        box-shadow: 0 0 10px rgba(255, 0, 128, 0.2);
    }
    .login-prompt {
        padding: 20px;
        text-align: center;
        background: rgba(0,0,0,0.8);
        border-top: 1px dashed rgba(255,255,255,0.1);
    }
</style>
@endpush

@section('content')
<div class="full-section">
<section style="padding-top: 130px; max-width: 900px; margin: 0 auto;">
  <div class="section-label">// public_lounge</div>
  <h2 class="section-title">Café <span style="color:var(--pink)">Room</span></h2>
  <p class="section-sub">The global hub. Connect with other travelers currently roaming the neon city.</p>
  
  <div class="cafe-chat-container">
      <div class="chat-messages" id="messages">
          @forelse ($messages as $message)
              @php
                  $isSelf = Auth::check() && Auth::id() === $message->user_id;
              @endphp
              <div class="chat-msg {{ $isSelf ? 'self' : 'other' }}">
                  <div class="msg-author">
                      <a href="{{ route('profile.public', $message->user_id) }}" style="color: inherit; text-decoration: none; border-bottom: 1px dashed transparent; transition: border-color 0.3s;" onmouseover="this.style.borderBottomColor='currentColor'" onmouseout="this.style.borderBottomColor='transparent'">
                          {{ $message->user->name ?? 'Unknown' }}
                      </a>
                  </div>
                  <div class="msg-bubble">{{ $message->content }}</div>
              </div>
          @empty
              <div style="text-align: center; color: var(--text-dim); margin-top: auto; margin-bottom: auto; font-style: italic;">
                  The lounge is empty... Be the first to transmit a message.
              </div>
          @endforelse
      </div>

      @auth
          <div class="chat-input-area">
              <input type="text" id="messageInput" placeholder="Transmit a hologram message..." autocomplete="off">
              <button id="sendBtn" class="btn btn-cyan">SEND ⚡</button>
          </div>
          <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf-token">
      @else
          <div class="login-prompt">
              <span style="color: var(--text-dim); margin-right: 15px;">Authentication required to transmit.</span>
              <a href="{{ route('login') }}" class="btn btn-purple">LOGIN</a>
          </div>
      @endauth
  </div>
</section>
</div>

@push('scripts')
<script>
(function() {
    const messagesDiv = document.getElementById('messages');
    if (messagesDiv) {
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    const input = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    
    if (input && sendBtn) {
        const sendMessage = async () => {
            const csrf = document.getElementById('csrf-token')?.content;
            const text = input.value.trim();
            if (text === '') return;

            // Optimistic UI Append
            const msgContainer = document.createElement('div');
            msgContainer.className = 'chat-msg self';
            msgContainer.innerHTML = `
                <div class="msg-author">You</div>
                <div class="msg-bubble">${text}</div>
            `;
            
            // Remove empty placeholder if it exists
            if (messagesDiv.children.length === 1 && messagesDiv.children[0].style.textAlign === 'center') {
                messagesDiv.innerHTML = '';
            }
            
            messagesDiv.appendChild(msgContainer);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
            input.value = '';

            try {
                await fetch("{{ route('cafe.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ content: text })
                });
            } catch (err) {
                console.error('Chat error:', err);
            }
        };

        sendBtn.addEventListener('click', sendMessage);
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                sendMessage();
            }
        });
    }
})();
</script>
@endpush
@endsection
