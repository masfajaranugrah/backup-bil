@extends('layouts/layoutMaster')

@section('title', 'Daftar Tagihan')

@section('vendor-style')
@vite([
  'resources/css/app.css',
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
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
  --success-color: #18181b;
}

/* Card Design */
.card {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--card-shadow);
  transition: var(--transition);
  overflow: hidden;
}

.card:hover {
  box-shadow: var(--card-hover-shadow);
}

/* Dashboard Cards with Border Accent */
.card-border-shadow-primary::before,
.card-border-shadow-success::before,
.card-border-shadow-warning::before,
.card-border-shadow-info::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
}

.card-border-shadow-primary::before {
  background: #18181b;
}

.card-border-shadow-success::before {
  background: #18181b;
}

.card-border-shadow-warning::before {
  background: #f59e0b;
}

.card-border-shadow-info::before {
  background: #18181b;
}

/* Stats Card */
.stats-card {
  border-radius: var(--border-radius);
  padding: 1.5rem;
  background: #fff;
  border: 1px solid #e4e4e7;
  transition: var(--transition);
}

.stats-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
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

/* Avatar */
.avatar-initial {
  border-radius: 12px;
  transition: var(--transition);
  background: #18181b !important;
  color: #fafafa !important;
}

.card:hover .avatar-initial {
  transform: scale(1.05);
}

/* Neutralize accent labels - shadcn style (gray background) */
.bg-label-primary,
.bg-label-success,
.bg-label-warning,
.bg-label-dark,
.bg-label-secondary {
  background: #f4f4f5 !important;
  color: #18181b !important;
  border: 1px solid #e4e4e7 !important;
}

/* Badge Paket - Black background, white text */
.bg-label-info {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
  border-radius: 9999px !important;
}

/* Stats icon - gray background */
.stats-icon.bg-label-primary,
.stats-icon.bg-label-success,
.stats-icon.bg-label-warning,
.stats-icon.bg-label-info {
  background: #f4f4f5 !important;
  color: #18181b !important;
}

.bg-label-danger {
  background: #dc2626 !important;
  color: #fafafa !important;
}

.bg-label-warning {
  background: #f59e0b !important;
  color: #fafafa !important;
}

/* ========================================= */
/* BUTTONS - ALL BLACK */
/* ========================================= */
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
  transform: none !important;
}

.btn-success,
.btn.btn-success {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
  box-shadow: none !important;
}

.btn-success:hover,
.btn.btn-success:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn-warning,
.btn.btn-warning {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
  box-shadow: none !important;
}

.btn-warning:hover,
.btn.btn-warning:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
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

.btn-sm {
  padding: 0.375rem 0.75rem !important;
  font-size: 0.8125rem !important;
}

/* Outline Buttons */
.btn-outline-primary,
.btn-outline-secondary,
.btn-outline-success,
.btn.btn-outline-primary,
.btn.btn-outline-secondary,
.btn.btn-outline-success {
  background: transparent !important;
  background-color: transparent !important;
  border: 1px solid #e4e4e7 !important;
  color: #18181b !important;
}

.btn-outline-primary:hover,
.btn-outline-secondary:hover,
.btn-outline-success:hover,
.btn.btn-outline-primary:hover,
.btn.btn-outline-secondary:hover,
.btn.btn-outline-success:hover {
  background: #f4f4f5 !important;
  background-color: #f4f4f5 !important;
  border-color: #18181b !important;
  color: #18181b !important;
}

/* ========================================= */
/* BADGES - SHADCN STYLE */
/* ========================================= */
.badge {
  border-radius: 9999px !important;
  font-weight: 500 !important;
  letter-spacing: 0 !important;
  display: inline-flex !important;
  align-items: center !important;
  gap: 0.25rem !important;
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

.badge.bg-primary,
.bg-primary:not(.btn):not(.modal-header) {
  background: #18181b !important;
  color: #fafafa !important;
}

.badge.bg-info,
.bg-info:not(.btn) {
  background: #18181b !important;
  color: #fafafa !important;
}

/* Form Controls */
.form-select, .form-control {
  border-radius: 8px;
  border: 1px solid #e4e4e7;
  padding: 0.625rem 1rem;
  transition: var(--transition);
}

.form-select:focus, .form-control:focus {
  border-color: #18181b;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b;
}

/* Modal - Black Header */
.modal-content {
  border-radius: 16px;
  border: none;
  box-shadow: 0 8px 32px rgba(0,0,0,0.15);
}

.modal-header {
  border-radius: 16px 16px 0 0;
  padding: 1.5rem;
  border-bottom: none;
  background: #18181b !important;
  color: #fafafa !important;
}

.modal-header.bg-primary,
.modal-header.bg-warning,
.modal-header.bg-success,
.modal-header.bg-info {
  background: #18181b !important;
  color: #fafafa !important;
}

.modal-header .modal-title,
.modal-header h5,
.modal-header .btn-close {
  color: #fafafa !important;
}

.modal-header .btn-close {
  filter: brightness(0) invert(1) !important;
}

.modal-body {
  padding: 2rem;
  max-height: 70vh;
  overflow-y: auto;
}

#detailModal .modal-body,
[id^="modalEditTagihan-"] .modal-body {
  max-height: none;
}

#modalTambahTagihan .modal-content,
[id^="modalEditTagihan-"] .modal-content,
#modalMassTagihan .modal-content {
  height: 100vh;
  display: flex;
  flex-direction: column;
}

#modalTambahTagihan form,
[id^="modalEditTagihan-"] form,
#modalMassTagihan form {
  display: flex;
  flex-direction: column;
  height: 100%;
}

#modalTambahTagihan .modal-body,
[id^="modalEditTagihan-"] .modal-body,
#modalMassTagihan .modal-body {
  flex: 1 1 auto;
  overflow-y: auto;
  max-height: none;
}

#modalTambahTagihan .modal-footer,
[id^="modalEditTagihan-"] .modal-footer,
#modalMassTagihan .modal-footer {
  flex-shrink: 0;
}

.modal-footer {
  padding: 1.5rem;
  border-top: 1px solid #e4e4e7;
  background: #fafafa;
}

