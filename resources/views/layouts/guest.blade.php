<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EventHub') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary: #1a1a2e;
                --accent: #e94560;
                --accent-light: #ff6b8a;
                --secondary: #16213e;
                --surface: #0f3460;
                --gold: #f5a623;
                --text-light: #94a3b8;
                --white: #ffffff;
                --bg: #f8fafc;
            }

            * { box-sizing: border-box; }

            body {
                font-family: 'DM Sans', sans-serif;
                background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
                color: #1e293b;
                min-height: 100vh;
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: 'Syne', sans-serif;
            }

            .auth-container {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 2rem 1rem;
            }

            .auth-box {
                background: var(--white);
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                width: 100%;
                max-width: 420px;
            }

            .auth-header {
                background: linear-gradient(135deg, var(--primary), var(--secondary));
                color: var(--white);
                padding: 2.5rem 1.5rem;
                text-align: center;
            }

            .auth-logo {
                width: 48px;
                height: 48px;
                margin: 0 auto 1rem;
                background: var(--accent);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                font-weight: 800;
            }

            .auth-header h1 {
                font-size: 1.8rem;
                margin: 0 0 0.5rem;
                font-weight: 800;
            }

            .auth-header p {
                font-size: 0.9rem;
                color: var(--text-light);
                margin: 0;
            }

            .auth-body {
                padding: 2rem;
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-label {
                display: block;
                font-weight: 600;
                font-size: 0.875rem;
                color: #1e293b;
                margin-bottom: 0.5rem;
                letter-spacing: 0.02em;
            }

            .form-control {
                width: 100%;
                padding: 0.7rem 1rem;
                border: 1.5px solid #e2e8f0;
                border-radius: 10px;
                font-size: 0.95rem;
                font-family: 'DM Sans', sans-serif;
                transition: all 0.2s;
                background: #f8fafc;
                color: #1e293b;
            }

            .form-control:focus {
                outline: none;
                border-color: var(--accent);
                background: var(--white);
                box-shadow: 0 0 0 3px rgba(233, 69, 96, 0.1);
            }

            .form-control::placeholder {
                color: #94a3b8;
            }

            .form-error {
                color: #ef4444;
                font-size: 0.8rem;
                margin-top: 0.35rem;
                display: flex;
                align-items: center;
                gap: 0.3rem;
            }

            .form-checkbox {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.9rem;
            }

            .form-checkbox input {
                width: 18px;
                height: 18px;
                cursor: pointer;
                accent-color: var(--accent);
            }

            .form-checkbox label {
                cursor: pointer;
                color: #475569;
            }

            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
                padding: 0.75rem 1.5rem;
                border-radius: 10px;
                font-weight: 600;
                font-size: 0.95rem;
                cursor: pointer;
                text-decoration: none;
                border: none;
                transition: all 0.2s;
                font-family: 'DM Sans', sans-serif;
                width: 100%;
            }

            .btn-primary {
                background: var(--accent);
                color: var(--white);
            }

            .btn-primary:hover {
                background: var(--accent-light);
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(233, 69, 96, 0.4);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .auth-footer {
                padding: 1.5rem;
                border-top: 1px solid #f1f5f9;
                text-align: center;
                font-size: 0.9rem;
                color: #475569;
            }

            .auth-footer a {
                color: var(--accent);
                text-decoration: none;
                font-weight: 600;
                transition: color 0.2s;
            }

            .auth-footer a:hover {
                color: var(--accent-light);
            }

            .auth-divider {
                text-align: center;
                margin: 1.5rem 0;
                color: #cbd5e1;
                font-size: 0.85rem;
                position: relative;
            }

            .auth-divider::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 0;
                right: 0;
                height: 1px;
                background: #e2e8f0;
                z-index: 0;
            }

            .auth-divider span {
                position: relative;
                background: var(--white);
                padding: 0 0.75rem;
                z-index: 1;
            }

            .session-status {
                padding: 1rem;
                margin-bottom: 1.25rem;
                border-radius: 10px;
                background: #d1fae5;
                color: #065f46;
                border-left: 4px solid #10b981;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .error-message {
                padding: 1rem;
                margin-bottom: 1.25rem;
                border-radius: 10px;
                background: #fee2e2;
                color: #991b1b;
                border-left: 4px solid #ef4444;
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
            }

            /* Responsive */
            @media (max-width: 640px) {
                .auth-container {
                    padding: 1rem;
                }

                .auth-box {
                    max-width: 100%;
                }

                .auth-header {
                    padding: 2rem 1.5rem;
                }

                .auth-header h1 {
                    font-size: 1.5rem;
                }

                .auth-body {
                    padding: 1.5rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <div class="auth-box">
                <div class="auth-header">
                    <div class="auth-logo">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h1>{{ config('app.name', 'EventHub') }}</h1>
                    <p>Discover & Create Amazing Events</p>
                </div>
                <div class="auth-body">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
