@extends('layouts/layoutMaster')

@section('title', 'Gaji Karyawan')

@php
use Illuminate\Support\Str;
@endphp

{{-- VENDOR STYLE --}}
@section('vendor-style')
@vite([
  'resources/css/app.css',
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
<style>
:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --card-hover-shadow: 0 4px 16px rgba(0,0,0,0.12);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --primary-color: #18181b;
  --gray-border: #e4e4e7;
}
.card {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--card-shadow);
  background: white;
  transition: var(--transition);
}
.card:hover { box-shadow: var(--card-hover-shadow); }
.stats-card {
  border-radius: 12px;
  transition: transform 0.2s, box-shadow 0.2s;
  background: #18181b;
  color: #fafafa;
}
.stats-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(24,24,27,0.3);
}
.badge-status { font-weight: 600; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; }
.action-buttons { gap: 12px; }
.card-header-custom {
  background: #ffffff !important;
  border-bottom: 1px solid var(--gray-border);
  padding: 1.5rem;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
  color: #18181b;
}
.card-header-custom h4 { color: #18181b !important; }
.card-header-custom p { color: #71717a !important; }
.card-header-custom i { color: #18181b !important; }
.btn-primary, .btn.btn-primary, .btn-add {
  background: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: 600;
}
.btn-primary i, .btn.btn-primary i { color: #ffffff !important; }
.btn-primary:hover, .btn-add:hover {
  background: #27272a !important;
  border-color: #27272a !important;
  transform: translateY(-2px) !important;
}
.btn-add i { margin-right: 8px; }
.btn-danger { background: #18181b !important; color: #fafafa !important; border: 1px solid #18181b !important; }
.btn-danger:hover { background: #27272a !important; }
.btn-secondary { background: transparent !important; border: 1px solid #e4e4e7 !important; color: #18181b !important; }
.btn-secondary:hover { background: #f4f4f5 !important; border-color: #18181b !important; }
.btn-outline-primary, .btn-outline-danger {
  background: transparent !important;
  border: 1px solid #18181b !important;
  color: #18181b !important;
}
.btn-outline-primary:hover, .btn-outline-danger:hover {
  background: #18181b !important;
  color: #fafafa !important;
}
.btn-icon {
  width: 32px;
  height: 32px;
  padding: 0 !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
}

/* ========== TABLE STYLES (MUI Style aligned with Tagihan) ========== */
.table-modern {
  width: 100%;
  border-collapse: collapse;
}

.table-modern thead th {
  text-align: left;
  padding: 1rem 1.25rem;
  font-size: 0.78rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #64748b;
  font-weight: 800;
  border-bottom: 1px solid #e5eaf0;
  background: #f8fafc;
  white-space: nowrap;
}

.table-modern tbody tr {
  transition: background 0.2s;
}

.table-modern tbody tr:hover td {
  background: #fcfcfd !important;
}

.table-modern tbody td {
  padding: 1.05rem 1.25rem;
  vertical-align: middle;
  border-bottom: 1px dashed #e5eaf0;
  color: #18181b;
}

.table-modern tr.row-selected td {
  background: #edf4fd !important;
}

.table-modern thead th:first-child,
.table-modern tbody td:first-child {
  text-align: center;
  width: 60px;
}

/* ========== BADGES - SHADCN STYLE ========== */
.badge {
  border-radius: 9999px !important;
  font-weight: 500 !important;
  letter-spacing: 0 !important;
  display: inline-flex !important;
  align-items: center !important;
  gap: 0.25rem !important;
  padding: 0.35rem 0.75rem !important;
}

.bg-label-dark {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
}

.bg-label-info {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
}

/* ========================================= */
/* PAGINATION STYLES */
/* ========================================= */
.table-modern.is-dense th {
  padding: 0.7rem 1rem !important;
}

.table-modern.is-dense td {
  padding: 0.65rem 1rem !important;
}

.dense-toggle-wrap {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  color: #334155;
}

.dense-toggle-wrap input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #0f172a;
}

.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.pagination-wrapper .pagination {
  flex-wrap: nowrap;
  gap: 0.35rem;
}

.pagination-wrapper .page-link {
  width: 36px;
  min-width: 36px;
  height: 36px;
  border-radius: 50% !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 0.85rem;
  padding: 0 !important;
  line-height: 1;
}

.pagination-wrapper .mui-pagination {
  align-items: center;
  gap: 0.5rem;
  display: flex;
}

.pagination-wrapper .mui-pagination .page-link {
  width: 36px !important;
  min-width: 36px !important;
  height: 36px !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
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
}

.pagination-wrapper .mui-pagination .page-link.page-nav-icon {
  width: 36px !important;
  min-width: 36px !important;
  height: 36px !important;
  padding: 0 !important;
  border-radius: 50% !important;
}

/* Hide DataTables sort arrows on table headers */
.table-modern thead th.sorting::before,
.table-modern thead th.sorting::after,
.table-modern thead th.sorting_asc::before,
.table-modern thead th.sorting_asc::after,
.table-modern thead th.sorting_desc::before,
.table-modern thead th.sorting_desc::after {
  display: none !important;
  content: none !important;
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

/* Custom Checkbox */
.custom-check {
  appearance: none;
  width: 18px;
  height: 18px;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  background: #fff;
  cursor: pointer;
  position: relative;
  vertical-align: middle;
  outline: none;
  box-shadow: none;
  display: block;
  margin: 0 auto;
  vertical-align: middle;
}
.custom-check:checked {
  background: #0f172a;
  border-color: #0f172a;
}
.custom-check:checked::after {
  content: '';
  position: absolute;
  top: 3px;
  left: 6px;
  width: 4px;
  height: 8px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.table-modern thead th:first-child,
.table-modern tbody td:first-child {
  width: 56px !important;
  text-align: center !important;
  vertical-align: middle !important;
  padding-left: 0.75rem !important;
  padding-right: 0.75rem !important;
}

.checkbox-col {
  width: 56px !important;
  text-align: center !important;
}

.checkbox-wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
}

/* Selection Toolbar */
.selection-toolbar {
  display: none;
  align-items: center;
  justify-content: space-between;
  background: #e7f0fb;
  border-bottom: 1px solid #d6e4f3;
  padding: 0.85rem 1.25rem;
}
.selection-toolbar.active {
  display: flex;
}
.selection-toolbar .selected-text {
  font-weight: 700;
  color: #0f172a;
  font-size: 1rem;
}
.selection-toolbar .clear-btn {
  border: 0;
  background: transparent;
  color: #ff4528;
  width: 34px;
  height: 34px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s;
  cursor: pointer;
}
.selection-toolbar .clear-btn:hover {
  background: rgba(255, 69, 40, 0.08);
}

/* Product Cell */
.product-cell {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}
.product-info h6 {
  margin: 0 0 0.15rem 0;
  font-weight: 700;
  color: #0f172a;
  font-size: 0.9rem;
}
.product-info span {
  font-size: 0.78rem;
  color: #64748b;
  display: block;
}

/* Action Menu (3 Dots) */
.action-btn {
  background: transparent;
  border: none;
  color: #94a3b8;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}
.action-btn:hover {
  background: #f1f5f9;
  color: #0f172a;
}
.tagihan-action-menu {
  position: absolute !important;
  top: 50% !important;
  inset: auto calc(100% + 14px) auto auto !important;
  transform: translateY(-50%) !important;
  min-width: 250px;
  padding: 10px;
  border: 1px solid #d7e2ee;
  border-radius: 22px;
  background: linear-gradient(110deg, #fff4f2 0%, #edf7ff 100%);
  box-shadow: 0 18px 36px rgba(15, 23, 42, 0.14);
  z-index: 1200;
}

.datatables-users .dropdown {
  position: relative !important;
}

.tagihan-action-menu::after {
  content: '';
  position: absolute;
  right: -9px;
  top: 50%;
  width: 18px;
  height: 18px;
  border-top: 1px solid #d7e2ee;
  border-right: 1px solid #d7e2ee;
  background: #f2f8ff;
  transform: translateY(-50%) rotate(45deg);
}
.tagihan-action-menu .dropdown-item {
  border-radius: 14px;
  padding: 12px 14px;
  font-size: 1.05rem;
  font-weight: 700;
  color: #1e293b;
  display: flex;
  align-items: center;
  gap: 12px;
  transition: all 0.2s;
}
.tagihan-action-menu .dropdown-item:hover {
  background: rgba(255, 255, 255, 0.78);
  color: #1e293b;
}
.tagihan-action-menu .dropdown-item i {
  font-size: 1.2rem;
  color: inherit;
}
.tagihan-action-menu .dropdown-item.text-danger {
  color: #ff3b30;
}

.table-responsive {
  overflow-x: auto !important;
  overflow-y: visible !important;
}

.card-datatable {
  overflow: visible !important;
}

/* Hide duplicate pagination summary from Laravel Links */
.pagination-wrapper nav .text-muted {
    display: none !important;
}
.dataTables_info {
    display: none !important;
}

/* ========================================= */
/* RESTORED CSS CLASSES (MODAL, LOADER, DLL) */
/* ========================================= */
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
.spinner-border-custom { width: 3rem; height: 3rem; border-width: 0.3rem; }
.modal-content { border-radius: 16px; border: none; box-shadow: 0 8px 32px rgba(0,0,0,0.15); }
.modal-header {
  background: #18181b;
  border-radius: 16px 16px 0 0;
  padding: 1.5rem;
  border-bottom: none;
}
.modal-title { font-weight: 600; font-size: 1.125rem; color: #ffffff; }
.modal-body { padding: 2rem; max-height: 70vh; overflow-y: auto; }
.modal-footer { padding: 1.5rem; border-top: 1px solid #f0f0f0; background: #fafafa; }
.btn-close-white { filter: brightness(0) invert(1); }
/* Modal Blur Effect */
.modal-backdrop { 
  backdrop-filter: blur(8px) !important; 
  -webkit-backdrop-filter: blur(8px) !important; 
  background-color: rgba(24, 24, 27, 0.5) !important; 
}
.modal-backdrop.show { opacity: 1 !important; }
.modal { backdrop-filter: none !important; }
.modal-content { backdrop-filter: none !important; filter: none !important; }
.detail-section {
  background: #ffffff;
  border: 1px solid #e4e4e7;
  border-radius: 12px;
  padding: 1.25rem;
  margin-bottom: 1.25rem;
  transition: all 0.2s;
}
.detail-section:hover { box-shadow: 0 2px 8px rgba(0,0,0,0.08); border-color: #18181b; }
.detail-section h6 {
  color: #18181b;
  font-weight: 700;
  margin-bottom: 1.25rem;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  display: flex;
  align-items: center;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #18181b;
}
.detail-section h6 i { margin-right: 0.5rem; font-size: 1.1rem; }
.detail-item { display: flex; padding: 0.875rem 0; border-bottom: 1px solid #f0f0f0; align-items: flex-start; }
.detail-item:last-child { border-bottom: none; padding-bottom: 0; }
.detail-label {
  color: #18181b;
  font-weight: 600;
  min-width: 180px;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
}
.detail-label i { margin-right: 0.5rem; color: #71717a; font-size: 1rem; }
.detail-value { color: #18181b; font-size: 0.875rem; flex: 1; word-break: break-word; }
.employee-header-info {
  text-align: center;
  padding: 1.5rem;
  background: #f4f4f5;
  border-radius: 12px;
  margin-bottom: 1.5rem;
  border: 1px solid #e4e4e7;
}
.employee-name { font-size: 1.5rem; font-weight: 700; color: #18181b; margin-bottom: 0.5rem; }
.btn-icon-detail {
  width: 32px;
  height: 32px;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  transition: all 0.3s;
}
.pagination-wrapper .mui-pagination {
  gap: 0.5rem !important; /* Fix pagination kepenet */
}

.gaji-toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 11000;
  min-width: 280px;
  max-width: min(380px, calc(100vw - 32px));
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.9rem 1rem;
  border-radius: 12px;
  background: #18181b;
  color: #ffffff;
  box-shadow: 0 18px 36px rgba(15, 23, 42, 0.18);
  transform: translateY(10px);
  opacity: 0;
  pointer-events: none;
  transition: all 0.25s ease;
}

.gaji-toast.show {
  transform: translateY(0);
  opacity: 1;
}

.gaji-toast i {
  color: #86efac;
  font-size: 1.25rem;
}

.gaji-toast span {
  color: #ffffff;
  font-size: 0.9rem;
  font-weight: 700;
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
<script>
document.addEventListener("DOMContentLoaded", function() {

    function showGajiToast(message) {
        if (!message) return;
        const toast = document.createElement('div');
        toast.className = 'gaji-toast';
        toast.innerHTML = `<i class="ri-check-line"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        toast.offsetHeight;
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3200);
    }

    @if(session('success'))
        showGajiToast(@json(session('success')));
    @endif

    const pendingGajiToast = localStorage.getItem('gaji_toast_success');
    if (pendingGajiToast) {
        localStorage.removeItem('gaji_toast_success');
        showGajiToast(pendingGajiToast);
    }

    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }

    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    // Inisialisasi DataTables
    const dtUserTable = $('.datatables-users').DataTable({
        paging: false,
        searching: false, // Disable DataTables search
        ordering: true,
        info: false,
        responsive: false,
        dom: 'rt',
        columnDefs: [
            { orderable: false, targets: [0, 1, -1] }
        ],
        language: {
            zeroRecords: "Tidak ada data yang sesuai"
        }
    });

    // Event Detail Gaji
    $(document).on('click', '.btn-detail', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const tr = $(this).closest('tr');
        const nama = tr.data('nama') || '-';
        const gajiPokok = tr.data('gaji-pokok') || '0';
        const tunjJabatan = tr.data('tunj-jabatan') || '0';
        const tunjFungsional = tr.data('tunj-fungsional') || '0';
        const transport = tr.data('transport') || '0';
        const makan = tr.data('makan') || '0';
        const tunjDynamic = tr.data('tunj-dynamic') || '-';
        const tunjKehadiran = tr.data('tunj-kehadiran') || '0';
        const lembur = tr.data('lembur') || '0';
        const potSosial = tr.data('pot-sosial') || '0';
        const potDenda = tr.data('pot-denda') || '0';
        const potKoperasi = tr.data('pot-koperasi') || '0';
        const potPajak = tr.data('pot-pajak') || '0';
        const potLain = tr.data('pot-lain') || '0';
        const total = tr.data('total') || '0';
        const grandTotal = tr.data('grand-total') || '0';

        const initial = nama ? nama.charAt(0).toUpperCase() : '?';

        const html = `
            <div class="employee-header-info">
                <div class="employee-name">${nama}</div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-money-dollar-circle-line"></i>Komponen Gaji Pokok</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-bank-card-line"></i>Gaji Pokok
                    </span>
                    <span class="detail-value"><strong>Rp ${gajiPokok}</strong></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-briefcase-line"></i>Tunjangan Jabatan
                    </span>
                    <span class="detail-value">Rp ${tunjJabatan}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-award-line"></i>Tunjangan Fungsional
                    </span>
                    <span class="detail-value">Rp ${tunjFungsional}</span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-gift-line"></i>Tunjangan Tambahan</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-taxi-line"></i>Transport
                    </span>
                    <span class="detail-value">Rp ${transport}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-restaurant-line"></i>Makan
                    </span>
                    <span class="detail-value">Rp ${makan}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-star-line"></i>Tunjangan Dinamis
                    </span>
                    <span class="detail-value">${tunjDynamic}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-calendar-check-line"></i>Tunjangan Kehadiran
                    </span>
                    <span class="detail-value">Rp ${tunjKehadiran}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-time-line"></i>Lembur
                    </span>
                    <span class="detail-value">Rp ${lembur}</span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-subtract-line"></i>Potongan</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-hand-heart-line"></i>Potongan Sosial
                    </span>
                    <span class="detail-value text-danger">Rp ${potSosial}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-error-warning-line"></i>Potongan Denda
                    </span>
                    <span class="detail-value text-danger">Rp ${potDenda}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-store-line"></i>Potongan Koperasi
                    </span>
                    <span class="detail-value text-danger">Rp ${potKoperasi}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-file-list-line"></i>Potongan Pajak
                    </span>
                    <span class="detail-value text-danger">Rp ${potPajak}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-more-line"></i>Potongan Lainnya
                    </span>
                    <span class="detail-value text-danger">Rp ${potLain}</span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-calculator-line"></i>Total Gaji</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-money-dollar-box-line"></i>Total Penghasilan
                    </span>
                    <span class="detail-value"><strong>Rp ${total}</strong></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-wallet-3-line"></i>Grand Total (Setelah Potongan)
                    </span>
                    <span class="detail-value text-success"><strong style="font-size: 1.1rem;">Rp ${grandTotal}</strong></span>
                </div>
            </div>
        `;

        $('#detailModal .modal-body').html(html);
        $('#detailModal').modal('show');
    });

    // Event DELETE dengan konfirmasi - VERSI SIMPLE
    $(document).on('click', '.btn-delete-single', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = $(this).closest('.employee-row').find('form');

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Yakin ingin menghapus data gaji ini? Data tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
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
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                form.submit();
            }
        });
    });

    // ========== CHECKBOX SELECT ALL & BULK DELETE ==========
    const $selectAll = $('#selectAllEmployees');
    const $selectionToolbar = $('#selectionToolbar');
    const $selectedCount = $('#selectedCount');
    const $clearSelectionBtn = $('#clearSelectionBtn');

    function updateBulkState() {
        const $all = $('.employee-checkbox');
        const $checked = $('.employee-checkbox:checked');
        const count = $checked.length;
        $selectAll.prop('checked', $all.length > 0 && count === $all.length);
        
        if (count > 0) {
            $selectionToolbar.addClass('active');
            $selectedCount.text(count);
            $('.employee-row').removeClass('row-selected');
            $checked.each(function() {
                $(this).closest('.employee-row').addClass('row-selected');
            });
        } else {
            $selectionToolbar.removeClass('active');
            $('.employee-row').removeClass('row-selected');
        }
    }

    $selectAll.on('change', function() {
        $('.employee-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkState();
    });

    $(document).on('change', '.employee-checkbox', function() {
        updateBulkState();
    });

    $clearSelectionBtn.on('click', function() {
        const $checked = $('.employee-checkbox:checked');
        if ($checked.length === 0) return;

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: `Yakin ingin menghapus ${$checked.length} data gaji terpilih?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
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
            buttonsStyling: false
        }).then(async (result) => {
            if (result.isConfirmed) {
                showLoading();
                let successCount = 0;
                
                for(let i=0; i<$checked.length; i++) {
                    const tr = $($checked[i]).closest('.employee-row');
                    const form = tr.find('form');
                    const url = form.attr('action');
                    const token = form.find('input[name="_token"]').val();
                    
                    try {
                        await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: `_token=${token}&_method=DELETE`
                        });
                        successCount++;
                    } catch (e) {
                        console.error('Failed to delete', url);
                    }
                }
                
                if (successCount > 0) {
                    localStorage.setItem('gaji_toast_success', `${successCount} data gaji berhasil dihapus.`);
                }
                hideLoading();
                window.location.reload();
            }
        });
    });




// Event Copy Share Link
$(document).on('click', '.btn-share', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const url = $(this).data('url');
    
    // Copy to clipboard
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Link slip gaji berhasil disalin ke clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        });
    } else {
        // Fallback for non-secure context
        let textArea = document.createElement("textarea");
        textArea.value = url;
        textArea.style.position = "fixed";
        textArea.style.left = "-9999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            document.execCommand('copy');
            Swal.fire({
                icon: 'success',
                title: 'Disalin!',
                text: 'Link slip gaji berhasil disalin ke clipboard.',
                timer: 1500,
                showConfirmButton: false
            });
        } catch (err) {
            console.error('Fallback copy failed', err);
        }
        document.body.removeChild(textArea);
    }
});

    // Dense padding toggle
    const denseToggle = document.getElementById('densePaddingToggle');
    const tableEl = document.querySelector('.table-modern');
    if (denseToggle && tableEl) {
        const savedDense = localStorage.getItem('gaji_dense_padding') === '1';
        denseToggle.checked = savedDense;
        tableEl.classList.toggle('is-dense', savedDense);

        denseToggle.addEventListener('change', function() {
            const isDense = denseToggle.checked;
            tableEl.classList.toggle('is-dense', isDense);
            localStorage.setItem('gaji_dense_padding', isDense ? '1' : '0');
        });
    }

});
</script>
@endsection

{{-- CONTENT --}}
@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay">
    <div class="spinner-border spinner-border-custom text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <div>
        <h4 class="mb-1 fw-bold text-dark">
            <i class="ri-money-dollar-circle-line me-2"></i>Data Gaji Karyawan
        </h4>
        <p class="mb-0 text-muted small">Kelola dan monitor gaji karyawan perusahaan</p>
    </div>
    <div class="mt-3 mt-md-0">
        <a href="{{ route('gaji.create') }}" class="btn btn-primary btn-add shadow-sm">
            <i class="ri-add-circle-line me-1"></i>
            Tambah Data Gaji
        </a>
    </div>
</div>

<!-- Gaji Karyawan List -->
<div class="card border-0 shadow-sm">
    <div class="card-header-custom">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">
                    <i class="ri-list-check me-2"></i>Gaji Karyawan
                </h5>
            </div>
            <!-- Search Form -->
            <div class="mt-4">
                <form action="{{ route('gaji.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group" style="max-width: 400px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="ri-search-line text-muted"></i>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control border-start-0 ps-0" 
                            placeholder="Cari nama karyawan atau nominal..." 
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line me-1"></i> Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('gaji.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-close-line me-1"></i> Reset
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div id="selectionToolbar" class="selection-toolbar">
            <span class="selected-text"><span id="selectedCount">0</span> item terpilih</span>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-2" id="clearSelectionBtn" title="Hapus yang dipilih">
                    <i class="ri-delete-bin-line"></i> Hapus
                </button>
            </div>
        </div>

        <div class="card-datatable table-responsive p-0">
            <table class="datatables-users table table-modern table-hover mb-0">
                <thead>
                    <tr>
                        <th class="checkbox-col">
                            <div class="checkbox-wrap">
                                <input type="checkbox" class="custom-check" id="selectAllEmployees">
                            </div>
                        </th>
                        <th>Detail</th>
                        <th>Nama</th>
                        <th>Gaji Pokok</th>
                        <th>Tunj Jabatan</th>
                        <th>Tunj Fungsional</th>
                        <th>Grand Total</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salaries as $salary)
                    <tr class="employee-row"
                        data-nama="{{ $salary->employee->full_name }}"
                        data-gaji-pokok="{{ number_format($salary->gaji_pokok, 0, ',', '.') }}"
                        data-tunj-jabatan="{{ number_format($salary->tunj_jabatan, 0, ',', '.') }}"
                        data-tunj-fungsional="{{ number_format($salary->tunj_fungsional, 0, ',', '.') }}"
                        data-transport="{{ number_format($salary->transport, 0, ',', '.') }}"
                        data-makan="{{ number_format($salary->makan, 0, ',', '.') }}"
                        data-tunj-dynamic="@if($salary->tunj_dynamic)
                            @foreach(json_decode($salary->tunj_dynamic, true) as $key => $val)
                                {{ 'Tunjangan '.($key+1).': Rp '.number_format($val,0,',','.') }} ({{ json_decode($salary->tunj_keterangan, true)[$key] ?? '-' }})<br>
                            @endforeach
                        @else - @endif"
                        data-tunj-kehadiran="{{ number_format($salary->tunj_kehadiran, 0, ',', '.') }}"
                        data-lembur="{{ number_format($salary->lembur, 0, ',', '.') }}"
                        data-pot-sosial="{{ number_format($salary->pot_sosial, 0, ',', '.') }}"
                        data-pot-denda="{{ number_format($salary->pot_denda, 0, ',', '.') }}"
                        data-pot-koperasi="{{ number_format($salary->pot_koperasi, 0, ',', '.') }}"
                        data-pot-pajak="{{ number_format($salary->pot_pajak, 0, ',', '.') }}"
                        data-pot-lain="{{ number_format($salary->pot_lain, 0, ',', '.') }}"
                        data-total="{{ number_format($salary->total, 0, ',', '.') }}"
                        data-grand-total="{{ number_format($salary->grand_total, 0, ',', '.') }}"
                    >
                        <td class="checkbox-col">
                            <div class="checkbox-wrap">
                                <input type="checkbox" class="custom-check employee-checkbox" value="{{ $salary->id }}">
                            </div>
                            <form action="{{ route('gaji.delete', $salary->id) }}" method="POST" class="d-none form-delete-{{ $salary->id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-icon btn-outline-primary btn-detail" title="Lihat Detail">
                                <i class="ri-eye-line"></i>
                            </button>
                        </td>

                        <td>
                            <div class="product-cell">
                                <div class="product-info">
                                    <h6>{{ $salary->employee->full_name }}</h6>
                                </div>
                            </div>
                        </td>

                        <td><span class="badge bg-label-success">Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</span></td>
                        <td>Rp {{ number_format($salary->tunj_jabatan, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($salary->tunj_fungsional, 0, ',', '.') }}</td>
                        <td><strong class="text-primary">Rp {{ number_format($salary->grand_total, 0, ',', '.') }}</strong></td>

                        <td>
                            <div class="d-flex justify-content-center">
                                <div class="dropdown">
                                    <button class="action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end tagihan-action-menu">
                                        <li>
                                            <a class="dropdown-item btn-share" href="#" data-url="{{ route('gaji.share.public', $salary->id) }}">
                                                <i class="ri-share-forward-line"></i> Salin Link
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('gaji.print', $salary->id) }}" target="_blank">
                                                <i class="ri-printer-line"></i> Cetak PDF
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('gaji.edit', $salary->id) }}">
                                                <i class="ri-edit-2-line"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger btn-delete-single">
                                                <i class="ri-delete-bin-line"></i> Hapus
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="pagination-wrapper mt-4 p-3 d-flex justify-content-between align-items-center">
              <label class="dense-toggle-wrap mb-0">
                <input type="checkbox" id="densePaddingToggle">
                <span class="small">Dense padding</span>
              </label>
              <div>
                @if($salaries->lastPage() > 1)
                    {{ $salaries->appends(request()->query())->onEachSide(1)->links('pagination.mui') }}
                @else
                    <nav aria-label="Page navigation">
                        <ul class="pagination mui-pagination mb-0 justify-content-end">
                            <li class="page-item disabled">
                                <span class="page-link page-nav-icon">&laquo;</span>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link page-nav-icon">&lsaquo;</span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link page-nav-icon">&rsaquo;</span>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link page-nav-icon">&raquo;</span>
                            </li>
                        </ul>
                    </nav>
                @endif
              </div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-4">
                <h5 class="modal-title">
                    <i class="ri-information-line me-2"></i>Detail Gaji Karyawan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Custom content will be inserted via JavaScript -->
            </div>
 
        </div>
    </div>
</div>
@endsection
