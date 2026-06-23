@extends('layouts.app')

@section('title', 'Global Chat — Cyber Café 2099')

@section('content')

  <section id="chat">
    <div class="section-title">
      <h2>Global Café Chat</h2>
      <p>Meet travelers from the neon city.</p>
    </div>

    <div class="chat-box">
      <div class="messages" id="messages">

        {{-- Load existing messages from DB (passed by controller) --}}
        @forelse ($messages as $message)
          <div class="msg">
            <strong>{{ $message->user->name ?? 'Unknown' }}:</strong>
            {{ $message->content }}
          </div>
        @empty
          <div class="msg">
            <strong>AI Barista:</strong> The café is quiet... be the first to speak ☕
          </div>
        @endforelse

      </div>

      {{-- Chat input: only show for logged-in users --}}
      @auth
        <div class="chat-input">
          {{-- We use a form POST to save message to DB --}}
          <input type="text" id="messageInput" placeholder="Send a hologram message..."
                 onkeydown="if(event.key==='Enter') sendMessage()">
          <button onclick="sendMessage()">Send</button>
        </div>

        {{-- CSRF token for AJAX requests --}}
        <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf-token">
      @else
        <p style="color: var(--soft); margin-top: 15px; text-align:center;">
          <a href="{{ route('login') }}" style="color: var(--cyan);">Login</a> to join the conversation.
        </p>
      @endauth
    </div>
  </section>

@endsection

@push('scripts')
<script>
  async function sendMessage() {
    const input   = document.getElementById('messageInput');
    const messages = document.getElementById('messages');
    const csrf    = document.getElementById('csrf-token')?.content;

    if (input.value.trim() === '') return;

    // Optimistic UI: show message immediately
    const msg = document.createElement('div');
    msg.classList.add('msg');
    msg.innerHTML = `<strong>You:</strong> ${input.value}`;
    messages.appendChild(msg);
    messages.scrollTop = messages.scrollHeight;

    const userText = input.value;
    input.value = '';

    // Send to Laravel backend
    try {
      const response = await fetch("{{ route('chat.store') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ content: userText })
      });

      const data = await response.json();

      // Show AI Barista reply
      if (data.ai_reply) {
        setTimeout(() => {
          const aiReply = document.createElement('div');
          aiReply.classList.add('msg');
          aiReply.innerHTML = `<strong>AI Barista:</strong> ${data.ai_reply}`;
          messages.appendChild(aiReply);
          messages.scrollTop = messages.scrollHeight;
        }, 1000);
      }

    } catch (err) {
      console.error('Chat error:', err);
    }
  }
</script>
@endpush