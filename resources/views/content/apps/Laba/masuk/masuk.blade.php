@extends('layouts/layoutMaster')

@section('title', 'Data Laba Masuk')

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
@endsection

{{-- PAGE STYLE --}}
@section('page-style')
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
  background: #ffffff !important;
  border-bottom: 1px solid var(--gray-border);
  padding: 1.5rem;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
}

.card-header-custom h4 {
  color: #18181b !important;
  font-size: 1.5rem;
}

.card-header-custom p {
  color: #71717a !important;
}

.card-header-custom i:not(.btn-add i) {
  color: #18181b !important;
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
}

.btn-add {
  padding: 10px 24px !important;
  border-radius: 8px !important;
  font-weight: 600 !important;
  box-shadow: 0 4px 12px rgba(24, 24, 27, 0.25) !important;
  transition: all 0.3s ease !important;
}

.btn-add:hover {
  transform: translateY(-2px) !important;
  box-shadow: 0 6px 16px rgba(24, 24, 27, 0.35) !important;
}

.btn-secondary,
.btn.btn-secondary {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn-secondary:hover,
.btn.btn-secondary:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn-danger,
.btn.btn-danger {
  background: #dc2626 !important;
  background-color: #dc2626 !important;
  color: #fafafa !important;
  border: 1px solid #dc2626 !important;
}

.btn-danger:hover,
.btn.btn-danger:hover {
  background: #b91c1c !important;
  background-color: #b91c1c !important;
  border-color: #b91c1c !important;
  color: #fafafa !important;
}

/* Outline Buttons */
.btn-outline-primary,
.btn.btn-outline-primary {
  background: transparent !important;
  background-color: transparent !important;
  border: 1px solid #e4e4e7 !important;
  color: #18181b !important;
}

.btn-outline-primary:hover,
.btn.btn-outline-primary:hover {
  background: #18181b !important;
  background-color: #18181b !important;
  border-color: #18181b !important;
  color: #fafafa !important;
}

.btn-outline-danger,
.btn.btn-outline-danger {
  background: transparent !important;
  background-color: transparent !important;
  border: 1px solid #e4e4e7 !important;
  color: #dc2626 !important;
}

.btn-outline-danger:hover,
.btn.btn-outline-danger:hover {
  background: #dc2626 !important;
  background-color: #dc2626 !important;
  border-color: #dc2626 !important;
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

/* ========== BANK SUMMARY CARDS ========== */
.bank-summary-card {
  background: #ffffff;
  border: 1px solid var(--gray-border);
  border-radius: 10px;
  padding: 1.25rem;
  transition: var(--transition);
}

.bank-summary-card:hover {
  border-color: #18181b;
  box-shadow: 0 4px 12px rgba(24, 24, 27, 0.1);
}

.bank-summary-card .bank-name {
  color: #71717a;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.bank-summary-card .bank-icon {
  color: #18181b;
}

.bank-summary-card .bank-total {
  color: #18181b;
  font-size: 1.25rem;
  font-weight: 700;
}

/* ========== TABLE STYLES ========== */
.table-modern {
  margin-bottom: 0;
  border-radius: 8px;
  overflow: hidden;
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
  text-align: center;
  width: 60px;
}

.table-modern thead th:first-child {
  cursor: default !important;
  background-image: none !important;
}

.table-modern thead th:first-child::before,
.table-modern thead th:first-child::after,
.table-modern.dataTable thead > tr > th:first-child::before,
.table-modern.dataTable thead > tr > th:first-child::after,
.table-modern.dataTable thead > tr > td:first-child::before,
.table-modern.dataTable thead > tr > td:first-child::after {
  content: none !important;
  display: none !important;
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

.bg-label-primary {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
}

/* ========== PAGINATION STYLES ========== */
.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-top: 1px solid #f0f0f0;
  background: #fafafa;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.pagination-info {
  color: #71717a;
  font-size: 0.875rem;
  font-weight: 500;
}

.pagination {
  margin: 0;
  gap: 0.5rem;
  justify-content: flex-end;
}

.pagination .page-item .page-link {
  border-radius: 50% !important;
  width: 32px !important;
  min-width: 32px !important;
  max-width: 32px !important;
  height: 32px !important;
  flex: 0 0 32px;
  padding: 0 !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e4e4e7;
  color: #18181b;
  font-weight: 600;
  background-color: #fff;
  margin: 0 2px;
  transition: all 0.3s ease;
  line-height: 1;
  box-sizing: border-box;
}

.pagination-wrapper .mui-pagination .page-link {
  width: 32px !important;
  min-width: 32px !important;
  max-width: 32px !important;
  height: 32px !important;
  flex: 0 0 32px;
  padding: 0 !important;
  border-radius: 50% !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.pagination-wrapper nav .pagination.mui-pagination .page-item,
.pagination-wrapper nav .pagination.mui-pagination .page-link {
  width: 32px !important;
  min-width: 32px !important;
  max-width: 32px !important;
  height: 32px !important;
  flex: 0 0 32px !important;
  padding: 0 !important;
  border-radius: 50% !important;
  line-height: 1 !important;
  box-sizing: border-box;
}

.dense-toggle-wrap {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  color: #334155;
}

.dense-toggle-wrap input[type="checkbox"] {
  width: 22px;
  height: 22px;
  accent-color: #18181b;
}

.table-modern.is-dense th,
.table-modern.is-dense td {
  padding: 0.55rem 0.85rem !important;
}

.income-check {
  appearance: none;
  width: 22px;
  height: 22px;
  border: 1.5px solid #cbd5e1;
  border-radius: 5px;
  background: #ffffff;
  cursor: pointer;
  position: relative;
  display: block;
  margin: 0 auto;
  transition: all 0.2s;
}

.income-check:hover {
  border-color: #18181b;
}

.income-check:checked {
  background: #18181b;
  border-color: #18181b;
}

.income-check:checked::after {
  content: '';
  position: absolute;
  top: 3px;
  left: 7px;
  width: 6px;
  height: 11px;
  border: solid #ffffff;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.selection-toolbar {
  display: none;
  align-items: center;
  justify-content: space-between;
  margin: 0 0 1rem;
  padding: 0.9rem 1.2rem;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  background: #f8fafc;
}

.selection-toolbar.active {
  display: flex;
}

.selection-toolbar .selected-text {
  color: #18181b;
  font-size: 1rem;
  font-weight: 700;
}

.selection-toolbar .toolbar-delete-btn {
  border: 0;
  background: #fee2e2;
  color: #dc2626;
  width: 36px;
  height: 36px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
}

.selection-toolbar .toolbar-delete-btn:hover {
  background: #fecaca;
  transform: translateY(-1px);
}

.income-snackbar {
  position: fixed;
  right: 32px;
  bottom: 32px;
  z-index: 12000;
  min-width: 420px;
  max-width: min(720px, calc(100vw - 48px));
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.1rem 1.4rem;
  border-radius: 22px;
  background: #111827;
  color: #ffffff;
  box-shadow: 0 22px 50px rgba(15, 23, 42, 0.28);
  transform: translateY(18px);
  opacity: 0;
  pointer-events: none;
  transition: all 0.25s ease;
}

.income-snackbar.show {
  transform: translateY(0);
  opacity: 1;
}

.income-snackbar i {
  color: #86efac;
  font-size: 1.35rem;
}

.income-snackbar span {
  color: #ffffff;
  font-size: 1.05rem;
  font-weight: 700;
  line-height: 1.3;
}

@media (max-width: 576px) {
  .income-snackbar {
    right: 16px;
    bottom: 16px;
    min-width: 0;
    width: calc(100vw - 32px);
    border-radius: 18px;
  }
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
.pagination-wrapper .pagination + div,
.pagination-wrapper nav + div,
.pagination-wrapper div:has(> nav) > p,
.pagination-wrapper > div > nav ~ *:not(.pagination),
.pagination-wrapper > div:last-child p {
  display: none !important;
}

/* Alternative: Hide any 'Showing X to Y of Z results' text */
.pagination-wrapper div:last-child > p,
.pagination-wrapper > div > .text-sm,
nav[role="navigation"] > div:first-child,
nav[role="navigation"] > div > p {
  display: none !important;
}

/* ========== EMPTY STATE ========== */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-state-icon {
  font-size: 4rem;
  color: #e4e4e7;
  margin-bottom: 1rem;
}

.empty-state h5 {
  color: #18181b;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.empty-state p {
  color: #71717a;
  margin-bottom: 1.5rem;
}

/* ========== LOADING OVERLAY ========== */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
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

/* ========== MODAL STYLING ========== */
.modal-backdrop {
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  background-color: rgba(0, 0, 0, 0.5) !important;
}

.modal-backdrop.show {
  opacity: 1 !important;
}

.modal-content {
  border-radius: 16px;
  border: none;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.modal-header {
  background: #18181b !important;
  border-radius: 16px 16px 0 0;
  color: white;
  padding: 1.75rem 2rem;
  border: none;
}

.modal-title {
  font-weight: 600;
  font-size: 1.125rem;
  color: #fafafa !important;
}

.modal-header .btn-close {
  filter: invert(1);
  opacity: 1;
}

.modal-body {
  padding: 1.5rem;
  max-height: 70vh;
  overflow-y: auto;
}

.modal-footer {
  padding: 1.5rem 2rem;
  border-top: 1px solid #e4e4e7;
  background: #fafafa;
  border-radius: 0 0 16px 16px;
}

.income-icon {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: #18181b !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 2rem;
  margin-bottom: 1rem;
  box-shadow: 0 4px 16px rgba(24, 24, 27, 0.4);
  border: 4px solid white;
}

.detail-section {
  background: white;
  border: 1px solid #e4e4e7;
  border-radius: 10px;
  padding: 1.25rem;
  margin-bottom: 1.25rem;
  transition: all 0.2s ease;
}

.detail-section:hover {
  border-color: #18181b;
  box-shadow: 0 2px 8px rgba(24, 24, 27, 0.1);
}

.detail-section h6 {
  color: #18181b !important;
  font-weight: 700;
  margin-bottom: 1rem;
  font-size: 0.85rem;
  text-transform: uppercase;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #18181b;
  display: flex;
  align-items: center;
}

.detail-section h6 i {
  margin-right: 0.5rem;
  font-size: 1.1rem;
  color: #18181b !important;
}

.detail-item {
  display: flex;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f0f0f0;
}

.detail-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.detail-label {
  color: #71717a;
  font-weight: 600;
  min-width: 150px;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
}

.detail-label i {
  margin-right: 0.5rem;
  color: #18181b !important;
  font-size: 1rem;
}

.detail-value {
  color: #18181b;
  font-size: 0.875rem;
  flex: 1;
  word-break: break-word;
}

.income-header-info {
  text-align: center;
  padding: 1.5rem;
  background: #fafafa;
  border-radius: 10px;
  margin-bottom: 1.5rem;
  border: 1px solid #e4e4e7;
}

.income-amount {
  font-size: 1.75rem;
  font-weight: 700;
  color: #18181b;
  margin-bottom: 0.5rem;
}

.income-category {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  background: #18181b !important;
  color: white;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.875rem;
  box-shadow: 0 2px 8px rgba(24, 24, 27, 0.3);
}

/* ========== TEXT COLORS ========== */
.text-primary {
  color: #18181b !important;
}

.text-muted {
  color: #71717a !important;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
  .card-header-custom {
    padding: 1rem 1.25rem;
  }

  .pagination-wrapper {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }

  .btn-add {
    width: 100%;
  }

  .detail-label {
    min-width: 120px;
    font-size: 0.8rem;
  }

  .detail-value {
    font-size: 0.8rem;
  }
}

@media (max-width: 576px) {
  .table-modern {
    font-size: 0.85rem;
  }

  .table-modern thead th,
  .table-modern tbody td {
    padding: 0.75rem 0.5rem;
  }

  .empty-state {
    padding: 3rem 1rem;
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

.card .pagination-wrapper .pagination.mui-pagination {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: flex-end !important;
  gap: 0.75rem !important;
  margin: 0 !important;
}

.card .pagination-wrapper .pagination.mui-pagination .page-item {
  width: 38px !important;
  min-width: 38px !important;
  max-width: 38px !important;
  height: 38px !important;
  min-height: 38px !important;
  max-height: 38px !important;
  flex: 0 0 38px !important;
  border-radius: 50% !important;
  overflow: hidden !important;
}

.card .pagination-wrapper .pagination.mui-pagination .page-link,
.card .pagination-wrapper .pagination.mui-pagination .page-link.page-nav-icon {
  width: 38px !important;
  min-width: 38px !important;
  max-width: 38px !important;
  height: 38px !important;
  min-height: 38px !important;
  max-height: 38px !important;
  aspect-ratio: 1 / 1 !important;
  flex: 0 0 38px !important;
  padding: 0 !important;
  margin: 0 !important;
  border-radius: 50% !important;
  display: inline-grid !important;
  place-items: center !important;
  line-height: 1 !important;
  white-space: nowrap !important;
  box-sizing: border-box !important;
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
    function showIncomeSnackbar(message) {
        if (!message) return;
        const existing = document.querySelector('.income-snackbar');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = 'income-snackbar';
        toast.innerHTML = `<i class="ri-checkbox-circle-line"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        toast.offsetHeight;
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3200);
    }

    @if(session('success'))
      showIncomeSnackbar(@json(session('success')));
    @endif

    const pendingIncomeSnackbar = localStorage.getItem('income_snackbar_success');
    if (pendingIncomeSnackbar) {
        localStorage.removeItem('income_snackbar_success');
        showIncomeSnackbar(pendingIncomeSnackbar);
    }

    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }

    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    // ? HANYA INISIALISASI DATATABLES JIKA ADA DATA (untuk sorting only, no paging/search)
    @if($incomes->count() > 0)
        const dtIncomeTable = $('.datatables-income').DataTable({
            paging: false,
            searching: false,
            ordering: true,
            info: false,
            responsive: false,
            dom: 'rt',
            columnDefs: [
              { orderable: false, targets: [0, -1] }
            ],
            language: {
                emptyTable: "Tidak ada data laba masuk tersedia",
                zeroRecords: "Tidak ditemukan data yang sesuai"
            }
        });
    @endif

    const denseToggleIncome = document.getElementById('densePaddingToggleIncome');
    const incomeTableEl = document.querySelector('.datatables-income.table-modern');
    if (denseToggleIncome && incomeTableEl) {
        const savedDense = localStorage.getItem('income_dense_padding') === '1';
        denseToggleIncome.checked = savedDense;
        incomeTableEl.classList.toggle('is-dense', savedDense);

        denseToggleIncome.addEventListener('change', function() {
            const isDense = denseToggleIncome.checked;
            incomeTableEl.classList.toggle('is-dense', isDense);
            localStorage.setItem('income_dense_padding', isDense ? '1' : '0');
        });
    }

    function updateIncomeSelectionState() {
        const $all = $('.income-row-checkbox');
        const $checked = $('.income-row-checkbox:checked');
        const count = $checked.length;
        $('#selectAllIncomes').prop('checked', $all.length > 0 && count === $all.length);
        $('#incomeSelectedCount').text(`${count} dipilih`);
        $('#incomeSelectionToolbar').toggleClass('active', count > 0);
    }

    $('#selectAllIncomes').on('change', function() {
        $('.income-row-checkbox').prop('checked', this.checked);
        updateIncomeSelectionState();
    });

    $(document).on('change', '.income-row-checkbox', updateIncomeSelectionState);

    $('#btnBulkDeleteIncome').on('click', async function() {
        const checked = $('.income-row-checkbox:checked').toArray();
        if (!checked.length) return;

        const result = await Swal.fire({
            title: 'Hapus Data?',
            text: `Yakin ingin menghapus ${checked.length} data laba masuk terpilih?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        });

        if (!result.isConfirmed) return;

        showLoading();
        let successCount = 0;
        for (const cb of checked) {
            const id = $(cb).val();
            const source = $(cb).data('source');
            const deleteUrl = source === 'income'
                ? `{{ url('/dashboard/admin/incomes') }}/${id}`
                : `{{ url('/dashboard/admin/tagihan/tagihan-lunas') }}/${id}`;
            try {
                const response = await fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
                if (response.ok) successCount++;
            } catch (e) {}
        }

        hideLoading();
        if (successCount > 0) {
            localStorage.setItem('income_snackbar_success', `${successCount} data laba masuk berhasil dihapus.`);
            window.location.reload();
        } else {
            Swal.fire('Gagal', 'Data belum berhasil dihapus. Coba ulangi lagi.', 'error');
        }
    });

    // Event Detail - gunakan event delegation
    $(document).on('click', '.btn-detail', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const tr = $(this).closest('tr');
        const kode = tr.data('kode') || '-';
        const kategori = tr.data('kategori') || '-';
        const jumlah = tr.data('jumlah') || 0;
        const keterangan = tr.data('keterangan') || '-';
        const tanggalMasuk = tr.data('tanggal-masuk') || '-';
        const jamMasuk = tr.data('jam-masuk') || '-';

        const html = `
            <div class="income-header-info">
                <div class="income-icon mx-auto">
                    <i class="ri-money-dollar-circle-line"></i>
                </div>
                <div class="income-amount">Rp ${parseInt(jumlah).toLocaleString('id-ID')}</div>
                <div class="income-category">
                    <i class="ri-bookmark-line me-2"></i>${kategori}
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-information-line"></i>Informasi Pemasukan</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-barcode-line"></i>Kode Transaksi
                    </span>
                    <span class="detail-value"><strong>${kode}</strong></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-bookmark-3-line"></i>Kategori
                    </span>
                    <span class="detail-value">
                        <span class="badge bg-label-primary">${kategori}</span>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-money-dollar-box-line"></i>Jumlah Laba
                    </span>
                    <span class="detail-value">
                        <strong style="color: #18181b; font-size: 1.1rem;">Rp ${parseInt(jumlah).toLocaleString('id-ID')}</strong>
                    </span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-calendar-event-line"></i>Waktu Pemasukan</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-calendar-check-line"></i>Tanggal Masuk
                    </span>
                    <span class="detail-value">${tanggalMasuk}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-time-line"></i>Jam Masuk
                    </span>
                    <span class="detail-value">${jamMasuk}</span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-file-text-line"></i>Keterangan</h6>
                <div class="detail-item">
                    <span class="detail-value">${keterangan}</span>
                </div>
            </div>
        `;

        $('#detailModal .modal-body').html(html);
        $('#detailModal').modal('show');
    });

    // Event DELETE - gunakan event delegation
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Yakin ingin menghapus data laba masuk ini? Data tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: false,
            showCloseButton: false,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f5365c',
            cancelButtonColor: '#8898aa',
            reverseButtons: false,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = $(form).find('.btn-delete');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');
                showLoading();

                setTimeout(() => {
                    hideLoading();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data laba masuk berhasil dihapus.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        form.submit();
                    });
                }, 1000);
            }
        });
    });
});
</script>
@endsection

