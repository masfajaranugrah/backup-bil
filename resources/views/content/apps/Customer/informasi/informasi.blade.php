@php
    use Illuminate\Support\Str;

    $totalInformasi = $iklans->where('type', 'informasi')->count();
    $totalMaintenance = $iklans->where('type', 'maintenance')->count();
    $totalIklan = $iklans->where('type', 'iklan')->count();

    $resolveInfoImageUrl = function (?string $image) {
        $image = trim((string) $image);
        if ($image === '') {
            return '';
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            $host = parse_url($image, PHP_URL_HOST);
            if ($host && $host !== request()->getHost()) {
                return route('customer.media.proxy', ['url' => $image]);
            }

            return $image;
        }

        return asset('storage/' . ltrim($image, '/'));
    };
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    @include('content.apps.Customer.partials.disable-zoom')
    <title>Informasi Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
            padding: 0 0 100px 0;
            min-height: 100vh;
            color: #0f172a;
        }
        .container { max-width: 680px; padding: 0 16px; }
        .page-header {
            padding: 24px 0 18px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0f172a;
            font-size: 1.1rem;
            cursor: pointer;
            flex-shrink: 0;
        }
        .page-title { font-size: 1.375rem; font-weight: 800; color: #0f172a; }
        .page-sub { font-size: 0.8125rem; color: #64748b; margin-top: 2px; }
        .hero-info {
            background: linear-gradient(135deg, #111827 0%, #312e81 100%);
            border-radius: 20px;
            padding: 22px;
            color: #fff;
            margin-bottom: 18px;
            overflow: hidden;
            position: relative;
        }
        .hero-info::after {
            content: '';
            position: absolute;
            width: 160px;
            height: 160px;
            right: -60px;
            top: -50px;
            border-radius: 999px;
            background: rgba(255,255,255,0.1);
        }
        .hero-info h1 { font-size: 1.25rem; font-weight: 800; margin-bottom: 8px; position: relative; z-index: 1; }
        .hero-info p { font-size: 0.9rem; color: #cbd5e1; margin: 0; line-height: 1.6; position: relative; z-index: 1; }
        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 18px; }
        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 14px 10px;
            text-align: center;
        }
        .stat-number { font-size: 1.25rem; font-weight: 800; color: #0f172a; }
        .stat-label { font-size: 0.6875rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.03em; }
        .filter-row { display: flex; gap: 8px; overflow-x: auto; padding: 2px 0 14px; margin-bottom: 4px; }
        .filter-btn {
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 0.8125rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .filter-btn.active { background: #0f172a; border-color: #0f172a; color: #fff; }
        .info-list { display: flex; flex-direction: column; gap: 14px; }
        .info-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        }
        .info-card:hover { transform: translateY(-2px); box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08); border-color: #cbd5e1; }
        .info-card.maintenance { border-color: #fbbf24; }
        .info-image {
            width: 100%;
            height: 190px;
            object-fit: cover;
            background: #f1f5f9;
        }
        .info-content { padding: 18px; }
        .info-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 800;
            margin-bottom: 12px;
        }
        .info-badge.maintenance { background: #fef3c7; color: #b45309; }
        .info-badge.informasi { background: #dbeafe; color: #0369a1; }
        .info-badge.iklan { background: #ede9fe; color: #7c3aed; }
        .info-title { font-size: 1.0625rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; line-height: 1.4; }
        .info-message { font-size: 0.9rem; color: #475569; line-height: 1.65; white-space: pre-line; margin-bottom: 14px; }
        .info-time { display: flex; align-items: center; gap: 6px; font-size: 0.75rem; color: #94a3b8; }
        .empty-state {
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            padding: 34px 20px;
            text-align: center;
            color: #64748b;
        }
        .empty-state i { font-size: 2rem; color: #94a3b8; margin-bottom: 10px; }
        .info-detail-modal {
            position: fixed;
            inset: 0;
            z-index: 1200;
            background: #ffffff;
            display: none;
            flex-direction: column;
            min-height: 100vh;
        }
        .info-detail-modal.show { display: flex; }
        .info-detail-header {
            position: sticky;
            top: 0;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: calc(14px + env(safe-area-inset-top, 0px)) 16px 14px;
            background: rgba(255, 255, 255, 0.94);
            border-bottom: 1px solid #e2e8f0;
            backdrop-filter: blur(10px);
        }
        .info-detail-close {
            width: 40px;
            height: 40px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex: 0 0 auto;
        }
        .info-detail-heading { min-width: 0; }
        .info-detail-heading strong { display: block; font-size: 0.95rem; color: #0f172a; }
        .info-detail-heading span { display: block; margin-top: 2px; font-size: 0.76rem; color: #64748b; }
        .info-detail-body {
            overflow-y: auto;
            padding: 18px 16px calc(110px + env(safe-area-inset-bottom, 0px));
            max-width: 680px;
            width: 100%;
            margin: 0 auto;
        }
        .info-detail-image {
            width: 100%;
            max-height: 360px;
            object-fit: cover;
            border-radius: 18px;
            margin-bottom: 18px;
            background: #f1f5f9;
            display: none;
        }
        .info-detail-title {
            margin: 12px 0 12px;
            font-size: 1.45rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.25;
        }
        .info-detail-message {
            color: #334155;
            font-size: 0.98rem;
            line-height: 1.75;
            white-space: pre-line;
        }
        .info-detail-time {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 18px;
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 600;
        }
        @media (max-width: 420px) {
            .hero-info { padding: 20px; }
            .stats-row { gap: 8px; }
            .stat-card { padding: 12px 8px; }
            .info-image { height: 160px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <button class="back-btn" onclick="window.location.href='{{ route('customer.tagihan.home') }}'">
                <i class="bi bi-arrow-left"></i>
            </button>
            <div>
                <div class="page-title">Informasi</div>
                <div class="page-sub">Pemberitahuan, maintenance, dan promo terbaru</div>
            </div>
        </div>

        <div class="hero-info">
            <h1>Update Untuk Pelanggan</h1>
            <p>Semua informasi resmi dari Jernih Multi Komunikasi dikumpulkan di halaman ini agar lebih mudah dibaca.</p>
        </div>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number">{{ $totalInformasi }}</div>
                <div class="stat-label">Info</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalMaintenance }}</div>
                <div class="stat-label">Maintenance</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $totalIklan }}</div>
                <div class="stat-label">Promo</div>
            </div>
        </div>

        <div class="filter-row">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="informasi">Informasi</button>
            <button class="filter-btn" data-filter="maintenance">Maintenance</button>
            <button class="filter-btn" data-filter="iklan">Promo</button>
        </div>

        @if($iklans->count() > 0)
            <div class="info-list" id="info-list">
                @foreach($iklans as $iklan)
                    @php($imageUrl = $resolveInfoImageUrl($iklan->image))
                    <article class="info-card {{ $iklan->type }}"
                        data-type="{{ $iklan->type }}"
                        data-title="{{ e($iklan->title) }}"
                        data-message="{{ e($iklan->message) }}"
                        data-image="{{ $imageUrl }}"
                        data-time="{{ $iklan->created_at->diffForHumans() }}"
                        role="button"
                        tabindex="0"
                        onclick="openInfoDetail(this)"
                        onkeydown="handleInfoCardKeydown(event, this)">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $iklan->title }}" class="info-image" loading="lazy">
                        @endif
                        <div class="info-content">
                            <span class="info-badge {{ $iklan->type }}">
                                @if($iklan->type === 'maintenance')
                                    <i class="bi bi-tools"></i> Maintenance
                                @elseif($iklan->type === 'informasi')
                                    <i class="bi bi-info-circle"></i> Informasi
                                @else
                                    <i class="bi bi-megaphone"></i> Promo
                                @endif
                            </span>
                            <div class="info-title">{{ $iklan->title }}</div>
                            <div class="info-message">{{ $iklan->message }}</div>
                            <div class="info-time">
                                <i class="bi bi-clock"></i>
                                <span>{{ $iklan->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-bell-slash d-block"></i>
                <strong>Belum ada informasi</strong>
                <div class="mt-1">Informasi terbaru akan tampil di sini.</div>
            </div>
        @endif
    </div>

    <div id="info-detail-modal" class="info-detail-modal" aria-hidden="true">
        <div class="info-detail-header">
            <button type="button" class="info-detail-close" onclick="closeInfoDetail()" aria-label="Tutup detail informasi">
                <i class="bi bi-arrow-left"></i>
            </button>
            <div class="info-detail-heading">
                <strong>Detail Informasi</strong>
                <span id="detail-subtitle">Pemberitahuan pelanggan</span>
            </div>
        </div>
        <div class="info-detail-body">
            <img id="detail-image" class="info-detail-image" src="" alt="">
            <span id="detail-badge" class="info-badge informasi"></span>
            <h1 id="detail-title" class="info-detail-title"></h1>
            <div id="detail-message" class="info-detail-message"></div>
            <div class="info-detail-time">
                <i class="bi bi-clock"></i>
                <span id="detail-time"></span>
            </div>
        </div>
    </div>

    @include('content.apps.Customer.tagihan.bottom-navbar', ['active' => 'informasi'])

    <script>
        const typeLabels = {
            maintenance: { icon: 'bi-tools', label: 'Maintenance' },
            informasi: { icon: 'bi-info-circle', label: 'Informasi' },
            iklan: { icon: 'bi-megaphone', label: 'Promo' },
        };

        function openInfoDetail(card) {
            const modal = document.getElementById('info-detail-modal');
            const type = card.dataset.type || 'informasi';
            const typeInfo = typeLabels[type] || typeLabels.informasi;
            const image = card.dataset.image || '';
            const imageEl = document.getElementById('detail-image');
            const badgeEl = document.getElementById('detail-badge');

            document.getElementById('detail-title').textContent = card.dataset.title || '';
            document.getElementById('detail-message').textContent = card.dataset.message || '';
            document.getElementById('detail-time').textContent = card.dataset.time || '';
            document.getElementById('detail-subtitle').textContent = typeInfo.label;

            badgeEl.className = 'info-badge ' + type;
            badgeEl.innerHTML = '<i class="bi ' + typeInfo.icon + '"></i> ' + typeInfo.label;

            if (image) {
                imageEl.src = image;
                imageEl.alt = card.dataset.title || 'Gambar informasi';
                imageEl.style.display = 'block';
            } else {
                imageEl.removeAttribute('src');
                imageEl.style.display = 'none';
            }

            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeInfoDetail() {
            const modal = document.getElementById('info-detail-modal');
            modal.classList.remove('show');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        function handleInfoCardKeydown(event, card) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openInfoDetail(card);
            }
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeInfoDetail();
            }
        });

        document.querySelectorAll('.filter-btn').forEach((button) => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;

                document.querySelectorAll('.filter-btn').forEach((item) => item.classList.remove('active'));
                button.classList.add('active');

                document.querySelectorAll('.info-card').forEach((card) => {
                    card.style.display = filter === 'all' || card.dataset.type === filter ? '' : 'none';
                });
            });
        });
    </script>
</body>
</html>