.btn-close-white {
  filter: brightness(0) invert(1);
}

/* Modal Backdrop */
.modal-backdrop {
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

.modal-backdrop.show {
  opacity: 0.4;
  background-color: #18181b;
}

/* Table */
.table {
  border-collapse: separate;
  border-spacing: 0;
}

.modern-table {
  width: 100%;
  min-width: 0;
  table-layout: fixed;
  border-collapse: separate;
  border-spacing: 0;
}

.modern-table thead th {
  background: #f8fafc;
  border: none;
  padding: 1.1rem 0.95rem !important;
  font-weight: 800;
  color: #667085;
  font-size: 0.82rem;
  text-transform: uppercase;
  letter-spacing: 0;
  white-space: nowrap;
  border-bottom: 1px solid #e2e8f0 !important;
}

.modern-table tbody tr {
  transition: var(--transition);
}

.modern-table tbody tr:not(.empty-state-row):hover {
  background: #f4f4f5;
}

.modern-table tbody td {
  padding: 0.85rem 0.95rem;
  border-bottom: 1px dashed #d8e1ec;
  vertical-align: middle;
  color: #18181b;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.modern-table th:first-child,
.modern-table td:first-child {
  text-align: center;
}

.modern-table th:last-child,
.modern-table td:last-child {
  text-align: center;
  overflow: visible;
}

.modern-table tbody tr:last-child td {
  border-bottom: none;
}

/* Empty State */
.empty-state-row td {
  background: #fafbfc !important;
  border: none !important;
}

.empty-state-content {
  padding: 3rem 1rem;
}

/* Loading Overlay */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(24, 24, 27, 0.5);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  backdrop-filter: blur(4px);
}

/* ========================================= */
/* DETAIL MODAL STYLES */
/* ========================================= */
.customer-header-info {
  text-align: center;
  padding: 1.5rem;
  background: #f4f4f5;
  border-radius: 12px;
  margin-bottom: 1.5rem;
  border: 1px solid #e4e4e7;
}

.customer-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: #18181b !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: #fafafa !important;
  font-weight: 700;
  font-size: 2.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 4px 16px rgba(24, 24, 27, 0.3);
  border: 4px solid white;
}

.customer-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: #18181b;
  margin-bottom: 0.5rem;
}

.customer-status {
  display: inline-block;
  padding: 0.5rem 1.5rem;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.875rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.detail-section {
  background: #ffffff;
  border: 1px solid #e4e4e7;
  border-radius: 12px;
  padding: 1.25rem;
  margin-bottom: 1.25rem;
  transition: all 0.2s;
}

.detail-section:hover {
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  border-color: #18181b;
}

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

.detail-section h6 i {
  margin-right: 0.5rem;
  font-size: 1.1rem;
}

.detail-item {
  display: flex;
  padding: 0.875rem 0;
  border-bottom: 1px solid #e4e4e7;
  align-items: flex-start;
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
  color: #a1a1aa;
  font-size: 1rem;
}

.detail-value {
  color: #18181b;
  font-size: 0.875rem;
  flex: 1;
  word-break: break-word;
}

/* Card Header */
.card-header {
  background: transparent;
  padding: 1.5rem;
  border-bottom: 1px solid #e4e4e7;
}

.card-header-custom {
  background: #ffffff !important;
  color: #18181b !important;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
  padding: 1.5rem;
  border-bottom: 1px solid #e4e4e7;
}

.card-header-custom h4,
.card-header-custom h5,
.card-header-custom p,
.card-header-custom i,
.card-header-custom small {
  color: #18181b !important;
}

.card-header-custom .opacity-75 {
  color: #71717a !important;
}

/* Input Groups */
.input-group-text {
  border-radius: 8px 0 0 8px;
  background: #f4f4f5;
  border: 1px solid #e4e4e7;
  color: #18181b;
  font-weight: 500;
}

/* ========================================= */
/* PAGINATION STYLES */
/* ========================================= */
.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.85rem 1.1rem;
  border-top: 1px solid #e4e4e7;
  background: #fff;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.pagination-wrapper .pagination {
  margin: 0;
  flex-wrap: nowrap;
  gap: 0.15rem;
}

.pagination-wrapper .page-link {
  width: 30px !important;
  min-width: 30px !important;
  max-width: 30px !important;
  height: 30px !important;
  min-height: 30px !important;
  max-height: 30px !important;
  border-radius: 50% !important;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 0 !important;
  color: #1f2937 !important;
  font-size: 0.9rem;
  font-weight: 700;
  background: transparent !important;
  box-shadow: none !important;
}

.pagination-wrapper .mui-pagination {
  align-items: center;
  gap: 0.3rem;
}

.pagination-wrapper .mui-pagination .page-link.page-nav-icon {
  font-size: 1.1rem;
  font-weight: 600;
  line-height: 1;
}

.pagination-wrapper .mui-pagination .page-item.active .page-link {
  background: #1f2933 !important;
  color: #fff !important;
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
  letter-spacing: 0;
}

.pagination-info {
  color: #71717a;
  font-size: 0.875rem;
  font-weight: 500;
}

/* Hide DataTables default controls */
.dataTables_info,
.dataTables_paginate,
.dataTables_length,
.dataTables_filter,
.dataTables_scrollHead,
.dataTables_scrollFoot {
  display: none !important;
}

