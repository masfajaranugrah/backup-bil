@extends('layouts/layoutMaster')

@section('title', 'Tagihan - Apps')

@section('vendor-style')
@vite([
  'resources/css/app.css',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/style.css">
<style>
/* ========================================= */
/* SHADCN UI STYLE - MODERN CLEAN 2025 */
/* ========================================= */
:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --card-hover-shadow: 0 4px 16px rgba(0,0,0,0.12);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --primary-color: #18181b;
}

.card {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--card-shadow);
  transition: var(--transition);
  overflow: visible;
  background: #ffffff;
}

.card:hover {
  box-shadow: var(--card-hover-shadow);
}

.card-header-custom {
  color: #18181b;
  border-radius: 12px 12px 0 0 !important;
  padding: 1.5rem;
  border-bottom: 2px solid #e4e4e7;
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

/* ========================================= */
/* SHADCN UI STYLE BUTTONS - ALL BLACK */
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
.btn-primary:focus {
  outline: none !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
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

/* Outline Buttons */
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

.btn-sm {
  padding: 0.375rem 0.75rem !important;
  font-size: 0.8125rem !important;
}

/* ========================================= */
/* SHADCN UI STYLE BADGES */
/* ========================================= */
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

/* Status Lunas - Black */
.badge.bg-success {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
  border-radius: 9999px !important;
}

/* Status Belum Bayar - Red */
.badge.bg-warning,
.badge.bg-danger {
  background: #dc2626 !important;
  color: #fafafa !important;
  border: none !important;
  border-radius: 9999px !important;
}

/* Neutralize accent labels - shadcn style */
.bg-label-primary,
.bg-label-success,
.bg-label-warning,
.bg-label-dark {
  background: #f4f4f5 !important;
  color: #18181b !important;
  border: 1px solid #e4e4e7 !important;
}

/* Badge Paket - Black background */
.bg-label-info {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
  border-radius: 9999px !important;
}

/* Clean Table Design */
.table-modern {
  border-collapse: separate;
  border-spacing: 0;
  margin: 0;
}

.mui-table-paper {
  margin: 1.25rem;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  background: #ffffff;
  box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 10px 28px rgba(15, 23, 42, 0.06);
  overflow: visible;
}

.mui-table-paper .table-responsive {
  border-radius: 16px;
  overflow-x: auto;
  overflow-y: visible;
}

.table-modern thead th {
  background: #ffffff;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.82rem;
  letter-spacing: 0.08em;
  color: #64748b;
  border: none;
  border-bottom: 1px dashed #e5e7eb;
  padding: 1rem 1.25rem;
  height: 56px;
  white-space: nowrap;
  vertical-align: middle;
  line-height: 1;
}

.table-modern tbody tr {
  transition: all 0.2s;
  border-bottom: 1px dashed #e5e7eb;
  cursor: pointer;
}

.table-modern tbody tr:hover {
  background-color: rgba(25, 118, 210, 0.04) !important;
  transform: none;
}

.table-modern tbody tr.row-selected {
  background: #eff6ff !important;
}

.table-modern tbody td {
  padding: 1rem 1.25rem;
  vertical-align: middle;
  font-size: 0.875rem;
  color: #111827;
  border-bottom: 1px dashed #eef2f7;
  height: 64px;
}

.table-modern thead th:first-child,
.table-modern tbody td:first-child {
  padding-left: 1.75rem;
}

.table-modern thead th:last-child,
.table-modern tbody td:last-child {
  padding-right: 1.75rem;
}

.table-modern tbody tr:last-child td {
  border-bottom: 0;
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

.table-modern.is-dense thead th {
  padding-top: .6rem !important;
  padding-bottom: .6rem !important;
}

.table-modern.is-dense tbody td {
  padding-top: .6rem !important;
  padding-bottom: .6rem !important;
}

.table-modern.is-dense thead th:first-child,
.table-modern.is-dense tbody td:first-child {
  padding-top: 1rem !important;
  padding-bottom: 1rem !important;
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

.lunas-row-checkbox {
  width: 20px;
  height: 20px;
  border-radius: 6px;
  accent-color: #111827;
  cursor: pointer;
}

.lunas-selection-toolbar {
  display: none;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1.1rem 1.5rem;
  background: #eaf2ff;
  border: 1px solid #dbe7f7;
  border-radius: 12px 12px 0 0;
  color: #111827;
}

.lunas-selection-toolbar.active {
  display: flex;
}

.lunas-selection-toolbar .selected-text {
  font-size: 1.05rem;
  font-weight: 800;
}

.lunas-selection-toolbar .delete-selected-btn {
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

.lunas-selection-toolbar .delete-selected-btn:hover {
  background: rgba(15, 23, 42, 0.08);
  color: #dc2626;
}

.lunas-toast {
  position: fixed;
  right: 24px;
  bottom: 24px;
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 10px;
  max-width: 380px;
  padding: 14px 18px;
  border-radius: 16px;
  background: #111827;
  color: #fff;
  box-shadow: 0 22px 55px rgba(15, 23, 42, 0.22);
  font-weight: 700;
  transform: translateX(120%);
  opacity: 0;
  transition: all 0.35s ease;
}

.lunas-toast.show {
  transform: translateX(0);
  opacity: 1;
}

.btn-icon-detail {
  width: 32px;
  height: 32px;
  padding: 0;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  background: transparent !important;
  color: #18181b !important;
  border: 1px solid #e4e4e7 !important;
  transition: all 0.15s;
}

.btn-icon-detail:hover {
  background: #f4f4f5 !important;
  border-color: #a1a1aa !important;
}

.lunas-action-toggle {
  width: 32px;
  height: 32px;
  border: 0;
  border-radius: 0;
  background: transparent;
  color: #111827;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.35rem;
  box-shadow: none;
  padding: 0;
}

.lunas-action-toggle:hover,
.lunas-action-toggle:focus {
  background: transparent;
  color: #000000;
  transform: none;
  box-shadow: none;
}

.lunas-action-menu {
  min-width: 240px;
  padding: 0.65rem;
  border: 1px solid rgba(191, 219, 254, 0.75);
  border-radius: 18px;
  background: linear-gradient(135deg, rgba(239, 246, 255, 0.98) 0%, rgba(255, 241, 242, 0.98) 100%);
  box-shadow: 0 22px 50px rgba(15, 23, 42, 0.16);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
}

.lunas-action-menu .dropdown-item {
  border-radius: 13px;
  padding: 0.72rem 0.82rem;
  color: #1f2937;
  font-size: 0.9rem;
  font-weight: 750;
  display: flex;
  align-items: center;
  gap: 0.72rem;
}

.lunas-action-menu .dropdown-item:hover {
  background: rgba(255, 255, 255, 0.75);
  color: #111827;
}

.lunas-action-menu .dropdown-item i {
  width: 22px;
  font-size: 1.12rem;
  color: #111827 !important;
}

.lunas-action-menu .dropdown-item.action-danger {
  color: #dc2626;
}

/* Form Controls */
.form-select,
.form-control {
  border-radius: 8px;
  border: 1px solid #e4e4e7;
  padding: 0.625rem 1rem;
  transition: var(--transition);
  font-size: 0.875rem;
}

.form-select:focus,
.form-control:focus {
  border-color: #18181b !important;
  box-shadow: none !important;
  outline: none !important;
}

.form-control[readonly] {
  background-color: #f4f4f5;
}

/* Flatpickr Calendar */
.flatpickr-calendar {
  border: 1px solid #e4e4e7;
  border-radius: 14px;
  box-shadow: 0 18px 38px rgba(15, 23, 42, 0.16);
  padding: 10px 12px 12px;
  width: 336px;
  max-width: calc(100vw - 24px);
  font-family: inherit;
  background: #ffffff;
  z-index: 1065;
}

.flatpickr-calendar.arrowTop:before,
.flatpickr-calendar.arrowTop:after {
  display: none;
}

.flatpickr-months {
  align-items: center;
  margin-bottom: 6px;
}

.flatpickr-month {
  height: 50px;
}

.flatpickr-current-month {
  padding-top: 10px;
  font-size: 1.05rem;
  color: #0f172a;
}

.flatpickr-current-month .flatpickr-monthDropdown-months,
.flatpickr-current-month input.cur-year {
  font-weight: 700;
  color: #0f172a;
}

.flatpickr-weekdays {
  margin-bottom: 2px;
}

.flatpickr-rContainer,
.flatpickr-weekdays,
.flatpickr-days {
  width: 100% !important;
  min-width: 100% !important;
  max-width: 100% !important;
}

span.flatpickr-weekday {
  color: #374151;
  font-weight: 600;
  font-size: 0.94rem;
}

.flatpickr-days,
.dayContainer {
  width: 100%;
  min-width: 100%;
  max-width: 100%;
}

.flatpickr-day {
  width: calc(100% / 7);
  min-width: calc(100% / 7);
  max-width: calc(100% / 7);
  aspect-ratio: 1 / 1;
  height: auto;
  line-height: 1;
  margin: 0;
  border-radius: 999px;
  color: #374151;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.flatpickr-day.selected,
.flatpickr-day.startRange,
.flatpickr-day.endRange {
  background: #111827;
  border-color: #111827;
  color: #ffffff;
}

.flatpickr-day.today {
  border-color: #9ca3af;
}

.flatpickr-day.prevMonthDay,
.flatpickr-day.nextMonthDay {
  color: #9ca3af;
}

@media (max-width: 576px) {
  .flatpickr-calendar {
    width: min(340px, calc(100vw - 16px));
    padding: 8px 10px 10px;
  }

  .flatpickr-day {
    aspect-ratio: 1 / 1;
  }
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
  background: #18181b !important;
}

.modal-header.bg-primary,
.modal-header.bg-warning {
  background: #18181b !important;
  border-bottom: none;
}

.modal-header.bg-primary .modal-title,
.modal-header.bg-warning .modal-title {
  color: #fafafa;
}

.modal-title {
  font-weight: 600;
  font-size: 1.125rem;
  color: #fafafa;
}

.modal-body {
  padding: 1.5rem;
  padding-top: 2rem;
  max-height: 65vh;
  overflow-y: auto;
}

.modal-footer {
  padding: 1rem 1.5rem;
  padding-top: 1rem;
  padding-bottom: 1rem;
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

/* Modal backdrop with blur effect */
.modal-backdrop.show {
  opacity: 1;
  background-color: rgba(24, 24, 27, 0.4);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Detail Section in Modal */
.detail-section {
  background: #ffffff;
  border: 1px solid #e4e4e7;
  border-radius: 8px;
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
  padding: 0.75rem 0;
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  color: #5a5f7d;
  font-weight: 600;
  font-size: 0.875rem;
  flex-shrink: 0;
  min-width: 140px;
}

.detail-value {
  color: #2c3e50;
  font-size: 0.875rem;
  text-align: right;
  word-break: break-word;
  flex: 1;
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

.btn-close-white,
.modal-header .btn-close {
  filter: brightness(0) invert(1) !important;
  opacity: 0.8 !important;
}

.btn-close-white:hover,
.modal-header .btn-close:hover {
  opacity: 1 !important;
}

/* Image Preview */
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

/* Responsive */
@media (max-width: 768px) {
  .modal-body {
    padding: 1.5rem;
  }

  .card-header-custom {
    padding: 1.25rem;
  }

  .detail-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }

  .detail-label {
    min-width: auto;
  }

  .detail-value {
    text-align: left;
  }
}

/* Scrollbar */
.modal-body::-webkit-scrollbar {
  width: 6px;
}

.modal-body::-webkit-scrollbar-track {
  background: #e5e7eb;
  border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
  background: #18181b;
  border-radius: 10px;
}

/* ========================================= */
/* PAGINATION STYLES */
/* ========================================= */
.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem 1.25rem;
  border-top: 1px solid #f0f0f0;
  background: #ffffff;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.mui-pagination {
  gap: 0.45rem;
}

.mui-pagination .page-link {
  min-width: 36px;
  height: 36px;
  padding: 0 !important;
  border: 0 !important;
  border-radius: 50% !important;
  color: rgba(0, 0, 0, 0.87) !important;
  background: transparent !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.875rem;
  font-weight: 500;
  box-shadow: none !important;
}

.mui-pagination .page-link:hover {
  background: rgba(0, 0, 0, 0.04) !important;
}

.mui-pagination .page-item.active .page-link {
  background: #18181b !important;
  color: #ffffff !important;
}

.mui-pagination .page-item.disabled .page-link {
  color: rgba(0, 0, 0, 0.26) !important;
  background: transparent !important;
}

.mui-pagination .page-nav-icon {
  font-size: 1.2rem;
}

.pagination-info {
  color: #71717a;
  font-size: 0.875rem;
  font-weight: 500;
}

.pagination-modern {
  display: flex;
  align-items: center;
  gap: 0.45rem;
}

.pagination-pages {
  display: flex;
  align-items: center;
  gap: 0.45rem;
}

.page-dot-btn {
  min-width: 44px;
  height: 44px;
  border: none;
  border-radius: 999px;
  background: #f3f4f6;
  color: #111827;
  font-weight: 600;
  font-size: 1.1rem;
  line-height: 1;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all 0.2s ease;
}

.page-dot-btn:hover:not(.disabled):not(.active) {
  background: #e5e7eb;
  color: #111827;
}

.page-dot-btn.active {
  background: #0f111a;
  color: #ffffff;
  box-shadow: 0 6px 14px rgba(15, 17, 26, 0.2);
}

.page-dot-btn.disabled {
  opacity: 0.55;
  pointer-events: none;
}

.page-dot-btn.nav-btn {
  font-size: 1.3rem;
}

.page-ellipsis {
  min-width: 44px;
  height: 44px;
  border-radius: 999px;
  background: #f3f4f6;
  color: #6b7280;
  font-weight: 700;
  font-size: 1rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

@media (max-width: 576px) {
  .pagination-wrapper {
    flex-direction: column;
    gap: 1rem;
    align-items: center;
    text-align: center;
  }

  .pagination-modern {
    transform: scale(0.82);
    transform-origin: center;
  }
}

/* Hide DataTables Default Elements */
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate,
.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
  display: none !important;
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
p:has(span.font-medium),
/* Extra aggressive selectors */
.pagination-wrapper nav > div.flex,
.pagination-wrapper nav > div.sm\:flex-1,
.pagination-wrapper nav > div.justify-between,
.pagination-wrapper nav > div:not(.flex):not(:has(.pagination)):not(:has(.page-item)),
nav.d-flex > div:first-child:not(:has(.pagination)),
nav.d-flex > div.d-none,
.pagination-wrapper > div:last-child > nav > div.d-none,
.pagination-wrapper > div:last-child > nav > div.d-sm-flex > div:first-child {
  display: none !important;
}

/* Match daftar tagihan visual system */
.container-fluid {
  font-family: 'Inter', sans-serif;
}

.card.border-0.shadow-sm {
  border: 1px solid #e9edf3 !important;
  border-radius: 14px !important;
  box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04) !important;
  overflow: hidden;
}

.card-header-custom {
  background: #fff !important;
  border-bottom: 1px solid #eef2f7 !important;
}

.card-header-custom h4 {
  font-weight: 800;
  color: #18181b;
}

.card-body > .px-4.py-3.border-bottom {
  background: #fff;
  border-bottom-color: #eef2f7 !important;
}

.card-body > .px-4.py-3.border-bottom .form-control {
  min-height: 46px;
  border-radius: 10px;
  border-color: #e2e8f0 !important;
  font-weight: 500;
}

.table-modern thead th {
  background: #ffffff !important;
  color: #64748b !important;
  font-weight: 800 !important;
  font-size: 0.82rem !important;
  vertical-align: middle !important;
}

.table-modern tbody td {
  border-bottom: 1px dashed #e5eaf0 !important;
}

.pagination-wrapper {
  background: #fff !important;
}

@media (max-width: 767.98px) {
  .container-fluid {
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
  }

  .card-header-custom .d-flex {
    align-items: stretch !important;
  }

  .card-header-custom .btn,
  .card-header-custom .badge {
    width: 100%;
    justify-content: center;
  }

  .card-body > .px-4.py-3.border-bottom {
    padding-left: 0.9rem !important;
    padding-right: 0.9rem !important;
  }

  .card-body > .px-4.py-3.border-bottom form > .d-flex {
    flex-direction: column;
    align-items: stretch !important;
  }

  .card-body > .px-4.py-3.border-bottom form > .d-flex > div {
    min-width: 100% !important;
  }
}
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
])
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/index.js"></script>
@endsection

@section('page-script')
<style>
/* ========== TAGIHAN LUNAS DELETE MODAL ========== */
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
    const denseToggleLunas = document.getElementById('densePaddingToggleLunas');
    const lunasTable = document.querySelector('.table-modern');
    if (denseToggleLunas && lunasTable) {
        const saved = localStorage.getItem('dense_lunas_tagihan') === '1';
        denseToggleLunas.checked = saved;
        lunasTable.classList.toggle('is-dense', saved);
        denseToggleLunas.addEventListener('change', function () {
            const isDense = denseToggleLunas.checked;
            lunasTable.classList.toggle('is-dense', isDense);
            localStorage.setItem('dense_lunas_tagihan', isDense ? '1' : '0');
        });
    }
    // ========================================
    // FILTER TANGGAL DARI - SAMPAI
    // ========================================
    const fpDari = flatpickr('#filterTanggalDari', {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d M Y",
        locale: "id",
        disableMobile: true,
        defaultDate: "{{ request('tanggal_dari') }}",
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                fpSampai.set('minDate', selectedDates[0]);
            }
        }
    });

    const fpSampai = flatpickr('#filterTanggalSampai', {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d M Y",
        locale: "id",
        disableMobile: true,
        defaultDate: "{{ request('tanggal_sampai') }}",
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                fpDari.set('maxDate', selectedDates[0]);
            }
        }
    });

    // Set initial min/max jika sudah ada value
    @if(request('tanggal_dari'))
    fpSampai.set('minDate', '{{ request('tanggal_dari') }}');
    @endif
    @if(request('tanggal_sampai'))
    fpDari.set('maxDate', '{{ request('tanggal_sampai') }}');
    @endif

    // ========================================
    // HELPER FUNCTIONS
    // ========================================
    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }

    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    function showLunasToast(message) {
        const toast = document.createElement('div');
        toast.className = 'lunas-toast';
        toast.innerHTML = `<i class="ri-check-line" style="color:#86efac;font-size:1.25rem;"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        toast.offsetHeight;
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 350);
        }, 3000);
    }

    function formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
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

    function updateLunasSelection() {
        const $all = $('.lunas-checkbox');
        const $checked = $('.lunas-checkbox:checked');
        const selectedCount = $checked.length;

        $('#lunasSelectedCount').text(`${selectedCount} dipilih`);
        $('#lunasSelectionToolbar').toggleClass('active', selectedCount > 0);
        $('tr[data-tagihan-id]').removeClass('row-selected');
        $checked.closest('tr[data-tagihan-id]').addClass('row-selected');

        const $selectAll = $('#selectAllLunas');
        $selectAll.prop('checked', $all.length > 0 && selectedCount === $all.length);
        $selectAll.prop('indeterminate', selectedCount > 0 && selectedCount < $all.length);
    }

    $('#selectAllLunas').on('change', function () {
        $('.lunas-checkbox').prop('checked', this.checked);
        updateLunasSelection();
    });

    $(document).on('change', '.lunas-checkbox', updateLunasSelection);

    $('#lunasBulkDeleteBtn').on('click', function () {
        const $checked = $('.lunas-checkbox:checked');
        const totalSelected = $checked.length;

        if (!totalSelected) {
            showLunasToast('Pilih tagihan lunas terlebih dahulu.');
            return;
        }

        Swal.fire({
            title: 'Hapus Tagihan Lunas?',
            html: `<p class="mb-0">Yakin ingin menghapus <strong>${totalSelected}</strong> tagihan lunas?<br><span style="color:#6b7280;font-size:0.875rem;">Data tagihan dan kwitansi akan dihapus permanen.</span></p>`,
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
                const tagihanId = $row.data('tagihan-id');

                return $.ajax({
                    url: `/dashboard/admin/tagihan/tagihan-lunas/${tagihanId}`,
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
                        updateLunasSelection();
                    });
                    return true;
                }).catch(() => false);
            }).get();

            const results = await Promise.all(requests);
            const successCount = results.filter(Boolean).length;
            hideLoading();
            updateLunasSelection();

            if (successCount > 0) {
                showLunasToast(`${successCount} data berhasil di delete.`);
            }

            if (successCount < totalSelected) {
                Swal.fire('Sebagian gagal', `${totalSelected - successCount} tagihan gagal dihapus. Coba ulangi lagi.`, 'warning');
            }
        });
    });

    // ========================================
    // CUSTOM MODAL DETAIL IMPLEMENTATION
    // ========================================

    /**
     * Build modal content HTML dari data tagihan
     */
    function buildModalContent(data) {
        return `
            <div class="row g-0 min-vh-100">
                <!-- Left Sidebar -->
                <div class="col-lg-4 col-xl-3 border-end bg-light p-4 p-xl-5 d-flex flex-column align-items-center">
                    <div class="customer-avatar mb-4" style="width: 120px; height: 120px; font-size: 3.5rem; background: linear-gradient(135deg, #111827 0%, #0b1220 100%); color: white; display: flex; justify-content: center; align-items: center; border-radius: 50%; box-shadow: 0 4px 16px rgba(0,0,0,0.15);">
                        ${(data.nama || '-').charAt(0).toUpperCase()}
                    </div>
                    <h3 class="fw-bold text-center mb-1" style="color: #1e293b;">${data.nama}</h3>
                    <p class="text-muted text-center mb-4 fs-5">${data.nomorId}</p>
                    
                    <span class="badge ${data.status === 'lunas' ? 'bg-success' : 'bg-warning'} rounded-pill px-4 py-2 mb-5 fs-6 shadow-sm">
                        ${data.status.toUpperCase()}
                    </span>

                    <div class="w-100 mt-2">
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-whatsapp-line text-success fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">WhatsApp</small>
                                <a href="https://wa.me/${data.whatsapp.replace(/\D/g, '')}" target="_blank" class="text-dark fw-bold text-decoration-none fs-5">${data.whatsapp}</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-map-pin-line text-primary fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Alamat</small>
                                <span class="text-dark fw-bold fs-6">${data.alamat}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-3 shadow-sm border">
                            <div class="bg-light p-3 rounded shadow-sm me-3"><i class="ri-building-line text-info fs-3"></i></div>
                            <div>
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.75rem;">Area</small>
                                <span class="text-dark fw-bold fs-6">${data.kecamatan}, ${data.kabupaten}</span>
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
                                                <h3 class="fw-bold mb-0 text-dark">${data.paket}</h3>
                                            </div>
                                            <div class="bg-white p-3 rounded-circle shadow-sm"><i class="ri-router-line text-primary fs-2"></i></div>
                                        </div>
                                        <div class="d-flex align-items-center gap-4 mt-5">
                                            <div>
                                                <small class="text-muted d-block mb-1 text-uppercase fw-bold">Kecepatan</small>
                                                <span class="fw-bold fs-4 text-dark">${data.kecepatan}</span>
                                            </div>
                                            <div class="border-start ps-4">
                                                <small class="text-muted d-block mb-1 text-uppercase fw-bold">Harga per Bulan</small>
                                                <span class="fw-bold text-success fs-4">${data.harga}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 rounded-4" style="background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%); border-left: 6px solid #22c55e !important;">
                                    <div class="card-body p-4 p-xl-5">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div>
                                                <p class="text-success fw-bold mb-1 text-uppercase" style="letter-spacing: 1px;">Jatuh Tempo</p>
                                                <h3 class="fw-bold mb-0 text-dark">${data.jatuhTempo}</h3>
                                            </div>
                                            <div class="bg-white p-3 rounded-circle shadow-sm"><i class="ri-calendar-event-line text-success fs-2"></i></div>
                                        </div>
                                        <div class="d-flex align-items-center gap-4 mt-5">
                                            <div>
                                                <small class="text-muted d-block mb-1 text-uppercase fw-bold">Tanggal Mulai</small>
                                                <span class="fw-bold fs-4 text-dark">${data.tanggalMulai}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="fw-bold mb-4 text-dark"><i class="ri-file-text-line me-2"></i>Catatan Tambahan</h4>
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-5">
                            <p class="mb-0 text-secondary fs-5" style="line-height: 1.6;">${data.catatan || 'Tidak ada catatan khusus untuk pelanggan ini.'}</p>
                        </div>

                        <h4 class="fw-bold mb-4 text-dark"><i class="ri-image-line me-2"></i>Bukti Pembayaran</h4>
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                            ${data.bukti && data.bukti !== '' ? `
                            <div class="d-flex justify-content-end gap-2 mb-3">
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-inline-zoom-out" title="Zoom Out">-</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-inline-zoom-in" title="Zoom In">+</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-inline-zoom-reset" title="Reset">Reset</button>
                            </div>
                            <div class="bukti-inline-container border rounded-4 overflow-hidden shadow-sm" style="height: 430px; background:#f8fafc; position:relative; cursor:grab;">
                                <img src="${data.bukti}" alt="Bukti Pembayaran" class="bukti-inline-image" style="position:absolute; top:50%; left:50%; transform: translate(calc(-50% + 0px), calc(-50% + 0px)) scale(1); transform-origin:center center; max-width:none; user-select:none; -webkit-user-drag:none;">
                            </div>
                            <div class="mt-3 text-center">
                                <a href="${data.bukti}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-4">
                                    <i class="ri-download-line me-1"></i>Unduh Bukti
                                </a>
                            </div>
                            ` : '<p class="mb-0 text-muted">Belum ada bukti pembayaran.</p>'}
                        </div>

                        <h4 class="fw-bold mb-4 text-dark"><i class="ri-file-pdf-line me-2"></i>Kwitansi</h4>
                        <div class="bg-white p-4 rounded-4 shadow-sm border mb-4 text-center">
                            ${data.kwitansi && data.kwitansi !== '' ? `
                                <a href="${data.kwitansi}" target="_blank" class="btn btn-outline-danger btn-lg px-4 rounded-pill shadow-sm">
                                    <i class="ri-download-line me-2"></i>Unduh PDF Kwitansi
                                </a>
                            ` : '<p class="mb-0 text-muted">Belum ada kwitansi.</p>'}
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-4 pt-3 border-top">
                            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i>Tutup
                            </button>
                            <button type="button" class="btn btn-primary px-4 btn-edit-lunas" data-tagihan-id="${data.id}">
                                <i class="ri-edit-2-line me-1"></i>Edit Tagihan Lunas
                            </button>
                            <button type="button" class="btn btn-outline-warning px-4 btn-reject-lunas" data-tagihan-id="${data.id}" data-nama="${data.nama}">
                                <i class="ri-close-circle-line me-1"></i>Tolak / Batalkan
                            </button>
                            <button type="button" class="btn btn-outline-danger px-4 btn-delete-modal" data-tagihan-id="${data.id}" data-nama="${data.nama}">
                                <i class="ri-delete-bin-line me-1"></i>Hapus Tagihan Lunas
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Event handler untuk button detail di tabel
     */
    $(document).on('click', '.btn-lunas-detail', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const tr = $(this).closest('tr');
        const row = tr.get(0);
        const readRowData = (key, fallback = '-') => {
            if (!row) return fallback;
            const value = row.dataset[key] ?? tr.attr('data-' + key.replace(/[A-Z]/g, letter => '-' + letter.toLowerCase()));
            return (value === undefined || value === null || value === '') ? fallback : value;
        };

        const tagihanData = {
            id: readRowData('tagihanId'),
            status: readRowData('status'),
            nomorId: readRowData('nomorId'),
            nama: readRowData('nama'),
            whatsapp: readRowData('whatsapp'),
            alamat: readRowData('alamat'),
            kecamatan: readRowData('kecamatan'),
            kabupaten: readRowData('kabupaten'),
            provinsi: readRowData('provinsi'),
            paket: readRowData('paket'),
            paketId: readRowData('paketId', ''),
            harga: readRowData('harga'),
            kecepatan: readRowData('kecepatan'),
            tanggalMulai: readRowData('tanggalMulai'),
            jatuhTempo: readRowData('jatuhTempo'),
            bukti: readRowData('bukti', ''),
            kwitansi: readRowData('kwitansi', ''),
            catatan: readRowData('catatan'),
            typePembayaran: readRowData('typePembayaran', 'Cash/Tunai'),
            typePembayaranValue: readRowData('typePembayaranValue', 'cash')
        };

        // Build content dan footer modal
        const modalContent = buildModalContent(tagihanData);

        // Populate modal custom
        $('#detailModal .modal-body').html(modalContent);

        // Simpan data untuk digunakan handler lain
        $('#detailModal').data('tagihan-data', tagihanData);

        // Show modal menggunakan Bootstrap 5 API
        const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        detailModal.show();
    });

    $(document).on('change', '#editLunasPaket', function () {
        const selected = $(this).find('option:selected');
        $('#editLunasHargaPreview').text(formatRupiah(selected.data('harga') || 0));
        $('#editLunasKecepatanPreview').text(`${selected.data('kecepatan') || '-'} Mbps`);
    });

    $(document).on('click', '.btn-edit-lunas', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const tagihanId = $(this).data('tagihan-id');
        const $row = $(`tr[data-tagihan-id="${tagihanId}"]`);
        if (!$row.length) {
            Swal.fire('Gagal', 'Data tagihan tidak ditemukan di tabel.', 'error');
            return;
        }

        $('#editLunasForm')[0].reset();
        $('#editLunasTagihanId').val(tagihanId);
        $('#editLunasNama').text($row.data('nama') || '-');
        $('#editLunasNoId').text($row.data('nomor-id') || '-');
        $('#editLunasPaket').val($row.data('paket-id') || '').trigger('change');
        $('#editLunasTypePembayaran').val($row.data('type-pembayaran-value') || 'cash');
        $('#editLunasBuktiInfo').html($row.data('bukti')
            ? `<a href="${$row.data('bukti')}" target="_blank" class="text-decoration-none"><i class="ri-external-link-line me-1"></i>Lihat bukti saat ini</a>`
            : '<span class="text-muted">Belum ada bukti pembayaran.</span>');

        const showEditModal = () => bootstrap.Modal.getOrCreateInstance(document.getElementById('editLunasModal')).show();
        const detailModalEl = document.getElementById('detailModal');
        const detailModal = detailModalEl ? bootstrap.Modal.getInstance(detailModalEl) : null;
        if (detailModalEl?.classList.contains('show') && detailModal) {
            $('#detailModal').one('hidden.bs.modal', showEditModal);
            detailModal.hide();
            return;
        }

        showEditModal();
    });

    $('#editLunasForm').on('submit', function(e) {
        e.preventDefault();

        const tagihanId = $('#editLunasTagihanId').val();
        const formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        showLoading();

        $.ajax({
            url: `/dashboard/admin/tagihan/${tagihanId}/update-lunas`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).done((response) => {
            const data = response.data || {};
            const $row = $(`tr[data-tagihan-id="${tagihanId}"]`);

            if ($row.length) {
                $row.attr('data-paket-id', data.paket_id || '')
                    .attr('data-paket', data.paket_nama || '-')
                    .attr('data-harga', data.harga_formatted || '-')
                    .attr('data-kecepatan', data.kecepatan || '-')
                    .attr('data-bukti', data.bukti_url || '')
                    .attr('data-kwitansi', data.kwitansi_url || '')
                    .attr('data-type-pembayaran', data.type_pembayaran || 'cash')
                    .attr('data-type-pembayaran-value', $('#editLunasTypePembayaran').val() || 'cash');

                $row.find('.lunas-type-cell').text(data.type_pembayaran || 'cash');
                $row.find('.lunas-price-cell strong').text(data.harga_formatted || '-');
                $row.find('.lunas-kwitansi-link').attr('href', data.kwitansi_url || '#').removeClass('disabled');
            }

            bootstrap.Modal.getInstance(document.getElementById('editLunasModal'))?.hide();
            showLunasToast(response.message || 'Tagihan lunas berhasil diperbarui.');
        }).fail((xhr) => {
            const errors = xhr.responseJSON?.errors || {};
            const firstError = Object.values(errors)[0]?.[0];
            Swal.fire('Gagal', firstError || xhr.responseJSON?.message || 'Gagal memperbarui tagihan lunas.', 'error');
        }).always(() => {
            hideLoading();
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

    // ========================================
    // MODAL FOOTER BUTTON HANDLERS
    // ========================================

    // Hapus handler edit button (tidak ada tombol edit di lunas)

    // Handler tombol hapus jika ingin pakai SweetAlert (opsional, default pakai form submit)
    // $(document).on('click', '.btn-delete-from-detail', function(e) {
    //     e.preventDefault();
    //     const tagihanId = $(this).data('tagihan-id');
    //     const nama = $(this).data('nama');
    //     Swal.fire({
    //         title: 'Konfirmasi Penghapusan',
    //         html: `Yakin ingin menghapus tagihan <strong>${nama}</strong>?<br><small class="text-danger">Data tidak dapat dikembalikan!</small>`,
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: '<i class="ri-delete-bin-line me-1"></i>Ya, Hapus!',
    //         cancelButtonText: '<i class="ri-close-line me-1"></i>Batal',
    //         confirmButtonColor: '#ff3e1d',
    //         cancelButtonColor: '#8898aa',
    //         customClass: {
    //             confirmButton: 'btn btn-danger me-2',
    //             cancelButton: 'btn btn-secondary'
    //         },
    //         buttonsStyling: false
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             showLoading();
    //             const form = $('<form>', {
    //                 'method': 'POST',
    //                 'action': `/dashboard/admin/tagihan/tagihan-lunas/${tagihanId}`
    //             });
    //             form.append($('<input>', {
    //                 'type': 'hidden',
    //                 'name': '_token',
    //                 'value': $('meta[name="csrf-token"]').attr('content')
    //             }));
    //             form.append($('<input>', {
    //                 'type': 'hidden',
    //                 'name': '_method',
    //                 'value': 'DELETE'
    //             }));
    //             $('body').append(form);
    //             form.submit();
    //         }
    //     });
    // });

    /**
     * Konfirmasi lunas button handler
     */
    $(document).on('click', '.btn-konfirmasi-from-detail', function(e) {
        e.preventDefault();
        const tagihanId = $(this).data('tagihan-id');
        const nama = $(this).data('nama');

        Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `Apakah tagihan <strong>${nama}</strong> sudah lunas?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-check-circle-line me-1"></i>Ya, Sudah Lunas!',
            cancelButtonText: '<i class="ri-close-line me-1"></i>Batal',
            confirmButtonColor: '#71dd37',
            cancelButtonColor: '#8898aa',
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-success',
                cancelButton: 'swal-tailwind-cancel'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                $.ajax({
                    url: `/dashboard/admin/tagihan/${tagihanId}/bayar`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function(response) {
                        hideLoading();
                        if(response.success) {
                            $('#detailModal').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Berhasil!',
                                html: `
                                    <p class="mb-3">Tagihan <strong>${nama}</strong> telah ditandai lunas.</p>
                                    ${response.pdfUrl ? `
                                        <a href="${response.pdfUrl}" target="_blank" class="btn btn-primary">
                                            <i class="ri-printer-line me-1"></i>Cetak Kwitansi
                                        </a>
                                    ` : ''}
                                `,
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                                allowOutsideClick: false,
                                customClass: {
                                    container: 'swal-tailwind-backdrop',
                                    popup: 'swal-tailwind-popup',
                                    confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-primary'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    container: 'swal-tailwind-backdrop',
                                    popup: 'swal-tailwind-popup',
                                    confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger'
                                },
                                buttonsStyling: false
                            });
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error Server!',
                            text: 'Terjadi kesalahan pada server.',
                            confirmButtonText: 'OK',
                            customClass: {
                                container: 'swal-tailwind-backdrop',
                                popup: 'swal-tailwind-popup',
                                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger'
                            },
                            buttonsStyling: false
                        });
                    }
                });
            }
        });
    });

    // ========================================
    // REJECT TAGIHAN LUNAS HANDLER
    // ========================================
    $(document).on('click', '.btn-reject-lunas', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const tagihanId = $(this).data('tagihan-id');
        const nama = $(this).data('nama');

        const detailModalEl = document.getElementById('detailModal');
        const detailModal = detailModalEl ? bootstrap.Modal.getInstance(detailModalEl) : null;
        if (detailModal) {
            detailModal.hide();
        }

        Swal.fire({
            title: 'Tolak Tagihan Lunas?',
            html: `Transaksi <strong>${nama}</strong> akan dibatalkan.<br><span style="color:#6b7280;font-size:0.875rem;">Status kembali ke belum bayar, bukti/kwitansi dihapus, dan data administrasi masuk akan ditarik.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-close-circle-line"></i> &nbsp;Ya, Tolak',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel'
            }
        }).then((result) => {
            if (!result.isConfirmed) return;

            showLoading();

            $.ajax({
                url: `/dashboard/admin/tagihan/${tagihanId}/tolak-lunas`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).done((response) => {
                $(`tr[data-tagihan-id="${tagihanId}"]`).fadeOut(250, function () {
                    $(this).remove();
                    updateLunasSelection();
                });

                showLunasToast(response.message || 'Tagihan lunas berhasil ditolak.');
            }).fail((xhr) => {
                const message = xhr.responseJSON?.message || 'Gagal menolak tagihan lunas. Coba ulangi lagi.';
                Swal.fire('Gagal', message, 'error');
            }).always(() => {
                hideLoading();
            });
        });
    });

    // ========================================
    // DELETE TAGIHAN LUNAS HANDLER
    // ========================================
    $(document).on('click', '.btn-delete-tagihan', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const tagihanId = $(this).data('tagihan-id');
        const nama = $(this).data('nama');
        
        Swal.fire({
            title: 'Hapus Tagihan Lunas?',
            html: `Yakin ingin menghapus tagihan <strong>${nama}</strong>?<br><span style="color:#6b7280;font-size:0.875rem;">Data tagihan dan kwitansi akan dihapus permanen.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-delete-bin-line"></i> &nbsp;Ya, Hapus',
            cancelButtonText: 'Batal',
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                
                // Submit delete form
                const form = $('<form>', {
                    'method': 'POST',
                    'action': `/dashboard/admin/tagihan/tagihan-lunas/${tagihanId}`
                });
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': $('meta[name="csrf-token"]').attr('content')
                }));
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
    });

    // Handler untuk tombol hapus di modal (SweetAlert)
    $(document).on('click', '.btn-delete-modal', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const tagihanId = $(this).data('tagihan-id');
        const nama = $(this).data('nama');
        
        // Tutup modal detail dulu
        const detailModalEl = document.getElementById('detailModal');
        const detailModal = bootstrap.Modal.getInstance(detailModalEl);
        if (detailModal) {
            detailModal.hide();
        }
        
        Swal.fire({
            title: 'Hapus Tagihan Lunas?',
            html: `Yakin ingin menghapus tagihan <strong>${nama}</strong>?<br><span style="color:#6b7280;font-size:0.875rem;">Data tagihan dan kwitansi akan dihapus permanen.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-delete-bin-line"></i> &nbsp;Ya, Hapus',
            cancelButtonText: 'Tidak',
            buttonsStyling: false,
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();
                
                const form = $('<form>', {
                    'method': 'POST',
                    'action': `/dashboard/admin/tagihan/tagihan-lunas/${tagihanId}`
                });
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': $('meta[name="csrf-token"]').attr('content')
                }));
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_method',
                    'value': 'DELETE'
                }));
                
                $('body').append(form);
                form.submit();
            }
        });
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
  <!-- Main Table Card -->
  <div class="card border-0 shadow-sm">
 <div class="card-header-custom">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="ri-bill-line me-2"></i>Daftar Tagihan Lunas
            </h4>
            <p class="mb-0 opacity-75 small">Kelola seluruh tagihan pelanggan yang sudah lunas</p>
        </div>

        <!-- Button Actions -->
        <div class="d-flex flex-wrap align-items-center gap-2">
          <!-- Total Customer Lunas Badge -->
          @if(($tagihans->total() ?? 0) > 0)
          <span class="badge" style="padding: 10px 20px; font-size: 0.9rem; background: rgba(24, 24, 27, 0.1); color: #18181b; border: 1px solid rgba(24, 24, 27, 0.2);">
            <i class="ri-group-line me-1"></i>
            {{ number_format($tagihans->total()) }} Tagihan Lunas
          </span>
          @endif

          <!-- Buttons Export Excel -->
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-dark d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalExportBulanan">
                <i class="ri-file-excel-2-line me-1"></i> Export Laporan Bulanan
            </button>
            <button type="button" class="btn btn-dark d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modalExportTanggalBayar">
              <i class="ri-calendar-check-line me-1"></i> Export Tanggal Bayar
            </button>
            <form action="{{ route('tagihan.export.semua_lunas') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary d-flex align-items-center">
                    <i class="ri-check-double-line me-1"></i> Export Master Lunas
                </button>
            </form>
          </div>
        </div>
    </div>
