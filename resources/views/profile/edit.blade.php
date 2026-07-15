@extends('layouts.app')

@section('title', 'Profile Settings — Cyber Café 2099')

@push('styles')
<style>
    .settings-panel {
        background: rgba(0,0,0,0.6);
        border: 1px solid var(--purple);
        border-radius: 8px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 0 20px rgba(138,43,226,0.1);
    }
    .settings-panel h3 {
        color: var(--cyan);
        font-family: var(--font-mono);
        margin-bottom: 20px;
        border-bottom: 1px dashed rgba(255,255,255,0.1);
        padding-bottom: 10px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        color: var(--text-dim);
        font-family: var(--font-mono);
        font-size: 0.8rem;
        margin-bottom: 8px;
        text-transform: uppercase;
    }
    .form-group input, .form-group textarea, .form-group select {
        width: 100%;
        background: rgba(255,255,255,0.02);
        border: 1px dashed var(--cyan);
        color: white;
        padding: 12px;
        border-radius: 4px;
        outline: none;
        transition: border 0.3s;
    }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
        border-color: var(--pink);
    }
    .form-group select option {
        background: var(--bg2);
        color: white;
    }
    .form-error {
        color: var(--pink);
        font-size: 0.8rem;
        margin-top: 5px;
    }
</style>
@endpush

@section('content')
<div class="full-section">
<section style="padding-top: 130px; max-width: 800px; margin: 0 auto;">
  <div class="section-label">// sys_config</div>
  <h2 class="section-title">Profile <span style="color:var(--purple)">Settings</span></h2>
  <a href="{{ route('profile') }}" style="color: var(--text-dim); font-size: 0.85rem; text-decoration: none;">← Return to Dossier</a>
  
  <div style="margin-top: 30px;">
      
      <div class="settings-panel">
          <h3>// Update Identity</h3>
          <form method="post" action="{{ route('profile.update') }}">
              @csrf
              @method('patch')
              <div class="form-group">
                  <label for="name">Alias (Name)</label>
                  <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                  @error('name')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group">
                  <label for="email">Com-Link (Email)</label>
                  <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                  @error('email')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group">
                  <label for="bio">Status / Bio</label>
                  <textarea id="bio" name="bio" rows="3">{{ old('bio', $user->bio) }}</textarea>
                  @error('bio')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group">
                  <label for="theme_color">Theme Color</label>
                  <select id="theme_color" name="theme_color">
                      <option value="cyan" {{ old('theme_color', $user->theme_color) == 'cyan' ? 'selected' : '' }}>Cyan</option>
                      <option value="pink" {{ old('theme_color', $user->theme_color) == 'pink' ? 'selected' : '' }}>Pink</option>
                      <option value="purple" {{ old('theme_color', $user->theme_color) == 'purple' ? 'selected' : '' }}>Purple</option>
                  </select>
                  @error('theme_color')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <button type="submit" class="btn btn-cyan">Save Identity</button>
              
              @if (session('status') === 'profile-updated')
                  <span style="color: #0f8; font-family: var(--font-mono); font-size: 0.8rem; margin-left: 15px;">Updated Successfully.</span>
              @endif
          </form>
      </div>

      <div class="settings-panel">
          <h3>// Security Protocol</h3>
          <form method="post" action="{{ route('password.update') }}">
              @csrf
              @method('put')
              <div class="form-group">
                  <label for="current_password">Current Password</label>
                  <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
                  @error('current_password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group">
                  <label for="password">New Password</label>
                  <input type="password" id="password" name="password" required autocomplete="new-password">
                  @error('password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group">
                  <label for="password_confirmation">Confirm Password</label>
                  <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
                  @error('password_confirmation', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <button type="submit" class="btn btn-purple">Update Security</button>
              
              @if (session('status') === 'password-updated')
                  <span style="color: #0f8; font-family: var(--font-mono); font-size: 0.8rem; margin-left: 15px;">Security Updated.</span>
              @endif
          </form>
      </div>

  </div>
</section>
</div>
@endsection