.dataTables_scrollBody {
  border: 0 !important;
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

/* Text colors */
.text-primary {
  color: #18181b !important;
}

.text-success {
  color: #18181b !important;
}

.text-info {
  color: #18181b !important;
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

/* Custom Checkbox */
.outstanding-checkbox {
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
.outstanding-checkbox:checked {
  background: #0f172a;
  border-color: #0f172a;
}
.outstanding-checkbox:checked::after {
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

.outstanding-selection-toolbar {
  position: sticky;
  top: 0;
  z-index: 8;
  display: none;
  align-items: center;
  justify-content: space-between;
  padding: 0.85rem 1.25rem;
  margin-bottom: 0;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
}

.outstanding-selection-toolbar.active {
  display: flex;
}

.outstanding-selection-toolbar .selected-text {
  font-weight: 700;
  color: #0f172a;
  font-size: 1rem;
}

.outstanding-selection-toolbar .delete-selected-btn {
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
  transition: background 0.15s, color 0.15s;
}

.outstanding-selection-toolbar .delete-selected-btn:hover {
  background: #fee2e2;
  color: #ef4444;
}

.action-btn {
  border: 0;
  background: transparent;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  color: #94a3b8;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

.action-btn:hover {
  background: #eef2f7;
  color: #475569;
}

.outstanding-table-wrap {
  overflow: visible !important;
}

.outstanding-table-wrap .dropdown {
  position: relative;
  display: inline-flex;
}

.outstanding-table-wrap .dropdown-menu {
  z-index: 1200;
}

.tagihan-action-menu {
  position: absolute !important;
  top: 50% !important;
  right: calc(100% + 14px) !important;
  left: auto !important;
  transform: translateY(-50%) !important;
  min-width: 250px;
  padding: 10px;
  border: 1px solid #d7e2ee;
  border-radius: 22px;
  background: linear-gradient(110deg, #fff4f2 0%, #edf7ff 100%);
  box-shadow: 0 18px 36px rgba(15, 23, 42, 0.14);
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
  gap: 12px;
}

.tagihan-action-menu .dropdown-item:hover,
.tagihan-action-menu .dropdown-item:focus {
  background: rgba(255, 255, 255, 0.78);
  color: #1e293b;
}

.tagihan-action-menu .danger-action,
.tagihan-action-menu .danger-action:hover,
.tagihan-action-menu .danger-action:focus {
  color: #ff3b30;
}

.outstanding-toast {
  position: fixed;
  right: 24px;
  bottom: 24px;
  transform: translateY(20px);
  background: #061533;
  color: #fff;
  border-radius: 34px;
  padding: 16px 24px;
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
  width: min(90vw, 420px) !important;
  border-radius: 20px !important;
  padding: 2rem 1.5rem 1.5rem !important;
  box-shadow: 0 25px 60px rgba(0,0,0,0.18) !important;
  border: none !important;
}

.swal-outstanding-delete .swal2-title {
  color: #18181b !important;
  font-weight: 700 !important;
  font-size: 1.3rem !important;
  line-height: 1.3 !important;
  margin-top: 0.2rem !important;
}

.swal-outstanding-confirm {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
  color: #fff !important;
  border: none !important;
  border-radius: 12px !important;
  padding: 0.65rem 1.5rem !important;
  font-size: 0.875rem !important;
  font-weight: 600 !important;
  box-shadow: 0 4px 15px rgba(239,68,68,0.35) !important;
  transition: all 0.2s !important;
  margin: 0 0.35rem !important;
  min-width: unset !important;
  min-height: unset !important;
}

.swal-outstanding-confirm:hover {
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
  box-shadow: 0 6px 20px rgba(239,68,68,0.45) !important;
  transform: translateY(-1px) !important;
}

.swal-outstanding-cancel {
  background: #f4f4f5 !important;
  color: #52525b !important;
  border: 1px solid #e4e4e7 !important;
  border-radius: 12px !important;
  padding: 0.65rem 1.5rem !important;
  font-size: 0.875rem !important;
  font-weight: 600 !important;
  transition: all 0.2s !important;
  margin: 0 0.35rem !important;
  min-width: unset !important;
  min-height: unset !important;
}

.swal-outstanding-cancel:hover {
  background: #e4e4e7 !important;
  color: #18181b !important;
}

.swal2-html-container.swal-outstanding-html {
  color: #52525b !important;
  font-size: 0.9rem !important;
  line-height: 1.6 !important;
  margin-top: 0.2rem !important;
  margin-bottom: 0.5rem !important;
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.card {
  animation: fadeIn 0.3s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
  .modal-body {
    padding: 1.5rem;
  }
  .card-body {
    padding: 1.25rem;
  }
  .pagination-wrapper {
    flex-direction: column;
    gap: 1rem;
    text-align: center;
  }
}

/* Dense Padding Toggle */
.datatables-users.is-dense thead th {
  padding: 0.5rem 0.75rem !important;
}
.datatables-users.is-dense tbody td {
  padding: 0.5rem 0.75rem !important;
}

.datatables-users thead th {
  padding-top: 1.1rem !important;
  padding-bottom: 1.1rem !important;
}

.datatables-users tbody td strong {
  font-weight: 700;
  color: #5b6276;
}

.datatables-users tbody .badge.bg-label-dark {
  border: 1px solid #d1d5db;
  background: #f3f4f6 !important;
  color: #2b2f3a !important;
  font-weight: 700;
}

.datatables-users tbody code {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  width: 100%;
  max-width: 230px;
  justify-content: center;
  letter-spacing: 0.01em;
}

.datatables-users tbody .badge.bg-danger,
.datatables-users tbody .badge.bg-warning,
.datatables-users tbody .badge.bg-success,
.datatables-users tbody .badge.bg-secondary {
  background: #eef2f7 !important;
  border: 0 !important;
  color: #49556b !important;
  font-weight: 700;
  border-radius: 12px !important;
  padding: 0.55rem 1.05rem !important;
}

.dense-toggle-wrap {
  display: inline-flex;
  align-items: center;
  gap: 0.55rem;
}

.dense-toggle-wrap input[type="checkbox"] {
  appearance: none;
  width: 22px;
  height: 22px;
  border: 2px solid #9ca3af;
  border-radius: 7px;
  background: #fff;
  cursor: pointer;
  position: relative;
  flex-shrink: 0;
}

.dense-toggle-wrap input[type="checkbox"]:checked {
  background: #0f172a;
  border-color: #0f172a;
}

.dense-toggle-wrap input[type="checkbox"]:checked::after {
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

/* Hide DataTables Sorting Arrows */
.table thead th.sorting::before,
.table thead th.sorting::after,
.table thead th.sorting_asc::before,
.table thead th.sorting_asc::after,
.table thead th.sorting_desc::before,
.table thead th.sorting_desc::after {
  display: none !important;
  content: none !important;
}
.table thead th {
  cursor: default !important;
}

</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
])
@endsection

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ========================================
    // HELPER FUNCTIONS
    // ========================================
    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }

    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    function showOutstandingToast(message) {
        const toast = document.createElement('div');
        toast.className = 'outstanding-toast';
        toast.innerHTML = `<i class="ri-checkbox-circle-line" style="color:#6ee7b7;font-size:1.35rem;"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 250);
        }, 2600);
    }

    function updateOutstandingSelection() {
        const $all = $('.outstanding-checkbox');
        const $checked = $('.outstanding-checkbox:checked');
        const selectedCount = $checked.length;
        $('#outstandingSelectedCount').text(`${selectedCount} dipilih`);
        $('#outstandingSelectionToolbar').toggleClass('active', selectedCount > 0);
        $('#selectAllOutstanding').prop('checked', $all.length > 0 && selectedCount === $all.length);
    }

    $('#selectAllOutstanding').on('change', function () {
        $('.outstanding-checkbox').prop('checked', this.checked);
        updateOutstandingSelection();
    });

    $(document).on('change', '.outstanding-checkbox', updateOutstandingSelection);

    $('#outstandingBulkDeleteBtn').on('click', async function () {
        const $checked = $('.outstanding-checkbox:checked');
        const total = $checked.length;
        if (!total) return;

        const result = await Swal.fire({
            title: 'Hapus Outstanding?',
            html: `Yakin ingin menghapus <strong>${total}</strong> data outstanding?<br><span style="color:#6b7280;font-size:0.875rem;">Data tidak dapat dikembalikan.</span>`,
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
                popup: 'swal-tailwind-popup swal-outstanding-delete',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel',
                htmlContainer: 'swal-outstanding-html'
            }
        });

        if (!result.isConfirmed) return;

        showLoading();
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let successCount = 0;

        for (const checkbox of $checked.toArray()) {
            const $row = $(checkbox).closest('tr');
            const deleteUrl = $row.data('delete-url');
            if (!deleteUrl) continue;

            try {
                await $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    data: { _token: csrfToken, _method: 'DELETE' },
                    headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' }
                });
                $row.remove();
                successCount++;
            } catch (e) {}
        }

        hideLoading();
        $('#selectAllOutstanding').prop('checked', false);
        updateOutstandingSelection();
        if (successCount > 0) {
            showOutstandingToast(`${successCount} data berhasil di delete.`);
        }
    });

    const formatDate = d => d.toISOString().split('T')[0];
    const denseToggleOutstanding = document.getElementById('densePaddingToggleOutstanding');
    const outstandingTable = document.querySelector('.modern-table');
    if (denseToggleOutstanding && outstandingTable) {
        const savedDense = localStorage.getItem('dense_outstanding_tagihan') === '1';
        denseToggleOutstanding.checked = savedDense;
        outstandingTable.classList.toggle('is-dense', savedDense);
        denseToggleOutstanding.addEventListener('change', function () {
            const isDense = denseToggleOutstanding.checked;
            outstandingTable.classList.toggle('is-dense', isDense);
            localStorage.setItem('dense_outstanding_tagihan', isDense ? '1' : '0');
        });
    }

    @if(session('success'))
        showOutstandingToast(@json(session('success')));
    @endif

    // ========================================
    // FLATPICKR INITIALIZATION
    // ========================================
    $(document).on('shown.bs.modal', '[id^="modalEditTagihan-"]', function () {
        flatpickr($(this).find('.flatpickr-edit-start'), {
            dateFormat: "Y-m-d",
            allowInput: true
        });
        flatpickr($(this).find('.flatpickr-edit-end'), {
            dateFormat: "Y-m-d",
            allowInput: true
        });
    });

    flatpickr("#tanggal_mulai", {
        dateFormat: "Y-m-d",
        defaultDate: new Date(),
        allowInput: true
    });

    flatpickr("#tanggal_berakhir", {
        dateFormat: "Y-m-d",
        allowInput: false
    });

    // ========================================
    // SELECT2 PELANGGAN - AJAX MODE
    // ========================================
    $('#pelangganSelect').select2({
        placeholder: '-- Ketik untuk mencari pelanggan --',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#modalTambahTagihan'),
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("pelanggan.search") }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    q: params.term,
                    page: params.page || 1
                };
            },
            processResults: function(data, params) {
                params.page = params.page || 1;
                return {
                    results: data.results,
                    pagination: {
                        more: data.pagination.more
                    }
                };
            },
            cache: true
        },
        language: {
            inputTooShort: function() {
                return 'Ketik minimal 1 karakter untuk mencari...';
            },
            searching: function() {
                return 'Mencari...';
            },
            noResults: function() {
                return 'Tidak ditemukan';
            }
        }
    });

    const tglMulai = document.getElementById('tanggal_mulai');
    if (tglMulai) {
        tglMulai.value = formatDate(new Date());
    }

    function fillFields(selected) {
        if (!selected || !selected.id) {
            $('#nama_lengkap, #alamat_jalan, #rt, #rw, #desa, #kecamatan, #kabupaten, #provinsi, #kode_pos, #no_whatsapp, #nomer_id, #paket, #harga, #masa_pembayaran, #kecepatan, #pelanggan_id, #paket_id, #tanggal_berakhir').val('');
            return;
        }

        // Data dari AJAX response Select2
        $('#nama_lengkap').val(selected.nama || '');
        $('#alamat_jalan').val(selected.alamat_jalan || '');
        $('#rt').val(selected.rt || '');
        $('#rw').val(selected.rw || '');
        $('#desa').val(selected.desa || '');
        $('#kecamatan').val(selected.kecamatan || '');
        $('#kabupaten').val(selected.kabupaten || '');
        $('#provinsi').val(selected.provinsi || '');
        $('#kode_pos').val(selected.kode_pos || '');
        $('#no_whatsapp').val(selected.nowhatsapp || '');
        $('#nomer_id').val(selected.nomorid || '');
        $('#paket').val(selected.paket || '');
        $('#harga').val(selected.harga || '');
        $('#masa_pembayaran').val(selected.masa || '');
        $('#kecepatan').val(selected.kecepatan || '');
        $('#pelanggan_id').val(selected.id);
        $('#paket_id').val(selected.paket_id || '');

        // Hitung tanggal berakhir
        const startDateVal = $('#tanggal_mulai').val();
        if (startDateVal && selected.masa) {
            const startDate = new Date(startDateVal);
            const endDate = new Date(startDate);
            endDate.setDate(startDate.getDate() + parseInt(selected.masa));
            $('#tanggal_berakhir').val(formatDate(endDate));
        }
    }

    // Handler saat pelanggan dipilih dari dropdown
    $('#pelangganSelect').on('select2:select', function(e) {
        fillFields(e.params.data);
    });

    // Handler saat pelanggan di-clear
    $('#pelangganSelect').on('select2:clear', function() {
        fillFields(null);
    });

    if (tglMulai) {
        tglMulai.addEventListener('change', function () {
            // Recalculate tanggal_berakhir jika ada pelanggan yang dipilih
            const selectedData = $('#pelangganSelect').select2('data');
            if (selectedData && selectedData.length > 0 && selectedData[0].id) {
                const data = selectedData[0];
                if (data.masa) {
                    const startDate = new Date($('#tanggal_mulai').val());
                    const endDate = new Date(startDate);
                    endDate.setDate(startDate.getDate() + parseInt(data.masa));
                    $('#tanggal_berakhir').val(formatDate(endDate));
                }
            }
        });
    }

    // Modal shown - focus ke search pelanggan
    $('#modalTambahTagihan').on('shown.bs.modal', function () {
        $('#pelangganSelect').select2('open');
    });

    // Tabel Outstanding memakai server-side pagination; DataTables scrollX membuat header duplikat.

    // ========================================
    // AUTO SUBMIT ON FILTER CHANGE
    // ========================================
    $('#statusFilter').on('change', function() {
        $('#filterForm').submit();
    });

    // ========================================
    // LOADING OVERLAY ON FORM SUBMIT
    // ========================================
    $('#filterForm').on('submit', function() {
        showLoading();
    });

    // ========================================
    // SWEETALERT DELETE
    // ========================================
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const form = this;

        Swal.fire({
            title: 'Hapus Outstanding?',
            html: 'Yakin ingin menghapus tagihan ini?<br><span style="color:#6b7280;font-size:0.875rem;">Data tidak dapat dikembalikan.</span>',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Batal',
            confirmButtonText: '<i class="ri-delete-bin-line"></i> &nbsp;Ya, Hapus',
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup swal-outstanding-delete',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel',
                htmlContainer: 'swal-outstanding-html'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                setTimeout(() => form.submit(), 500);
            }
        });
    });

    // ========================================
    // MASS TAGIHAN
    // ========================================
    $('#modalMassTagihan').on('shown.bs.modal', function () {
        flatpickr(".flatpickr-select-start-all", {
            dateFormat: "Y-m-d",
            defaultDate: new Date(),
            minDate: "today",
            allowInput: true
        });
        flatpickr(".flatpickr-select-start-end", {
            dateFormat: "Y-m-d",
            defaultDate: new Date().fp_incr(7),
            minDate: "today",
            allowInput: true
        });
    });

    // ========================================
    // ? BUTTON DETAIL - SHOW MODAL
    // ========================================
    $(document).on('click', '.btn-detail', function() {
        const $row = $(this).closest('tr');

        // Ambil data dari table cells
        const nomorId = $row.data('nomor-id') || $row.find('.badge.bg-label-dark').text().trim();
        const namaLengkap = $row.data('nama') || $row.find('strong').first().text().trim();
        const noWhatsappDisplay = $row.data('whatsapp') || $row.find('code').text().trim();
        const noWhatsapp = String(noWhatsappDisplay).replace(/\D/g, '');
        const status = $row.data('status-label') || $row.find('td .badge').last().text().trim();
        const paket = $row.data('paket') || '-';
        const harga = $row.data('harga') || '-';

        // Data dari attribute
        const alamat = $row.data('alamat') || '-';
        const kecamatan = $row.data('kecamatan') || '-';
        const kabupaten = $row.data('kabupaten') || '-';
        const provinsi = $row.data('provinsi') || '-';
        const kecepatan = $row.data('kecepatan') || '-';
        const tanggalMulai = $row.data('tanggal-mulai') || '-';
        const jatuhTempo = $row.data('jatuh-tempo') || '-';
        const catatan = $row.data('catatan') || '-';
        const buktiUrl = $row.data('bukti') || '';

        // Badge status color
        const statusClass = status.toLowerCase().includes('lunas') ? 'bg-success' : 'bg-danger';
        const statusIcon = status.toLowerCase().includes('lunas') ? 'checkbox-circle' : 'close-circle';

        // Build modal content
        const modalContent = `
            <div class="customer-header-info">
                <div class="customer-avatar">
                    ${namaLengkap.charAt(0).toUpperCase()}
                </div>
                <h5 class="customer-name">${namaLengkap}</h5>
                <span class="badge ${statusClass} customer-status">
                    <i class="ri-${statusIcon}-line me-1"></i>
                    ${status}
                </span>
            </div>

            <!-- Informasi Dasar -->
            <div class="detail-section">
                <h6><i class="ri-user-3-line"></i>Informasi Dasar</h6>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-barcode-line"></i>
                        Nomor ID
                    </div>
                    <div class="detail-value"><strong>${nomorId}</strong></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-user-line"></i>
                        Nama Lengkap
                    </div>
                    <div class="detail-value">${namaLengkap}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-whatsapp-line"></i>
                        WhatsApp
                    </div>
                    <div class="detail-value">
                        <a href="https://wa.me/${noWhatsapp}" target="_blank" class="text-success text-decoration-none">
                            <i class="ri-whatsapp-line me-1"></i>${noWhatsappDisplay}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="detail-section">
                <h6><i class="ri-map-pin-line"></i>Alamat Lengkap</h6>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-map-2-line"></i>
                        Alamat
                    </div>
                    <div class="detail-value">${alamat}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-building-line"></i>
                        Kecamatan
                    </div>
                    <div class="detail-value">${kecamatan}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-map-pin-range-line"></i>
                        Kabupaten
                    </div>
                    <div class="detail-value">${kabupaten}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-global-line"></i>
                        Provinsi
                    </div>
                    <div class="detail-value">${provinsi}</div>
                </div>
            </div>

            <!-- Paket Internet -->
            <div class="detail-section">
                <h6><i class="ri-wifi-line"></i>Paket Internet</h6>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-box-3-line"></i>
                        Nama Paket
                    </div>
                    <div class="detail-value">${paket}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-speed-line"></i>
                        Kecepatan
                    </div>
                    <div class="detail-value"><strong>${kecepatan}</strong></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-money-dollar-circle-line"></i>
                        Harga
                    </div>
                    <div class="detail-value"><strong class="text-primary">${harga}</strong></div>
                </div>
            </div>

            <!-- Tagihan -->
            <div class="detail-section">
                <h6><i class="ri-calendar-check-line"></i>Detail Tagihan</h6>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-calendar-line"></i>
                        Tanggal Mulai
                    </div>
                    <div class="detail-value">${tanggalMulai}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-calendar-close-line"></i>
                        Jatuh Tempo
                    </div>
                    <div class="detail-value"><strong class="text-danger">${jatuhTempo}</strong></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">
                        <i class="ri-file-text-line"></i>
                        Catatan
                    </div>
                    <div class="detail-value">${catatan}</div>
                </div>

            </div>
        `;

        // Populate modal dan tampilkan
        $('#detailModal .modal-body').html(modalContent);
        $('#detailModal').modal('show');
    });
});
</script>
@endsection

@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay">
    <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div class="container-fluid px-4 py-4">

  <!-- ========================================= -->
  <!-- FILTER & SEARCH -->
  <!-- ========================================= -->
  <div class="card mb-4">
    <div class="card-body">
      <form method="GET" action="{{ route('tagihan.outstanding') }}" id="filterForm">
        <div class="row g-3 align-items-end">
          <div class="col-md-9">
            <label class="form-label small fw-semibold mb-2">
              <i class="ri-search-line me-1"></i>Pencarian
            </label>
            <input
              type="text"
              name="search"
              class="form-control"
              placeholder="Cari nama, No. ID, WhatsApp..."
              value="{{ request('search') }}">
          </div>

          <div class="col-md-3">
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-grow-1">
                <i class="ri-search-line me-1"></i>Cari
              </button>
              @if(request()->hasAny(['search']))
                <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">
                  <i class="ri-refresh-line me-1"></i>Reset
                </a>
              @endif
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- ========================================= -->
  <!-- DAFTAR TAGIHAN -->
  <!-- ========================================= -->
<div class="card border-0 shadow-sm">
    <div class="card-header-custom">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h4 class="mb-1 fw-bold">
            Daftar Tagihan Outstanding
          </h4>
          <p class="mb-0 opacity-75 small">Kelola seluruh tagihan pelanggan secara efisien.</p>
        </div>

        <div class="d-flex align-items-center gap-2">
          @if($tagihans->total() > 0)
            <span class="badge" style="padding: 10px 20px; font-size: 14px; background: rgba(24, 24, 27, 0.1); color: #18181b; border: 1px solid rgba(24, 24, 27, 0.2);">
              <i class="ri-database-2-line me-1"></i>
              {{ $tagihans->total() }} Tagihan
            </span>
          @endif

          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTagihan">
            <span style="color: #fafafa; font-weight: bold;">+</span> Tambah Outstanding
          </button>
        </div>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive outstanding-table-wrap p-3">
        <div class="outstanding-selection-toolbar" id="outstandingSelectionToolbar">
          <span class="selected-text" id="outstandingSelectedCount">0 dipilih</span>
          <button type="button" class="delete-selected-btn" id="outstandingBulkDeleteBtn" title="Hapus data dipilih">
            <i class="ri-delete-bin-line"></i>
          </button>
        </div>
        <table class="datatables-users modern-table" style="width: 100%;">
          <colgroup>
            <col style="width:64px;">
            <col style="width:14%;">
            <col style="width:29%;">
            <col style="width:27%;">
            <col style="width:20%;">
            <col style="width:86px;">
          </colgroup>
          <thead>
            <tr>
              <th style="width: 64px;">
                <input type="checkbox" class="outstanding-checkbox" id="selectAllOutstanding" aria-label="Pilih semua">
              </th>
              <th>NO. ID</th>
              <th>NAMA</th>
              <th>NO. WA</th>
              <th>STATUS</th>
              <th>ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tagihans as $item)
            @php
              $status = strtolower($item['status_pembayaran'] ?? '');
              $badgeClass = match($status) {
                'lunas' => 'badge bg-success',
                'proses verifikasi' => 'badge bg-warning',
                'belum bayar' => 'badge bg-danger',
                default => 'badge bg-secondary',
              };

              $alamatParts = [];
              if($item['alamat_jalan']) $alamatParts[] = $item['alamat_jalan'];
              if($item['rt'] || $item['rw']) $alamatParts[] = 'RT '.$item['rt'].' / RW '.$item['rw'];
              if($item['desa']) $alamatParts[] = 'Desa '.$item['desa'];
              if($item['kecamatan']) $alamatParts[] = 'Kecamatan '.$item['kecamatan'];
              if($item['kabupaten']) $alamatParts[] = 'Kabupaten '.$item['kabupaten'];
              if($item['provinsi']) $alamatParts[] = $item['provinsi'];
              $alamatLengkap = implode(', ', $alamatParts);

              $buktiUrl = !empty($item['bukti_pembayaran']) ? asset('storage/kwitansi/' . $item['bukti_pembayaran']) : '';
            @endphp
            <tr
              data-tagihan-id="{{ $item['id'] }}"
              data-nomor-id="{{ $item['nomer_id'] }}"
              data-nama="{{ $item['nama_lengkap'] }}"
              data-whatsapp="{{ $item['no_whatsapp'] }}"
              data-status-label="{{ ucfirst($status ?: '-') }}"
              data-paket="{{ $item['paket']['nama_paket'] ?? '-' }}"
              data-harga="Rp {{ number_format($item['paket']['harga'] ?? 0, 0, ',', '.') }}"
              data-delete-url="{{ route('tagihan.destroy', $item['id']) }}"
              data-alamat="{{ $alamatLengkap }}"
              data-kecamatan="{{ $item['kecamatan'] ?? '-' }}"
              data-kabupaten="{{ $item['kabupaten'] ?? '-' }}"
              data-provinsi="{{ $item['provinsi'] ?? '-' }}"
              data-kecepatan="{{ $item['paket']['kecepatan'] ?? '-' }} Mbps"
              data-tanggal-mulai="{{ $item['tanggal_mulai'] ? \Carbon\Carbon::parse($item['tanggal_mulai'])->format('d M Y') : '-' }}"
              data-jatuh-tempo="{{ $item['tanggal_berakhir'] ? \Carbon\Carbon::parse($item['tanggal_berakhir'])->format('d M Y') : '-' }}"
              data-catatan="{{ $item['catatan'] ?? '-' }}"
              data-bukti="{{ $buktiUrl }}"
            >
              <td style="width: 64px;">
                <input type="checkbox" class="outstanding-checkbox" value="{{ $item['id'] }}" aria-label="Pilih {{ $item['nama_lengkap'] }}">
              </td>
              <td><span class="badge bg-label-dark">{{ $item['nomer_id'] }}</span></td>
              <td><strong>{{ $item['nama_lengkap'] }}</strong></td>
              <td>
                <a href="https://wa.me/{{ $item['no_whatsapp'] }}" target="_blank" class="text-decoration-none">
                  <code style="background: #18181b; padding: 6px 12px; border-radius: 6px; font-size: 0.875rem; font-weight: 600; color: #fafafa;">
                    <span class="material-symbols-rounded me-1" style="font-size:1rem;vertical-align:text-bottom;">sms</span>{{ $item['no_whatsapp'] }}
                  </code>
                </a>
              </td>
              <td>
                <span class="{{ $badgeClass }}">
                  <i class="ri-{{ $status == 'lunas' ? 'checkbox-circle' : 'close-circle' }}-line me-1"></i>
                  {{ ucfirst($status ?: '-') }}
                </span>
              </td>
              <td>
                <div class="dropdown">
                  <button class="action-btn" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <span class="material-symbols-rounded">more_vert</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end tagihan-action-menu">
                    <li>
                      <a class="dropdown-item d-flex align-items-center btn-detail" href="javascript:void(0);">
                        <span class="material-symbols-rounded" style="font-size:1.2rem;">visibility</span> Detail
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalEditTagihan-{{ $item['id'] }}">
                        <span class="material-symbols-rounded" style="font-size:1.2rem;">edit</span> Edit
                      </a>
                    </li>
                    <li>
                      <form action="{{ route('tagihan.destroy', $item['id']) }}" method="POST" class="delete-form m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item danger-action d-flex align-items-center w-100 border-0 bg-transparent text-start">
                          <span class="material-symbols-rounded" style="font-size:1.2rem;">delete</span> Delete
                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            @empty
            <tr class="empty-state-row">
              <td colspan="6" class="text-center">
                <div class="empty-state-content">
                  <div class="mb-3">
                    <i class="ri-inbox-line" style="font-size: 4rem; color: #ddd;"></i>
                  </div>

                  @if(request()->hasAny(['search']))
                    <h5 class="text-muted mb-2">
                      <i class="ri-search-eye-line me-2"></i>Data Tidak Ditemukan
                    </h5>
                    <p class="text-muted mb-3">
                      Tidak ada data yang sesuai dengan pencarian Anda.
                    </p>

                    <div class="mb-3">
                      @if(request('search'))
                        <span class="badge bg-label-primary me-2" style="padding: 8px 16px;">
                          <i class="ri-search-line me-1"></i>
                          Pencarian: "{{ request('search') }}"
                        </span>
                      @endif
                    </div>

                    <a href="{{ route('tagihan.index') }}" class="btn btn-primary mt-2">
                      <i class="ri-refresh-line me-1"></i>Reset & Tampilkan Semua Data
                    </a>
                  @else
                    <h5 class="text-muted mb-2">
                      <i class="ri-file-list-line me-2"></i>Belum Ada Data Tagihan
                    </h5>
                    <p class="text-muted">
                      Saat ini belum ada data tagihan yang terdaftar dalam sistem.
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

    @if($tagihans->count() > 0)
      <div class="pagination-wrapper">
        <label class="dense-toggle-wrap mb-0">
          <input type="checkbox" id="densePaddingToggleOutstanding">
          <span>Dense padding</span>
        </label>
        <div>
          {{ $tagihans->appends(request()->query())->onEachSide(1)->links('pagination.mui') }}
        </div>
      </div>
    @endif
  </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content border-0">
      <button type="button" class="btn-close position-absolute top-0 end-0 m-4 z-3" style="background-color: white; padding: 1rem; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" data-bs-dismiss="modal"></button>
      <div class="modal-body">
        <!-- Content will be inserted via JavaScript -->
      </div>
    </div>
  </div>
</div>


<!-- ========================================= -->
<!-- MODAL: TAMBAH TAGIHAN -->
<!-- ========================================= -->
<div class="modal fade" id="modalTambahTagihan" tabindex="-1">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <form action="{{ route('tagihan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white fw-bold">
            <i class="ri-add-circle-line me-2"></i>Tambah Tagihan Baru
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <!-- Pilih Pelanggan -->
            <div class="col-12">
              <label class="form-label fw-semibold">Pilih Pelanggan <span class="text-danger">*</span></label>
              <select id="pelangganSelect" name="pelanggan_id" class="form-select select2" required>
                <option value=""></option>
                <!-- Options akan diload via AJAX Select2 -->
              </select>
            </div>

            <input type="hidden" name="paket_id" id="paket_id">

            <!-- Info Pelanggan -->
            <div class="col-12 mt-4">
              <h6 class="text-primary fw-bold mb-3">
                <i class="ri-user-3-line me-2"></i>Informasi Pelanggan
              </h6>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Nama Lengkap</label>
              <input type="text" id="nama_lengkap" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Nomor ID</label>
              <input type="text" id="nomer_id" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Nomor WhatsApp</label>
              <input type="text" id="no_whatsapp" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Kode Pos</label>
              <input type="text" id="kode_pos" class="form-control bg-light" readonly>
            </div>

            <!-- Alamat -->
            <div class="col-12 mt-4">
              <h6 class="text-primary fw-bold mb-3">
                <i class="ri-map-pin-line me-2"></i>Alamat Lengkap
              </h6>
            </div>

            <div class="col-12">
              <label class="form-label small text-muted">Alamat Jalan</label>
              <input type="text" id="alamat_jalan" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-3">
              <label class="form-label small text-muted">RT</label>
              <input type="text" id="rt" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-3">
              <label class="form-label small text-muted">RW</label>
              <input type="text" id="rw" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Desa/Kelurahan</label>
              <input type="text" id="desa" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-4">
              <label class="form-label small text-muted">Kecamatan</label>
              <input type="text" id="kecamatan" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-4">
              <label class="form-label small text-muted">Kabupaten/Kota</label>
              <input type="text" id="kabupaten" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-4">
              <label class="form-label small text-muted">Provinsi</label>
              <input type="text" id="provinsi" class="form-control bg-light" readonly>
            </div>

            <!-- Paket -->
            <div class="col-12 mt-4">
              <h6 class="text-primary fw-bold mb-3">
                <i class="ri-box-3-line me-2"></i>Informasi Paket
              </h6>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Nama Paket</label>
              <input type="text" id="paket" class="form-control bg-light" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Harga Paket</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" id="harga" name="harga" class="form-control bg-light" readonly>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Kecepatan</label>
              <div class="input-group">
                <input type="text" id="kecepatan" class="form-control bg-light" readonly>
                <span class="input-group-text">Mbps</span>
              </div>
            </div>

            <div class="col-md-6">
              <label class="form-label small text-muted">Masa Pembayaran</label>
              <div class="input-group">
                <input type="text" id="masa_pembayaran" class="form-control bg-light" readonly>
                <span class="input-group-text">Hari</span>
              </div>
            </div>

            <!-- Tagihan -->
            <div class="col-12 mt-4">
              <h6 class="text-primary fw-bold mb-3">
                <i class="ri-calendar-check-line me-2"></i>Detail Tagihan
              </h6>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
              <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Tanggal Jatuh Tempo <span class="text-danger">*</span></label>
              <input type="date" id="tanggal_berakhir" name="tanggal_berakhir" class="form-control bg-light" readonly required>
            </div>

            <div class="col-12">
              <label class="form-label">Catatan (Opsional)</label>
              <textarea class="form-control" id="catatan" name="catatan" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <div class="col-12">
              <label class="form-label">Upload Bukti Pembayaran (Opsional)</label>
              <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf">
              <small class="text-muted">Format: JPG, PNG, PDF | Max: 2MB</small>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="ri-close-line me-1"></i>Batal
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="ri-save-line me-1"></i>Simpan Tagihan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ========================================= -->
<!-- MODAL: EDIT TAGIHAN (FOREACH) -->
<!-- ========================================= -->
@foreach($tagihans as $tagihan)
<div class="modal fade" id="modalEditTagihan-{{ $tagihan['id'] }}" tabindex="-1">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <form action="{{ route('tagihan.update', $tagihan['id']) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-header bg-warning py-4">
          <h5 class="modal-title text-white fw-bold">
            <i class="ri-edit-2-line me-2"></i>Edit Tagihan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1); opacity: 1;"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Nama Pelanggan</label>
              <input type="text" class="form-control bg-light" value="{{ $tagihan['nama_lengkap'] ?? '-' }}" readonly>
            </div>

            <input type="hidden" name="pelanggan_id" value="{{ $tagihan['pelanggan_id'] ?? '' }}">
            <input type="hidden" name="paket_id" value="{{ $tagihan['paket']['id'] ?? '' }}">

            <div class="col-md-6">
              <label class="form-label fw-semibold">Tanggal Mulai</label>
              <input type="text" name="tanggal_mulai" class="form-control flatpickr-edit-start" value="{{ $tagihan['tanggal_mulai'] }}" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Tanggal Jatuh Tempo</label>
              <input type="text" name="tanggal_berakhir" class="form-control flatpickr-edit-end" value="{{ $tagihan['tanggal_berakhir'] }}" required>
            </div>

            <div class="col-12">
              <label class="form-label">Catatan</label>
              <textarea class="form-control" name="catatan" rows="2">{{ $tagihan['catatan'] ?? '' }}</textarea>
            </div>

            <div class="col-12">
              <label class="form-label">Bukti Pembayaran</label>
              <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*,.pdf">
              <small class="text-muted">Format: JPG, PNG, PDF | Max: 2MB</small>
            </div>
          </div>
        </div>

        <div class="modal-footer py-4">
           <button type="submit" class="btn btn-warning">
            <i class="ri-save-line me-1"></i>Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<!-- ========================================= -->
<!-- MODAL: MASS TAGIHAN -->
<!-- ========================================= -->
<div class="modal fade" id="modalMassTagihan" tabindex="-1">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <form action="{{ route('tagihan.massStore') }}" method="POST">
        @csrf

        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title fw-bold">
            <i class="ri-group-line me-2"></i>Buat Tagihan Massal
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="alert alert-info d-flex align-items-center">
            <i class="ri-information-line me-2" style="font-size: 1.5rem;"></i>
            <div>
              <strong>{{ count($pelanggan) }} pelanggan</strong> akan dibuatkan tagihan secara otomatis
            </div>
          </div>

          <div class="border rounded p-3 mb-3" style="max-height: 200px; overflow-y: auto; background: #f8f9fa;">
            @foreach($pelanggan as $p)
            <div class="py-2 border-bottom">
              <span class="badge bg-dark me-2">{{ $p->nomer_id }}</span>
              <strong>{{ $p->nama_lengkap }}</strong>
            </div>
            @endforeach
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Mulai</label>
            <input type="text" name="tanggal_mulai" class="form-control flatpickr-select-start-all" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Jatuh Tempo</label>
            <input type="text" name="tanggal_berakhir" class="form-control flatpickr-select-start-end" required>
          </div>

          <div class="alert alert-warning small mb-0">
            <i class="ri-error-warning-line me-1"></i>
            Semua pelanggan di atas akan otomatis dibuatkan tagihan baru
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">
            <i class="ri-check-circle-line me-1"></i>Buat Semua Tagihan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
