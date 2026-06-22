@extends('layouts/layoutMaster')

@section('title', 'Data Pelanggan')

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

.btn-primary i, .btn.btn-primary i {
  color: #ffffff !important;
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
  transition: all 0.3s ease !important;
}
.btn-primary.btn-add i {
  color: #ffffff !important;
}

.btn-add:hover {
  transform: translateY(-2px) !important;
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

/* ========== SEARCH SECTION ========== */
.search-section {
  background: var(--gray-bg);
  padding: 1.5rem;
  border-bottom: 1px solid var(--gray-border);
}

.search-input-group {
  max-width: 900px;
  margin: 0 auto;
}

.search-input-group .input-group {
  box-shadow: 0 2px 8px rgba(0,0,0,0.06);
  border-radius: 10px;
  overflow: hidden;
}

.search-input-group .input-group-text {
  background: white;
  border: 1px solid #e4e4e7;
  border-right: 0;
  padding: 0.75rem 1rem;
}

.search-input-group .input-group-text i {
  color: #18181b !important;
}

.search-input-group .form-control {
  border: 1px solid #e4e4e7;
  border-left: 0;
  border-right: 0;
  padding: 0.75rem 1rem;
  font-size: 0.95rem;
}

.search-input-group .form-control:focus {
  border-color: #18181b;
  box-shadow: none;
}

.search-input-group .btn {
  border: 1px solid #18181b;
  padding: 0.75rem 1.5rem !important;
  font-weight: 600;
  white-space: nowrap;
}

.btn-clear-search {
  background: white !important;
  border: 1px solid #e4e4e7 !important;
  border-left: 0 !important;
  border-right: 0 !important;
  color: #71717a !important;
  padding: 0.75rem 1rem !important;
}

.btn-clear-search:hover {
  background: #f4f4f5 !important;
  color: #dc2626 !important;
}

.search-info-box {
  max-width: 900px;
  margin: 1rem auto 0;
  padding: 0.75rem 1rem;
  background: white;
  border-radius: 8px;
  border: 1px solid #e4e4e7;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.search-keyword {
  background: #f4f4f5;
  color: #18181b;
  padding: 2px 10px;
  border-radius: 4px;
  font-weight: 600;
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
  text-align: center;
  width: 50px;
}

.table-modern thead th.sorting::before,
.table-modern thead th.sorting::after,
.table-modern thead th.sorting_asc::before,
.table-modern thead th.sorting_asc::after,
.table-modern thead th.sorting_desc::before,
.table-modern thead th.sorting_desc::after {
  display: none !important;
  content: none !important;
}

/* ========== MUI CHECKBOX ========== */
.mui-checkbox {
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
.mui-checkbox:checked {
  background: #0f172a;
  border-color: #0f172a;
}
.mui-checkbox:checked::after {
  content: '';
  position: absolute;
  top: 2px;
  left: 7px;
  width: 5px;
  height: 10px;
  border: solid #fff;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.action-btn {
  background: transparent;
  border: 0;
  color: #94a3b8;
  width: 32px;
  height: 32px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s ease;
}
.action-btn:hover,
.action-btn[aria-expanded="true"] {
  background: #f1f5f9;
  color: #0f172a;
}
.action-btn i {
  font-size: 1.25rem;
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
  height: auto !important;
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
.tagihan-action-menu .dropdown-item.text-danger,
.tagihan-action-menu .dropdown-item.text-danger:hover {
  color: #ff3b30 !important;
}
.table-modern .dropdown {
  position: relative !important;
}
.table-responsive {
  overflow-x: auto !important;
  overflow-y: visible !important;
}
.table-modern tbody td:last-child,
.table-modern thead th:last-child {
  overflow: visible !important;
  position: relative;
}

/* ========== BULK ACTION BAR ========== */
.bulk-action-bar {
  display: none;
  align-items: center;
  justify-content: space-between;
  padding: 0.85rem 1.25rem;
  background: #eaf1fb;
  color: #0f172a;
  font-size: 1rem;
  font-weight: 700;
  border-bottom: 1px solid #d6e4f3;
  animation: slideDown 0.2s ease;
}

.bulk-action-bar #selectedCount {
  font-size: 1.05rem;
}

.bulk-action-bar.show {
  display: flex;
}

.bulk-action-bar .selected-count {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.bulk-action-bar .btn-bulk-delete {
  background: transparent;
  color: #64748b;
  border: none;
  width: 34px;
  height: 34px;
  border-radius: 8px;
  font-size: 1.2rem;
  font-weight: 600;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.35rem;
  transition: background 0.15s ease;
}

.bulk-action-bar .btn-bulk-delete:hover {
  background: rgba(100, 116, 139, 0.12);
}

@keyframes slideDown {
  from { opacity: 0; transform: translateY(-8px); }
  to { opacity: 1; transform: translateY(0); }
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

.badge.bg-success,
.bg-success {
  background: #22c55e !important;
  color: #fafafa !important;
}

.badge.bg-warning,
.bg-warning {
  background: #f59e0b !important;
  color: #fafafa !important;
}

.badge.bg-danger,
.bg-danger {
  background: #dc2626 !important;
  color: #fafafa !important;
}

.badge.bg-secondary,
.bg-secondary:not(.btn) {
  background: #71717a !important;
  color: #fafafa !important;
}

.bg-label-dark {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
}

/* ========== PAGINATION SECTION ========== */
.pagination-section {
  background: var(--gray-bg);
  padding: 1.5rem;
  border-top: 1px solid var(--gray-border);
  border-radius: 0 0 12px 12px;
}

.pagination-wrapper {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}

.pagination-info {
  color: #71717a;
  font-size: 0.9rem;
}

.pagination-info i {
  color: #18181b;
  margin-right: 0.5rem;
}

.pagination {
  margin: 0;
}

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
  font-weight: 600;
  font-size: 0.8rem;
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
  font-size: 0.82rem;
  font-weight: 600;
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
  margin: 0.5rem 0;
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
  padding: 2rem 2rem;
  border-top: 1px solid #e4e4e7;
  background: #fafafa;
  border-radius: 0 0 16px 16px;
}

.swal2-container.pelanggan-delete-backdrop {
  background: rgba(15, 23, 42, 0.48) !important;
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}

.swal2-popup.pelanggan-delete-popup {
  border: 1px solid rgba(226, 232, 240, 0.9) !important;
  box-shadow: 0 24px 70px rgba(15, 23, 42, 0.22) !important;
}

.swal2-popup.pelanggan-delete-popup .swal2-title {
  color: #0f172a;
  font-size: 1.35rem;
  font-weight: 800;
  letter-spacing: 0;
}

.swal2-popup.pelanggan-delete-popup .swal2-html-container {
  color: #64748b;
  font-size: 0.95rem;
  line-height: 1.6;
  margin-top: 0.75rem;
}

.swal2-popup.pelanggan-delete-popup .swal2-actions {
  gap: 0.75rem;
  margin-top: 1.5rem;
}

.customer-avatar {
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
  min-width: 180px;
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

.customer-header-info {
  text-align: center;
  padding: 1.5rem;
  background: #fafafa;
  border-radius: 10px;
  margin-bottom: 1.5rem;
  border: 1px solid #e4e4e7;
}

.customer-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: #18181b;
  margin-bottom: 0.5rem;
}

.customer-id {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  background: #18181b !important;
  color: white;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.875rem;
  box-shadow: 0 2px 8px rgba(24, 24, 27, 0.3);
}

.ktp-preview {
  max-width: 100%;
  border-radius: 8px;
  border: 1px solid #e4e4e7;
  margin-top: 0.5rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.progress-flow {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  margin-top: 0.25rem;
  flex-wrap: wrap;
}

.progress-dot {
  width: 24px;
  height: 24px;
  border-radius: 999px;
  border: 2px solid #d4d4d8;
  color: #a1a1aa;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.72rem;
  font-weight: 700;
  background: #fff;
  flex-shrink: 0;
}

.progress-dot.done {
  border-color: #16a34a;
  color: #16a34a;
}

.progress-dot.current {
  border-color: #16a34a;
  background: #16a34a;
  color: #fff;
}

.progress-line {
  width: 22px;
  height: 2px;
  background: #d4d4d8;
  border-radius: 2px;
}

.progress-line.done {
  background: #16a34a;
}

.progress-caption {
  margin-top: 0.6rem;
  font-size: 0.8rem;
  color: #71717a;
}

/* ========== TEXT COLORS ========== */
.text-primary {
  color: #18181b !important;
}

.text-success {
  color: #22c55e !important;
}

.text-danger {
  color: #dc2626 !important;
}

.text-warning {
  color: #f59e0b !important;
}

.text-muted {
  color: #71717a !important;
}

/* ========== WHATSAPP LINK ========== */
.text-success.text-decoration-none {
  color: #18181b !important;
}

.text-success.text-decoration-none:hover {
  color: #27272a !important;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 768px) {
  .card-header-custom,
  .search-section,
  .pagination-section {
    padding: 1rem 1.25rem;
  }

  .pagination-wrapper {
    flex-direction: column;
    text-align: center;
  }

  .tagihan-action-menu {
    top: calc(100% + 10px) !important;
    right: 0 !important;
    left: auto !important;
    transform: none !important;
    min-width: 210px;
  }

  .tagihan-action-menu::after {
    display: none !important;
  }

  .btn-add {
    width: 100%;
  }

  .search-input-group .input-group {
    flex-wrap: wrap;
  }

  .search-input-group .btn {
    flex: 1 1 100%;
    border-radius: 0 0 8px 8px !important;
    border: 1px solid #18181b !important;
    margin-top: -1px;
  }

  .detail-label {
    min-width: 120px;
    font-size: 0.8rem;
  }

  .detail-value {
    font-size: 0.8rem;
  }

  .modal-body {
    padding: 1rem;
    max-height: 75vh;
  }

  .modal-dialog {
    margin: 0.5rem;
  }

  .detail-item {
    flex-direction: column;
    gap: 0.35rem;
    align-items: flex-start;
  }

  .detail-label {
    min-width: 0;
  }

  .progress-dot {
    width: 28px;
    height: 28px;
    font-size: 0.78rem;
  }

  .progress-line {
    width: 18px;
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

  .customer-avatar {
    width: 72px;
    height: 72px;
    font-size: 1.75rem;
  }

  .customer-name {
    font-size: 1.1rem;
  }

  .customer-id {
    font-size: 0.78rem;
    padding: 0.4rem 0.9rem;
  }

  .detail-section {
    padding: 0.9rem;
    margin-bottom: 0.9rem;
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

/* Hide duplicate pagination summary from Laravel Links */
.pagination-wrapper nav .text-muted {
    display: none !important;
}

/* ========================================= */
/* EXACT PAGINATION STYLES (MATCH OUTSTANDING) */
/* ========================================= */
.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  background: #fafafa;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
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

/* Ensure DataTables info is hidden */
.dataTables_info {
    display: none !important;
}

/* Custom Hover for Outline Button - White Icon, Black BG, No Shadow */
.btn-outline-primary:hover {
    background-color: #18181b !important;
    color: #ffffff !important;
    border-color: #18181b !important;
    box-shadow: none !important;
}
.btn-outline-primary:hover i {
    color: #ffffff !important;
}

/* ========== STAT CARDS ========== */
.stat-cards {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid var(--gray-border);
  background: var(--gray-bg);
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.25rem;
  background: #fff;
  border: 1px solid var(--gray-border);
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.2s ease;
  text-decoration: none !important;
  color: inherit !important;
}

.stat-card:hover {
  border-color: #18181b;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transform: translateY(-1px);
}

.stat-card.active {
  background: #18181b;
  border-color: #18181b;
  color: #fff !important;
}

.stat-card.active .stat-icon {
  background: rgba(255,255,255,0.15);
  color: #fff;
}

.stat-card.active .stat-label {
  color: #a1a1aa;
}

.stat-card.active .stat-value {
  color: #fff;
}

.stat-icon {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  flex-shrink: 0;
}

.stat-icon.total {
  background: #f4f4f5;
  color: #18181b;
}

.stat-icon.approve {
  background: #dcfce7;
  color: #16a34a;
}

.stat-icon.pending {
  background: #fef3c7;
  color: #d97706;
}

.stat-label {
  font-size: 0.75rem;
  color: #71717a;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.025em;
}

.stat-value {
  font-size: 1.375rem;
  font-weight: 700;
  color: #18181b;
  line-height: 1.2;
}

@media (max-width: 768px) {
  .stat-cards {
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
    padding: 0.75rem 1rem;
  }
  .stat-card {
    flex-direction: column;
    text-align: center;
    gap: 0.5rem;
    padding: 0.75rem 0.5rem;
  }
  .stat-icon {
    width: 36px;
    height: 36px;
    font-size: 1rem;
  }
  .stat-value {
    font-size: 1.125rem;
  }
  .stat-label {
    font-size: 0.625rem;
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
@if (env('ENABLE_ONESIGNAL', false) && config('services.onesignal.app_id'))
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
    window.OneSignal = window.OneSignal || [];
    OneSignal.push(function() {
        OneSignal.init({
            appId: "{{ config('services.onesignal.app_id') }}",
            safari_web_id: "{{ env('ONESIGNAL_SAFARI_WEB_ID', '') }}",
            allowLocalhostAsSecureOrigin: true,
        });

        OneSignal.on('subscriptionChange', function (isSubscribed) {
            if (isSubscribed) {
                OneSignal.getUserId(function(player_id) {
                    fetch('/pelanggan/save-player-id', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ player_id })
                    });
                });
            }
        });
    });
</script>
@endif

<script>
document.addEventListener("DOMContentLoaded", function() {
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
        toast.innerHTML = `<i class="ri-${isError ? 'error-warning-fill' : 'checkbox-circle-fill'}" style="font-size:1.15rem;line-height:1;color:${isError ? '#fecaca' : '#86efac'}"></i><span>${message}</span>`;
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


    // ? HANYA INISIALISASI DATATABLES JIKA ADA DATA
    @if($pelanggan->count() > 0)
        const dtUserTable = $('.datatables-users').DataTable({
            paging: false,
            searching: false,
            ordering: true,
            info: false,
            responsive: false,
            dom: 'rt',
            columnDefs: [
              { orderable: false, targets: [0, 1, -1] }
            ],
            language: {
                emptyTable: "Tidak ada data pelanggan tersedia",
                zeroRecords: "Tidak ditemukan data yang sesuai"
            }
        });
    @endif

    // Clear search button
    $('#clearSearch').on('click', function(e) {
        e.preventDefault();
        showLoading();
        window.location.href = "{{ route('pelanggan') }}";
    });

    // Show loading saat submit form
    $('#searchForm').on('submit', function() {
        showLoading();
    });

    // EVENT DETAIL MODAL
    $(document).on('click', '.btn-detail', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const tr = $(this).closest('tr');

        const nomerId = tr.data('nomer-id') || '-';
        const namaLengkap = tr.data('nama') || '-';
        const noWhatsapp = tr.data('whatsapp') || '-';
        const alamatJalan = tr.data('alamat') || '-';
        const rt = tr.data('rt') || '-';
        const rw = tr.data('rw') || '-';
        const kecamatan = tr.data('kecamatan') || '-';
        const kabupaten = tr.data('kabupaten') || '-';
        const tanggalMulai = tr.data('tanggal-mulai') || '-';
        const fotoKtp = tr.data('foto-ktp') || '';
        const status = tr.data('status') || '-';
        const marketingName = tr.data('marketing-name') || 'Sistem';
        const marketingEmail = tr.data('marketing-email') || '-';
        const createdAt = tr.data('created-at') || '-';
        const progressNote = tr.attr('data-progress-note') || '-';
        const rawProgres = tr.data('progres') || '';
        const initial = namaLengkap ? namaLengkap.charAt(0).toUpperCase() : '?';
        const statusLower = status.toLowerCase();
        const progres = rawProgres || (statusLower === 'approve' ? 'Registrasi' : 'Belum Diproses');

        let statusBadge = '';
        if (statusLower === 'approve') {
            statusBadge = '<span class="badge bg-success">Approve</span>';
        } else if (statusLower === 'pending' || statusLower === 'proses') {
            statusBadge = '<span class="badge bg-warning">Progress</span>';
        } else if (statusLower === 'reject') {
            statusBadge = '<span class="badge bg-danger">Reject</span>';
        } else {
            statusBadge = '<span class="badge bg-secondary">' + status + '</span>';
        }

        const progressStages = [
            { value: 'Belum Diproses', label: 'Belum Diproses' },
            { value: 'Tarik Kabel', label: 'Tarik Kabel' },
            { value: 'Aktivasi', label: 'Aktivasi' },
            { value: 'Registrasi', label: 'Register' }
        ];
        const currentStageIndex = progressStages.findIndex(stage => stage.value === progres);
        const isApproved = statusLower === 'approve';
        const currentStageLabel = (progressStages.find(stage => stage.value === progres)?.label) || 'Belum Diproses';
        const progressFlowHtml = `
            <div class="progress-flow">
                ${progressStages.map((stage, i) => {
                    const done = isApproved || (currentStageIndex !== -1 && i < currentStageIndex);
                    const current = !isApproved && currentStageIndex !== -1 && i === currentStageIndex;
                    const dotClass = done ? 'done' : (current ? 'current' : '');
                    const dotValue = done ? '<i class="ri-check-line"></i>' : (i + 1);
                    const lineClass = done ? 'done' : '';
                    const line = i < progressStages.length - 1 ? `<div class="progress-line ${lineClass}"></div>` : '';
                    return `<div class="progress-dot ${dotClass}" title="${stage.label}">${dotValue}</div>${line}`;
                }).join('')}
            </div>
            <div class="progress-caption">Alur: Belum Diproses -> Tarik Kabel -> Aktivasi -> Register<br>Tahap Saat Ini: <strong>${isApproved ? 'Register' : currentStageLabel}</strong></div>
        `;

        const html = `
            <div class="customer-header-info">
                <div class="customer-avatar mx-auto">
                    ${initial}
                </div>
                <div class="customer-name">${namaLengkap}</div>
                <div class="customer-id">
                    <i class="ri-barcode-line me-2"></i>ID: ${nomerId}
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-user-3-line"></i>Informasi Pribadi</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-id-card-line"></i>No. ID
                    </span>
                    <span class="detail-value"><strong>${nomerId}</strong></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-user-line"></i>Nama Lengkap
                    </span>
                    <span class="detail-value">${namaLengkap}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-whatsapp-line"></i>No. WhatsApp
                    </span>
                    <span class="detail-value">
                        <a href="https://wa.me/${noWhatsapp}" target="_blank" class="text-success text-decoration-none">
                            <strong>${noWhatsapp}</strong> <i class="ri-external-link-line"></i>
                        </a>
                    </span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-map-pin-line"></i>Alamat Lengkap</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-road-map-line"></i>Jalan
                    </span>
                    <span class="detail-value">${alamatJalan}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-community-line"></i>RT / RW
                    </span>
                    <span class="detail-value">${rt} / ${rw}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-building-line"></i>Kecamatan
                    </span>
                    <span class="detail-value">${kecamatan}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-map-2-line"></i>Kabupaten
                    </span>
                    <span class="detail-value">${kabupaten}</span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-calendar-check-line"></i>Informasi Langganan</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-calendar-line"></i>Tanggal Mulai
                    </span>
                    <span class="detail-value">${tanggalMulai}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-shield-check-line"></i>Status
                    </span>
                    <span class="detail-value">${statusBadge}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-git-merge-line"></i>Alur Progres
                    </span>
                    <span class="detail-value">${progressFlowHtml}</span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-file-text-line"></i>Catatan & Deskripsi</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-sticky-note-line"></i>Catatan Progres
                    </span>
                    <span class="detail-value" style="white-space: pre-wrap;">${progressNote}</span>
                </div>
                
            </div>

            <div class="detail-section">
                <h6><i class="ri-user-settings-line"></i>Ditambahkan Oleh</h6>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-user-star-line"></i>Di tambahkan olehs
                    </span>
                    <span class="detail-value">
                        <strong>${marketingName}</strong>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-mail-line"></i>Email
                    </span>
                    <span class="detail-value">${marketingEmail}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">
                        <i class="ri-time-line"></i>Tanggal Input
                    </span>
                    <span class="detail-value">${createdAt}</span>
                </div>
            </div>

            <div class="detail-section">
                <h6><i class="ri-image-line"></i>Foto KTP</h6>
                <div class="text-center">
                    ${fotoKtp ? '<img src="' + fotoKtp + '" class="ktp-preview" alt="Foto KTP">' : '<p class="text-muted">Tidak ada foto KTP</p>'}
                </div>
            </div>
        `;

        $('#detailModal .modal-body').html(html);
        $('#detailModal').modal('show');
    });

    // EVENT DELETE
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: 'Yakin ingin menghapus data pelanggan ini? Data tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: false,
            showCloseButton: false,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f5365c',
            cancelButtonColor: '#8898aa',
            reverseButtons: true,
            allowOutsideClick: false,
            customClass: {
                container: 'swal-tailwind-backdrop pelanggan-delete-backdrop',
                popup: 'swal-tailwind-popup pelanggan-delete-popup',
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
                const btn = $(form).find('.btn-delete');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');
                showLoading();
                form.submit();
            }
        });
    });

    const denseToggle = document.getElementById('densePaddingToggle');

    // BULK DELETE CHECKBOX LOGIC
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const selectedCount = document.getElementById('selectedCount');
    const btnBulkDelete = document.getElementById('btnBulkDelete');

    function updateBulkActionBar() {
        if (!bulkActionBar || !selectedCount) return;
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        selectedCount.textContent = checkedCount;
        
        if (checkedCount > 0) {
            bulkActionBar.classList.add('show');
        } else {
            bulkActionBar.classList.remove('show');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkActionBar();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (selectAll) {
                const allChecked = document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length;
                selectAll.checked = allChecked;
            }
            updateBulkActionBar();
        });
    });

    if (btnBulkDelete) {
        btnBulkDelete.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Hapus Pelanggan Terpilih?',
                text: `Anda akan menghapus ${selectedIds.length} pelanggan. Data tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    container: 'swal-tailwind-backdrop pelanggan-delete-backdrop',
                    popup: 'swal-tailwind-popup pelanggan-delete-popup',
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
                    
                    // Lakukan request ke route bulk delete jika ada, 
                    // atau loop request delete biasa (sebaiknya dibuat endpoint khusus).
                    // Contoh menggunakan loop delete karena blm ada endpoint khusus bulk-delete di web.php:
                    let deletePromises = selectedIds.map(id => {
                        return fetch(`/dashboard/admin/pelanggan/delete/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ _method: 'DELETE' })
                        });
                    });

                    Promise.all(deletePromises)
                        .then(() => {
                            hideLoading();
                            window.location.reload();
                        })
                        .catch(err => {
                            hideLoading();
                            console.error(err);
                            Swal.fire('Error', 'Terjadi kesalahan saat menghapus data.', 'error');
                        });
                }
            });
        });
    }
    const tableEl = document.querySelector('.table-modern');
    if (denseToggle && tableEl) {
        const savedDense = localStorage.getItem('pelanggan_dense_padding') === '1';
        denseToggle.checked = savedDense;
        tableEl.classList.toggle('is-dense', savedDense);

        denseToggle.addEventListener('change', function() {
            const isDense = denseToggle.checked;
            tableEl.classList.toggle('is-dense', isDense);
            localStorage.setItem('pelanggan_dense_padding', isDense ? '1' : '0');
        });
    }

    document.querySelectorAll('.table-modern .dropdown').forEach(dropdown => {
        const button = dropdown.querySelector('[data-bs-toggle="dropdown"]');
        const menu = dropdown.querySelector('.tagihan-action-menu');
        if (!button || !menu) return;

        dropdown.addEventListener('shown.bs.dropdown', () => {
            const buttonRect = button.getBoundingClientRect();
            const menuWidth = menu.offsetWidth || 230;
            const menuHeight = menu.offsetHeight || 280;
            const gap = 12;
            const padding = window.innerWidth < 768 ? 8 : 16;
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
            menu.style.setProperty('height', 'auto', 'important');
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
            menu.style.removeProperty('height');
            menu.style.removeProperty('transform');
            menu.style.removeProperty('--action-menu-arrow-top');
            menu.style.removeProperty('--action-menu-arrow-right');
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
                    <i class="ri-user-star-line me-2 text-primary"></i>Data Pelanggan
                </h4>
                <p class="mb-0 text-muted small">Kelola dan monitor data pelanggan</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="/pelanggan/export" class="btn btn-outline-primary btn-add">
                    <i class="ri-file-excel-2-line me-2"></i>Export Excel
                </a>
                <a href="{{ route('pelanggan.export.progress', request()->only(['search', 'status'])) }}" class="btn btn-outline-warning btn-add">
                    <i class="ri-file-list-3-line me-2"></i>Export Progress
                </a>
                <a href="{{ route('add-pelanggan') }}" class="btn btn-primary btn-add">
                    <i class="ri-user-add-line me-2"></i>Tambah Pelanggan
                </a>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="stat-cards">
        <a href="{{ route('pelanggan') }}" class="stat-card {{ !$statusFilter ? 'active' : '' }}">
            <div class="stat-icon total">
                <i class="ri-group-line"></i>
            </div>
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $countTotal }}</div>
            </div>
        </a>
        <a href="{{ route('pelanggan', ['status' => 'approve']) }}" class="stat-card {{ $statusFilter === 'approve' ? 'active' : '' }}">
            <div class="stat-icon approve">
                <i class="ri-checkbox-circle-line"></i>
            </div>
            <div>
                <div class="stat-label">Approve</div>
                <div class="stat-value">{{ $countApprove }}</div>
            </div>
        </a>
        <a href="{{ route('pelanggan', ['status' => 'proses']) }}" class="stat-card {{ $statusFilter === 'proses' ? 'active' : '' }}">
            <div class="stat-icon pending">
                <i class="ri-time-line"></i>
            </div>
            <div>
                <div class="stat-label">Progress</div>
                <div class="stat-value">{{ $countPending }}</div>
            </div>
        </a>
    </div>

    {{-- SEARCH SECTION --}}
    <div class="search-section">
        <form action="{{ route('pelanggan') }}" method="GET" id="searchForm">
            @if($statusFilter)
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            @endif
            <div class="search-input-group">
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="ri-search-line text-primary"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari berdasarkan ID, Nama, No. WA, Alamat, RT/RW, Kecamatan, Kabupaten, atau Status..."
                        value="{{ request('search') }}"
                        id="searchInput"
                        autocomplete="off"
                    >
                    @if(request('search'))
                    <button type="button" class="btn btn-clear-search" id="clearSearch" title="Hapus Pencarian">
                        <i class="ri-close-line"></i>
                    </button>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-search-2-line me-1"></i>Cari
                    </button>
                </div>
            </div>

            @if(request('search'))
            <div class="search-info-box">
                <div>
                    <i class="ri-filter-3-line text-primary me-2"></i>
                    <small class="text-muted">
                        Hasil pencarian: <span class="search-keyword">"{{ request('search') }}"</span>
                    </small>
                </div>
                <a href="{{ route('pelanggan') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ri-refresh-line me-1"></i>Reset
                </a>
            </div>
            @endif
        </form>
    </div>

    {{-- BULK ACTION BAR --}}
    <div class="bulk-action-bar" id="bulkActionBar">
        <div class="selected-count">
            <span id="selectedCount">0</span> dipilih
        </div>
        <button type="button" class="btn-bulk-delete" id="btnBulkDelete">
            <i class="ri-delete-bin-line"></i>
        </button>
    </div>

    {{-- TABLE SECTION --}}
    <div class="card-body p-0">
        <div class="table-responsive p-0">
            @if($pelanggan->count() > 0)
                <table class="datatables-users table table-modern table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="mui-checkbox" id="selectAll" title="Pilih Semua"></th>
                            <th>No. ID</th>
                            <th>Nama Lengkap</th>
                            <th>No. WhatsApp</th>
                            <th>Alamat</th>
                            <th>Tanggal Input</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pelanggan as $p)
                        <tr
                            data-id="{{ $p->id }}"
                            data-nomer-id="{{ $p->nomer_id }}"
                            data-nama="{{ $p->nama_lengkap }}"
                            data-whatsapp="{{ $p->no_whatsapp }}"
                            data-alamat="{{ $p->alamat_jalan }}"
                            data-rt="{{ $p->rt }}"
                            data-rw="{{ $p->rw }}"
                            data-kecamatan="{{ $p->kecamatan }}"
                            data-kabupaten="{{ $p->kabupaten }}"
                            data-tanggal-mulai="{{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}"
                            data-foto-ktp="{{ $p->foto_ktp ? asset('storage/' . $p->foto_ktp) : '' }}"
                            data-status="{{ ucfirst($p->status ?? '-') }}"
                            data-progres="{{ $p->progres ?? (strtolower($p->status ?? '') === 'approve' ? 'Registrasi' : \App\Models\Pelanggan::PROGRES_BELUM_DIPROSES) }}"
                            data-marketing-name="{{ $p->user->name ?? 'Admin' }}"
                            data-marketing-email="{{ $p->user->email ?? '-' }}"
                            data-created-at="{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y H:i') }}"
                            data-progress-note="{{ $p->progress_note }}"
                        >
                            <td><input type="checkbox" class="mui-checkbox row-checkbox" value="{{ $p->id }}"></td>
                            <td>
                                <span class="badge bg-label-dark">{{ $p->nomer_id }}</span>
                            </td>

                            <td>
                                <span class="fw-semibold">{{ $p->nama_lengkap }}</span>
                            </td>

                            <td>
                                <a href="https://wa.me/{{ $p->no_whatsapp }}" target="_blank" class="text-success text-decoration-none">
                                    <i class="ri-whatsapp-line me-1"></i>{{ $p->no_whatsapp }}
                                </a>
                            </td>

                            <td>
                                {{ Str::limit($p->alamat_jalan, 30) }}<br>
                                <small class="text-muted">RT {{ $p->rt }}/RW {{ $p->rw }}, {{ $p->kecamatan }}</small>
                            </td>

                            <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y H:i') }}</td>

                            <td>
                                @php
                                  $statusClass = match(strtolower($p->status ?? '')) {
                                      'reject' => 'badge bg-danger',
                                      'pending', 'proses' => 'badge bg-warning',
                                      'approve' => 'badge bg-success',
                                      default => 'badge bg-secondary',
                                  };
                                @endphp
                                <span class="{{ $statusClass }}">{{ in_array(strtolower($p->status ?? ''), ['pending', 'proses']) ? 'Progress' : ucfirst($p->status ?? '-') }}</span>
                            </td>

                            <td class="text-center">
                                <div class="dropdown d-inline-block">
                                    <button class="action-btn" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" data-bs-boundary="window">
                                        <i class="ri-more-2-fill"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end tagihan-action-menu">
                                        <li>
                                            <button class="dropdown-item btn-detail" type="button">
                                                <i class="ri-eye-line"></i>Detail
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('pelanggan.edit', $p->id) }}">
                                                <i class="ri-edit-2-line"></i>Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider my-1"></li>
                                        <li>
                                            <form action="{{ route('pelanggan.delete', $p->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger btn-delete w-100 border-0 bg-transparent text-start">
                                                    <i class="ri-delete-bin-line"></i>Delete
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
            @else
                {{-- EMPTY STATE --}}
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="ri-inbox-line"></i>
                    </div>
                    @if(request('search'))
                        <h5>Tidak ada hasil untuk "{{ request('search') }}"</h5>
                        <p>Coba gunakan kata kunci lain atau ubah filter pencarian</p>
                        <a href="{{ route('pelanggan') }}" class="btn btn-outline-primary">
                            <i class="ri-refresh-line me-2"></i>Reset Pencarian
                        </a>
                    @else
                        <h5>Belum ada data pelanggan</h5>
                        <p>Mulai tambahkan pelanggan baru untuk mengelola data</p>
                        <a href="{{ route('add-pelanggan') }}" class="btn btn-primary">
                            <i class="ri-user-add-line me-2"></i>Tambah Pelanggan Pertama
                        </a>
                    @endif
                </div>
            @endif
        </div>

        @if($pelanggan->count() > 0)
        <div class="pagination-wrapper">
          <label class="dense-toggle-wrap mb-0">
            <input type="checkbox" id="densePaddingToggle">
            <span>Dense padding</span>
          </label>
          <div>
            {{ $pelanggan->appends(request()->query())->onEachSide(1)->links('pagination.mui') }}
          </div>
        </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
              <div class="modal-header bg-primary py-4">
        <h5 class="modal-title text-white fw-bold">
          <i class="ri-information-line me-2"></i>Detail Pelanggan
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
