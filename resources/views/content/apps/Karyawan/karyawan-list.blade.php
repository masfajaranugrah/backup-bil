@extends('layouts/layoutMaster')

@section('title', 'Data Karyawan')

@php
use Illuminate\Support\Str;
@endphp

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
@import url('https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0');

.material-symbols-rounded {
  font-family: 'Material Symbols Rounded' !important;
  font-weight: normal;
  font-style: normal;
  font-size: 1.25rem;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-flex;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-feature-settings: 'liga';
  -webkit-font-smoothing: antialiased;
  font-variation-settings: 'FILL' 0, 'wght' 500, 'GRAD' 0, 'opsz' 24;
}

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
  overflow: visible;
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

.card-header-custom i {
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

.btn-primary i,
.btn.btn-primary i {
  color: #ffffff !important;
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

.btn-add i {
  color: #fafafa !important;
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
  color: #18181b !important;
}

.btn-outline-danger:hover,
.btn.btn-outline-danger:hover {
  background: #18181b !important;
  background-color: #18181b !important;
  border-color: #18181b !important;
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
.modern-table {
  width: 100%;
  border-collapse: collapse;
}

.modern-table thead th {
  text-align: left;
  padding: 1.1rem 0.95rem !important;
  font-size: 0.82rem;
  text-transform: uppercase;
  letter-spacing: 0;
  color: #667085;
  font-weight: 800;
  border-bottom: 1px solid #e2e8f0 !important;
  background: #f8fafc;
  white-space: nowrap;
}

.modern-table tbody tr {
  transition: background 0.2s;
}

.modern-table tbody tr:hover td {
  background: #fcfcfd !important;
}

.modern-table tbody td {
  padding: 0.85rem 0.95rem;
  vertical-align: middle;
  border-bottom: 1px dashed #d8e1ec;
  color: #18181b;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.modern-table tr.row-selected td {
  background: #edf4fd !important;
}

.modern-table thead th:first-child,
.modern-table tbody td:first-child {
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
/* PAGINATION STYLES (COMPACTED / DI KECILKAN) */
/* ========================================= */
.modern-table.is-dense th {
  padding: 0.7rem 1rem !important;
}

.modern-table.is-dense td {
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

/* ========================================= */
/* EXACT PAGINATION STYLES (MATCH OUTSTANDING) */
/* ========================================= */
.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem 1.75rem;
  background: #fafafa;
  border-top: 1px solid #e4e4e7;
}

.pagination-wrapper .pagination {
  margin: 0;
  gap: 0.5rem;
}

.pagination-wrapper .page-item .page-link {
  border-radius: 50% !important;
  width: 30px;
  height: 30px;
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

.pagination-wrapper .page-item .page-link:hover {
  background-color: #18181b;
  border-color: #18181b;
  color: #fafafa;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(24, 24, 27, 0.2);
}

.pagination-wrapper .page-item.active .page-link {
  background-color: #18181b !important;
  border-color: #18181b !important;
  color: #fafafa !important;
  box-shadow: 0 4px 12px rgba(24, 24, 27, 0.4);
}

.pagination-wrapper .page-item.disabled .page-link {
  background-color: #f4f4f5;
  border-color: #e4e4e7;
  color: #a1a1aa;
  cursor: not-allowed;
}

.pagination-wrapper .mui-pagination {
  gap: 0.5rem;
}

.pagination-wrapper .mui-pagination .page-link.page-nav-icon {
  width: 30px;
  height: 30px;
}

/* Hide DataTables sort arrows on table headers */
.modern-table thead th.sorting::before,
.modern-table thead th.sorting::after,
.modern-table thead th.sorting_asc::before,
.modern-table thead th.sorting_asc::after,
.modern-table thead th.sorting_desc::before,
.modern-table thead th.sorting_desc::after {
  display: none !important;
  content: none !important;
}

.dense-toggle-wrap {
  display: inline-flex;
  align-items: center;
  gap: .5rem;
  font-weight: 600;
  color: #334155;
}

.dense-toggle-wrap input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #111827;
}

@media (max-width: 768px) {
  .pagination-wrapper {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
}

/* Custom Checkbox */
.custom-check {
  appearance: none;
  width: 22px;
  height: 22px;
  border: 2px solid #cbd5e1;
  border-radius: 5px;
  background: #fff;
  cursor: pointer;
  position: relative;
  flex-shrink: 0;
}
.custom-check:checked {
  background: #0f172a;
  border-color: #0f172a;
}
.custom-check:checked::after {
  content: '';
  position: absolute;
  top: 2px;
  left: 7px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
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

/* Action Button (Titik 3) */
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
  cursor: pointer;
  transition: all 0.2s ease;
  box-shadow: none;
}
.action-btn:hover, .action-btn[aria-expanded="true"] {
  background: #f1f5f9;
  color: #0f172a;
}
.action-btn i {
  font-size: 1.25rem;
  line-height: 1;
}

.tagihan-action-menu {
  position: absolute !important;
  top: 50% !important;
  right: calc(100% + 12px) !important;
  left: auto !important;
  transform: translateY(-50%) !important;
  width: 230px !important;
  min-width: 230px !important;
  max-width: calc(100vw - 32px) !important;
  height: auto !important;
  padding: 0.75rem;
  border: 1px solid #e2e8f0 !important;
  border-radius: 18px !important;
  background: linear-gradient(115deg, #fff4f1 0%, #ffffff 50%, #f1fbff 100%) !important;
  box-shadow: 0 18px 42px rgba(15, 23, 42, 0.16) !important;
  margin: 0 !important;
  z-index: 20000;
}

.tagihan-action-menu::after {
  content: '';
  display: none;
  position: absolute;
  top: 50%;
  right: -8px;
  width: 16px;
  height: 16px;
  background: #f7fdff;
  border-right: 1px solid #e2e8f0;
  border-top: 1px solid #e2e8f0;
  transform: translateY(-50%) rotate(45deg);
}

.tagihan-action-menu.show::after {
  display: block;
}

.tagihan-action-menu .dropdown-item {
  position: relative;
  z-index: 1;
  border-radius: 10px;
  padding: 0.55rem 0.6rem;
  font-weight: 600;
  color: #1f2937;
  display: flex;
  align-items: center;
  gap: 0.65rem;
}

.tagihan-action-menu .dropdown-item:hover {
  background: rgba(255, 255, 255, 0.72);
  color: #111827;
}

.tagihan-action-menu .dropdown-item i {
  font-size: 1.25rem;
  color: inherit !important;
}

.tagihan-action-menu .dropdown-item.danger-action {
  color: #ff4528 !important;
}

.tagihan-action-menu .dropdown-item.danger-action:hover {
  background: rgba(255, 69, 40, 0.08) !important;
}

.modern-table .dropdown {
  position: relative !important;
}

.card-datatable {
  overflow: visible !important;
}

.table-responsive {
  overflow-x: visible !important;
  overflow-y: visible !important;
}

.modern-table tbody td:last-child,
.modern-table thead th:last-child {
  overflow: visible !important;
  position: relative;
}

.dataTables_wrapper::after {
  content: '';
  display: table;
  clear: both;
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
  background-color: rgba(24, 24, 27, 0.4) !important;
}

.modal-backdrop.show {
  opacity: 1 !important;
}

.modal-content {
  border-radius: 12px;
  border: 1px solid #e4e4e7;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
  background: #ffffff;
  overflow: hidden;
}

.modal-header {
  background: #18181b !important;
  padding: 1.5rem;
  border-bottom: 1px solid #e4e4e7;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-title {
  font-weight: 600;
  font-size: 1.125rem;
  color: #fafafa !important;
  margin: 0;
}

.modal-header .btn-close {
  padding: 0.5rem;
  margin: -0.5rem -0.5rem -0.5rem auto;
  filter: invert(1);
  opacity: 0.8;
  transition: opacity 0.15s ease;
}

.modal-header .btn-close:hover {
  opacity: 1;
}

.modal-body {
  padding: 1.5rem;
  padding-top: 2rem;
  max-height: 70vh;
  overflow-y: auto;
}

.modal-footer {
  padding: 1.25rem 1.5rem;
  border-top: 1px solid #e4e4e7;
  background: #fafafa;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  min-height: 4.5rem;
}

.outstanding-toast {
  position: fixed;
  right: 24px;
  bottom: 24px;
  transform: translateY(20px);
  background: #061533;
  color: #fff;
  border-radius: 34px;
  padding: 0.9rem 1.35rem;
  display: inline-flex;
  align-items: center;
  gap: 12px;
  font-size: 1rem;
  z-index: 2100;
  opacity: 0;
  transition: all 0.25s ease;
  box-shadow: 0 14px 30px rgba(2, 6, 23, 0.25);
}

.outstanding-toast.show {
  opacity: 1;
  transform: translateY(0);
}

.swal-outstanding-delete {
  width: min(92vw, 640px) !important;
  border-radius: 16px !important;
  padding: 1.4rem 1.2rem 1.15rem !important;
}

.swal-outstanding-title {
  color: #666d80 !important;
  font-weight: 700 !important;
  font-size: clamp(1.35rem, 2.4vw, 2.35rem) !important;
  line-height: 1.1 !important;
  margin-top: 0.2rem !important;
}

.swal2-html-container.swal-outstanding-html {
  color: #555b6a !important;
  font-size: clamp(1rem, 1.55vw, 1.35rem) !important;
  line-height: 1.35 !important;
  margin-top: 0.2rem !important;
  margin-bottom: 1.05rem !important;
}

.swal-outstanding-confirm,
.swal-outstanding-cancel {
  border-radius: 12px !important;
  min-width: 118px;
  min-height: 48px;
  font-size: 1.1rem !important;
  font-weight: 600 !important;
  padding: 0.55rem 1.1rem !important;
}

.swal-outstanding-confirm {
  background: #0b0f1c !important;
  border: 3px solid #ffffff !important;
  box-shadow: 0 0 0 2px #151827 !important;
}

.swal-outstanding-cancel {
  background: #0b0f1c !important;
}

.swal2-icon.swal2-warning {
  border-color: #f4b74a !important;
  color: #f4b022 !important;
}

/* ========== KARYAWAN DELETE MODAL ========== */
.swal-karyawan-popup {
  border-radius: 20px !important;
  padding: 2rem 1.5rem 1.5rem !important;
  box-shadow: 0 25px 60px rgba(0,0,0,0.18) !important;
  border: none !important;
  width: min(90vw, 420px) !important;
}

.swal-karyawan-popup .swal2-title {
  font-size: 1.4rem !important;
  font-weight: 700 !important;
  color: #18181b !important;
  margin-bottom: 0.5rem !important;
}

.swal-karyawan-popup .swal2-html-container {
  color: #52525b !important;
  font-size: 0.95rem !important;
  line-height: 1.6 !important;
}

.swal-karyawan-icon {
  margin: 0 auto 1rem !important;
  width: 4rem !important;
  height: 4rem !important;
  border-color: #fbbf24 !important;
}

.swal-karyawan-confirm {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
  color: #fff !important;
  border: none !important;
  border-radius: 12px !important;
  padding: 0.65rem 1.5rem !important;
  font-size: 0.9rem !important;
  font-weight: 600 !important;
  box-shadow: 0 4px 15px rgba(239,68,68,0.35) !important;
  transition: all 0.2s !important;
  margin: 0 0.4rem !important;
}

.swal-karyawan-confirm:hover {
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
  box-shadow: 0 6px 20px rgba(239,68,68,0.45) !important;
  transform: translateY(-1px) !important;
}

.swal-karyawan-cancel {
  background: #f4f4f5 !important;
  color: #52525b !important;
  border: 1px solid #e4e4e7 !important;
  border-radius: 12px !important;
  padding: 0.65rem 1.5rem !important;
  font-size: 0.9rem !important;
  font-weight: 600 !important;
  transition: all 0.2s !important;
  margin: 0 0.4rem !important;
}

.swal-karyawan-cancel:hover {
  background: #e4e4e7 !important;
  color: #18181b !important;
}

.employee-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: #18181b !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 2.5rem;
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

.employee-header-info {
  text-align: center;
  padding: 1.5rem;
  background: #fafafa;
  border-radius: 10px;
  margin-bottom: 1.5rem;
  border: 1px solid #e4e4e7;
}

.employee-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: #18181b;
  margin-bottom: 0.5rem;
}

.employee-position {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  background: #18181b !important;
  color: white;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.875rem;
  box-shadow: 0 2px 8px rgba(24, 24, 27, 0.3);
}

.employee-detail-modal {
  width: 100vw;
  max-width: 100vw;
  height: 100vh;
  margin: 0;
}

.employee-detail-content {
  border-radius: 0 !important;
  width: 100vw;
  height: 100vh;
  min-height: 100vh;
  max-height: 100vh;
  overflow: hidden !important;
}

.employee-detail-shell {
  height: 100%;
  display: grid;
  grid-template-columns: minmax(300px, 36%) 1fr;
  background: #fff;
  overflow: hidden;
}

.employee-detail-sidebar {
  background: linear-gradient(180deg, #f1f2f5 0%, #fafafa 100%);
  border-right: 1px solid #dfe3ea;
  padding: 2rem 1.4rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  overflow-y: auto;
}

.employee-detail-main {
  padding: 1.9rem;
  position: relative;
  display: flex;
  flex-direction: column;
  overflow-y: auto;
}

#detailModal .modal-body {
  max-height: none !important;
  height: 100%;
  overflow: hidden !important;
  padding: 0 !important;
}

#detailModal .modal-dialog {
  max-width: 100vw !important;
  width: 100vw !important;
  height: 100vh !important;
  margin: 0 !important;
  padding: 0 !important;
}

#detailModal {
  padding: 0 !important;
}

.employee-modal-close {
  position: absolute;
  top: 1.25rem;
  right: 1.25rem;
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background-color: #f8fafc;
  box-shadow: 0 8px 18px rgba(15, 23, 42, 0.08);
  z-index: 10;
}

.employee-info-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.employee-info-card {
  min-height: 92px;
  border-radius: 12px;
  border: 1px solid #d7dce5;
  border-left-width: 4px;
  background: #f8fafc;
  padding: 1rem;
}

.employee-info-card.black {
  border-left-color: #18181b;
}

.employee-info-card.red {
  border-left-color: #ef4444;
}

.employee-document-box {
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  background: #f8fafc;
  padding: 1.25rem;
  flex: 1;
  min-height: 250px;
}

.employee-document-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 0;
  min-height: 205px;
}

.employee-document-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 1rem;
}

.employee-document-item + .employee-document-item {
  border-left: 1px solid #e5e7eb;
}

.employee-empty-doc {
  width: 100%;
  min-height: 150px;
  border-radius: 12px;
  display: grid;
  place-items: center;
  color: #71717a;
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

  .employee-detail-modal {
    width: 100vw;
    max-width: 100vw;
    height: 100vh;
    margin: 0;
  }

  .employee-detail-content {
    width: 100vw;
    height: 100vh;
    min-height: 100vh;
    max-height: 100vh;
  }

  .employee-detail-shell {
    grid-template-columns: 1fr;
    height: auto;
  }

  .employee-detail-sidebar {
    border-right: 0;
    border-bottom: 1px solid #dfe3ea;
  }

  .employee-info-grid,
  .employee-document-grid {
    grid-template-columns: 1fr;
  }

  .employee-document-item + .employee-document-item {
    border-left: 0;
    border-top: 1px solid #e5e7eb;
  }
}

@media (max-width: 576px) {
  .modern-table {
    font-size: 0.85rem;
  }

  .modern-table thead th,
  .modern-table tbody td {
    padding: 0.75rem 0.5rem;
  }

  .outstanding-toast {
    right: 12px;
    bottom: 12px;
    max-width: calc(100vw - 24px);
    font-size: 0.92rem;
    padding: 0.75rem 1rem;
  }
}

/* ========== DELETE CONFIRMATION MODAL ========== */
.delete-confirm-overlay {
  position: fixed;
  inset: 0;
  background: rgba(24, 24, 27, 0.5);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  z-index: 99998;
  display: none;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.2s ease;
}

.delete-confirm-overlay.is-open {
  display: flex;
  opacity: 1;
}

.delete-confirm-card {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  width: min(420px, calc(100vw - 32px));
  padding: 2rem;
  text-align: center;
  transform: scale(0.95) translateY(10px);
  transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}

.delete-confirm-overlay.is-open .delete-confirm-card {
  transform: scale(1) translateY(0);
}

.delete-confirm-icon {
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: #fef2f2;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
}

.delete-confirm-icon .material-symbols-rounded {
  font-size: 1.75rem;
  color: #ef4444;
}

.delete-confirm-card h4 {
  font-size: 1.15rem;
  font-weight: 700;
  color: #18181b;
  margin-bottom: 0.5rem;
}

.delete-confirm-card p {
  font-size: 0.9rem;
  color: #71717a;
  margin-bottom: 1.5rem;
  line-height: 1.5;
}

.delete-confirm-actions {
  display: flex;
  gap: 0.75rem;
  justify-content: center;
}

.delete-confirm-actions .btn-cancel-delete {
  flex: 1;
  padding: 0.65rem 1.25rem;
  border-radius: 10px;
  border: 1px solid #e4e4e7;
  background: #fff;
  color: #18181b;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.15s ease;
}

.delete-confirm-actions .btn-cancel-delete:hover {
  background: #f4f4f5;
  border-color: #a1a1aa;
}

.delete-confirm-actions .btn-exec-delete {
  flex: 1;
  padding: 0.65rem 1.25rem;
  border-radius: 10px;
  border: 1px solid #dc2626;
  background: #dc2626;
  color: #fff;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.15s ease;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
}

.delete-confirm-actions .btn-exec-delete:hover {
  background: #b91c1c;
  border-color: #b91c1c;
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
  'resources/assets/vendor/libs/moment/moment.js',
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Helper function untuk loading overlay
    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }

    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    function showOutstandingToast(message, type = 'success') {
        const toast = document.createElement('div');
        const isError = type === 'error';
        toast.style.cssText = [
            'position:fixed',
            'right:24px',
            'bottom:28px',
            'transform:translateY(10px)',
            'z-index:99999',
            'display:flex',
            'align-items:center',
            'gap:12px',
            'max-width:min(520px,calc(100vw - 32px))',
            'min-width:min(360px,calc(100vw - 32px))',
            'padding:14px 20px',
            'border-radius:18px',
            `background:${isError ? '#dc2626' : '#111827'}`,
            'color:#fff',
            'box-shadow:0 16px 42px rgba(15,23,42,.32)',
            'font-size:1rem',
            'font-weight:500',
            'opacity:0',
            'transition:all .2s ease'
        ].join(';');
        toast.innerHTML = `<span class="material-symbols-rounded" style="font-size:1.15rem;line-height:1;color:${isError ? '#fecaca' : '#86efac'}">check_circle</span><span>${message}</span>`;
        document.body.appendChild(toast);
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(10px)';
            setTimeout(() => toast.remove(), 220);
        }, 3200);
    }

    @if(session('success'))
      showOutstandingToast(@json(session('success')));
    @endif

    // Inisialisasi DataTable
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

    // Event Detail Karyawan - via icon mata
    $(document).on('click', '.btn-detail', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = $(this);
        const tr = btn.closest('tr');

        // Get data from tr attributes - paksa ke String agar .charAt() tidak error
        const nik          = String(tr.data('nik')          ?? '-') || '-';
        const fullName     = String(tr.data('nama')         ?? '-') || '-';
        const address      = String(tr.data('alamat')       ?? '-') || '-';
        const birthPlace   = String(tr.data('tempat-lahir') ?? '-') || '-';
        const birthDate    = String(tr.data('tanggal-lahir')?? '-') || '-';
        const phone        = String(tr.data('hp')           ?? '-') || '-';
        const joinDate     = String(tr.data('tanggal-masuk')?? '-') || '-';
        const position     = String(tr.data('jabatan')      ?? '-') || '-';
        const bank         = String(tr.data('bank')         ?? '-') || '-';
        const accountNumber= String(tr.data('no-rekening')  ?? '-') || '-';
        const accountName  = String(tr.data('atas-nama')    ?? '-') || '-';
        const hasFoto      = tr.data('has-foto') == 1;
        const hasFotoKtp   = tr.data('has-foto-ktp') == 1;
        const employeeId   = tr.data('id');
        const initial      = fullName.charAt(0).toUpperCase() || '?';

        const photoUrl = hasFoto ? `/dashboard/admin/employees/image/${employeeId}/foto` : '';
        const ktpUrl = hasFotoKtp ? `/dashboard/admin/employees/image/${employeeId}/foto_ktp` : '';

        const html = `
            <div class="employee-detail-shell">
                <!-- Left Sidebar -->
                <div class="employee-detail-sidebar">
                    <div class="employee-avatar mx-auto shadow-sm" style="width: 110px; height: 110px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #18181b; color: white; font-size: 3rem; margin-bottom: 1.25rem; border: 4px solid white; overflow: hidden;">
                        ${hasFoto ? `<img src="${photoUrl}" alt="Foto" style="width:100%; height:100%; object-fit:cover;">` : initial}
                    </div>
                    <h4 class="fw-bold mb-1 text-dark text-center">${fullName}</h4>
                    <p class="text-muted small mb-3 text-center">${nik}</p>
                    
                    <span class="badge bg-danger mb-4 px-3 py-2" style="font-size:0.8rem;">
                        <i class="ri-briefcase-line me-1"></i> ${position}
                    </span>

                    <div class="w-100 mt-2">
                        <div class="bg-white rounded p-3 mb-3 shadow-sm border border-light">
                            <small class="text-muted d-block fw-bold" style="font-size:0.7rem;">NO. HP / WHATSAPP</small>
                            <a href="tel:${phone}" class="text-dark fw-bold text-decoration-none d-flex align-items-center mt-1">
                                <i class="ri-whatsapp-line me-2 text-success" style="font-size:1.2rem;"></i> ${phone}
                            </a>
                        </div>
                        <div class="bg-white rounded p-3 mb-3 shadow-sm border border-light">
                            <small class="text-muted d-block fw-bold" style="font-size:0.7rem;">ALAMAT</small>
                            <div class="d-flex align-items-start mt-1">
                                <i class="ri-map-pin-line me-2 text-muted mt-1"></i>
                                <span class="text-dark fw-medium" style="font-size:0.85rem;">${address}</span>
                            </div>
                        </div>
                        <div class="bg-white rounded p-3 shadow-sm border border-light">
                            <small class="text-muted d-block fw-bold" style="font-size:0.7rem;">TANGGAL MASUK</small>
                            <div class="d-flex align-items-center mt-1">
                                <i class="ri-calendar-check-line me-2 text-primary"></i>
                                <span class="text-dark fw-medium">${joinDate}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="employee-detail-main">
                    <button type="button" class="btn-close employee-modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    
                    <div class="mb-4 pe-5">
                        <h3 class="fw-bold text-dark mb-1">Informasi Karyawan</h3>
                        <p class="text-muted small">Rincian data pribadi dan perbankan karyawan.</p>
                    </div>

                    <div class="employee-info-grid">
                        <div class="employee-info-card black">
                                <small class="text-muted fw-bold d-block mb-1" style="font-size:0.75rem;">TEMPAT, TANGGAL LAHIR</small>
                                <strong class="text-dark" style="font-size:0.95rem;">${birthPlace}, ${birthDate}</strong>
                        </div>
                        <div class="employee-info-card red">
                                <small class="text-muted fw-bold d-block mb-1" style="font-size:0.75rem;">REKENING BANK</small>
                                <strong class="text-dark d-block" style="font-size:0.95rem;">${bank}</strong>
                                <span class="text-muted small">${accountNumber} a/n ${accountName}</span>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 d-flex align-items-center text-dark">
                        <i class="ri-folder-user-line me-2"></i> Dokumen Pegawai
                    </h6>
                    
                    <div class="employee-document-box text-center">
                        <div class="employee-document-grid">
                            <div class="employee-document-item">
                                <p class="small fw-bold text-muted mb-2">Foto Karyawan</p>
                                ${hasFoto ? 
                                    `<img src="${photoUrl}" class="img-fluid rounded shadow-sm" style="max-height: 140px; cursor: pointer; border: 2px solid white;" onclick="window.open('${photoUrl}', '_blank')">` : 
                                    `<div class="employee-empty-doc"><div><i class="ri-image-line text-muted" style="font-size: 2rem;"></i><p class="small text-muted mt-2 mb-0">Belum ada foto</p></div></div>`}
                            </div>
                            <div class="employee-document-item">
                                <p class="small fw-bold text-muted mb-2">Foto KTP</p>
                                ${hasFotoKtp ? 
                                    `<img src="${ktpUrl}" class="img-fluid rounded shadow-sm" style="max-height: 140px; cursor: pointer; border: 2px solid white;" onclick="window.open('${ktpUrl}', '_blank')">` : 
                                    `<div class="employee-empty-doc"><div><i class="ri-bank-card-line text-muted" style="font-size: 2rem;"></i><p class="small text-muted mt-2 mb-0">Belum ada KTP</p></div></div>`}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        `;

        $('#detailModal .modal-body').html(html);

        // Gunakan Bootstrap 5 native modal API
        const detailModalEl = document.getElementById('detailModal');
        let detailModal = bootstrap.Modal.getInstance(detailModalEl);
        if (!detailModal) {
            detailModal = new bootstrap.Modal(detailModalEl, {
                backdrop: true,
                keyboard: true,
                focus: true
            });
        }
        detailModal.show();
    });

    // Event DELETE dengan SweetAlert
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = this;

        // Get employee name from the row
        const $row = $(this).closest('tr');
        const nama = $row.data('nama') || 'karyawan ini';

        Swal.fire({
            title: 'Hapus Karyawan?',
            html: `Yakin ingin menghapus data <strong>${nama}</strong>?<br><span style="color:#6b7280;font-size:0.875rem;">Data yang dihapus tidak dapat dikembalikan.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-delete-bin-line"></i> &nbsp;Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup swal-karyawan-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger swal-karyawan-confirm',
                cancelButton: 'swal-tailwind-cancel',
                icon: 'swal-karyawan-icon'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => form.submit(), 300);
            }
        });
    });

    // ========== CHECKBOX SELECT ALL & BULK DELETE ==========
    const $selectAll = $('#selectAllEmployees');
    const $selectionToolbar = $('#selectionToolbar');
    const $selectedCount = $('#selectedCount');
    const $bulkBtn = $('#btnBulkDelete');

    function updateBulkState() {
        const $all = $('.employee-checkbox');
        const $checked = $('.employee-checkbox:checked');
        const count = $checked.length;
        $selectAll.prop('checked', $all.length > 0 && count === $all.length);
        $selectAll.prop('indeterminate', count > 0 && count < $all.length);

        // Toggle row-selected class on rows
        $('tr.employee-row').removeClass('row-selected');
        $checked.closest('tr.employee-row').addClass('row-selected');

        $selectedCount.text(count + ' dipilih');
        if (count > 0) {
            $selectionToolbar.addClass('active').fadeIn(200);
        } else {
            $selectionToolbar.removeClass('active').fadeOut(200);
        }
    }

    $selectAll.on('change', function() {
        $('.employee-checkbox').prop('checked', this.checked);
        updateBulkState();
    });

    $(document).on('change', '.employee-checkbox', function() {
        updateBulkState();
    });

    $bulkBtn.on('click', function() {
        const ids = $('.employee-checkbox:checked').map(function() { return $(this).val(); }).get();
        if (ids.length === 0) return;

        Swal.fire({
            title: 'Hapus Karyawan?',
            html: `Yakin ingin menghapus <strong>${ids.length} karyawan</strong> terpilih?<br><span style="color:#6b7280;font-size:0.875rem;">Data yang dihapus tidak dapat dikembalikan.</span>`,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: '<i class="ri-delete-bin-line"></i> &nbsp;Ya, Hapus',
            reverseButtons: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            buttonsStyling: false,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup swal-karyawan-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger swal-karyawan-confirm',
                cancelButton: 'swal-tailwind-cancel',
                icon: 'swal-karyawan-icon'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                $.ajax({
                    url: '{{ route("employees.bulkDestroy") }}',
                    type: 'POST',
                    data: { ids: ids, _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        hideLoading();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        hideLoading();
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.' });
                    }
                });
            }
        });
    });

    // ========== DENSE PADDING TOGGLE ==========
    const denseToggle = document.getElementById('densePaddingToggle');
    const tableEl = document.querySelector('.modern-table');
    if (denseToggle && tableEl) {
        const savedDense = localStorage.getItem('karyawan_dense_padding') === '1';
        denseToggle.checked = savedDense;
        tableEl.classList.toggle('is-dense', savedDense);

        denseToggle.addEventListener('change', function() {
            const isDense = denseToggle.checked;
            tableEl.classList.toggle('is-dense', isDense);
            localStorage.setItem('karyawan_dense_padding', isDense ? '1' : '0');
        });
    }

    // Removed dropdown manual positioning scripts
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

<!-- Karyawan List Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header-custom">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="ri-team-line me-2"></i>Data Karyawan
                </h4>
                <p class="mb-0 opacity-75 small">Kelola dan monitor data karyawan perusahaan</p>
            </div>
            <div class="d-flex action-buttons mt-3 mt-md-0 align-items-center">
                <a href="{{ route('karyawan.create') }}" class="btn btn-primary btn-add">
                    <i class="ri-user-add-line"></i>
                    Tambah Karyawan Baru
                </a>
            </div>
        </div>

        <!-- Search Form -->
        <div class="mt-4">
            <form action="{{ route('karyawan.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group" style="max-width: 400px;">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="ri-search-line text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control border-start-0 ps-0" 
                        placeholder="Cari NIK, Nama, HP, atau Jabatan..." 
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-search-line me-1"></i> Cari
                </button>
                @if(request('search'))
                <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line me-1"></i> Reset
                </a>
                @endif
            </form>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="card-datatable table-responsive p-3" style="position: relative;">
            <!-- Selection Toolbar -->
            <div class="selection-toolbar rounded-3 mb-3" id="selectionToolbar" style="display: none;">
                <span class="selected-text" id="selectedCount">0 dipilih</span>
                <button type="button" class="clear-btn" id="btnBulkDelete" title="Hapus Terpilih">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>

            <table class="datatables-users table modern-table table-hover">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;"><input type="checkbox" id="selectAllEmployees" class="custom-check"></th>
                        <th>Nama Lengkap</th>
                        <th>Alamat</th>
                        <th>No. HP</th>
                        <th>Tanggal Masuk</th>
                        <th>Jabatan</th>
                        <th class="text-center" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                    <tr
                        data-nik="{{ $employee->nik }}"
                        data-nama="{{ $employee->full_name }}"
                        data-alamat="{{ $employee->full_address }}"
                        data-tempat-lahir="{{ $employee->place_of_birth }}"
                        data-tanggal-lahir="{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') }}"
                        data-hp="{{ $employee->no_hp }}"
                        data-tanggal-masuk="{{ \Carbon\Carbon::parse($employee->tanggal_masuk)->format('d M Y') }}"
                        data-jabatan="{{ $employee->jabatan }}"
                        data-bank="{{ $employee->bank }}"
                        data-no-rekening="{{ $employee->no_rekening }}"
                        data-atas-nama="{{ $employee->atas_nama }}"
                        data-id="{{ $employee->id }}"
                        data-has-foto="{{ $employee->foto ? 1 : 0 }}"
                        data-has-foto-ktp="{{ $employee->foto_ktp ? 1 : 0 }}"
                        class="employee-row"
                    >
                        <td class="text-center">
                            <input type="checkbox" class="employee-checkbox custom-check" value="{{ $employee->id }}">
                        </td>
                        <td>
                            <div class="product-cell">
                                <div class="product-info">
                                    <h6>{{ $employee->full_name }}</h6>
                                    <span>NIK: {{ $employee->nik }}</span>
                                </div>
                            </div>
                        </td>

                        <td>{{ Str::limit($employee->full_address, 30) }}</td>

                        <td>{{ $employee->no_hp }}</td>

                        <td>{{ \Carbon\Carbon::parse($employee->tanggal_masuk)->format('d M Y') }}</td>

                        <td>
                            <span class="badge bg-label-info">{{ $employee->jabatan }}</span>
                        </td>

                        <td>
                            <div class="dropdown d-flex justify-content-center">
                                <button class="action-btn" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end tagihan-action-menu">
                                    <li>
                                        <a class="dropdown-item btn-detail" href="javascript:void(0);">
                                            <i class="ri-eye-line"></i> Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('employees.edit', $employee->id) }}">
                                            <i class="ri-edit-2-line"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="delete-form m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item danger-action w-100 border-0 bg-transparent text-start">
                                                <i class="ri-delete-bin-line"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($employees->count() > 0)
        <div class="pagination-wrapper">
          <label class="dense-toggle-wrap mb-0">
            <input type="checkbox" id="densePaddingToggle">
            <span>Dense padding</span>
          </label>
          <div>
            @if($employees->total() > 40)
              {{ $employees->appends(request()->query())->onEachSide(1)->links('pagination.mui') }}
            @else
              <nav aria-label="Pagination">
                <ul class="pagination mui-pagination mb-0">
                  <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-left-double-line"></i></span></li>
                  <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-left-s-line"></i></span></li>
                  <li class="page-item active"><span class="page-link">1</span></li>
                  <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-right-s-line"></i></span></li>
                  <li class="page-item disabled"><span class="page-link"><i class="ri-arrow-right-double-line"></i></span></li>
                </ul>
              </nav>
            @endif
          </div>
        </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered employee-detail-modal">
        <div class="modal-content border-0 shadow-lg employee-detail-content">
            <div class="modal-body p-0">
                <!-- Custom content will be inserted via JavaScript -->
            </div>
        </div>
    </div>
</div>

@endsection
