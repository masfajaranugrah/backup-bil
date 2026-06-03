@extends('layouts/layoutMaster')

@section('title', 'Status Pelanggan')

@section('vendor-style')
@vite([
  'resources/css/app.css',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
<style>
/* ========================================= */
/* SHADCN UI STYLE - BLACK & WHITE */
/* ========================================= */
:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --card-hover-shadow: 0 4px 16px rgba(0,0,0,0.12);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --primary-color: #18181b;
  --gray-bg: #fafafa;
  --gray-border: #e4e4e7;
}

* {
  box-sizing: border-box;
}

body {
  background: #f5f5f9;
}

/* ========== CARD ========== */
.card {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--card-shadow);
  background: white;
  transition: var(--transition);
  overflow: hidden;
}

.card:hover {
  box-shadow: var(--card-hover-shadow);
}

/* ========== HEADER SECTION ========== */
.card-header-custom {
  background:
    radial-gradient(circle at top right, rgba(14, 165, 233, 0.10), transparent 28%),
    linear-gradient(180deg, #ffffff 0%, #f8fafc 100%) !important;
  border-bottom: 1px solid #eef2f7;
  padding: 1.5rem;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.card-header-custom h4 {
  color: #18181b !important;
  font-size: 1.28rem;
  font-weight: 800;
}

.card-header-custom p {
  color: #71717a !important;
}

.card-header-custom i {
  color: #18181b !important;
}

/* ========== STATS CARDS ========== */
.stats-card {
  border-radius: var(--border-radius);
  padding: 1.5rem;
  background: #18181b;
  border: none;
  transition: var(--transition);
  color: #fafafa;
}

.stats-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(24, 24, 27, 0.3);
}

.stats-card h2,
.stats-card .fw-bold {
  color: #fafafa !important;
}

.stats-card .text-muted {
  color: rgba(250, 250, 250, 0.7) !important;
}

.stats-icon {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
}

.bg-label-primary {
  background: rgba(250, 250, 250, 0.15) !important;
  color: #fafafa !important;
}

.bg-label-success {
  background: rgba(250, 250, 250, 0.15) !important;
  color: #fafafa !important;
}

.bg-label-secondary {
  background: rgba(250, 250, 250, 0.15) !important;
  color: #fafafa !important;
}

.bg-label-info {
  background: #18181b !important;
  color: #fafafa !important;
}

.bg-label-dark {
  background: #18181b !important;
  color: #fafafa !important;
}

.text-success {
  color: #18181b !important;
}

.text-secondary {
  color: #71717a !important;
}

/* ========== BUTTONS - ALL BLACK ========== */
.btn {
  border-radius: 6px !important;
  padding: 0.5rem 1rem !important;
  font-weight: 500 !important;
  font-size: 0.875rem !important;
  transition: all 0.15s ease !important;
  cursor: pointer !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  gap: 0.5rem !important;
}

.btn-primary,
.btn.btn-primary {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
  box-shadow: none !important;
}

.btn-primary:hover,
.btn.btn-primary:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
  transform: translateY(-2px) !important;
}

