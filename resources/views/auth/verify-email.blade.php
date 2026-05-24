<x-guest-layout>
    @if (session('status') == 'verification-link-sent')
        <div class="session-status">
            <i class="fas fa-check-circle" style="flex-shrink: 0;"></i>
            <div>A new verification link has been sent to your email address.</div>
        </div>
    @endif

    <h2 style="font-size: 1.5rem; margin: 0 0 0.5rem; color: #1e293b; font-weight: 800;">Verify Email</h2>
    <p style="color: #64748b; margin: 0 0 1.5rem; font-size: 0.9rem;">Thanks for signing up! Before getting started, please verify your email address by clicking the link we just sent you.</p>

    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem; display: flex; gap: 0.75rem; align-items: flex-start;">
        <i class="fas fa-info-circle" style="color: #92400e; flex-shrink: 0; margin-top: 0.15rem;"></i>
        <div style="color: #92400e; font-size: 0.9rem;">
            <strong>Didn't receive the email?</strong> We can send you another one.
        </div>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn" style="background: #f1f5f9; color: #475569; border: 1.5px solid #e2e8f0;">
                <i class="fas fa-sign-out-alt"></i>
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
