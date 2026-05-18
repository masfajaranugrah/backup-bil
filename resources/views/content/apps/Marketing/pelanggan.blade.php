
@extends('layouts/layoutMaster')

@section('title', 'Data Pelanggan')

@php
use Illuminate\Support\Str;
@endphp

{{-- VENDOR STYLE --}}
@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
<style>
/* ========================================= */
/* MODERN CLEAN STYLES (Match tagihan.blade.php) */
/* ========================================= */
:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --card-hover-shadow: 0 4px 16px rgba(0,0,0,0.12);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --primary-color: #111827;
  --success-color: #28c76f;
}

.tagihan-page-shell {
  padding: 0;
  max-width: 100%;
}

.tagihan-page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 1.5rem;
}

.tagihan-title-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 0.5rem;
}

.tagihan-title-icon {
  background: #111827;
  color: white;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  font-size: 1.25rem;
}

.tagihan-count-badge {
  background: #f3f4f6;
  color: #111827;
  padding: 0.25rem 0.75rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.35rem;
}

.tagihan-header-actions {
  display: flex;
  gap: 0.75rem;
}

/* Stats Card */
.stats-card {
  border-radius: var(--border-radius);
  padding: 1.5rem;
  background: #ffffff;
  color: #0f172a;
  border: 1px solid #e5e7eb;
  transition: var(--transition);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stats-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
}

.stats-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  background: #f3f4f6;
  color: #111827;
}

/* Card Design */
.card-main {
  border: 1px solid #e5e7eb;
  border-radius: var(--border-radius);
  box-shadow: 0 1px 3px rgba(0,0,0,0.05);
  background: #fff;
  transition: var(--transition);
  overflow: hidden;
}

.card-main:hover {
  box-shadow: var(--card-hover-shadow);
}

/* Search Bar */
.search-container {
  display: flex;
  align-items: center;
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  padding: 0.4rem 0.8rem;
  width: 100%;
  max-width: 350px;
  transition: var(--transition);
}
.search-container:focus-within {
  border-color: #111827;
  background: #fff;
}
.search-container input {
  border: none;
  background: transparent;
  outline: none;
  width: 100%;
  padding-left: 0.5rem;
  font-size: 0.9rem;
}

/* Table */
.modern-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}
.modern-table thead th {
  background: #f9fafb;
  color: #6b7280;
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb;
  white-space: nowrap;
}
.modern-table tbody td {
  padding: 0.85rem 1rem;
  border-bottom: 1px solid #e5e7eb;
  vertical-align: middle;
}
.modern-table tbody tr {
  transition: background-color 0.15s;
}
.modern-table tbody tr:hover {
  background-color: #f9fafb;
}

