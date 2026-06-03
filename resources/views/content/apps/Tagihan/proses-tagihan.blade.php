@extends('layouts/layoutMaster')

@section('title', 'Proses Verifikasi Tagihan')

@section('vendor-style')
@vite([
  'resources/css/app.css',
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
])
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

/* ========================================= */
/* MODERN CLEAN STYLES - BLACK & WHITE THEME */
/* ========================================= */

.ticket-toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 10px;
  max-width: 360px;
  padding: 14px 16px;
  border-radius: 10px;
  background: #18181b;
  color: #fff;
  box-shadow: 0 14px 34px rgba(24,24,27,0.24);
  font-size: 0.9rem;
  font-weight: 600;
  transform: translateX(120%);
  opacity: 0;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.ticket-toast.show {
  transform: translateX(0);
  opacity: 1;
}

/* SELECT2 OVERRIDES - BLACK & WHITE */
.select2-container--default .select2-results__option--highlighted[aria-selected] {
  background-color: #18181b !important; /* Black background for selected item */
  color: #ffffff !important;
}
.select2-container--default .select2-selection--single {
  border-color: #e2e8f0 !important;
}
.select2-container--default .select2-selection--single:focus,
.select2-container--open .select2-selection--single {
  border-color: #18181b !important; /* Black border on focus */
}
.select2-dropdown {
  border-color: #e2e8f0 !important;
}

/* FLATPICKR OVERRIDES - BLACK & WHITE */
.flatpickr-day.selected, 
.flatpickr-day.startRange, 
.flatpickr-day.endRange, 
.flatpickr-day.selected.inRange, 
.flatpickr-day.startRange.inRange, 
.flatpickr-day.endRange.inRange, 
.flatpickr-day.selected:focus, 
.flatpickr-day.startRange:focus, 
.flatpickr-day.endRange:focus, 
.flatpickr-day.selected:hover, 
.flatpickr-day.startRange:hover, 
.flatpickr-day.endRange:hover, 
.flatpickr-day.selected.prevMonthDay, 
.flatpickr-day.startRange.prevMonthDay, 
.flatpickr-day.endRange.prevMonthDay, 
.flatpickr-day.selected.nextMonthDay, 
.flatpickr-day.startRange.nextMonthDay, 
.flatpickr-day.endRange.nextMonthDay {
  background: #18181b !important;
  border-color: #18181b !important;
  color: #fff !important;
}

/* INPUT FOCUS OVERRIDES */
.form-control:focus, .form-select:focus {
  border-color: #18181b !important;
  box-shadow: 0 0 0 0.25rem rgba(24, 24, 27, 0.1) !important;
}

:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --card-hover-shadow: 0 4px 16px rgba(0,0,0,0.12);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --primary-color: #111827;
  --success-color: #28c76f;
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
  transform: translateY(-2px);
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
  background: linear-gradient(180deg, #111827 0%, #0b1220 100%);
}

.card-border-shadow-success::before {
  background: linear-gradient(180deg, #d1d5db 0%, #9ca3af 100%);
}

.card-border-shadow-warning::before {
  background: linear-gradient(180deg, #e5e7eb 0%, #d1d5db 100%);
}

.card-border-shadow-info::before {
  background: linear-gradient(180deg, #cbd5e1 0%, #94a3b8 100%);
}

/* Stats Card */
.stats-card {
  border-radius: var(--border-radius);
  padding: 1.5rem;
  background: #ffffff;
  color: #0f172a;
  border: 1px solid #e5e7eb;
  transition: var(--transition);
}

.stats-card p,
.stats-card h2,
.stats-card .text-muted {
  color: #0f172a !important;
}

.stats-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
}

.stats-icon {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  background: #f3f4f6;
  color: #111827;
}

/* Avatar */
.avatar-initial {
  border-radius: 12px;
  transition: var(--transition);
}

.card:hover .avatar-initial {
  transform: scale(1.05);
}

/* ========================================= */
/* SHADCN UI STYLE BUTTONS - ALL BLACK */
/* Override Bootstrap default button colors */
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

/* Primary Button - Black */
.btn.btn-primary,
.btn-primary {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn.btn-primary:hover,
.btn-primary:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn.btn-primary:focus,
.btn-primary:focus,
.btn.btn-primary:focus-visible,
.btn-primary:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
  background: #18181b !important;
  color: #fafafa !important;
}

.btn.btn-primary:active,
.btn-primary:active {
  background: #09090b !important;
  color: #fafafa !important;
}

/* Warning Button - Black */
.btn.btn-warning,
.btn-warning {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn.btn-warning:hover,
.btn-warning:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn.btn-warning:focus,
.btn-warning:focus,
.btn.btn-warning:focus-visible,
.btn-warning:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
  background: #18181b !important;
  color: #fafafa !important;
}

/* Success Button - Black */
.btn.btn-success,
.btn-success {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn.btn-success:hover,
.btn-success:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn.btn-success:focus,
.btn-success:focus,
.btn.btn-success:focus-visible,
.btn-success:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
  background: #18181b !important;
  color: #fafafa !important;
}

/* Secondary Button - Black */
.btn.btn-secondary,
.btn-secondary {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn.btn-secondary:hover,
.btn-secondary:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn.btn-secondary:focus,
.btn-secondary:focus,
.btn.btn-secondary:focus-visible,
.btn-secondary:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
  background: #18181b !important;
  color: #fafafa !important;
}

/* Danger Button - Black */
.btn.btn-danger,
.btn-danger {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn.btn-danger:hover,
.btn-danger:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn.btn-danger:focus,
.btn-danger:focus,
.btn.btn-danger:focus-visible,
.btn-danger:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
  background: #18181b !important;
  color: #fafafa !important;
}

/* Outline Buttons - Light background, black text */
.btn.btn-outline-primary,
.btn.btn-outline-secondary,
.btn.btn-outline-danger,
.btn-outline-primary,
.btn-outline-secondary,
.btn-outline-danger {
  background: transparent !important;
  background-color: transparent !important;
  border: 1px solid #e4e4e7 !important;
  color: #18181b !important;
}

.btn.btn-outline-primary:hover,
.btn.btn-outline-secondary:hover,
.btn.btn-outline-danger:hover,
.btn-outline-primary:hover,
.btn-outline-secondary:hover,
.btn-outline-danger:hover {
  background: #f4f4f5 !important;
  background-color: #f4f4f5 !important;
  border-color: #a1a1aa !important;
  color: #18181b !important;
}

.btn.btn-outline-primary:focus,
.btn.btn-outline-secondary:focus,
.btn.btn-outline-danger:focus,
.btn-outline-primary:focus-visible,
.btn-outline-secondary:focus-visible,
.btn-outline-danger:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
}

/* Small Button */
.btn.btn-sm,
.btn-sm {
  padding: 0.375rem 0.75rem !important;
  font-size: 0.8125rem !important;
}

/* Icon Button */
.btn-icon {
  width: 2rem;
  height: 2rem;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

/* ========================================= */
/* SHADCN UI STYLE BADGES & TEXT */
/* ========================================= */

/* Badges */
.badge {
  padding: 0.25rem 0.625rem;
  border-radius: 9999px;
  font-weight: 500;
  font-size: 0.75rem;
  letter-spacing: 0;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
}

/* Neutralize accent labels and badges - shadcn style */
.bg-label-primary,
.bg-label-success,
.bg-label-warning,
.bg-label-dark {
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

.stats-icon.bg-label-primary,
.stats-icon.bg-label-success,
.stats-icon.bg-label-warning,
.stats-icon.bg-label-info {
  background: #f4f4f5 !important;
  color: #18181b !important;
}

/* Status Lunas - Black */
.badge.bg-success {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
  border-radius: 9999px !important;
}

/* Status Proses Verifikasi - Warning Yellow */
.badge.bg-warning {
  background: #f59e0b !important;
  color: #ffffff !important;
  border: none !important;
  border-radius: 9999px !important;
}

/* Status Belum Bayar - Red with white text, rounded */
.badge.bg-danger {
  background: #dc2626 !important;
  color: #fafafa !important;
  border: none !important;
  border-radius: 9999px !important;
}

/* Solid badges default */
.bg-info,
.bg-primary,
.bg-dark {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
}

/* All text colors - Black (shadcn style) */
.text-success,
.text-info,
.text-warning,
.text-primary,
.text-danger,
.text-muted {
  color: #71717a !important;
}
 

/* Card Header */
.card-header {
  background: transparent;
  padding: 1.5rem;
  border-bottom: 1px solid #f0f0f0;
}

.card-header-custom {
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  border-radius: var(--border-radius) var(--border-radius) 0 0;
  padding: 1.5rem;
  border-bottom: 1px solid #f0f0f0;
}

/* Search Form */
.search-wrapper {
  background: #f8f9fa;
  padding: 1.25rem;
  border-radius: 10px;
  margin-bottom: 1rem;
}

/* Input Groups */
.input-group-text {
  border-radius: 8px 0 0 8px;
  background: #f8f9fa;
  border: 1px solid #e0e0e0;
  color: #5a5f7d;
  font-weight: 500;
}

.input-group .form-control {
  border-left: none;
  border-color: #e0e0e0;
}

.input-group .form-control:focus {
  border-color: var(--primary-color);
  box-shadow: none;
}

.input-group:focus-within .input-group-text {
  border-color: var(--primary-color);
}

.input-group:focus-within .form-control {
  border-color: var(--primary-color);
}

/* Form Controls */
.form-select, .form-control {
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  padding: 0.625rem 1rem;
  transition: var(--transition);
}

.form-select:focus, .form-control:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.12);
}

/* Table */
.table {
  border-collapse: separate;
  border-spacing: 0;
}

.table thead th {
  background: #f8fafc;
  border: none;
  padding: 1rem;
  font-weight: 600;
  color: #0f172a;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  white-space: nowrap;
}

.table tbody tr {
  transition: var(--transition);
}

.table tbody tr:not(.empty-state-row):hover {
  background: #f1f5f9;
  transform: scale(1.001);
}

.table tbody td {
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb;
  vertical-align: middle;
}

.table thead th:first-child,
.table tbody td:first-child {
  text-align: center;
}

/* Empty State */
.empty-state-row td {
  background: #fafbfc !important;
  border: none !important;
}

.empty-state-content {
  padding: 3rem 1rem;
}

/* ========================================= */
/* SHADCN UI STYLE MODAL */
/* ========================================= */
.modal-content {
  border-radius: 12px;
  border: 1px solid #e4e4e7;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
  background: #ffffff;
  overflow: hidden;
}

.modal-header {
  padding: 1.25rem 1.5rem 1.75rem 1.5rem;
  border-bottom: 1px solid #e4e4e7;
  background: #fafafa;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-header .modal-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #18181b;
  margin: 0;
}

.modal-header.bg-light {
  background: #18181b !important;
  border-bottom: none;
}

.modal-header.bg-light .modal-title {
  color: #fafafa;
}

.modal-header.bg-warning {
  background: #18181b !important;
  border-bottom: none;
}

.modal-header.bg-warning .modal-title {
  color: #fafafa;
}

.modal-header .btn-close {
  padding: 0.5rem;
  margin: -0.5rem -0.5rem -0.5rem auto;
  opacity: 0.5;
  transition: opacity 0.15s ease;
}

.modal-header .btn-close:hover {
  opacity: 1;
}

.modal-body {
  padding: 1.5rem;
  padding-top: 2rem;
  max-height: 65vh;
  overflow-y: auto;
}

.modal-footer {
  padding: 1rem 1.5rem;
  margin-top: 0.5rem;
  border-top: 1px solid #e4e4e7;
  background: #fafafa;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.75rem;
}

.btn-close-white {
  filter: brightness(0) invert(1);
}

/* Modal Dialog Centered with proper spacing */
.modal-dialog-centered {
  min-height: calc(100% - 3.5rem);
  margin: 1.75rem auto;
}

/* Modal backdrop with blur effect */
.modal-backdrop.show {
  opacity: 1;
  background-color: rgba(24, 24, 27, 0.4);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Loading Overlay */
/* .loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.5);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
} */

/* ========================================= */
/* DETAIL MODAL STYLES */
/* ========================================= */
#detailModal .modal-dialog {
  margin: 0;
  max-width: 100%;
  height: 100dvh;
}

#detailModal .modal-content {
  height: 100dvh;
  border-radius: 0;
  border: 0;
  overflow: hidden;
}

