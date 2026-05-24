<x-guest-layout>
    <!-- Session Status -->
    @if ($errors->any())
        <div class="error-message">
            <i class="fas fa-exclamation-circle" style="flex-shrink: 0; margin-top: 0.15rem;"></i>
            <div>
                <strong>Login Failed</strong>
                <ul style="margin: 0.25rem 0 0; padding-left: 1.25rem; font-size: 0.85rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if (session('status'))
        <div class="session-status">
            <i class="fas fa-check-circle" style="flex-shrink: 0;"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <h2 style="font-size: 1.5rem; margin: 0 0 0.5rem; color: #1e293b; font-weight: 800;">Welcome Back</h2>
    <p style="color: #64748b; margin: 0 0 1.5rem; font-size: 0.9rem;">Sign in to your account to continue</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                :value="old('email')" 
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
                Password
            </label>
            <input 
                id="password" 
                class="form-control"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="••••••••"
            />
            @error('password')
                <div class="form-error">
                    <i class="fas fa-times-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-group" style="margin-bottom: 1.75rem;">
            <div class="form-checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Remember me on this device</label>
            </div>
        </div>

        <!-- Sign In Button -->
        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.25rem;">
            <i class="fas fa-sign-in-alt"></i>
            Sign In
        </button>

        <!-- Links -->
        <div class="auth-footer" style="border-top: none; padding: 0;">
            @if (Route::has('password.request'))
                <div style="margin-bottom: 1rem;">
                    <a href="{{ route('password.request') }}" style="font-size: 0.85rem;">
                        <i class="fas fa-key" style="margin-right: 0.3rem;"></i>
                        Forgot your password?
                    </a>
                </div>
            @endif

            @if (Route::has('register'))
                <div style="border-top: 1px solid #f1f5f9; padding-top: 1rem;">
                    Don't have an account?
                    <a href="{{ route('register') }}">
                        <i class="fas fa-user-plus" style="margin-right: 0.3rem;"></i>
                        Create one
                    </a>
                </div>
            @endif
        </div>
    </form>
</x-guest-layout>
