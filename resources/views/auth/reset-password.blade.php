<x-guest-layout>
    @if ($errors->any())
        <div class="error-message">
            <i class="fas fa-exclamation-circle" style="flex-shrink: 0; margin-top: 0.15rem;"></i>
            <div>
                <strong>Error</strong>
                <ul style="margin: 0.25rem 0 0; padding-left: 1.25rem; font-size: 0.85rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <h2 style="font-size: 1.5rem; margin: 0 0 0.5rem; color: #1e293b; font-weight: 800;">Create New Password</h2>
    <p style="color: #64748b; margin: 0 0 1.5rem; font-size: 0.9rem;">Enter your email and a new password to reset your account.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">
                <i class="fas fa-envelope" style="color: var(--accent); margin-right: 0.4rem;"></i>
                Email Address
            </label>
            <input 
                id="email" 
                class="form-control" 
                type="email" 
                name="email" 
                value="{{ old('email', $request->email) }}" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="you@example.com"
            />
            @error('email')
                <div class="form-error">
                    <i class="fas fa-times-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">
                <i class="fas fa-lock" style="color: var(--accent); margin-right: 0.4rem;"></i>
                New Password
            </label>
            <input 
                id="password" 
                class="form-control"
                type="password"
                name="password"
                required 
                autocomplete="new-password"
                placeholder="••••••••"
            />
            @error('password')
                <div class="form-error">
                    <i class="fas fa-times-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group" style="margin-bottom: 1.75rem;">
            <label for="password_confirmation" class="form-label">
                <i class="fas fa-lock-check" style="color: var(--accent); margin-right: 0.4rem;"></i>
                Confirm Password
            </label>
            <input 
                id="password_confirmation" 
                class="form-control"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••"
            />
            @error('password_confirmation')
                <div class="form-error">
                    <i class="fas fa-times-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Reset Button -->
        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.25rem;">
            <i class="fas fa-check-circle"></i>
            Reset Password
        </button>

        <!-- Back to Login -->
        <div class="auth-footer" style="border-top: 1px solid #f1f5f9;">
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left" style="margin-right: 0.3rem;"></i>
                Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>