.btn-success,
.btn.btn-success {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn-success:hover,
.btn.btn-success:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn-secondary,
.btn.btn-secondary {
  background: transparent !important;
  background-color: transparent !important;
  border: 1px solid #e4e4e7 !important;
  color: #18181b !important;
}

.btn-secondary:hover,
.btn.btn-secondary:hover {
  background: #f4f4f5 !important;
  background-color: #f4f4f5 !important;
  border-color: #18181b !important;
  color: #18181b !important;
}

/* ========== FORM CONTROLS ========== */
.form-control,
.form-select {
  border-radius: 8px;
  border: 1px solid #e4e4e7;
  padding: 0.625rem 1rem;
  transition: var(--transition);
  color: #18181b;
}

.form-control:focus,
.form-select:focus {
  border-color: #18181b;
  box-shadow: 0 0 0 3px rgba(24, 24, 27, 0.1);
}

/* ========== TABLE STYLES ========== */
.table-modern {
  margin-bottom: 0;
  border-radius: 0;
  overflow: hidden;
  border-collapse: collapse;
  border-spacing: 0;
  table-layout: fixed;
}

.table-modern thead th {
  background: #f8fafc;
  font-weight: 800;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0;
  color: #64748b;
  padding: 1rem 1.1rem;
  border-bottom: 1px solid #e5eaf0;
  white-space: nowrap;
}

.table-modern tbody tr {
  transition: var(--transition);
}

.table-modern tbody tr:not(.empty-state-row):hover td {
  background-color: #fcfcfd !important;
}

.table-modern tbody td {
  padding: 1rem 1.1rem;
  vertical-align: middle;
  border-bottom: 1px dashed #e5eaf0;
  color: #18181b;
  white-space: nowrap;
  transition: background 0.2s;
}

.table-modern.is-dense thead th,
.table-modern.is-dense tbody td {
  padding-top: 0.62rem;
  padding-bottom: 0.62rem;
}

.status-row-checkbox,
.mui-checkbox,
#densePaddingToggleStatus {
  width: 20px;
  min-width: 20px;
  height: 20px;
  min-height: 20px;
  border-radius: 6px;
  accent-color: #18181b;
  cursor: pointer;
  margin: 0;
}

.status-row-selected {
  background: #f8fafc !important;
}

.table-modern tbody tr.status-row-selected td {
  background: #edf4fd !important;
}

.status-selection-toolbar {
  display: none;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 0.9rem 1.25rem;
  background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
  border-bottom: 1px solid #e4e4e7;
}

.status-selection-toolbar.active {
  display: flex;
}

.status-selection-toolbar .selected-text {
  color: #18181b;
  font-size: 0.95rem;
  font-weight: 800;
}

.delete-selected-btn {
  width: 40px;
  height: 40px;
  border: 0;
  border-radius: 10px;
  background: transparent;
  color: #71717a;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.35rem;
  transition: var(--transition);
}

.delete-selected-btn:hover {
  background: rgba(220, 38, 38, 0.10);
  color: #dc2626;
}

.dense-toggle-wrap {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  font-weight: 600;
  color: #334155;
  min-height: 34px;
}

.status-icon {
  width: 32px;
  min-width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ========== BADGES ========== */
.badge {
  border-radius: 9999px !important;
  font-weight: 500 !important;
  padding: 0.35rem 0.75rem !important;
}

.badge.bg-success {
  background: #18181b !important;
  color: #fafafa !important;
}

.badge.bg-secondary {
  background: #f4f4f5 !important;
  color: #71717a !important;
  border: 1px solid #e4e4e7;
}

/* ========== PAGINATION STYLES ========== */
.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.1rem 1.5rem;
  border-top: 1px solid #eef2f7;
  background: #ffffff;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.pagination-info {
  color: #71717a;
  font-size: 0.875rem;
  font-weight: 500;
}

.pagination {
  margin: 0;
  gap: 0.45rem;
  justify-content: flex-end;
}

.pagination .page-item .page-link {
  border-radius: 50% !important;
  width: 32px;
  min-width: 32px;
  height: 32px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e4e4e7;
  color: #18181b;
  font-weight: 600;
  background-color: #fff;
  margin: 0 2px;
  transition: all 0.3s ease;
}

.pagination-wrapper .mui-pagination {
  align-items: center;
  gap: 0.45rem;
}

.pagination-wrapper .mui-pagination .page-item,
.pagination-wrapper .mui-pagination .page-link {
  width: 34px !important;
  min-width: 34px !important;
  max-width: 34px !important;
  height: 34px !important;
  min-height: 34px !important;
  max-height: 34px !important;
  flex: 0 0 34px !important;
}

