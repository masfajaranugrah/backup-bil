@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

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

/* ========== CARD ========== */
.card {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--card-shadow);
  background: white;
  transition: var(--transition);
  overflow: visible;
}

.card:hover {
  box-shadow: var(--card-hover-shadow);
}

/* ========== STATS CARD ========== */
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

/* ========== HEADER SECTION ========== */
.card-header-custom {
  background: #ffffff !important;
  border-bottom: 1px solid var(--gray-border);
  padding: 1.5rem;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
  color: #18181b;
}

.card-header-custom h4 {
  color: #18181b !important;
  font-size: 1.5rem;
}

.card-header-custom p {
  color: #71717a !important;
}

.card-header-custom i {
  color: #18181b !important;
}

/* ========== TABLE STYLES ========== */
.table-modern {
  margin-bottom: 0;
  border-radius: 8px;
  overflow: visible;
  border-collapse: separate;
  border-spacing: 0;
}

.table-modern thead th {
  background: #f8fafc;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.5px;
  color: #18181b;
  padding: 1rem;
  border: none;
  white-space: nowrap;
}

.table-modern tbody tr {
  transition: var(--transition);
  border-bottom: 1px solid #e4e4e7;
}

.table-modern tbody tr:hover {
  background-color: #f4f4f5 !important;
}

.table-modern tbody td {
  padding: 1rem;
  vertical-align: middle;
  border-bottom: 1px solid #e4e4e7;
  color: #18181b;
}

.table-modern thead th:first-child,
.table-modern tbody td:first-child {
  width: 56px;
  text-align: center;
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
.btn.btn-primary,
.btn-add {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
  box-shadow: none !important;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: 600;
}

.btn-primary:hover,
.btn.btn-primary:hover,
.btn-add:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
  transform: translateY(-2px) !important;
}

.btn-add i {
  margin-right: 8px;
  color: #ffffff !important;
}

.account-search-form {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  align-items: center;
  justify-content: space-between;
  padding: 1rem;
  border: 1px solid #e4e4e7;
  border-radius: 12px;
  background: #fafafa;
}

.account-search-input-wrap {
  position: relative;
  flex: 1 1 280px;
}

.account-search-input-wrap i {
  position: absolute;
  left: 0.875rem;
  top: 50%;
  transform: translateY(-50%);
  color: #71717a;
  font-size: 1rem;
}

.account-search-input {
  width: 100%;
  min-height: 42px;
  border: 1px solid #d4d4d8;
  border-radius: 10px;
  padding: 0.625rem 0.875rem 0.625rem 2.5rem;
  font-size: 0.875rem;
  color: #18181b;
  background: #ffffff;
}

.account-search-input:focus {
  outline: none;
  border-color: #18181b;
  box-shadow: 0 0 0 3px rgba(24, 24, 27, 0.08);
}

