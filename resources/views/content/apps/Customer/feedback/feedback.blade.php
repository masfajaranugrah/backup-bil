<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    @include('content.apps.Customer.partials.disable-zoom')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Kendala & Masukan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            padding: 0 0 96px;
            background: #f6f8fb;
            color: #111827;
            font-family: 'Inter', sans-serif;
        }

        .feedback-shell {
            width: min(100%, 720px);
            margin: 0 auto;
            padding: 0 16px 28px;
        }

        .feedback-hero {
            margin: 0 -16px 20px;
            padding: 20px 16px 30px;
            color: #fff;
            background:
                radial-gradient(circle at top right, rgba(16, 185, 129, 0.26), transparent 34%),
                linear-gradient(135deg, #111827 0%, #334155 100%);
        }

        .feedback-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 26px;
        }

        .back-button,
        .home-button {
            width: 42px;
            height: 42px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 14px;
            color: #fff;
            background: rgba(255, 255, 255, 0.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            backdrop-filter: blur(8px);
        }

        .back-button i,
        .home-button i {
            font-size: 1.25rem;
        }

        .hero-copy {
            max-width: 520px;
        }

        .hero-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            margin-bottom: 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            color: #d1fae5;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .hero-title {
            margin: 0 0 8px;
            font-size: clamp(1.7rem, 6vw, 2.3rem);
            font-weight: 800;
            line-height: 1.08;
            letter-spacing: 0;
        }

        .hero-text {
            margin: 0;
            color: #e5e7eb;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .feedback-card {
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        }

        .form-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .form-title-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: #ecfdf5;
            color: #059669;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex: 0 0 auto;
        }

        .form-title h2 {
            margin: 0;
            font-size: 1.12rem;
            font-weight: 800;
            color: #0f172a;
        }

        .form-title p {
            margin: 3px 0 0;
            color: #64748b;
            font-size: 0.84rem;
        }

        .form-label {
            color: #1f2937;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .form-control {
            border-color: #dbe2ea;
            border-radius: 14px;
            padding: 12px 14px;
            color: #111827;
            background: #fbfdff;
        }

        textarea.form-control {
            min-height: 170px;
            resize: vertical;
            line-height: 1.55;
        }

        .form-control:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.12);
            background: #fff;
        }

        .upload-box {
            position: relative;
            border: 1px dashed #b7c2d0;
            border-radius: 16px;
            padding: 14px;
            background: #f8fafc;
        }

        .upload-hint {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-bottom: 10px;
            color: #64748b;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        .upload-hint i {
            color: #059669;
            font-size: 1.2rem;
        }

        .submit-button {
            width: 100%;
            min-height: 52px;
            border: 0;
            border-radius: 16px;
            background: #059669;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 800;
            box-shadow: 0 12px 22px rgba(5, 150, 105, 0.24);
        }

        .submit-button:active {
            transform: translateY(1px);
        }

        .alert {
            border: 0;
            border-radius: 16px;
            font-size: 0.9rem;
        }

        .alert-success {
            color: #065f46;
            background: #dcfce7;
        }

        .alert-danger {
            color: #991b1b;
            background: #fee2e2;
        }

        .history-card {
            margin-top: 16px;
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            background: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
        }

        .history-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .history-title h2 {
            margin: 0;
            color: #0f172a;
            font-size: 1.04rem;
            font-weight: 800;
        }

        .history-count {
            padding: 6px 10px;
            border-radius: 999px;
            background: #ecfdf5;
            color: #059669;
            font-size: 0.76rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .history-list {
            display: grid;
            gap: 10px;
        }

        .history-item {
            padding: 13px;
            border: 1px solid #eef2f7;
            border-radius: 16px;
            background: #f8fafc;
        }

        .history-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 7px;
            color: #64748b;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .history-status {
            padding: 5px 8px;
            border-radius: 999px;
            background: #ecfdf5;
            color: #047857;
            font-size: 0.7rem;
            white-space: nowrap;
        }

        .history-message {
            margin: 0;
            color: #111827;
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .history-note {
            margin-top: 9px;
            padding: 10px 11px;
            border-radius: 13px;
            background: #fff;
            color: #475569;
            font-size: 0.82rem;
            line-height: 1.45;
        }

        .empty-history {
            margin: 0;
            padding: 14px;
            border-radius: 16px;
            background: #f8fafc;
            color: #64748b;
            font-size: 0.88rem;
        }

        @media (max-width: 420px) {
            .feedback-card,
            .history-card {
                border-radius: 18px;
            }
        }
    </style>
</head>
<body>
    <main class="feedback-shell">
        <section class="feedback-hero">
            <div class="feedback-topbar">
                <a href="{{ route('customer.tagihan.home') }}" class="back-button" aria-label="Kembali">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <a href="{{ route('customer.tagihan.home') }}" class="home-button" aria-label="Home">
                    <i class="bi bi-house-door"></i>
                </a>
            </div>

            <div class="hero-copy">
                <div class="hero-label">
                    <i class="bi bi-chat-square-text"></i>
                    Pusat Masukan
                </div>
                <h1 class="hero-title">Laporan Kendala & Masukan</h1>
                <p class="hero-text">
                    Sampaikan kendala atau ide pengembangan aplikasi agar admin mudah menindaklanjuti.
                </p>
            </div>
        </section>

        <section class="feedback-card">
            @if(session('feedback_success'))
                <div class="alert alert-success d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>{{ session('feedback_success') }}</div>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-bold mb-1">Periksa kembali input Anda.</div>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-title">
                <div class="form-title-icon">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h2>Tulis Masukan</h2>
                    <p>Jelaskan kendala atau masukan dengan singkat dan jelas.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('customer.feedback.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="message" class="form-label">Isi feedback / kendala / masukan</label>
                    <textarea
                        id="message"
                        name="message"
                        class="form-control @error('message') is-invalid @enderror"
                        placeholder="Contoh: Tombol pembayaran sulit ditemukan, atau saya ingin ada fitur notifikasi jatuh tempo..."
                        required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="attachment" class="form-label">Screenshot / lampiran jika ada</label>
                    <div class="upload-box">
                        <div class="upload-hint">
                            <i class="bi bi-paperclip"></i>
                            <span>Opsional. Format yang didukung: JPG, PNG, WEBP, atau PDF maksimal 5 MB.</span>
                        </div>
                        <input
                            id="attachment"
                            type="file"
                            name="attachment"
                            class="form-control @error('attachment') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp,.pdf">
                        @error('attachment')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="submit-button">
                    <i class="bi bi-send-fill"></i>
                    Kirim Masukan
                </button>
            </form>
        </section>

        <section class="history-card">
            <div class="history-title">
                <h2>Riwayat Masukan</h2>
                <span class="history-count">{{ ($feedbacks ?? collect())->count() }} terbaru</span>
            </div>

            @if(($feedbacks ?? collect())->isNotEmpty())
                <div class="history-list">
                    @foreach($feedbacks as $feedback)
                        <article class="history-item">
                            <div class="history-meta">
                                <span>{{ $feedback->created_at->format('d M Y, H:i') }}</span>
                                <span class="history-status">
                                    {{ $feedback->admin_note ? 'Sudah ditanggapi' : 'Menunggu admin' }}
                                </span>
                            </div>
                            <p class="history-message">{{ \Illuminate\Support\Str::limit($feedback->message, 140) }}</p>
                            @if($feedback->admin_note)
                                <div class="history-note">
                                    <strong>Catatan admin:</strong> {{ $feedback->admin_note }}
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <p class="empty-history">Belum ada masukan yang pernah dikirim dari akun ini.</p>
            @endif
        </section>
    </main>

    @include('content.apps.Customer.tagihan.bottom-navbar', ['active' => 'home'])
</body>
</html>