.pagination-wrapper .mui-pagination .page-link {
  margin: 0 !important;
  padding: 0 !important;
  border: 0 !important;
  border-radius: 50% !important;
  background: transparent !important;
  color: #1f2937 !important;
  box-shadow: none !important;
  font-size: 0.9rem;
  font-weight: 700;
  line-height: 1 !important;
  overflow: hidden;
  white-space: nowrap;
}

.pagination-wrapper .mui-pagination .page-item.active .page-link {
  background: #1f2933 !important;
  color: #ffffff !important;
}

.pagination-wrapper .mui-pagination .page-link:hover {
  background: rgba(31, 41, 51, 0.06) !important;
  color: #111827 !important;
}

.pagination-wrapper .mui-pagination .page-item.disabled .page-link {
  background: transparent !important;
  color: #cbd5e1 !important;
}

.pagination-wrapper .mui-pagination .pagination-ellipsis .page-link {
  color: #64748b !important;
  letter-spacing: 0.08em;
}

.pagination .page-item .page-link:hover {
  background-color: #f4f4f5;
  border-color: #18181b;
  color: #18181b;
}

.pagination .page-item.active .page-link {
  background-color: #18181b !important;
  border-color: #18181b !important;
  color: #fafafa !important;
  box-shadow: none;
}

.pagination .page-item.disabled .page-link {
  background-color: #f4f4f5;
  border-color: #e4e4e7;
  color: #a1a1aa;
  cursor: not-allowed;
}

/* Hide default Laravel pagination results text */
.pagination-wrapper > div:last-child p,
.pagination-wrapper div:last-child > p,
nav[role="navigation"] > div:first-child,
nav[role="navigation"] > div > p {
  display: none !important;
}