/* Cell info */
.product-cell { display: flex; align-items: center; gap: 0.75rem; }
.product-info h6 { margin: 0 0 0.25rem 0; font-weight: 600; font-size: 0.9rem; color: #111827; }
.product-info span { font-size: 0.8rem; color: #6b7280; }

.badge-status {
  display: inline-flex;
  align-items: center;
  padding: 0.35rem 0.75rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  line-height: 1.2;
  white-space: nowrap;
}
.badge-approve { background: #dcfce7; color: #15803d; }
.badge-pending { background: #fef3c7; color: #92400e; }
.badge-reject { background: #fee2e2; color: #b91c1c; }
.badge-default { background: #f1f5f9; color: #475569; }

/* Action Buttons */
.btn-action {
  width: 32px;
  height: 32px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  border: 1px solid #e5e7eb;
  background: #fff;
  color: #4b5563;
  transition: all 0.2s;
}
.btn-action:hover {
  background: #f3f4f6;
  color: #111827;
  border-color: #d1d5db;
}

.table-actions {
  display: inline-flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.4rem;
  flex-wrap: nowrap;
  white-space: nowrap;
}

.btn-kebab {
  width: 34px;
  height: 34px;
  border-radius: 10px;
}

.action-menu {
  min-width: 260px;
  padding: 0.5rem;
  border-radius: 16px;
  border: 1px solid #e5e7eb;
  background:
    radial-gradient(circle at 88% 12%, rgba(191, 232, 255, 0.38) 0%, rgba(191, 232, 255, 0) 42%),
    radial-gradient(circle at 12% 92%, rgba(255, 199, 199, 0.24) 0%, rgba(255, 199, 199, 0) 48%),
    linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
  box-shadow: 0 12px 28px rgba(15, 23, 42, 0.12);
}

.action-menu .dropdown-item {
  border-radius: 12px;
  padding: 0.62rem 0.75rem;
  display: flex;
  align-items: center;
  gap: 0.7rem;
  font-size: 1rem;
  color: #1f2937;
}

.action-menu .dropdown-item i {
  font-size: 1.25rem;
}

.action-menu .dropdown-item:hover {
  background: rgba(15, 23, 42, 0.05);
}

.action-menu .action-danger-wrap {
  margin-top: 0.45rem;
  border-radius: 12px;
  background: linear-gradient(145deg, rgba(255, 214, 214, 0.55) 0%, rgba(255, 230, 230, 0.32) 100%);
  padding: 0.2rem;
}

.action-menu .action-danger {
  color: #ef4444;
  font-weight: 500;
}

.action-menu .action-danger:hover {
  background: rgba(239, 68, 68, 0.14);
  color: #dc2626;
}

.action-menu .action-section-title {
  font-size: 0.7rem;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  color: #9ca3af;
  font-weight: 700;
  margin: 0.5rem 0.45rem 0.3rem;
}

.progress-label {
  display: inline-block;
  margin-top: 0.25rem;
  font-size: 0.75rem;
  color: #6b7280;
  font-weight: 500;
  white-space: nowrap;
}

.alamat-main {
  font-size: 0.85rem;
  color: #1f2937;
  max-width: 220px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.35;
}

.alamat-sub {
  font-size: 0.75rem;
  color: #6b7280;
  line-height: 1.3;
}

/* Stepper */
.stepper-mini {
  display: flex;
  align-items: center;
  gap: 0.2rem;
  min-height: 20px;
}
.step-dot {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 2px solid #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.6rem;
  font-weight: 700;
  color: #9ca3af;
}
.step-dot.done {
  background: #10b981;
  border-color: #10b981;
  color: #fff;
}
.step-dot.current {
  background: #111827;
  border-color: #111827;
  color: #fff;
}
.step-dot.done::before {
  content: "\2713";
  font-size: 0.65rem;
  line-height: 1;
}
.step-line {
  width: 12px;
  height: 2px;
  background: #e5e7eb;
}
.step-line.done { background: #10b981; }

/* Empty State */
.empty-state {
  padding: 3rem 1rem;
  text-align: center;
}

.empty-state i {
  font-size: 2.5rem;
  color: #d1d5db;
  margin-bottom: 0.75rem;
  display: block;
}

.empty-state p {
  color: #6b7280;
  font-size: 0.875rem;
  margin: 0;
}

/* No results from client-side search */
.no-results-row {
  display: none;
}

.no-results-row td {
  text-align: center;
  padding: 2rem 1rem !important;
  color: #6b7280;
}

/* Pagination */
.pagination-wrapper {
  padding: 1rem 1.5rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 1rem;
  background: #fff;
}

.pagination-info {
  font-size: 0.8rem;
  color: #6b7280;
}

.pagination-info strong {
  color: #111827;
  font-weight: 600;
}

.pagination {
  margin-bottom: 0;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.375rem;
  flex-wrap: wrap;
  list-style: none;
  padding: 0;
}

.pagination .page-item {
  margin: 0 !important;
}

.pagination .page-item .page-link {
  width: 36px;
  height: 36px;
  min-width: 36px;
  min-height: 36px;
  border-radius: 8px !important;
  border: 1px solid #e5e7eb;
  color: #4b5563;
  background: #fff;
  font-size: 0.9rem;
  font-weight: 500;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  line-height: 1;
  text-decoration: none;
  transition: all 0.2s;
}

.pagination .page-item.active .page-link {
  background-color: #111827;
  border-color: #111827;
  color: #fff;
  box-shadow: 0 4px 6px -1px rgba(17, 24, 39, 0.2);
}

.pagination .page-item.disabled .page-link {
  color: #9ca3af;
  background: #f9fafb;
  border-color: #f3f4f6;
  opacity: 1;
  pointer-events: none;
}

.pagination .page-item .page-link:hover {
  background: #f3f4f6;
  color: #111827;
  border-color: #d1d5db;
}

.pagination .page-item.active .page-link:hover {
  background-color: #1f2937;
  color: #fff;
}

.loading-overlay {
  position: fixed;
  inset: 0;
  background: rgba(255,255,255,0.7);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

@media (min-width: 768px) {
  .modern-table thead th:nth-child(4),
  .modern-table tbody td:nth-child(4) {
    min-width: 210px;
  }

  .modern-table thead th:last-child,
  .modern-table tbody td:last-child {
    width: 176px;
  }
}
</style>
@endsection

{{-- VENDOR SCRIPT --}}
@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

{{-- PAGE SCRIPT --}}
@section('page-script')
<style>
/* Force hide SweetAlert deny button agar modal hanya 2 tombol */
.swal2-deny,
.swal2-styled.swal2-deny {
    display: none !important;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Search: submit on Enter key
    const searchInput = document.getElementById('search-pelanggan');
    if (searchInput) {
        // Auto-submit after 600ms debounce
        let searchTimer;
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                this.closest('form').submit();
                return;
            }
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                this.closest('form').submit();
            }, 600);
        });
    }


    // Modal Update Progres
    $(document).on('click', '.btn-update-progres', function() {
        const id = $(this).data('id');
        const progres = $(this).data('progres');
        const note = $(this).data('note');
        const isPending = $(this).data('pending') == 1;
        
        $('#formUpdateProgres').attr('action', `/dashboard/marketing/pelanggan/${id}/progres`);
        $('#progresSelect').val(progres);
        $('#isPendingCheck').prop('checked', isPending);
        $('#progressNote').val(note);
        
        $('#modalUpdateProgres').modal('show');
    });

    // Detail Modal
    $(document).on('click', '.btn-detail', function() {
        const row = $(this).closest('tr[data-nomer-id], div[data-nomer-id]');
        const modal = $('#detailModal');

        modal.find('#detail-nama').text(row.data('nama') || '-');
        modal.find('#detail-id').text(row.data('nomer-id') || '-');
        modal.find('#detail-whatsapp').text(row.data('whatsapp') || '-');
        modal.find('#detail-whatsapp-link').attr('href', 'https://wa.me/' + (row.data('whatsapp') || ''));
        modal.find('#detail-alamat').text(row.data('alamat') || '-');
        modal.find('#detail-rt-rw').text((row.data('rt') || '-') + ' / ' + (row.data('rw') || '-'));
        modal.find('#detail-kecamatan').text(row.data('kecamatan') || '-');
        modal.find('#detail-kabupaten').text(row.data('kabupaten') || '-');
        modal.find('#detail-tanggal-mulai').text(row.data('tanggal-mulai') || '-');
        modal.find('#detail-status').text(row.data('status') || '-');
        modal.find('#detail-progres').text(row.data('progres') || 'Belum Diproses');
        modal.find('#detail-marketing').text((row.data('marketing-name') || '-'));
        modal.find('#detail-note').text(row.data('progress-note') || '-');
        modal.find('#detail-deskripsi').text(row.data('deskripsi') || '-');

        const fotoKtp = row.data('foto-ktp');
        if (fotoKtp) {
            modal.find('#detail-foto-ktp').attr('src', fotoKtp).removeClass('d-none');
            modal.find('#detail-no-foto').addClass('d-none');
        } else {
            modal.find('#detail-foto-ktp').addClass('d-none');
            modal.find('#detail-no-foto').removeClass('d-none');
        }

        modal.modal('show');
    });

    // Delete confirmation
    $(document).on('click', '.btn-delete', function() {
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Hapus Pelanggan?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

    // Quick progres change confirmation
    $(document).on('click', '.quick-progres-form button[type="submit"]', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const stage = form.find('input[name="progres"]').val();

        Swal.fire({
            title: `Ubah progres ke "${stage}"?`,
            icon: 'question',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Ubah!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });

});
</script>
@endsection

@section('content')
<div class="loading-overlay">
    <div class="spinner-border text-dark" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="tagihan-page-shell">
  <div class="tagihan-page-header">
    <div>
      <div class="tagihan-title-row">
        <span class="tagihan-title-icon"><i class="ri-group-line"></i></span>
        <h4 class="fw-bold text-dark m-0">Data Pelanggan</h4>
        <span class="tagihan-count-badge">
          <i class="ri-database-2-line"></i>
          {{ $pelanggan->total() }} Pelanggan
        </span>
      </div>
      <p class="text-muted m-0" style="font-size: 0.92rem;">Kelola semua data pelanggan Anda secara efisien.</p>
    </div>
    <div class="tagihan-header-actions">
      <a href="{{ route('marketing.add-pelanggan') }}" class="btn btn-primary" style="background:#111827; border-color:#111827;">
        <i class="ri-add-line me-1"></i> Tambah Pelanggan
      </a>
    </div>
  </div>

<style>
/* Tab style - sama dengan ticket.blade.php */
.pelanggan-tabs {
    display: flex;
    gap: 1.5rem;
    overflow-x: auto;
    scrollbar-width: none;
}
.pelanggan-tabs::-webkit-scrollbar { display: none; }
.pelanggan-tab {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-bottom: 0.8rem;
    color: #71717a;
    font-weight: 500;
    font-size: 0.875rem;
    text-decoration: none;
    border-bottom: 2px solid transparent;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.pelanggan-tab:hover { color: #18181b; }
.pelanggan-tab.active {
    color: #18181b;
    border-bottom-color: #18181b;
    font-weight: 600;
}
.badge-count {
    padding: 0.15rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}
.bg-dark-count { background: #18181b; color: #fff; }
.bg-success-light { background: #dcfce7; color: #166534; }
.bg-warning-light { background: #fef9c3; color: #854d0e; }
.bg-danger-light  { background: #fee2e2; color: #991b1b; }
.bg-gray-light    { background: #f4f4f5; color: #3f3f46; }
.bg-info-light    { background: #e0f2fe; color: #075985; }

/* Toast notification */
.pelanggan-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 380px;
    padding: 14px 18px;
    border-radius: 10px;
    background: #18181b;
    color: #fff;
    box-shadow: 0 14px 34px rgba(24,24,27,0.24);
    font-size: 0.9rem;
    font-weight: 500;
    opacity: 0;
    transform: translateY(12px);
    transition: all 0.25s ease;
    pointer-events: none;
}
.pelanggan-toast.show { opacity: 1; transform: translateY(0); }
.pelanggan-toast.is-error { background: #dc2626; }
</style>

{{-- Toast dari session --}}
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toast = document.createElement('div');
    toast.className = 'pelanggan-toast';
    toast.innerHTML = '<span class="material-symbols-rounded" style="color:#4ade80; font-size:1.2rem; line-height:1; flex-shrink:0;">check_circle</span><span>{{ addslashes(session("success")) }}</span>';
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 50);
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 3500);
});
</script>
@endif
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toast = document.createElement('div');
    toast.className = 'pelanggan-toast is-error';
    toast.innerHTML = '<i class="ri-error-warning-fill" style="color:#fca5a5; font-size:1.2rem; flex-shrink:0;"></i><span>{{ addslashes(session("error")) }}</span>';
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 50);
    setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 300); }, 4000);
});
</script>
@endif

<div style="border-bottom: 1px solid #e4e4e7; margin-bottom: 1.25rem;">
    <div class="pelanggan-tabs">
        {{-- All --}}
        <a href="{{ route('marketing.pelanggan', array_filter(['search' => request('search')])) }}"
           class="pelanggan-tab {{ is_null($progresFilter) || $progresFilter === '' ? 'active' : '' }}">
            All <span class="badge-count bg-dark-count">{{ $totalAll }}</span>
        </a>
        {{-- Per progres --}}
        @php
            $tabColors = [
                'Belum Diproses' => 'bg-gray-light',
                'Tarik Kabel'    => 'bg-warning-light',
                'Aktivasi'       => 'bg-success-light',
                'Registrasi'     => 'bg-info-light',
            ];
        @endphp
        @foreach(\App\Models\Pelanggan::PROGRES_STAGES as $stage)
        <a href="{{ route('marketing.pelanggan', array_filter(['progres' => $stage, 'search' => request('search')])) }}"
           class="pelanggan-tab {{ $progresFilter === $stage ? 'active' : '' }}">
            {{ $stage }}
            <span class="badge-count {{ $tabColors[$stage] ?? 'bg-gray-light' }}">
                {{ $progresStats[$stage] ?? 0 }}
            </span>
        </a>
        @endforeach
    </div>
</div>

    <div class="card-main">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3" style="padding: 1rem 1.5rem; border-bottom: 1px solid #e5e7eb; background: #fff;">
            <form method="GET" action="{{ route('marketing.pelanggan') }}" class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 420px;">
                @if($progresFilter)
                    <input type="hidden" name="progres" value="{{ $progresFilter }}">
                @endif
                <div class="search-container" style="flex: 1;">
                    <i class="ri-search-line" style="color: #6b7280;"></i>
                    <input type="text" id="search-pelanggan" name="search" placeholder="Cari pelanggan, ID, no WA, alamat..." value="{{ request('search') }}">
                </div>
            </form>
            <a href="{{ route('marketing.pelanggan', array_filter(['progres' => $progresFilter])) }}" class="btn-action" title="Refresh">
                <i class="ri-refresh-line"></i>
            </a>
        </div>
        
        <!-- Table Content -->
        <div class="card-body p-0">
            <!-- Desktop Table -->
            <div class="table-responsive d-none d-md-block">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">#</th>
                            <th>Pelanggan</th>
                            <th>Kontak</th>
                            <th>Progres</th>
                            <th>Alamat</th>
                            <th>Status</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="pelangganTableBody">
                        @forelse($pelanggan as $key => $p)
                        <tr data-searchable="{{ strtolower($p->nama_lengkap . ' ' . $p->nomer_id . ' ' . $p->no_whatsapp . ' ' . $p->alamat_jalan . ' ' . $p->kecamatan . ' ' . ($p->status ?? '') . ' ' . ($p->progres ?? '')) }}"
                            data-nomer-id="{{ $p->nomer_id }}"
                            data-nama="{{ $p->nama_lengkap }}"
                            data-whatsapp="{{ $p->no_whatsapp }}"
                            data-alamat="{{ $p->alamat_jalan }}"
                            data-rt="{{ $p->rt }}"
                            data-rw="{{ $p->rw }}"
                            data-kecamatan="{{ $p->kecamatan }}"
                            data-kabupaten="{{ $p->kabupaten }}"
                            data-tanggal-mulai="{{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') : '' }}"
                            data-foto-ktp="{{ $p->foto_ktp ? asset('storage/' . $p->foto_ktp) : '' }}"
                            data-status="{{ $p->status }}"
                            data-progres="{{ $p->progres }}"
                            data-marketing-name="{{ optional($p->user)->name }}"
                            data-marketing-email="{{ optional($p->user)->email }}"
                            data-created-at="{{ $p->created_at }}"
                            data-progress-note="{{ $p->progress_note }}"
                            data-is-pending="{{ \Illuminate\Support\Str::startsWith(strtoupper(trim((string)($p->progress_note ?? ''))), '[PENDING]') ? 1 : 0 }}"
                            data-deskripsi="{{ $p->deskripsi }}">
                            
                            <td style="text-align: center; color: #6b7280; font-size: 0.8rem;">{{ $pelanggan->firstItem() + $key }}</td>
                            <td>
                                <div class="product-cell">
                                    <div class="product-info">
                                        <h6 class="mb-0">{{ $p->nama_lengkap }}</h6>
                                        <span style="font-family: monospace;">{{ $p->nomer_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($p->no_whatsapp)
                                <a href="https://wa.me/{{ $p->no_whatsapp }}" target="_blank" class="text-decoration-none" style="color: #4b5563; font-size: 0.85rem; display: flex; align-items: center; gap: 4px;">
                                    <i class="ri-whatsapp-line" style="color: #16a34a;"></i> {{ $p->no_whatsapp }}
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $stages = ['Belum Diproses', 'Tarik Kabel', 'Aktivasi', 'Registrasi'];
                                    $isApproved = strtolower($p->status ?? '') === 'approve';
                                    $currentProgress = blank($p->progres) ? \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES : $p->progres;
                                    $currentStage = array_search($currentProgress, $stages);
                                    $isPendingStage = \Illuminate\Support\Str::startsWith(strtoupper(trim((string)($p->progress_note ?? ''))), '[PENDING]');
                                @endphp
                                <div class="stepper-mini mb-1">
                                    @foreach($stages as $index => $stage)
                                        @php
                                            $dotClass = '';
                                            if ($isApproved) {
                                                $dotClass = 'done';
                                            } elseif ($currentStage !== false) {
                                                if ($index < $currentStage) {
                                                    $dotClass = 'done';
                                                } elseif ($index === $currentStage) {
                                                    $dotClass = $isPendingStage && $currentProgress !== \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES ? 'pending-current' : 'current';
                                                }
                                            }
                                        @endphp
                                        <div class="step-dot {{ $dotClass }}" title="{{ $stage }}"></div>
                                        @if(!$loop->last)
                                            <div class="step-line {{ ($isApproved || ($currentStage !== false && $index < $currentStage)) ? 'done' : '' }}"></div>
                                        @endif
                                    @endforeach
                                </div>
                                <span class="progress-label">
                                    {{ $currentProgress }}
                                    @if($isPendingStage && $currentProgress !== \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES)
                                        <span class="text-danger ms-1">(Pending)</span>
                                    @endif
                                </span>
                            </td>
                            <td>
                                <div class="alamat-main" title="{{ $p->alamat_jalan }}">{{ $p->alamat_jalan ?? '-' }}</div>
                                @if($p->kecamatan)
                                <div class="alamat-sub">{{ $p->kecamatan }}</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusKey = strtolower($p->status ?? 'pending');
                                    $badgeClass = match($statusKey) {
                                        'approve' => 'badge-approve',
                                        'pending', 'proses' => 'badge-pending',
                                        'reject' => 'badge-reject',
                                        default => 'badge-default',
                                    };
                                    $statusLabel = $statusKey === 'approve' ? 'Approve' : ($statusKey === 'reject' ? 'Reject' : (($isPendingStage && $currentProgress !== \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES) ? 'Pending' : ($currentProgress !== \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES ? 'Progres' : 'Belum Diproses')));
                                @endphp
                                <span class="badge-status {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td style="text-align: right;">
                                <div class="table-actions">
                                    <div class="dropdown">
                                        <button type="button" class="btn-action btn-kebab" title="Aksi" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end action-menu">
                                            <button type="button" class="dropdown-item btn-detail">
                                                <i class="ri-eye-line"></i> Detail
                                            </button>
                                            @if(!$isApproved)
                                            <div class="action-section-title">Update Progres</div>
                                            @foreach(\App\Models\Pelanggan::PROGRES_STAGES as $stage)
                                            <form action="{{ route('marketing.pelanggan.progres', $p->id) }}" method="POST" class="quick-progres-form">
                                                @csrf
                                                <input type="hidden" name="progres" value="{{ $stage }}">
                                                <input type="hidden" name="progress_note" value="{{ $p->progress_note ?? '' }}">
                                                <input type="hidden" name="return_url" value="{{ url()->current() . '?' . http_build_query(request()->query()) }}">
                                                <button type="submit" class="dropdown-item {{ $currentProgress === $stage ? 'fw-bold' : '' }}">
                                                    <i class="{{ $currentProgress === $stage ? 'ri-checkbox-circle-fill text-success' : 'ri-refresh-line text-muted' }}"></i>
                                                    {{ $stage }}
                                                </button>
                                            </form>
                                            @endforeach
                                            @endif
                                            <a href="{{ route('marketing.pelanggan.edit', $p->id) }}" class="dropdown-item">
                                                <i class="ri-edit-2-line"></i> Edit
                                            </a>
                                            <div class="action-danger-wrap">
                                                <form action="{{ route('marketing.pelanggan.delete', $p->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item action-danger btn-delete">
                                                        <i class="ri-delete-bin-line"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="ri-inbox-line"></i>
                                    <p>Tidak ada data pelanggan ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                        <tr class="no-results-row" id="noResultsRow">
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="ri-search-line"></i>
                                    <p>Tidak ada hasil yang cocok dengan pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <!-- Mobile View (Cards) -->
        <div class="d-md-none mobile-cards">
            @forelse($pelanggan as $p)
            <div class="m-card {{ (strtolower($p->status ?? '') !== 'approve' && (blank($p->progres) || $p->progres === \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES)) ? 'is-urgent' : '' }}"
                data-searchable="{{ strtolower($p->nama_lengkap . ' ' . $p->nomer_id . ' ' . $p->no_whatsapp . ' ' . $p->alamat_jalan . ' ' . ($p->progres ?? '')) }}"
                data-nomer-id="{{ $p->nomer_id }}"
                data-nama="{{ $p->nama_lengkap }}"
                data-whatsapp="{{ $p->no_whatsapp }}"
                data-alamat="{{ $p->alamat_jalan }}"
                data-rt="{{ $p->rt }}"
                data-rw="{{ $p->rw }}"
                data-kecamatan="{{ $p->kecamatan }}"
                data-kabupaten="{{ $p->kabupaten }}"
                data-tanggal-mulai="{{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') : '' }}"
                data-foto-ktp="{{ $p->foto_ktp ? asset('storage/' . $p->foto_ktp) : '' }}"
                data-status="{{ $p->status }}"
                data-progres="{{ $p->progres }}"
                data-marketing-name="{{ optional($p->user)->name }}"
                data-marketing-email="{{ optional($p->user)->email }}"
                data-created-at="{{ $p->created_at }}"
                data-progress-note="{{ $p->progress_note }}"
                data-is-pending="{{ \Illuminate\Support\Str::startsWith(strtoupper(trim((string)($p->progress_note ?? ''))), '[PENDING]') ? 1 : 0 }}"
                data-deskripsi="{{ $p->deskripsi }}">

                <div class="m-card-top">
                    <span class="badge-id">{{ $p->nomer_id }}</span>
                </div>

                <div class="m-card-name">{{ $p->nama_lengkap }}</div>
                @php
                    $stages = ['Belum Diproses', 'Tarik Kabel', 'Aktivasi', 'Registrasi'];
                    $isApproved = strtolower($p->status ?? '') === 'approve';
                    $isPendingStage = \Illuminate\Support\Str::startsWith(
                        strtoupper(trim((string)($p->progress_note ?? ''))),
                        '[PENDING]'
                    );
                    $currentStageLabel = blank($p->progres) ? \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES : $p->progres;
                    $currentStage = array_search($currentStageLabel, $stages);
                    $isOwner = true; // Allow all marketing users to update
                    $isUrgent = !$isApproved && $currentStageLabel === \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES;
                    if ($isApproved) {
                        $currentStageLabel = 'Registrasi';
                    }
                @endphp
                <div class="m-progress-section">
                    <div class="m-progress-head">
                        Tahap Progres
                        <strong>{{ $currentStageLabel }}</strong>
                    </div>
                    @if($isUrgent)
                    <div class="mb-2">
                        <span class="urgency-chip urgent">Urgent</span>
                    </div>
                    @endif
                    <div class="stepper-mini mb-0">
                        @foreach($stages as $index => $stage)
                            @php
                                $dotClass = '';
                                $dotValue = $index + 1;
                                if ($isApproved) {
                                    $dotClass = 'done';
                                    $dotValue = '?';
                                } elseif ($currentStage !== false) {
                                    if ($index < $currentStage) {
                                        $dotClass = 'done';
                                        $dotValue = '?';
                                    } elseif ($index === $currentStage) {
                                        $dotClass = $isPendingStage && $currentStageLabel !== \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES ? 'pending-current' : 'current';
                                    }
                                }
                            @endphp
                            <div class="step-dot {{ $dotClass }}" title="{{ $stage }}">{{ $dotValue }}</div>
                            @if(!$loop->last)
                                <div class="step-line {{ ($isApproved || ($currentStage !== false && $index < $currentStage)) ? 'done' : '' }}"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <details class="mobile-quick-panel">
                    @php
                        $currentProgress = $p->progres ?? \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES;
                    @endphp
                    <summary class="quick-mobile-title">
                        <span>Update cepat</span>
                        <small>Buka jika perlu</small>
                    </summary>
                    <div class="mobile-quick-body">
                        @if($p->progress_note)
                        <div class="quick-note-preview mb-2">
                            <span class="quick-note-preview-label">Catatan terakhir</span>
                            <p>{{ trim(preg_replace('/^\[PENDING\]\s*/i', '', preg_replace('/\*\(Diupdate oleh:.*?\)\*/s', '', $p->progress_note))) }}</p>
                        </div>
                        @endif
                        @if($isOwner)
                        <form action="{{ route('marketing.pelanggan.progres', $p->id) }}" method="POST" class="quick-progress-form">
                            @csrf
                            <input type="hidden" name="return_url" value="{{ request()->fullUrl() }}">
                            <div class="quick-progress-grid">
                            <select name="progres" class="progress-select">
                                @foreach(\App\Models\Pelanggan::PROGRES_STAGES as $stage)
                                <option value="{{ $stage }}" {{ $currentProgress === $stage ? 'selected' : '' }}>{{ $stage }}</option>
                                @endforeach
                            </select>
                            <label style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.75rem;color:var(--text-secondary);font-weight:600;">
                                <input type="checkbox" name="is_pending" value="1" {{ $isPendingStage && $currentProgress !== \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES ? 'checked' : '' }}>
                                Tandai Pending (Kendala)
                            </label>
                            <textarea
                                name="progress_note"
                                class="quick-note-input"
                                rows="3"
                                maxlength="1000"
                                required
                                placeholder="Wajib isi alasan/keterangan update status">{{ old('progress_note', '') }}</textarea>
                            <button type="submit" class="btn btn-dark btn-sm quick-save-btn" style="width:100%; border-radius: 8px; background: #111827;">
                                <i class="ri-save-line"></i> Simpan
                            </button>
                            </div>
                        </form>
                        @else
                        <div class="cell-sub">Readonly. Hanya user {{ optional($p->user)->name ?? '-' }} yang bisa ubah data ini.</div>
                        @endif
                    </div>
                </details>

                <div class="m-card-actions">
                    <button class="btn btn-outline-secondary btn-sm btn-detail" style="border-radius: 8px;">
                        <i class="ri-eye-line"></i> Detail
                    </button>
                    @if($isOwner)
                    <a href="{{ route('marketing.pelanggan.edit', $p->id) }}" class="btn btn-outline-primary btn-sm" style="border-radius: 8px;">
                        <i class="ri-edit-2-line"></i> Edit
                    </a>
                    <form action="{{ route('marketing.pelanggan.delete', $p->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete" style="width:100%; border-radius: 8px;">
                            <i class="ri-delete-bin-line"></i> Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="ri-inbox-line"></i>
                <p>Tidak ada data pelanggan.</p>
            </div>
            @endforelse
        </div>

        @include('components.marketing-pagination', ['paginator' => $pelanggan])
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0" style="box-shadow: 0 4px 16px rgba(0,0,0,0.12); border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid #e5e7eb; padding: 1rem 1.25rem;">
                <div>
                    <h6 class="modal-title mb-0" style="font-weight: 700; font-size: 0.9375rem; color: #111827;">Detail Pelanggan</h6>
                    <small id="detail-id" class="text-muted" style="font-family: monospace;"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 1.25rem;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">Nama Lengkap</p>
                            <p class="mb-0 fw-semibold" id="detail-nama"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">No. WhatsApp</p>
                            <a id="detail-whatsapp-link" href="#" target="_blank" class="text-decoration-none d-flex align-items-center gap-1" style="color:#16a34a;">
                                <i class="ri-whatsapp-line"></i>
                                <span id="detail-whatsapp" class="fw-semibold"></span>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">Status</p>
                            <p class="mb-0 fw-semibold" id="detail-status"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">Progres</p>
                            <p class="mb-0 fw-semibold" id="detail-progres"></p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">Alamat</p>
                            <p class="mb-0" id="detail-alamat"></p>
                            <small class="text-muted">RT/RW: <span id="detail-rt-rw"></span> | Kec. <span id="detail-kecamatan"></span> | Kab. <span id="detail-kabupaten"></span></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">Tgl Gabung</p>
                            <p class="mb-0" id="detail-tanggal-mulai"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">Marketing</p>
                            <p class="mb-0" id="detail-marketing"></p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-1" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">Catatan Progres</p>
                            <p class="mb-0 fst-italic text-secondary" id="detail-note" style="font-size:0.875rem;"></p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3" style="background:#f9fafb; border-radius:8px; border:1px solid #e5e7eb;">
                            <p class="text-muted mb-2" style="font-size:0.7rem; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">Foto KTP</p>
                            <img id="detail-foto-ktp" src="" alt="Foto KTP" class="img-fluid d-none" style="max-height:200px; border-radius:8px; border:1px solid #e5e7eb;">
                            <p id="detail-no-foto" class="text-muted mb-0 d-none" style="font-size:0.85rem;">Foto KTP tidak tersedia.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 0.75rem 1.25rem; background: #f8fafc;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection
