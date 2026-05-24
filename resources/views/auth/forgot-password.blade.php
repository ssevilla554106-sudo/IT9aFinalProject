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

    @if (session('status'))
        <div class="session-status">
            <i class="fas fa-check-circle" style="flex-shrink: 0;"></i>
            <div>{{ session('status') }}</div>
        </div>
    @endif

    <h2 style="font-size: 1.5rem; margin: 0 0 0.5rem; color: #1e293b; font-weight: 800;">Reset Password</h2>
    <p style="color: #64748b; margin: 0 0 1.5rem; font-size: 0.9rem;">Forgot your password? We'll send you a link to reset it.</p>

    <form method="POST" action="{{ route('password.email') }}">
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
                value="{{ old('email') }}" 
                required 
                autofocus
                placeholder="you@example.com"
            />
            @error('email')
                <div class="form-error">
                    <i class="fas fa-times-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Send Link Button -->
        <button type="submit" class="btn btn-primary" style="margin-bottom: 1.25rem;">
            <i class="fas fa-paper-plane"></i>
            Send Reset Link
        </button>

        <!-- Back to Login -->
        <div class="auth-footer" style="border-top: 1px solid #f1f5f9;">
            Remember your password?
            <a href="{{ route('login') }}">
                <i class="fas fa-arrow-left" style="margin-right: 0.3rem;"></i>
                Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>
