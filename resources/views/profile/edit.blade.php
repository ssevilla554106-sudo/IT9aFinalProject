@extends('layouts.app')

@section('title', 'Edit Profile')

@push('styles')
<style>
.profile-page { padding: 3rem 0; }
.profile-header { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
.profile-header h1 { margin: 0; font-size: 2rem; font-weight: 800; }
.profile-header p { margin: 0; color: #94a3b8; }
.profile-card { background: white; border-radius: 20px; box-shadow: 0 2px 20px rgba(0,0,0,0.06); padding: 2rem; margin-bottom: 1.5rem; }
.profile-grid { display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; }
@media (max-width: 880px) { .profile-grid { grid-template-columns: 1fr; } }
.profile-sidebar { background: white; border-radius: 20px; padding: 1.75rem; box-shadow: 0 2px 20px rgba(0,0,0,0.06); }
.profile-avatar { width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, #e94560, #0f3460); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 800; margin-bottom: 1rem; }
.profile-name { font-size: 1.1rem; font-weight: 700; margin-bottom: 0.25rem; }
.profile-email { color: #64748b; font-size: 0.88rem; word-break: break-all; }
.profile-meta { margin-top: 1.25rem; display: grid; gap: 0.85rem; }
.profile-meta-item { display: flex; justify-content: space-between; color: #475569; font-size: 0.9rem; }
.profile-meta-item .label { color: #94a3b8; }
.form-section-title { font-size: 1rem; font-weight: 700; margin-bottom: 1.25rem; color: #1e293b; display: flex; align-items: center; gap: 0.65rem; }
.form-footer { display: flex; justify-content: flex-end; gap: 1rem; flex-wrap: wrap; margin-top: 1.25rem; }
</style>
@endpush

@section('content')
<div class="container profile-page">
    <div class="profile-header">
        <div>
            <h1><i class="fas fa-user-circle" style="color:#e94560;"></i> Edit Profile</h1>
            <p>Update your identity and account details.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    @if(session('status') === 'profile-updated')
        <div class="alert alert-success" style="margin-top:1.5rem;"><i class="fas fa-check-circle"></i> Profile updated successfully.</div>
    @endif

    <div class="profile-grid" style="margin-top:1.75rem;">
        <div>
            <div class="profile-card">
                <div class="form-section-title"><i class="fas fa-id-badge" style="color:#e94560;"></i> Identity</div>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="form-group" style="margin-bottom:1rem;">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required autofocus>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:1rem;">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:1rem;">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Optional">
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="{{ route('events.my-events') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>

            <div class="profile-card">
                <div class="form-section-title"><i class="fas fa-lock" style="color:#e94560;"></i> Update Password</div>

                @if(session('status') === 'password-updated')
                    <div class="alert alert-success" style="margin-bottom:1.25rem;"><i class="fas fa-check-circle"></i> Password updated successfully.</div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group" style="margin-bottom:1rem;">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" autocomplete="current-password" required>
                        @error('current_password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:1rem;">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password" required>
                        @error('password', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:1rem;">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
                        @error('password_confirmation', 'updatePassword')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Change Password</button>
                    </div>
                </form>
            </div>
        </div>

        <aside class="profile-sidebar">
            <div class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="profile-name">{{ auth()->user()->name }}</div>
            <div class="profile-email">{{ auth()->user()->email }}</div>

            <div class="profile-meta">
                <div class="profile-meta-item"><span class="label">Member since</span><span>{{ auth()->user()->created_at->format('M Y') }}</span></div>
                <div class="profile-meta-item"><span class="label">Events created</span><span>{{ auth()->user()->events()->count() }}</span></div>
                <div class="profile-meta-item"><span class="label">Registrations</span><span>{{ auth()->user()->registrations()->count() }}</span></div>
            </div>
        </aside>
    </div>
</div>
@endsection