#detailModal .modal-body {
  padding: 0;
  max-height: none;
  height: 100%;
  overflow-y: auto;
}

#detailModal .btn-close {
  background-color: #fff;
  padding: 1rem;
  border-radius: 50%;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  opacity: 1;
}

.customer-header-info {
  text-align: center;
  padding: 1.5rem;
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
  border-radius: 12px;
  margin-bottom: 1.5rem;
  border: 1px solid #e8e8e8;
}

.customer-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: linear-gradient(135deg, #111827 0%, #0b1220 100%);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 2.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 4px 16px rgba(17, 24, 39, 0.4);
  border: 4px solid white;
}

.customer-name {
  font-size: 1.5rem;
  font-weight: 700;
  color: #2c3e50;
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
  border-radius: 8px;
  padding: 1.25rem;
  margin-bottom: 1.25rem;
  transition: all 0.2s;
}

.detail-section:first-child,
.modal-body > .detail-section:first-of-type {
  margin-top: 0.5rem;
}

.detail-section:hover {
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  border-color: #111827;
}

.detail-section h6 {
  color: #111827;
  font-weight: 700;
  margin-bottom: 1.25rem;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  display: flex;
  align-items: center;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #111827;
}

.detail-section h6 i {
  margin-right: 0.5rem;
  font-size: 1.1rem;
}

.detail-item {
  display: flex;
  padding: 0.875rem 0;
  border-bottom: 1px solid #f0f0f0;
  align-items: flex-start;
}

.detail-item:last-child {
  border-bottom: none;
  padding-bottom: 0;
}

.detail-label {
  color: #5a5f7d;
  font-weight: 600;
  min-width: 150px;
  font-size: 0.875rem;
  display: flex;
  align-items: center;
}

.detail-label i {
  margin-right: 0.5rem;
  color: #a8afc7;
  font-size: 1rem;
}

.detail-value {
  color: #2c3e50;
  font-size: 0.875rem;
  flex: 1;
  word-break: break-word;
}

/* ========================================= */
/* PAGINATION STYLES */
/* ========================================= */
.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-top: 1px solid #f0f0f0;
  background: transparent;
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
  width: 40px;
  height: 40px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e4e4e7;
  color: #18181b;
  font-weight: 600;
  background-color: #fff;
  margin: 0 4px;
  transition: all 0.3s ease;
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

.pagination-wrapper .mui-pagination {
  align-items: center;
  gap: 0.85rem;
}

.pagination-wrapper .mui-pagination .page-link {
  width: 40px;
  min-width: 40px;
  height: 40px;
  margin: 0 !important;
  padding: 0 !important;
  border: 0 !important;
  border-radius: 50% !important;
  background: transparent !important;
  color: #1f2937 !important;
  box-shadow: none !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
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

/* Hide Bootstrap Pagination Info Text (Showing X to Y of Z results) */
.pagination-wrapper > div:last-child > nav > div:first-child,
.pagination-wrapper nav > div.hidden,
.pagination-wrapper nav > div:first-child:not(:last-child),
.pagination-wrapper small.text-muted,
.pagination-wrapper .text-sm,
[role="navigation"] > div:first-child:not([aria-label]) {
  display: none !important;
}

/* More specific: hide "Showing X of Y results" text in pagination wrapper */
.pagination-wrapper > div > nav > div.flex.justify-between > div:first-child,
.pagination-wrapper > div > nav > div > p,
.pagination-wrapper > div > nav > div > span.relative,
nav[role="navigation"] > div:first-child,
nav[role="navigation"] > div > p,
nav[role="navigation"] .hidden,
nav[role="navigation"] > div.flex-1,
.pagination-wrapper p.text-sm,
.pagination-wrapper .leading-5,
p:has(span.font-medium) {
  display: none !important;
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.card {
  animation: fadeIn 0.3s ease-out;
}

/* Image Hover */
.table img {
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  transition: var(--transition);
  cursor: pointer;
}

.table img:hover {
  transform: scale(1.5);
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  z-index: 999;
}

@media (max-width: 768px) {
  .modal-body {
    padding: 1.5rem;
  }
  .card-header {
    padding: 1.25rem;
  }
  .detail-label {
    min-width: 120px;
  }
}

/* Match daftar tagihan visual system */
.container-fluid {
  font-family: 'Inter', sans-serif;
  background: transparent;
}

.card {
  border: 1px solid rgba(226, 232, 240, 0.92) !important;
  border-radius: 18px !important;
  background: #ffffff !important;
  box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08) !important;
  overflow: hidden;
}

.card-body {
  background: #ffffff;
}

.card:hover {
  transform: none !important;
}

.card-header {
  background:
    radial-gradient(circle at top right, rgba(14, 165, 233, 0.10), transparent 28%),
    linear-gradient(180deg, #ffffff 0%, #f8fafc 100%) !important;
  border-bottom: 1px solid #eef2f7 !important;
  padding: 1.45rem 1.6rem !important;
}

.card-header h5 {
  font-size: 1.28rem;
  font-weight: 800;
  color: #18181b;
  letter-spacing: 0;
}

.verification-title-icon {
  width: 42px;
  height: 42px;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: rgba(245, 158, 11, 0.13);
  color: #b45309;
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.92);
}

.verification-summary-chip {
  min-height: 42px;
  padding: 0.6rem 1rem;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.78);
  color: #0f172a;
  border: 1px solid rgba(226, 232, 240, 0.95);
  box-shadow: 0 10px 22px rgba(15, 23, 42, 0.07);
  font-size: 0.9rem;
  font-weight: 800;
}

.search-wrapper {
  padding: 1rem 0 0;
  margin-top: 1rem;
  margin-bottom: 0;
  background: transparent;
  border: 0;
  border-top: 1px solid #eef2f7;
  border-radius: 0;
  box-shadow: none;
}

.search-wrapper .input-group {
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
  box-shadow: none;
}

.search-wrapper .input-group:focus-within {
  border-color: #94a3b8;
  box-shadow: 0 0 0 4px rgba(148, 163, 184, 0.16);
}

.search-wrapper .input-group-text,
.search-wrapper .form-control {
  border: 0 !important;
  background: #fff !important;
}

.search-wrapper .form-control {
  min-height: 48px;
  font-weight: 500;
}

.search-wrapper .btn {
  min-height: 48px;
  border-radius: 12px !important;
}

.table thead th {
  background: #f8fafc !important;
  color: #64748b !important;
  font-weight: 800 !important;
}

.table tbody td {
  border-bottom: 1px dashed #e5eaf0 !important;
}

.verification-table {
  min-width: 0;
  width: 100%;
  table-layout: fixed;
}

.verification-table thead th {
  padding: 0.95rem 0.9rem !important;
  background: #f8fafc !important;
  color: #475569 !important;
  font-size: 0.82rem !important;
  font-weight: 800 !important;
  text-transform: none !important;
  letter-spacing: 0 !important;
  border-bottom: 1px solid #e5eaf0 !important;
}

.verification-table tbody td {
  padding: 1rem 0.9rem !important;
  border-bottom: 1px dashed #e5eaf0 !important;
  background: rgba(255, 255, 255, 0.82);
}

.verification-table thead th:nth-child(1),
.verification-table tbody td:nth-child(1) {
  width: 58px;
}

.verification-table thead th:nth-child(2),
.verification-table tbody td:nth-child(2) { width: 29%; }

.verification-table thead th:nth-child(3),
.verification-table tbody td:nth-child(3) { width: 14%; }

.verification-table thead th:nth-child(4),
.verification-table tbody td:nth-child(4) { width: 16%; }

.verification-table thead th:nth-child(5),
.verification-table tbody td:nth-child(5) { width: 13%; }

.verification-table thead th:nth-child(6),
.verification-table tbody td:nth-child(6) { width: 15%; }

.verification-table thead th:nth-child(7),
.verification-table tbody td:nth-child(7) { width: 48px; }

.verification-table tbody tr:hover {
  background: #f8fafc !important;
  transform: none !important;
}

