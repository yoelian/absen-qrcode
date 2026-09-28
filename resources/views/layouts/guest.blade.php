<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AbsenPro') }} — Masuk Portal</title>

    <!-- Google Fonts: Outfit & Inter -->
    <link href="{{ asset('assets/css/fonts.css') }}" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-icons.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --brand-primary: #0284c7;
            --brand-gradient: linear-gradient(135deg, #0284c7 0%, #3b82f6 50%, #6366f1 100%);
            --bg-gradient: radial-gradient(at 0% 0%, rgba(2, 132, 199, 0.15) 0px, transparent 50%),
                           radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                           #0f172a;
        }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
            padding: 24px;
            margin: 0;
            overflow-x: hidden;
            position: relative;
        }

        /* Decorative Background Orbs */
        .bg-orb-1 {
            position: absolute;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(2, 132, 199, 0) 70%);
            top: -100px;
            left: -100px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(40px);
            animation: floatOrb 12s ease-in-out infinite alternate;
        }

        .bg-orb-2 {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, rgba(79, 70, 229, 0) 70%);
            bottom: -150px;
            right: -100px;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(50px);
            animation: floatOrb 15s ease-in-out infinite alternate-reverse;
        }

        @keyframes floatOrb {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 30px) scale(1.1); }
        }

        .auth-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 0 1px rgba(255, 255, 255, 0.05);
            padding: 40px;
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .auth-brand-logo {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: var(--brand-gradient);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 2rem;
            box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.5);
            margin-bottom: 20px;
        }

        .auth-title {
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            background: linear-gradient(180deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 6px;
        }

        .auth-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
            margin-bottom: 28px;
        }

        .form-label {
            color: #cbd5e1;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .input-group-modern {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group-modern .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 1.1rem;
            pointer-events: none;
            transition: color 0.2s ease;
            z-index: 5;
        }

        .form-control-modern {
            background: rgba(15, 23, 42, 0.6) !important;
            border: 1px solid rgba(148, 163, 184, 0.2) !important;
            border-radius: 14px !important;
            padding: 13px 16px 13px 44px !important;
            color: #f8fafc !important;
            font-size: 0.95rem !important;
            font-weight: 500 !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            width: 100%;
        }

        .form-control-modern:focus {
            background: rgba(15, 23, 42, 0.85) !important;
            border-color: #38bdf8 !important;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15) !important;
            outline: none !important;
        }

        .input-group-modern:focus-within .input-icon {
            color: #38bdf8;
        }

        .btn-modern-primary {
            background: var(--brand-gradient);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            padding: 14px 24px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.3px;
            width: 100%;
            box-shadow: 0 10px 25px -5px rgba(2, 132, 199, 0.4);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(2, 132, 199, 0.6);
            color: #ffffff;
        }

        .btn-modern-primary:active {
            transform: translateY(0);
        }

        .demo-badge {
            background: rgba(255, 255, 255, 0.06);
            border: 1px dashed rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .demo-pill {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            border: 1px solid rgba(56, 189, 248, 0.3);
            border-radius: 8px;
            padding: 3px 8px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .demo-pill:hover {
            background: rgba(56, 189, 248, 0.3);
            color: #ffffff;
        }

        .back-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: color 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 20px;
        }

        .back-link:hover {
            color: #38bdf8;
        }
    </style>
</head>
<body>
    <div class="bg-orb-1"></div>
    <div class="bg-orb-2"></div>

    <div class="auth-card">
        {{ $slot }}
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
