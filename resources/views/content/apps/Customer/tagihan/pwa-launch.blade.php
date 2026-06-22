<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    @include('content.apps.partials.pwa-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JMK</title>
    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            background: #f8fafc;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #0f172a;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at 18% 18%, rgba(14, 165, 233, 0.18), transparent 28%),
                radial-gradient(circle at 82% 26%, rgba(99, 102, 241, 0.14), transparent 30%),
                radial-gradient(circle at 50% 92%, rgba(20, 184, 166, 0.12), transparent 36%),
                linear-gradient(180deg, #ffffff 0%, #f6f9ff 100%);
        }

        .launch-card {
            width: min(300px, 78vw);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 18px;
            text-align: center;
            animation: loader-enter 620ms cubic-bezier(0.2, 0.8, 0.2, 1) both;
        }

        .launch-logo {
            position: relative;
            width: 108px;
            height: 108px;
            border-radius: 32px;
            display: grid;
            place-items: center;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.98), rgba(248, 250, 252, 0.88));
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.14), inset 0 1px 0 rgba(255, 255, 255, 0.9);
            isolation: isolate;
        }

        .launch-logo::before {
            content: '';
            position: absolute;
            inset: -7px;
            border-radius: 38px;
            background: conic-gradient(from 0deg, transparent 0 22%, #0ea5e9 34%, #6366f1 52%, transparent 68% 100%);
            animation: loader-orbit 1.45s linear infinite;
            z-index: -2;
        }

        .launch-logo::after {
            content: '';
            position: absolute;
            inset: 8px;
            border-radius: 26px;
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.12), rgba(99, 102, 241, 0.08)), #ffffff;
            z-index: -1;
        }

        .launch-logo span {
            position: relative;
            font-size: 1.65rem;
            font-weight: 900;
            letter-spacing: -0.06em;
            color: transparent;
            background: linear-gradient(135deg, #0f172a 0%, #0369a1 48%, #4f46e5 100%);
            -webkit-background-clip: text;
            background-clip: text;
            text-shadow: 0 10px 22px rgba(15, 23, 42, 0.12);
        }

        .launch-pulse {
            position: absolute;
            width: 14px;
            height: 14px;
            right: 14px;
            top: 14px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.35);
            animation: loader-pulse 1.25s ease-out infinite;
        }

        .launch-copy {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .launch-title {
            margin: 0;
            font-size: 1.08rem;
            font-weight: 800;
            letter-spacing: -0.035em;
        }

        .launch-subtitle {
            margin: 0;
            color: #64748b;
            font-size: 0.84rem;
            font-weight: 600;
        }

        .is-leaving {
            opacity: 0;
            transform: scale(1.015);
            transition: opacity 360ms ease, transform 360ms ease;
        }

        @keyframes loader-enter {
            from { opacity: 0; transform: translateY(12px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        @keyframes loader-orbit {
            to { transform: rotate(360deg); }
        }

        @keyframes loader-pulse {
            70% { box-shadow: 0 0 0 12px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
    </style>
</head>
<body>
    <main class="launch-card" role="status" aria-live="polite" aria-label="Memuat aplikasi">
        <div class="launch-logo" aria-hidden="true">
            <span>JMK</span>
            <i class="launch-pulse"></i>
        </div>
        <div class="launch-copy">
            <p class="launch-title">Menyiapkan aplikasi</p>
            <p class="launch-subtitle">Menghubungkan layanan Anda</p>
        </div>
    </main>

    <script>
        window.setTimeout(function () {
            document.body.classList.add('is-leaving');
            window.setTimeout(function () {
                window.location.replace(@json(route('customer.tagihan.home')));
            }, 260);
        }, 900);
    </script>
</body>
</html>