.verification-table tbody tr:hover td {
  background: #f8fafc !important;
}

.verification-table.is-dense thead th {
  padding-top: 0.6rem !important;
  padding-bottom: 0.6rem !important;
}

.verification-table.is-dense tbody td {
  padding-top: 0.6rem !important;
  padding-bottom: 0.6rem !important;
}

.verification-table tbody tr.row-selected {
  background: #eff6ff !important;
}

.verification-table tbody tr.row-selected td {
  background: #eff6ff !important;
}

.verification-row-checkbox {
  width: 20px;
  height: 20px;
  border-radius: 6px;
  accent-color: #111827;
  cursor: pointer;
}

.verification-selection-toolbar {
  display: none;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 0.9rem 1.5rem;
  background: linear-gradient(90deg, #eff6ff 0%, #ffffff 100%);
  border-bottom: 1px solid #dbe7f7;
  color: #111827;
}

.verification-selection-toolbar.active {
  display: flex;
}

.verification-selection-toolbar .selected-text {
  font-size: 0.98rem;
  font-weight: 800;
}

.verification-selection-toolbar .delete-selected-btn {
  width: 42px;
  height: 42px;
  border: 0;
  border-radius: 12px;
  background: transparent;
  color: #64748b;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.45rem;
}

.verification-selection-toolbar .delete-selected-btn:hover {
  background: rgba(15, 23, 42, 0.08);
  color: #dc2626;
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

.verification-check {
  width: 22px;
  height: 22px;
  border: 2px solid #cbd5e1;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  color: transparent;
  transition: all 0.2s ease;
}

.verification-check.is-checked {
  border-color: #22c55e;
  background: #22c55e;
  color: #fff;
}

.verification-customer {
  display: flex;
  align-items: center;
  gap: 0;
  min-width: 0;
}

.verification-avatar {
  display: none !important;
  width: 56px;
  height: 56px;
  border-radius: 14px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  flex: 0 0 56px;
  color: #fff;
  font-size: 1.35rem;
  font-weight: 800;
  box-shadow: 0 12px 24px rgba(15, 23, 42, 0.1);
}

.verification-name {
  margin: 0;
  color: #0f172a;
  font-size: 0.98rem;
  font-weight: 800;
  line-height: 1.25;
}

.verification-subtext,
.verification-date span,
.verification-stock-text {
  color: #94a3b8;
  font-size: 0.88rem;
  font-weight: 500;
}

.verification-subtext {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.verification-date {
  min-width: 0;
  color: #1f2937;
  font-weight: 700;
}

.verification-date span {
  display: block;
  margin-top: 0.25rem;
}

.verification-stock {
  min-width: 0;
}

.verification-stock-bar {
  width: min(112px, 100%);
  height: 8px;
  overflow: hidden;
  border-radius: 999px;
  background: #e2e8f0;
  margin-bottom: 0.5rem;
}

.verification-stock-fill {
  display: block;
  width: 22%;
  height: 100%;
  border-radius: inherit;
  background: linear-gradient(90deg, #f59e0b, #facc15);
  box-shadow: 0 0 18px rgba(245, 158, 11, 0.38);
}

.verification-price {
  color: #111827;
  font-size: 1.02rem;
  font-weight: 800;
  white-space: nowrap;
}

.verification-status-pill {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.5rem 0.82rem;
  border-radius: 999px;
  background: #f1f5f9;
  color: #475569;
  font-size: 0.82rem;
  font-weight: 800;
  white-space: nowrap;
  max-width: 100%;
}

.verification-status-pill.is-process {
  background: #fff7ed;
  color: #9a3412;
  border: 1px solid #fed7aa;
}

.verification-action-btn {
  width: 40px;
  height: 40px;
  border: 0;
  border-radius: 12px;
  background: #f8fafc;
  color: #64748b;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  line-height: 1;
}

.verification-action-btn:hover,
.verification-action-btn:focus {
  background: #111827;
  color: #ffffff;
}

.verification-action-menu {
  border: 1px solid #e5eaf0;
  border-radius: 14px;
  padding: 0.55rem;
  min-width: 210px;
  background: rgba(255, 255, 255, 0.96);
  box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
}

.verification-action-menu .dropdown-item {
  border-radius: 10px;
  gap: 0.72rem;
  padding: 0.68rem 0.78rem;
  color: #1f2937;
  font-size: 0.92rem;
  font-weight: 700;
}

.verification-action-menu .dropdown-item:hover {
  background: #f1f5f9;
}

.verification-action-menu .dropdown-item i {
  width: 22px;
  color: #1f2937;
  font-size: 1.18rem;
}

.verification-action-menu .dropdown-item.danger-action {
  color: #ff3b30;
}

.verification-action-menu .dropdown-item.danger-action i {
  color: #ff3b30;
}

@media (max-width: 767.98px) {
  .container-fluid {
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
  }

  .card-header {
    padding: 1rem !important;
  }

  .verification-title-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    flex: 0 0 38px;
  }

  .verification-summary-chip {
    width: 100%;
    justify-content: center;
  }

  .search-wrapper {
    padding: 0.85rem;
  }

  .search-wrapper .row {
    gap: 0.75rem;
  }

  .search-wrapper .col-md-10,
  .search-wrapper .col-md-2 {
    width: 100%;
  }

  .search-wrapper .col-md-2 .d-flex {
    width: 100%;
  }

  .search-wrapper .btn {
    min-height: 44px;
  }

  .verification-table {
    min-width: 760px;
  }
}
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
])
@endsection

@section('page-script')
<style>
/* ========== VERIFIKASI TAGIHAN DELETE MODAL ========== */
.swal-tagihan-popup {
  border-radius: 20px !important;
  padding: 2rem 1.5rem 1.5rem !important;
  box-shadow: 0 25px 60px rgba(0,0,0,0.18) !important;
  border: none !important;
  width: min(90vw, 420px) !important;
}
.swal-tagihan-popup .swal2-title {
  font-size: 1.3rem !important;
  font-weight: 700 !important;
  color: #18181b !important;
}
.swal-tagihan-popup .swal2-html-container {
  color: #52525b !important;
  font-size: 0.9rem !important;
  line-height: 1.6 !important;
}
.swal-tagihan-confirm {
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
}
.swal-tagihan-confirm:hover {
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
  box-shadow: 0 6px 20px rgba(239,68,68,0.45) !important;
  transform: translateY(-1px) !important;
}
.swal-tagihan-cancel {
  background: #f4f4f5 !important;
  color: #52525b !important;
  border: 1px solid #e4e4e7 !important;
  border-radius: 12px !important;
  padding: 0.65rem 1.5rem !important;
  font-size: 0.875rem !important;
  font-weight: 600 !important;
  transition: all 0.2s !important;
  margin: 0 0.35rem !important;
}
.swal-tagihan-cancel:hover {
  background: #e4e4e7 !important;
  color: #18181b !important;
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const buktiBaseUrl = "{{ asset('storage/bukti_pembayaran') }}";

    function buildBuktiUrl(raw) {
        const value = String(raw || '').trim();
        if (!value || value === '-') return '';

        // Jika sudah full URL (http/https), pakai langsung (jangan rewrite domain)
        if (/^https?:\/\//i.test(value)) {
            return value;
        }

        let path = value;
        try {
            path = new URL(value, window.location.origin).pathname;
        } catch (e) {
            path = value;
        }

        const fileName = path.split('/').filter(Boolean).pop();
        if (!fileName || fileName === '-') return '';

        return `${buktiBaseUrl}/${encodeURIComponent(fileName)}`;
    }

    function escapeHtml(value) {
        return String(value || '').replace(/[&<>'"]/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#039;',
                '"': '&quot;'
            }[char];
        });
    }

    // ========================================
    // HELPER FUNCTIONS
    // ========================================
    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }
    
    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    const denseToggleProses = document.getElementById('densePaddingToggleProses');
    const prosesTable = document.querySelector('.verification-table');
    if (denseToggleProses && prosesTable) {
        const saved = localStorage.getItem('dense_proses_tagihan') === '1';
        denseToggleProses.checked = saved;
        prosesTable.classList.toggle('is-dense', saved);
        denseToggleProses.addEventListener('change', function () {
            const isDense = denseToggleProses.checked;
            prosesTable.classList.toggle('is-dense', isDense);
            localStorage.setItem('dense_proses_tagihan', isDense ? '1' : '0');
        });
    }

    function updateVerificationSelection() {
        const $all = $('.verification-checkbox');
        const $checked = $('.verification-checkbox:checked');
        const selectedCount = $checked.length;

        $('#verificationSelectedCount').text(`${selectedCount} dipilih`);
        $('#verificationSelectionToolbar').toggleClass('active', selectedCount > 0);
        $('tr[data-tagihan-id]').removeClass('row-selected');
        $checked.closest('tr[data-tagihan-id]').addClass('row-selected');

        const $selectAll = $('#selectAllVerification');
        $selectAll.prop('checked', $all.length > 0 && selectedCount === $all.length);
        $selectAll.prop('indeterminate', selectedCount > 0 && selectedCount < $all.length);
    }

    $('#selectAllVerification').on('change', function () {
        $('.verification-checkbox').prop('checked', this.checked);
        updateVerificationSelection();
    });

    $(document).on('change', '.verification-checkbox', updateVerificationSelection);

    $('#verificationBulkDeleteBtn').on('click', function () {
        const $checked = $('.verification-checkbox:checked');
        const totalSelected = $checked.length;

        if (!totalSelected) {
            showToast('Pilih tagihan terlebih dahulu.');
            return;
        }

        Swal.fire({
            title: 'Hapus Tagihan Dipilih?',
            html: `<p class="mb-0">Yakin ingin menghapus <strong>${totalSelected}</strong> tagihan?<br><span style="color:#6b7280;font-size:0.875rem;">Data tidak dapat dikembalikan.</span></p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-delete-bin-line"></i> &nbsp;Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel'
            }
        }).then(async (result) => {
            if (!result.isConfirmed) return;

            showLoading();
            const requests = $checked.map(function () {
                const $row = $(this).closest('tr[data-tagihan-id]');
                const $form = $row.find('.delete-form').first();

                return $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    $row.fadeOut(250, function () {
                        $(this).remove();
                        updateVerificationSelection();
                    });
                    return true;
                }).catch(() => false);
            }).get();

            const results = await Promise.all(requests);
            const successCount = results.filter(Boolean).length;
            hideLoading();
            updateVerificationSelection();

            if (successCount > 0) {
                showToast(`${successCount} data berhasil di delete.`);
            }

            if (successCount < totalSelected) {
                Swal.fire('Sebagian gagal', `${totalSelected - successCount} tagihan gagal dihapus. Coba ulangi lagi.`, 'warning');
            }
        });
    });

    function syncCheckedStateToTable() {
        $('tr[data-tagihan-id]').each(function () {
            const $row = $(this);
            const tagihanId = String($row.data('tagihan-id') || '');
            if (!tagihanId) return;

            const isChecked = localStorage.getItem(`tagihan_checked_${tagihanId}`) === '1';
            const $box = $row.find('.verification-check').first();
            if (!$box.length) return;

            if (isChecked) {
                $box.addClass('is-checked')
                    .html('<i class="ri-check-line" style="font-size:0.85rem;"></i>')
                    .attr('title', 'Sudah dicek');
            } else {
                $box.removeClass('is-checked')
                    .html('')
                    .attr('title', 'Belum dicek');
            }
        });
    }

    // ========================================
    // DETAIL MODAL - MODERN UI
    // ========================================
    $(document).on('click', '.btn-detail', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const tr = this.closest('tr');
        const $tr = $(tr);
        const readDetail = (key, fallback = '-') => {
            const value = tr?.dataset?.[key];
            return value === undefined || value === null || value === '' ? fallback : value;
        };
        
        // Get data from attributes so the detail modal stays stable with minimal table layouts.
        const nomorId = readDetail('nomorId');
        const namaLengkap = readDetail('nama');
        const noWhatsapp = readDetail('whatsapp');
        const statusPembayaran = readDetail('statusLabel');
        
        // Get hidden data from data attributes
        const alamatLengkap = readDetail('alamat');
        const kecamatan = readDetail('kecamatan');
        const kabupaten = readDetail('kabupaten');
        const provinsi = readDetail('provinsi');
        const paket = readDetail('paket');
        const harga = readDetail('harga');
        const kecepatan = readDetail('kecepatan');
        const tanggalMulai = readDetail('tanggalMulai');
        const jatuhTempo = readDetail('jatuhTempo');
        const typePembayaran = readDetail('typePembayaran', 'Belum dipilih');
        const catatan = readDetail('catatan');
        const buktiPembayaran = buildBuktiUrl(readDetail('bukti', ''));
        
        // Get tagihan ID and status for button
        const tagihanId = $tr.data('tagihan-id');
        const nama = namaLengkap;
        const status = $tr.find('.btn-konfirmasi').length > 0 ? 'belum_bayar' : 'lunas';
        const checkedKey = `tagihan_checked_${tagihanId}`;
        const isChecked = localStorage.getItem(checkedKey) === '1';
        
        // Build bukti section
        let buktiSection = '<span class="text-muted">Belum ada bukti pembayaran.</span>';
        if (buktiPembayaran) {
            const isPdfBukti = /\.pdf(\?|#|$)/i.test(String(buktiPembayaran));
            buktiSection = `
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <span class="badge ${isChecked ? 'bg-success' : 'bg-secondary'} rounded-pill px-3 py-2 bukti-checked-badge" data-tagihan-id="${tagihanId}">
                        <i class="ri-pushpin-${isChecked ? 'fill' : 'line'} me-1"></i>${isChecked ? 'Sudah dicek' : 'Belum dicek'}
                    </span>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm ${isChecked ? 'btn-success' : 'btn-outline-success'} btn-toggle-checked" data-tagihan-id="${tagihanId}">
                            <i class="ri-check-line me-1"></i>${isChecked ? 'Ditandai' : 'Tandai Dicek'}
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-inline-zoom-toggle">
                            <i class="ri-search-eye-line me-1"></i>Zoom
                        </button>
                    </div>
                </div>
                ${isPdfBukti ? '' : `
                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-inline-zoom-out" title="Zoom Out">-</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-inline-zoom-in" title="Zoom In">+</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-inline-zoom-reset" title="Reset">Reset</button>
                </div>`}
                <div class="bukti-inline-container border rounded-4 overflow-hidden shadow-sm" style="height: 430px; background:#f8fafc; position:relative; cursor:${isPdfBukti ? 'default' : 'grab'};">
                    ${isPdfBukti
                        ? `<iframe src="${buktiPembayaran}" title="Bukti Pembayaran PDF" style="width:100%;height:100%;border:0;background:#fff;"></iframe>`
                        : `<img src="${buktiPembayaran}" alt="Bukti Pembayaran" class="bukti-inline-image" style="position:absolute; top:50%; left:50%; transform: translate(calc(-50% + 0px), calc(-50% + 0px)) scale(1); transform-origin:center center; max-width:none; user-select:none; -webkit-user-drag:none;">`
                    }
                </div>
                <div class="mt-3 text-center">
                    <a href="${buktiPembayaran}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-4">
                        <i class="ri-download-line me-1"></i>Unduh Bukti
                    </a>
                </div>
            `;
        }
        
        const initial = namaLengkap ? namaLengkap.charAt(0).toUpperCase() : '?';
        const statusLower = statusPembayaran.toLowerCase();
        const statusClass = statusLower.includes('lunas') ? 'bg-success' : statusLower.includes('proses') ? 'bg-warning' : 'bg-secondary';
        
        const html = `
            <div class="row g-0 min-vh-100">
                <div class="col-lg-4 col-xl-3 border-end bg-light p-4 p-xl-5 d-flex flex-column align-items-center">
                    <div class="customer-avatar mb-4" style="width: 120px; height: 120px; font-size: 3.5rem;">${initial}</div>
                    <h3 class="fw-bold text-center mb-1" style="color:#1e293b;">${namaLengkap}</h3>
                    <p class="text-muted text-center mb-4 fs-5">${nomorId}</p>
                    <span class="badge ${statusClass} rounded-pill px-4 py-2 mb-5 fs-6 shadow-sm">
                        <i class="ri-checkbox-circle-line me-1"></i>${statusPembayaran}
                    </span>

                    <div class="w-100 mt-2">
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-whatsapp-line text-success fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size:0.75rem;">WhatsApp</small>
                                <a href="https://wa.me/${String(noWhatsapp).replace(/\\D/g, '')}" target="_blank" class="text-dark fw-bold text-decoration-none fs-5">${noWhatsapp}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-map-pin-line text-primary fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size:0.75rem;">Alamat</small>
                                <span class="text-dark fw-bold fs-6">${alamatLengkap}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-building-line text-info fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size:0.75rem;">Area</small>
                                <span class="text-dark fw-bold fs-6">${kecamatan}, ${kabupaten}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-xl-9 p-4 p-xl-5">
                    <div class="max-w-4xl mx-auto py-4">
                        <h2 class="fw-bold mb-2 text-dark">Ringkasan Tagihan</h2>
                        <p class="text-muted mb-5 fs-5">Rincian paket layanan dan status verifikasi pelanggan.</p>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 rounded-4" style="background:linear-gradient(135deg,#eff6ff 0%,#ffffff 100%); border-left:6px solid #3b82f6 !important;">
                                    <div class="card-body p-4 p-xl-5">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div>
                                                <p class="text-primary fw-bold mb-1 text-uppercase" style="letter-spacing:1px;">Paket Layanan</p>
                                                <h3 class="fw-bold mb-0 text-dark">${paket}</h3>
                                            </div>
                                            <div class="bg-white p-3 rounded-circle shadow-sm"><i class="ri-router-line text-primary fs-2"></i></div>
                                        </div>
                                        <div class="d-flex align-items-center gap-4 mt-5">
                                            <div>
                                                <small class="text-muted d-block mb-1 text-uppercase fw-bold">Kecepatan</small>
                                                <span class="fw-bold fs-4 text-dark">${kecepatan}</span>
                                            </div>
                                            <div class="border-start ps-4">
                                                <small class="text-muted d-block mb-1 text-uppercase fw-bold">Harga per Bulan</small>
                                                <span class="fw-bold text-success fs-4">${harga}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 rounded-4" style="background:linear-gradient(135deg,#fef2f2 0%,#ffffff 100%); border-left:6px solid #ef4444 !important;">
                                    <div class="card-body p-4 p-xl-5">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div>
                                                <p class="text-danger fw-bold mb-1 text-uppercase" style="letter-spacing:1px;">Jatuh Tempo</p>
                                                <h3 class="fw-bold mb-0 text-dark">${jatuhTempo}</h3>
                                            </div>
                                            <div class="bg-white p-3 rounded-circle shadow-sm"><i class="ri-calendar-event-line text-danger fs-2"></i></div>
                                        </div>
                                        <div class="d-flex align-items-center gap-4 mt-5">
                                            <div>
                                                <small class="text-muted d-block mb-1 text-uppercase fw-bold">Tanggal Mulai</small>
                                                <span class="fw-bold fs-4 text-dark">${tanggalMulai}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="fw-bold mb-4 text-dark"><i class="ri-bank-card-line me-2"></i>Metode Pembayaran</h4>
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-5">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <span class="text-muted fw-semibold">Type Pembayaran</span>
                                <span class="badge bg-label-primary px-3 py-2 fs-6">${typePembayaran}</span>
                            </div>
                        </div>

                        <h4 class="fw-bold mb-4 text-dark"><i class="ri-file-text-line me-2"></i>Catatan Tambahan</h4>
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-5">
                            <p class="mb-0 text-secondary fs-5" style="line-height:1.6;">${catatan || 'Tidak ada catatan khusus untuk pelanggan ini.'}</p>
                        </div>

                        <h4 class="fw-bold mb-4 text-dark"><i class="ri-image-line me-2"></i>Bukti Pembayaran</h4>
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            ${buktiSection}
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-outline-primary px-4" id="btnEditPaket">
                                <i class="ri-edit-line me-1"></i>Edit Paket
                            </button>
                            <button type="button" class="btn btn-danger px-4" id="btnTolakPembayaran">
                                <i class="ri-close-circle-line me-1"></i>Tolak Pembayaran
                            </button>
                            <button type="button" class="btn btn-success px-4" id="btnKonfirmasiDetail">
                                <i class="ri-check-circle-line me-1"></i>Konfirmasi Lunas
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#detailModal .modal-body').html(html);
        $('#detailModal').data('tagihan-id', tagihanId);
        $('#detailModal').data('tagihan-nama', nama);
        $('#detailModal').data('tagihan-status', status);
        
        const btnKonfirmasi = $('#btnKonfirmasiDetail');
        if (status === 'lunas') {
            btnKonfirmasi.prop('disabled', true).removeClass('btn-success').addClass('btn-secondary').html('<i class="ri-check-circle-line me-1"></i> Sudah Lunas');
        } else {
            btnKonfirmasi.prop('disabled', false).removeClass('btn-secondary').addClass('btn-success').html('<i class="ri-check-circle-line me-1"></i> Konfirmasi Lunas');
        }
        
        $('#detailModal').modal('show');
    });

    $(document).on('click', '.btn-toggle-checked', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const tagihanId = String($btn.data('tagihan-id') || '');
        if (!tagihanId) return;

        const key = `tagihan_checked_${tagihanId}`;
        const nextChecked = localStorage.getItem(key) !== '1';
        localStorage.setItem(key, nextChecked ? '1' : '0');

        const $badge = $(`.bukti-checked-badge[data-tagihan-id="${tagihanId}"]`);
        if (nextChecked) {
            $btn.removeClass('btn-outline-success').addClass('btn-success')
                .html('<i class="ri-check-line me-1"></i>Ditandai');
            $badge.removeClass('bg-secondary').addClass('bg-success')
                .html('<i class="ri-pushpin-fill me-1"></i>Sudah dicek');
        } else {
            $btn.removeClass('btn-success').addClass('btn-outline-success')
                .html('<i class="ri-check-line me-1"></i>Tandai Dicek');
            $badge.removeClass('bg-success').addClass('bg-secondary')
                .html('<i class="ri-pushpin-line me-1"></i>Belum dicek');
        }

        syncCheckedStateToTable();
    });

    // ========================================
    // KONFIRMASI PEMBAYARAN DARI MODAL DETAIL
    // ========================================
    $(document).on('click', '#btnKonfirmasiDetail', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const tagihanId = $('#detailModal').data('tagihan-id');
        const nama = $('#detailModal').data('tagihan-nama');
        
        if (!tagihanId) {
            Swal.fire('Error!', 'Data tagihan tidak ditemukan.', 'error');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `<p class="mb-0">Apakah <strong>${nama}</strong> sudah melakukan pembayaran?</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2dce89',
            cancelButtonColor: '#8898aa',
            confirmButtonText: '<i class="ri-check-line me-1"></i>Ya, Lunas',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-success',
                cancelButton: 'swal-tailwind-cancel'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // showLoading();
                
                $.ajax({
                    url: `/dashboard/admin/tagihan/${tagihanId}/bayar`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        // hideLoading();
                        if(response.success) {
                            $('#detailModal').modal('hide');
                            
                            // Update the row dynamically by removing it
                            const row = $(`tr[data-tagihan-id="${tagihanId}"]`);
                            if (row.length) {
                                row.fadeOut(400, function() {
                                    $(this).remove();
                                });
                            }

                            showToast(`Tagihan ${nama} telah ditandai lunas.`);
                        } else {
                            Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function() {
                        // hideLoading();
                        Swal.fire('Gagal!', 'Terjadi kesalahan server.', 'error');
                    }
                });
            }
        });
    });

    // ========================================
    // TOLAK PEMBAYARAN
    // ========================================
    $(document).on('click', '#btnTolakPembayaran', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const tagihanId = $('#detailModal').data('tagihan-id');
        const nama = $('#detailModal').data('tagihan-nama');
        
        if (!tagihanId) {
            Swal.fire('Error!', 'Data tagihan tidak ditemukan.', 'error');
            return;
        }

        const openRejectPrompt = () => Swal.fire({
            title: 'Tolak Pembayaran',
            html: `<div class="text-start">
                <p class="mb-3">Berikan alasan penolakan untuk <strong>${escapeHtml(nama)}</strong>. Catatan ini akan tampil di halaman tagihan customer.</p>
                <label for="swalAlasanPenolakan" class="form-label fw-semibold">Catatan Penolakan</label>
                <textarea id="swalAlasanPenolakan" class="form-control" rows="4" maxlength="1000" placeholder="Contoh: Nominal transfer tidak sesuai, bukti pembayaran kurang jelas, atau rekening tujuan tidak sesuai."></textarea>
                <div class="form-text mt-2">Status akan dikembalikan ke "Belum Bayar" dan bukti pembayaran akan dihapus.</div>
            </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#8898aa',
            confirmButtonText: '<i class="ri-close-line me-1"></i>Ya, Tolak',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel'
            },
            buttonsStyling: false,
            focusConfirm: false,
            didOpen: () => {
                document.getElementById('swalAlasanPenolakan')?.focus();
            },
            preConfirm: () => {
                const alasan = document.getElementById('swalAlasanPenolakan')?.value.trim() || '';
                if (alasan.length < 5) {
                    Swal.showValidationMessage('Catatan penolakan minimal 5 karakter.');
                    return false;
                }

                return alasan;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // showLoading();
                
                $.ajax({
                    url: `/dashboard/admin/tagihan/${tagihanId}/kembalikan-belum-bayar`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        alasan_penolakan: result.value,
                    },
                    success: function(response) {
                        // hideLoading();
                        if(response.success) {
                            $('#detailModal').modal('hide');
                            
                            // Update the row dynamically by removing it
                            const row = $(`tr[data-tagihan-id="${tagihanId}"]`);
                            if (row.length) {
                                row.fadeOut(400, function() {
                                    $(this).remove();
                                });
                            }

                            Swal.fire({
                                title: 'Pembayaran Ditolak!',
                                html: `<p>Tagihan <strong>${escapeHtml(nama)}</strong> telah dikembalikan ke status "Belum Bayar".</p><div class="alert alert-danger text-start mt-3 mb-0"><strong>Alasan:</strong><br>${escapeHtml(result.value)}</div>`,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                customClass: {
                                    container: 'swal-tailwind-backdrop',
                                    popup: 'swal-tailwind-popup',
                                    confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-primary'
                                },
                                buttonsStyling: false
                            });
                        } else {
                            Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function(xhr) {
                        // hideLoading();
                        const response = xhr.responseJSON;
                        const errors = response?.errors || {};
                        const message = errors.alasan_penolakan?.[0] || response?.message || 'Terjadi kesalahan server.';
                        Swal.fire('Gagal!', message, 'error').then(() => $('#detailModal').modal('show'));
                    }
                });
            } else if (result.dismiss) {
                $('#detailModal').modal('show');
            }
        });

        if ($('#detailModal').hasClass('show')) {
            $('#detailModal').one('hidden.bs.modal', openRejectPrompt).modal('hide');
            return;
        }

        openRejectPrompt();
    });

    // ========================================
    // EDIT PAKET MODAL
    // ========================================
    function openEditPaketModal(tagihanId, nama, fromDetail = false) {
        if (!tagihanId) {
            Swal.fire('Error!', 'Data tagihan tidak ditemukan.', 'error');
            return;
        }

        const $tr = $(`tr[data-tagihan-id="${tagihanId}"]`);
        const tanggalMulaiRaw = $tr.data('tanggal-mulai-raw') || '';
        const tanggalBerakhirRaw = $tr.data('tanggal-berakhir-raw') || '';
        const currentPaketId = $tr.data('paket-id') || '';

        $('#editPaketModal')
            .data('tagihan-id', tagihanId)
            .data('tagihan-nama', nama)
            .data('from-detail', fromDetail ? 1 : 0);
        $('#editNamaTagihan').text(nama || '-');

        $('#editTanggalMulai').val(tanggalMulaiRaw);
        $('#editTanggalBerakhir').val(tanggalBerakhirRaw);

        if (currentPaketId) {
            $('#selectPaketEdit').val(currentPaketId).trigger('change');
        } else {
            $('#selectPaketEdit').val('').trigger('change');
        }

        const showEditModal = function() {
            $('#editPaketModal').modal('show');

            if ($.fn.select2 && !$('#selectPaketEdit').hasClass('select2-hidden-accessible')) {
                $('#selectPaketEdit').select2({
                    dropdownParent: $('#editPaketModal'),
                    placeholder: '-- Cari dan Pilih Paket --',
                    allowClear: true,
                    width: '100%'
                });
            }

            if (window.flatpickr) {
                if (!$('#editTanggalMulai').hasClass('flatpickr-input')) {
                    flatpickr('#editTanggalMulai', {
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'd M Y',
                        allowInput: true,
                        defaultDate: tanggalMulaiRaw || null
                    });
                } else if ($('#editTanggalMulai')[0]._flatpickr) {
                    $('#editTanggalMulai')[0]._flatpickr.setDate(tanggalMulaiRaw || null);
                }

                if (!$('#editTanggalBerakhir').hasClass('flatpickr-input')) {
                    flatpickr('#editTanggalBerakhir', {
                        dateFormat: 'Y-m-d',
                        altInput: true,
                        altFormat: 'd M Y',
                        allowInput: true,
                        defaultDate: tanggalBerakhirRaw || null
                    });
                } else if ($('#editTanggalBerakhir')[0]._flatpickr) {
                    $('#editTanggalBerakhir')[0]._flatpickr.setDate(tanggalBerakhirRaw || null);
                }
            }
        };

        if (fromDetail && $('#detailModal').hasClass('show')) {
            $('#detailModal').modal('hide');
            setTimeout(showEditModal, 300);
            return;
        }

        showEditModal();
    }

    $(document).on('click', '#btnEditPaket', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const tagihanId = $('#detailModal').data('tagihan-id');
        const nama = $('#detailModal').data('tagihan-nama');

        openEditPaketModal(tagihanId, nama, true);
    });

    // ========================================
    // KEMBALI DARI EDIT MODAL KE DETAIL MODAL
    // ========================================
    $(document).on('click', '#btnBackToDetailFromEdit', function(e) {
        e.preventDefault();
        
        $('#editPaketModal').modal('hide');

        if ($('#editPaketModal').data('from-detail')) {
            setTimeout(function() {
                $('#detailModal').modal('show');
            }, 300);
        }
    });

    // ========================================
    // SIMPAN PERUBAHAN PAKET
    // ========================================
    $(document).on('click', '#btnSimpanPaket', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const tagihanId = $('#editPaketModal').data('tagihan-id');
        const nama = $('#editPaketModal').data('tagihan-nama');
        const paketId = $('#selectPaketEdit').val();
        const tanggalMulai = $('#editTanggalMulai').val();
        const tanggalBerakhir = $('#editTanggalBerakhir').val();
        
        if (!tagihanId) {
            Swal.fire('Error!', 'Data tagihan tidak ditemukan.', 'error');
            return;
        }
        
        if (!paketId) {
            Swal.fire('Peringatan!', 'Silakan pilih paket terlebih dahulu.', 'warning');
            return;
        }
        
        if (!tanggalMulai) {
            Swal.fire('Peringatan!', 'Silakan isi tanggal mulai.', 'warning');
            return;
        }
        
        if (!tanggalBerakhir) {
            Swal.fire('Peringatan!', 'Silakan isi tanggal berakhir.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Perubahan',
            html: `<p class="mb-0">Apakah Anda yakin ingin menyimpan perubahan tagihan untuk <strong>${nama}</strong>?</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#18181b',
            cancelButtonColor: '#8898aa',
            confirmButtonText: '<i class="ri-check-line me-1"></i>Ya, Simpan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-primary',
                cancelButton: 'swal-tailwind-cancel'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // showLoading();
                
                $.ajax({
                    url: `/dashboard/admin/tagihan/${tagihanId}/update-paket`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        paket_id: paketId,
                        tanggal_mulai: tanggalMulai,
                        tanggal_berakhir: tanggalBerakhir
                    },
                    success: function(response) {
                        // hideLoading();
                        if(response.success) {
                            const d = response.data || {};
                            const $row = $(`tr[data-tagihan-id="${tagihanId}"]`);
                            if ($row.length) {
                                const paketNama = d.paket_nama || '';
                                const hargaFormatted = d.harga_formatted || '';
                                const kecepatanText = d.kecepatan ? `${d.kecepatan} Mbps` : '';

                                if (d.paket_id) $row.attr('data-paket-id', d.paket_id);
                                if (paketNama) $row.attr('data-paket', paketNama);
                                if (hargaFormatted) $row.attr('data-harga', hargaFormatted);
                                if (kecepatanText) $row.attr('data-kecepatan', kecepatanText);
                                if (d.tanggal_mulai_formatted) $row.attr('data-tanggal-mulai', d.tanggal_mulai_formatted);
                                if (d.tanggal_berakhir_formatted) $row.attr('data-jatuh-tempo', d.tanggal_berakhir_formatted);
                                if (d.tanggal_mulai) $row.attr('data-tanggal-mulai-raw', d.tanggal_mulai);
                                if (d.tanggal_berakhir) $row.attr('data-tanggal-berakhir-raw', d.tanggal_berakhir);

                                const nomorId = $row.attr('data-nomor-id') || '-';
                                if (paketNama) {
                                    $row.find('.verification-subtext').text(`${nomorId} · ${$row.attr('data-whatsapp') || '-'}`);
                                }
                                if (d.tanggal_berakhir_formatted) {
                                    $row.find('.verification-date').contents().first()[0].textContent = `${d.tanggal_berakhir_formatted} `;
                                }
                                if (hargaFormatted) {
                                    $row.find('.verification-price').text(hargaFormatted);
                                }
                            }

                            $('#editPaketModal').modal('hide');
                            showToast(`Paket untuk ${nama} telah diperbarui.`);
                        } else {
                            Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function(xhr) {
                        // hideLoading();
                        const response = xhr.responseJSON;
                        Swal.fire('Gagal!', response?.message || 'Terjadi kesalahan server.', 'error');
                    }
                });
            }
        });
    });

    $(document).on('click', '.btn-edit-paket-row', function(e) {
        e.preventDefault();
        e.stopPropagation();

        openEditPaketModal($(this).data('id'), $(this).data('nama'), false);
    });

    // ========================================
    // UPDATE HARGA WHEN PAKET CHANGES
    // ========================================
    $('#selectPaketEdit').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const harga = selectedOption.data('harga') || 0;
        const kecepatan = selectedOption.data('kecepatan') || 0;
        
        $('#previewHarga').text('Rp ' + new Intl.NumberFormat('id-ID').format(harga));
        $('#previewKecepatan').text(kecepatan + ' Mbps');
    });

    // ========================================
    // KONFIRMASI PEMBAYARAN DARI TABEL
    // ========================================
    $(document).on('click', '.btn-konfirmasi', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const tagihanId = $(this).data('id');
        const nama = $(this).data('nama');

        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `<p class="mb-0">Apakah <strong>${nama}</strong> sudah melakukan pembayaran?</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2dce89',
            cancelButtonColor: '#8898aa',
            confirmButtonText: '<i class="ri-check-line me-1"></i>Ya, Lunas',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-success',
                cancelButton: 'swal-tailwind-cancel'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // showLoading();
                
                $.ajax({
                    url: `/dashboard/admin/tagihan/${tagihanId}/bayar`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        // hideLoading();
                        if(response.success) {
                            // Update the row dynamically by removing it
                            const row = $(`tr[data-tagihan-id="${tagihanId}"]`);
                            if (row.length) {
                                row.fadeOut(400, function() {
                                    $(this).remove();
                                });
                            }

                            showToast(`Tagihan ${nama} telah ditandai lunas.`);
                        } else {
                            Swal.fire('Gagal!', response.message || 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function() {
                        hideLoading();
                        Swal.fire('Gagal!', 'Terjadi kesalahan server.', 'error');
                    }
                });
            }
        });
    });

    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const form = this;
        const $form = $(form);
        const $row = $form.closest('tr');

        Swal.fire({
            title: 'Hapus Tagihan?',
            html: '<p class="mb-0">Yakin ingin menghapus tagihan ini?<br><span style="color:#6b7280;font-size:0.875rem;">Data tidak dapat dikembalikan.</span></p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-delete-bin-line"></i> &nbsp;Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel'
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(resp) {
                    if (resp.success) {
                        $row.fadeOut(300, function() {
                            $(this).remove();
                        });
                        showToast(resp.message || 'Tagihan berhasil dihapus.');
                    } else {
                        Swal.fire('Gagal!', resp.message || 'Terjadi kesalahan.', 'error');
                    }
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                    Swal.fire('Gagal!', msg, 'error');
                }
            });
        });
    });

    // ========================================
    // ZOOM + DRAG BUKTI INLINE (tanpa popup)
    // ========================================
    function applyInlineTransform($container) {
        const $img = $container.find('.bukti-inline-image');
        const scale = Number($container.attr('data-scale') || 1);
        const x = Number($container.attr('data-x') || 0);
        const y = Number($container.attr('data-y') || 0);
        $img.css('transform', `translate(calc(-50% + ${x}px), calc(-50% + ${y}px)) scale(${scale})`);
    }

    function initInlineViewer($container) {
        if ($container.attr('data-init') === '1') return;
        $container.attr('data-init', '1');
        $container.attr('data-scale', '1');
        $container.attr('data-x', '0');
        $container.attr('data-y', '0');
        applyInlineTransform($container);
    }

    $(document).on('mouseenter', '.bukti-inline-container', function() {
        initInlineViewer($(this));
    });

    $(document).on('click', '.btn-inline-zoom-toggle', function(e) {
        e.preventDefault();
        const $container = $(this).closest('.bg-white').find('.bukti-inline-container');
        initInlineViewer($container);
        const current = Number($container.attr('data-scale') || 1);
        const next = current <= 1 ? 2 : 1;
        $container.attr('data-scale', String(next));
        if (next === 1) {
            $container.attr('data-x', '0');
            $container.attr('data-y', '0');
        }
        applyInlineTransform($container);
    });

    $(document).on('click', '.btn-inline-zoom-in, .btn-inline-zoom-out, .btn-inline-zoom-reset', function(e) {
        e.preventDefault();
        const $container = $(this).closest('.bg-white').find('.bukti-inline-container');
        initInlineViewer($container);

        const current = Number($container.attr('data-scale') || 1);
        if ($(this).hasClass('btn-inline-zoom-reset')) {
            $container.attr('data-scale', '1');
            $container.attr('data-x', '0');
            $container.attr('data-y', '0');
        } else if ($(this).hasClass('btn-inline-zoom-in')) {
            $container.attr('data-scale', String(Math.min(6, current + 0.2)));
        } else {
            $container.attr('data-scale', String(Math.max(0.5, current - 0.2)));
        }
        applyInlineTransform($container);
    });

    $(document).on('wheel', '.bukti-inline-container', function(e) {
        e.preventDefault();
        const $container = $(this);
        initInlineViewer($container);
        const current = Number($container.attr('data-scale') || 1);
        const delta = e.originalEvent.deltaY < 0 ? 0.12 : -0.12;
        $container.attr('data-scale', String(Math.min(6, Math.max(0.5, current + delta))));
        applyInlineTransform($container);
    });

    $(document).on('mousedown', '.bukti-inline-container', function(e) {
        const $container = $(this);
        initInlineViewer($container);
        $container.attr('data-dragging', '1');
        $container.attr('data-start-x', String(e.clientX));
        $container.attr('data-start-y', String(e.clientY));
        $container.css('cursor', 'grabbing');
    });

    $(document).on('mousemove', function(e) {
        const $container = $('.bukti-inline-container[data-dragging="1"]');
        if (!$container.length) return;

        const startX = Number($container.attr('data-start-x') || 0);
        const startY = Number($container.attr('data-start-y') || 0);
        const x = Number($container.attr('data-x') || 0);
        const y = Number($container.attr('data-y') || 0);

        $container.attr('data-start-x', String(e.clientX));
        $container.attr('data-start-y', String(e.clientY));
        $container.attr('data-x', String(x + (e.clientX - startX)));
        $container.attr('data-y', String(y + (e.clientY - startY)));
        applyInlineTransform($container);
    });

    $(document).on('mouseup', function() {
        $('.bukti-inline-container[data-dragging="1"]').attr('data-dragging', '0').css('cursor', 'grab');
    });

    // initial paint untuk indikator "sudah dicek" di tabel
    syncCheckedStateToTable();
});
</script>
@endsection

