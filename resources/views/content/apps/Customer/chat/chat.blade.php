@use('Illuminate\Support\Facades\Auth')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    @include('content.apps.Customer.partials.disable-zoom')
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat - User</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --chat-safe-bottom: env(safe-area-inset-bottom, 0px);
            --nav-bar-offset: 0px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        html {
            height: 100%;
        }

        body {
            /* Gunakan --app-height yang di-set JS agar Android Chrome benar */
            height: var(--app-height, 100dvh);
            overflow: hidden;
            background-color: #0b141a;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            align-items: stretch;
            justify-content: center;
        }

        .chat-container {
            width: 100%;
            height: var(--app-height, 100dvh);
            display: flex;
            flex-direction: column;
            background: #0c1317;
            overflow: hidden;
            position: relative;
        }

        .chat-header {
            background: #1f2c33;
            color: #e9edef;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid #2a3942;
            flex-shrink: 0;
            min-height: 70px;
            z-index: 10;
        }

        .header-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00a884 0%, #00d9a8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 168, 132, 0.3);
        }

        .header-info {
            flex: 1;
        }

        .header-info h1 {
            font-size: 17px;
            margin-bottom: 4px;
            font-weight: 600;
            color: #e9edef;
        }

        .user-status {
            font-size: 13px;
            color: #8696a0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .status-dot {
            width: 7px;
            height: 7px;
            background: #00a884;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 16px 12px;
            /* Padding bottom akan di-set oleh JS sesuai tinggi input container */
            padding-bottom: 110px;
            /* WhatsApp-like doodle background on a dark tone */
            background-color: #0b141a;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            position: relative;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior: contain;
            scroll-behavior: smooth;
            min-height: 0;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .message {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            animation: slideIn 0.3s ease-out;
            position: relative;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.received {
            justify-content: flex-start;
        }

        .message-bubble {
            max-width: 75%;
            position: relative;
        }

        .message-content {
            padding: 6px 10px 8px 10px;
            border-radius: 8px;
            word-wrap: break-word;
            position: relative;
            box-shadow: 0 1px 0.5px rgba(11, 20, 26, 0.13);
            display: inline-block;
            min-width: 100px;
        }

        /* WhatsApp Sender Tail (Dark Mode) */
        .message.sent .message-content::before {
            content: '';
            position: absolute;
            top: 0;
            right: -8px;
            width: 0;
            height: 0;
            border-top: 0px solid transparent;
            border-bottom: 12px solid transparent;
            border-left: 10px solid #005c4b;
        }

        /* WhatsApp Receiver Tail (Dark Mode) */
        .message.received .message-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: -8px;
            width: 0;
            height: 0;
            border-top: 0px solid transparent;
            border-bottom: 12px solid transparent;
            border-right: 10px solid #202c33;
        }

        .message.sent .message-content {
            background: #005c4b;
            color: #e9edef;
            border-top-right-radius: 0;
        }

        .message.received .message-content {
            background: #202c33;
            color: #e9edef;
            border-top-left-radius: 0;
        }

        .message-text {
            font-size: 14.2px;
            line-height: 19px;
            padding-right: 45px; /* space for the timestamp */
            padding-bottom: 5px;
        }

        .message-info {
            font-size: 11px;
            color: #667781;
            position: absolute;
            right: 8px;
            bottom: 4px;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .message.sent .message-info {
            color: rgba(255, 255, 255, 0.92);
        }

        .message.sent .message-status,
        .message.sent .message-status.read {
            color: #ffffff;
        }

        .message-actions {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-left: 10px;
        }

        .message-action-btn {
            width: 22px;
            height: 22px;
            border: none;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.12);
            color: #e9edef;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.18s ease;
        }

        .message-action-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-1px);
        }

        /* Confirm modal for delete */
        .chat-confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.12s ease;
        }

        .chat-confirm-overlay.hide {
            opacity: 0;
            transition: opacity 0.12s ease;
        }

        .chat-confirm-dialog {
            background: #0b1221;
            border-radius: 14px;
            padding: 20px 22px 18px;
            width: min(360px, 90vw);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255,255,255,0.06);
        }

        .chat-prompt-dialog {
            width: min(440px, 92vw);
        }

        .chat-confirm-dialog h4 {
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: 700;
            color: #e2e8f0;
        }

        .chat-confirm-dialog p {
            margin: 0 0 16px;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.5;
        }

        .chat-prompt-input {
            width: 100%;
            border: 1px solid #1f2937;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            resize: vertical;
            min-height: 82px;
            color: #e2e8f0;
            background: #111827;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .chat-prompt-input:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 0 3px rgba(34, 211, 238, 0.18);
        }

        .location-card {
            background: #0b1221;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid #1f2937;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        }

        .location-card .location-img {
            display: block;
            width: 100%;
            max-width: 280px;
            height: auto;
        }

        .location-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            background: #0f172a;
            color: #e2e8f0;
            font-size: 12px;
        }

        .location-coord {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .location-btn {
            background: #22d3ee;
            color: #0b1221;
            padding: 6px 10px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .location-btn:hover {
            background: #0ea5e9;
            color: #0b1221;
        }


        .chat-confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .chat-confirm-actions .btn-cancel,
        .chat-confirm-actions .btn-confirm {
            border: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .chat-confirm-actions .btn-cancel {
            background: #1f2937;
            color: #e2e8f0;
        }

        .chat-confirm-actions .btn-confirm {
            background: #22d3ee;
            color: #0b1221;
        }

        .chat-confirm-actions .btn-cancel:hover { background: #111827; }
        .chat-confirm-actions .btn-confirm:hover { background: #0ea5e9; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .message-text-deleted {
            font-style: italic;
            opacity: 0.78;
        }

        .message-edited {
            font-size: 10px;
            opacity: 0.82;
            margin-left: 4px;
        }

        .typing-indicator {
            padding: 12px 16px;
            font-size: 13px;
            color: #8696a0;
            display: flex;
            align-items: center;
            gap: 8px;
            background: #0c1317;
            flex-shrink: 0;
            min-height: 45px;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dot {
            width: 6px;
            height: 6px;
            background: #8696a0;
            border-radius: 50%;
            animation: pulse 1.4s ease-in-out infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        .chat-input-container {
            padding: 10px 12px;
            /* Fixed di bawah layar, di atas Android nav bar */
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #1f2c33;
            border-top: 1px solid #2a3942;
            z-index: 1000;
            /* Gunakan padding bottom untuk Android nav bar */
            padding-bottom: 10px;
        }

        .chat-input-form {
            display: flex;
            gap: 8px;
            align-items: flex-end;
        }

        .input-wrapper {
            flex: 1;
            max-width: calc(100% - 54px);
            position: relative;
            background: #2a3942;
            border-radius: 24px;
            display: flex;
            align-items: center;
            padding: 0 8px;
            min-height: 42px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .emoji-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            padding: 6px;
            color: #8696a0;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .emoji-button:hover {
            color: #e9edef;
            transform: scale(1.1);
        }

        .chat-input {
            flex: 1;
            padding: 10px 8px;
            border: none;
            background: transparent;
            font-size: 14.5px;
            outline: none;
            color: #e9edef;
            font-family: inherit;
            resize: none;
            max-height: 100px;
            overflow-y: auto;
            min-width: 0;
        }

        .chat-input::placeholder {
            color: #8696a0;
        }

        .attach-button,
        .location-button,
        .plus-menu-button,
        .voice-button {
            background: transparent;
            color: #8696a0;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
            border-radius: 50%;
        }

        .attach-button:hover,
        .location-button:hover,
        .plus-menu-button:hover,
        .voice-button:hover {
            color: #e9edef;
            transform: scale(1.1);
        }

        .attach-button:active,
        .location-button:active,
        .plus-menu-button:active,
        .voice-button:active {
            transform: scale(0.95);
        }

        .plus-menu-button.is-open {
            color: #111827 !important;
            transform: rotate(45deg) scale(1.08);
        }

        .chat-action-sheet {
            position: absolute;
            left: 0;
            bottom: calc(100% + 14px);
            width: min(330px, calc(100vw - 44px));
            padding: 8px;
            border-radius: 26px;
            border: 1px solid rgba(255, 255, 255, 0.92);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(241, 245, 249, 0.9));
            box-shadow: 0 28px 70px rgba(15, 23, 42, 0.24), inset 0 1px 0 rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(22px) saturate(170%);
            -webkit-backdrop-filter: blur(22px) saturate(170%);
            opacity: 0;
            pointer-events: none;
            transform: translateY(10px) scale(0.96);
            transform-origin: bottom left;
            transition: opacity 0.22s ease, transform 0.22s ease;
            z-index: 1200;
        }

        .chat-action-sheet.is-open {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }

        .chat-action-button {
            width: 100%;
            border: 0;
            background: rgba(255, 255, 255, 0.76);
            color: #0f172a !important;
            border-radius: 20px;
            padding: 11px 12px;
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 15px;
            line-height: 1.15;
            font-weight: 900;
            letter-spacing: -0.02em;
            cursor: pointer;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.86), 0 8px 18px rgba(15, 23, 42, 0.06);
            transition: transform 0.18s ease, background 0.18s ease, box-shadow 0.18s ease;
        }

        .chat-action-button + .chat-action-button {
            margin-top: 8px;
        }

        .chat-action-button:hover {
            background: #ffffff !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.95), 0 12px 26px rgba(15, 23, 42, 0.1);
            transform: translateY(-1px);
        }

        .chat-action-button i {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: radial-gradient(circle at 35% 25%, #334155, #020617 76%);
            color: #ffffff;
            font-size: 18px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.22);
        }

        .chat-action-sheet .location-button i {
            background: radial-gradient(circle at 35% 25%, #38bdf8, #0f172a 76%);
        }

        .chat-action-button span {
            display: block;
            color: #0f172a;
        }

        .voice-button.is-recording {
            color: #ef4444 !important;
            animation: pulse 1s ease-in-out infinite;
        }

        .voice-note-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 6px;
        }

        .voice-note-card i {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.16);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .voice-note-card audio {
            width: 220px;
            max-width: 100%;
            height: 36px;
            filter: invert(1) hue-rotate(180deg);
        }

        .media-preview-container {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        .remove-media-btn {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .media-filename {
            font-size: 12px;
            color: #8696a0;
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .send-button {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #00a884 0%, #00d9a8 100%);
            color: #ffffff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 168, 132, 0.3);
        }

        .send-button:hover {
            background: linear-gradient(135deg, #06cf9c 0%, #00ffc8 100%);
            transform: scale(1.05);
        }

        .send-button:active {
            transform: scale(0.95);
        }

        .send-button:disabled {
            background: #3b4a54;
            cursor: not-allowed;
            box-shadow: none;
        }

        .date-divider {
            text-align: center;
            margin: 20px 0;
        }

        .message.system {
            justify-content: center;
            align-items: center;
        }

        .system-message-notice {
            max-width: min(520px, 92%);
            padding: 8px 14px;
            border-radius: 999px;
            background: #ecfeff;
            border: 1px solid #bae6fd;
            color: #0369a1;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
        }

        .system-notice-link {
            color: inherit;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .date-text {
            display: inline-block;
            padding: 6px 14px;
            background: #1f2c33;
            color: #8696a0;
            font-size: 12.5px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        /* Bottom Navbar Styles */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #1f2c33;
            border-top: 1px solid #2a3942;
            transition: transform 0.3s ease;
            z-index: 999;
            padding-bottom: var(--chat-safe-bottom);
        }

        .bottom-nav.hidden {
            transform: translateY(100%);
        }

        /* Mobile specific */
        @media (max-width: 767px) {
            :root {
                --chat-safe-bottom: max(env(safe-area-inset-bottom, 0px), 0px);
            }

            body {
                padding: 0;
            }

            .chat-container {
                height: 100dvh;
                height: 100%;
            }

            .chat-input-container {
                /* Android nav bar safe padding - minimum 16px fallback */
                padding-bottom: max(env(safe-area-inset-bottom, 0px), 16px);
                padding-bottom: calc(10px + max(env(safe-area-inset-bottom, 0px), 16px));
            }

            .input-wrapper {
                max-width: calc(100% - 50px);
            }
        }

        .back-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: transparent;
            color: #8696a0;
            text-decoration: none;
            transition: all 0.3s ease;
            flex-shrink: 0;
            font-size: 18px;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #00a884;
            transform: scale(1.1);
        }

        .back-button:active {
            transform: scale(0.95);
        }

        /* Desktop styles */
        @media (min-width: 768px) {
            body {
                padding: 0;
            }

            .chat-container {
                max-width: 100%;
                height: 100dvh;
                border: none;
            }

            .bottom-nav {
                position: relative;
            }

            .input-wrapper {
                max-width: calc(100% - 60px);
            }
        }

        /* Customer chat refresh: bright grid canvas with glass panels */
        body {
            background: #f6f7fb !important;
        }

        .chat-container {
            background: #f8fafc !important;
        }

        .chat-container::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 18% 12%, rgba(15, 23, 42, 0.08), transparent 26%),
                radial-gradient(circle at 88% 18%, rgba(148, 163, 184, 0.2), transparent 28%);
            z-index: 0;
        }

        .chat-header {
            position: relative;
            z-index: 3;
            margin: 14px 14px 0;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.95) !important;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.72), rgba(255, 255, 255, 0.34)) !important;
            color: #111827 !important;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12), inset 0 1px 0 rgba(255, 255, 255, 0.92), inset 0 -1px 0 rgba(255, 255, 255, 0.34);
            backdrop-filter: blur(26px) saturate(180%);
            -webkit-backdrop-filter: blur(26px) saturate(180%);
        }

        .chat-header::after,
        .chat-input-container::after {
            content: '';
            position: absolute;
            inset: 1px;
            border-radius: inherit;
            pointer-events: none;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.82), rgba(255, 255, 255, 0.16) 38%, rgba(255, 255, 255, 0.04) 68%);
            opacity: 0.72;
        }

        .header-avatar {
            background: linear-gradient(145deg, #111827 0%, #000000 100%) !important;
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.24) !important;
        }

        .header-info h1 {
            color: #0f172a !important;
            font-weight: 900;
            letter-spacing: -0.03em;
        }

        .user-status {
            color: #64748b !important;
            font-weight: 700;
        }

        .status-dot {
            background: #111827 !important;
            box-shadow: 0 0 0 6px rgba(15, 23, 42, 0.08);
        }

        .back-button {
            background: rgba(255, 255, 255, 0.72) !important;
            color: #111827 !important;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
        }

        .back-button:hover {
            background: #111827 !important;
            color: #ffffff !important;
        }

        .chat-messages {
            z-index: 1;
            padding: 28px 18px 120px !important;
            background-color: #f8fafc !important;
            background-image:
                linear-gradient(rgba(15, 23, 42, 0.055) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15, 23, 42, 0.055) 1px, transparent 1px) !important;
            background-size: 34px 34px !important;
            background-position: -1px -1px !important;
        }

        .chat-messages::before {
            content: '';
            position: fixed;
            left: 50%;
            top: 16%;
            width: min(680px, 80vw);
            height: min(680px, 80vw);
            transform: translateX(-50%);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0));
            pointer-events: none;
        }

        .message {
            position: relative;
            z-index: 2;
            margin-bottom: 14px;
            gap: 10px;
            align-items: flex-end;
        }

        .message-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
            color: #111827;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: -0.04em;
            background: radial-gradient(circle at 35% 25%, #ffffff 0%, #f2e7df 34%, #d7b8a3 62%, #9f725d 100%);
            border: 2px solid rgba(255, 255, 255, 0.92);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.16), inset 0 1px 0 rgba(255, 255, 255, 0.88);
        }

        .message.sent .message-avatar {
            order: 2;
            background: radial-gradient(circle at 35% 25%, #ffffff 0%, #dbeafe 34%, #93c5fd 62%, #1d4ed8 100%);
        }

        .message.received .message-avatar {
            order: 0;
            background: radial-gradient(circle at 35% 25%, #ffffff 0%, #dcfce7 34%, #86efac 62%, #15803d 100%);
        }

        .message-bubble {
            max-width: min(76%, 760px);
        }

        .message-content {
            padding: 12px 16px 18px !important;
            border-radius: 22px !important;
            min-width: 112px;
            border: 1px solid rgba(255, 255, 255, 0.26);
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.18) !important;
            background-clip: padding-box;
        }

        .message.sent .message-content,
        .message.received .message-content {
            background: linear-gradient(145deg, #30343c 0%, #16181d 100%) !important;
            color: #ffffff !important;
            border-top-left-radius: 20px !important;
            border-top-right-radius: 20px !important;
        }

        .message.sent .message-content {
            border-bottom-right-radius: 7px !important;
        }

        .message.received .message-content {
            border-bottom-left-radius: 7px !important;
        }

        .message-content::before {
            display: none !important;
        }

        .message-text {
            color: #ffffff !important;
            font-size: 15px;
            line-height: 1.52;
        }

        .message-info,
        .message.sent .message-info,
        .message.received .message-info,
        .message-status,
        .message-status.read {
            color: rgba(255, 255, 255, 0.68) !important;
        }

        .date-text,
        .system-message-notice {
            background: rgba(255, 255, 255, 0.78) !important;
            border: 1px solid rgba(15, 23, 42, 0.08) !important;
            color: #0f172a !important;
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.08) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .typing-indicator {
            background: rgba(255, 255, 255, 0.78) !important;
            color: #111827 !important;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .chat-input-container {
            z-index: 1000;
            margin: 0 14px 14px;
            padding: 12px !important;
            overflow: visible;
            border: 1px solid rgba(255, 255, 255, 0.96) !important;
            border-radius: 28px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.76), rgba(255, 255, 255, 0.34)) !important;
            box-shadow: 0 26px 70px rgba(15, 23, 42, 0.18), inset 0 1px 0 rgba(255, 255, 255, 0.95), inset 0 -1px 0 rgba(255, 255, 255, 0.32);
            backdrop-filter: blur(30px) saturate(190%);
            -webkit-backdrop-filter: blur(30px) saturate(190%);
        }

        .chat-input-form {
            position: relative;
            z-index: 1;
        }

        .input-wrapper {
            background: rgba(255, 255, 255, 0.48) !important;
            border: 1px solid rgba(255, 255, 255, 0.72);
            border-radius: 22px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.92), 0 10px 24px rgba(15, 23, 42, 0.06);
            backdrop-filter: blur(14px) saturate(150%);
            -webkit-backdrop-filter: blur(14px) saturate(150%);
        }

        .chat-input {
            background: transparent !important;
            color: #111827 !important;
            font-weight: 700;
        }

        .chat-input::placeholder {
            color: #94a3b8 !important;
            font-weight: 700;
        }

        .attach-button,
        .location-button,
        .plus-menu-button,
        .voice-button {
            color: #475569 !important;
        }

        .attach-button:hover,
        .location-button:hover,
        .plus-menu-button:hover,
        .voice-button:hover {
            background: rgba(15, 23, 42, 0.08) !important;
            color: #111827 !important;
        }

        .send-button {
            background: linear-gradient(145deg, #111827 0%, #000000 100%) !important;
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.28) !important;
        }

        .send-button:hover {
            background: linear-gradient(145deg, #334155 0%, #111827 100%) !important;
        }

        @media (max-width: 767px) {
            .chat-header {
                margin: 10px 10px 0;
                border-radius: 20px;
            }

            .chat-messages {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }

            .message-bubble {
                max-width: calc(100% - 58px);
            }

            .message-avatar {
                width: 38px;
                height: 38px;
                font-size: 12px;
            }

            .chat-input-container {
                margin: 0 10px 10px;
                border-radius: 24px;
                padding-bottom: calc(12px + max(env(safe-area-inset-bottom, 0px), 12px)) !important;
            }

            .chat-action-sheet {
                left: -2px;
                bottom: calc(100% + 12px);
                width: min(344px, calc(100vw - 42px));
                border-radius: 24px;
            }

            .chat-action-button {
                padding: 10px 12px;
                font-size: 14px;
            }
        }
    </style>
    <script>
        // Set --app-height berdasarkan window.innerHeight
        // window.innerHeight pada Android Chrome sudah exclude navigation bar
        function setAppHeight() {
            document.documentElement.style.setProperty('--app-height', window.innerHeight + 'px');
        }
        setAppHeight();
        window.addEventListener('resize', setAppHeight);
        window.addEventListener('orientationchange', function() {
            setTimeout(setAppHeight, 200);
        });
    </script>
</head>

<body>
    <div class="chat-container">
        <div class="chat-header">
            <a href="{{ url('https://layanan.jernih.net.id/dashboard/customer/tagihan/home') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="header-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="header-info">
                <h1>Chat CS</h1>
                <div class="user-status">
                    <span class="status-dot"></span>
                    @php
                        $auth = Auth::user() ?? Auth::guard('customer')->user();
                    @endphp

                    <span>{{ $auth->name ?? $auth->nama_lengkap ?? 'Customer' }}</span>
                </div>
            </div>
        </div>

        <div class="chat-messages" id="chatMessages">
            <!-- Messages will be loaded here -->
        </div>

        <div class="typing-indicator" id="typingIndicator" style="display: none;">
            <div class="typing-dots">
                <span class="typing-dot"></span>
                <span class="typing-dot"></span>
                <span class="typing-dot"></span>
            </div>
            <span>Admin sedang mengetik...</span>
        </div>

        <div class="chat-input-container" id="chatInputContainer">
            <div id="mediaPreview"
                style="display: none; padding: 8px 12px; background: rgba(255, 255, 255, 0.72); border: 1px solid rgba(15, 23, 42, 0.08); border-radius: 16px; margin-bottom: 8px;">
            </div>
            <form class="chat-input-form" id="chatForm" enctype="multipart/form-data">
                @csrf
                <input type="file" id="mediaInput" accept="image/*,video/*" style="display: none;">
                <div class="input-wrapper">
                    <button type="button" class="plus-menu-button" id="plusMenuButton" title="Tambah lampiran" aria-expanded="false">
                        <i class="fas fa-plus"></i>
                    </button>
                    <div class="chat-action-sheet" id="chatActionSheet" aria-hidden="true">
                        <button type="button" class="chat-action-button attach-button" id="attachButton" title="Kirim foto/video">
                            <i class="fas fa-image"></i>
                            <span>Pilih gambar/video</span>
                        </button>
                        <button type="button" class="chat-action-button location-button" id="locationButton" title="Kirim lokasi">
                            <i class="fas fa-location-arrow"></i>
                            <span>Share lokasi</span>
                        </button>
                    </div>
                    <input type="text" class="chat-input" id="messageInput" placeholder="Tulis pesan"
                        autocomplete="off">
                    <button type="button" class="voice-button" id="voiceButton" title="Rekam voice note">
                        <i class="fas fa-microphone"></i>
                    </button>
                </div>
                <button type="submit" class="send-button" id="sendButton">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        window.userId = "{{ auth('customer')->id() }}";
        window.userName = "{{ $auth->name ?? $auth->nama_lengkap ?? 'Customer' }}";
        window.isAdmin = false;
        window.chatSessionId = "{{ request('session_id') }}";

        console.log('Chat User Initialized');
        console.log('User ID:', window.userId);
        console.log('User Name:', window.userName);

        // Elements
        const messageInput = document.getElementById('messageInput');
        const bottomNav = document.querySelector('.bottom-nav');
        const chatMessages = document.getElementById('chatMessages');
        const chatInputContainer = document.getElementById('chatInputContainer');

        // Smooth scroll to bottom function
        function scrollToBottom(smooth = true) {
            if (chatMessages) {
                chatMessages.scrollTo({
                    top: chatMessages.scrollHeight,
                    behavior: smooth ? 'smooth' : 'auto'
                });
            }
        }

        // Make scrollToBottom available globally
        window.scrollToBottom = scrollToBottom;

        // Detect if user is at bottom (for auto-scroll on new messages)
        let isAtBottom = true;

        if (chatMessages) {
            chatMessages.addEventListener('scroll', function () {
                const threshold = 50;
                const position = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight;
                isAtBottom = position < threshold;
            }, { passive: true });
        }

        // Auto-scroll only if user is already at bottom
        function autoScrollIfNeeded() {
            if (isAtBottom) {
                scrollToBottom(true);
            }
        }

        window.autoScrollIfNeeded = autoScrollIfNeeded;

        // Fungsi: sesuaikan padding-bottom chat-messages agar tidak tertutup input
        function adjustChatPadding() {
            const inputContainer = document.getElementById('chatInputContainer');
            if (inputContainer && chatMessages) {
                const inputHeight = inputContainer.getBoundingClientRect().height;
                const safeBottomPadding = (inputHeight + 24);
                chatMessages.style.paddingBottom = Math.max(safeBottomPadding, 110) + 'px';
            }
        }
        // Expose globally so chat.js can call it too
        window.adjustChatPadding = adjustChatPadding;

        // MutationObserver: auto-adjust padding & scroll setiap kali pesan baru ditambahkan
        if (chatMessages) {
            const msgObserver = new MutationObserver(function() {
                adjustChatPadding();
            });
            msgObserver.observe(chatMessages, { childList: true, subtree: false });
        }

        // Handle keyboard untuk mobile (Android & iOS)
        if (window.visualViewport) {
            const viewport = window.visualViewport;

            function updateLayoutForKeyboard() {
                const visibleHeight = viewport.height;
                const keyboardHeight = window.innerHeight - visibleHeight;
                const inputContainer = document.getElementById('chatInputContainer');

                if (keyboardHeight > 100) {
                    // Keyboard tampil: geser input ke atas keyboard
                    if (inputContainer) {
                        inputContainer.style.bottom = keyboardHeight + 'px';
                    }
                } else {
                    // Keyboard hilang: kembalikan input ke bawah
                    if (inputContainer) {
                        inputContainer.style.bottom = '0';
                    }
                }

                adjustChatPadding();
                setTimeout(() => scrollToBottom(true), 100);
            }

            viewport.addEventListener('resize', updateLayoutForKeyboard, { passive: true });

            messageInput.addEventListener('focus', () => {
                setTimeout(updateLayoutForKeyboard, 200);
            });

            messageInput.addEventListener('blur', () => {
                setTimeout(() => {
                    const inputContainer = document.getElementById('chatInputContainer');
                    if (inputContainer) inputContainer.style.bottom = '0';
                    adjustChatPadding();
                    scrollToBottom(true);
                }, 200);
            });
        } else {
            // Fallback: dengarkan window resize (keyboard event di Android lama)
            window.addEventListener('resize', () => {
                setTimeout(adjustChatPadding, 100);
            });

            messageInput.addEventListener('focus', () => {
                setTimeout(() => scrollToBottom(true), 400);
            });
        }

        // Prevent iOS bounce scroll on body
        document.body.addEventListener('touchmove', function (e) {
            if (e.target === document.body) {
                e.preventDefault();
            }
        }, { passive: false });

        // Initial scroll to bottom on page load
        window.addEventListener('load', function () {
            setTimeout(() => {
                scrollToBottom(false);
                adjustChatPadding();
            }, 200);
        });
    </script>

    @vite(['resources/js/bootstrap.js', 'resources/js/echo.js', 'resources/js/chat.js'])

</body>

</html>
