@extends('layouts.app')

@section('title', 'Access Denied — Cyber Café 2099')

@section('content')
<div class="full-section">
<section style="padding-top: 150px; display: flex; align-items: center; justify-content: center;">
    <div style="background: rgba(0,0,0,0.8); border: 1px solid var(--pink); padding: 40px; border-radius: 8px; width: 100%; max-width: 450px; text-align: center; box-shadow: 0 0 30px rgba(255,0,128,0.2);">
        
        <div style="font-size: 3rem; margin-bottom: 10px;">🔒</div>
        <h2 style="color: var(--pink); font-family: var(--font-mono); margin-bottom: 5px;">ACCESS DENIED</h2>
        <div style="color: var(--text-dim); font-family: var(--font-mono); font-size: 0.85rem; margin-bottom: 30px;">
            NODE: {{ $table->name }} <br>
            Requires security clearance.
        </div>

        @if(session('error'))
            <div style="color: var(--pink); font-family: var(--font-mono); font-size: 0.8rem; margin-bottom: 15px; background: rgba(255,0,128,0.1); padding: 10px; border: 1px dashed var(--pink);">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('study.password', $table->id) }}" method="POST">
            @csrf
            <input type="password" name="password" required autofocus
                   placeholder="Enter Passcode..." 
                   style="width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px dashed var(--cyan); color: white; text-align: center; font-family: var(--font-mono); margin-bottom: 20px; outline: none;">
            
            <button type="submit" class="btn btn-cyan" style="width: 100%; justify-content: center; padding: 12px;">DECRYPT NODE</button>
        </form>
        
        <a href="{{ route('study.index') }}" style="display: block; margin-top: 20px; color: var(--text-dim); font-family: var(--font-mono); font-size: 0.8rem; text-decoration: none; transition: 0.3s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='var(--text-dim)'">
            [ RETURN TO LOBBY ]
        </a>
    </div>
</section>
</div>
@endsection