@section('content')


<div class="container-fluid px-4 py-4">
  <!-- ========================================= -->
  <!-- DAFTAR TAGIHAN PROSES VERIFIKASI -->
  <!-- ========================================= -->
  <div class="card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <span class="verification-title-icon">
            <i class="ri-file-list-3-line fs-4"></i>
          </span>
          <div>
            <h5 class="mb-1 fw-bold">Tagihan Proses Verifikasi</h5>
            <small class="text-muted">Kelola tagihan yang sedang dalam proses verifikasi pembayaran</small>
          </div>
        </div>
        
        @if($tagihans->total() > 0)
        <div>
          <span class="verification-summary-chip">
            <i class="ri-database-2-line me-1"></i>
            {{ $tagihans->total() }} Tagihan
          </span>
        </div>
        @endif
      </div>

      <!-- ========================================= -->
      <!-- FORM SEARCH -->
      <!-- ========================================= -->
      <div class="search-wrapper mt-3">
        <form action="{{ url()->current() }}" method="GET">
          <div class="row g-3 align-items-center">
            <div class="col-md-10">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="ri-search-line"></i>
                </span>
                <input type="text" 
                  class="form-control" 
                  name="search" 
                  placeholder="Cari berdasarkan Nama, No. ID, WhatsApp, Alamat, Kecamatan, Kabupaten..." 
                  value="{{ request('search') }}"
                  autocomplete="off">
              </div>
            </div>
            <div class="col-md-2">
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                  <i class="ri-search-line me-1"></i>Cari
                </button>
                @if(request('search'))
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary" title="Reset">
                  <i class="ri-refresh-line"></i>
                </a>
                @endif
              </div>
            </div>
          </div>
        </form>
      </div>
      <!-- END FORM SEARCH -->

    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <div class="verification-selection-toolbar" id="verificationSelectionToolbar">
          <span class="selected-text" id="verificationSelectedCount">0 dipilih</span>
          <button type="button" class="delete-selected-btn" id="verificationBulkDeleteBtn" title="Hapus data dipilih">
            <i class="ri-delete-bin-line"></i>
          </button>
        </div>
        <table class="table table-hover mb-0 verification-table">
          <thead>
            <tr>
              <th style="width: 56px;">
                <input type="checkbox" class="verification-row-checkbox" id="selectAllVerification" aria-label="Pilih semua tagihan">
              </th>
              <th>Pelanggan</th>
              <th>Jatuh tempo</th>
              <th>Progres</th>
              <th>Tagihan</th>
              <th>Status</th>
              <th style="width: 70px;"></th>
            </tr>
          </thead>
          <tbody>
            @forelse($tagihans as $item)
            @php
              $status = strtolower($item->status_pembayaran ?? '');
              $statusLabel = ucwords(str_replace('_', ' ', $status) ?: 'Belum Bayar');
              $colors = ['#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899'];
              $nameSeed = $item->pelanggan->nama_lengkap ?? '-';
              $bgColor = $colors[strlen($nameSeed) % count($colors)];

              $alamatParts = [];
              if($item->pelanggan->alamat_jalan ?? '') $alamatParts[] = $item->pelanggan->alamat_jalan;
              if(($item->pelanggan->rt ?? '') || ($item->pelanggan->rw ?? '')) {
                $alamatParts[] = 'RT '.($item->pelanggan->rt ?? '-').' / RW '.($item->pelanggan->rw ?? '-');
              }
              if($item->pelanggan->desa ?? '') $alamatParts[] = 'Desa '.$item->pelanggan->desa;
              if($item->pelanggan->kecamatan ?? '') $alamatParts[] = 'Kecamatan '.$item->pelanggan->kecamatan;
              if($item->pelanggan->kabupaten ?? '') $alamatParts[] = 'Kabupaten '.$item->pelanggan->kabupaten;
              if($item->pelanggan->provinsi ?? '') $alamatParts[] = $item->pelanggan->provinsi;
              $alamatLengkap = implode(', ', $alamatParts);
              $hargaFormatted = 'Rp ' . number_format($item->paket->harga ?? 0, 0, ',', '.');
              $tanggalMulaiFormatted = $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-';
              $jatuhTempoFormatted = $item->tanggal_berakhir ? \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') : '-';
              $rawTypePembayaran = trim((string)($item->type_pembayaran ?? ''));
              if ($rawTypePembayaran === '') {
                $typePembayaranLabel = 'Belum dipilih';
              } elseif (strtolower($rawTypePembayaran) === 'cash') {
                $typePembayaranLabel = 'Cash / Tunai';
              } elseif (!empty($item->rekening->nama_bank ?? null)) {
                $typePembayaranLabel = $item->rekening->nama_bank;
              } else {
                $typePembayaranLabel = $rawTypePembayaran;
              }
              $buktiUrl = trim((string)($item->bukti_pembayaran_resolved ?? ''));
            @endphp

            <tr 
              data-tagihan-id="{{ $item->id }}"
              data-nomor-id="{{ $item->pelanggan->nomer_id ?? '-' }}"
              data-nama="{{ $item->pelanggan->nama_lengkap ?? '-' }}"
              data-whatsapp="{{ $item->pelanggan->no_whatsapp ?? '-' }}"
              data-status-label="{{ $statusLabel }}"
              data-alamat="{{ $alamatLengkap }}"
              data-kecamatan="{{ $item->pelanggan->kecamatan ?? '-' }}"
              data-kabupaten="{{ $item->pelanggan->kabupaten ?? '-' }}"
              data-provinsi="{{ $item->pelanggan->provinsi ?? '-' }}"
              data-paket="{{ $item->paket->nama_paket ?? '-' }}"
              data-paket-id="{{ $item->paket->id ?? '' }}"
              data-harga="{{ $hargaFormatted }}"
              data-kecepatan="{{ $item->paket->kecepatan ?? '-' }} Mbps"
              data-tanggal-mulai="{{ $tanggalMulaiFormatted }}"
              data-tanggal-mulai-raw="{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('Y-m-d') : '' }}"
              data-jatuh-tempo="{{ $jatuhTempoFormatted }}"
              data-tanggal-berakhir-raw="{{ $item->tanggal_berakhir ? \Carbon\Carbon::parse($item->tanggal_berakhir)->format('Y-m-d') : '' }}"
              data-catatan="{{ $item->catatan ?? '-' }}"
              data-type-pembayaran="{{ $typePembayaranLabel }}"
              data-bukti="{{ $buktiUrl }}"
            >
              <td>
                <input type="checkbox" class="verification-row-checkbox verification-checkbox" value="{{ $item->id }}" aria-label="Pilih tagihan {{ $item->pelanggan->nama_lengkap ?? '-' }}">
              </td>
              <td>
                <div class="verification-customer">
                  <div>
                    <h6 class="verification-name">{{ $item->pelanggan->nama_lengkap ?? '-' }}</h6>
                    <div class="verification-subtext">{{ $item->pelanggan->nomer_id ?? '-' }} · {{ $item->pelanggan->no_whatsapp ?? '-' }}</div>
                  </div>
                </div>
              </td>
              <td>
                <div class="verification-date">
                  {{ $jatuhTempoFormatted }}
                  <span>{{ $item->tanggal_berakhir ? \Carbon\Carbon::parse($item->tanggal_berakhir)->translatedFormat('h:i a') : '' }}</span>
                </div>
              </td>
              <td>
                <div class="verification-stock">
                  <div class="verification-stock-bar">
                    <span class="verification-stock-fill"></span>
                  </div>
                  <div class="verification-stock-text">Menunggu verifikasi</div>
                </div>
              </td>
              <td>
                <span class="verification-price">{{ $hargaFormatted }}</span>
              </td>
              <td>
                <span class="verification-status-pill is-process">{{ $statusLabel }}</span>
              </td>
              <td>
                <div class="dropdown">
                  <button class="verification-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-2-fill"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end verification-action-menu">
                    <li>
                      <a class="dropdown-item d-flex align-items-center btn-detail" href="javascript:void(0);">
                        <i class="ri-eye-line"></i> Detail
                      </a>
                    </li>
                    @if($status === 'lunas')
                    <li>
                      <span class="dropdown-item d-flex align-items-center text-muted">
                        <i class="ri-check-circle-line"></i> Lunas
                      </span>
                    </li>
                    @else
                    <li>
                      <a class="dropdown-item d-flex align-items-center btn-konfirmasi" href="javascript:void(0);"
                        data-id="{{ $item->id }}"
                        data-nama="{{ $item->pelanggan->nama_lengkap ?? '-' }}">
                        <i class="ri-checkbox-circle-line"></i> Verifikasi Lunas
                      </a>
                    </li>
                    @endif
                    <li>
                      <a class="dropdown-item d-flex align-items-center btn-edit-paket-row" href="javascript:void(0);"
                        data-id="{{ $item->id }}"
                        data-nama="{{ $item->pelanggan->nama_lengkap ?? '-' }}">
                        <i class="ri-pencil-line"></i> Edit
                      </a>
                    </li>
                    <li>
                      <form action="{{ route('tagihan.destroy', $item->id) }}" method="POST" class="delete-form m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item danger-action d-flex align-items-center w-100 border-0 bg-transparent text-start">
                          <i class="ri-delete-bin-line"></i> Delete
                        </button>
                      </form>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center py-5">
                <div class="mb-3">
                  <i class="ri-inbox-line" style="font-size: 4rem; color: #ddd;"></i>
                </div>
                @if(request('search'))
                <h5 class="text-muted mb-2">Tidak Ada Hasil</h5>
                <p class="text-muted">Tidak ditemukan tagihan dengan kata kunci "<strong>{{ request('search') }}</strong>"</p>
                <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-primary mt-2">
                  <i class="ri-refresh-line me-1"></i>Reset Pencarian
                </a>
                @else
                <h5 class="text-muted mb-2">Tidak Ada Tagihan Dalam Proses Verifikasi</h5>
                <p class="text-muted">Saat ini tidak ada tagihan yang sedang dalam proses verifikasi.</p>
                @endif
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- ========================================= -->
      <!-- PAGINATION LARAVEL -->
      <!-- ========================================= -->
      @if($tagihans->total() > 0)
      <div class="pagination-wrapper">
        @if(request('search'))
        <div class="pagination-info">
          <span class="badge bg-label-primary">
            <i class="ri-search-line me-1"></i>Hasil pencarian: "{{ request('search') }}"
          </span>
        </div>
        @endif
        <label class="dense-toggle-wrap mb-0">
          <input type="checkbox" id="densePaddingToggleProses">
          <span>Dense padding</span>
        </label>
        <div>
          {{ $tagihans->onEachSide(2)->links('vendor.pagination.custom-always') }}
        </div>
      </div>
      @elseif(request('search'))
      <div class="px-4 py-3 border-top bg-light">
        <div class="text-muted small">
          <span class="badge bg-label-primary">
            <i class="ri-search-line me-1"></i>Hasil pencarian: "{{ request('search') }}"
          </span>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>

