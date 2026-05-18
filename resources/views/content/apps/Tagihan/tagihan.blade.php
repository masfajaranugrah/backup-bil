@extends('layouts/layoutMaster')

@section('title', 'Daftar Tagihan')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/select2/select2.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/style.css">
<style>
/* ========================================= */
/* FLATPICKR CUSTOM THEME */
.flatpickr-calendar {
  border: 1px solid rgba(0,0,0,0.05) !important;
  box-shadow: 0 20px 40px -10px rgba(0,0,0,0.15) !important;
  border-radius: 16px !important;
  padding: 16px !important;
  width: 360px !important;
  max-width: calc(100vw - 24px) !important;
  font-family: inherit !important;
  background: white !important;
}
.flatpickr-months {
  margin-bottom: 12px !important;
}
.flatpickr-current-month {
  font-size: 1rem !important;
  padding: 0 !important;
}
.flatpickr-current-month .flatpickr-monthDropdown-months {
  font-weight: 700 !important;
  font-size: 1rem !important;
}
.flatpickr-days,
.dayContainer {
  width: 100% !important;
}
.flatpickr-months .flatpickr-prev-month, 
.flatpickr-months .flatpickr-next-month {
  top: 0 !important;
  padding: 10px !important;
}
.flatpickr-monthSelect-months {
    gap: 8px;
    display: flex !important;
    flex-wrap: wrap !important;
    justify-content: space-between !important;
}
.flatpickr-monthSelect-month {
    border-radius: 10px !important;
    padding: 12px 0 !important;
    font-weight: 500 !important;
    font-size: 0.9rem !important;
    width: 31% !important;
    margin: 0 !important;
    margin-bottom: 8px !important;
    transition: all 0.2s ease !important;
    background: #f4f4f5 !important;
    color: #52525b !important;
    border: 1px solid transparent !important;
}
.flatpickr-monthSelect-month:hover {
    background: #e4e4e7 !important;
    color: #18181b !important;
}
.flatpickr-monthSelect-month.selected {
    background: #18181b !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(24,24,27,0.3) !important;
}

/* Broadcast datepicker: prevent right-side clipping on modal */
.flatpickr-calendar.broadcast-date-picker {
  width: 300px !important;
  max-width: calc(100vw - 24px) !important;
  padding: 10px 12px 12px !important;
}

.flatpickr-calendar.broadcast-date-picker .flatpickr-days,
.flatpickr-calendar.broadcast-date-picker .dayContainer {
  width: 100% !important;
}

.light-style .flatpickr-calendar, .light-style .flatpickr-days{
  width: 300px !important;
}
/* MODERN CLEAN STYLES */
/* ========================================= */
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

/* Search Button - Black (shadcn-like) */
.btn.btn-search-dark,
.btn-search-dark {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
}

.btn.btn-search-dark:hover,
.btn-search-dark:hover {
  background: #27272a !important;
  background-color: #27272a !important;
  border-color: #27272a !important;
  color: #fafafa !important;
}

.btn.btn-search-dark:focus,
.btn-search-dark:focus,
.btn.btn-search-dark:focus-visible,
.btn-search-dark:focus-visible {
  outline: none !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
}

.btn.btn-search-dark:active,
.btn-search-dark:active {
  background: #09090b !important;
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

/* Status Belum Bayar - Red with white text, rounded */
.badge.bg-danger {
  background: #dc2626 !important;
  color: #fafafa !important;
  border: none !important;
  border-radius: 9999px !important;
}

/* Solid badges default */
.bg-info,
.bg-warning,
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
  padding: 1.25rem 1.5rem;
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

.modal-header.bg-primary {
  background: #18181b !important;
  border-bottom: none;
}

.modal-header.bg-primary .modal-title {
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

/* Fullscreen detail modal tanpa ruang footer kosong */
#detailModal .modal-dialog {
  margin: 0;
  max-width: 100%;
  height: 100dvh;
}

#detailModal .modal-content {
  height: 100dvh;
  border-radius: 0;
}

#detailModal .modal-body {
  padding: 0;
  max-height: none;
  height: 100%;
  overflow-y: auto;
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

/* Loading Overlay */
.loading-overlay {
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
}

/* ========================================= */
/* DETAIL MODAL STYLES */
/* ========================================= */
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
  box-shadow: 0 4px 16px rgba(105, 108, 255, 0.4);
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

/* Input Groups */
.input-group-text {
  border-radius: 8px 0 0 8px;
  background: #f8f9fa;
  border: 1px solid #e0e0e0;
  color: #5a5f7d;
  font-weight: 500;
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
  background-color: #fff;
  border-color: #e4e4e7;
  color: #18181b;
}

.pagination .page-item.active .page-link {
  background-color: #fff !important;
  border-color: #e4e4e7 !important;
  color: #18181b !important;
  box-shadow: none;
}

.pagination .page-item.disabled .page-link {
  background-color: #fff;
  border-color: #e4e4e7;
  color: #a1a1aa;
  cursor: not-allowed;
}

/* DataTables pagination styles */
.dataTables_wrapper .dataTables_info {
  float: left !important;
  padding-top: 1.25rem;
  padding-bottom: 1rem;
  color: #71717a;
  font-size: 0.875rem;
}

.dataTables_wrapper .dataTables_paginate {
  float: right !important;
  text-align: right !important;
  padding-top: 1rem;
  padding-bottom: 1rem;
}

.dataTables_wrapper .dataTables_paginate .pagination {
  justify-content: flex-end !important;
  margin: 0 !important;
}

.dataTables_wrapper .dataTables_paginate .page-item .page-link {
  border-radius: 50% !important;
  width: 40px !important;
  height: 40px !important;
  padding: 0 !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  margin: 0 4px !important;
  border: 1px solid #e4e4e7 !important;
  color: #18181b !important;
  background: #fff !important;
  background-color: #fff !important;
  font-weight: 600 !important;
  transition: all 0.3s ease !important;
}

.dataTables_wrapper .dataTables_paginate .page-item .page-link:hover {
  background: #f4f4f5 !important;
  background-color: #f4f4f5 !important;
  border-color: #18181b !important;
  color: #18181b !important;
}

.dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
  background: #18181b !important;
  background-color: #18181b !important;
  border-color: #18181b !important;
  color: #fafafa !important;
}

.dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
  background: #f4f4f5 !important;
  background-color: #f4f4f5 !important;
  border-color: #e4e4e7 !important;
  color: #a1a1aa !important;
  cursor: not-allowed !important;
}

.dataTables_wrapper::after {
  content: '';
  display: table;
  clear: both;
}

.pagination-wrapper .pagination {
  flex-wrap: nowrap;
  gap: 0.35rem;
}

.pagination-wrapper .page-link {
  min-width: 40px;
  height: 40px;
  border-radius: 999px !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
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

/* Hide DataTables default controls if using custom pagination */
.dataTables_length,
.dataTables_filter {
  display: none !important;
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
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/select2/select2.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
])
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
@endsection

@section('page-script')
<style>
/* Force hide SweetAlert deny button */
.swal2-deny, .swal2-styled.swal2-deny { display: none !important; }
</style>
<script>
// Data rekening untuk dropdown verifikasi (rendered by Blade, used in JS)
const rekeningList = @json($rekeningList ?? []);