/* ========== LOADING OVERLAY ========== */
.loading-overlay {
  position: fixed;
  inset: 0;
  background: rgba(24, 24, 27, 0.5);
  backdrop-filter: blur(4px);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.spinner-border-custom {
  width: 3rem;
  height: 3rem;
  border-width: 0.3rem;
}

/* ========== EMPTY STATE ========== */
.empty-state-row td {
  background: #fafbfc !important;
  border: none !important;
}

.empty-state-content {
  padding: 3rem 1rem;
}

table.dataTable tbody tr.empty-state-row,
table.dataTable tbody tr.empty-state-row:hover {
  background: #fafbfc !important;
}

/* ========== SCROLLBAR ========== */
.table-responsive::-webkit-scrollbar {
  height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background: #18181b;
  border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
  background: #27272a;
}

/* ========== HIDE DATATABLES CONTROLS ========== */
.dataTables_info,
.dataTables_paginate,
.dataTables_length {
  display: none !important;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
  .pagination-wrapper {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }

  .stats-card {
    margin-bottom: 1rem;
  }

  .table-responsive {
    margin-bottom: 1rem;
  }
}

/* ========== ANIMATIONS ========== */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.card {
  animation: fadeIn 0.3s ease-out;
}
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    @if(session('success'))
      Swal.fire({
        toast: true,
        position: 'bottom-end',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        timer: 3200,
        timerProgressBar: true
      });
    @endif

    const statusTable = document.querySelector('.status-table');
    const denseToggle = document.getElementById('densePaddingToggleStatus');
    if (denseToggle && statusTable) {
      const saved = localStorage.getItem('dense_status_pelanggan') === '1';
      denseToggle.checked = saved;
      statusTable.classList.toggle('is-dense', saved);

      denseToggle.addEventListener('change', function () {
        statusTable.classList.toggle('is-dense', this.checked);
        localStorage.setItem('dense_status_pelanggan', this.checked ? '1' : '0');
      });
    }

    const selectAllStatus = document.getElementById('selectAllStatus');
    const statusCheckboxes = document.querySelectorAll('.status-checkbox');
    const statusToolbar = document.getElementById('statusSelectionToolbar');
    const statusSelectedCount = document.getElementById('statusSelectedCount');
    const statusBulkDeleteBtn = document.getElementById('statusBulkDeleteBtn');
    const csrfToken = @json(csrf_token());

    function syncStatusSelection() {
      const checked = document.querySelectorAll('.status-checkbox:checked');
      if (statusSelectedCount) statusSelectedCount.textContent = `${checked.length} dipilih`;
      if (statusToolbar) statusToolbar.classList.toggle('active', checked.length > 0);
      if (selectAllStatus) {
        selectAllStatus.checked = statusCheckboxes.length > 0 && checked.length === statusCheckboxes.length;
        selectAllStatus.indeterminate = checked.length > 0 && checked.length < statusCheckboxes.length;
      }

      document.querySelectorAll('tr[data-pelanggan-row]').forEach((row) => {
        const checkbox = row.querySelector('.status-checkbox');
        row.classList.toggle('status-row-selected', !!checkbox && checkbox.checked);
      });
    }

    async function deleteStatusRows(targets) {
      const rows = Array.from(targets).filter((row) => row && row.dataset.deleteUrl);
      if (!rows.length) return;

      const result = await Swal.fire({
        title: rows.length > 1 ? 'Hapus pelanggan terpilih?' : 'Hapus pelanggan ini?',
        html: `<p class="mb-0">Anda akan menghapus <strong>${rows.length}</strong> data pelanggan.<br><span style="color:#71717a;font-size:0.875rem;">Data tidak dapat dikembalikan.</span></p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#71717a'
      });

      if (!result.isConfirmed) return;

      const overlay = document.querySelector('.loading-overlay');
      if (overlay) overlay.style.display = 'flex';

      let successCount = 0;
      for (const row of rows) {
        try {
          const response = await fetch(row.dataset.deleteUrl, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8'
            }
          });
          if (response.ok) successCount++;
        } catch (error) {
          console.warn('Gagal menghapus pelanggan:', error);
        }
      }

      if (overlay) overlay.style.display = 'none';

      if (successCount === rows.length) {
        Swal.fire({
          toast: true,
          position: 'bottom-end',
          icon: 'success',
          title: `${successCount} data pelanggan berhasil dihapus`,
          showConfirmButton: false,
          timer: 2200
        }).then(() => window.location.reload());
      } else {
        Swal.fire('Sebagian gagal', `${rows.length - successCount} data gagal dihapus. Coba ulangi lagi.`, 'warning')
          .then(() => window.location.reload());
      }
    }

    if (selectAllStatus) {
      selectAllStatus.addEventListener('change', function () {
        statusCheckboxes.forEach((checkbox) => {
          checkbox.checked = this.checked;
        });
        syncStatusSelection();
      });
    }

    statusCheckboxes.forEach((checkbox) => {
      checkbox.addEventListener('change', syncStatusSelection);
    });

    if (statusBulkDeleteBtn) {
      statusBulkDeleteBtn.addEventListener('click', function () {
        const rows = Array.from(document.querySelectorAll('.status-checkbox:checked'))
          .map((checkbox) => checkbox.closest('tr[data-pelanggan-row]'));
        deleteStatusRows(rows);
      });
    }

    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
      statusFilter.addEventListener('change', function () {
        this.form.submit();
      });
    }

    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
      filterForm.addEventListener('submit', function () {
        const overlay = document.querySelector('.loading-overlay');
        if (overlay) overlay.style.display = 'flex';
      });
    }
  });
</script>
@endsection

@section('content')
<div class="loading-overlay">
  <div class="spinner-border spinner-border-custom text-light" role="status">
    <span class="visually-hidden">Loading...</span>
  </div>
</div>

<div class="container-fluid px-4 py-4">
 

 

  {{-- Filter & Search --}}
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" action="{{ route('pelanggan.status.active') }}" id="filterForm">
        <div class="row g-3 align-items-end">
          <div class="col-md-5">
            <label class="form-label small fw-semibold mb-2">
              <i class="ri-search-line me-1"></i>Pencarian
            </label>
            <input
              type="text"
              name="search"
              class="form-control"
              placeholder="Cari nama, No. ID, WhatsApp, alamat..."
              value="{{ request('search') }}">
          </div>

          <div class="col-md-3">
            <label class="form-label small fw-semibold mb-2">
              <i class="ri-filter-3-line me-1"></i>Filter Status
            </label>
            <select
              name="status_filter"
              id="statusFilter"
              class="form-select">
              <option value="">Semua Status</option>
              <option value="Active" {{ request('status_filter') == 'Active' ? 'selected' : '' }}>Active</option>
              <option value="Inactive" {{ request('status_filter') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>

          <div class="col-md-4">
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1" style="height: 42px !important;">
                <i class="ri-search-line me-1 text-white"></i>Cari
              </button>

              @if(request('status_filter') || request('search'))
                <a href="{{ route('pelanggan.status.active') }}" class="btn btn-secondary">
                  <i class="ri-refresh-line me-1"></i>Reset
                </a>
              @endif
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Data Table --}}
<div class="card border-0 shadow-sm">
  <div class="card-header-custom">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <h4 class="mb-1 fw-bold">
          <i class="ri-user-follow-line me-2"></i>Status Pelanggan
        </h4>
        <p class="mb-0 opacity-75 small">Monitor status login dan aktivitas pelanggan secara real-time.</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        {{-- Badge total data --}}
        @if($pelanggan->total() > 0)
          <span class="badge" style="background-color: #f4f4f5; color: #18181b; padding: 0 20px; height: 42px; display: inline-flex; align-items: center; font-size: 0.9rem; border-radius: 50px !important; border: 1px solid #e4e4e7;">
            <i class="ri-database-2-line me-2"></i>
            {{ $pelanggan->total() }} Data Total
          </span>
        @endif

        {{-- Export Excel di kanan header --}}
        <a href="{{ url('/pelanggan/export') }}" class="btn btn-primary" style="background-color: #18181b !important; border-color: #18181b !important; color: white !important; height: 42px !important; display: inline-flex !important; align-items: center !important; padding: 0 20px !important; border-radius: 8px !important;">
          <i class="ri-file-excel-2-line me-1" style="color: #ffffff !important;"></i> Export Excel
        </a>
      </div>
    </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <div class="status-selection-toolbar" id="statusSelectionToolbar">
          <span class="selected-text" id="statusSelectedCount">0 dipilih</span>
          <button type="button" class="delete-selected-btn" id="statusBulkDeleteBtn" title="Hapus data dipilih">
            <i class="ri-delete-bin-line"></i>
          </button>
        </div>
        <table class="table table-modern table-hover status-table mb-0" style="width: 100%;">
          <thead>
            <tr>
              <th style="width: 56px; text-align: center;">
                <input type="checkbox" class="mui-checkbox" id="selectAllStatus" aria-label="Pilih semua pelanggan">
              </th>
              <th>Nama</th>
              <th>No. WhatsApp</th>
              <th>Alamat</th>
              <th>No. ID</th>
              <th>Status</th>
              <th>Login Terakhir</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pelanggan as $index => $item)
              @php
                $isActive    = optional($item->loginStatus)->is_active;
                $loggedInAt  = optional($item->loginStatus)->logged_in_at;
              @endphp
              <tr data-pelanggan-row data-delete-url="{{ route('pelanggan.delete', $item->id) }}">
                <td class="text-center">
                  <input type="checkbox" class="status-row-checkbox status-checkbox" value="{{ $item->id }}" aria-label="Pilih pelanggan {{ $item->nama_lengkap }}">
                </td>

                <td>
                  <div class="d-flex align-items-center">
                  
                    <span class="fw-semibold">{{ $item->nama_lengkap }}</span>
                  </div>
                </td>

                <td>
                  <a
                    href="https://wa.me/{{ $item->no_whatsapp }}"
                    target="_blank"
                    class="text-decoration-none">
                    <code style="background: #18181b; padding: 6px 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 600; color: #fafafa;">
                      <i class="ri-whatsapp-line me-1" style="color: #fafafa;"></i>{{ $item->no_whatsapp }}
                    </code>
                  </a>
                </td>

                <td>
                  <div style="min-width: 200px; max-width: 250px;">
                    <div class="text-truncate">{{ $item->alamat_jalan ?? '-' }}</div>
                    <small class="text-muted">
                      RT {{ $item->rt ?? '-' }}/RW {{ $item->rw ?? '-' }}, {{ $item->kecamatan ?? '-' }}
                    </small>
                  </div>
                </td>

                <td>
                  <span class="badge bg-label-dark" style="padding: 8px 12px; font-size: 0.85rem; font-family: monospace;">
                    {{ $item->nomer_id ?? '-' }}
                  </span>
                </td>

                <td>
                  @if($isActive)
                    <span class="badge bg-success">
                      <i class="ri-checkbox-circle-line me-1"></i>Active
                    </span>
                  @else
                    <span class="badge bg-secondary">
                      <i class="ri-close-circle-line me-1"></i>Inactive
                    </span>
                  @endif
                </td>

                <td>
                  @if($loggedInAt)
                    <div>
                      <small class="d-block fw-semibold">
                        {{ $loggedInAt->timezone(config('app.timezone'))->format('d M Y') }}
                      </small>
                      <small class="text-muted">
                        {{ $loggedInAt->timezone(config('app.timezone'))->format('H:i') }} WIB
                      </small>
                    </div>
                  @else
                    <span class="text-muted small">Belum pernah login</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr class="empty-state-row">
                <td colspan="7" class="text-center">
                  <div class="empty-state-content">
                    <div class="mb-3">
                      <i class="ri-inbox-line" style="font-size: 4rem; color: #ddd;"></i>
                    </div>

                    @if(request('search') || request('status_filter'))
                      <h5 class="text-muted mb-2">
                        <i class="ri-search-eye-line me-2"></i>Data Tidak Ditemukan
                      </h5>
                      <p class="text-muted mb-3">
                        Tidak ada data yang sesuai dengan pencarian atau filter yang Anda pilih.
                      </p>

                      <div class="mb-3">
                        @if(request('search'))
                          <span class="badge bg-label-primary me-2" style="padding: 8px 16px;">
                            <i class="ri-search-line me-1"></i>
                            Pencarian: "{{ request('search') }}"
                          </span>
                        @endif

                        @if(request('status_filter'))
                          <span class="badge bg-label-info" style="padding: 8px 16px;">
                            <i class="ri-filter-line me-1"></i>
                            Status: {{ request('status_filter') }}
                          </span>
                        @endif
                      </div>

                      <a href="{{ route('pelanggan.status.active') }}" class="btn btn-primary mt-2">
                        <i class="ri-refresh-line me-1"></i>Reset Filter &amp; Tampilkan Semua Data
                      </a>
                    @else
                      <h5 class="text-muted mb-2">
                        <i class="ri-user-unfollow-line me-2"></i>Belum Ada Data Pelanggan
                      </h5>
                      <p class="text-muted">
                        Saat ini belum ada data pelanggan yang terdaftar dalam sistem.
                      </p>
                    @endif
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

      <div class="pagination-wrapper">
        <label class="dense-toggle-wrap mb-0">
          <input type="checkbox" class="mui-checkbox" id="densePaddingToggleStatus">
          <span>Dense padding</span>
        </label>
        <div>
          @if($pelanggan->hasPages())
            {{ $pelanggan->onEachSide(1)->links('pagination.mui') }}
          @else
            <nav>
              <ul class="pagination mui-pagination mb-0">
                <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
              </ul>
            </nav>
          @endif
        </div>
      </div>
  </div>

</div>
@endsection