<!-- ========================================= -->
<!-- MODAL: DETAIL - MODERN UI -->
<!-- ========================================= -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content border-0">
      <button type="button" class="btn-close position-absolute top-0 end-0 m-4 z-3" data-bs-dismiss="modal"></button>
      <div class="modal-body">
        <!-- Content will be inserted via JavaScript -->
      </div>
    </div>
  </div>
</div>

<!-- ========================================= -->
<!-- MODAL: BUKTI PEMBAYARAN -->
<!-- ========================================= -->
<div class="modal fade" id="buktiModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title text-white mb-4">
          <i class="ri-image-line me-2"></i>Bukti Pembayaran
        </h5>
        <button type="button" class="btn-close btn-close-white mb-2" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <!-- Zoom Controls -->
        <div class="zoom-controls mb-3">
          <button type="button" class="btn btn-sm btn-outline-dark me-1" id="btnZoomOut" title="Zoom Out">
            <i class="ri-zoom-out-line"></i>
          </button>
          <span id="zoomLevel" class="mx-2 fw-bold">100%</span>
          <button type="button" class="btn btn-sm btn-outline-dark ms-1" id="btnZoomIn" title="Zoom In">
            <i class="ri-zoom-in-line"></i>
          </button>
          <button type="button" class="btn btn-sm btn-outline-dark ms-3" id="btnZoomReset" title="Reset Zoom">
            <i class="ri-refresh-line"></i>
          </button>
        </div>
        
        <!-- Image Container with Zoom -->
        <div id="imageContainer" style="overflow: hidden; position: relative; max-height: 55vh; border-radius: 8px; background: #f4f4f5; cursor: grab;">
          <img id="buktiImage" src="" alt="Bukti Pembayaran" 
               style="transition: transform 0.2s ease; transform-origin: center center; max-width: 100%; max-height: 55vh; box-shadow: 0 4px 20px rgba(0,0,0,0.15); border-radius: 8px;">
        </div>
        
        <p class="text-muted small mt-2 mb-0">
          <i class="ri-information-line me-1"></i>Gunakan tombol zoom atau scroll mouse untuk memperbesar/memperkecil. Klik dan drag untuk menggeser gambar.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mt-2" id="btnBackToDetail">
          <i class="ri-arrow-left-line me-1"></i>Kembali
        </button>
        <button type="button" class="btn btn-success mt-2" id="btnKonfirmasiBukti">
          <i class="ri-check-circle-line me-1"></i> Verifikasi Tagihan
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ========================================= -->
<!-- MODAL: EDIT PAKET -->
<!-- ========================================= -->
<div class="modal fade" id="editPaketModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #18181b;">
        <h5 class="modal-title text-white mb-4">
          <i class="ri-edit-line me-2"></i>Edit Tagihan
        </h5>
        <button type="button" class="btn-close btn-close-white mb-2" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <p class="text-muted mb-2">Mengedit tagihan untuk:</p>
          <h5 class="fw-bold" id="editNamaTagihan">-</h5>
        </div>
        
        <div class="row">
          <div class="col-12 mb-4">
            <label class="form-label fw-semibold"><i class="ri-box-3-line me-1"></i>Pilih Paket</label>
            <select class="form-select select2-paket" id="selectPaketEdit" style="width: 100%;">
              <option value="">-- Cari dan Pilih Paket --</option>
              @foreach($paket as $p)
                <option value="{{ $p->id }}" data-harga="{{ $p->harga }}" data-kecepatan="{{ $p->kecepatan }}" data-masa="{{ $p->masa_pembayaran ?? 30 }}">
                  {{ $p->nama_paket }} - Rp {{ number_format($p->harga, 0, ',', '.') }} ({{ $p->kecepatan }} Mbps)
                </option>
              @endforeach
            </select>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold"><i class="ri-calendar-line me-1"></i>Tanggal Mulai</label>
            <input type="text" class="form-control flatpickr-date" id="editTanggalMulai" placeholder="Pilih tanggal mulai">
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-semibold"><i class="ri-calendar-event-line me-1"></i>Tanggal Berakhir</label>
            <input type="text" class="form-control flatpickr-date" id="editTanggalBerakhir" placeholder="Pilih tanggal berakhir">
          </div>
        </div>
        
        <div class="detail-section">
          <h6><i class="ri-box-3-line"></i>Preview Paket</h6>
          <div class="detail-item">
            <span class="detail-label"><i class="ri-money-dollar-circle-line"></i>Harga</span>
            <span class="detail-value"><strong id="previewHarga">-</strong></span>
          </div>
          <div class="detail-item">
            <span class="detail-label"><i class="ri-speed-line"></i>Kecepatan</span>
            <span class="detail-value" id="previewKecepatan">-</span>
          </div>
        </div>
        
        <div class="alert alert-warning mt-3" role="alert">
          <i class="ri-information-line me-2"></i>
          <small>Mengubah paket akan memperbarui nominal tagihan sesuai harga paket baru.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mt-2" id="btnBackToDetailFromEdit">
          <i class="ri-arrow-left-line me-1"></i>Kembali
        </button>
        <button type="button" class="btn btn-primary mt-2" id="btnSimpanPaket">
          <i class="ri-save-line me-1"></i>Simpan Perubahan
        </button>
      </div>
    </div>
  </div>
