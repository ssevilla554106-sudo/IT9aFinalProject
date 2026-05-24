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

    <h2 style="font-size: 1.5rem; margin: 0 0 0.5rem; color: #1e293b; font-weight: 800;">Confirm Password</h2>
    <p style="color: #64748b; margin: 0 0 1.5rem; font-size: 0.9rem;">This is a secure area of the application. Please confirm your password to continue.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="form-group" style="margin-bottom: 1.75rem;">
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

        <!-- Confirm Button -->
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check-circle"></i>
            Confirm Password
        </button>
    </form>
</x-guest-layout>
