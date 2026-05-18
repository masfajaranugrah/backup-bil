@extends('layouts/layoutMaster')

@section('title', 'Tagihan - Apps')

@section('vendor-style')
@vite([
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
  overflow: hidden;
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
  border-radius: 8px;
  overflow: hidden;
}

.table-modern thead th {
  background: #f8fafc;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.5px;
  color: #18181b;
  border: none;
  padding: 1rem;
}

.table-modern tbody tr {
  transition: all 0.2s;
  border-bottom: 1px solid #e4e4e7;
  cursor: pointer;
}

.table-modern tbody tr:hover {
  background-color: #f4f4f5 !important;
  transform: scale(1.001);
}

.table-modern tbody td {
  padding: 1rem;
  vertical-align: middle;
  font-size: 0.875rem;
  color: #18181b;
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
  background: #f8fafc !important;
  color: #64748b !important;
  font-weight: 800 !important;
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
<script>
document.addEventListener("DOMContentLoaded", function () {
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
    $(document).on('click', '.btn-icon-detail', function(e) {
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
            harga: readRowData('harga'),
            kecepatan: readRowData('kecepatan'),
            tanggalMulai: readRowData('tanggalMulai'),
            jatuhTempo: readRowData('jatuhTempo'),
            bukti: readRowData('bukti', ''),
            kwitansi: readRowData('kwitansi', ''),
            catatan: readRowData('catatan')
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
                confirmButton: 'btn btn-success me-2',
                cancelButton: 'btn btn-secondary'
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
                                    confirmButton: 'btn btn-primary'
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
                                    confirmButton: 'btn btn-danger'
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
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    }
                });
            }
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
            title: 'Konfirmasi Hapus',
            html: `Yakin ingin menghapus tagihan <strong>${nama}</strong>?<br><small class="text-danger">Data tagihan dan kwitansi akan dihapus permanen!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-delete-bin-line me-1"></i>Ya, Hapus!',
            cancelButtonText: '<i class="ri-close-line me-1"></i>Batal',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#71717a',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false,
            reverseButtons: true
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
            title: 'Konfirmasi Hapus',
            html: `Yakin ingin menghapus tagihan <strong>${nama}</strong>?<br><small class="text-danger">Data tagihan dan kwitansi akan dihapus permanen!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-delete-bin-line me-1"></i>Ya, Hapus!',
            cancelButtonText: '<i class="ri-close-line me-1"></i>Tidak',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#71717a',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false,
            reverseButtons: true
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

      <div class="table-responsive p-3">
        <table class="table table-modern table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>No</th>
              <th>Detail</th>
              <th>No. ID</th>
              <th>Nama</th>
              <th>WhatsApp</th>
              <th>Type Pembayaran</th>
              <th>Status</th>
              <th>Harga</th>
              <th>Kwitansi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($tagihans as $item)
            @php
              $typePembayaranRaw = strtolower(trim((string) ($item->type_pembayaran ?? '')));
              $typePembayaranLabel = match ($typePembayaranRaw) {
                'cash', 'tunai', 'card' => 'Cash/Tunai',
                'transfer', 'bank transfer' => 'Transfer Bank',
                'qris' => 'QRIS',
                default => !empty($item->type_pembayaran) ? ucwords(str_replace(['_', '-'], ' ', (string) $item->type_pembayaran)) : '-',
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
              data-harga="Rp {{ number_format($item->paket->harga ?? 0, 0, ',', '.') }}"
              data-kecepatan="{{ $item->paket->kecepatan ?? '-' }} Mbps"
              data-tanggal-mulai="{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') : '-' }}"
              data-jatuh-tempo="{{ $item->tanggal_berakhir ? \Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') : '-' }}"
              data-bukti="{{ !empty($item->bukti_pembayaran) ? asset('storage/' . $item->bukti_pembayaran) : '' }}"
              data-kwitansi="{{ !empty($item->kwitansi) ? asset('storage/'. $item->kwitansi) : '' }}"
              data-catatan="{{ $item->catatan ?? '-' }}"
              data-type-pembayaran="{{ $typePembayaranLabel }}"
            >
              <td class="text-muted fw-semibold" style="width: 60px;">{{ ($tagihans->firstItem() ?? 1) + $loop->index }}</td>
              <td>
                <button class="btn btn-sm btn-icon btn-outline-primary btn-icon-detail" title="Lihat Detail">
                  <i class="ri-eye-line"></i>
                </button>
              </td>
              <td><span class="badge bg-label-dark">{{ $item->pelanggan->nomer_id ?? '-' }}</span></td>
              <td><strong>{{ $item->pelanggan->nama_lengkap ?? '-' }}</strong></td>
              <td>{{ $item->pelanggan->no_whatsapp ?? '-' }}</td>
              <td>{{ $typePembayaranLabel }}</td>
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
              <td><strong>Rp {{ number_format($item->paket->harga ?? 0, 0, ',', '.') }}</strong></td>
              <td>
                @if(!empty($item->kwitansi))
                  <a href="{{ asset('storage/' . $item->kwitansi) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Download Kwitansi">
                    <i class="ri-file-pdf-line"></i>
                  </a>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      @if($tagihans->hasPages())
      <div class="pagination-wrapper">
        <div class="pagination-info">
          Menampilkan <strong>{{ $tagihans->firstItem() ?? 0 }}</strong> - <strong>{{ $tagihans->lastItem() ?? 0 }}</strong>
          dari <strong>{{ $tagihans->total() }}</strong> tagihan
        </div>
        <div class="pagination-modern">
          @php
            $current = $tagihans->currentPage();
            $last = $tagihans->lastPage();
            $visiblePages = [];

            if ($last <= 10) {
                $visiblePages = range(1, $last);
            } elseif ($current <= 5) {
                $visiblePages = [1,2,3,4,5,6,7,8,'ellipsis',$last-1,$last];
            } elseif ($current >= $last - 4) {
                $visiblePages = [1,2,'ellipsis',$last-7,$last-6,$last-5,$last-4,$last-3,$last-2,$last-1,$last];
            } else {
                $visiblePages = [1,2,'ellipsis',$current-1,$current,$current+1,'ellipsis',$last-1,$last];
            }

            $visiblePages = array_values(array_filter($visiblePages, function($item) use ($last) {
                return $item === 'ellipsis' || (is_int($item) && $item >= 1 && $item <= $last);
            }));
          @endphp

          <a href="{{ $tagihans->onFirstPage() ? '#' : $tagihans->appends(request()->query())->previousPageUrl() }}"
             class="page-dot-btn nav-btn {{ $tagihans->onFirstPage() ? 'disabled' : '' }}"
             aria-label="Halaman sebelumnya">
            <i class="ri-arrow-left-s-line"></i>
          </a>

          <div class="pagination-pages">
            @php $prevWasEllipsis = false; @endphp
            @foreach($visiblePages as $page)
              @if($page === 'ellipsis')
                @if(!$prevWasEllipsis)
                  <span class="page-ellipsis">...</span>
                @endif
                @php $prevWasEllipsis = true; @endphp
              @else
                <a href="{{ $tagihans->appends(request()->query())->url($page) }}"
                   class="page-dot-btn {{ $page === $current ? 'active' : '' }}"
                   aria-label="Halaman {{ $page }}">
                  {{ $page }}
                </a>
                @php $prevWasEllipsis = false; @endphp
              @endif
            @endforeach
          </div>

          <a href="{{ $tagihans->hasMorePages() ? $tagihans->appends(request()->query())->nextPageUrl() : '#' }}"
             class="page-dot-btn nav-btn {{ !$tagihans->hasMorePages() ? 'disabled' : '' }}"
             aria-label="Halaman selanjutnya">
            <i class="ri-arrow-right-s-line"></i>
          </a>
        </div>
      </div>
      @endif
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