.account-search-actions {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.btn-danger,
.btn.btn-danger {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn-danger:hover,
.btn.btn-danger:hover {
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

.btn-outline-primary,
.btn-outline-danger {
  background: transparent !important;
  border: 1px solid #18181b !important;
  color: #18181b !important;
}

.btn-outline-primary:hover,
.btn-outline-danger:hover {
  background: #18181b !important;
  color: #fafafa !important;
}

/* ========== BADGES ========== */
.badge {
  border-radius: 9999px !important;
  font-weight: 500 !important;
  padding: 0.35rem 0.75rem !important;
}

.badge.bg-label-primary {
  background: #18181b !important;
  color: #fafafa !important;
}

.badge-status {
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 6px;
  font-size: 0.75rem;
}

.action-buttons {
  gap: 12px;
}

.icon-wrapper {
  width: 48px;
  height: 48px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  font-size: 24px;
  background: #18181b;
  color: #fafafa;
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

/* ========== ACTION MENU (TAGIHAN STYLE) ========== */
.action-btn {
  background: transparent;
  border: 0;
  color: #94a3b8;
  width: 32px;
  height: 32px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
}

.action-btn:hover,
.action-btn[aria-expanded="true"] {
  background: #f1f5f9;
  color: #0f172a;
}

.tagihan-action-menu {
  position: absolute !important;
  top: 50% !important;
  right: calc(100% + 14px) !important;
  left: auto !important;
  transform: translateY(-50%) !important;
  width: 230px;
  min-width: 230px;
  padding: 10px;
  border: 1px solid #d7e2ee !important;
  border-radius: 22px !important;
  background: linear-gradient(110deg, #fff4f2 0%, #edf7ff 100%) !important;
  box-shadow: 0 18px 36px rgba(15, 23, 42, 0.14) !important;
  z-index: 20000;
}

.tagihan-action-menu.fixed-action-menu {
  position: fixed !important;
  right: auto !important;
  bottom: auto !important;
  transform: none !important;
}

.tagihan-action-menu::after {
  content: '';
  display: none;
  position: absolute;
  right: var(--action-menu-arrow-right, -9px);
  top: var(--action-menu-arrow-top, 50%);
  width: 18px;
  height: 18px;
  border-top: 1px solid #d7e2ee;
  border-right: 1px solid #d7e2ee;
  background: #f2f8ff;
  transform: translateY(-50%) rotate(45deg);
}

.tagihan-action-menu.show::after {
  display: block;
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
}

.tagihan-action-menu .dropdown-item:hover {
  background: rgba(255, 255, 255, 0.78);
  color: #1e293b;
}

.tagihan-action-menu .dropdown-item i {
  color: inherit;
}

.tagihan-action-menu .dropdown-item.text-danger,
.tagihan-action-menu .dropdown-item.text-danger:hover {
  color: #ff3b30 !important;
}

.datatables-users .dropdown {
  position: relative;
}

.card-datatable,
.table-responsive {
  overflow: visible !important;
}

.table-modern tbody td:last-child,
.table-modern thead th:last-child {
  overflow: visible !important;
  position: relative;
}

/* ========== MODAL STYLES ========== */
.modal-content {
  border-radius: 16px;
  border: none;
  box-shadow: 0 8px 32px rgba(0,0,0,0.15);
}

.modal-header {
  background: #18181b;
  border-radius: 16px 16px 0 0;
  padding: 1.5rem;
  border-bottom: none;
}

.modal-title {
  font-weight: 600;
  font-size: 1.125rem;
  color: #ffffff;
}

.modal-body {
  padding: 2rem;
}

.modal-body p {
  margin-bottom: 1rem;
  line-height: 1.6;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f0f0f0;
}

.modal-body p:last-child {
  border-bottom: none;
}

.modal-body strong {
  color: #18181b;
  font-weight: 600;
  display: inline-block;
  min-width: 100px;
}

.modal-footer {
  padding: 1.5rem;
  border-top: 1px solid #f0f0f0;
  background: #fafafa;
}

.btn-close-white {
  filter: brightness(0) invert(1);
}

/* ========== USER AVATAR ========== */
.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #18181b;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #fafafa;
  font-weight: 600;
  font-size: 1rem;
  margin-right: 12px;
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

.custom-check {
  appearance: none;
  width: 18px;
  height: 18px;
  border: 1px solid #cbd5e1;
  border-radius: 4px;
  background: #fff;
  cursor: pointer;
  position: relative;
  display: block;
  margin: 0 auto;
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

.selection-toolbar {
  display: none;
  align-items: center;
  justify-content: space-between;
  background: #eaf1fb;
  border: 1px solid #d7e2ee;
  color: #0f172a;
  padding: 0.8rem 1.1rem;
}

.selection-toolbar .selected-text {
  font-size: 1.9rem;
  font-weight: 700;
  color: #0f172a;
}

.selection-toolbar .toolbar-delete-btn {
  border: 0;
  background: transparent;
  color: #64748b;
  width: 34px;
  height: 34px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.selection-toolbar .toolbar-delete-btn:hover {
  background: rgba(100, 116, 139, 0.12);
}

.selection-toolbar.active {
  display: flex;
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
  min-width: 32px;
  height: 32px;
  border-radius: 999px !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}

.pagination-wrapper .mui-pagination {
  align-items: center;
  gap: 0.85rem;
  display: flex;
}

.pagination-wrapper .mui-pagination .page-link {
  width: 32px;
  min-width: 32px;
  height: 32px;
  margin: 0 !important;
  padding: 0 !important;
  border: 0 !important;
  border-radius: 50% !important;
  background: transparent !important;
  color: #1f2937 !important;
  box-shadow: none !important;
  font-size: 1rem;
  line-height: 1;
  font-weight: 700;
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

/* ========== ANIMATIONS ========== */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.card {
  animation: fadeIn 0.3s ease-out;
}

@media (max-width: 768px) {
  .tagihan-action-menu {
    top: calc(100% + 10px) !important;
    right: 0 !important;
    left: auto !important;
    transform: none !important;
    width: 210px;
    min-width: 210px;
  }

  .tagihan-action-menu::after {
    display: none;
  }
}
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
@vite(['resources/assets/js/extended-ui-perfect-scrollbar.js'])
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Helper function untuk loading overlay
    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }
    
    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    // Inisialisasi DataTable dengan styling modern
    // Inisialisasi DataTable dengan styling modern (Tanpa Pagination internal karena pakai backend pagination)
    const dtUserTable = $('.datatables-users').DataTable({
        paging: false, // Matikan paging DataTables
        searching: false, // Matikan searching DataTables
        ordering: true,
        info: false, // Matikan info DataTables
        responsive: true,
        dom: 'rt', // Hanya tampilkan table
        columnDefs: [
            { orderable: false, targets: [0, -1] }
        ],
        language: {
            zeroRecords: "Tidak ada data yang sesuai"
        }
    });

    // Event Detail User
    $(document).on('click', '.btn-detail', function(e) {
        e.stopPropagation();
        const tr = $(this).closest('tr');
        const row = dtUserTable.row(tr).data();
        if (!row) return;

        const html = `
            <div class="row g-3">
                <div class="col-12 text-center mb-3">
                    <div class="user-avatar mx-auto" style="width: 80px; height: 80px; font-size: 2rem;">
                        ${row[1].charAt(0).toUpperCase()}
                    </div>
                </div>
                <div class="col-12">
                    <p><strong>Nama:</strong> ${row[1]}</p>
                </div>
                <div class="col-12">
                    <p><strong>Email:</strong> ${row[2]}</p>
                </div>
                <div class="col-12">
                    <p><strong>Role:</strong> <span class="badge bg-label-primary">${row[3]}</span></p>
                </div>
            </div>
        `;
        $('#detailModal .modal-body').html(html);
        $('#detailModal').modal('show');
    });

     // Event DELETE dengan konfirmasi modern - HANYA 2 BUTTON
$(document).on('click', '.btn-delete', function(e) {
    e.preventDefault();
    e.stopPropagation();
    const form = $(this).closest('form');

    Swal.fire({
        title: 'Konfirmasi Penghapusan',
        text: 'Yakin ingin menghapus data karyawan ini? Data tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        showDenyButton: false,  // Pastikan deny button tidak muncul
        confirmButtonText: '<i class="ri-delete-bin-line me-2"></i>Ya, Hapus!',
        cancelButtonText: '<i class="ri-close-line me-2"></i>Batal',
        confirmButtonColor: '#f5365c',
        cancelButtonColor: '#8898aa',
        reverseButtons: true,  // Cancel di kiri, Confirm di kanan
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary'
        },
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then((result) => {
        if (result.isConfirmed) {
            const btn = $(form).find('.btn-delete');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');
            showLoading();
            
            setTimeout(() => {
                hideLoading();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Dihapus!',
                    text: 'Data karyawan berhasil dihapus dari sistem.',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    allowOutsideClick: false
                }).then(() => {
                    form.submit();
                });
            }, 1000);
        }
    });
});

function updateSelectionState() {
    const $all = $('.row-checkbox');
    const $checked = $('.row-checkbox:checked');
    const count = $checked.length;
    $('#selectAllUsers').prop('checked', $all.length > 0 && count === $all.length);
    $('#selectAllUsers').prop('indeterminate', count > 0 && count < $all.length);
    $('#selectedCount').text(count + ' dipilih');
    $('#selectionToolbar').toggleClass('active', count > 0);
}

$('#selectAllUsers').on('change', function() {
    $('.row-checkbox').prop('checked', this.checked);
    updateSelectionState();
});

$(document).on('change', '.row-checkbox', updateSelectionState);

$('#btnBulkDelete').on('click', async function() {
    const checked = $('.row-checkbox:checked').toArray();
    if (!checked.length) return;

    const result = await Swal.fire({
        title: 'Konfirmasi Penghapusan',
        text: `Hapus ${checked.length} akun terpilih?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    });

    if (!result.isConfirmed) return;
    showLoading();

    for (const cb of checked) {
        const $row = $(cb).closest('tr');
        const $form = $row.find('form.delete-form');
        if (!$form.length) continue;
        try {
          await $.post($form.attr('action'), {
              _token: $('meta[name="csrf-token"]').attr('content'),
              _method: 'DELETE'
          });
        } catch (e) {}
    }

    hideLoading();
    location.reload();
});

const denseToggle = document.getElementById('densePaddingToggle');
const tableEl = document.querySelector('.table-modern');
if (denseToggle && tableEl) {
    const savedDense = localStorage.getItem('team_dense_padding') === '1';
    denseToggle.checked = savedDense;
    tableEl.classList.toggle('is-dense', savedDense);

    denseToggle.addEventListener('change', function() {
        const isDense = denseToggle.checked;
        tableEl.classList.toggle('is-dense', isDense);
        localStorage.setItem('team_dense_padding', isDense ? '1' : '0');
    });
}

document.querySelectorAll('.datatables-users .dropdown').forEach(dropdown => {
    const button = dropdown.querySelector('[data-bs-toggle="dropdown"]');
    const menu = dropdown.querySelector('.tagihan-action-menu');
    if (!button || !menu) return;

    dropdown.addEventListener('shown.bs.dropdown', () => {
        const buttonRect = button.getBoundingClientRect();
        const menuWidth = menu.offsetWidth || 230;
        const menuHeight = menu.offsetHeight || 250;
        const gap = 12;
        const padding = window.innerWidth < 768 ? 8 : 14;
        let top = buttonRect.top + (buttonRect.height / 2) - (menuHeight / 2);
        let left = buttonRect.left - menuWidth - gap;

        if (top + menuHeight > window.innerHeight - padding) {
            top = window.innerHeight - menuHeight - padding;
        }
        if (top < padding) {
            top = padding;
        }
        if (left < padding) {
            left = padding;
        }

        const arrowTop = buttonRect.top + (buttonRect.height / 2) - top;
        const arrowRight = Math.max(-9, left + menuWidth - buttonRect.left - 9);
        menu.classList.add('fixed-action-menu');
        menu.style.setProperty('top', `${top}px`, 'important');
        menu.style.setProperty('left', `${left}px`, 'important');
        menu.style.setProperty('right', 'auto', 'important');
        menu.style.setProperty('bottom', 'auto', 'important');
        menu.style.setProperty('transform', 'none', 'important');
        menu.style.setProperty('--action-menu-arrow-top', `${arrowTop}px`);
        menu.style.setProperty('--action-menu-arrow-right', `${arrowRight}px`);
    });

    dropdown.addEventListener('hidden.bs.dropdown', () => {
        menu.classList.remove('fixed-action-menu');
        menu.style.removeProperty('top');
        menu.style.removeProperty('left');
        menu.style.removeProperty('right');
        menu.style.removeProperty('bottom');
        menu.style.removeProperty('transform');
        menu.style.removeProperty('--action-menu-arrow-top');
        menu.style.removeProperty('--action-menu-arrow-right');
    });
});

});
</script>
@endsection

@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay">
    <div class="spinner-border spinner-border-custom text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Users List Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header-custom">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="ri-group-line me-2"></i>Data Users
                </h4>
                <p class="mb-0 opacity-75 small">Kelola dan monitor data pengguna sistem</p>
            </div>
            <div class="d-flex action-buttons mt-3 mt-md-0">
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-add">
                    <i class="ri-user-add-line"></i>
                    Tambah User Baru
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="card-datatable table-responsive p-3">
            <div class="selection-toolbar rounded-3 mb-3" id="selectionToolbar">
                <span class="selected-text" id="selectedCount">0 dipilih</span>
                <button type="button" class="toolbar-delete-btn" id="btnBulkDelete" title="Hapus Terpilih">
                    <i class="ri-delete-bin-line fs-5"></i>
                </button>
            </div>
            <form method="GET" action="{{ route('users.index') }}" class="account-search-form mb-3">
                <div class="account-search-input-wrap">
                    <i class="ri-search-line"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        class="account-search-input"
                        placeholder="Cari nama, email, atau role akun..."
                        autocomplete="off">
                </div>
                <div class="account-search-actions">
                    @if(!empty($search))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-close-line"></i> Reset
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-line"></i> Cari
                    </button>
                </div>
            </form>
            <table class="datatables-users table table-modern table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" class="custom-check" id="selectAllUsers"></th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                    
                        
                        <td>
                            <input type="checkbox" class="custom-check row-checkbox" value="{{ $user->id }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                                          <div>
                                    <span class="fw-semibold">{{ $user->name }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <i class="ri-mail-line me-1 text-muted"></i>
                            {{ $user->email }}
                        </td>
                        
                        <td>
                            <span class="badge bg-label-primary">
                                <i class="ri-shield-user-line me-1"></i>{{ ucfirst($user->role) }}
                            </span>
                        </td>
                        
                        <td>
                            <div class="d-flex justify-content-center">
                                <div class="dropdown">
                                    <button class="action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end tagihan-action-menu">
                                        <li>
                                            <a class="dropdown-item btn-detail" href="javascript:void(0);">
                                                <i class="ri-eye-line"></i> Detail
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                <i class="ri-edit-2-line"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-form m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger btn-delete w-100 border-0 bg-transparent text-start">
                                                    <i class="ri-delete-bin-line"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="ri-search-eye-line d-block mb-2" style="font-size: 2rem;"></i>
                            Tidak ada akun yang sesuai dengan pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top: 1px solid #f1f5f9; background: #ffffff;">
          <div class="d-flex align-items-center gap-4">
            <label class="dense-toggle-wrap mb-0">
              <input type="checkbox" id="densePaddingToggle">
              <span>Dense padding</span>
            </label>
          </div>
          <div class="pagination-wrapper border-top-0 p-0 bg-transparent m-0">
            @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->total() > 0)
              {{ $users->appends(request()->query())->onEachSide(1)->links('pagination.mui') }}
            @endif
          </div>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ri-information-line me-2"></i>Detail User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <!-- Content will be inserted via JavaScript -->
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