document.addEventListener("DOMContentLoaded", function () {
    const buktiBaseUrl = "{{ asset('storage/bukti_pembayaran') }}";
    const tagihanDetailBaseUrl = "{{ url('dashboard/admin/tagihan/data') }}";

    function buildBuktiUrl(raw) {
        const value = String(raw || '').trim();
        if (!value || value === '-') return '';

        // Jika sudah berupa full URL yang valid (http/https) → ekstrak filename saja
        try {
            const parsed = new URL(value);
            // Adalah URL absolut – ambil bagian filename dari pathname
            const fileName = parsed.pathname.split('/').filter(Boolean).pop();
            if (fileName && fileName !== '-') {
                return `${buktiBaseUrl}/${encodeURIComponent(fileName)}`;
            }
        } catch (e) {
            // Bukan URL absolut, lanjut proses sebagai path relatif / filename
        }

        // Tangani path relatif seperti "bukti_pembayaran/filename.jpg"
        // atau path production seperti "/var/www/.../bukti_pembayaran/filename.jpg"
        const fileName = value.split('/').filter(Boolean).pop();
        if (!fileName || fileName === '-') return '';

        return `${buktiBaseUrl}/${encodeURIComponent(fileName)}`;
    }

    function renderBuktiSection(buktiUrl, tagihanId, isChecked) {
        if (!buktiUrl) {
            return `
                <h4 class="fw-bold mb-4 text-dark"><i class="ri-image-line me-2"></i>Bukti Pembayaran</h4>
                <div class="bg-white p-5 rounded-4 shadow-sm border text-center text-muted d-flex flex-column align-items-center gap-3">
                    <i class="ri-image-add-line" style="font-size:3.5rem; color:#cbd5e1;"></i>
                    <div>
                        <p class="fw-semibold mb-1 text-dark" style="font-size:1rem;">Belum ada bukti pembayaran</p>
                        <p class="mb-0 text-muted" style="font-size:0.875rem;">Pelanggan belum mengunggah bukti pembayaran untuk tagihan ini.</p>
                    </div>
                </div>
            `;
        }

        return `
            <h4 class="fw-bold mb-4 text-dark"><i class="ri-image-line me-2"></i>Bukti Pembayaran</h4>
            <div class="bg-white p-4 rounded-4 shadow-sm border">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <span class="badge ${isChecked ? 'bg-success' : 'bg-secondary'} rounded-pill px-3 py-2 bukti-checked-badge" data-tagihan-id="${tagihanId}">
                            <i class="ri-pushpin-${isChecked ? 'fill' : 'line'} me-1"></i>
                            ${isChecked ? 'Sudah dicek' : 'Belum dicek'}
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm ${isChecked ? 'btn-success' : 'btn-outline-success'} btn-toggle-checked"
                            data-tagihan-id="${tagihanId}">
                            <i class="ri-check-line me-1"></i>${isChecked ? 'Ditandai' : 'Tandai Sudah Dicek'}
                        </button>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mb-3">
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-zoom-out">-</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-zoom-in">+</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary btn-zoom-reset">Reset</button>
                </div>
                <div class="bukti-viewport border rounded-3" style="height: 420px; overflow: hidden; position: relative; cursor: grab; background:#f8fafc;">
                    <img src="${buktiUrl}" class="bukti-zoom-img" style="position:absolute; top:50%; left:50%; transform: translate(-50%, -50%) scale(1); transform-origin: center center; max-width: none; user-select: none; -webkit-user-drag: none;">
                </div>
                <div class="mt-4 text-center">
                    <a href="${buktiUrl}" target="_blank" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm"><i class="ri-download-line me-2"></i> Unduh Bukti Pembayaran</a>
                </div>
            </div>
        `;
    }

    function decrementRecordsCounter() {
        const counterEl = document.querySelector('.toolbar-top .text-muted');
        if (!counterEl) return;
        const match = counterEl.textContent.match(/\d+/);
        if (!match) return;
        const current = parseInt(match[0], 10);
        if (Number.isNaN(current)) return;
        const next = Math.max(0, current - 1);
        counterEl.textContent = `${next} RECORDS FOUND`;
    }

    function showBottomToast(message, type = 'success') {
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
        toast.innerHTML = `<span class="material-symbols-rounded" style="font-size:1.15rem;line-height:1;color:${isError ? '#fecaca' : '#86efac'}">${isError ? 'error' : 'check_circle'}</span><span>${message}</span>`;
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

    // ========================================
    // HELPER FUNCTIONS
    // ========================================
    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }

    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    const formatDate = d => d.toISOString().split('T')[0];

    const filterPanel = document.getElementById('tagihanFilterPanel');
    const filterToggle = document.getElementById('toggleTagihanFilters');
    const filterClose = document.getElementById('closeTagihanFilters');
    const topSearchInput = document.getElementById('topSearchInput');

    function setFilterPanel(open) {
        if (!filterPanel || !filterToggle) return;
        filterPanel.classList.toggle('is-open', open);
        filterToggle.classList.toggle('is-active', open);
        filterToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    filterToggle?.addEventListener('click', function () {
        setFilterPanel(!filterPanel?.classList.contains('is-open'));
    });

    filterClose?.addEventListener('click', function () {
        setFilterPanel(false);
    });

    document.addEventListener('click', function (event) {
        if (!filterPanel || !filterToggle) return;
        if (!filterPanel.classList.contains('is-open')) return;
        if (filterPanel.contains(event.target) || filterToggle.contains(event.target)) return;
        setFilterPanel(false);
    });

    topSearchInput?.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            $('#filterForm').submit();
        }
    });

    if (window.matchMedia('(max-width: 767.98px)').matches) {
        document.querySelectorAll('.tagihan-tool-btn[title]').forEach(function (button) {
            button.dataset.mobileTitle = button.getAttribute('title') || '';
            button.removeAttribute('title');
        });
    }

    // ========================================
    // FLATPICKR INITIALIZATION
    // ========================================
    $(document).on('shown.bs.modal', '[id^="modalEditTagihan-"]', function () {
        const $modal = $(this);

        $modal.find('.select2-edit-paket').each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) return;
            $(this).select2({
                dropdownParent: $modal,
                width: '100%',
                placeholder: '-- Cari dan Pilih Paket --'
            });
        });

        flatpickr($(this).find('.flatpickr-edit-start'), {
            dateFormat: "Y-m-d",
        allowInput: true,
        minDate: null,
        disableMobile: true
        });
        flatpickr($(this).find('.flatpickr-edit-end'), {
            dateFormat: "Y-m-d",
        allowInput: true,
        minDate: null,
        disableMobile: true
        });
    });

    flatpickr("#tanggal_mulai", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        defaultDate: new Date(),
        allowInput: true,
        locale: "id",
        disableMobile: true
    });

    flatpickr("#tanggal_berakhir", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "j F Y",
        allowInput: false,
        locale: "id",
        disableMobile: true
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
                    page: params.page || 1,
                    filter_no_tagihan: 1 // Filter pelanggan yang belum punya tagihan
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

    // ========================================
    // AUTO SUBMIT ON FILTER CHANGE
    // ========================================
    $('#statusFilter').on('change', function() {
        $('#filterForm').submit();
    });

    // Flatpickr for month/year filter
    flatpickr('#periodeTrigger', {
        plugins: [new monthSelectPlugin({
            shorthand: true,
            dateFormat: "Y-m",
            altFormat: "F Y",
            theme: "light"
        })],
        locale: "id", // Use Indonesian locale
        disableMobile: true,
        defaultDate: "{{ request('periode') }}",
        onChange: function(selectedDates, dateStr) {
            if (dateStr) {
                $('#periodeInput').val(dateStr);
                showLoading();
                $('#filterForm').submit();
            }
        }
    });

    window.resetFilterTagihan = function(e) {
        e.stopPropagation();
        showLoading();
        // Remove all filter params
        const url = new URL(window.location.href);
        url.searchParams.delete('periode');
        url.searchParams.delete('harga_paket');
        url.searchParams.delete('search');
        window.location.href = url.toString();
    }

    // ========================================
    // LOADING OVERLAY ON FORM SUBMIT
    // ========================================
    $('#filterForm').on('submit', function() {
        showLoading();
    });

    // ========================================
    // SWEETALERT DELETE
    // ========================================
    function deleteTagihanRealtime(form) {
        const $form = $(form);
        const actionUrl = $form.attr('action');
        const $row = $form.closest('tr');
        const namaPelanggan = ($row.data('nama') || '').toString().trim();

        return Swal.fire({
            title: 'Konfirmasi Penghapusan',
            html: '<p class="mb-0">Yakin ingin menghapus tagihan ini?<br><strong class="text-danger">Data tidak dapat dikembalikan!</strong></p>',
            icon: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            showDenyButton: false,
            confirmButtonColor: '#f5365c',
            cancelButtonColor: '#8898aa',
            confirmButtonText: '<i class="ri-delete-bin-line me-1"></i>Hapus',
            cancelButtonText: 'Batal',
            allowOutsideClick: false,
            allowEscapeKey: false,
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                $.ajax({
                    url: actionUrl,
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
                        hideLoading();
                        if (resp.success) {
                            // Hapus baris dari tabel dengan animasi fade
                            $row.fadeOut(400, function() {
                                $(this).remove();
                                decrementRecordsCounter();

                                // Update badge total tagihan
                                const $totalBadge = $('.tagihan-count-badge');
                                if ($totalBadge.length) {
                                    const currentTotal = parseInt($totalBadge.text().replace(/\D/g, ''), 10) || 0;
                                    const nextTotal = Math.max(0, currentTotal - 1);
                                    $totalBadge.html(`<span class="material-symbols-rounded" style="font-size:1rem;">database</span> ${nextTotal} Tagihan`);
                                }
                            });

                            // Notif kanan bawah setelah delete berhasil
                            showBottomToast(`Tagihan ${namaPelanggan || 'pelanggan ini'} berhasil dihapus.`);
                        } else {
                            Swal.fire('Gagal!', resp.message || 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        const msg = xhr.responseJSON?.message || xhr.statusText || 'Terjadi kesalahan server.';
                        Swal.fire('Gagal!', msg, 'error');
                    }
                });
            }
        });
    }

    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        e.stopPropagation();
        deleteTagihanRealtime(this);
    });

    $(document).on('click', '.btn-delete-tagihan', function(e) {
        e.preventDefault();
        e.stopPropagation();
        deleteTagihanRealtime(this.closest('form'));
    });

    // ========================================
    // EDIT TAGIHAN TANPA RELOAD (REALTIME)
    // ========================================
    $(document).on('submit', '.edit-tagihan-form', function(e) {
        e.preventDefault();
        const form = this;
        const $form = $(form);
        const actionUrl = $form.attr('action');
        const tagihanId = $form.data('tagihan-id');
        const formData = new FormData(form);
        const $row = $(`tr[data-tagihan-id="${tagihanId}"]`);
        const namaPelanggan = ($row.data('nama') || '').toString().trim();

        showLoading();

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(resp) {
                hideLoading();
                if (!resp.success) {
                    Swal.fire('Gagal!', resp.message || 'Terjadi kesalahan.', 'error');
                    return;
                }

                const d = resp.data || {};
                const tanggalBerakhir = d.tanggal_berakhir_formatted || '-';
                const catatan = d.catatan || '-';
                const paketNama = d.paket_nama || $row.attr('data-paket') || '-';
                const hargaFormatted = d.harga_formatted || $row.attr('data-harga') || '-';
                const kecepatanText = d.kecepatan ? `${d.kecepatan} Mbps` : ($row.attr('data-kecepatan') || '-');

                // Update isi row realtime
                $row.attr('data-jatuh-tempo', tanggalBerakhir);
                $row.attr('data-catatan', catatan);
                $row.attr('data-paket', paketNama);
                $row.attr('data-harga', hargaFormatted);
                $row.attr('data-kecepatan', kecepatanText);
                if (d.paket_id) {
                    $row.attr('data-paket-id', d.paket_id);
                }

                $row.find('td:nth-child(3) .date-cell').html(`${tanggalBerakhir}<span class="time"></span>`);
                $row.find('td:nth-child(2) .product-info span').text(`ID: ${$row.attr('data-nomor-id') || '-'} | ${paketNama}`);
                $row.find('td:nth-child(5)').text(hargaFormatted);

                // Tutup modal
                const modalEl = form.closest('.modal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                showBottomToast(`Tagihan ${namaPelanggan || 'pelanggan'} berhasil diperbarui.`);
            },
            error: function(xhr) {
                hideLoading();
                const msg = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                Swal.fire('Gagal!', msg, 'error');
            }
        });
    });

    // ========================================
    // KONFIRMASI PEMBAYARAN (VERIFIKASI LUNAS)
    // ========================================
    $(document).on('click', '.btn-konfirmasi', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const id = $(this).data('id');
        const nama = $(this).data('nama');

        Swal.fire({
            title: 'Verifikasi Tagihan Lunas',
        html: (() => {
                const opts = rekeningList.map(r =>
                    `<option value="${r.id}">${r.nama_bank}</option>`
                ).join('');
                return `
                    <p class="mb-3">Konfirmasi pembayaran untuk <strong>${nama}</strong>?</p>
                    <div class="text-start">
                        <label class="form-label small fw-semibold mb-1">Tipe Pembayaran</label>
                        <select id="swal-type-bayar" class="form-select form-select-sm mb-3">
                            <option value="">— Pilih Metode —</option>
                            ${opts}
                            <option value="cash">Cash / Tunai</option>
                        </select>
                        <label class="form-label small fw-semibold mb-1">Upload Bukti (Opsional)</label>
                        <input type="file" id="swal-bukti" class="form-control form-control-sm" accept="image/*,.pdf">
                    </div>
                `;
            })(),
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true,
            showDenyButton: false,
            confirmButtonText: '<i class="ri-checkbox-circle-line me-1"></i>Verifikasi Lunas',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-sm me-2',
                cancelButton: 'btn btn-sm btn-secondary'
            },
            allowOutsideClick: false,
            allowEscapeKey: true,
            didOpen: () => {
                // Force style confirm button
                const confirmBtn = Swal.getConfirmButton();
                if (confirmBtn) {
                    confirmBtn.style.background = '#16a34a';
                    confirmBtn.style.color = '#fff';
                    confirmBtn.style.border = '1px solid #16a34a';
                }
                // Force hide deny button if somehow rendered
                const denyBtn = Swal.getDenyButton();
                if (denyBtn) denyBtn.style.display = 'none';
            },
            preConfirm: () => {
                const typeBayar = document.getElementById('swal-type-bayar').value;
                if (!typeBayar) {
                    Swal.showValidationMessage('Pilih tipe pembayaran terlebih dahulu');
                    return false;
                }
                return typeBayar;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                const formData = new FormData();
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                formData.append('type_pembayaran', result.value);

                const buktiFile = document.getElementById('swal-bukti') ? document.getElementById('swal-bukti').files[0] : null;
                if (buktiFile) {
                    formData.append('bukti_pembayaran', buktiFile);
                }

                $.ajax({
                    url: `/dashboard/admin/tagihan/konfirmasi/${id}`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(resp) {
                        hideLoading();
                        if (resp.success) {
                            const $row = $(`tr[data-tagihan-id="${id}"]`);
                            if ($row.length) {
                                // Di halaman "belum bayar", yang sudah lunas langsung disembunyikan.
                                $row.fadeOut(350, function() {
                                    $(this).remove();
                                    decrementRecordsCounter();
                                });
                            }

                            showBottomToast(`Tagihan ${nama} telah ditandai lunas.`);
                        } else {
                            Swal.fire('Gagal!', resp.message || 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        const msg = xhr.responseJSON?.message || 'Terjadi kesalahan server.';
                        Swal.fire('Gagal!', msg, 'error');
                    }
                });
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
        allowInput: true,
        minDate: null,
        disableMobile: true
        });
        flatpickr(".flatpickr-select-start-end", {
            dateFormat: "Y-m-d",
            defaultDate: new Date().fp_incr(7),
        allowInput: true,
        minDate: null,
        disableMobile: true
        });

        // Reset search dan checkbox saat modal dibuka
        $('#searchPelanggan').val('');
        $('#selectAllPelanggan').prop('checked', false);
        $('.pelanggan-checkbox').prop('checked', false);
        $('.pelanggan-item').show();
        updateSelectedCount();
    });

    // ========================================
    // SEARCH PELANGGAN
    // ========================================
    $(document).on('keyup input paste', '#searchPelanggan', function() {
        const searchTerm = $(this).val().toLowerCase().trim();

        // Hapus pesan "tidak ada hasil" jika ada
        $('#noResultMessage').remove();

        if (searchTerm === '') {
            $('.pelanggan-item').show();
            updateSelectAllState();
            return;
        }

        let visibleCount = 0;
        $('.pelanggan-item').each(function() {
            const $item = $(this);
            const nama = String($item.attr('data-nama') || '').toLowerCase();
            const nomerId = String($item.attr('data-nomer-id') || '').toLowerCase();
            const wa = String($item.attr('data-wa') || '').toLowerCase();

            // Normalize search term (hapus spasi, dash, dll untuk nomor)
            const normalizedSearch = searchTerm.replace(/[\s\-+]/g, '');
            const normalizedWa = wa.replace(/[\s\-+]/g, '');

            if (nama.includes(searchTerm) ||
                nomerId.includes(searchTerm) ||
                wa.includes(searchTerm) ||
                normalizedWa.includes(normalizedSearch)) {
                $item.show();
                visibleCount++;
            } else {
              // Sembunyikan saja tanpa menghapus pilihan supaya tidak hilang saat berganti search
              $item.hide();
            }
        });

        // Update select all state setelah filter
        updateSelectAllState();
        updateSelectedCount();

        // Jika tidak ada hasil, tampilkan pesan
        if (visibleCount === 0) {
            $('#pelangganList').append('<div id="noResultMessage" class="text-center py-3 text-muted"><i class="ri-search-line me-1"></i>Tidak ada hasil ditemukan</div>');
        }
    });

    // ========================================
    // SELECT ALL
    // ========================================
    $('#selectAllPelanggan').on('change', function() {
        const isChecked = $(this).prop('checked');
        $('.pelanggan-item:visible .pelanggan-checkbox').prop('checked', isChecked);
        updateSelectedCount();
    });

    // ========================================
    // INDIVIDUAL CHECKBOX
    // ========================================
    $(document).on('change', '.pelanggan-checkbox', function() {
        updateSelectedCount();
        updateSelectAllState();
    });

    // ========================================
    // UPDATE SELECTED COUNT
    // ========================================
    function updateSelectedCount() {
        const count = $('.pelanggan-checkbox:checked').length;
        $('#selectedCount').text(count + ' dipilih');
        $('#submitCount').text(count);

        // Disable submit jika tidak ada yang dipilih
        if (count === 0) {
            $('#btnSubmitMass').prop('disabled', true).addClass('opacity-50');
        } else {
            $('#btnSubmitMass').prop('disabled', false).removeClass('opacity-50');
        }
    }

    // ========================================
    // UPDATE SELECT ALL STATE
    // ========================================
    function updateSelectAllState() {
        const visibleCheckboxes = $('.pelanggan-item:visible .pelanggan-checkbox');
        const checkedCheckboxes = $('.pelanggan-item:visible .pelanggan-checkbox:checked');

        if (visibleCheckboxes.length === 0) {
            $('#selectAllPelanggan').prop('checked', false);
        } else {
            $('#selectAllPelanggan').prop('checked', visibleCheckboxes.length === checkedCheckboxes.length);
        }
    }

    // ========================================
    // FORM SUBMIT VALIDATION
    // ========================================
    $('#formMassTagihan').on('submit', function(e) {
        const selectedCount = $('.pelanggan-checkbox:checked').length;
        if (selectedCount === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Pilih minimal 1 pelanggan untuk dibuatkan tagihan.',
                confirmButtonText: 'OK'
            });
            return false;
        }

        showLoading();
    });

    // ========================================
    // ? BUTTON DETAIL - SHOW MODAL
    // ========================================
    $(document).on('click', '.btn-detail', function() {
        const row = this.closest('tr');
        const readDetail = (key, fallback = '-') => {
            const attrName = 'data-' + key.replace(/[A-Z]/g, letter => '-' + letter.toLowerCase());
            const value = this.dataset?.[key] ?? row?.dataset?.[key] ?? this.getAttribute(attrName) ?? row?.getAttribute(attrName);
            return value === undefined || value === null || value === '' ? fallback : value;
        };

        // Ambil data dari data-attribute agar tidak rusak saat struktur tabel berubah.
        const nomorId = readDetail('nomorId');
        const namaLengkap = readDetail('nama');
        const noWhatsappDisplay = readDetail('whatsapp');
        const noWhatsapp = noWhatsappDisplay.replace(/\D/g, '');
        const tagihanId = readDetail('tagihanId', '');
        const status = readDetail('status');
        const paket = readDetail('paket');
        const harga = readDetail('harga');
        const alamat = readDetail('alamat');
        const kecamatan = readDetail('kecamatan');
        const kabupaten = readDetail('kabupaten');
        const provinsi = readDetail('provinsi');
        const kecepatan = readDetail('kecepatan');
        const tanggalMulai = readDetail('tanggalMulai');
        const jatuhTempo = readDetail('jatuhTempo');
        const catatan = readDetail('catatan');
        const buktiUrl = buildBuktiUrl(readDetail('bukti', ''));

        // Badge status color
        const normalizedStatus = String(status).toLowerCase();
        const isProcessStatus = normalizedStatus.includes('proses') || normalizedStatus.includes('verifikasi');
        const statusClass = normalizedStatus.includes('lunas') ? 'bg-success' : (isProcessStatus ? 'bg-warning' : 'bg-danger');
        const statusIcon = normalizedStatus.includes('lunas') ? 'checkbox-circle' : (isProcessStatus ? 'time' : 'close-circle');
        const checkedKey = `tagihan_checked_${tagihanId}`;
        const isChecked = localStorage.getItem(checkedKey) === '1';

        // Build modal content (Premium Fullscreen UI)
        const modalContent = `
            <div class="row g-0 min-vh-100">
                <!-- Left Sidebar -->
                <div class="col-lg-4 col-xl-3 border-end bg-light p-4 p-xl-5 d-flex flex-column align-items-center">
                    <div class="customer-avatar mb-4" style="width: 120px; height: 120px; font-size: 3.5rem;">
                        ${(namaLengkap || '-').charAt(0).toUpperCase()}
                    </div>
                    <h3 class="fw-bold text-center mb-1" style="color: #1e293b;">${namaLengkap}</h3>
                    <p class="text-muted text-center mb-4 fs-5">${nomorId}</p>
                    
                    <span class="badge ${statusClass} rounded-pill px-4 py-2 mb-5 fs-6 shadow-sm">
                        <i class="ri-${statusIcon}-line me-1"></i> ${status.toUpperCase()}
                    </span>

                    <div class="w-100 mt-2">
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-whatsapp-line text-success fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">WhatsApp</small>
                                <a href="https://wa.me/${noWhatsapp}" target="_blank" class="text-dark fw-bold text-decoration-none fs-5">${noWhatsappDisplay}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-map-pin-line text-primary fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Alamat</small>
                                <span class="text-dark fw-bold fs-6">${alamat}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-building-line text-info fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Area</small>
                                <span class="text-dark fw-bold fs-6">${kecamatan}, ${kabupaten}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content -->
                <div class="col-lg-8 col-xl-9 p-4 p-xl-5">
                    <div class="max-w-4xl mx-auto py-4">
                        <h2 class="fw-bold mb-2 text-dark">Ringkasan Tagihan</h2>
                        <p class="text-muted mb-5 fs-5">Rincian paket layanan dan status penagihan pelanggan.</p>
                        
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 rounded-4" style="background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%); border-left: 6px solid #3b82f6 !important;">
                                    <div class="card-body p-4 p-xl-5">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div>
                                                <p class="text-primary fw-bold mb-1 text-uppercase" style="letter-spacing: 1px;">Paket Layanan</p>
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
                                <div class="card border-0 shadow-sm h-100 rounded-4" style="background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%); border-left: 6px solid #ef4444 !important;">
                                    <div class="card-body p-4 p-xl-5">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div>
                                                <p class="text-danger fw-bold mb-1 text-uppercase" style="letter-spacing: 1px;">Jatuh Tempo</p>
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

                        <h4 class="fw-bold mb-4 text-dark"><i class="ri-file-text-line me-2"></i>Catatan Tambahan</h4>
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-5">
                            <p class="mb-0 text-secondary fs-5" style="line-height: 1.6;">${catatan || 'Tidak ada catatan khusus untuk pelanggan ini.'}</p>
                        </div>

                        <div class="bukti-section-wrap" data-tagihan-id="${tagihanId}">
                            ${renderBuktiSection(buktiUrl, tagihanId, isChecked)}
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                            ${normalizedStatus.includes('lunas') ? '' : `
                            <button type="button" class="btn btn-success rounded-pill px-4 btn-modal-verify"
                                data-id="${tagihanId}" data-nama="${namaLengkap}">
                                <i class="ri-checkbox-circle-line me-1"></i>Verifikasi
                            </button>
                            `}
                            <button type="button" class="btn btn-dark rounded-pill px-4 btn-modal-edit"
                                data-tagihan-id="${tagihanId}">
                                <i class="ri-edit-2-line me-1"></i>Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Populate modal dan tampilkan
        $('#detailModal .modal-body').html(modalContent);
        $('#detailModal').modal('show');

        if (tagihanId) {
            $.getJSON(`${tagihanDetailBaseUrl}/${tagihanId}`)
                .done(function(resp) {
                    const rawBukti = resp?.data?.bukti_pembayaran ?? '';
                    const detailBuktiUrl = buildBuktiUrl(rawBukti);
                    const checked = localStorage.getItem(checkedKey) === '1';
                    // Selalu update section bukti dari server (termasuk jika kosong → tampil pesan)
                    $(`.bukti-section-wrap[data-tagihan-id="${tagihanId}"]`)
                        .html(renderBuktiSection(detailBuktiUrl, tagihanId, checked));
                });
        }
    });

    // Aksi dari tombol dalam modal detail
    $(document).on('click', '.btn-modal-verify', function() {
        const id = $(this).data('id');
        $('#detailModal').modal('hide');
        setTimeout(() => {
            $(`.btn-konfirmasi[data-id="${id}"]`).first().trigger('click');
        }, 180);
    });

    $(document).on('click', '.btn-modal-edit', function() {
        const tagihanId = $(this).data('tagihan-id');
        $('#detailModal').modal('hide');
        setTimeout(() => {
            $(`#modalEditTagihan-${tagihanId}`).modal('show');
        }, 180);
    });

    // Zoom + drag bukti pembayaran
    function applyBuktiTransform($viewport) {
        const $img = $viewport.find('.bukti-zoom-img');
        const scale = Number($viewport.attr('data-scale') || 1);
        const x = Number($viewport.attr('data-x') || 0);
        const y = Number($viewport.attr('data-y') || 0);
        $img.css('transform', `translate(calc(-50% + ${x}px), calc(-50% + ${y}px)) scale(${scale})`);
    }

    $(document).on('mouseenter', '.bukti-viewport', function() {
        const $viewport = $(this);
        if (!$viewport.attr('data-init')) {
            $viewport.attr('data-init', '1');
            $viewport.attr('data-scale', '1');
            $viewport.attr('data-x', '0');
            $viewport.attr('data-y', '0');
            applyBuktiTransform($viewport);
        }
    });

    $(document).on('wheel', '.bukti-viewport', function(e) {
        e.preventDefault();
        const $viewport = $(this);
        const current = Number($viewport.attr('data-scale') || 1);
        const delta = e.originalEvent.deltaY < 0 ? 0.15 : -0.15;
        const next = Math.min(6, Math.max(0.5, current + delta));
        $viewport.attr('data-scale', String(next));
        applyBuktiTransform($viewport);
    });

    $(document).on('mousedown', '.bukti-viewport', function(e) {
        const $viewport = $(this);
        $viewport.attr('data-dragging', '1');
        $viewport.attr('data-start-x', String(e.clientX));
        $viewport.attr('data-start-y', String(e.clientY));
        $viewport.css('cursor', 'grabbing');
    });

    $(document).on('mousemove', function(e) {
        const $viewport = $('.bukti-viewport[data-dragging="1"]');
        if (!$viewport.length) return;
        const startX = Number($viewport.attr('data-start-x') || 0);
        const startY = Number($viewport.attr('data-start-y') || 0);
        const lastX = Number($viewport.attr('data-x') || 0);
        const lastY = Number($viewport.attr('data-y') || 0);

        const dx = e.clientX - startX;
        const dy = e.clientY - startY;
        $viewport.attr('data-start-x', String(e.clientX));
        $viewport.attr('data-start-y', String(e.clientY));
        $viewport.attr('data-x', String(lastX + dx));
        $viewport.attr('data-y', String(lastY + dy));
        applyBuktiTransform($viewport);
    });

    $(document).on('mouseup', function() {
        $('.bukti-viewport[data-dragging="1"]').attr('data-dragging', '0').css('cursor', 'grab');
    });

    $(document).on('click', '.btn-zoom-in, .btn-zoom-out, .btn-zoom-reset', function() {
        const $box = $(this).closest('.bg-white');
        const $viewport = $box.find('.bukti-viewport');
        if (!$viewport.length) return;
        const current = Number($viewport.attr('data-scale') || 1);

        if ($(this).hasClass('btn-zoom-reset')) {
            $viewport.attr('data-scale', '1');
            $viewport.attr('data-x', '0');
            $viewport.attr('data-y', '0');
        } else if ($(this).hasClass('btn-zoom-in')) {
            $viewport.attr('data-scale', String(Math.min(6, current + 0.2)));
        } else {
            $viewport.attr('data-scale', String(Math.max(0.5, current - 0.2)));
        }
        applyBuktiTransform($viewport);
    });

    // Tandai bukti sudah dicek (persist di browser per tagihan)
    $(document).on('click', '.btn-toggle-checked', function() {
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
                .html('<i class="ri-check-line me-1"></i>Tandai Sudah Dicek');
            $badge.removeClass('bg-success').addClass('bg-secondary')
                .html('<i class="ri-pushpin-line me-1"></i>Belum dicek');
        }
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
  <!-- MODERN TOOLBAR & DAFTAR TAGIHAN -->
  <!-- ========================================= -->
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,400,0,0');

    .tagihan-page-shell,
    .tagihan-page-shell *,
    .tagihan-grid-card,
    .tagihan-grid-card *,
    .pagination-wrapper,
    .pagination-wrapper * {
      font-family: 'Inter', sans-serif !important;
    }

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

    .tagihan-page-shell {
      max-width: 100%;
    }

    .tagihan-page-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      gap: 1.25rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .tagihan-title-row {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      margin-bottom: 0.35rem;
    }

    .tagihan-title-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #18181b;
      background: #f4f4f5;
      border: 1px solid #e4e4e7;
    }

    .tagihan-count-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.45rem 0.85rem;
      border-radius: 999px;
      color: #3f3f46;
      background: #f4f4f5;
      border: 1px solid #e4e4e7;
      font-size: 0.78rem;
      font-weight: 700;
    }

    .tagihan-page-actions {
      display: flex;
      align-items: center;
      gap: 0.65rem;
      flex-wrap: wrap;
    }

    .tagihan-grid-card {
      background: #fff;
      border: 1px solid #e9edf3;
      border-radius: 14px;
      box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
      overflow: visible;
      position: relative;
    }

    .tagihan-grid-toolbar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #eef2f7;
      background: #fff;
      flex-wrap: wrap;
    }

    .tagihan-toolbar-left,
    .tagihan-toolbar-right {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-wrap: wrap;
    }

    .tagihan-control {
      position: relative;
      min-width: 260px;
    }

    .tagihan-control.search {
      display: block;
      min-width: min(520px, 100%);
      flex: 1 1 420px;
    }

    .tagihan-control .control-icon {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #64748b;
      z-index: 2;
    }

    .tagihan-control .control-chevron {
      position: absolute;
      right: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #64748b;
      pointer-events: none;
      z-index: 2;
    }

    .tagihan-input,
    .tagihan-select {
      width: 100%;
      min-height: 48px;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      background: #fff;
      color: #111827;
      font-size: 0.95rem;
      font-weight: 500;
      padding: 0.75rem 2.7rem 0.75rem 3rem;
      outline: none;
      transition: border-color 0.16s ease, box-shadow 0.16s ease;
    }

    .tagihan-select {
      appearance: none;
      cursor: pointer;
    }

    .tagihan-input::placeholder {
      color: #94a3b8;
      font-weight: 500;
    }

    .tagihan-input:focus,
    .tagihan-select:focus {
      border-color: #18181b;
      box-shadow: 0 0 0 4px rgba(24, 24, 27, 0.12);
    }

    .tagihan-tool-btn {
      border: 0;
      background: transparent;
      color: #252b35;
      min-height: 42px;
      padding: 0.6rem 0.75rem;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      font-weight: 700;
      cursor: pointer;
      transition: background 0.16s ease, color 0.16s ease;
    }

    .tagihan-tool-btn:hover,
    .tagihan-tool-btn.is-active {
      background: #f4f4f5;
      color: #18181b;
    }

    .tagihan-filter-panel {
      display: none;
      position: absolute;
      top: 74px;
      right: 1.25rem;
      z-index: 200;
      width: min(760px, calc(100% - 2.5rem));
      grid-template-columns: auto minmax(260px, 1fr) auto;
      align-items: end;
      gap: 1rem;
      padding: 1.25rem 1.5rem;
      background: linear-gradient(90deg, #fff4f1 0%, #f5fcff 100%);
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
    }

    .tagihan-filter-panel .tagihan-input,
    .tagihan-filter-panel .tagihan-select {
      background-color: rgba(255, 255, 255, 0.76);
      border-color: #e2e8f0;
      box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
    }

    .tagihan-filter-fields {
      display: grid;
      grid-template-columns: repeat(2, minmax(210px, 1fr));
      gap: 0.9rem;
      width: 100%;
      min-width: 0;
    }

    .tagihan-filter-panel.is-open {
      display: grid !important;
    }

    .tagihan-filter-panel::before {
      content: '';
      position: absolute;
      top: -8px;
      right: 145px;
      width: 16px;
      height: 16px;
      background: #fff;
      border-left: 1px solid #e2e8f0;
      border-top: 1px solid #e2e8f0;
      transform: rotate(45deg);
    }

    .tagihan-filter-close {
      width: 48px;
      height: 48px;
      border-radius: 999px;
      border: 0;
      background: rgba(15, 23, 42, 0.08);
      color: #64748b;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0;
      flex: 0 0 48px;
    }

    .tagihan-filter-field label {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      margin: 0 0 -0.35rem 1rem;
      padding: 0 0.35rem;
      color: #475569;
      background: linear-gradient(90deg, #fff8f5 0%, #f8fcff 100%);
      font-weight: 800;
      font-size: 0.78rem;
      position: relative;
      z-index: 2;
    }

    .tagihan-filter-actions {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      height: 48px;
      margin-bottom: 0;
    }

    .tagihan-filter-actions .btn {
      height: 48px;
      min-width: 48px;
      margin: 0;
      white-space: nowrap;
    }

    .tagihan-filter-actions .btn-outline-secondary {
      padding-left: 0.85rem !important;
      padding-right: 0.85rem !important;
    }

    .tagihan-filter-field .tagihan-input,
    .tagihan-filter-field .tagihan-select {
      min-height: 48px;
      height: 48px;
    }

    /* Custom Table UI Styles based on Screenshot */
    .table-container {
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.03);
      border: 1px solid #f1f5f9;
      overflow: hidden;
    }
    .toolbar-top {
      padding: 1.25rem 1.5rem;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }
    .toolbar-left, .toolbar-right {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .modern-select {
      appearance: none;
      background: #fff;
      border: 1px solid #e2e8f0;
      padding: 0.5rem 2.25rem 0.5rem 1rem;
      border-radius: 8px;
      font-size: 0.875rem;
      font-weight: 500;
      color: #334155;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 14px;
      cursor: pointer;
    }
    .modern-search {
      position: relative;
    }
    .modern-search i {
      position: absolute;
      left: 1rem;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
    }
    .modern-search input {
      padding: 0.5rem 1rem 0.5rem 2.5rem;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      font-size: 0.875rem;
      width: 200px;
      transition: width 0.3s;
    }
    .modern-search input:focus {
      outline: none;
      border-color: #cbd5e1;
      width: 250px;
    }
    .toolbar-btn {
      background: transparent;
      border: none;
      color: #0f172a;
      font-weight: 600;
      font-size: 0.875rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .toolbar-btn:hover {
      background: #f8fafc;
    }
    .toolbar-btn i {
      font-size: 1.1rem;
      color: #475569;
    }
    .modern-table {
      width: 100%;
      border-collapse: collapse;
    }
    .modern-table th {
      text-align: left;
      padding: 1rem 1.25rem;
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #64748b;
      font-weight: 800;
      border-bottom: 1px solid #e5eaf0;
      background: #f8fafc;
    }
    .modern-table td {
      padding: 1.05rem 1.25rem;
      vertical-align: middle;
      border-bottom: 1px dashed #e5eaf0;
      transition: background 0.2s;
    }
    .modern-table tr:hover td {
      background: #fcfcfd;
    }
    
    /* Product / Customer Item */
    .product-cell {
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .product-avatar {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: white;
      font-size: 1.25rem;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .product-info h6 {
      margin: 0 0 0.25rem 0;
      font-weight: 800;
      color: #0f172a;
      font-size: 0.95rem;
    }
    .product-info span {
      font-size: 0.8rem;
      color: #94a3b8;
    }

    /* Date Cell */
    .date-cell {
      color: #0f172a;
      font-weight: 500;
      font-size: 0.875rem;
    }
    .date-cell .time {
      display: block;
      color: #94a3b8;
      font-weight: 400;
      font-size: 0.75rem;
      margin-top: 0.25rem;
    }

    /* Stock/Status Bar */
    .stock-bar-container {
      width: 60px;
      height: 4px;
      background: #f1f5f9;
      border-radius: 2px;
      margin-bottom: 0.5rem;
      overflow: hidden;
    }
    .stock-bar {
      height: 100%;
      border-radius: 2px;
    }
    .stock-text {
      font-size: 0.75rem;
      color: #64748b;
    }

    /* Price */
    .price-cell {
      font-weight: 800;
      color: #0f172a;
      font-size: 1rem;
    }

    /* Status Pill */
    .status-pill {
      padding: 0.35rem 0.75rem;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 600;
      display: inline-flex;
    }
    .status-draft { background: #f1f5f9; color: #475569; }
    .status-published { background: #e0f2fe; color: #0284c7; }
    .status-scheduled { background: #fef3c7; color: #b45309; }

    /* Action Button */
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
      transition: background 0.2s, color 0.2s;
    }
    .action-btn:hover {
      background: #f1f5f9;
      color: #0f172a;
    }

    .action-btn .material-symbols-rounded {
      font-size: 1.2rem;
      line-height: 1;
    }

    .tagihan-action-menu {
      min-width: 160px;
      padding: 0.7rem;
      border: 1px solid #e2e8f0 !important;
      border-radius: 14px !important;
      background: linear-gradient(115deg, #fff4f1 0%, #ffffff 50%, #f1fbff 100%) !important;
      box-shadow: 0 18px 42px rgba(15, 23, 42, 0.16) !important;
      margin-top: -65px !important;
    }

    .tagihan-action-menu::after {
      content: '';
      position: absolute;
      top: 90px;
      right: -8px;
      width: 16px;
      height: 16px;
      background: #f7fdff;
      border-right: 1px solid #e2e8f0;
      border-top: 1px solid #e2e8f0;
      transform: rotate(45deg);
    }

    .tagihan-action-menu .dropdown-item {
      position: relative;
      z-index: 1;
      border-radius: 10px;
      padding: 0.55rem 0.6rem;
      font-weight: 600;
      color: #1f2937;
      gap: 0.65rem;
    }

    .tagihan-action-menu .dropdown-item:hover {
      background: rgba(255, 255, 255, 0.72);
      color: #111827;
    }

    .tagihan-action-menu .dropdown-item.danger-action {
      color: #ff4528 !important;
    }

    .tagihan-action-menu .dropdown-item.danger-action:hover {
      background: rgba(255, 69, 40, 0.08) !important;
    }

    @media (max-width: 1199.98px) {
      .tagihan-grid-toolbar {
        align-items: stretch;
      }

      .tagihan-toolbar-right {
        justify-content: flex-start;
      }
    }

    @media (max-width: 767.98px) {
      html,
      body {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
      }

      .container-fluid {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
        max-width: 100vw;
      }

      .tagihan-page-header {
        align-items: stretch;
        gap: 1rem;
      }

      .tagihan-title-row {
        flex-wrap: wrap;
      }

      .tagihan-title-row h4 {
        font-size: 1.25rem;
      }

      .tagihan-count-badge {
        width: fit-content;
      }

      .tagihan-page-actions,
      .tagihan-page-actions .btn {
        width: 100%;
      }

      .tagihan-page-actions {
        gap: 0.75rem;
      }

      .tagihan-page-actions .btn {
        justify-content: center !important;
        min-height: 46px;
        white-space: nowrap;
      }

      .tagihan-grid-card {
        border-radius: 12px;
        overflow: visible;
        width: 100%;
        max-width: 100%;
      }

      .tagihan-grid-toolbar {
        flex-direction: column;
        align-items: stretch;
        gap: 0.85rem;
      }

      .tagihan-toolbar-left,
      .tagihan-toolbar-right {
        width: 100%;
      }

      .tagihan-control,
      .tagihan-control.search {
        width: 100%;
        min-width: 100%;
        flex-basis: auto;
      }

      .tagihan-input,
      .tagihan-select {
        min-height: 46px;
        height: 46px;
        font-size: 0.92rem;
      }

      .tagihan-grid-toolbar {
        padding: 0.9rem;
      }

      .tagihan-filter-panel.is-open {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        position: fixed;
        top: auto;
        left: 0.75rem;
        right: 0.75rem;
        bottom: max(0.75rem, env(safe-area-inset-bottom));
        width: auto;
        max-width: calc(100vw - 1.5rem);
        padding: 0.95rem;
        gap: 0.75rem;
        border-radius: 18px;
        z-index: 1080;
        background: linear-gradient(100deg, #fff1ed 0%, #ffffff 48%, #eefbff 100%);
        box-shadow: 0 18px 46px rgba(15, 23, 42, 0.24);
        border: 1px solid #e2e8f0;
        box-sizing: border-box;
      }

      .tagihan-filter-panel::before {
        display: none;
      }

      .tagihan-filter-close {
        align-self: flex-start;
        width: 42px;
        height: 42px;
        flex-basis: 42px;
        background: rgba(15, 23, 42, 0.08);
      }

      .tagihan-filter-actions {
        width: 100%;
        height: auto;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 52px;
        gap: 0.65rem;
      }

      .tagihan-filter-actions .btn {
        height: 46px;
        min-width: 0;
      }

      .tagihan-filter-actions .btn-outline-secondary {
        padding-left: 0 !important;
        padding-right: 0 !important;
      }

      .tagihan-filter-field label {
        margin-left: 0.75rem;
        font-size: 0.72rem;
        background: #fff;
      }

      .tagihan-filter-fields {
        grid-template-columns: 1fr;
        gap: 0.75rem;
      }

      .tagihan-toolbar-right {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.55rem;
      }

      .tagihan-tool-btn {
        width: 100%;
        min-height: 42px;
        padding: 0.55rem 0.4rem;
        font-size: 0.84rem;
        justify-content: center;
        min-width: 0;
        background: #f8fafc;
        border: 1px solid #e5eaf0;
      }

      .tagihan-tool-btn .material-symbols-rounded {
        font-size: 1.1rem;
      }

      .toolbar-top {
        align-items: flex-start;
        padding: 0.85rem 1rem !important;
      }

      .toolbar-top > div {
        flex-wrap: wrap;
        row-gap: 0.45rem;
      }

      .modern-table {
        min-width: 0;
        width: 100%;
        display: block;
      }

      .tagihan-grid-card > div[style*="overflow-x"] {
        overflow: visible !important;
      }

      .modern-table thead {
        display: none;
      }

      .modern-table tbody {
        display: grid;
        gap: 0.75rem;
        padding: 0.75rem;
        background: linear-gradient(180deg, #f8fafc 0%, #eef3f8 100%);
      }

      .modern-table tr {
        display: grid;
        grid-template-columns: 30px 46px minmax(0, 1fr);
        gap: 0.75rem;
        padding: 0.85rem;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 72%, #f1f5f9 100%);
        border: 1px solid #dfe7f0;
        border-radius: 16px;
        box-shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
        max-width: 100%;
        box-sizing: border-box;
      }

      .modern-table th,
      .modern-table td {
        padding: 0;
        border-bottom: 0;
        background: transparent !important;
      }

      .modern-table td:first-child {
        grid-column: 1;
        grid-row: 1;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding-top: 0.15rem;
      }

      .modern-table td:nth-child(2) {
        grid-column: 2 / 4;
      }

      .modern-table td:nth-child(n + 3) {
        grid-column: 1 / 4;
        display: grid;
        grid-template-columns: 104px minmax(0, 1fr);
        align-items: center;
        gap: 0.65rem;
        min-width: 0;
      }

      .modern-table td:nth-child(3)::before {
        content: 'Jatuh Tempo';
      }

      .modern-table td:nth-child(4)::before {
        content: 'Status Bayar';
      }

      .modern-table td:nth-child(5)::before {
        content: 'Tagihan';
      }

      .modern-table td:nth-child(6)::before {
        content: 'Status';
      }

      .modern-table td:nth-child(7)::before {
        content: 'Aksi';
      }

      .modern-table td:nth-child(n + 3)::before {
        color: #64748b;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.03em;
      }

      .product-avatar {
        width: 46px;
        height: 46px;
        font-size: 1rem;
      }

      .product-info h6 {
        font-size: 0.86rem;
        line-height: 1.25;
        word-break: break-word;
      }

      .product-cell {
        align-items: flex-start;
        gap: 0.75rem;
      }

      .product-info span {
        display: block;
        line-height: 1.35;
        word-break: break-word;
      }

      .date-cell,
      .price-cell {
        font-size: 0.88rem;
        min-width: 0;
      }

      .stock-bar-container {
        width: 100%;
        max-width: 110px;
        margin-bottom: 0.35rem;
      }

      .status-pill {
        width: fit-content;
      }

      .modern-table td:nth-child(7) {
        align-items: center;
      }

      .modern-table td:nth-child(7) .dropdown {
        justify-self: start;
      }

      .d-flex.justify-content-between.align-items-center.px-4.py-3 {
        flex-direction: column;
        align-items: stretch !important;
        gap: 0.85rem;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
      }

      .d-flex.justify-content-between.align-items-center.px-4.py-3 > .d-flex {
        flex-wrap: wrap;
        gap: 0.65rem !important;
      }

      .pagination-wrapper {
        overflow-x: auto;
        padding-bottom: 0.2rem;
      }

      .pagination-wrapper .pagination {
        justify-content: flex-start;
      }

      .pagination-wrapper .page-link {
        min-width: 34px;
        height: 34px;
        font-size: 0.82rem;
      }
    }

    @media (max-width: 430px) {
      .container-fluid {
        padding-left: 0.6rem !important;
        padding-right: 0.6rem !important;
      }

      .tagihan-page-actions .btn {
        min-height: 44px;
        font-size: 0.86rem !important;
      }

      .tagihan-grid-toolbar {
        padding: 0.75rem;
      }

      .tagihan-input,
      .tagihan-select {
        height: 44px;
        min-height: 44px;
      }

      .tagihan-toolbar-right {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }

      .tagihan-tool-btn {
        font-size: 0.78rem;
        gap: 0.3rem;
      }

      .toolbar-top {
        padding: 0.75rem !important;
      }

      .toolbar-top .vr {
        display: none;
      }

      .modern-table tbody {
        padding: 0.65rem;
      }

      .modern-table tr {
        grid-template-columns: 28px 42px minmax(0, 1fr);
        padding: 0.75rem;
        gap: 0.65rem;
      }

      .modern-table td:nth-child(n + 3) {
        grid-template-columns: 1fr;
        gap: 0.25rem;
        padding-top: 0.35rem;
      }

      .modern-table td:nth-child(n + 3)::before {
        font-size: 0.68rem;
      }

      .product-avatar {
        width: 42px;
        height: 42px;
      }

      .product-info h6 {
        font-size: 0.82rem;
      }

      .product-info span {
        font-size: 0.78rem;
      }

      .tagihan-filter-panel.is-open {
        left: 0;
        right: 0;
        bottom: max(0.6rem, env(safe-area-inset-bottom));
        width: auto;
        max-width: calc(100vw - 1.2rem);
      }
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
  </style>

  <!-- Header Section -->
  <div class="tagihan-page-shell">
    <div class="tagihan-page-header">
      <div>
        <div class="tagihan-title-row">
          <span class="tagihan-title-icon material-symbols-rounded">receipt_long</span>
          <h4 class="fw-bold text-dark m-0">Daftar Tagihan</h4>
          <span class="tagihan-count-badge">
            <span class="material-symbols-rounded" style="font-size:1rem;">database</span>
            {{ $tagihans->total() }} Tagihan
          </span>
        </div>
        <p class="text-muted m-0" style="font-size: 0.92rem;">Kelola seluruh tagihan pelanggan secara efisien dan akurat.</p>
      </div>
      <div class="tagihan-page-actions">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTagihan">
          <span class="material-symbols-rounded" style="font-size:1.1rem;">add</span> Tambah Tagihan
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMassTagihan">
          <span class="material-symbols-rounded" style="font-size:1.1rem;">groups</span> Tagihan Massal
        </button>
      </div>
    </div>

  <form method="GET" action="{{ route('tagihan.get') }}" id="filterForm">
    <input type="hidden" name="periode" id="periodeInput" value="{{ request('periode') }}">

    <div class="tagihan-grid-card mb-4">
      <div class="tagihan-grid-toolbar">
        <div class="tagihan-toolbar-left">
          <div class="tagihan-control search" id="tagihanSearchControl">
            <span class="control-icon material-symbols-rounded">search</span>
            <input type="text" name="search" class="tagihan-input" id="topSearchInput" placeholder="Search..."
              value="{{ request('search') }}">
          </div>
        </div>
        <div class="tagihan-toolbar-right">
          <button type="button" class="tagihan-tool-btn" title="Columns">
            <span class="material-symbols-rounded">view_column</span> Columns
          </button>
          <button type="button" class="tagihan-tool-btn {{ request('search') || request('periode') || request('harga_paket') ? 'is-active' : '' }}" id="toggleTagihanFilters" aria-expanded="false" aria-controls="tagihanFilterPanel">
            <span class="material-symbols-rounded">filter_alt</span> Filters
          </button>
          <button type="button" id="btnExportBelumLunas" class="tagihan-tool-btn" title="Export belum lunas">
            <span class="material-symbols-rounded">download</span> Export
          </button>
        </div>
      </div>

      <div class="tagihan-filter-panel" id="tagihanFilterPanel">
        <button type="button" class="tagihan-filter-close" id="closeTagihanFilters" aria-label="Tutup filter">
          <span class="material-symbols-rounded">close</span>
        </button>
        <div class="tagihan-filter-fields">
          <div class="tagihan-filter-field">
            <label>Periode Bulan Tahun</label>
            <input type="text" class="tagihan-input" id="periodeTrigger" readonly placeholder="Pilih periode"
              value="{{ request('periode') ?: '' }}">
          </div>
          <div class="tagihan-filter-field">
            <label>Biaya Paket</label>
            <select name="harga_paket" class="tagihan-select" id="hargaPaketFilter">
              <option value="">Semua Biaya</option>
              @foreach($paket->pluck('harga')->filter()->unique()->sort()->values() as $hargaPaket)
                <option value="{{ $hargaPaket }}" {{ (string) request('harga_paket') === (string) $hargaPaket ? 'selected' : '' }}>
                  Rp {{ number_format((int) $hargaPaket, 0, ',', '.') }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="tagihan-filter-actions">
          <button type="submit" class="btn btn-primary">
            <span class="material-symbols-rounded" style="font-size:1.05rem;">search</span> Apply
          </button>
          @if(request('periode') || request('search') || request('filter_status') || request('harga_paket'))
            <a href="{{ route('tagihan.get') }}" class="btn btn-outline-secondary">
              <span class="material-symbols-rounded" style="font-size:1.05rem;">restart_alt</span>
            </a>
          @endif
        </div>
      </div>

      <div class="toolbar-top" style="padding: 0.85rem 1.25rem; border-bottom: 1px solid #eef2f7; background:#f8fafc;">
        <div class="d-flex align-items-center gap-3">
          <span class="fw-bold text-dark" style="font-size: 0.86rem; text-transform: uppercase;">Status Bayar</span>
          <div class="vr" style="height: 22px; color: #dbe3ec;"></div>
          <span class="text-muted" style="font-size: 0.78rem; font-weight: 800; text-transform: uppercase;">
            {{ $tagihans->total() }} Records Found
          </span>
        </div>
      </div>

      <!-- Table -->
      <div style="overflow-x: auto;">
        <table class="modern-table">
          <thead>
            <tr>
              <th style="width: 50px; text-align: center;"><input type="checkbox" class="custom-check" id="selectAllPelanggan"></th>
              <th>Customer / Paket</th>
              <th>Jatuh Tempo</th>
              <th>Status Bayar</th>
              <th>Tagihan</th>
              <th>Status</th>
              <th style="width: 50px; text-align: center;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tagihans as $item)
            @php
              $status = strtolower($item['status_pembayaran'] ?? '');
              $isLunas = $status == 'lunas';
              $isProsesVerifikasi = $status == 'proses_verifikasi';
              
              // Colors for Avatar based on string length to simulate variety
              $colors = ['#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899'];
              $colorIdx = strlen($item['nama_lengkap']) % count($colors);
              $bgColor = $colors[$colorIdx];

              $alamatParts = [];
              if($item['alamat_jalan']) $alamatParts[] = $item['alamat_jalan'];
              if($item['rt'] || $item['rw']) $alamatParts[] = 'RT '.($item['rt'] ?? '-').' / RW '.($item['rw'] ?? '-');
              if($item['desa']) $alamatParts[] = 'Desa '.$item['desa'];
              if($item['kecamatan']) $alamatParts[] = 'Kecamatan '.$item['kecamatan'];
              if($item['kabupaten']) $alamatParts[] = 'Kabupaten '.$item['kabupaten'];
              if($item['provinsi']) $alamatParts[] = $item['provinsi'];
              $alamatLengkap = implode(', ', $alamatParts);

              $buktiRaw = trim((string)($item['bukti_pembayaran'] ?? ''));
              if ($buktiRaw !== '' && $buktiRaw !== '-') {
                $buktiFile = basename(parse_url($buktiRaw, PHP_URL_PATH) ?: $buktiRaw);
                $buktiUrl = ($buktiFile && $buktiFile !== '-') 
                  ? asset('storage/bukti_pembayaran/' . $buktiFile)
                  : '';
              } else {
                $buktiUrl = '';
              }
              $statusLabel = $status ? ucwords(str_replace('_', ' ', $status)) : '-';
              $statusPillClass = $isLunas ? 'status-published' : ($isProsesVerifikasi ? 'status-scheduled' : 'status-draft');
              $stockWidth = $isLunas ? '100%' : ($isProsesVerifikasi ? '65%' : '20%');
              $stockColor = $isLunas ? '#10b981' : ($isProsesVerifikasi ? '#3b82f6' : '#f59e0b');
              $stockText = $isLunas ? 'Selesai' : ($isProsesVerifikasi ? 'Verifikasi' : 'Pending');
              $hargaFormatted = 'Rp ' . number_format($item['paket']['harga'] ?? 0, 0, ',', '.');
              $tanggalMulaiFormatted = $item['tanggal_mulai'] ? \Carbon\Carbon::parse($item['tanggal_mulai'])->format('d M Y') : '-';
              $jatuhTempoFormatted = $item['tanggal_berakhir'] ? \Carbon\Carbon::parse($item['tanggal_berakhir'])->format('d M Y') : '-';
            @endphp
            <tr class="pelanggan-item"
              data-tagihan-id="{{ $item['id'] }}"
              data-nomor-id="{{ $item['nomer_id'] ?? '-' }}"
              data-nama="{{ $item['nama_lengkap'] ?? '-' }}"
              data-whatsapp="{{ $item['no_whatsapp'] ?? '-' }}"
              data-status="{{ $statusLabel }}"
              data-paket="{{ $item['paket']['nama_paket'] ?? '-' }}"
              data-harga="{{ $hargaFormatted }}"
              data-alamat="{{ $alamatLengkap ?: '-' }}"
              data-kecamatan="{{ $item['kecamatan'] ?? '-' }}"
              data-kabupaten="{{ $item['kabupaten'] ?? '-' }}"
              data-provinsi="{{ $item['provinsi'] ?? '-' }}"
              data-kecepatan="{{ $item['paket']['kecepatan'] ?? '-' }} Mbps"
              data-tanggal-mulai="{{ $tanggalMulaiFormatted }}"
              data-jatuh-tempo="{{ $jatuhTempoFormatted }}"
              data-catatan="{{ $item['catatan'] ?? '-' }}"
              data-bukti="{{ $buktiUrl }}">
              <td><input type="checkbox" class="custom-check pelanggan-checkbox" value="{{ $item['id'] }}"></td>
              <td>
                <div class="product-cell">
                  <div class="product-info">
                    <h6>{{ $item['nama_lengkap'] }}</h6>
                    <span>ID: {{ $item['nomer_id'] ?? '-' }} | {{ $item['paket']['nama_paket'] ?? '-' }}</span>
                  </div>
                </div>
              </td>
              <td>
                <div class="date-cell">
                  {{ $item['tanggal_berakhir'] ? \Carbon\Carbon::parse($item['tanggal_berakhir'])->translatedFormat('d M Y') : '-' }}
                  <span class="time">{{ $item['tanggal_berakhir'] ? \Carbon\Carbon::parse($item['tanggal_berakhir'])->translatedFormat('h:i a') : '' }}</span>
                </div>
              </td>
              <td>
                <div class="stock-bar-container">
                  <div class="stock-bar" style="width: {{ $stockWidth }}; background: {{ $stockColor }};"></div>
                </div>
                <span class="stock-text">{{ $stockText }}</span>
              </td>
              <td class="price-cell">
                {{ $hargaFormatted }}
              </td>
              <td>
                <span class="status-pill {{ $statusPillClass }}">
                  {{ $statusLabel }}
                </span>
              </td>
              <td>
                <div class="dropdown">
                  <button class="action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="material-symbols-rounded">more_vert</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end tagihan-action-menu">
                    <li>
                      <a class="dropdown-item d-flex align-items-center btn-detail" href="javascript:void(0);"
                        data-nomor-id="{{ $item['nomer_id'] ?? '-' }}"
                        data-nama="{{ $item['nama_lengkap'] ?? '-' }}"
                        data-whatsapp="{{ $item['no_whatsapp'] ?? '-' }}"
                        data-status="{{ $statusLabel }}"
                        data-paket="{{ $item['paket']['nama_paket'] ?? '-' }}"
                        data-harga="{{ $hargaFormatted }}"
                        data-alamat="{{ $alamatLengkap ?: '-' }}"
                        data-kecamatan="{{ $item['kecamatan'] ?? '-' }}"
                        data-kabupaten="{{ $item['kabupaten'] ?? '-' }}"
                        data-provinsi="{{ $item['provinsi'] ?? '-' }}"
                        data-kecepatan="{{ $item['paket']['kecepatan'] ?? '-' }} Mbps"
                        data-tanggal-mulai="{{ $tanggalMulaiFormatted }}"
                        data-jatuh-tempo="{{ $jatuhTempoFormatted }}"
                        data-catatan="{{ $item['catatan'] ?? '-' }}"
                        data-bukti="{{ $buktiUrl }}">
                        <span class="material-symbols-rounded" style="font-size:1.25rem;">visibility</span> Detail
                      </a>
                    </li>
                    @if(!$isLunas)
                    <li>
                      <a class="dropdown-item d-flex align-items-center btn-konfirmasi" href="javascript:void(0);" data-id="{{ $item['id'] }}" data-nama="{{ $item['nama_lengkap'] }}">
                        <span class="material-symbols-rounded text-success" style="font-size:1.25rem;">check_circle</span> Verifikasi Lunas
                      </a>
                    </li>
                    @endif
                    <li>
                      <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#modalEditTagihan-{{ $item['id'] }}">
                        <span class="material-symbols-rounded" style="font-size:1.25rem;">edit</span> Edit
                      </a>
                    </li>
                    <li>
                      <form action="{{ route('tagihan.destroy', $item['id']) }}" method="POST" class="delete-form m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="dropdown-item danger-action btn-delete-tagihan d-flex align-items-center w-100 border-0 bg-transparent text-start">
                          <span class="material-symbols-rounded" style="font-size:1.25rem;">delete</span> Delete
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
                <span class="material-symbols-rounded" style="font-size: 3rem; color: #cbd5e1;">inbox</span>
                <h6 class="mt-3 text-muted">Tidak ada data tagihan.</h6>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      <!-- Pagination Footer -->
      <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top: 1px solid #f1f5f9; background: #ffffff;">
        <div class="d-flex align-items-center gap-4">
          <span class="text-muted" style="font-size: 0.8125rem;">
            Showing {{ $tagihans->firstItem() ?? 0 }}-{{ $tagihans->lastItem() ?? 0 }} of {{ $tagihans->total() }}
          </span>
        </div>
        <div class="pagination-wrapper">
          {{ $tagihans->appends(request()->query())->links('vendor.pagination.tagihan-compact') }}
        </div>
      </div>
    </div>
    </div>
  </form>
</div>



{{-- MODAL DETAIL --}}
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content border-0">
      <button type="button" class="btn-close position-absolute top-0 end-0 m-4 z-3" style="background-color: white; padding: 1rem; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); filter: none;" data-bs-dismiss="modal"></button>
      <div class="modal-body p-0">
        <!-- Content will be inserted via JavaScript -->
      </div>
    </div>
  </div>
</div>


<!-- ========================================= -->
<!-- MODAL: TAMBAH TAGIHAN -->
<!-- ========================================= -->
<div class="modal fade" id="modalTambahTagihan" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
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
                <!-- Options diload via AJAX Select2 -->
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
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('tagihan.update', $tagihan['id']) }}" method="POST" enctype="multipart/form-data" class="edit-tagihan-form" data-tagihan-id="{{ $tagihan['id'] }}">
        @csrf
        @method('PUT')

        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title fw-bold">
            <i class="ri-edit-2-line me-2"></i>Edit Tagihan
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Nama Pelanggan</label>
              <input type="text" class="form-control bg-light" value="{{ $tagihan['nama_lengkap'] ?? '-' }}" readonly>
            </div>

            <input type="hidden" name="pelanggan_id" value="{{ $tagihan['pelanggan_id'] ?? '' }}">

            <div class="col-12 mt-3">
              <label class="form-label fw-semibold">Pilih Paket</label>
              <select name="paket_id" class="form-select select2-edit-paket" required>
                @foreach($paket as $pkt)
                  <option value="{{ $pkt->id }}" {{ ($tagihan['paket']['id'] ?? '') == $pkt->id ? 'selected' : '' }}>
                    {{ $pkt->nama_paket }} - Rp {{ number_format($pkt->harga, 0, ',', '.') }}
                  </option>
                @endforeach
              </select>
            </div>

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

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
<!-- MODAL: BROADCAST TAGIHAN (AJAX-BASED) -->
<!-- ========================================= -->
<!-- ========================================= -->
<!-- MODAL: BROADCAST TAGIHAN (BATCH + MANUAL) -->
<!-- ========================================= -->
<div class="modal fade" id="modalMassTagihan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable m-0">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 0; overflow: hidden;">
      
      <!-- Modern Header -->
      <div class="modal-header border-0 py-4 px-5" style="background: linear-gradient(135deg, #18181b 0%, #27272a 100%);">
        <div>
            <h4 class="modal-title fw-bold text-white mb-1">
            <i class="ri-rocket-line me-2 text-warning"></i>Generator Tagihan Massal
            </h4>
            <p class="text-white-50 mb-0 small">Buat tagihan untuk banyak pelanggan sekaligus dengan cepat dan aman.</p>
        </div>
        <button type="button" class="btn btn-icon btn-sm btn-dark rounded-circle text-white-50 hover-white" data-bs-dismiss="modal">
            <i class="ri-close-line fs-5"></i>
        </button>
      </div>

      <div class="modal-body p-0">
        <div class="d-flex flex-column flex-lg-row h-100" style="min-height: calc(100vh - 120px);">
            
            <!-- Sidebar / Mode Selection -->
            <div class="col-lg-3 bg-light border-end p-4">
                <label class="small fw-bold text-uppercase text-muted mb-3 tracking-wide">Pilih Metode</label>
                
                <div class="d-grid gap-3">
                    <label class="mode-card p-3 rounded-3 border bg-white cursor-pointer position-relative active-mode" id="labelModeAll">
                        <input type="radio" name="broadcastMode" value="all" class="d-none" checked>
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-success-subtle text-success rounded-circle p-2 me-3">
                                <i class="ri-broadcast-line fs-5"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Broadcast Semua</h6>
                        </div>
                        <p class="small text-muted mb-0 lh-sm">
                            Generate tagihan otomatis untuk setiap pelanggan yang eligible.
                        </p>
                        <div class="active-indicator"></div>
                    </label>

                    <label class="mode-card p-3 rounded-3 border bg-white cursor-pointer position-relative" id="labelModeManual">
                        <input type="radio" name="broadcastMode" value="manual" class="d-none">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-box bg-primary-subtle text-primary rounded-circle p-2 me-3">
                                <i class="ri-checkbox-multiple-line fs-5"></i>
                            </div>
                            <h6 class="fw-bold mb-0 text-dark">Pilih Manual</h6>
                        </div>
                        <p class="small text-muted mb-0 lh-sm">
                            Cari dan pilih pelanggan spesifik secara manual.
                        </p>
                        <div class="active-indicator"></div>
                    </label>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-9 p-5 position-relative">
                
                <!-- SECTION: ALL -->
                <div id="sectionAll" class="mode-section animate__animated animate__fadeIn">
                    <div class="text-center py-5">
                       <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle" style="width: 80px; height: 80px;">
                                <i class="ri-user-star-line fs-1"></i>
                            </div>
                       </div>
                       
                       <h3 class="fw-bold text-dark mb-2">Siap untuk Broadcast?</h3>
                       <p class="text-muted w-75 mx-auto mb-4">
                            Sistem akan memindai <span class="fw-bold text-dark">semua pelanggan</span> yang belum memiliki tagihan di bulan ini.
                       </p>

                       <div class="card bg-dark bg-gradient text-white border-0 d-inline-block px-5 py-3 rounded-4 shadow-lg mb-3">
                            <h1 class="display-4 fw-bold mb-0" id="broadcastCount">
                                <span class="spinner-border spinner-border-sm"></span>
                            </h1>
                            <span class="text-white small text-uppercase spacing-2">Pelanggan Approve</span>
                       </div>

                       <div class="d-flex flex-wrap justify-content-center gap-2">
                           <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                               Siap dikirim: <strong id="broadcastEligibleCount">-</strong>
                           </span>
                           <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2">
                               Pending status: <strong id="broadcastPendingCount">-</strong>
                           </span>
                       </div>
                    </div>
                </div>

                <!-- SECTION: MANUAL -->
                <div id="sectionManual" class="mode-section d-none animate__animated animate__fadeIn">
                    <div class="d-flex justify-content-between align-items-end mb-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Pilih Pelanggan</h5>
                            <p class="text-muted small mb-0">Cari pelanggan yang ingin dibuatkan tagihan.</p>
                        </div>
                        <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm" id="manualSelectedCount">0 Terpilih</span>
                    </div>

                    <div class="input-group input-group-lg border rounded-3 overflow-hidden shadow-sm mb-4">
                        <span class="input-group-text bg-white border-0 ps-4"><i class="ri-search-line text-muted"></i></span>
                        <input type="text" class="form-control border-0 py-3" id="manualSearchInput" placeholder="Ketik nama, ID, atau alamat pelanggan..." style="font-size: 0.95rem;">
                    </div>

                    <div class="border rounded-3 overflow-hidden bg-white shadow-sm" style="height: 350px; overflow-y: auto;">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light sticky-top" style="z-index: 5;">
                                <tr>
                                    <th width="60" class="text-center py-3">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" id="checkAllManual" style="cursor: pointer;">
                                        </div>
                                    </th>
                                    <th class="py-3 text-secondary small text-uppercase">Pelanggan</th>
                                    <th class="py-3 text-secondary small text-uppercase">Paket</th>
                                    <th class="py-3 text-secondary small text-uppercase">Lokasi</th>
                                </tr>
                            </thead>
                            <tbody id="manualTableBody" class="border-top-0">
                                <!-- AJAX CONTENT -->
                            </tbody>
                        </table>
                        
                        <!-- Empty States & Loading -->
                        <div id="manualLoading" class="text-center py-5 d-none">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="small text-muted mt-2">Memuat data...</p>
                        </div>
                        <div id="manualEmpty" class="text-center py-5 text-muted d-none">
                            <div class="mb-2"><i class="ri-search-2-line fs-1 opacity-25"></i></div>
                            <p class="mb-0">Tidak ditemukan data yang cocok</p>
                        </div>
                         <div class="text-center p-3 border-top bg-light">
                             <button type="button" id="btnLoadMoreManual" class="btn btn-sm btn-outline-dark rounded-pill px-4 d-none">
                                Muat Lebih Banyak
                             </button>
                        </div>
                    </div>
                </div>

                <!-- Footer Config (Always Visible) -->
                <div class="mt-4 pt-4 border-top">
                    <div class="row g-4 align-items-end">
                        <div class="col-md-6">
                             <label class="form-label small fw-bold text-muted text-uppercase mb-2">Tanggal Mulai</label>
                             <div class="input-group">
                                 <span class="input-group-text bg-light border-end-0"><i class="ri-calendar-line"></i></span>
                                 <input type="text" id="broadcastTanggalMulai" class="form-control border-start-0 ps-0 date-picker-flat" placeholder="YYYY-MM-DD" style="font-weight: 500;">
                             </div>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label small fw-bold text-muted text-uppercase mb-2">Jatuh Tempo</label>
                             <div class="input-group">
                                 <span class="input-group-text bg-light border-end-0"><i class="ri-calendar-check-line"></i></span>
                                 <input type="text" id="broadcastTanggalBerakhir" class="form-control border-start-0 ps-0 text-danger date-picker-flat" placeholder="YYYY-MM-DD" style="font-weight: 600;">
                             </div>
                        </div>
                        <div class="col-12">
                             <button type="button" class="btn btn-dark btn-lg w-100 rounded-3 shadow-lg" id="btnBroadcastSubmit">
                                <span class="d-flex align-items-center justify-content-center gap-2">
                                    <i class="ri-flashlight-fill text-warning"></i>
                                    <span>Proses Tagihan</span>
                                </span>
                             </button>
                        </div>
                    </div>
                </div>
                
                 <!-- Progress Overlay (Absolute) -->
                <div id="broadcastProgressSection" class="position-absolute top-0 start-0 w-100 h-100 bg-white d-flex flex-column justify-content-center align-items-center d-none" style="z-index: 50; border-radius: 0 0 16px 0;">
                     <div class="text-center w-50">
                          <div class="mb-4">
                               <div class="spinner-grow text-warning" role="status" style="width: 3rem; height: 3rem;"></div>
                          </div>
                          <h4 class="fw-bold mb-2">Sedang Memproses...</h4>
                          <p class="text-muted mb-4 small">Mohon jangan tutup halaman ini. Sistem sedang membuat tagihan secara bertahap.</p>
                          
                          <div class="progress rounded-pill bg-light shadow-inner" style="height: 12px;">
                            <div class="progress-bar bg-warning rounded-pill progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="broadcastProgressBar"></div>
                          </div>
                          <div class="mt-2 d-flex justify-content-between text-muted x-small fw-bold">
                              <span>PROGRESS</span>
                              <span id="progressText">0%</span>
                          </div>
                     </div>
                </div>
                
                <!-- Result & Success (Absolute) -->
                <div id="broadcastResult" class="position-absolute top-0 start-0 w-100 h-100 bg-white d-flex flex-column justify-content-center align-items-center d-none" style="z-index: 51;">
                    <!-- Injected via JS -->
                </div>

            </div> <!-- End Col-9 -->
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Custom Styles for Modal */
.cursor-pointer { cursor: pointer; }
.hover-white:hover { background: rgba(255,255,255,0.2) !important; color: white !important; }
.spacing-2 { letter-spacing: 2px; }
.shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.06); }
.x-small { font-size: 0.75rem; }

/* Mode Card Styling */
.mode-card {
    transition: all 0.2s ease;
    border: 1px solid #e5e7eb;
}
.mode-card:hover {
    border-color: #f59e0b; /* Warning color */
    background-color: #fffbeb;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
.mode-card.active-mode {
    border-color: #f59e0b;
    background-color: #fffbeb;
    box-shadow: 0 0 0 1px #f59e0b;
}
.active-indicator {
    display: none;
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background: #f59e0b;
    border-radius: 50%;
}
.mode-card.active-mode .active-indicator { display: block; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- VARIABLES ---
    const modal = document.getElementById('modalMassTagihan');
    const bsModal = modal ? new bootstrap.Modal(modal) : null;
    
    // UI Elements
    const modeInputs = document.querySelectorAll('input[name="broadcastMode"]');
    const labelAll = document.getElementById('labelModeAll');
    const labelManual = document.getElementById('labelModeManual');
    
    const sectionAll = document.getElementById('sectionAll');
    const sectionManual = document.getElementById('sectionManual');
    const countEl = document.getElementById('broadcastCount');
    const eligibleCountEl = document.getElementById('broadcastEligibleCount');
    const pendingCountEl = document.getElementById('broadcastPendingCount');
    const btnSubmit = document.getElementById('btnBroadcastSubmit');
    const progressSection = document.getElementById('broadcastProgressSection');
    const progressBar = document.getElementById('broadcastProgressBar');
    const progressText = document.getElementById('progressText');
    const resultEl = document.getElementById('broadcastResult');
    
    // Manual Selection Logic Items
    let manualPage = 1;
    let manualQuery = '';
    let manualSelectedIds = new Set(); 
    let isLoadingManual = false;
    
    // Flatpickr Instances
    let broadcastStartPicker = null;
    let broadcastEndPicker = null;

    // Init Flatpickr
    if(document.querySelector('#broadcastTanggalMulai')) {
        broadcastStartPicker = flatpickr('#broadcastTanggalMulai', {
            mode: 'single',
            dateFormat: 'Y-m-d',
            defaultDate: 'today',
            position: 'auto center',
            appendTo: document.body,
            onReady: function(selectedDates, dateStr, instance) {
                instance.calendarContainer.classList.add('broadcast-date-picker');
            }
        });
    }
    if(document.querySelector('#broadcastTanggalBerakhir')) {
        broadcastEndPicker = flatpickr('#broadcastTanggalBerakhir', {
            mode: 'single',
            dateFormat: 'Y-m-d',
            position: 'auto center',
            appendTo: document.body,
            onReady: function(selectedDates, dateStr, instance) {
                instance.calendarContainer.classList.add('broadcast-date-picker');
            }
        });
    }

    // --- INITIALIZATION ---
    if (modal) {
        modal.addEventListener('shown.bs.modal', function() {
            // Reset to defaults
            fetchBroadcastCount(); // Get All Count
            
            // Date Initialization handled by Flatpickr below
            const today = new Date();
            if(broadcastStartPicker) broadcastStartPicker.setDate(today);
            
            // Reset UI
            progressSection.classList.add('d-none');
            resultEl.classList.add('d-none');
            btnSubmit.disabled = false;
            
            // Default Mode: All
            document.querySelector('input[value="all"]').checked = true;
            updateModeUI('all');
            
            // Load Manual Data (Init) if empty
            if (manualSelectedIds.size === 0) {
                 loadManualCustomers(true);
            }
        });
    }

    // --- MODE SWITCHING ---
    modeInputs.forEach(input => {
        input.addEventListener('change', function() {
            updateModeUI(this.value);
        });
    });

    function updateModeUI(mode) {
        // Update Labels
        if (mode === 'all') {
            labelAll.classList.add('active-mode');
            labelManual.classList.remove('active-mode');
            
            sectionAll.classList.remove('d-none');
            sectionManual.classList.add('d-none');
        } else {
            labelAll.classList.remove('active-mode');
            labelManual.classList.add('active-mode');
            
            sectionAll.classList.add('d-none');
            sectionManual.classList.remove('d-none');
        }
    }

    // --- LOGIC: FETCH COUNT (ALL) ---
    function fetchBroadcastCount() {
        countEl.innerHTML = '<span class="spinner-border spinner-border-sm text-warning"></span>';
        const countUrl = new URL('{{ route("tagihan.broadcast.count") }}', window.location.origin);
        countUrl.searchParams.set('_ts', String(Date.now()));

        return fetch(countUrl.toString(), {
            cache: 'no-store',
            headers: {
                'Cache-Control': 'no-cache',
                'Pragma': 'no-cache',
                'Accept': 'application/json'
            }
        })
            .then(res => res.json())
            .then(data => {
                countEl.textContent = data.count;
                if (eligibleCountEl) eligibleCountEl.textContent = data.approve_count ?? data.eligible_count ?? data.count ?? 0;
                if (pendingCountEl) pendingCountEl.textContent = data.pending_status_count ?? 0;
                return data;
            })
            .catch(err => {
                console.error(err);
                countEl.textContent = '-';
                if (eligibleCountEl) eligibleCountEl.textContent = '-';
                if (pendingCountEl) pendingCountEl.textContent = '-';
                return null;
            });
    }

    // --- LOGIC: MANUAL SELECTION ---
    const manualSearchInput = document.getElementById('manualSearchInput');
    const btnLoadMore = document.getElementById('btnLoadMoreManual');

    let searchTimeout;
    manualSearchInput.addEventListener('keyup', function() {
        clearTimeout(searchTimeout);
        manualQuery = this.value;
        searchTimeout = setTimeout(() => loadManualCustomers(true), 500);
    });

    btnLoadMore.addEventListener('click', () => loadManualCustomers(false));

    function loadManualCustomers(reset) {
        if (isLoadingManual) return;
        isLoadingManual = true;
        
        if (reset) {
            manualPage = 1;
            document.getElementById('manualTableBody').innerHTML = '';
            document.getElementById('manualEmpty').classList.add('d-none');
        } else {
            manualPage++;
        }

        document.getElementById('manualLoading').classList.remove('d-none');
        btnLoadMore.classList.add('d-none');

        const url = new URL('{{ route("pelanggan.search") }}');
        url.searchParams.set('q', manualQuery);
        url.searchParams.set('page', manualPage);
        url.searchParams.set('filter_no_tagihan', 1);

        fetch(url)
            .then(res => res.json())
            .then(data => {
                document.getElementById('manualLoading').classList.add('d-none');
                isLoadingManual = false;
                
                const customers = data.results || [];
                
                if (reset && customers.length === 0) {
                    document.getElementById('manualEmpty').classList.remove('d-none');
                    return;
                }

                customers.forEach(cx => {
                    const isChecked = manualSelectedIds.has(String(cx.id)) ? 'checked' : '';
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="text-center">
                            <input type="checkbox" class="form-check-input manual-check" value="${cx.id}" ${isChecked} style="width: 1.2em; height: 1.2em; cursor: pointer;">
                        </td>
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 200px;">${cx.nama || cx.text}</div>
                            <div class="x-small text-muted font-monospace">${cx.nomorid || '-'}</div>
                        </td>
                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">${cx.paket || '-'}</span></td>
                        <td>
                            <div class="small text-muted text-truncate" style="max-width:180px;">
                                <i class="ri-map-pin-line me-1"></i>${cx.alamat_jalan || '-'}
                            </div>
                        </td>
                    `;
                    document.getElementById('manualTableBody').appendChild(tr);
                });

                if (data.pagination && data.pagination.more) {
                    btnLoadMore.classList.remove('d-none');
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('manualLoading').classList.add('d-none');
                isLoadingManual = false;
            });
    }

    document.getElementById('manualTableBody').addEventListener('change', function(e) {
        if (e.target.classList.contains('manual-check')) {
            const id = e.target.value;
            if (e.target.checked) manualSelectedIds.add(id);
            else manualSelectedIds.delete(id);
            updateManualCounter();
        }
    });

    document.getElementById('checkAllManual').addEventListener('change', function(e) {
        const isChecked = e.target.checked;
        const checks = document.querySelectorAll('.manual-check');
        checks.forEach(chk => {
            chk.checked = isChecked;
            if (isChecked) manualSelectedIds.add(chk.value);
            else manualSelectedIds.delete(chk.value);
        });
        updateManualCounter();
    });

    function updateManualCounter() {
        document.getElementById('manualSelectedCount').textContent = `${manualSelectedIds.size} Terpilih`;
    }

    // --- LOGIC: SUBMIT & BATCH PROCESSING ---
    btnSubmit.addEventListener('click', async function() {
        const mode = document.querySelector('input[name="broadcastMode"]:checked').value;
        const start = document.getElementById('broadcastTanggalMulai').value;
        const end = document.getElementById('broadcastTanggalBerakhir').value;

        if (!start || !end) {
            Swal.fire({
                icon: 'error',
                title: 'Tanggal Belum Lengkap',
                text: 'Harap isi Tanggal Mulai dan Jatuh Tempo.',
                confirmButtonColor: '#18181b'
            });
            return;
        }

        if (new Date(start) > new Date(end)) {
            Swal.fire({
                icon: 'error',
                title: 'Tanggal Tidak Valid',
                text: 'Tanggal Jatuh Tempo harus sama atau setelah Tanggal Mulai.',
                confirmButtonColor: '#18181b'
            });
            return;
        }

        let targetIds = [];

        if (mode === 'manual') {
            targetIds = Array.from(manualSelectedIds);
            if (targetIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Ada Pelanggan',
                    text: 'Silakan pilih minimal 1 pelanggan pada mode manual.',
                    confirmButtonColor: '#18181b'
                });
                return;
            }
        } else {
             try {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                // Refresh angka real-time sebelum proses agar sesuai data approve terbaru
                const latestCount = await fetchBroadcastCount();
                const totalApprove = Number(
                    (latestCount?.approve_count ?? countEl?.textContent ?? '0').toString().replace(/[^\d]/g, '')
                ) || 0;
                const res = await fetch('{{ route("tagihan.broadcast.ids") }}');
                const data = await res.json();
                targetIds = data.ids || [];
                
                if (targetIds.length === 0) {
                    Swal.fire('Info', 'Tidak ada pelanggan eligible untuk diproses.', 'info');
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = `<span class="d-flex align-items-center justify-content-center gap-2"><i class="ri-flashlight-fill text-warning"></i><span>Proses Tagihan</span></span>`;
                    return;
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Gagal mengambil data pelanggan.', 'error');
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = `<span class="d-flex align-items-center justify-content-center gap-2"><i class="ri-flashlight-fill text-warning"></i><span>Proses Tagihan</span></span>`;
                return;
            }
        }

        // Confirmation (Using standard Swal but visually consistent)
        const result = await Swal.fire({
            title: 'Konfirmasi Proses',
            html: `Total approve: <strong>${mode === 'all' ? (Number((countEl?.textContent || '0').replace(/[^\d]/g, '')) || 0) : targetIds.length}</strong><br>Yang akan diproses: <strong>${targetIds.length} pelanggan</strong>.<br><small class="text-muted">Sisanya biasanya sudah punya tagihan bulan ini atau belum memiliki paket.</small>`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#18181b', // Dark
            cancelButtonColor: '#e4e4e7', // Light gray
            confirmButtonText: 'Ya, Proses Sekarang',
            cancelButtonText: '<span style="color:#3f3f46">Batal</span>',
            reverseButtons: true
        });

        if (!result.isConfirmed) {
             btnSubmit.disabled = false;
             btnSubmit.innerHTML = `<span class="d-flex align-items-center justify-content-center gap-2"><i class="ri-flashlight-fill text-warning"></i><span>Proses Tagihan</span></span>`;
             return;
        }

        // START BATCHING
        progressSection.classList.remove('d-none');
        
        let processed = 0;
        let successTotal = 0;
        let failedTotal = 0;
        const total = targetIds.length;
        const batchSize = 25; 
        
        for (let i = 0; i < total; i += batchSize) {
            const chunk = targetIds.slice(i, i + batchSize);
            try {
                const res = await fetch('{{ route("tagihan.broadcast.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tanggal_mulai: start,
                        tanggal_berakhir: end,
                        pelanggan_ids: chunk
                    })
                });
                
                const json = await res.json();
                if (json.success) {
                    successTotal += (json.processed || 0);
                    failedTotal += (json.failed || 0);
                } else {
                    failedTotal += chunk.length;
                }
            } catch (err) {
                console.error(err);
                failedTotal += chunk.length;
            }

            processed = Math.min(i + batchSize, total);
            // Update UI
            const percent = Math.round((processed / total) * 100);
            progressBar.style.width = `${percent}%`;
            progressText.textContent = `${percent}% (${processed}/${total})`;
        }

        // Finish
        // Show Success in resultEl
        progressSection.classList.add('d-none');
        resultEl.classList.remove('d-none');
        
        resultEl.innerHTML = `
            <div class="text-center animate__animated animate__zoomIn">
                <div class="mb-3 text-success">
                    <i class="ri-checkbox-circle-fill" style="font-size: 5rem;"></i>
                </div>
                <h2 class="fw-bold text-dark mb-2">Selesai!</h2>
                <p class="text-muted mb-4">
                    Berhasil memproses <strong>${successTotal}</strong> tagihan.<br>
                    <span class="text-danger small">${failedTotal > 0 ? failedTotal + ' gagal.' : ''}</span>
                </p>
                <button onclick="window.location.reload()" class="btn btn-dark btn-lg rounded-pill px-5 shadow-lg">
                    <i class="ri-refresh-line me-2"></i>Refresh Halaman
                </button>
            </div>
        `; 
    });

    // Export Belum Lunas Handler
    $('#btnExportBelumLunas').on('click', function(e) {
        e.preventDefault();
        const search = $('input[name="search"]').val();
        const periode = $('input[name="periode"]').val();
        
        let url = '{{ route("tagihan.export.belumlunas") }}';
        const params = new URLSearchParams();
        
        if (search) params.append('search', search);
        if (periode) params.append('periode', periode);
        
        if (params.toString()) {
            url += '?' + params.toString();
        }
        
        window.location.href = url;
    });
});
</script>
@endsection