{{-- CONTENT --}}
@section('content')
<div class="loading-overlay">
    <div class="spinner-border spinner-border-custom text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="card">
    {{-- HEADER --}}
    <div class="card-header-custom">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="ri-money-dollar-circle-line me-2 text-primary"></i>Data Laba Masuk
                </h4>
                <p class="mb-0 text-muted small">
                    Kelola dan monitor pemasukan laba perusahaan
                    @if(request('filter_month') || request('filter_year'))
                        <span class="badge bg-label-dark ms-2">
                            <i class="ri-filter-line me-1"></i>Filter: {{ $monthLabel ?? '-' }}
                        </span>
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <!-- Search Input -->
                <form action="{{ route('income.index') }}" method="GET" class="d-flex align-items-center">
                    @if(request('filter_month'))
                        <input type="hidden" name="filter_month" value="{{ request('filter_month') }}">
                    @endif
                    @if(request('filter_year'))
                        <input type="hidden" name="filter_year" value="{{ request('filter_year') }}">
                    @endif
                    <div class="input-group" style="width: 300px;">
                        <span class="input-group-text bg-white border-end-0" style="border-color: #e4e4e7;">
                            <i class="ri-search-line text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0 ps-0" 
                               name="search" 
                               placeholder="Cari kode, kategori..." 
                               value="{{ request('search') }}"
                               style="border-color: #e4e4e7;">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-search-line me-1" style="color: #fff !important;"></i>Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('income.index', request()->except('search')) }}" class="btn btn-outline-danger" title="Clear search">
                                <i class="ri-close-line"></i>
                            </a>
                        @endif
                    </div>
                </form>
                
                <div class="d-flex gap-2">
                    <a href="{{ route('income.export', request()->only(['filter_month', 'filter_year', 'search'])) }}" class="btn btn-outline-secondary" title="Export Harian (Pisah Sheet)">
                        <i class="ri-file-excel-line me-1"></i>Export (Pisah Sheet)
                    </a>

                    <a href="{{ route('income.export.monthly', request()->only(['filter_month', 'filter_year'])) }}" class="btn btn-outline-success" title="Export Rekap Sebulan Penuh">
                        <i class="ri-file-excel-2-line me-1"></i>Export Bulanan (1 Sheet)
                    </a>

                    <a href="{{ route('income.export.dedicated', request()->only(['filter_month', 'filter_year', 'search'])) }}" class="btn btn-outline-info" title="Export Khusus Paket Dedicated">
                        <i class="ri-vip-crown-line me-1"></i>Export Dedicated
                    </a>
                </div>
                <a href="{{ route('income.create') }}" class="btn btn-primary btn-add">
                    <i class="ri-add-circle-line me-2"></i>Tambah Laba Masuk
                </a>
            </div>
        </div>
        
        {{-- Search Result Info --}}
        @if(request('search'))
        <div class="mt-3 pt-3 border-top">
            <div class="d-flex align-items-center justify-content-between">
                <span class="text-muted">
                    <i class="ri-search-line me-1"></i>
                    Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
                    <span class="badge bg-label-dark ms-2">{{ $incomes->total() }} data</span>
                </span>
                <a href="{{ route('income.index', request()->except('search')) }}" class="btn btn-sm btn-outline-danger">
                    <i class="ri-close-line me-1"></i>Hapus Filter
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Total Pemasukan Per Tanggal -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="mb-0 fw-bold"><i class="ri-calendar-check-line me-2"></i>Total Pemasukan Per Tanggal</h6>
                <small class="text-muted">Periode: {{ $monthLabel ?? '-' }}</small>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('income.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    <select name="filter_month" class="form-select form-select-sm border-0 bg-light fw-semibold" style="width: auto; cursor: pointer;" onchange="this.form.submit()">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $filterMonth == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="filter_year" class="form-select form-select-sm border-0 bg-light fw-semibold" style="width: auto; cursor: pointer;" onchange="this.form.submit()">
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ $filterYear == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                <table class="table table-hover mb-0">
                    <thead class="bg-light sticky-top">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th class="text-end pe-4">Total Pemasukan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyTotals as $daily)
                            <tr>
                                <td class="ps-4 border-bottom-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm bg-light rounded-circle me-3 d-flex align-items-center justify-content-center">
                                            <i class="ri-calendar-line text-muted"></i>
                                        </div>
                                        <div>
                                            <span class="fw-semibold d-block text-dark">
                                                {{ \Carbon\Carbon::parse($daily->date)->locale('id')->translatedFormat('l, d F Y') }}
                                            </span>
                                            @if(\Carbon\Carbon::parse($daily->date)->isToday())
                                                <span class="badge bg-label-success" style="font-size: 0.7rem;">Hari Ini</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end pe-4 border-bottom-0">
                                    <span class="fw-bold text-dark">Rp {{ number_format($daily->total, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4 text-muted">Belum ada data pemasukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($dailyTotals->count() > 0)
                    <tfoot style="background-color: #18181b;">
                        <tr>
                            <td class="ps-4 fw-bold py-3" style="color: #ffffff !important;">
                                <i class="ri-money-dollar-circle-line me-2" style="color: #ffffff !important;"></i>Total {{ $monthLabel ?? 'Bulan Ini' }}
                            </td>
                            <td class="text-end pe-4 fw-bold py-3" style="font-size: 1.1rem; color: #ffffff !important;">
                                Rp {{ number_format($monthlyTotal ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- TABLE SECTION --}}
    <div class="card-body p-0">
        <div class="table-responsive p-3">
            @if($incomes->count() > 0)
                <div class="selection-toolbar" id="incomeSelectionToolbar">
                    <span class="selected-text" id="incomeSelectedCount">0 dipilih</span>
                    <button type="button" class="toolbar-delete-btn" id="btnBulkDeleteIncome" title="Hapus Terpilih">
                        <i class="ri-delete-bin-line fs-5"></i>
                    </button>
                </div>
                <table class="datatables-income table table-modern table-hover">
                    <thead>
                        <tr>
                            <th style="width: 56px; text-align: center;">
                                <input type="checkbox" class="income-check" id="selectAllIncomes">
                            </th>
                            <th>Pelanggan</th>
                            <th>Paket</th>
                            <th>Tipe Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Tanggal Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incomes as $i)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" class="income-check income-row-checkbox" value="{{ $i->id }}" data-source="{{ $i->sumber ?? 'tagihan' }}">
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <strong style="font-size:0.9rem;">{{ $i->nama_pelanggan ?? '-' }}</strong>
                                    <small class="text-muted">{{ $i->nomer_id ?? '-' }}</small>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-label-dark">{{ $i->nama_paket ?? '-' }}</span>
                            </td>

                            <td>
                                @if(in_array(strtolower($i->tipe_pembayaran ?? ''), ['cash', 'cash / tunai', 'tunai'], true) || empty($i->tipe_pembayaran))
                                    <span class="badge" style="background:#f4f4f5;color:#18181b;border:1px solid #e4e4e7;">
                                        <i class="ri-money-dollar-circle-line me-1"></i>cash
                                    </span>
                                @else
                                    <span class="badge" style="background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;">
                                        <i class="ri-bank-line me-1"></i>{{ $i->tipe_pembayaran }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                <strong style="color: #18181b;">Rp {{ number_format($i->jumlah, 0, ',', '.') }}</strong>
                            </td>

                            <td>{{ \Carbon\Carbon::parse($i->tanggal_pembayaran)->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                {{-- EMPTY STATE --}}
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ri-inbox-line"></i>
                    </div>
                    <h5>Belum ada data laba masuk</h5>
                    <p>Mulai tambahkan data laba masuk untuk mengelola pemasukan</p>
                    <a href="{{ route('income.create') }}" class="btn btn-primary">
                        <i class="ri-add-circle-line me-2"></i>Tambah Laba Masuk Pertama
                    </a>
                </div>
            @endif
        </div>

      <div class="pagination-wrapper">
        <label class="dense-toggle-wrap mb-0">
          <input type="checkbox" id="densePaddingToggleIncome">
          <span class="small">Dense padding</span>
        </label>
        <div>
          @if($incomes->hasPages())
            {{ $incomes->appends(request()->query())->onEachSide(1)->links('pagination.mui') }}
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

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary py-4">
                <h5 class="modal-title text-white fw-bold">
                    <i class="ri-information-line me-2"></i>Detail Laba Masuk
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- Content will be inserted via JavaScript -->
            </div>
        </div>
    </div>
</div>
@endsection
