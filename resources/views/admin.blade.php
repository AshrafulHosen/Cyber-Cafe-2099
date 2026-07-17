@extends('layouts.app')

@section('title', 'Admin Dashboard — Cyber Café 2099')

@section('content')
<div class="full-section">
    <section style="padding-top: 120px;">
        <div class="section-label">system_admin</div>
        <h2 class="section-title">Backend <span style="color:var(--cyan)">Dashboard</span></h2>
        
        @if(session('success'))
            <div style="background: rgba(0, 255, 136, 0.1); border: 1px solid #0f8; padding: 15px; border-radius: 4px; color: #0f8; text-align: center; font-family: var(--font-mono); margin-bottom: 30px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: rgba(255, 45, 120, 0.1); border: 1px solid var(--pink); padding: 15px; border-radius: 4px; color: var(--pink); text-align: center; font-family: var(--font-mono); margin-bottom: 30px;">
                {{ session('error') }}
            </div>
        @endif

        <!-- STATS -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px;">
            <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--cyan); padding: 20px; border-radius: 8px; text-align: center;">
                <div style="font-family: var(--font-mono); color: var(--cyan); font-size: 2rem;">{{ $stats['total_users'] }}</div>
                <div style="color: var(--text-dim); font-size: 0.8rem; text-transform: uppercase; margin-top: 5px;">Total Users</div>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--purple); padding: 20px; border-radius: 8px; text-align: center;">
                <div style="font-family: var(--font-mono); color: var(--purple); font-size: 2rem;">{{ $stats['total_tables'] }}</div>
                <div style="color: var(--text-dim); font-size: 0.8rem; text-transform: uppercase; margin-top: 5px;">Total Tables</div>
            </div>
            <div style="background: rgba(255,255,255,0.02); border: 1px solid #0f8; padding: 20px; border-radius: 8px; text-align: center;">
                <div style="font-family: var(--font-mono); color: #0f8; font-size: 2rem;">{{ $stats['active_sessions'] }}</div>
                <div style="color: var(--text-dim); font-size: 0.8rem; text-transform: uppercase; margin-top: 5px;">Active Sessions</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
            <!-- ALL TABLES -->
            <div>
                <h3 style="color: white; font-family: var(--font-mono); margin-bottom: 20px; border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 10px;">TABLE MANAGEMENT</h3>
                
                <form action="{{ route('admin.tables.store') }}" method="POST" style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    @csrf
                    <div style="margin-bottom: 10px;">
                        <input type="text" name="name" placeholder="Table Name" required style="width: 100%; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid var(--cyan); color: white;">
                    </div>
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <select name="activity" required style="flex: 1; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid var(--cyan); color: white;">
                            <option value="studying">Studying</option>
                            <option value="coding">Coding</option>
                            <option value="gaming">Gaming</option>
                            <option value="chatting">Chatting</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <select name="color" required style="flex: 1; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid var(--cyan); color: white;">
                            <option value="blue">Blue</option>
                            <option value="purple">Purple</option>
                            <option value="green">Green</option>
                            <option value="red">Red</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-cyan" style="width: 100%; justify-content: center;">+ CREATE GLOBAL TABLE</button>
                </form>

                <div style="background: rgba(255,255,255,0.02); border-radius: 8px; padding: 10px; max-height: 500px; overflow-y: auto;">
                    @foreach($tables as $table)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div>
                                <div style="color: white; font-weight: bold;">
                                    {{ $table->name }}
                                    @if($table->password) <span style="font-size: 0.8rem;" title="Password Protected">🔒</span> @endif
                                </div>
                                <div style="color: var(--text-dim); font-size: 0.8rem;">
                                    {{ ucfirst($table->activity) }} · {{ ucfirst($table->color) }}
                                    <br>
                                    @if($table->user_id)
                                        Owner: <span style="color: var(--cyan);">{{ $table->owner->name ?? 'Unknown' }}</span>
                                    @else
                                        <span style="color: var(--purple);">[GLOBAL TABLE]</span>
                                    @endif
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <button type="button" onclick="document.getElementById('edit-form-{{ $table->id }}').style.display = document.getElementById('edit-form-{{ $table->id }}').style.display === 'none' ? 'block' : 'none'" style="background: transparent; border: 1px solid var(--cyan); color: var(--cyan); padding: 5px 10px; border-radius: 4px; cursor: pointer; font-family: var(--font-mono); font-size: 0.8rem;">EDIT</button>
                                <form action="{{ route('admin.tables.delete', $table->id) }}" method="POST" onsubmit="return confirm('Delete this table entirely?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: transparent; border: 1px solid var(--pink); color: var(--pink); padding: 5px 10px; border-radius: 4px; cursor: pointer; font-family: var(--font-mono); font-size: 0.8rem;">DELETE</button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Inline Edit Form -->
                        <div id="edit-form-{{ $table->id }}" style="display: none; padding: 15px; background: rgba(0,0,0,0.3); border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <form action="{{ route('admin.tables.update', $table->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                                    <input type="text" name="name" value="{{ $table->name }}" required style="flex: 2; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid var(--cyan); color: white;">
                                    <select name="activity" required style="flex: 1; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid var(--cyan); color: white;">
                                        <option value="studying" {{ $table->activity == 'studying' ? 'selected' : '' }}>Studying</option>
                                        <option value="coding" {{ $table->activity == 'coding' ? 'selected' : '' }}>Coding</option>
                                        <option value="gaming" {{ $table->activity == 'gaming' ? 'selected' : '' }}>Gaming</option>
                                        <option value="chatting" {{ $table->activity == 'chatting' ? 'selected' : '' }}>Chatting</option>
                                        <option value="inactive" {{ $table->activity == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <select name="color" required style="flex: 1; padding: 10px; background: rgba(0,0,0,0.5); border: 1px solid var(--cyan); color: white;">
                                        <option value="blue" {{ $table->color == 'blue' ? 'selected' : '' }}>Blue</option>
                                        <option value="purple" {{ $table->color == 'purple' ? 'selected' : '' }}>Purple</option>
                                        <option value="green" {{ $table->color == 'green' ? 'selected' : '' }}>Green</option>
                                        <option value="red" {{ $table->color == 'red' ? 'selected' : '' }}>Red</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-cyan" style="width: 100%; justify-content: center; padding: 8px;">SAVE CHANGES</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- USERS -->
            <div>
                <h3 style="color: white; font-family: var(--font-mono); margin-bottom: 20px; border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 10px;">USER MODERATION</h3>
                
                <div style="background: rgba(255,255,255,0.02); border-radius: 8px; padding: 10px; max-height: 500px; overflow-y: auto;">
                    @foreach($users as $user)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div>
                                <div style="color: white; font-weight: bold;">
                                    {{ $user->name }} 
                                    @if($user->is_admin) <span style="color: var(--cyan); font-size: 0.7rem; border: 1px solid var(--cyan); padding: 2px 4px; border-radius: 3px; margin-left: 5px;">ADMIN</span> @endif
                                </div>
                                <div style="color: var(--text-dim); font-size: 0.8rem;">{{ $user->email }} · {{ $user->cyber_credits }} CC</div>
                            </div>
                            @if(!$user->is_admin)
                                <form action="{{ route('admin.users.ban', $user->id) }}" method="POST">
                                    @csrf
                                    @if($user->is_banned)
                                        <button type="submit" style="background: rgba(0, 255, 136, 0.1); border: 1px solid #0f8; color: #0f8; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-family: var(--font-mono); font-size: 0.8rem;">UNBAN</button>
                                    @else
                                        <button type="submit" style="background: rgba(255, 45, 120, 0.1); border: 1px solid var(--pink); color: var(--pink); padding: 5px 10px; border-radius: 4px; cursor: pointer; font-family: var(--font-mono); font-size: 0.8rem;">BAN</button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </section>
</div>
@endsection
