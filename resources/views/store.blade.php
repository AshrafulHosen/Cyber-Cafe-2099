@extends('layouts.app')

@section('title', 'Cyber Store — Cyber Café 2099')

@section('content')
<div class="full-section">
<section style="padding-top: 120px;">
  <div style="text-align: center; margin-bottom: 40px;">
    <div class="section-label">inventory.market</div>
    <h2 class="section-title">Cyber <span style="color:var(--cyan)">Store</span></h2>
    <p class="section-sub" style="margin-inline: auto;">Upgrade your neuro-rig and augment your experience.</p>
    
    <div style="display: inline-block; margin-top: 20px; padding: 10px 20px; border: 1px solid var(--pink); border-radius: 4px; background: rgba(255, 45, 120, 0.1);">
      <span style="font-family: var(--font-mono); font-size: 0.9rem; color: var(--pink);">YOUR BALANCE:</span>
      <span id="credits-balance" style="font-size: 1.5rem; font-weight: 700; color: #fff; margin-left: 10px;">{{ number_format($user->cyber_credits) }}</span> <span style="color:var(--text-dim);">CC</span>
    </div>
  </div>

  <div style="max-width: 1000px; margin-inline: auto; padding: 0 20px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 30px;">
      
      @foreach($items as $item)
        @php
            $isOwned = in_array($item->id, $ownedItemIds);
        @endphp
        
        <div class="store-item" style="background: rgba(10, 10, 15, 0.8); border: 1px solid var(--dark-border); border-radius: 8px; padding: 25px; text-align: center; transition: 0.3s; position: relative; {{ $isOwned ? 'opacity: 0.6;' : '' }}">
          
          <div style="font-size: 3.5rem; margin-bottom: 15px;">{{ $item->icon }}</div>
          <h3 style="font-family: var(--font-head); font-size: 1.2rem; margin-bottom: 5px;">{{ $item->name }}</h3>
          <div style="font-family: var(--font-mono); font-size: 0.75rem; color: var(--cyan); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">[{{ $item->type }}]</div>
          
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <div style="font-family: var(--font-mono); font-size: 1.1rem; color: var(--pink);">
              {{ number_format($item->price) }} <span style="font-size: 0.7rem; color: var(--text-dim);">CC</span>
            </div>
            
            @if($isOwned)
              <button disabled style="background: transparent; border: 1px solid var(--text-dim); color: var(--text-dim); padding: 8px 16px; border-radius: 4px; font-family: var(--font-mono); font-size: 0.8rem; cursor: not-allowed;">
                OWNED
              </button>
            @else
              <button class="buy-btn" data-id="{{ $item->id }}" data-price="{{ $item->price }}" style="background: var(--pink); border: none; color: #000; font-weight: bold; padding: 8px 16px; border-radius: 4px; font-family: var(--font-mono); font-size: 0.8rem; cursor: pointer; transition: 0.2s;">
                ACQUIRE
              </button>
            @endif
          </div>
        </div>
      @endforeach
      
    </div>
  </div>
</section>
</div>

@push('styles')
<style>
  .store-item:hover {
    border-color: var(--pink);
    box-shadow: 0 0 20px rgba(255, 45, 120, 0.2);
    transform: translateY(-5px);
  }
  .buy-btn:hover {
    background: #fff !important;
    box-shadow: 0 0 15px var(--pink);
  }
</style>
@endpush

@push('scripts')
<script>
  (function() {
    const buyBtns = document.querySelectorAll('.buy-btn');
    const balanceEl = document.getElementById('credits-balance');
    
    buyBtns.forEach(btn => {
      btn.addEventListener('click', async function() {
        const itemId = this.getAttribute('data-id');
        const price = parseInt(this.getAttribute('data-price'));
        const currentBalance = parseInt(balanceEl.innerText.replace(/,/g, ''));
        
        if (currentBalance < price) {
            alert('INSUFFICIENT FUNDS. Grind more focus hours to earn cyber credits.');
            return;
        }

        const originalText = this.innerText;
        this.innerText = 'PROCESSING...';
        this.disabled = true;

        try {
          const response = await fetch("{{ route('store.purchase') }}", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
              "Accept": "application/json"
            },
            body: JSON.stringify({ item_id: itemId })
          });

          const data = await response.json();
          
          if (data.success) {
            // Update balance
            balanceEl.innerText = new Intl.NumberFormat().format(data.new_balance);
            
            // Change button to OWNED
            this.innerText = 'OWNED';
            this.style.background = 'transparent';
            this.style.border = '1px solid var(--text-dim)';
            this.style.color = 'var(--text-dim)';
            this.style.cursor = 'not-allowed';
            this.classList.remove('buy-btn');
            
            // Dim the card
            this.closest('.store-item').style.opacity = '0.6';
          } else {
            alert(data.message || 'Transaction failed.');
            this.innerText = originalText;
            this.disabled = false;
          }
        } catch (err) {
          console.error('Purchase error:', err);
          alert('Network error during transaction.');
          this.innerText = originalText;
          this.disabled = false;
        }
      });
    });
  })();
</script>
@endpush
@endsection
