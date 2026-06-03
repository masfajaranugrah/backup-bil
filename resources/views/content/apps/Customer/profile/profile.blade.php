@php
    $user = auth('customer')->user();
    $paket = $user->paket ?? null;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    @include('content.apps.Customer.partials.disable-zoom')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile - {{ $user->nama_lengkap ?? 'Pelanggan' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            padding: 0 0 90px 0;
            min-height: 100vh;
            color: #0f172a;
        }

        .container {
            max-width: 680px;
            padding: 0 16px;
        }

        /* Hero */
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            padding: 40px 20px 80px;
            margin: 0 -16px;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            width: 260px;
            height: 260px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            top: -100px;
            right: -80px;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .profile-avatar-wrap {
            position: relative;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            margin: 0 auto 16px;
        }

        .profile-avatar-inner {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffffff 0%, #e0f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            color: #0f172a;
        }

        .hero-name {
            font-size: 1.375rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
            position: relative;
            z-index: 2;
        }

        .hero-sub {
            font-size: 0.875rem;
            color: #94a3b8;
            position: relative;
            z-index: 2;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 14px;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 10px;
            position: relative;
            z-index: 2;
        }

        .status-pill.aktif {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-pill.nonaktif {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Card Container */
        .profile-card {
            background: white;
            border-radius: 20px 20px 0 0;
            margin-top: -36px;
            padding: 24px 0 8px;
            position: relative;
            z-index: 3;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.05);
        }

        /* Paket card */
        .paket-card {
            margin: 0 20px 20px;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            border-radius: 16px;
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .paket-card::after {
            content: '';
            position: absolute;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
            top: -40px;
            right: -30px;
        }

        .paket-label {
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 6px;
        }

        .paket-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .paket-speed {
            font-size: 0.8125rem;
            color: #94a3b8;
            margin-bottom: 14px;
        }

        .paket-price {
            font-size: 1.125rem;
            font-weight: 700;
            color: #38bdf8;
        }

        .paket-price span {
            font-size: 0.75rem;
            font-weight: 500;
            color: #94a3b8;
        }

        /* Section title */
        .section-title {
            font-size: 0.8125rem;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0 20px;
            margin-bottom: 12px;
        }

        /* Info items */
        .info-list {
            border-top: 1px solid #f1f5f9;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .info-icon.blue {
            background: #eff6ff;
            color: #3b82f6;
        }

        .info-icon.green {
            background: #f0fdf4;
            color: #22c55e;
        }

        .info-icon.amber {
            background: #fffbeb;
            color: #f59e0b;
        }

        .info-icon.slate {
            background: #f1f5f9;
            color: #64748b;
        }

        .info-icon.rose {
            background: #fff1f2;
            color: #f43f5e;
        }

        .info-meta {
            flex: 1;
            min-width: 0;
        }

        .info-label {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 500;
            margin-bottom: 1px;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Menu items */
        .menu-section {
            padding: 16px 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.15s ease;
            margin-bottom: 8px;
            text-decoration: none;
            color: #0f172a;
            width: 100%;
            font-family: 'Inter', sans-serif;
            text-align: left;
        }

        .menu-item:hover {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #0f172a;
        }

        .menu-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .menu-icon.blue {
            background: #eff6ff;
            color: #3b82f6;
        }

        .menu-icon.amber {
            background: #fffbeb;
            color: #f59e0b;
        }

        .menu-icon.green {
            background: #f0fdf4;
            color: #22c55e;
        }

        .notification-setting {
            cursor: default;
        }

        .notification-copy {
            flex: 1;
            min-width: 0;
        }

        .menu-text {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .notification-status {
            margin-top: 2px;
            font-size: 0.74rem;
            font-weight: 500;
            color: #94a3b8;
            line-height: 1.35;
        }

        .mui-switch {
            position: relative;
            display: inline-flex;
            width: 52px;
            height: 32px;
            flex-shrink: 0;
        }

        .mui-switch input {
            width: 0;
            height: 0;
            opacity: 0;
        }

        .mui-switch-track {
            position: absolute;
            inset: 0;
            border-radius: 999px;
            background: #cbd5e1;
            transition: background 0.2s ease, opacity 0.2s ease;
            box-shadow: inset 0 0 0 1px rgba(15, 23, 42, 0.06);
        }

        .mui-switch-thumb {
            position: absolute;
            top: 4px;
            left: 4px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.24);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .mui-switch input:checked + .mui-switch-track {
            background: #22c55e;
        }

        .mui-switch input:checked + .mui-switch-track .mui-switch-thumb {
            transform: translateX(20px);
        }

        .mui-switch input:focus-visible + .mui-switch-track {
            outline: 3px solid rgba(34, 197, 94, 0.22);
            outline-offset: 2px;
        }

        .mui-switch input:disabled + .mui-switch-track {
            opacity: 0.6;
            cursor: wait;
        }

        .notification-blur-container.swal2-container {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .notification-result-popup {
            width: min(92vw, 360px) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            border-radius: 24px !important;
            background: rgba(255, 255, 255, 0.92) !important;
            box-shadow: 0 24px 70px rgba(15, 23, 42, 0.22) !important;
            padding: 22px !important;
        }

        .notification-result {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            text-align: center;
        }

        .notification-result-icon {
            width: 58px;
            height: 58px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.85rem;
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.18);
        }

        .notification-result-icon.success {
            background: linear-gradient(135deg, #22c55e, #14b8a6);
        }

        .notification-result-icon.muted {
            background: linear-gradient(135deg, #64748b, #334155);
        }

        .notification-result-title {
            margin: 2px 0 0;
            color: #0f172a;
            font-size: 1.08rem;
            font-weight: 800;
        }

        .notification-result-text {
            margin: 0;
            max-width: 280px;
            color: #64748b;
            font-size: 0.86rem;
            line-height: 1.45;
        }

        .menu-arrow {
            color: #cbd5e1;
            font-size: 0.875rem;
        }

        /* Logout */
        .logout-section {
            padding: 20px 20px 0;
        }

        .logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 20px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 14px;
            color: #dc2626;
            font-size: 0.9375rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .logout-btn:hover {
            background: #fee2e2;
            border-color: #fca5a5;
        }

        .app-version {
            text-align: center;
            padding: 16px 20px;
            color: #cbd5e1;
            font-size: 0.75rem;
        }

        /* Bottom nav */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 72px;
            background: #ffffff;
            display: flex;
            justify-content: space-around;
            align-items: center;
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

        /* ========== SWEETALERT TAILWIND STYLING ========== */
        .swal2-container.swal2-backdrop-show {
            background: rgba(15, 23, 42, 0.5) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
        }

        .swal-tailwind-backdrop {
            background: rgba(15, 23, 42, 0.5) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
        }

        .swal-tailwind-popup {
            border-radius: 1.5rem !important;
            border: 1px solid rgba(226, 232, 240, 0.9) !important;
            background: #ffffff !important;
            padding: 2rem !important;
            box-shadow: 0 25px 70px rgba(15, 23, 42, 0.22) !important;
            max-width: 380px !important;
        }

        .swal-tailwind-popup .swal2-title {
            font-size: 1.35rem !important;
            font-weight: 800 !important;
            color: #0f172a !important;
            margin-top: 0.5rem !important;
            letter-spacing: -0.02em !important;
        }

        .swal-tailwind-popup .swal2-html-container {
            font-size: 0.95rem !important;
            line-height: 1.6 !important;
            color: #64748b !important;
            margin-top: 0.75rem !important;
        }

        .swal-tailwind-popup .swal2-actions {
            margin-top: 1.75rem !important;
            gap: 0.75rem !important;
            width: 100% !important;
            justify-content: stretch !important;
        }

        .swal-tailwind-confirm {
            flex: 1 !important;
            border-radius: 0.75rem !important;
            border: 0 !important;
            padding: 0.75rem 1.25rem !important;
            font-weight: 700 !important;
            color: #ffffff !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15) !important;
            font-family: 'Inter', sans-serif !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .swal-tailwind-confirm-danger {
            background: #ef4444 !important;
        }
        .swal-tailwind-confirm-danger:hover {
            background: #dc2626 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.25) !important;
        }

        .swal-tailwind-cancel {
            flex: 1 !important;
            border-radius: 0.75rem !important;
            border: 0 !important;
            background: #f1f5f9 !important;
            padding: 0.75rem 1.25rem !important;
            font-weight: 700 !important;
            color: #334155 !important;
            transition: all 0.2s ease !important;
            font-family: 'Inter', sans-serif !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .swal-tailwind-cancel:hover {
            background: #e2e8f0 !important;
            color: #0f172a !important;
            transform: translateY(-1px) !important;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- Hero Section -->
        <div class="hero-section">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar-inner">
                    {{ strtoupper(substr($user->nama_lengkap ?? 'P', 0, 1)) }}
                </div>
            </div>
            <div class="hero-name">{{ $user->nama_lengkap ?? 'Pelanggan' }}</div>
            <div class="hero-sub">ID: {{ $user->nomer_id ?? '-' }}</div>

            @if(in_array($user->status, ['active', 'aktif', 'approve']))
                <span class="status-pill aktif">
                    <i class="bi bi-check-circle-fill"></i> {{ ucfirst($user->status) }}
                </span>
            @else
                <span class="status-pill nonaktif">
                    <i class="bi bi-x-circle-fill"></i> {{ ucfirst($user->status ?? 'Tidak diketahui') }}
                </span>
            @endif
        </div>

        <!-- Profile Card -->
        <div class="profile-card">



            <!-- Info Akun -->
            <div class="section-title">Informasi Akun</div>
            <div class="info-list">
                <div class="info-item">
                    <div class="info-icon blue"><i class="bi bi-person-fill"></i></div>
                    <div class="info-meta">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $user->nama_lengkap ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon green"><i class="bi bi-fingerprint"></i></div>
                    <div class="info-meta">
                        <div class="info-label">Nomer ID</div>
                        <div class="info-value">{{ $user->nomer_id ?? '-' }}</div>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-icon amber"><i class="bi bi-whatsapp"></i></div>
                    <div class="info-meta">
                        <div class="info-label">Nomor WhatsApp</div>
                        <div class="info-value">{{ $user->no_whatsapp ?? '-' }}</div>
                    </div>
                </div>

                <!-- Menu -->
                <div class="menu-section">
                    <a href="/dashboard/customer/riwayat" class="menu-item">
                        <div class="menu-icon blue"><i class="bi bi-clock-history"></i></div>
                        <span class="menu-text">Riwayat Pembayaran</span>
                        <i class="bi bi-chevron-right menu-arrow"></i>
                    </a>
                    <a href="/dashboard/customer/faq" class="menu-item">
                        <div class="menu-icon amber"><i class="bi bi-question-circle"></i></div>
                        <span class="menu-text">FAQ & Bantuan</span>
                        <i class="bi bi-chevron-right menu-arrow"></i>
                    </a>
                    <div class="menu-item notification-setting" id="btn-enable-fcm" role="button" tabindex="0">
                        <div class="menu-icon green"><i class="bi bi-bell-fill"></i></div>
                        <div class="notification-copy">
                            <div class="menu-text">Notifikasi Tagihan</div>
                            <div class="notification-status" id="fcm-status-text">
                                {{ $user->fcm_token ? 'Aktif di perangkat ini' : 'Aktifkan untuk tagihan dan info terbaru' }}
                            </div>
                        </div>
                        <label class="mui-switch" for="fcm-switch" aria-label="Aktifkan notifikasi tagihan">
                            <input type="checkbox" id="fcm-switch" {{ $user->fcm_token ? 'checked' : '' }}>
                            <span class="mui-switch-track">
                                <span class="mui-switch-thumb"></span>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Logout -->
                <div class="logout-section">
                    <button class="logout-btn" id="btn-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        Keluar dari Akun
                    </button>
                </div>

                <div class="app-version">Billing JMK v1.0 � PT Jernih Multi Komunikasi</div>
            </div>
        </div>

        @include('content.apps.Customer.tagihan.bottom-navbar', ['active' => 'profile'])
        @include('content.apps.partials.firebase-messaging', ['user' => $user])

        <script>
            document.getElementById('btn-logout').addEventListener('click', () => {
                Swal.fire({
                    title: 'Yakin Ingin Keluar?',
                    html: `<div class="text-center"><span style="font-size: 0.95rem; line-height: 1.5; color: #64748b;">Jika Anda keluar, Anda <b>tidak akan menerima notifikasi</b> tagihan baru dan pengingat jatuh tempo secara realtime. Tetap login untuk selalu update informasi tagihan Anda.</span></div>`,
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
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('/customer/logout', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        }).then(r => {
                            if (r.ok) {
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
                                }).then(() => window.location.href = '/');
                            } else throw new Error();
                        }).catch(() => Swal.fire({
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
                        }));
                    }
                });
            });

            // Enable location permission
            const enableLocBtn = document.getElementById('enableLocationBtn');
            if (enableLocBtn) {
                enableLocBtn.addEventListener('click', async () => {
                    if (!navigator.geolocation) {
                        Swal.fire('Perangkat tidak mendukung', 'Browser Anda tidak mendukung akses lokasi.', 'warning');
                        return;
                    }

                    const requestOnce = () => new Promise((resolve) => {
                        navigator.geolocation.getCurrentPosition(
                            (pos) => resolve({ ok: true, pos }),
                            (err) => resolve({ ok: false, err }),
                            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                        );
                    });

                    const res = await requestOnce();
                    if (res.ok) {
                        Swal.fire('Lokasi aktif', 'Izin lokasi sudah diizinkan.', 'success');
                        return;
                    }

                    const steps = `
            <ol style="text-align:left; padding-left:20px; margin:0">
              <li>Ketuk ikon gembok / info situs di address bar.</li>
              <li>Ubah izin <b>Location</b> menjadi <b>Allow</b>.</li>
              <li>Muat ulang halaman, lalu tekan lagi tombol ini.</li>
            </ol>
        `;
                    Swal.fire({
                        icon: 'info',
                        title: 'Izin lokasi diblokir',
                        html: steps,
                        confirmButtonText: 'Oke, saya coba'
                    });
                });
            }

            async function waitForFirebaseMessaging() {
                if (typeof window.enableFirebaseMessaging === 'function') {
                    return;
                }

                await new Promise((resolve, reject) => {
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

            const enableFcmBtn = document.getElementById('btn-enable-fcm');
            const enableFcmSwitch = document.getElementById('fcm-switch');
            const fcmStatusText = document.getElementById('fcm-status-text');

            function setFcmSwitchLoading(isLoading) {
                if (!enableFcmSwitch) return;
                enableFcmSwitch.disabled = isLoading;
            }

            function markFcmEnabled() {
                if (enableFcmSwitch) {
                    enableFcmSwitch.checked = true;
                }
                if (fcmStatusText) {
                    fcmStatusText.textContent = 'Aktif di perangkat ini';
                }
            }

            function markFcmDisabled(message = 'Aktifkan untuk tagihan dan info terbaru') {
                if (enableFcmSwitch) {
                    enableFcmSwitch.checked = false;
                }
                if (fcmStatusText) {
                    fcmStatusText.textContent = message;
                }
            }

            function showNotificationResult(type) {
                const isEnabled = type === 'enabled';

                Swal.fire({
                    html: `
                        <div class="notification-result">
                            <div class="notification-result-icon ${isEnabled ? 'success' : 'muted'}">
                                <i class="bi ${isEnabled ? 'bi-bell-fill' : 'bi-bell-slash-fill'}"></i>
                            </div>
                            <div>
                                <h3 class="notification-result-title">
                                    ${isEnabled ? 'Notifikasi berhasil diaktifkan' : 'Notifikasi dinonaktifkan'}
                                </h3>
                                <p class="notification-result-text">
                                    ${isEnabled
                                        ? 'Info tagihan dan pengumuman akan dikirim ke perangkat ini.'
                                        : 'Token Firebase dan SID Webpushr sudah dihapus dari database.'}
                                </p>
                            </div>
                        </div>
                    `,
                    showConfirmButton: false,
                    timer: 1800,
                    timerProgressBar: true,
                    backdrop: 'rgba(15, 23, 42, 0.32)',
                    customClass: {
                        container: 'notification-blur-container',
                        popup: 'notification-result-popup'
                    }
                });
            }

            async function deleteFcmTokenFallback() {
                const endpoint = window.firebaseMessagingConfig?.deleteTokenEndpoint;
                if (!endpoint) {
                    throw new Error('Endpoint hapus token belum tersedia.');
                }

                const response = await fetch(endpoint, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal menonaktifkan notifikasi.');
                }
            }

            async function activateFirebaseNotification() {
                if (!('Notification' in window)) {
                    markFcmDisabled('Browser ini belum mendukung notifikasi');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Browser belum mendukung notifikasi',
                        confirmButtonColor: '#0f172a'
                    });
                    return;
                }

                if (fcmStatusText) {
                    fcmStatusText.textContent = 'Mengaktifkan notifikasi...';
                }

                setFcmSwitchLoading(true);

                try {

                    await waitForFirebaseMessaging();
                    const token = await window.enableFirebaseMessaging();

                    if (token) {
                        markFcmEnabled();
                        showNotificationResult('enabled');
                        return;
                    }

                    if (Notification.permission === 'denied') {
                        markFcmDisabled('Izin notifikasi diblokir di browser');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Notifikasi Diblokir',
                            html: 'Buka pengaturan situs di browser, ubah izin <b>Notifikasi</b> menjadi <b>Izinkan</b>, lalu refresh halaman ini.',
                            confirmButtonText: 'Mengerti'
                        });
                        return;
                    }

                    markFcmDisabled('Izin notifikasi belum diberikan');
                    Swal.fire({
                        icon: 'info',
                        title: 'Notifikasi belum aktif',
                        confirmButtonColor: '#0f172a'
                    });
                } catch (error) {
                    console.error('[FCM] Gagal aktivasi:', error);
                    markFcmDisabled(error?.message || 'Aktivasi notifikasi gagal');
                    Swal.fire({
                        icon: 'error',
                        title: error?.message || 'Aktivasi notifikasi gagal',
                        confirmButtonColor: '#0f172a'
                    });
                } finally {
                    setFcmSwitchLoading(false);
                }
            }

            async function deactivateFirebaseNotification() {
                if (fcmStatusText) {
                    fcmStatusText.textContent = 'Menonaktifkan notifikasi...';
                }

                setFcmSwitchLoading(true);

                try {
                    try {
                        await waitForFirebaseMessaging();
                    } catch (error) {
                        console.warn('[FCM] Firebase script belum siap, lanjut hapus token via endpoint.', error);
                    }

                    if (typeof window.disableFirebaseMessaging === 'function') {
                        await window.disableFirebaseMessaging();
                    } else {
                        await deleteFcmTokenFallback();
                        localStorage.setItem('firebase_messaging_disabled', 'true');
                        localStorage.removeItem('last_firebase_fcm_token');
                        localStorage.removeItem('firebase_messaging_config_signature');
                        localStorage.removeItem('firebase_messaging_browser_state_reset_signature');
                    }

                    markFcmDisabled();
                    showNotificationResult('disabled');
                } catch (error) {
                    console.error('[FCM] Gagal nonaktif:', error);
                    markFcmEnabled();
                    Swal.fire({
                        icon: 'error',
                        title: error?.message || 'Gagal menonaktifkan notifikasi',
                        confirmButtonColor: '#0f172a'
                    });
                } finally {
                    setFcmSwitchLoading(false);
                }
            }

            if (enableFcmBtn) {
                enableFcmBtn.addEventListener('click', (event) => {
                    if (event.target.closest('.mui-switch')) {
                        return;
                    }

                    event.preventDefault();

                    if (enableFcmSwitch?.checked) {
                        deactivateFirebaseNotification();
                    } else {
                        activateFirebaseNotification();
                    }
                });

                enableFcmBtn.addEventListener('keydown', (event) => {
                    if (event.key !== 'Enter' && event.key !== ' ') {
                        return;
                    }

                    event.preventDefault();

                    if (enableFcmSwitch?.checked) {
                        deactivateFirebaseNotification();
                    } else {
                        activateFirebaseNotification();
                    }
                });
            }

            if (enableFcmSwitch) {
                enableFcmSwitch.addEventListener('change', () => {
                    if (enableFcmSwitch.checked) {
                        activateFirebaseNotification();
                    } else {
                        deactivateFirebaseNotification();
                    }
                });
            }
        </script>
</body>

</html>
