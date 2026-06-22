@extends('layouts/layoutMaster')

@section('title', 'Laporan Kendala & Masukan')

@section('page-style')
<style>
    :root {
        --feedback-ink: #111827;
        --feedback-muted: #64748b;
        --feedback-line: #e5e7eb;
        --feedback-soft: #f8fafc;
        --feedback-card: #ffffff;
        --feedback-radius: 18px;
        --feedback-shadow: 0 18px 46px rgba(15, 23, 42, 0.08);
    }

    .feedback-page {
        color: var(--feedback-ink);
    }

    .feedback-hero {
        background: linear-gradient(135deg, #0f172a 0%, #18181b 52%, #312e81 100%);
        border-radius: 24px;
        padding: 28px;
        color: #ffffff;
        box-shadow: var(--feedback-shadow);
        margin-bottom: 18px;
        position: relative;
        overflow: hidden;
    }

    .feedback-hero::after {
        content: '';
        position: absolute;
        width: 240px;
        height: 240px;
        right: -70px;
        top: -90px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.11);
    }

    .feedback-hero-title {
        font-size: clamp(1.5rem, 2.4vw, 2.25rem);
        font-weight: 900;
        letter-spacing: -0.04em;
        margin: 0 0 8px;
    }

    .feedback-hero-subtitle {
        max-width: 640px;
        margin: 0;
        color: rgba(255, 255, 255, 0.76);
        font-size: 0.98rem;
    }

    .feedback-search {
        display: flex;
        gap: 10px;
        width: min(100%, 520px);
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 999px;
        padding: 8px;
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 1;
    }

    .feedback-search input {
        min-width: 0;
        flex: 1;
        border: 0;
        outline: 0;
        background: #ffffff;
        color: var(--feedback-ink);
        border-radius: 999px;
        padding: 12px 16px;
        font-weight: 700;
    }

    .feedback-search button,
    .feedback-search a {
        min-width: 44px;
        height: 44px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        text-decoration: none;
    }

    .feedback-stat-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .feedback-stat-card {
        background: var(--feedback-card);
        border: 1px solid var(--feedback-line);
        border-radius: 20px;
        padding: 18px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .feedback-stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.35rem;
        background: #111827;
        flex-shrink: 0;
    }

    .feedback-stat-icon.warning {
        background: #f59e0b;
    }

    .feedback-stat-icon.info {
        background: #2563eb;
    }

    .feedback-stat-label {
        color: var(--feedback-muted);
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .feedback-stat-value {
        font-size: 1.55rem;
        font-weight: 900;
        line-height: 1;
        margin-top: 4px;
    }

    .feedback-card {
        border: 1px solid var(--feedback-line);
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--feedback-shadow);
        background: var(--feedback-card);
    }

    .feedback-table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--feedback-line);
        background: #ffffff;
    }

    .feedback-table-title {
        font-size: 1rem;
        font-weight: 900;
        margin: 0;
    }

    .feedback-table-subtitle {
        color: var(--feedback-muted);
        font-size: 0.84rem;
        margin: 2px 0 0;
    }

    .feedback-table {
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .feedback-table thead th {
        background: var(--feedback-soft);
        color: #334155;
        border: 0;
        border-bottom: 1px solid var(--feedback-line);
        padding: 15px 18px;
        font-size: 0.74rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        white-space: nowrap;
    }

    .feedback-table tbody td {
        padding: 18px;
        vertical-align: top;
        border-bottom: 1px solid #eef2f7;
        color: var(--feedback-ink);
    }

    .feedback-table tbody tr {
        transition: background 0.15s ease;
    }

    .feedback-table tbody tr:hover {
        background: #f8fafc;
    }

    .feedback-date-chip {
        display: inline-flex;
        flex-direction: column;
        min-width: 92px;
        padding: 10px 12px;
        border-radius: 14px;
        background: #f1f5f9;
        color: #0f172a;
        font-weight: 900;
        line-height: 1.2;
    }

    .feedback-date-chip small {
        color: var(--feedback-muted);
        margin-top: 4px;
        font-weight: 800;
    }

    .feedback-customer-name {
        font-weight: 900;
        margin-bottom: 6px;
    }

    .feedback-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--feedback-muted);
        font-size: 0.82rem;
        margin-top: 2px;
    }

    .feedback-message-box {
        min-width: 280px;
        max-width: 440px;
        padding: 12px 14px;
        border-radius: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #1f2937;
        line-height: 1.55;
        white-space: pre-wrap;
        overflow-wrap: anywhere;
    }

    .feedback-status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 900;
        white-space: nowrap;
    }

    .feedback-status.done {
        background: #dcfce7;
        color: #166534;
    }

    .feedback-status.pending {
        background: #fef3c7;
        color: #92400e;
    }

    .feedback-note-form {
        min-width: 320px;
    }

    .feedback-note-input {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        background: #ffffff;
        color: var(--feedback-ink);
        padding: 12px 14px;
        resize: vertical;
        min-height: 88px;
        outline: 0;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .feedback-note-input:focus {
        border-color: #111827;
        box-shadow: 0 0 0 4px rgba(17, 24, 39, 0.08);
    }

    .feedback-action-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
    }

    .feedback-btn {
        border-radius: 999px;
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        white-space: nowrap;
    }

    .feedback-delete-btn {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        border: 1px solid #fecaca;
        color: #dc2626;
        background: #fff5f5;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .feedback-delete-btn:hover {
        color: #ffffff;
        background: #dc2626;
        border-color: #dc2626;
    }

    .feedback-empty {
        padding: 64px 20px;
        text-align: center;
        color: var(--feedback-muted);
    }

    .feedback-empty-icon {
        width: 70px;
        height: 70px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 24px;
        background: #f1f5f9;
        color: #111827;
        font-size: 2rem;
        margin-bottom: 14px;
    }

    .feedback-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        padding: 18px 22px;
        background: #fbfdff;
        border-top: 1px solid var(--feedback-line);
    }

    .feedback-pagination-info {
        color: var(--feedback-muted);
        font-weight: 700;
        font-size: 0.9rem;
    }

    .feedback-pagination .mui-pagination {
        align-items: center;
        gap: 0.55rem;
        display: flex;
        flex-wrap: wrap;
    }

    .feedback-pagination .mui-pagination .page-link {
        width: 34px;
        min-width: 34px;
        height: 34px;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        border-radius: 50% !important;
        background: transparent !important;
        color: #1f2937 !important;
        box-shadow: none !important;
        font-size: 0.84rem;
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .feedback-pagination .mui-pagination .page-item.active .page-link {
        background: #111827 !important;
        color: #ffffff !important;
    }

    .feedback-pagination .mui-pagination .page-link:hover {
        background: rgba(17, 24, 39, 0.08) !important;
        color: #111827 !important;
    }

    .feedback-pagination .mui-pagination .page-item.disabled .page-link {
        color: #cbd5e1 !important;
    }

    @media (max-width: 991.98px) {
        .feedback-hero {
            padding: 22px;
        }

        .feedback-stat-grid {
            grid-template-columns: 1fr;
        }

        .feedback-search {
            width: 100%;
        }
    }

    @media (max-width: 575.98px) {
        .feedback-hero .d-flex {
            align-items: stretch !important;
        }

        .feedback-search {
            border-radius: 18px;
            flex-wrap: wrap;
        }

        .feedback-search input {
            flex-basis: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y feedback-page">
    <div class="feedback-hero">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 position-relative" style="z-index: 1;">
            <div>
                <h1 class="feedback-hero-title">Laporan Kendala & Masukan</h1>
                <p class="feedback-hero-subtitle">Pantau laporan pelanggan, simpan catatan tindak lanjut, dan rapikan prioritas perbaikan aplikasi dari satu tempat.</p>
            </div>

            <form method="GET" action="{{ route('feedback.index') }}" class="feedback-search">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, ID, telp, isi feedback">
                @if(request('search'))
                    <a href="{{ route('feedback.index') }}" class="btn btn-light" title="Reset pencarian">
                        <i class="ri-close-line"></i>
                    </a>
                @endif
                <button type="submit" class="btn btn-light" title="Cari laporan">
                    <i class="ri-search-line"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="feedback-stat-grid">
        <div class="feedback-stat-card">
            <div class="feedback-stat-icon"><i class="ri-chat-check-line"></i></div>
            <div>
                <div class="feedback-stat-label">Total Laporan</div>
                <div class="feedback-stat-value">{{ number_format($statistics['total'] ?? 0) }}</div>
            </div>
        </div>
        <div class="feedback-stat-card">
            <div class="feedback-stat-icon warning"><i class="ri-time-line"></i></div>
            <div>
                <div class="feedback-stat-label">Belum Ditindaklanjuti</div>
                <div class="feedback-stat-value">{{ number_format($statistics['pending'] ?? 0) }}</div>
            </div>
        </div>
        <div class="feedback-stat-card">
            <div class="feedback-stat-icon info"><i class="ri-attachment-2"></i></div>
            <div>
                <div class="feedback-stat-label">Dengan Lampiran</div>
                <div class="feedback-stat-value">{{ number_format($statistics['with_attachment'] ?? 0) }}</div>
            </div>
        </div>
    </div>

    <div class="feedback-card">
        <div class="feedback-table-toolbar">
            <div>
                <h4 class="feedback-table-title">Daftar Laporan</h4>
                <p class="feedback-table-subtitle">{{ request('search') ? 'Hasil pencarian untuk: "' . request('search') . '"' : 'Semua laporan terbaru dari pelanggan' }}</p>
            </div>
            <span class="badge bg-label-dark">{{ $feedbacks->total() }} data</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success mx-4 mt-4 mb-0 rounded-3">
                <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table feedback-table align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Laporan</th>
                        <th>Status</th>
                        <th>Lampiran</th>
                        <th>Catatan Admin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($feedbacks as $feedback)
                        <tr>
                            <td>
                                <div class="feedback-date-chip">
                                    {{ $feedback->created_at->format('d M Y') }}
                                    <small>{{ $feedback->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="feedback-customer-name">{{ $feedback->pelanggan->nama_lengkap ?? '-' }}</div>
                                <div class="feedback-meta"><i class="ri-id-card-line"></i>ID: {{ $feedback->pelanggan->nomer_id ?? '-' }}</div>
                                <div class="feedback-meta"><i class="ri-phone-line"></i>{{ $feedback->pelanggan->no_telp ?? $feedback->pelanggan->no_whatsapp ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="feedback-message-box">{{ $feedback->message }}</div>
                            </td>
                            <td>
                                @if(trim((string) $feedback->admin_note) !== '')
                                    <span class="feedback-status done"><i class="ri-checkbox-circle-line"></i>Selesai dicatat</span>
                                @else
                                    <span class="feedback-status pending"><i class="ri-time-line"></i>Perlu tindak lanjut</span>
                                @endif
                            </td>
                            <td>
                                @if($feedback->attachment_url)
                                    <a href="{{ $feedback->attachment_url }}" target="_blank" class="btn btn-sm btn-outline-primary feedback-btn">
                                        <i class="ri-attachment-2"></i>
                                        Buka
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('feedback.update', $feedback) }}" class="feedback-note-form">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="admin_note" rows="3" class="feedback-note-input" placeholder="Tulis catatan tindak lanjut...">{{ old('admin_note', $feedback->admin_note) }}</textarea>
                                    <div class="feedback-action-row">
                                        <small class="text-muted">
                                            @if($feedback->admin)
                                                Diupdate oleh {{ $feedback->admin->name ?? '-' }}
                                            @else
                                                Belum ada admin
                                            @endif
                                        </small>
                                        <button type="submit" class="btn btn-sm btn-dark feedback-btn">
                                            <i class="ri-save-line"></i>Simpan
                                        </button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('feedback.destroy', $feedback) }}" onsubmit="return confirm('Hapus feedback ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="feedback-delete-btn" title="Hapus laporan">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="feedback-empty">
                                    <div class="feedback-empty-icon"><i class="ri-inbox-line"></i></div>
                                    <h5 class="fw-bold mb-1">Belum ada laporan</h5>
                                    <div>{{ request('search') ? 'Tidak ada laporan yang cocok dengan pencarian.' : 'Laporan kendala dan masukan pelanggan akan tampil di sini.' }}</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="feedback-pagination">
            <div class="feedback-pagination-info">
                Menampilkan {{ $feedbacks->firstItem() ?? 0 }}-{{ $feedbacks->lastItem() ?? 0 }} dari {{ $feedbacks->total() }} laporan
            </div>
            <div>
                {{ $feedbacks->onEachSide(1)->links('pagination.mui') }}
            </div>
        </div>
    </div>
</div>
@endsection
