@php
    $user = auth('customer')->user();
    use Illuminate\Support\Str;

@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    @include('content.apps.Customer.partials.disable-zoom')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Home - Dashboard</title>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Pixel Code - https://app.pushaja.com/ -->
    <script defer src="https://app.pushaja.com/pixel/pANATtCQfpZQVbbw"></script>
    <!-- END Pixel Code -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- SweetAlert2 -->
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --safe-bottom: max(env(safe-area-inset-bottom, 0px), 8px);
            --surface: #ffffff;
            --border: #e2e8f0;
            --shadow-soft: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-hover: 0 6px 18px rgba(0, 0, 0, 0.10);
            --radius-card: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            padding: 0 0 calc(90px + var(--safe-bottom)) 0;
            min-height: 100vh;
            color: #0f172a;
        }

        .container {
            max-width: 680px;
            padding: 0 16px 8px;
        }

        /* Virtual Card (replaces hero section & stats) */
        .virtual-card-wrapper {
            margin: 16px 0 32px;
        }

        .virtual-card {
            position: relative;
            background: #18181b;
            /* very dark grey/black */
            border-radius: 16px;
            padding: 24px;
            color: white;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            aspect-ratio: 1.586;
            /* Credit card ratio */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .vc-bg-pattern {
            position: absolute;
            top: 50%;
            right: -20px;
            width: 180px;
            height: 180px;
            transform: translateY(-50%);
            background-image: url('{{ asset("assets/img/jmk-logo.png") }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center right;
            opacity: 0.15;
            z-index: 0;
        }

        .vc-bg-shapes {
            display: none;
        }

        .vc-content {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: space-between;
        }

        .vc-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .vc-logo {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            font-family: 'Inter', sans-serif;
        }

        .vc-status {
            background: #dcfce7;
            color: #166534;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .vc-middle {
            margin-top: auto;
            margin-bottom: 24px;
        }

        .vc-number {
            font-family: 'Courier New', monospace;
            font-size: 1.6rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-shadow: none;
        }

        .vc-bottom {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .vc-name {
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .vc-brand {
            font-size: 1.8rem;
            font-weight: 800;
            font-style: italic;
            letter-spacing: -1px;
            text-shadow: none;
        }

        .header-greeting {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .network-status-card {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            padding: 8px 12px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.06);
            color: #0f172a;
            font-size: 0.82rem;
            font-weight: 800;
            line-height: 1;
        }

        .network-status-dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.14);
        }

        .network-status-card.signal-warning .network-status-dot {
            background: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.16);
        }

        .network-status-card.signal-bad .network-status-dot {
            background: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.16);
        }

        /* Iklan/Info Section */
        .info-section {
            margin-bottom: 24px;
        }

        .info-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 0;
            border: 1px solid #0f172a;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 12px;
            overflow: hidden;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .info-card:hover {
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .info-card.maintenance {
            background: #ffffff;
        }

        .info-card.informasi {
            background: #ffffff;
        }

        .info-card.iklan {
            background: #ffffff;
        }

        .info-image {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background: #f1f5f9;
        }

        .info-content {
            padding: 16px 20px;
        }

        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .info-badge.maintenance {
            background: #fef3c7;
            color: #d97706;
        }

        .info-badge.informasi {
            background: #dbeafe;
            color: #0369a1;
        }

        .info-badge.iklan {
            background: #ede9fe;
            color: #7c3aed;
        }

        .info-title {
            font-size: 1.0625rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .info-message {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .info-timestamp {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Modal Detail Iklan */
        .iklan-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            animation: fadeIn 0.3s ease;
            padding: 0;
        }

        .iklan-modal-overlay.show {
            display: flex;
        }

        .iklan-modal-content {
            background: white;
            border-radius: 0;
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100vh;
            overflow-y: auto;
            position: relative;
            animation: slideUp 0.3s ease;
            box-shadow: none;
        }

        .iklan-modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.2s ease;
        }

        .iklan-modal-close:hover {
            background: rgba(0, 0, 0, 0.7);
            transform: rotate(90deg);
        }

        .iklan-modal-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            cursor: zoom-in;
            transition: transform 0.3s ease;
        }

        .iklan-modal-image:hover {
            transform: scale(1.02);
        }

        .iklan-modal-body {
            padding: 24px;
        }

        .iklan-modal-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 24px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .iklan-modal-badge.maintenance {
            background: #fef3c7;
            color: #d97706;
        }

        .iklan-modal-badge.informasi {
            background: #dbeafe;
            color: #0369a1;
        }

        .iklan-modal-badge.iklan {
            background: #ede9fe;
            color: #7c3aed;
        }

        .iklan-modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .iklan-modal-message {
            font-size: 1rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 20px;
            white-space: pre-wrap;
        }

        .iklan-modal-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .iklan-modal-time {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.875rem;
            color: #94a3b8;
        }

        /* Image Zoom Modal */
        .zoom-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
            cursor: zoom-out;
            animation: fadeIn 0.3s ease;
            padding: 20px;
        }

        .zoom-overlay.show {
            display: flex;
        }

        .zoom-overlay img {
            max-width: 95%;
            max-height: 95%;
            object-fit: contain;
            animation: zoomIn 0.3s ease;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Modern Menu Grid (E-Wallet Style) */
        .section-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
            margin-top: 8px;
            letter-spacing: -0.01em;
        }

        .modern-menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px 14px;
            margin-bottom: 32px;
        }

        .modern-menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }

        .modern-menu-icon {
            width: 68px;
            height: 68px;
            background: #ffffff;
            border: 1.5px solid #475569;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            position: relative;
            font-size: 31px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .modern-menu-item:hover .modern-menu-icon {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-color: #0ea5e9;
        }

        .modern-menu-badge {
            position: absolute;
            bottom: -7px;
            right: -7px;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: white;
            border: 2px solid white;
            font-weight: bold;
        }

        .modern-menu-notification-count {
            position: absolute;
            top: -10px;
            right: -10px;
            min-width: 20px;
            height: 20px;
            padding: 0 5px;
            border-radius: 999px;
            background: #ef4444;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.35);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.66rem;
            font-weight: 900;
            line-height: 1;
            z-index: 3;
        }

        .badge-purple {
            background: #8b5cf6;
        }

        .badge-blue {
            background: #0ea5e9;
        }

        .badge-green {
            background: #10b981;
        }

        .badge-orange {
            background: #f59e0b;
        }

        .badge-slate {
            background: #64748b;
        }

        .modern-menu-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #334155;
            text-align: center;
            line-height: 1.3;
            max-width: 96px;
        }

        @media (max-width: 380px) {
            .modern-menu-grid {
                gap: 22px 10px;
            }

            .modern-menu-icon {
                width: 64px;
                height: 64px;
                font-size: 29px;
            }

            .modern-menu-text {
                font-size: 0.9rem;
                max-width: 92px;
            }
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: calc(72px + var(--safe-bottom));
            padding-bottom: var(--safe-bottom);
            background: #ffffff;
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            box-shadow: 0 -2px 16px rgba(0, 0, 0, 0.08);
            border-top: 1px solid #e2e8f0;
            z-index: 999;
        }

        .bottom-nav .tab-btn {
            background: none;
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            color: #94a3b8;
            position: relative;
            transition: all 0.2s ease;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 12px;
        }

        .bottom-nav .tab-btn:hover {
            background: #f8fafc;
        }

        .bottom-nav .tab-btn i {
            font-size: 1.5rem;
        }

        .bottom-nav .tab-btn span {
            font-size: 0.6875rem;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        .bottom-nav .tab-btn.active {
            color: #0f172a;
        }

        .bottom-nav .tab-btn.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 3px;
            background: #0f172a;
            border-radius: 0 0 3px 3px;
        }

        /* Profile Overlay */
        .profile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: none;
            align-items: flex-end;
            z-index: 1000;
            animation: fadeIn 0.2s ease;
        }

        .profile-overlay.show {
            display: flex;
        }

        .profile-modal {
            background: #ffffff;
            border-radius: 24px 24px 0 0;
            width: 100%;
            max-width: 680px;
            margin: 0 auto;
            padding: 24px;
            animation: slideUp 0.3s ease;
            box-shadow: 0 -4px 24px rgba(0, 0, 0, 0.12);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .profile-avatar i {
            font-size: 2.5rem;
            color: #64748b;
        }

        .profile-info h5 {
            margin: 0;
            color: #0f172a;
            font-size: 1.125rem;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .profile-info p {
            margin: 4px 0 0 0;
            color: #64748b;
            font-size: 0.875rem;
        }

        .profile-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 20px 0;
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 16px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            color: #dc2626;
            font-size: 0.9375rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 20px;
        }

        .logout-btn:hover {
            background: #fee2e2;
            border-color: #fca5a5;
        }

        .logout-btn i {
            font-size: 1.125rem;
        }

        /* ========== CUSTOM SWEETALERT2 MODAL - GLASSMORPHISM PREMIUM ========== */
        /* ========== GLOBAL SWEETALERT2 OVERRIDE - GLASSMORPHISM PREMIUM ========== */
        .swal2-popup {
            border-radius: 28px !important;
            padding: 36px 24px 24px !important;
            width: 90% !important;
            max-width: 380px !important;
            background: rgba(255, 255, 255, 0.85) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12), 0 8px 24px rgba(15, 23, 42, 0.04) !important;
            font-family: 'Inter', sans-serif !important;
            overflow: hidden !important;
        }

        .swal2-popup .swal2-title {
            font-size: 1.375rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            letter-spacing: -0.02em !important;
            margin-bottom: 8px !important;
            padding: 0 !important;
        }

        .swal2-popup .swal2-html-container {
            font-size: 0.875rem !important;
            color: #475569 !important;
            line-height: 1.6 !important;
            margin: 16px 0 24px !important;
            padding: 0 !important;
        }

        .swal2-backdrop-show {
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            background: rgba(15, 23, 42, 0.45) !important;
        }

        /* Glassmorphism Icon Badge */
        .glass-icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(15, 23, 42, 0.08);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.4);
        }

        .glass-icon-wrap i {
            color: #0f172a !important;
            font-size: 1.75rem;
        }

        .glass-modal-title {
            font-size: 1.375rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .glass-modal-text {
            font-size: 0.875rem;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .glass-features-row {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .glass-feature-tag {
            background: rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 12px;
            padding: 10px 14px;
            flex: 1;
            font-size: 0.78rem;
            font-weight: 700;
            color: #1e293b;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* SweetAlert2 Standard Icons Override */
        .swal2-icon {
            border-width: 3px !important;
            margin: 0 auto 20px !important;
            transform: scale(0.9);
        }

        .swal2-icon.swal2-warning {
            border-color: #f59e0b !important;
            color: #f59e0b !important;
        }

        .swal2-icon.swal2-warning .swal2-icon-content {
            font-size: 3rem !important;
            color: #f59e0b !important;
        }

        .swal2-icon.swal2-success {
            border-color: #10b981 !important;
        }

        .swal2-icon.swal2-success [class^=swal2-success-line] {
            background-color: #10b981 !important;
        }

        .swal2-icon.swal2-success .swal2-success-ring {
            border: 3px solid rgba(16, 185, 129, 0.2) !important;
        }

        .swal2-icon.swal2-error {
            border-color: #ef4444 !important;
        }

        .swal2-icon.swal2-error [class^=swal2-x-mark-line] {
            background-color: #ef4444 !important;
        }

        /* Buttons styling */
        .swal2-actions {
            margin-top: 16px !important;
            width: 100% !important;
            gap: 8px !important;
        }

        .swal2-confirm.swal2-styled {
            background: #0f172a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 0.9375rem !important;
            padding: 12px 24px !important;
            border-radius: 12px !important;
            border: none !important;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2) !important;
            transition: all 0.2s ease !important;
            margin: 0 !important;
            flex: 1;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px;
        }

        .swal2-confirm.swal2-styled:hover {
            background: #1e293b !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.25) !important;
        }

        .swal2-cancel.swal2-styled {
            background: transparent !important;
            color: #64748b !important;
            font-weight: 600 !important;
            font-size: 0.9375rem !important;
            padding: 12px 24px !important;
            border-radius: 12px !important;
            border: 1px solid rgba(15, 23, 42, 0.12) !important;
            transition: all 0.2s ease !important;
            margin: 0 !important;
            flex: 1;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 6px;
        }

        .swal2-cancel.swal2-styled:hover {
            background: rgba(15, 23, 42, 0.04) !important;
            border-color: rgba(15, 23, 42, 0.2) !important;
        }

        /* Backward compatibility for custom classes */
        .swal2-confirm.custom-btn {
            background: #0f172a !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 0.9375rem !important;
            padding: 14px 24px !important;
            border-radius: 14px !important;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25) !important;
            transition: all 0.2s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px;
            width: 100% !important;
            margin-top: 8px !important;
        }

        .swal2-confirm.custom-btn:hover {
            background: #1e293b !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.3) !important;
        }

        .swal2-cancel.custom-cancel-btn {
            background: transparent !important;
            color: #64748b !important;
            font-weight: 600 !important;
            font-size: 0.9375rem !important;
            padding: 14px 24px !important;
            border-radius: 14px !important;
            border: 1px solid rgba(15, 23, 42, 0.12) !important;
            transition: all 0.2s ease !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px;
            width: 100% !important;
            margin-top: 8px !important;
        }

        .swal2-cancel.custom-cancel-btn:hover {
            background: rgba(15, 23, 42, 0.04) !important;
            border-color: rgba(15, 23, 42, 0.2) !important;
        }

        .swal2-close {
            color: rgba(15, 23, 42, 0.4) !important;
            font-size: 1.5rem !important;
            opacity: 1 !important;
            transition: all 0.25s ease !important;
            width: 36px !important;
            height: 36px !important;
            background: rgba(15, 23, 42, 0.05) !important;
            border-radius: 50% !important;
            position: absolute !important;
            top: 14px !important;
            right: 14px !important;
            z-index: 20 !important;
        }

        .swal2-close:hover {
            color: #0f172a !important;
            background: rgba(15, 23, 42, 0.1) !important;
            transform: rotate(90deg) scale(1.1) !important;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        body.modal-open {
            overflow: hidden;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .hero-name {
                font-size: 1.5rem;
            }

            .stats-section {
                gap: 10px;
            }

            .stat-card {
                padding: 16px;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .action-card {
                min-height: 148px;
            }

            .iklan-modal-content {
                max-width: 95%;
            }

            .iklan-modal-image {
                height: 200px;
            }
        }

        /* Countdown Banner */
        .countdown-banner {
            margin: -12px 0 16px;
            padding: 14px 18px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid;
            animation: fadeInUp 0.4s ease;
        }

        .countdown-banner.safe {
            background: #f0fdf4;
            border-color: #bbf7d0;
            color: #166534;
        }

        .countdown-banner.warn {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }

        .countdown-banner.danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
            animation: fadeInUp 0.4s ease, pulse-subtle 2s ease-in-out infinite;
        }

        .countdown-banner.overdue {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #7f1d1d;
            animation: fadeInUp 0.4s ease, pulse-subtle 1.5s ease-in-out infinite;
        }

        .countdown-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .countdown-banner.safe .countdown-icon {
            background: #dcfce7;
            color: #16a34a;
        }

        .countdown-banner.warn .countdown-icon {
            background: #fef3c7;
            color: #d97706;
        }

        .countdown-banner.danger .countdown-icon {
            background: #fee2e2;
            color: #dc2626;
        }

        .countdown-banner.overdue .countdown-icon {
            background: #fee2e2;
            color: #991b1b;
        }

        .countdown-text {
            flex: 1;
        }

        .countdown-title {
            font-size: 0.8125rem;
            font-weight: 700;
            margin-bottom: 1px;
        }

        .countdown-sub {
            font-size: 0.6875rem;
            font-weight: 500;
            opacity: 0.8;
        }

        @keyframes pulse-subtle {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.01);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Lunas Card */
        .lunas-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            animation: fadeInUp 0.5s ease;
        }

        .lunas-card .lunas-emoji {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .lunas-card .lunas-title {
            font-size: 1rem;
            font-weight: 700;
            color: #166534;
            margin-bottom: 4px;
        }

        .lunas-card .lunas-sub {
            font-size: 0.8125rem;
            color: #15803d;
        }

        /* Modern Custom Spinner for Loading Modals */
        .modern-spinner {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: conic-gradient(from 180deg at 50% 50%, rgba(59, 130, 246, 0) 0deg, #3b82f6 360deg);
            -webkit-mask: radial-gradient(farthest-side, #0000 calc(100% - 6px), #000 0);
            mask: radial-gradient(farthest-side, #0000 calc(100% - 6px), #000 0);
            animation: modern-spin 0.8s infinite linear;
            margin: 0 auto 20px auto;
        }
        @keyframes modern-spin {
            to { transform: rotate(1turn); }
        }

        /* Force-hide cancel button inside custom-modal popups — must be LAST to win specificity */
        .swal2-popup.custom-modal .swal2-cancel.swal2-styled,
        .swal2-popup.custom-modal .swal2-cancel {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            overflow: hidden !important;
            visibility: hidden !important;
        }

        /* Custom Success Modal Styling - Premium Glassmorphism */
        .glass-success-icon-wrap {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(20, 184, 166, 0.1) 100%);
            border: 2px solid rgba(34, 197, 94, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            position: relative;
            box-shadow: 0 8px 24px rgba(34, 197, 94, 0.15);
        }

        .glass-success-icon-wrap i.bi-bell-fill {
            color: #22c55e !important;
            font-size: 2.2rem;
            animation: pulse-ring 2s cubic-bezier(0.215, 0.610, 0.355, 1) infinite;
        }

        .success-badge-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 26px;
            height: 26px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .success-badge-badge i {
            color: #22c55e !important;
            font-size: 1.15rem;
            display: block;
            line-height: 1;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.95); }
            50% { transform: scale(1.05); }
            100% { transform: scale(0.95); }
        }

        /* Specific CSS rules for success popups */
        .swal2-popup.custom-modal-success {
            border-radius: 24px !important;
            padding: 30px 20px 20px !important;
            max-width: 340px !important;
            background: rgba(255, 255, 255, 0.94) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.15) !important;
        }

        .swal2-popup.custom-modal-success .swal2-timer-progress-bar {
            background: linear-gradient(90deg, #22c55e, #14b8a6) !important;
            height: 3px !important;
            border-radius: 99px !important;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Greeting -->
        @php
            $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
            if ($hour >= 5 && $hour < 11) {
                $greeting = 'Selamat Pagi';
                $icon = 'bi-brightness-high-fill';
            } elseif ($hour >= 11 && $hour < 15) {
                $greeting = 'Selamat Siang';
                $icon = 'bi-sun-fill';
            } elseif ($hour >= 15 && $hour < 18) {
                $greeting = 'Selamat Sore';
                $icon = 'bi-sunset-fill';
            } else {
                $greeting = 'Selamat Malam';
                $icon = 'bi-moon-stars-fill';
            }

            $signalValue = strtolower(trim((string) data_get($user, 'kualitas_sinyal', data_get($user, 'sinyal', 'bagus'))));
            $signalClass = 'signal-good';
            $signalLabel = 'Good';

            if (in_array($signalValue, ['kurang', 'sedang', 'warning', 'kuning'], true)) {
                $signalClass = 'signal-warning';
                $signalLabel = 'Medium';
            } elseif (in_array($signalValue, ['jelek', 'buruk', 'bad', 'merah'], true)) {
                $signalClass = 'signal-bad';
                $signalLabel = 'Low';
            }

            $unreadInformationCount = $unreadInformationCount ?? session('customer_unread_information_count', 0);

            $unpaidTagihanCount = $tagihanBelum ?? 0;
        @endphp

        <div class="header-greeting mt-4 mb-4">
            <div class="network-status-card {{ $signalClass }}">
                <span class="network-status-dot"></span>
                <span>{{ $signalLabel }}</span>
            </div>
        </div>

        <!-- Virtual Card -->
        <div class="virtual-card-wrapper">
            <div class="virtual-card">
                <div class="vc-bg-shapes"></div>
                <div class="vc-bg-pattern"></div>
                <div class="vc-content">
                    <div class="vc-top">
                        <div class="vc-logo">{{ $greeting }} <i class="bi {{ $icon }}"></i></div>
                        <div class="vc-status">Aktif</div>
                    </div>
                    <div class="vc-middle">
                        <div class="vc-number">{{ $user->nomer_id ?? 'JMK-000000' }}</div>
                    </div>
                    <div class="vc-bottom">
                        <div class="vc-name">{{ strtoupper($user->nama_lengkap ?? 'PELANGGAN') }}</div>
                        <div class="vc-brand"><em>JMK</em></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-title">Menu Cepat</div>
        <div class="modern-menu-grid">
            <!-- Tagihan -->
            <a href="/dashboard/customer/tagihan" class="modern-menu-item" wire:navigate.hover data-customer-prefetch>
                <div class="modern-menu-icon" style="color: #0ea5e9;">
                    <i class="bi bi-receipt"></i>
                    @if($unpaidTagihanCount > 0)
                        <span class="modern-menu-notification-count" aria-label="{{ $unpaidTagihanCount }} tagihan belum dibayar">{{ $unpaidTagihanCount > 99 ? '99+' : $unpaidTagihanCount }}</span>
                    @endif
                    <div class="modern-menu-badge badge-purple"><i class="bi bi-plus"></i></div>
                </div>
                <div class="modern-menu-text">Tagihan</div>
            </a>

            <!-- Kwitansi -->
            <a href="/dashboard/customer/tagihan/selesai" class="modern-menu-item" wire:navigate.hover data-customer-prefetch>
                <div class="modern-menu-icon" style="color: #10b981;">
                    <i class="bi bi-file-earmark-text"></i>
                    <div class="modern-menu-badge badge-blue"><span style="font-size: 8px;">Rp</span></div>
                </div>
                <div class="modern-menu-text">Kwitansi</div>
            </a>

            <!-- Chat CS -->
            <a href="https://layanan.jernih.net.id/dashboard/customer/chat" class="modern-menu-item">
                <div class="modern-menu-icon" style="color: #f59e0b;">
                    <i class="bi bi-headset"></i>
                    <div class="modern-menu-badge badge-green"><i class="bi bi-chat"></i></div>
                </div>
                <div class="modern-menu-text">Chat CS</div>
            </a>

            <!-- Chat Admin -->
            <a href="https://layanan.jernih.net.id/dashboard/customer/chat-billing" class="modern-menu-item">
                <div class="modern-menu-icon" style="color: #8b5cf6;">
                    <i class="bi bi-person-badge"></i>
                    <div class="modern-menu-badge badge-orange"><i class="bi bi-shield-check"></i></div>
                </div>
                <div class="modern-menu-text">Chat Admin</div>
            </a>

            <!-- Riwayat -->
            <a href="/dashboard/customer/riwayat" class="modern-menu-item" wire:navigate.hover data-customer-prefetch>
                <div class="modern-menu-icon" style="color: #64748b;">
                    <i class="bi bi-clock-history"></i>
                    <div class="modern-menu-badge badge-slate"><i class="bi bi-arrow-repeat"></i></div>
                </div>
                <div class="modern-menu-text">Riwayat</div>
            </a>

            <!-- FAQ -->
            <a href="/dashboard/customer/faq" class="modern-menu-item" wire:navigate.hover data-customer-prefetch>
                <div class="modern-menu-icon" style="color: #ec4899;">
                    <i class="bi bi-question-circle"></i>
                    <div class="modern-menu-badge badge-blue"><i class="bi bi-info"></i></div>
                </div>
                <div class="modern-menu-text">FAQ</div>
            </a>

            <!-- Informasi -->
            <a href="{{ route('customer.informasi') }}" class="modern-menu-item" wire:navigate.hover data-customer-prefetch>
                <div class="modern-menu-icon" style="color: #7c3aed;">
                    <i class="bi bi-megaphone"></i>
                    @if($unreadInformationCount > 0)
                        <span class="modern-menu-notification-count" aria-label="{{ $unreadInformationCount }} informasi baru">{{ $unreadInformationCount > 99 ? '99+' : $unreadInformationCount }}</span>
                    @endif
                    <div class="modern-menu-badge badge-purple"><i class="bi bi-bell"></i></div>
                </div>
                <div class="modern-menu-text">Informasi</div>
            </a>

            <!-- Tulis Masukan -->
            <a href="{{ route('customer.feedback.create') }}" class="modern-menu-item" wire:navigate.hover data-customer-prefetch>
                <div class="modern-menu-icon" style="color: #2563eb;">
                    <i class="bi bi-chat-square-text"></i>
                    <div class="modern-menu-badge badge-green"><i class="bi bi-send"></i></div>
                </div>
                <div class="modern-menu-text">Tulis Masukan</div>
            </a>
        </div>

    </div>

    @include('content.apps.Customer.tagihan.bottom-navbar', ['active' => 'home'])

    <!-- Modal Detail Iklan -->
    <div id="iklan-modal-overlay" class="iklan-modal-overlay">
        <div class="iklan-modal-content">
            <button class="iklan-modal-close" onclick="closeIklanModal()">
                <i class="bi bi-x"></i>
            </button>
            <img id="iklan-modal-image" class="iklan-modal-image" src="" alt="" style="display: none;">
            <div class="iklan-modal-body">
                <span id="iklan-modal-badge" class="iklan-modal-badge"></span>
                <h3 id="iklan-modal-title" class="iklan-modal-title"></h3>
                <p id="iklan-modal-message" class="iklan-modal-message"></p>
                <div class="iklan-modal-footer">
                    <div id="iklan-modal-time" class="iklan-modal-time">
                        <i class="bi bi-clock"></i>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zoom Image Overlay -->
    <div id="zoom-overlay" class="zoom-overlay" onclick="closeZoom()">
        <img id="zoom-image" src="" alt="">
    </div>

    <script>

        // Logout
        document.getElementById('btn-logout').addEventListener('click', () => {
            Swal.fire({
                title: 'Yakin Ingin Keluar?',
                html: `<div class="tw-text-center"><span class="tw-text-sm tw-text-slate-500 tw-leading-relaxed">Jika Anda keluar, Anda <b>tidak akan menerima notifikasi</b> tagihan baru dan pengingat jatuh tempo secara realtime. Tetap login untuk selalu update informasi tagihan Anda.</span></div>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tetap Keluar',
                cancelButtonText: 'Batal, Tetap Login',
                customClass: {
                    container: 'swal-tailwind-backdrop',
                    popup: 'swal-tailwind-popup',
                    confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                    cancelButton: 'swal-tailwind-cancel'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInUp animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutDown animate__faster'
                },
                buttonsStyling: false,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('/customer/logout', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(response => {
                            if (response.ok) {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: 'Anda telah keluar dari akun',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false,
                                    customClass: {
                                        container: 'swal-tailwind-backdrop',
                                        popup: 'swal-tailwind-popup'
                                    },
                                    showClass: {
                                        popup: 'animate__animated animate__fadeInUp animate__faster'
                                    },
                                    hideClass: {
                                        popup: 'animate__animated animate__fadeOutDown animate__faster'
                                    }
                                }).then(() => {
                                    window.location.href = '/';
                                });
                            } else {
                                throw new Error('Logout failed');
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Gagal keluar dari akun. Silakan coba lagi.',
                                icon: 'error',
                                customClass: {
                                    container: 'swal-tailwind-backdrop',
                                    popup: 'swal-tailwind-popup',
                                    confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger'
                                },
                                buttonsStyling: false,
                                showClass: {
                                    popup: 'animate__animated animate__fadeInUp animate__faster'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutDown animate__faster'
                                }
                            });
                        });
                }
            });
        });

        // ========== MODAL DETAIL IKLAN ==========
        function openIklanModal(id, type, title, message, image, time) {
            const modal = document.getElementById('iklan-modal-overlay');
            const modalImage = document.getElementById('iklan-modal-image');
            const modalBadge = document.getElementById('iklan-modal-badge');
            const modalTitle = document.getElementById('iklan-modal-title');
            const modalMessage = document.getElementById('iklan-modal-message');
            const modalTime = document.getElementById('iklan-modal-time').querySelector('span');

            // Set badge
            let badgeIcon = '';
            let badgeText = '';

            if (type === 'maintenance') {
                badgeIcon = '<i class="bi bi-tools"></i>';
                badgeText = 'Maintenance';
            } else if (type === 'informasi') {
                badgeIcon = '<i class="bi bi-info-circle"></i>';
                badgeText = 'Informasi';
            } else {
                badgeIcon = '<i class="bi bi-megaphone"></i>';
                badgeText = 'Iklan';
            }

            modalBadge.className = 'iklan-modal-badge ' + type;
            modalBadge.innerHTML = badgeIcon + ' ' + badgeText;

            // Set content
            modalTitle.textContent = title;
            modalMessage.textContent = message;
            modalTime.textContent = time;

            // Set image
            if (image) {
                modalImage.src = image;
                modalImage.style.display = 'block';
                modalImage.onclick = function (e) {
                    e.stopPropagation();
                    openZoom(image);
                };
            } else {
                modalImage.style.display = 'none';
            }

            // Show modal
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeIklanModal() {
            const modal = document.getElementById('iklan-modal-overlay');
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }

        // Close modal when clicking outside
        document.getElementById('iklan-modal-overlay').addEventListener('click', function (e) {
            if (e.target === this) {
                closeIklanModal();
            }
        });

        // ========== IMAGE ZOOM ==========
        function openZoom(imageSrc) {
            const zoomOverlay = document.getElementById('zoom-overlay');
            const zoomImage = document.getElementById('zoom-image');

            zoomImage.src = imageSrc;
            zoomOverlay.classList.add('show');
        }

        function closeZoom() {
            const zoomOverlay = document.getElementById('zoom-overlay');
            zoomOverlay.classList.remove('show');
        }

        // ESC key to close modals
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeIklanModal();
                closeZoom();
            }
        });
    </script>

    @include('content.apps.partials.firebase-messaging', ['user' => $user])

    <script>
        const nomerid = "{{ $user->nomer_id }}";
        const customerHasFcmToken = @json(filled($user?->fcm_token));
        let firebasePermissionModalOpen = false;

        function checkNotificationStatus() {
            if (!('Notification' in window)) return;

            if (shouldPromptFirebasePermission()) {
                scheduleFirebasePermissionPrompt(1200);
            } else if (Notification.permission === 'granted') {
                if (typeof window.syncFirebaseMessaging === 'function') {
                    setTimeout(() => window.syncFirebaseMessaging(), 800);
                }
            }
        }

        function shouldPromptFirebasePermission() {
            if (!('Notification' in window)) return false;

            const isNotificationDisabled = localStorage.getItem('firebase_messaging_disabled') === 'true';
            const isPermissionNotGranted = Notification.permission !== 'granted';

            // We should prompt if:
            // 1. Manually disabled via profile switch (isNotificationDisabled is true)
            // 2. Or browser permission is not yet granted (isPermissionNotGranted is true)
            // 3. Or the database does not have any FCM token (!customerHasFcmToken)
            return isNotificationDisabled || isPermissionNotGranted || !customerHasFcmToken;
        }


        function scheduleFirebasePermissionPrompt(delay = 15000) {
            if (!shouldPromptFirebasePermission() || firebasePermissionModalOpen) return;

            setTimeout(() => {
                if (shouldPromptFirebasePermission() && !firebasePermissionModalOpen) {
                    showFirebasePermissionPopup();
                }
            }, delay);
        }

        function showFirebasePermissionPopup() {
            if (firebasePermissionModalOpen || !shouldPromptFirebasePermission()) return;

            firebasePermissionModalOpen = true;

            Swal.fire({
                html: `
            <div class="text-center">
                <!-- Icon Bell Glass -->
                <div class="glass-icon-wrap">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <h3 class="glass-modal-title">Aktifkan Notifikasi</h3>
                <p class="glass-modal-text">
                    Izinkan notifikasi agar Anda mendapatkan pemberitahuan realtime saat ada tagihan baru atau pengingat jatuh tempo langsung ke perangkat Anda.
                </p>
            </div>
        `,
                confirmButtonText: '<i class="bi bi-check-circle-fill" style="margin-right:6px;"></i> Iya, Saya Mau',
                showCancelButton: false,
                customClass: {
                    popup: 'custom-modal',
                    confirmButton: 'custom-btn',
                    cancelButton: 'd-none'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInUp animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutDown animate__faster'
                },
                allowOutsideClick: false,
                showCloseButton: true,
                backdrop: 'rgba(15,23,42,0.45)'
            }).then((result) => {
                if (result.isConfirmed) {
                    requestFirebasePermission();
                    return;
                }

                firebasePermissionModalOpen = false;
                scheduleFirebasePermissionPrompt();
            });
        }

        async function requestFirebasePermission() {
            Swal.fire({
                html: `
                    <div class="text-center">
                        <div class="modern-spinner"></div>
                        <h3 class="glass-modal-title" style="margin-bottom: 8px;">Mohon Tunggu</h3>
                        <p class="glass-modal-text" style="margin-bottom: 0;">Memproses permintaan notifikasi...</p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                showCancelButton: false,
                customClass: {
                    popup: 'custom-modal'
                }
            });

            try {
                await waitForFirebaseMessaging();

                if (typeof window.enableFirebaseMessaging !== 'function') {
                    throw new Error('Firebase Messaging belum siap.');
                }

                const token = await window.enableFirebaseMessaging();

                if (token) {
                    firebasePermissionModalOpen = false;
                    showSuccessToast();
                    return;
                }

                if (Notification.permission === 'denied') {
                    firebasePermissionModalOpen = false;
                    Swal.fire({
                        icon: 'warning',
                        title: 'Notifikasi Diblokir',
                        html: `
                    Untuk mengaktifkan notifikasi:<br><br>
                    <strong>1.</strong> Klik ikon <i class="bi bi-lock-fill"></i> di address bar<br>
                    <strong>2.</strong> Ubah Notifikasi ke "Izinkan"<br>
                    <strong>3.</strong> Refresh halaman ini
                `,
                        confirmButtonText: 'Mengerti'
                    }).then(() => {
                        scheduleFirebasePermissionPrompt();
                    });
                    return;
                }

                Swal.fire({
                    icon: 'info',
                    title: 'Notifikasi Belum Aktif',
                    text: 'Izin notifikasi belum diberikan.',
                    confirmButtonText: 'Mengerti'
                }).then(() => {
                    firebasePermissionModalOpen = false;
                    scheduleFirebasePermissionPrompt();
                });
            } catch (error) {
                console.error('Firebase notification failed:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengaktifkan',
                    text: error?.message || 'Notifikasi belum bisa diaktifkan. Coba refresh halaman lalu ulangi.',
                    confirmButtonText: 'Mengerti'
                }).then(() => {
                    firebasePermissionModalOpen = false;
                    scheduleFirebasePermissionPrompt();
                });
            }
        }

        function waitForFirebaseMessaging() {
            if (typeof window.enableFirebaseMessaging === 'function') {
                return Promise.resolve();
            }

            return new Promise((resolve, reject) => {
                const timeout = setTimeout(() => {
                    window.removeEventListener('firebase-messaging-loaded', onReady);
                    reject(new Error('Firebase Messaging timeout.'));
                }, 5000);

                function onReady() {
                    clearTimeout(timeout);
                    resolve();
                }

                window.addEventListener('firebase-messaging-loaded', onReady, { once: true });
            });
        }

        function showSuccessToast() {
            Swal.fire({
                html: `
                    <div class="text-center" style="padding: 10px 0;">
                        <!-- Animated modern success icon badge -->
                        <div class="glass-success-icon-wrap animate__animated animate__zoomIn">
                            <i class="bi bi-bell-fill"></i>
                            <div class="success-badge-badge">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                        </div>
                        <h3 class="glass-modal-title animate__animated animate__fadeInUp animate__faster" style="margin-top: 12px; margin-bottom: 8px;">
                            Notifikasi Aktif!
                        </h3>
                        <p class="glass-modal-text animate__animated animate__fadeInUp animate__faster" style="margin-bottom: 0; color: #475569;">
                            Selamat! Notifikasi tagihan dan pengingat jatuh tempo berhasil diaktifkan di perangkat ini.
                        </p>
                    </div>
                `,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                backdrop: 'rgba(15, 23, 42, 0.45)',
                customClass: {
                    popup: 'custom-modal-success'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInUp animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutDown animate__faster'
                }
            });
        }

        setInterval(function () {
            if (!('Notification' in window)) return;

            const currentPermission = Notification.permission;
            const lastPermission = localStorage.getItem('last_permission_status');
            if (lastPermission && lastPermission !== currentPermission) {
                if (currentPermission === 'granted' && typeof window.syncFirebaseMessaging === 'function') {
                    window.syncFirebaseMessaging();
                }
            }
            localStorage.setItem('last_permission_status', currentPermission);
        }, 5000);

        function getGreeting() {
            const now = new Date();
            // Konversi ke WIB (UTC+7)
            const wibOffset = 7 * 60; // offset dalam menit
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const wibTime = new Date(utc + (wibOffset * 60000));
            const hour = wibTime.getHours();

            if (hour >= 5 && hour < 11) {
                return 'Selamat Pagi! <i class="bi bi-brightness-high-fill"></i>';
            } else if (hour >= 11 && hour < 15) {
                return 'Selamat Siang! <i class="bi bi-sun-fill"></i>';
            } else if (hour >= 15 && hour < 18) {
                return 'Selamat Sore! <i class="bi bi-sunset-fill"></i>';
            } else {
                return 'Selamat Malam! <i class="bi bi-moon-stars-fill"></i>';
            }
        }


        // ========== POLLING UNTUK TAGIHAN BARU ==========
        const userNomerId = "{{ $user->nomer_id }}";
        let isModalShown = false;

        function checkForNewNotifications() {
            if (isModalShown || firebasePermissionModalOpen) return;
            fetch('/api/check-pending-notifications/' + encodeURIComponent(userNomerId), {
                headers: { 'Accept': 'application/json' },
            })
                .then((response) => response.ok ? response.json() : Promise.reject(response))
                .then(function (response) {
                    if (response.has_notification && !isModalShown) {
                        isModalShown = true;

                        const totalTagihan = response.total_tagihan || 1;
                        const tunggakanCount = response.tunggakan_count || 0;
                        const bulanTunggakan = response.bulan_tunggakan || 0;

                        // Tentukan isi berdasarkan status
                        const hasTunggakan = tunggakanCount > 0;

                        // Teks header
                        const headerSubtitle = hasTunggakan
                            ? `Anda memiliki <strong>${totalTagihan}</strong> tagihan yang belum dibayar`
                            : `Anda memiliki <strong>${totalTagihan}</strong> tagihan yang menunggu pembayaran`;

                        // Warna icon berdasarkan status
                        const iconColor = hasTunggakan ? '#ef4444' : '#3b82f6';
                        const iconName = hasTunggakan ? 'bi-exclamation-triangle-fill' : 'bi-receipt-cutoff';

                        // Badge tunggakan (hanya muncul jika ada)
                        const tunggakanBadge = hasTunggakan ? `
                <div style="background:linear-gradient(135deg,#fef2f2 0%,#fee2e2 100%);
                            border:1px solid #fca5a5;
                            border-radius:14px;
                            padding:14px 16px;
                            margin-bottom:14px;
                            display:flex;
                            align-items:center;
                            gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:#fca5a5;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-clock-history" style="color:#dc2626;font-size:1.25rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:0.8125rem;font-weight:700;color:#dc2626;margin-bottom:2px;">
                            ?? Tunggakan ${tunggakanCount} Tagihan
                        </div>
                        <div style="font-size:0.75rem;color:#ef4444;font-weight:500;">
                            Sudah lewat jatuh tempo ${bulanTunggakan > 0 ? `selama <strong>${bulanTunggakan} bulan</strong>` : 'beberapa waktu'}
                        </div>
                    </div>
                </div>` : '';

                        // Stat cards bawah
                        const statCards = hasTunggakan ? `
                <div class="modal-stat-row">
                    <div class="modal-stat-item" style="border-color:#fca5a5;background:#fef2f2;">
                        <span class="modal-stat-icon"><i class="bi bi-calendar-x" style="color:#ef4444;"></i></span>
                        <div class="modal-stat-label" style="color:#dc2626;">${tunggakanCount} Tunggakan</div>
                    </div>
                    <div class="modal-stat-item">
                        <span class="modal-stat-icon"><i class="bi bi-receipt" style="color:#0f172a;"></i></span>
                        <div class="modal-stat-label">${totalTagihan} Tagihan</div>
                    </div>
                </div>` : `
                <div class="modal-stat-row">
                    <div class="modal-stat-item">
                        <span class="modal-stat-icon"><i class="bi bi-lightning-charge-fill" style="color:#f59e0b;"></i></span>
                        <div class="modal-stat-label">Bayar Cepat</div>
                    </div>
                    <div class="modal-stat-item">
                        <span class="modal-stat-icon"><i class="bi bi-shield-check" style="color:#10b981;"></i></span>
                        <div class="modal-stat-label">Mudah & Aman</div>
                    </div>
                </div>`;

                        Swal.fire({
                            html: `
                    <div class="modal-content-wrapper">
                        <div class="modal-header-banner" style="${hasTunggakan ? 'background:linear-gradient(135deg,#7f1d1d 0%,#991b1b 60%,#7f1d1d 100%);' : ''}">
                            <div class="modal-icon-wrap" style="${hasTunggakan ? 'border-color:rgba(255,255,255,0.25);' : ''}">
                                <div class="modal-icon-inner">
                                    <i class="bi ${iconName}" style="color:${iconColor};"></i>
                                </div>
                            </div>
                            <div class="modal-header-title">${getGreeting()}</div>
                            <div class="modal-header-subtitle">${headerSubtitle}</div>
                        </div>
                        <div class="modal-body-card">
                            ${tunggakanBadge}
                            ${statCards}
                        </div>
                    </div>
                `,
                            confirmButtonText: '<i class="bi bi-arrow-right-circle-fill" style="margin-right:6px;"></i> Lihat Tagihan Sekarang',
                            showCancelButton: false,
                            customClass: {
                                popup: 'custom-modal',
                                confirmButton: hasTunggakan ? 'custom-btn custom-btn-danger' : 'custom-btn'
                            },
                            showClass: {
                                popup: 'animate__animated animate__zoomIn animate__faster'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__zoomOut animate__faster'
                            },
                            allowOutsideClick: false,
                            showCloseButton: true,
                            backdrop: 'rgba(15,23,42,0.65)'
                        }).then(result => {
                            if (result.isConfirmed) {
                                window.location.href = '/dashboard/customer/tagihan';
                            }
                            setTimeout(() => isModalShown = false, 5000);
                        });
                    }
                })
                .catch(function (error) {
                    console.error('Error polling:', error);
                });
        }

        // ========== INISIALISASI ==========
        document.addEventListener('DOMContentLoaded', function () {
            checkNotificationStatus();

            // Modal tagihan baru/tunggakan dimatikan sementara.
            // setTimeout(checkForNewNotifications, 3000);
            // setInterval(checkForNewNotifications, 21600000);
        });
    </script>

</body>

</html>
