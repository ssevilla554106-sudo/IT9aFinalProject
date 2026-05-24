<x-guest-layout>
    @if ($errors->any())
        <div class="error-message">
            <i class="fas fa-exclamation-circle" style="flex-shrink: 0; margin-top: 0.15rem;"></i>
            <div>
                <strong>Registration Failed</strong>
                <ul style="margin: 0.25rem 0 0; padding-left: 1.25rem; font-size: 0.85rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <h2 style="font-size: 1.5rem; margin: 0 0 0.5rem; color: #1e293b; font-weight: 800;">Create Account</h2>
    <p style="color: #64748b; margin: 0 0 1.5rem; font-size: 0.9rem;">Join us to discover and create amazing events</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">
                <i class="fas fa-user" style="color: var(--accent); margin-right: 0.4rem;"></i>
                Full Name
            </label>
            <input 
                id="name" 
                class="form-control" 
                type="text" 
                name="name" 
                value="{{ old('name') }}" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="John Doe"
            />
            @error('name')
                <div class="form-error">
                    <i class="fas fa-times-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

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
                value="{{ old('email') }}" 
                required 
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
                Password
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

        <!-- Register Button -->
        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.25rem;">
            <i class="fas fa-user-plus"></i>
            Create Account
        </button>

        <!-- Login Link -->
        <div class="auth-footer" style="border-top: 1px solid #f1f5f9;">
            Already have an account?
            <a href="{{ route('login') }}">
                <i class="fas fa-sign-in-alt" style="margin-right: 0.3rem;"></i>
                Sign in
            </a>
        </div>
    </form>
</x-guest-layout>