</div>




    <div class="card-body p-0">
      <!-- Filter Section (Moved Here) -->
      <div class="px-4 py-3 border-bottom">
        <form method="GET" action="{{ route('tagihan.lunas') }}" id="filterForm">
          <div class="d-flex align-items-center flex-wrap gap-3">
              <!-- Search -->
              <div style="min-width: 260px;">
                  <div class="input-group" style="border: 1px solid #e4e4e7; border-radius: 8px; overflow: hidden; background: #fff;">
                    <span class="input-group-text border-0 bg-white" style="color: #94a3b8;"><i class="ri-search-line"></i></span>
                    <input
                      type="search"
                      name="search"
                      class="form-control border-0"
                      style="min-height: 40px;"
                      placeholder="Cari nama, ID, WhatsApp..."
                      value="{{ request('search') }}">
                  </div>
              </div>

              <!-- Filter Tanggal Dari -->
              <div>
                <div class="input-group" style="border: 1px solid #e4e4e7; border-radius: 8px; overflow: hidden; background: #fff; min-width: 175px;">
                  <span class="input-group-text border-0 bg-white" style="color: #94a3b8;"><i class="ri-calendar-line"></i></span>
                  <input
                    type="text"
                    id="filterTanggalDari"
                    name="tanggal_dari"
                    class="form-control border-0"
                    style="min-height: 40px;"
                    placeholder="Dari tanggal"
                    value="{{ request('tanggal_dari') }}"
                    autocomplete="off"
                    readonly>
                </div>
              </div>

              <!-- Filter Tanggal Sampai -->
              <div>
                <div class="input-group" style="border: 1px solid #e4e4e7; border-radius: 8px; overflow: hidden; background: #fff; min-width: 175px;">
                  <span class="input-group-text border-0 bg-white" style="color: #94a3b8;"><i class="ri-calendar-check-line"></i></span>
                  <input
                    type="text"
                    id="filterTanggalSampai"
                    name="tanggal_sampai"
                    class="form-control border-0"
                    style="min-height: 40px;"
                    placeholder="Sampai tanggal"
                    value="{{ request('tanggal_sampai') }}"
                    autocomplete="off"
                    readonly>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="d-flex gap-2">
                  <button class="btn btn-primary" type="submit" style="height: 40px;">
                      <i class="ri-search-line me-1"></i>Cari
                  </button>

                  @if(request('search') || request('tanggal_dari') || request('tanggal_sampai') || request('filter_bulan'))
                  <a class="btn btn-outline-secondary" href="{{ route('tagihan.lunas') }}" style="height: 40px;">
                    <i class="ri-refresh-line me-1"></i>Reset
                  </a>
                  @endif
              </div>
          </div>
        </form>
      </div>

      <div class="table-responsive lunas-table-responsive">
        <div class="lunas-selection-toolbar" id="lunasSelectionToolbar">
          <span class="selected-text" id="lunasSelectedCount">0 dipilih</span>
          <button type="button" class="delete-selected-btn" id="lunasBulkDeleteBtn" title="Hapus data dipilih">
            <i class="ri-delete-bin-line"></i>
          </button>
        </div>
        <table class="table table-modern table-hover align-middle mb-0">
          <thead>
            <tr>
              <th style="width: 64px;">
                <input type="checkbox" class="lunas-row-checkbox" id="selectAllLunas" aria-label="Pilih semua tagihan lunas">
              </th>
              <th>No. ID</th>
              <th>Nama</th>
              <th>Tanggal Mulai</th>
              <th>Type Pembayaran</th>
              <th>Status</th>
              <th>Harga</th>
              <th class="text-end" style="width: 80px;">Tindakan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tagihans as $item)
            @php
              $typePembayaranRawOriginal = trim((string) ($item->type_pembayaran ?? ''));
              $typePembayaranRaw = strtolower($typePembayaranRawOriginal);
              $bankName = trim((string) ($item->rekening->nama_bank ?? ''));
              $looksLikeUuid = (bool) preg_match('/^[a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12}$/i', $typePembayaranRawOriginal);

              $typePembayaranLabel = match (true) {
                $typePembayaranRaw === '',
                $typePembayaranRaw === '-' => 'Cash/Tunai',
                in_array($typePembayaranRaw, ['cash', 'tunai', 'card'], true) => 'Cash/Tunai',
                in_array($typePembayaranRaw, ['transfer', 'bank transfer'], true) => !empty($bankName) ? $bankName : 'Transfer Bank',
                $typePembayaranRaw === 'qris' => 'QRIS',
                $looksLikeUuid && !empty($bankName) => $bankName,
                !empty($bankName) && (str_contains($typePembayaranRaw, 'bank') || $looksLikeUuid) => $bankName,
                default => ucwords(str_replace(['_', '-'], ' ', $typePembayaranRawOriginal)),
              };
            @endphp
            <tr
              data-tagihan-id="{{ $item->id }}"
              data-status="{{ strtolower($item->status_pembayaran ?? '') }}"
              data-nomor-id="{{ $item->pelanggan->nomer_id ?? '-' }}"
              data-nama="{{ $item->pelanggan->nama_lengkap ?? '-' }}"
              data-whatsapp="{{ $item->pelanggan->no_whatsapp ?? '-' }}"
              data-alamat="{{ collect([$item->pelanggan->alamat_jalan ?? '', ($item->pelanggan->rt || $item->pelanggan->rw) ? 'RT '.($item->pelanggan->rt ?? '').' / RW '.($item->pelanggan->rw ?? '') : null, $item->pelanggan->desa ? 'Desa '.$item->pelanggan->desa : null])->filter()->implode(', ') }}"
              data-kecamatan="{{ $item->pelanggan->kecamatan ?? '-' }}"
              data-kabupaten="{{ $item->pelanggan->kabupaten ?? '-' }}"
              data-provinsi="{{ $item->pelanggan->provinsi ?? '-' }}"
              data-paket="{{ $item->paket->nama_paket ?? '-' }}"
              data-paket-id="{{ $item->paket->id ?? '' }}"
              data-harga="Rp {{ number_format($item->paket->harga ?? 0, 0, ',', '.') }}"
              data-kecepatan="{{ $item->paket->kecepatan ?? '-' }} Mbps"
              data-tanggal-mulai="{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-' }}"
              data-jatuh-tempo="{{ $item->tanggal_berakhir ? \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') : '-' }}"
              data-bukti="{{ $item->bukti_pembayaran_resolved ?? '' }}"
              data-kwitansi="{{ !empty($item->kwitansi) ? asset('storage/'. $item->kwitansi) : '' }}"
              data-catatan="{{ $item->catatan ?? '-' }}"
              data-type-pembayaran="{{ $typePembayaranLabel }}"
              data-type-pembayaran-value="{{ $typePembayaranRawOriginal !== '' ? $typePembayaranRawOriginal : 'cash' }}"
            >
              <td style="width: 64px;">
                <input type="checkbox" class="lunas-row-checkbox lunas-checkbox" value="{{ $item->id }}" aria-label="Pilih tagihan lunas {{ $item->pelanggan->nama_lengkap ?? '-' }}">
              </td>
              <td><span class="badge bg-label-dark">{{ $item->pelanggan->nomer_id ?? '-' }}</span></td>
              <td><strong>{{ $item->pelanggan->nama_lengkap ?? '-' }}</strong></td>
              <td>{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d F Y') : '-' }}</td>
              <td class="lunas-type-cell">{{ $typePembayaranLabel }}</td>
              <td>
                <div class="d-flex align-items-center gap-1">
                  @php
                    $status = strtolower($item->status_pembayaran ?? '');
                    $badgeClass = match($status) {
                      'lunas' => 'badge bg-success',
                      'belum bayar' => 'badge bg-warning',
                      default => 'badge bg-secondary',
                    };
                  @endphp
                  <span class="{{ $badgeClass }}">{{ ucfirst($status ?: '-') }}</span>
                  @if($item->is_exported)
                    <i class="ri-checkbox-circle-fill text-primary" title="Sudah Diekspor (Auto Checklist)" style="font-size: 1.2rem;"></i>
                  @endif
                </div>
              </td>
              <td class="lunas-price-cell"><strong>Rp {{ number_format($item->paket->harga ?? 0, 0, ',', '.') }}</strong></td>
              <td class="text-end">
                <div class="dropdown">
                  <button class="lunas-action-toggle" type="button" data-bs-toggle="dropdown" data-bs-display="dynamic" data-bs-boundary="viewport" data-bs-placement="top-end" aria-expanded="false" title="Menu tindakan">
                    <i class="ri-more-2-fill"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end lunas-action-menu">
                    <li>
                      <a class="dropdown-item action-primary btn-lunas-detail" href="javascript:void(0);">
                        <i class="ri-eye-line"></i> Detail Tagihan
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item action-edit btn-edit-lunas" href="javascript:void(0);" data-tagihan-id="{{ $item->id }}">
                        <i class="ri-edit-2-line"></i> Edit Tagihan
                      </a>
                    </li>
                    @if(!empty($item->kwitansi))
                    <li>
                      <a href="{{ asset('storage/' . $item->kwitansi) }}" target="_blank" class="dropdown-item action-file lunas-kwitansi-link">
                        <i class="ri-file-pdf-line"></i> Unduh Kwitansi
                      </a>
                    </li>
                    @else
                    <li>
                      <span class="dropdown-item text-muted">
                        <i class="ri-file-pdf-line"></i> Kwitansi belum ada
                      </span>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                      <a class="dropdown-item action-warning btn-reject-lunas" href="javascript:void(0);" data-tagihan-id="{{ $item->id }}" data-nama="{{ $item->pelanggan->nama_lengkap ?? '-' }}">
                        <i class="ri-close-circle-line"></i> Tolak / Batalkan
                      </a>
                    </li>
                    <li>
                      <a class="dropdown-item action-danger btn-delete-modal" href="javascript:void(0);" data-tagihan-id="{{ $item->id }}" data-nama="{{ $item->pelanggan->nama_lengkap ?? '-' }}">
                        <i class="ri-delete-bin-line"></i> Hapus Tagihan
                      </a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="pagination-wrapper">
        <label class="dense-toggle-wrap mb-0">
          <input type="checkbox" id="densePaddingToggleLunas">
          <span>Dense padding</span>
        </label>
        <div>
          @if($tagihans->hasPages())
          {{ $tagihans->appends(request()->query())->onEachSide(4)->links('pagination.mui') }}
          @else
          <nav aria-label="Page navigation">
            <ul class="pagination mui-pagination mb-0 justify-content-end">
              <li class="page-item disabled"><span class="page-link page-nav-icon">&laquo;</span></li>
              <li class="page-item disabled"><span class="page-link page-nav-icon">&lsaquo;</span></li>
              <li class="page-item active"><span class="page-link">1</span></li>
              <li class="page-item disabled"><span class="page-link page-nav-icon">&rsaquo;</span></li>
              <li class="page-item disabled"><span class="page-link page-nav-icon">&raquo;</span></li>
            </ul>
          </nav>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL EDIT TAGIHAN LUNAS -->
<div class="modal fade" id="editLunasModal" tabindex="-1" aria-labelledby="editLunasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="editLunasForm" class="modal-content border-0 shadow" enctype="multipart/form-data">
      <div class="modal-header border-0 pb-0">
        <div>
          <h5 class="modal-title fw-bold" id="editLunasModalLabel">
            <i class="ri-edit-2-line me-2 text-primary"></i>Edit Tagihan Lunas
          </h5>
          <p class="text-muted small mb-0">Update paket, metode pembayaran, bukti pembayaran, kwitansi, dan administrasi masuk.</p>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" id="editLunasTagihanId">

        <div class="rounded-4 border bg-light p-3 mb-4">
          <div class="d-flex flex-wrap justify-content-between gap-2">
            <div>
              <div class="text-muted small text-uppercase fw-bold">Pelanggan</div>
              <div class="fw-bold" id="editLunasNama">-</div>
            </div>
            <div class="text-end">
              <div class="text-muted small text-uppercase fw-bold">No. ID</div>
              <div class="fw-bold" id="editLunasNoId">-</div>
            </div>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Paket Langganan</label>
            <select name="paket_id" id="editLunasPaket" class="form-select" required>
              <option value="">Pilih paket</option>
              @foreach($paketList as $paketItem)
                <option value="{{ $paketItem->id }}" data-harga="{{ $paketItem->harga }}" data-kecepatan="{{ $paketItem->kecepatan }}">
                  {{ $paketItem->nama_paket }} - Rp {{ number_format($paketItem->harga ?? 0, 0, ',', '.') }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Metode Pembayaran</label>
            <select name="type_pembayaran" id="editLunasTypePembayaran" class="form-select" required>
              <option value="cash">Cash / Tunai</option>
              @foreach($rekeningList as $rekening)
                <option value="{{ $rekening->id }}">{{ $rekening->nama_bank }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nominal Baru</label>
            <div class="form-control bg-light fw-bold text-success" id="editLunasHargaPreview">Rp 0</div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Kecepatan</label>
            <div class="form-control bg-light fw-bold" id="editLunasKecepatanPreview">- Mbps</div>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">Bukti Pembayaran Baru</label>
            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/jpeg,image/png,image/jpg,application/pdf">
            <div class="form-text">Kosongkan jika bukti pembayaran tidak ingin diganti. Maksimal 5 MB.</div>
            <div class="small mt-2" id="editLunasBuktiInfo"></div>
          </div>
        </div>

        <div class="alert alert-info mt-4 mb-0">
          <i class="ri-information-line me-1"></i>
          Saat disimpan, nominal di <strong>Administrasi Masuk</strong> akan otomatis mengikuti paket baru.
        </div>
      </div>
      <div class="modal-footer border-0 pt-0 px-4 pb-4">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">
          <i class="ri-save-3-line me-1"></i>Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL EXPORT TANGGAL BAYAR -->
<div class="modal fade" id="modalExportTanggalBayar" tabindex="-1" aria-labelledby="modalExportTanggalBayarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom py-3">
        <h5 class="modal-title fw-bold" id="modalExportTanggalBayarLabel">
          <i class="ri-calendar-check-line me-2 text-primary"></i>Export Tanggal Bayar
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tagihan.export.tanggal_bayar') }}" method="GET">
        <div class="modal-body p-4">
          <p class="text-muted small mb-4">
            Pilih <strong>tanggal bayar/verifikasi</strong>. Export hanya menampilkan pelanggan yang lunas pada tanggal tersebut.
          </p>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold">Tanggal Bayar / Verifikasi</label>
              <input type="date" name="tanggal_bayar" class="form-control" value="{{ request('tanggal_bayar') }}" required>
              <small class="text-muted">Contoh: 2026-04-15</small>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top py-3">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="ri-download-line me-1"></i> Export Excel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL DETAIL CUSTOM - 100% MILIK ANDA -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen modal-dialog-scrollable">
    <div class="modal-content border-0">
      <button type="button" class="btn-close position-absolute top-0 end-0 m-4 z-3" style="background-color: white; padding: 1rem; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); filter: none;" data-bs-dismiss="modal"></button>
      <div class="modal-body p-0">
        <!-- Content akan di-populate oleh JavaScript -->
        <div class="text-center py-5 d-flex justify-content-center align-items-center h-100">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL BUKTI PEMBAYARAN -->
<div class="modal fade" id="buktiModal" tabindex="-1" aria-labelledby="buktiModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header py-4">
        <h5 class="modal-title text-white" id="buktiModalLabel">
          <i class="ri-image-line me-2"></i>Bukti Pembayaran
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center p-4">
        <img id="buktiImage" src="" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 70vh; object-fit: contain;">
      </div>
      <div class="modal-footer py-4">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ri-arrow-left-line me-1"></i>Kembali
        </button>
        <a id="buktiDownloadLink" href="" target="_blank" class="btn btn-primary">
          <i class="ri-download-line me-1"></i>Download
        </a>
      </div>
    </div>
    </div>
  </div>
</div>

<!-- MODAL EXPORT BULANAN -->
<div class="modal fade" id="modalExportBulanan" tabindex="-1" aria-labelledby="modalExportBulananLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom py-3">
        <h5 class="modal-title fw-bold" id="modalExportBulananLabel">
          <i class="ri-file-excel-2-line me-2 text-success"></i>Export Laporan Bulanan
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('tagihan.export.bulan_lalu') }}" method="GET">
        <div class="modal-body p-4">
          <p class="text-muted small mb-4">
            Pilih bulan dan tahun. Sistem akan mengekspor data Pembayaran & Outstanding (OS) untuk <strong>1 bulan sebelumnya</strong> sesuai ketentuan.
          </p>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Bulan</label>
              <select name="bulan" class="form-select">
                @foreach(range(1, 12) as $m)
                  <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Tahun</label>
              <select name="tahun" class="form-select">
                @foreach(range(2024, date('Y')+1) as $y)
                  <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>
                    {{ $y }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top py-3">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">
            <i class="ri-download-line me-1"></i> Download Excel
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
