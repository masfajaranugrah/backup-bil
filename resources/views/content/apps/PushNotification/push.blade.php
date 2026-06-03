@extends('layouts/layoutMaster')

@section('title', 'Push Notification')

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
/* ========================================= */
/* SHADCN UI STYLE - BLACK & WHITE */
/* ========================================= */
:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --card-hover-shadow: 0 4px 16px rgba(0,0,0,0.12);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --primary-color: #18181b;
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

/* Card Header */
.card-header-custom {
  background: linear-gradient(180deg, #ffffff 0%, #fbfbfc 100%) !important;
  color: #18181b !important;
  border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
  padding: 1.75rem;
  border-bottom: 1px solid #e4e4e7;
}

.card-header-custom h4,
.card-header-custom h5,
.card-header-custom p,
.card-header-custom i {
  color: #18181b !important;
}

.card-header-custom .opacity-75 {
  color: #71717a !important;
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
}

.btn-primary i,
.btn.btn-primary i {
  color: #ffffff !important;
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

.btn-info,
.btn.btn-info {
  background: #18181b !important;
  background-color: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
  box-shadow: none !important;
}

.btn-info:hover,
.btn.btn-info:hover {
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

.btn-broadcast {
  min-height: 52px;
  padding: 12px 24px !important;
  border-radius: 14px !important;
  font-weight: 700 !important;
  letter-spacing: .2px;
  transition: all 0.25s !important;
  box-shadow: 0 10px 20px rgba(24,24,27,0.16) !important;
}

.btn-broadcast:hover {
  transform: translateY(-1px) !important;
  box-shadow: 0 12px 24px rgba(24,24,27,0.22) !important;
}

.btn-broadcast i {
  margin-right: 8px;
  color: #ffffff !important;
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

.badge-status {
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 9999px;
  font-size: 0.75rem;
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
.bg-primary:not(.btn) {
  background: #18181b !important;
  color: #fafafa !important;
}

.badge.bg-info,
.bg-info:not(.btn) {
  background: #18181b !important;
  color: #fafafa !important;
}

.badge.bg-secondary,
.bg-secondary:not(.btn) {
  background: #71717a !important;
  color: #fafafa !important;
}

/* Badge Labels */
.bg-label-info {
  background: #18181b !important;
  color: #fafafa !important;
  border: none !important;
  border-radius: 9999px !important;
}

.bg-label-primary,
.bg-label-success,
.bg-label-warning,
.bg-label-dark,
.bg-label-secondary {
  background: #f4f4f5 !important;
  color: #18181b !important;
  border: 1px solid #e4e4e7 !important;
}

/* ========================================= */
/* TABLE STYLES */
/* ========================================= */
.table-modern {
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
  border: none;
  padding: 1rem;
  white-space: nowrap;
}

.table-modern tbody tr {
  transition: var(--transition);
  border-bottom: 1px solid #e4e4e7;
}

.table-modern tbody tr:hover {
  background-color: #f4f4f5 !important;
}

.table-modern tbody tr.row-selected {
  background: #eff6ff !important;
}

.table-modern tbody td {
  padding: 1rem;
  border-bottom: 1px solid #e4e4e7;
  vertical-align: middle;
  color: #18181b;
}

.table-modern.is-dense thead th {
  padding-top: .6rem !important;
  padding-bottom: .6rem !important;
}

.table-modern.is-dense tbody td {
  padding-top: .6rem !important;
  padding-bottom: .6rem !important;
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

.push-row-checkbox {
  width: 20px;
  height: 20px;
  border-radius: 6px;
  accent-color: #111827;
  cursor: pointer;
}

.push-selection-toolbar {
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

.push-selection-toolbar.active {
  display: flex;
}

.push-selection-toolbar .selected-text {
  font-size: 1.05rem;
  font-weight: 800;
}

.push-selection-toolbar .send-selected-btn {
  border: 0;
  border-radius: 12px;
  background: #111827;
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: .5rem;
  padding: .7rem 1rem;
  font-weight: 800;
}

.push-selection-toolbar .send-selected-btn:hover {
  background: #1f2937;
}

.push-title-wrap {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: .65rem;
  margin-bottom: .4rem;
}

.push-total-chip {
  padding: .45rem .8rem;
  border-radius: 999px;
  background: #18181b;
  color: #fff !important;
  border: 1px solid #27272a;
  font-size: .86rem;
  font-weight: 600;
}

.push-search-row {
  margin-top: 1rem;
}

.push-search-form {
  display: flex;
  gap: .75rem;
  align-items: center;
  flex-wrap: wrap;
}

.push-search-group {
  max-width: 560px;
  width: 100%;
  border: 1px solid #e4e4e7;
  border-radius: 12px;
  overflow: hidden;
  background: #fff;
}

.push-search-group .input-group-text {
  border: 0;
  background: transparent;
  color: #71717a;
  padding-left: 1rem;
}

.push-search-group .form-control {
  border: 0;
  box-shadow: none !important;
  min-height: 48px;
}

/* ========================================= */
/* FORM CONTROLS */
/* ========================================= */
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

/* ========================================= */
/* LOADING OVERLAY */
/* ========================================= */
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

.spinner-border-custom {
  width: 3rem;
  height: 3rem;
  border-width: 0.3rem;
}

/* ========================================= */
/* ICON WRAPPER */
/* ========================================= */
.icon-wrapper {
  width: 48px;
  height: 48px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  font-size: 24px;
  background: #f4f4f5;
  color: #18181b;
}

/* ========================================= */
/* ACTION BUTTONS */
/* ========================================= */
.action-buttons {
  gap: 12px;
}

/* ========================================= */
/* DATATABLES CUSTOM */
/* ========================================= */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
  border: 1px solid #e4e4e7 !important;
  border-radius: 8px !important;
  padding: 0.5rem 1rem !important;
}

.dataTables_wrapper .dataTables_length select:focus,
.dataTables_wrapper .dataTables_filter input:focus {
  border-color: #18181b !important;
  box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
  outline: none !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button,
.dataTables_wrapper .dataTables_paginate .paginate_button:link,
.dataTables_wrapper .dataTables_paginate .paginate_button:visited {
  border-radius: 50% !important;
  width: 40px !important;
  height: 40px !important;
  padding: 0 !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  border: 1px solid #e4e4e7 !important;
  color: #18181b !important;
  background: #fff !important;
  background-color: #fff !important;
  margin: 0 4px !important;
  font-weight: 600 !important;
  transition: all 0.3s ease !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover,
.dataTables_wrapper .dataTables_paginate .paginate_button:focus,
.dataTables_wrapper .dataTables_paginate .paginate_button:active {
  background: #fff !important;
  background-color: #fff !important;
  border-color: #e4e4e7 !important;
  color: #18181b !important;
  transform: none !important;
  box-shadow: none !important;
  outline: none !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:link,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:visited,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:hover,
.dataTables_wrapper .dataTables_paginate .paginate_button.current:focus {
  background: #18181b !important;
  background-color: #18181b !important;
  border-color: #18181b !important;
  color: #fafafa !important;
  box-shadow: 0 4px 12px rgba(24, 24, 27, 0.4) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:link,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:visited {
  background: #f4f4f5 !important;
  background-color: #f4f4f5 !important;
  border-color: #e4e4e7 !important;
  color: #a1a1aa !important;
  cursor: not-allowed !important;
  transform: none !important;
  box-shadow: none !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:focus,
.dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
  background: #f4f4f5 !important;
  background-color: #f4f4f5 !important;
  border-color: #e4e4e7 !important;
  color: #a1a1aa !important;
  transform: none !important;
  box-shadow: none !important;
}

/* Override any Bootstrap/DataTables default link colors */
.page-link,
.paginate_button a,
.dataTables_paginate a {
  color: #18181b !important;
}

.page-item.active .page-link,
.page-link:hover {
  background-color: #18181b !important;
  border-color: #18181b !important;
  color: #fafafa !important;
}

.dataTables_info {
  color: #71717a;
  font-size: 0.875rem;
  font-weight: 500;
}

.dataTables_length label,
.dataTables_filter label {
  color: #18181b;
  font-weight: 500;
}

/* Pagination Wrapper */
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
nav[role="navigation"] > div > p,
.pagination-wrapper nav[role="navigation"] > div.hidden,
.pagination-wrapper nav[role="navigation"] > div:not(:has(.pagination)),
.pagination-wrapper > div:last-child > nav > div:first-child,
nav[role="navigation"] .hidden,
nav[role="navigation"] > div.flex-1,
.pagination-wrapper p.text-sm,
.pagination-wrapper .leading-5,
.pagination-wrapper span.relative,
p:has(span.font-medium) {
  display: none !important;
}

.dataTables_wrapper .dataTables_paginate {
  padding: 1rem 0;
}

.dataTables_wrapper {
  padding: 0;
}

/* Fix layout - Length & Search sejajar */
.dataTables_wrapper .row:first-child {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e4e4e7;
  background: #fafafa;
  display: flex !important;
  flex-wrap: wrap !important;
  align-items: center !important;
  justify-content: space-between !important;
}

.dataTables_wrapper .row:first-child > div {
  flex: 0 0 auto !important;
  width: auto !important;
  max-width: none !important;
  padding: 0.25rem 0.5rem !important;
}

.dataTables_wrapper .dataTables_length {
  display: flex !important;
  align-items: center !important;
  gap: 0.75rem !important;
}

.dataTables_wrapper .dataTables_length label {
  display: flex !important;
  align-items: center !important;
  gap: 0.75rem !important;
  margin-bottom: 0 !important;
  white-space: nowrap !important;
}

.dataTables_wrapper .dataTables_length select {
  margin: 0 0.5rem !important;
  width: 71px !important;
  display: inline-block !important;
}

.dataTables_wrapper .dataTables_filter {
  display: flex !important;
  align-items: center !important;
  gap: 0.5rem !important;
}

.dataTables_wrapper .dataTables_filter label {
  display: flex !important;
  align-items: center !important;
  gap: 0.5rem !important;
  margin-bottom: 0 !important;
  white-space: nowrap !important;
}

.dataTables_wrapper .dataTables_filter input {
  min-width: 200px !important;
}

.dataTables_wrapper .row:last-child {
  padding: 1rem 1.5rem;
  border-top: 1px solid #e4e4e7;
  background: #fafafa;
  display: flex !important;
  flex-wrap: wrap !important;
  align-items: center !important;
  justify-content: space-between !important;
}

.dataTables_wrapper .row:last-child > div {
  flex: 0 0 auto !important;
  width: auto !important;
  max-width: none !important;
  padding: 0.25rem 0.5rem !important;
}

/* Responsive - stack on mobile */
@media (max-width: 768px) {
  .push-search-form .btn {
    width: 100%;
  }

  .dataTables_wrapper .row:first-child,
  .dataTables_wrapper .row:last-child {
    flex-direction: column !important;
    gap: 1rem !important;
    align-items: stretch !important;
  }

  .dataTables_wrapper .row:first-child > div,
  .dataTables_wrapper .row:last-child > div {
    width: 100% !important;
  }

  .dataTables_wrapper .dataTables_filter input {
    width: 100% !important;
  }
}

/* ========================================= */
/* TEXT COLORS */
/* ========================================= */
.text-primary {
  color: #18181b !important;
}

.text-success {
  color: #22c55e !important;
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

/* ========================================= */
/* ANIMATIONS */
/* ========================================= */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.card {
  animation: fadeIn 0.3s ease-out;
}

/* ========================================= */
/* BOTTOM SNACKBAR */
/* ========================================= */
.bottom-snackbar {
  position: fixed;
  bottom: 24px;
  left: 50%;
  transform: translateX(-50%) translateY(120%);
  z-index: 99999;
  min-width: 380px;
  max-width: 560px;
  padding: 16px 20px;
  border-radius: 14px;
  background: #18181b;
  color: #fafafa;
  font-size: 0.9rem;
  font-weight: 500;
  line-height: 1.5;
  display: flex;
  align-items: flex-start;
  gap: 12px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.05);
  animation: snackbarSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  overflow: hidden;
}

.bottom-snackbar.snackbar-hide {
  animation: snackbarSlideDown 0.35s cubic-bezier(0.55, 0, 1, 0.45) forwards;
}

.bottom-snackbar .snackbar-icon {
  flex-shrink: 0;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  margin-top: 1px;
}

.bottom-snackbar .snackbar-icon.icon-success {
  background: #22c55e;
  color: #fff;
}

.bottom-snackbar .snackbar-icon.icon-error {
  background: #ef4444;
  color: #fff;
}

.bottom-snackbar .snackbar-icon.icon-info {
  background: #3b82f6;
  color: #fff;
}

.bottom-snackbar .snackbar-content {
  flex: 1;
}

.bottom-snackbar .snackbar-title {
  font-weight: 700;
  font-size: 0.85rem;
  margin-bottom: 2px;
  color: #e4e4e7;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.bottom-snackbar .snackbar-message {
  color: #d4d4d8;
  font-size: 0.88rem;
}

.bottom-snackbar .snackbar-message strong {
  color: #ffffff;
}

.bottom-snackbar .snackbar-close {
  flex-shrink: 0;
  background: transparent;
  border: none;
  color: #71717a;
  cursor: pointer;
  font-size: 18px;
  padding: 4px;
  line-height: 1;
  transition: color 0.2s;
}

.bottom-snackbar .snackbar-close:hover {
  color: #fafafa;
}

.bottom-snackbar .snackbar-progress {
  position: absolute;
  bottom: 0;
  left: 0;
  height: 3px;
  background: linear-gradient(90deg, #22c55e, #4ade80);
  border-radius: 0 0 14px 14px;
  animation: snackbarProgress 5s linear forwards;
}

@keyframes snackbarSlideUp {
  from { transform: translateX(-50%) translateY(120%); opacity: 0; }
  to { transform: translateX(-50%) translateY(0); opacity: 1; }
}

@keyframes snackbarSlideDown {
  from { transform: translateX(-50%) translateY(0); opacity: 1; }
  to { transform: translateX(-50%) translateY(120%); opacity: 0; }
}

@keyframes snackbarProgress {
  from { width: 100%; }
  to { width: 0%; }
}

@media (max-width: 480px) {
  .bottom-snackbar {
    min-width: auto;
    left: 12px;
    right: 12px;
    transform: translateX(0) translateY(120%);
  }
  @keyframes snackbarSlideUp {
    from { transform: translateX(0) translateY(120%); opacity: 0; }
    to { transform: translateX(0) translateY(0); opacity: 1; }
  }
  @keyframes snackbarSlideDown {
    from { transform: translateX(0) translateY(0); opacity: 1; }
    to { transform: translateX(0) translateY(120%); opacity: 0; }
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

@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay">
    <div class="spinner-border spinner-border-custom text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-header-custom">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <div class="push-title-wrap">
                    <h4 class="mb-0 fw-bold">
                        <i class="ri-bill-line me-2"></i>Daftar Tagihan Belum Lunas
                    </h4>
                    <span class="push-total-chip">{{ $totalTagihan ?? $tagihans->total() }} Total Tagihan</span>
                </div>
                <p class="mb-0 opacity-75 small">
                    Kelola dan kirim push notifikasi tagihan pelanggan dengan cepat.
                </p>
            </div>
            <div class="d-flex action-buttons mt-3 mt-md-0 gap-2">
                <button id="send-broadcast-push" class="btn btn-success btn-broadcast" data-total="{{ $totalTagihan ?? $tagihans->total() }}">
                    <i class="ri-notification-3-fill"></i>
                    Kirim Notifikasi ke Semua ({{ $totalTagihan ?? $tagihans->total() }})
                </button>
            </div>
        </div>
        
        <!-- Search Form -->
        <div class="push-search-row">
            <form action="{{ route('push.notification.index') }}" method="GET" class="push-search-form">
                <div class="input-group push-search-group">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, ID, atau WhatsApp..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-search-line me-1"></i> Cari
                </button>
                @if(request('search'))
                <a href="{{ route('push.notification.index') }}" class="btn btn-outline-secondary">
                    <i class="ri-close-line me-1"></i> Reset
                </a>
                @endif
            </form>
        </div>
    </div>

    <div class="broadcast-progress-panel d-none" id="broadcastProgressPanel">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
            <div>
                <div class="fw-semibold">Progress kirim notifikasi</div>
                <div class="text-muted small" id="broadcastProgressText">Menyiapkan log pelanggan...</div>
            </div>
            <div class="broadcast-progress-counts">
                <span class="badge bg-label-success" id="broadcastSentCount">0 terkirim</span>
                <span class="badge bg-label-danger" id="broadcastFailedCount">0 gagal</span>
                <span class="badge bg-label-secondary" id="broadcastSkippedCount">0 dilewati</span>
            </div>
        </div>
        <div class="progress broadcast-progress-bar">
            <div class="progress-bar bg-success" id="broadcastProgressBar" style="width: 0%">0%</div>
        </div>
        <div class="broadcast-log-list" id="broadcastLogList"></div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive p-3">
            <div class="push-selection-toolbar" id="pushSelectionToolbar">
                <span class="selected-text" id="pushSelectedCount">0 dipilih</span>
                <button type="button" class="send-selected-btn" id="send-selected-push">
                    <i class="ri-notification-3-line"></i>
                    Kirim Notifikasi Terpilih
                </button>
            </div>
            <table class="table table-modern table-hover">
                <thead>
                    <tr>
                        <th style="width: 64px;">
                            <input type="checkbox" class="push-row-checkbox" id="selectAllPush" aria-label="Pilih semua tagihan notifikasi">
                        </th>
                        <th>Nama Pelanggan</th>
                        <th>Nomer ID</th>
                        <th>Status</th>
                        <th>Notifikasi</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihans as $index => $tagihan)
                    @php
                        $statusKey = strtolower(trim($tagihan['status_pembayaran'] ?? ''));
                    @endphp
                    @continue($statusKey !== 'belum bayar')
                    <tr id="row-{{ $tagihan['id'] }}" data-tagihan-id="{{ $tagihan['id'] }}">
                        <td style="width: 64px;">
                            <input type="checkbox" class="push-row-checkbox push-checkbox" value="{{ $tagihan['id'] }}" aria-label="Pilih notifikasi {{ $tagihan['nama_lengkap'] }}">
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="fw-semibold">{{ $tagihan['nama_lengkap'] }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-label-info">{{ $tagihan['nomer_id'] ?? '-' }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($statusKey) {
                                    'lunas' => 'bg-success',
                                    'belum bayar' => 'bg-warning',
                                    'proses_verifikasi' => 'bg-info',
                                    'jatuh tempo' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge badge-status {{ $statusClass }}">
                                {{ $tagihan['status_label'] ?? ucwords(str_replace('_', ' ', $tagihan['status_pembayaran'])) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-label-secondary notification-log-badge" data-tagihan-id="{{ $tagihan['id'] }}">
                                <i class="ri-time-line me-1"></i>Belum dikirim
                            </span>
                        </td>
                        <td>{{ $tagihan['tanggal_mulai'] ?? '-' }}</td>
                        <td>{{ $tagihan['tanggal_berakhir'] ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="ri-inbox-line fs-1 text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">Tidak ada tagihan yang belum lunas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Laravel Pagination -->
        @if($tagihans->hasPages())
        <div class="pagination-wrapper">
          <label class="dense-toggle-wrap mb-0">
            <input type="checkbox" id="densePaddingTogglePush">
            <span>Dense padding</span>
          </label>
          <div>
            {{ $tagihans->appends(request()->query())->onEachSide(2)->links('vendor.pagination.tagihan-compact') }}
          </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const denseTogglePush = document.getElementById('densePaddingTogglePush');
    const pushTable = document.querySelector('.table-modern');
    if (denseTogglePush && pushTable) {
        const saved = localStorage.getItem('dense_push_tagihan') === '1';
        denseTogglePush.checked = saved;
        pushTable.classList.toggle('is-dense', saved);
        denseTogglePush.addEventListener('change', function () {
            const isDense = denseTogglePush.checked;
            pushTable.classList.toggle('is-dense', isDense);
            localStorage.setItem('dense_push_tagihan', isDense ? '1' : '0');
        });
    }
    // ========================================
    // BOTTOM SNACKBAR HELPER
    // ========================================
    function showBottomSnackbar(message, type = 'success', duration = 5000) {
        // Remove existing snackbar
        const existing = document.querySelector('.bottom-snackbar');
        if (existing) existing.remove();

        const iconMap = {
            success: { cls: 'icon-success', icon: 'ri-check-line' },
            error:   { cls: 'icon-error',   icon: 'ri-close-line' },
            info:    { cls: 'icon-info',     icon: 'ri-information-line' }
        };
        const iconData = iconMap[type] || iconMap.success;

        const titleMap = {
            success: 'Berhasil',
            error: 'Gagal',
            info: 'Informasi'
        };

        const snackbar = document.createElement('div');
        snackbar.className = 'bottom-snackbar';
        snackbar.innerHTML = `
            <div class="snackbar-icon ${iconData.cls}">
                <i class="${iconData.icon}"></i>
            </div>
            <div class="snackbar-content">
                <div class="snackbar-title">${titleMap[type] || 'Notifikasi'}</div>
                <div class="snackbar-message">${message}</div>
            </div>
            <button class="snackbar-close" onclick="this.parentElement.classList.add('snackbar-hide'); setTimeout(() => this.parentElement.remove(), 350);">
                <i class="ri-close-line"></i>
            </button>
            <div class="snackbar-progress" style="animation-duration: ${duration}ms;"></div>
        `;

        document.body.appendChild(snackbar);

        setTimeout(() => {
            if (snackbar.parentElement) {
                snackbar.classList.add('snackbar-hide');
                setTimeout(() => snackbar.remove(), 350);
            }
        }, duration);
    }

    // Fungsi helper untuk menampilkan loading
    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }

    function hideLoading() {
        $('.loading-overlay').css('display', 'none');
    }

    async function parseJsonFetchResponse(response) {
        const text = await response.text();
        let data = {};

        if (text) {
            try {
                data = JSON.parse(text);
            } catch (error) {
                data = {
                    message: response.ok
                        ? text
                        : `Respons server tidak valid (HTTP ${response.status}). Silakan login ulang atau cek log Laravel.`
                };
            }
        }

        if (!response.ok) {
            throw new Error(data.message || `HTTP error! status: ${response.status}`);
        }

        return data;
    }

    function statusBadgeHtml(status) {
        const map = {
            sent: '<span class="badge bg-label-success"><i class="ri-check-line me-1"></i>Terkirim</span>',
            failed: '<span class="badge bg-label-danger"><i class="ri-close-line me-1"></i>Gagal</span>',
            skipped: '<span class="badge bg-label-secondary"><i class="ri-subtract-line me-1"></i>Dilewati</span>',
            pending: '<span class="badge bg-label-warning"><i class="ri-loader-4-line me-1"></i>Proses</span>'
        };

        return map[status] || map.pending;
    }

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function startBroadcastProgress(progressUrl, total) {
        if (!progressUrl) return;

        const panel = $('#broadcastProgressPanel');
        const progressText = $('#broadcastProgressText');
        const progressBar = $('#broadcastProgressBar');
        const logList = $('#broadcastLogList');

        panel.removeClass('d-none');
        progressText.text(`0 dari ${total || 0} pelanggan diproses`);
        progressBar.css('width', '0%').text('0%');
        logList.html('');

        const poll = () => {
            fetch(progressUrl, { headers: { 'Accept': 'application/json' } })
                .then(parseJsonFetchResponse)
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Gagal membaca progress');
                    }

                    const counts = data.counts || {};
                    const percent = counts.percent || 0;
                    const processed = counts.processed || 0;
                    const totalCount = counts.total || total || 0;

                    progressText.text(`${processed} dari ${totalCount} pelanggan diproses`);
                    progressBar.css('width', `${percent}%`).text(`${percent}%`);
                    $('#broadcastSentCount').text(`${counts.sent || 0} terkirim`);
                    $('#broadcastFailedCount').text(`${counts.failed || 0} gagal`);
                    $('#broadcastSkippedCount').text(`${counts.skipped || 0} dilewati`);

                    (data.items || []).forEach(item => {
                        $(`.notification-log-badge[data-tagihan-id="${item.tagihan_id}"]`).replaceWith(
                            `<span class="notification-log-badge" data-tagihan-id="${item.tagihan_id}">${statusBadgeHtml(item.status)}</span>`
                        );
                    });

                    logList.html((data.items || []).slice(0, 20).map(item => `
                        <div class="broadcast-log-item">
                            <span>${statusBadgeHtml(item.status)} <strong>${escapeHtml(item.nama)}</strong> <span class="text-muted">(${escapeHtml(item.nomer_id)})</span></span>
                            <span class="text-muted">${escapeHtml(item.provider)}${item.message ? ' - ' + escapeHtml(item.message) : ''}</span>
                        </div>
                    `).join(''));

                    if (!counts.finished) {
                        setTimeout(poll, 2500);
                        return;
                    }

                    const type = (counts.failed || 0) > 0 ? 'info' : 'success';
                    showBottomSnackbar(
                        `Broadcast selesai: <strong>${counts.sent || 0}</strong> terkirim, <strong>${counts.failed || 0}</strong> gagal, <strong>${counts.skipped || 0}</strong> dilewati.`,
                        type,
                        8000
                    );
                })
                .catch(err => {
                    console.error('Progress error:', err);
                    progressText.text('Gagal membaca progress broadcast. Cek log server.');
                });
        };

        poll();
    }

    function updatePushSelection() {
        const $all = $('.push-checkbox');
        const $checked = $('.push-checkbox:checked');
        const selectedCount = $checked.length;

        $('#pushSelectedCount').text(`${selectedCount} dipilih`);
        $('#pushSelectionToolbar').toggleClass('active', selectedCount > 0);
        $('tr[data-tagihan-id]').removeClass('row-selected');
        $checked.closest('tr[data-tagihan-id]').addClass('row-selected');

        const $selectAll = $('#selectAllPush');
        $selectAll.prop('checked', $all.length > 0 && selectedCount === $all.length);
        $selectAll.prop('indeterminate', selectedCount > 0 && selectedCount < $all.length);
    }

    $('#selectAllPush').on('change', function () {
        $('.push-checkbox').prop('checked', this.checked);
        updatePushSelection();
    });

    $(document).on('change', '.push-checkbox', updatePushSelection);

    $('#send-selected-push').on('click', function () {
        const ids = $('.push-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (!ids.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum Ada Pilihan',
                text: 'Pilih tagihan yang ingin dikirim notifikasi terlebih dahulu.',
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'btn btn-warning' },
                buttonsStyling: false
            });
            return;
        }

        const btn = $(this);
        const originalText = btn.html();

        Swal.fire({
            title: 'Kirim Notifikasi Terpilih?',
            html: `<p>Kirim notifikasi tagihan ke <strong>${ids.length}</strong> pelanggan terpilih?</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="ri-send-plane-line me-2"></i>Ya, Kirim',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-success me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (!result.isConfirmed) return;

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...');

            fetch("{{ route('tagihan.push') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ tagihan_ids: ids })
            })
            .then(parseJsonFetchResponse)
            .then(data => {
                btn.prop('disabled', false).html(originalText);

                if (data.success && data.progress_url) {
                    showBottomSnackbar(`Broadcast dimulai untuk <strong>${data.total || ids.length}</strong> tagihan. Progress akan berjalan di bawah tombol.`, 'info');
                    startBroadcastProgress(data.progress_url, data.total || ids.length);
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Broadcast Tidak Dimulai',
                        text: data.message || 'Tidak ada notifikasi yang diproses.',
                        confirmButtonText: 'OK',
                        customClass: { confirmButton: 'btn btn-warning' },
                        buttonsStyling: false
                    });
                }
            })
            .catch(err => {
                console.error('Selected push error:', err);
                btn.prop('disabled', false).html(originalText);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: err.message || 'Gagal mengirim notifikasi terpilih.',
                    confirmButtonText: 'OK',
                    customClass: { confirmButton: 'btn btn-danger' },
                    buttonsStyling: false
                });
            });
        });
    });

    // ========================================
    // TOMBOL BROADCAST PUSH NOTIFICATION
    // Server-side broadcast, aman untuk puluhan ribu pelanggan
    // ========================================
    $('#send-broadcast-push').on('click', function() {
        const btn = $(this);
        const originalText = btn.html();
        const totalTagihan = Number(btn.data('total') || 0);

        // MODAL KONFIRMASI - 2 BUTTON: YA & BATAL
        Swal.fire({
            title: 'Apakah Anda yakin?',
            html: `
                <p>Kirim reminder tagihan ke <strong>${totalTagihan}</strong> pelanggan belum lunas?</p>
                <p class="text-muted small mt-2">
                    <i class="ri-information-line"></i> Sistem akan membuat batch di server dan mengirim via queue FCM multicast.
                </p>
            `,
            icon: 'question',
            showCancelButton: true,
            showDenyButton: false,
            showCloseButton: false,
            confirmButtonText: '<i class="ri-checkbox-circle-line me-2"></i>Ya, Kirim!',
            cancelButtonText: '<i class="ri-close-line me-2"></i>Batal',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            reverseButtons: false,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-success me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...');

                fetch("{{ route('push.notification.broadcast.all') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(parseJsonFetchResponse)
                .then(data => {
                    btn.prop('disabled', false).html(originalText);

                    console.log('Response data:', data);

                    if (data.success && data.progress_url) {
                        showBottomSnackbar(`Broadcast dimulai untuk <strong>${data.total || totalTagihan}</strong> tagihan. Progress akan berjalan di bawah tombol.`, 'info');
                        startBroadcastProgress(data.progress_url, data.total || totalTagihan);
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Broadcast Tidak Dimulai',
                            text: data.message || 'Tidak ada notifikasi yang diproses. Pastikan pelanggan memiliki token valid.',
                            showCancelButton: false,
                            showDenyButton: false,
                            showCloseButton: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-warning'
                            },
                            buttonsStyling: false
                        });
                    }
                })
                .catch(err => {
                    console.error('Error detail:', err);
                    btn.prop('disabled', false).html(originalText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: err.message || 'Gagal mengirim notifikasi. Silakan coba lagi atau hubungi administrator.',
                        showCancelButton: false,
                        showDenyButton: false,
                        showCloseButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                });
            }
        });
    });

    // ========================================
    // TOMBOL BROADCAST INFO/IKLAN - 2 BUTTON KONFIRMASI
    // ========================================
    $('#send-broadcast-info').on('click', function() {
        // MODAL INPUT - 2 BUTTON: KIRIM & BATAL
        Swal.fire({
            title: '<i class="ri-megaphone-line me-2"></i>Kirim Info/Iklan',
            html: `
                <div class="text-start">
                    <label for="swal-input-message" class="form-label fw-bold">Pesan yang akan dikirim:</label>
                    <textarea
                        id="swal-input-message"
                        class="form-control"
                        rows="4"
                        placeholder="Contoh: Promo spesial bulan ini! Diskon 50% untuk semua paket internet"
                        maxlength="500"
                    ></textarea>
                    <small class="text-muted d-block mt-2">
                        <i class="ri-information-line"></i> Maksimal 500 karakter
                    </small>
                </div>
            `,
            showCancelButton: true,
            showDenyButton: false,
            showCloseButton: false,
            confirmButtonText: '<i class="ri-send-plane-fill me-2"></i>Kirim Sekarang',
            cancelButtonText: '<i class="ri-close-line me-2"></i>Batal',
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            reverseButtons: false,
            allowOutsideClick: false,
            focusConfirm: false,
            customClass: {
                confirmButton: 'btn btn-info me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false,
            preConfirm: () => {
                const message = document.getElementById('swal-input-message').value.trim();
                if (!message) {
                    Swal.showValidationMessage('Pesan tidak boleh kosong!');
                    return false;
                }
                if (message.length < 10) {
                    Swal.showValidationMessage('Pesan minimal 10 karakter!');
                    return false;
                }
                return message;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const message = result.value;
                const btn = $('#send-broadcast-info');
                const originalText = btn.html();
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...');
                showLoading();

                fetch("{{ route('push.notification.broadcast.info') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ message: message })
                })
                .then(parseJsonFetchResponse)
                .then(data => {
                    hideLoading();
                    btn.prop('disabled', false).html(originalText);

                    console.log('Response data:', data);

                    if(data.success && data.sent > 0){
                        // ? MODAL SUCCESS - HANYA 1 BUTTON
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil Terkirim!',
                            html: `
                                <div class="text-center">
                                    <p class="mb-2"><strong class="text-info fs-4">${data.sent}</strong> notifikasi info berhasil dikirim</p>
                                    ${data.ignored > 0 ? `<p class="text-muted small mb-0"><i class="ri-information-line"></i> ${data.ignored} pelanggan diabaikan (SID kosong)</p>` : ''}
                                </div>
                            `,
                            showCancelButton: false,
                            showDenyButton: false,
                            showCloseButton: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-info'
                            },
                            buttonsStyling: false
                        });
                    } else {
                        // ? MODAL WARNING - HANYA 1 BUTTON
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak Ada Yang Terkirim',
                            text: data.message || 'Tidak ada notifikasi yang berhasil dikirim. Pastikan pelanggan memiliki SID yang valid.',
                            showCancelButton: false,
                            showDenyButton: false,
                            showCloseButton: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'btn btn-warning'
                            },
                            buttonsStyling: false
                        });
                    }
                })
                .catch(err => {
                    console.error('Error detail:', err);
                    hideLoading();
                    btn.prop('disabled', false).html(originalText);
                    // ? MODAL ERROR - HANYA 1 BUTTON
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: err.message || 'Gagal mengirim notifikasi. Silakan coba lagi atau hubungi administrator.',
                        showCancelButton: false,
                        showDenyButton: false,
                        showCloseButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                });
            }
        });
    });
});
</script>

<style>
/* Custom SweetAlert2 Styling */
.swal2-input,
.swal2-textarea {
    border: 2px solid #e4e4e7 !important;
    border-radius: 8px !important;
    padding: 12px !important;
    font-size: 14px !important;
}

.swal2-input:focus,
.swal2-textarea:focus {
    border-color: #18181b !important;
    box-shadow: 0 0 0 2px #fff, 0 0 0 4px #18181b !important;
}

.swal2-validation-message {
    background: #fef2f2 !important;
    color: #dc2626 !important;
    border: 1px solid #fecaca !important;
    border-radius: 6px !important;
    padding: 10px !important;
    margin-top: 10px !important;
}

/* Spinning icon animation */
.ri-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.2em;
}

.broadcast-progress-panel {
    margin: 16px;
    padding: 16px;
    border: 1px solid #e4e4e7;
    border-radius: 8px;
    background: #fff;
}

.broadcast-progress-bar {
    height: 18px;
    border-radius: 6px;
}

.broadcast-log-list {
    display: grid;
    gap: 8px;
    max-height: 260px;
    overflow: auto;
    margin-top: 12px;
}

.broadcast-log-item {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    padding: 8px 10px;
    border: 1px solid #f1f1f2;
    border-radius: 6px;
    font-size: 13px;
}

.broadcast-progress-counts {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
</style>
@endsection