</div>

<script>
// ========================================
// IMAGE ZOOM FUNCTIONALITY
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    const buktiImage = document.getElementById('buktiImage');
    const imageContainer = document.getElementById('imageContainer');
    const zoomLevelSpan = document.getElementById('zoomLevel');
    const btnZoomIn = document.getElementById('btnZoomIn');
    const btnZoomOut = document.getElementById('btnZoomOut');
    const btnZoomReset = document.getElementById('btnZoomReset');
    
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY, translateX = 0, translateY = 0;
    
    // Update zoom display
    function updateZoom() {
        buktiImage.style.transform = `scale(${currentZoom}) translate(${translateX}px, ${translateY}px)`;
        zoomLevelSpan.textContent = Math.round(currentZoom * 100) + '%';
        
        // Change cursor based on zoom level
        if (currentZoom > 1) {
            imageContainer.style.cursor = isDragging ? 'grabbing' : 'grab';
        } else {
            imageContainer.style.cursor = 'default';
            translateX = 0;
            translateY = 0;
        }
    }
    
    // Zoom In
    btnZoomIn.addEventListener('click', function() {
        if (currentZoom < 4) {
            currentZoom += 0.25;
            updateZoom();
        }
    });
    
    // Zoom Out
    btnZoomOut.addEventListener('click', function() {
        if (currentZoom > 0.5) {
            currentZoom -= 0.25;
            if (currentZoom <= 1) {
                translateX = 0;
                translateY = 0;
            }
            updateZoom();
        }
    });
    
    // Reset Zoom
    btnZoomReset.addEventListener('click', function() {
        currentZoom = 1;
        translateX = 0;
        translateY = 0;
        updateZoom();
    });
    
    // Mouse Wheel Zoom
    imageContainer.addEventListener('wheel', function(e) {
        e.preventDefault();
        if (e.deltaY < 0) {
            // Scroll up - Zoom in
            if (currentZoom < 4) {
                currentZoom += 0.15;
                updateZoom();
            }
        } else {
            // Scroll down - Zoom out
            if (currentZoom > 0.5) {
                currentZoom -= 0.15;
                if (currentZoom <= 1) {
                    translateX = 0;
                    translateY = 0;
                }
                updateZoom();
            }
        }
    });
    
    // Dragging for panning
    imageContainer.addEventListener('mousedown', function(e) {
        if (currentZoom > 1) {
            isDragging = true;
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
            imageContainer.style.cursor = 'grabbing';
        }
    });
    
    document.addEventListener('mousemove', function(e) {
        if (isDragging && currentZoom > 1) {
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            buktiImage.style.transform = `scale(${currentZoom}) translate(${translateX / currentZoom}px, ${translateY / currentZoom}px)`;
        }
    });
    
    document.addEventListener('mouseup', function() {
        isDragging = false;
        if (currentZoom > 1) {
            imageContainer.style.cursor = 'grab';
        }
    });
    
    // Reset zoom when modal is closed
    document.getElementById('buktiModal').addEventListener('hidden.bs.modal', function() {
        currentZoom = 1;
        translateX = 0;
        translateY = 0;
        updateZoom();
    });
    
    // Double click to toggle zoom
    buktiImage.addEventListener('dblclick', function() {
        if (currentZoom === 1) {
            currentZoom = 2;
        } else {
            currentZoom = 1;
            translateX = 0;
            translateY = 0;
        }
        updateZoom();
    });
});

// ========================================
// TOAST NOTIFICATION FUNCTION
// ========================================
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'ticket-toast';
    toast.innerHTML = '<i class="ri-check-line" style="color: #4ade80; font-size: 1.2rem;"></i><span style="font-weight: 500;">' + message + '</span>';
    document.body.appendChild(toast);
    
    // Trigger reflow to ensure transition works
    toast.offsetHeight; 
    
    setTimeout(() => toast.classList.add('show'), 50);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 3000);
}
</script>

@endsection
