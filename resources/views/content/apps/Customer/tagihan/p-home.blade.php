@php
    $user = auth('customer')->user();
use Illuminate\Support\Str;

@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
@include('content.apps.Customer.partials.disable-zoom')
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Home - Dashboard</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    background: #f8fafc;
    font-family: 'Inter', sans-serif;
    padding: 0 0 90px 0;
    min-height: 100vh;
    color: #0f172a;
}

.container {
    max-width: 680px;
    padding: 0 16px;
}

/* Hero Section */
.hero-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    padding: 32px 20px 40px;
    margin: 0 -16px;
    color: white;
}

.hero-greeting {
    font-size: 0.875rem;
    color: #94a3b8;
    margin-bottom: 4px;
}

.hero-name {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 8px;
    letter-spacing: -0.02em;
}

.hero-subtitle {
    font-size: 0.9375rem;
    color: #cbd5e1;
}

/* Stats Cards */
.stats-section {
    margin: -24px 0 24px;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    transition: all 0.2s ease;
}

.stat-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    border-color: #cbd5e1;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    font-size: 1.25rem;
}

.stat-icon.primary {
    background: #f0f9ff;
    color: #0369a1;
}

.stat-icon.success {
    background: #f0fdf4;
    color: #15803d;
}

.stat-icon.warning {
    background: #fef3c7;
    color: #d97706;
}

.stat-icon.danger {
    background: #fef2f2;
    color: #dc2626;
}

.stat-label {
    font-size: 0.8125rem;
    color: #64748b;
    margin-bottom: 4px;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.02em;
}

/* Iklan/Info Section */
.info-section {
    margin-bottom: 24px;
}

.info-card {
    background: white;
    border-radius: 16px;
    padding: 0;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    margin-bottom: 12px;
    overflow: hidden;
    transition: all 0.2s ease;
    cursor: pointer;
}

.info-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    border-color: #cbd5e1;
    transform: translateY(-2px);
}

.info-card.maintenance {
    border-color: #fbbf24;
    background: linear-gradient(to right, #fef3c7, #ffffff);
}

.info-card.informasi {
    border-color: #60a5fa;
    background: linear-gradient(to right, #dbeafe, #ffffff);
}

.info-card.iklan {
    border-color: #a78bfa;
    background: linear-gradient(to right, #ede9fe, #ffffff);
}

.info-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
    background: #f1f5f9;
}

.info-content {
    padding: 16px 20px;
}

.info-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.info-badge.maintenance {
    background: #fef3c7;
    color: #d97706;
}

.info-badge.informasi {
    background: #dbeafe;
    color: #0369a1;
}

.info-badge.iklan {
    background: #ede9fe;
    color: #7c3aed;
}

.info-title {
    font-size: 1.0625rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
    line-height: 1.4;
}

.info-message {
    font-size: 0.875rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 0;
}

.info-timestamp {
    font-size: 0.75rem;
    color: #94a3b8;
    margin-top: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Modal Detail Iklan */
.iklan-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    animation: fadeIn 0.3s ease;
    padding: 20px;
}

.iklan-modal-overlay.show {
    display: flex;
}

.iklan-modal-content {
    background: white;
    border-radius: 24px;
    width: 100%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    animation: slideUp 0.3s ease;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

.iklan-modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0,0,0,0.5);
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: all 0.2s ease;
}

.iklan-modal-close:hover {
    background: rgba(0,0,0,0.7);
    transform: rotate(90deg);
}

.iklan-modal-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    cursor: zoom-in;
    transition: transform 0.3s ease;
}

.iklan-modal-image:hover {
    transform: scale(1.02);
}

.iklan-modal-body {
    padding: 24px;
}

.iklan-modal-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 24px;
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 16px;
}

.iklan-modal-badge.maintenance {
    background: #fef3c7;
    color: #d97706;
}

.iklan-modal-badge.informasi {
    background: #dbeafe;
    color: #0369a1;
}

.iklan-modal-badge.iklan {
    background: #ede9fe;
    color: #7c3aed;
}

.iklan-modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 12px;
    line-height: 1.3;
}

.iklan-modal-message {
    font-size: 1rem;
    color: #475569;
    line-height: 1.7;
    margin-bottom: 20px;
    white-space: pre-wrap;
}

.iklan-modal-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
}

.iklan-modal-time {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.875rem;
    color: #94a3b8;
}

/* Image Zoom Modal */
.zoom-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.95);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 3000;
    cursor: zoom-out;
    animation: fadeIn 0.3s ease;
    padding: 20px;
}

.zoom-overlay.show {
    display: flex;
}

.zoom-overlay img {
    max-width: 95%;
    max-height: 95%;
    object-fit: contain;
    animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
    from {
        transform: scale(0.5);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Quick Actions */
.section-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 16px;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
    margin-bottom: 32px;
}

.action-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    text-decoration: none;
    color: inherit;
}

.action-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    border-color: #cbd5e1;
    transform: translateY(-2px);
}

.action-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    font-size: 1.75rem;
}

.action-icon.primary {
    background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    color: white;
}

.action-icon.success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.action-icon.warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.action-icon.purple {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    color: white;
}

.action-title {
    font-size: 0.9375rem;
    font-weight: 600;
    color: #0f172a;
    margin-bottom: 4px;
}

.action-subtitle {
    font-size: 0.75rem;
    color: #64748b;
}

/* Bottom Navigation */
.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 72px;
    background: #ffffff;
    display: flex;
    justify-content: space-around;
    align-items: center;
    box-shadow: 0 -2px 16px rgba(0,0,0,0.08);
    border-top: 1px solid #e2e8f0;
    z-index: 999;
}

.bottom-nav .tab-btn {
    background: none;
    border: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    color: #94a3b8;
    position: relative;
    transition: all 0.2s ease;
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 12px;
}

.bottom-nav .tab-btn:hover {
    background: #f8fafc;
}

.bottom-nav .tab-btn i {
    font-size: 1.5rem;
}

.bottom-nav .tab-btn span {
    font-size: 0.6875rem;
    font-weight: 600;
    letter-spacing: -0.01em;
}

.bottom-nav .tab-btn.active {
    color: #0f172a;
}

.bottom-nav .tab-btn.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 32px;
    height: 3px;
    background: #0f172a;
    border-radius: 0 0 3px 3px;
}

/* Profile Overlay */
.profile-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    display: none;
    align-items: flex-end;
    z-index: 1000;
    animation: fadeIn 0.2s ease;
}

.profile-overlay.show {
    display: flex;
}

.profile-modal {
    background: #ffffff;
    border-radius: 24px 24px 0 0;
    width: 100%;
    max-width: 680px;
    margin: 0 auto;
    padding: 24px;
    animation: slideUp 0.3s ease;
    box-shadow: 0 -4px 24px rgba(0,0,0,0.12);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.profile-avatar i {
    font-size: 2.5rem;
    color: #64748b;
}

.profile-info h5 {
    margin: 0;
    color: #0f172a;
    font-size: 1.125rem;
    font-weight: 700;
    letter-spacing: -0.01em;
}

.profile-info p {
    margin: 4px 0 0 0;
    color: #64748b;
    font-size: 0.875rem;
}

.profile-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 20px 0;
}

.logout-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px 16px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-radius: 12px;
    color: #dc2626;
    font-size: 0.9375rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-top: 20px;
}

.logout-btn:hover {
    background: #fee2e2;
    border-color: #fca5a5;
}

.logout-btn i {
    font-size: 1.125rem;
}

/* ========== CUSTOM SWEETALERT2 MODAL - GLASSMORPHISM PREMIUM ========== */
/* ========== GLOBAL SWEETALERT2 OVERRIDE - GLASSMORPHISM PREMIUM ========== */
.swal2-popup {
    border-radius: 28px !important;
    padding: 36px 24px 24px !important;
    width: 90% !important;
    max-width: 380px !important;
    background: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(24px) !important;
    -webkit-backdrop-filter: blur(24px) !important;
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12), 0 8px 24px rgba(15, 23, 42, 0.04) !important;
    font-family: 'Inter', sans-serif !important;
    overflow: hidden !important;
}

.swal2-popup .swal2-title {
    font-size: 1.375rem !important;
    font-weight: 800 !important;
    color: #0f172a !important;
    letter-spacing: -0.02em !important;
    margin-bottom: 8px !important;
    padding: 0 !important;
}

.swal2-popup .swal2-html-container {
    font-size: 0.875rem !important;
    color: #475569 !important;
    line-height: 1.6 !important;
    margin: 16px 0 24px !important;
    padding: 0 !important;
}

.swal2-backdrop-show {
    backdrop-filter: blur(8px) !important;
    -webkit-backdrop-filter: blur(8px) !important;
    background: rgba(15, 23, 42, 0.45) !important;
}

/* Glassmorphism Icon Badge */
.glass-icon-wrap {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(15, 23, 42, 0.05);
    border: 1px solid rgba(15, 23, 42, 0.08);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.4);
}

.glass-icon-wrap i {
    color: #0f172a !important;
    font-size: 1.75rem;
}

.glass-modal-title {
    font-size: 1.375rem;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.02em;
    margin-bottom: 8px;
}

.glass-modal-text {
    font-size: 0.875rem;
    color: #475569;
    line-height: 1.5;
    margin-bottom: 24px;
}

.glass-features-row {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
}

.glass-feature-tag {
    background: rgba(15, 23, 42, 0.04);
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 12px;
    padding: 10px 14px;
    flex: 1;
    font-size: 0.78rem;
    font-weight: 700;
    color: #1e293b;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

/* SweetAlert2 Standard Icons Override */
.swal2-icon {
    border-width: 3px !important;
    margin: 0 auto 20px !important;
    transform: scale(0.9);
}

.swal2-icon.swal2-warning {
    border-color: #f59e0b !important;
    color: #f59e0b !important;
}
.swal2-icon.swal2-warning .swal2-icon-content {
    font-size: 3rem !important;
    color: #f59e0b !important;
}

.swal2-icon.swal2-success {
    border-color: #10b981 !important;
}
.swal2-icon.swal2-success [class^=swal2-success-line] {
    background-color: #10b981 !important;
}
.swal2-icon.swal2-success .swal2-success-ring {
    border: 3px solid rgba(16, 185, 129, 0.2) !important;
}

.swal2-icon.swal2-error {
    border-color: #ef4444 !important;
}
.swal2-icon.swal2-error [class^=swal2-x-mark-line] {
    background-color: #ef4444 !important;
}

/* Buttons styling */
.swal2-actions {
    margin-top: 16px !important;
    width: 100% !important;
    gap: 8px !important;
}

.swal2-confirm.swal2-styled {
    background: #0f172a !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 0.9375rem !important;
    padding: 12px 24px !important;
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2) !important;
    transition: all 0.2s ease !important;
    margin: 0 !important;
    flex: 1;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px;
}

.swal2-confirm.swal2-styled:hover {
    background: #1e293b !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.25) !important;
}

.swal2-cancel.swal2-styled {
    background: transparent !important;
    color: #64748b !important;
    font-weight: 600 !important;
    font-size: 0.9375rem !important;
    padding: 12px 24px !important;
    border-radius: 12px !important;
    border: 1px solid rgba(15, 23, 42, 0.12) !important;
    transition: all 0.2s ease !important;
    margin: 0 !important;
    flex: 1;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px;
}

.swal2-cancel.swal2-styled:hover {
    background: rgba(15, 23, 42, 0.04) !important;
    border-color: rgba(15, 23, 42, 0.2) !important;
}

/* Backward compatibility for custom classes */
.swal2-confirm.custom-btn {
    background: #0f172a !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 0.9375rem !important;
    padding: 14px 24px !important;
    border-radius: 14px !important;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.25) !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px;
    width: 100% !important;
    margin-top: 8px !important;
}

.swal2-confirm.custom-btn:hover {
    background: #1e293b !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 18px rgba(15, 23, 42, 0.3) !important;
}

/* Modern Custom Spinner for Loading Modals */
.modern-spinner {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: conic-gradient(from 180deg at 50% 50%, rgba(59, 130, 246, 0) 0deg, #3b82f6 360deg);
    -webkit-mask: radial-gradient(farthest-side, #0000 calc(100% - 6px), #000 0);
    mask: radial-gradient(farthest-side, #0000 calc(100% - 6px), #000 0);
    animation: modern-spin 0.8s infinite linear;
    margin: 0 auto 20px auto;
}
@keyframes modern-spin {
    to { transform: rotate(1turn); }
}

/* Force-hide cancel button inside custom-modal popups */
.custom-modal .swal2-cancel {
    display: none !important;
}

.swal2-cancel.custom-cancel-btn {
    background: transparent !important;
    color: #64748b !important;
    font-weight: 600 !important;
    font-size: 0.9375rem !important;
    padding: 14px 24px !important;
    border-radius: 14px !important;
    border: 1px solid rgba(15, 23, 42, 0.12) !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px;
    width: 100% !important;
    margin-top: 8px !important;
}

.swal2-cancel.custom-cancel-btn:hover {
    background: rgba(15, 23, 42, 0.04) !important;
    border-color: rgba(15, 23, 42, 0.2) !important;
}

.swal2-close {
    color: rgba(15, 23, 42, 0.4) !important;
    font-size: 2rem !important;
    opacity: 1 !important;
    transition: all 0.25s ease !important;
}
        position: absolute !important;
        top: 14px !important;
        right: 14px !important;
        z-index: 20 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 36px !important;
        height: 36px !important;
        background: rgba(15, 23, 42, 0.05) !important;
        border-radius: 50% !important;
    }

    .swal2-close:hover {
        color: #0f172a !important;
        background: rgba(15, 23, 42, 0.1) !important;
        transform: rotate(90deg) scale(1.1) !important;
    }

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}

body.modal-open {
    overflow: hidden;
}

/* Responsive */
@media (max-width: 576px) {
    .hero-name {
        font-size: 1.5rem;
    }

    .stats-section {
        gap: 10px;
    }

    .stat-card {
        padding: 16px;
    }

    .quick-actions {
        gap: 10px;
    }

    .iklan-modal-content {
        max-width: 95%;
    }

    .iklan-modal-image {
        height: 200px;
    }
}
</style>
</head>

<body>
<div class="container">
    <!-- Hero Section -->
<!-- Hero Section -->
<div class="hero-section">
    @php
        $hour = \Carbon\Carbon::now('Asia/Jakarta')->format('H');
        if ($hour >= 5 && $hour < 11) {
            $greeting = 'Selamat Pagi';
            $icon = 'bi-brightness-high-fill';
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = 'Selamat Siang';
            $icon = 'bi-sun-fill';
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = 'Selamat Sore';
            $icon = 'bi-sunset-fill';
        } else {
            $greeting = 'Selamat Malam';
            $icon = 'bi-moon-stars-fill';
        }
    @endphp

    <div class="hero-greeting">
        {{ $greeting }}, <i class="bi {{ $icon }}"></i>
    </div>
    <div class="hero-name">{{ $user->nama_lengkap ?? 'Pelanggan' }}</div>
    <div class="hero-subtitle">Kelola tagihan Anda dengan mudah</div>
</div>


    <!-- Stats Cards -->
    <div class="stats-section">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-label">Total Tagihan</div>
            <div class="stat-value">{{ $totalTagihan ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Lunas</div>
            <div class="stat-value">{{ $tagihanLunas ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-clock"></i>
            </div>
            <div class="stat-label">Menunggu</div>
            <div class="stat-value">{{ $tagihanMenunggu ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="bi bi-exclamation-circle"></i>
            </div>
            <div class="stat-label">Belum Bayar</div>
            <div class="stat-value">{{ $tagihanBelum ?? 0 }}</div>
        </div>
    </div>

    <!-- Iklan Maintenance (Di Atas) -->
    @if(isset($iklans) && $iklans->where('type', 'maintenance')->count() > 0)
    <div class="info-section">
        @foreach($iklans->where('type', 'maintenance') as $iklan)
        <div class="info-card maintenance" onclick='openIklanModal("{{ $iklan->id }}", "{{ $iklan->type }}", "{{ addslashes($iklan->title) }}", {{ json_encode($iklan->message) }}, "{{ $iklan->image ? asset("storage/" . $iklan->image) : "" }}", "{{ $iklan->created_at->diffForHumans() }}")'>
            @if($iklan->image)
            <img src="{{ asset('storage/' . $iklan->image) }}" alt="{{ $iklan->title }}" class="info-image">
            @endif
            <div class="info-content">
                <span class="info-badge maintenance">
                    <i class="bi bi-tools"></i>
                    Maintenance
                </span>
                <div class="info-title">{{ $iklan->title }}</div>
                <p class="info-message">{{ Str::limit($iklan->message, 100) }}</p>
                <div class="info-timestamp">
                    <i class="bi bi-clock"></i>
                    {{ $iklan->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="section-title">Menu Cepat</div>
  <div class="quick-actions">
    <a href="/dashboard/customer/tagihan" class="action-card">
        <div class="action-icon primary">
            <i class="bi bi-receipt"></i>
        </div>
        <div class="action-title">Tagihan</div>
        <div class="action-subtitle">Lihat tagihan aktif</div>
    </a>

    <a href="/dashboard/customer/tagihan/selesai" class="action-card">
        <div class="action-icon success">
            <i class="bi bi-file-earmark-text"></i>
        </div>
        <div class="action-title">Kwitansi</div>
        <div class="action-subtitle">Riwayat pembayaran</div>
    </a>

    <a href="https://layanan.jernih.net.id/dashboard/customer/chat" class="action-card">
        <div class="action-icon warning">
            <i class="bi bi-chat-dots"></i>
        </div>
        <div class="action-title">Chat CS</div>
        <div class="action-subtitle">Hubungi kami</div>
    </a>

    <!-- Fitur Baru: Chat Admin -->
    <a href="https://layanan.jernih.net.id/dashboard/customer/chat-billing" class="action-card">
        <div class="action-icon purple">
            <i class="bi bi-person-badge"></i>
        </div>
        <div class="action-title">Chat Admin</div>
        <div class="action-subtitle">Hubungi admin</div>
    </a>
</div>

    <!-- Iklan/Informasi (Di Bawah) -->
    @if(isset($iklans) && $iklans->whereIn('type', ['informasi', 'iklan'])->count() > 0)
    <div class="section-title">Informasi & Promo</div>
    <div class="info-section">
        @foreach($iklans->whereIn('type', ['informasi', 'iklan']) as $iklan)
        <div class="info-card {{ $iklan->type }}" onclick='openIklanModal("{{ $iklan->id }}", "{{ $iklan->type }}", "{{ addslashes($iklan->title) }}", {{ json_encode($iklan->message) }}, "{{ $iklan->image ? asset("storage/" . $iklan->image) : "" }}", "{{ $iklan->created_at->diffForHumans() }}")'>
            @if($iklan->image)
            <img src="{{ asset('storage/' . $iklan->image) }}" alt="{{ $iklan->title }}" class="info-image">
            @endif
            <div class="info-content">
                <span class="info-badge {{ $iklan->type }}">
                    <i class="bi {{ $iklan->type == 'informasi' ? 'bi-info-circle' : 'bi-megaphone' }}"></i>
                    {{ ucfirst($iklan->type) }}
                </span>
                <div class="info-title">{{ $iklan->title }}</div>
                <p class="info-message">{{ Str::limit($iklan->message, 100) }}</p>
                <div class="info-timestamp">
                    <i class="bi bi-clock"></i>
                    {{ $iklan->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <button class="tab-btn active" onclick="window.location.href='/dashboard/customer/tagihan/home'">
        <i class="bi bi-house-door-fill"></i>
        <span>Home</span>
    </button>

    <button class="tab-btn" onclick="window.location.href='/dashboard/customer/tagihan'">
        <i class="bi bi-receipt"></i>
        <span>Tagihan</span>
    </button>

    <button class="tab-btn" onclick="window.location.href='/dashboard/customer/tagihan/selesai'">
        <i class="bi bi-file-earmark-text"></i>
        <span>Kwitansi</span>
    </button>

    <button class="tab-btn" onclick="window.location.href='/dashboard/customer/chat'">
        <i class="bi bi-chat-dots"></i>
        <span>Chat</span>
    </button>

    <button id="btn-profile" class="tab-btn">
        <i class="bi bi-person-circle"></i>
        <span>Profile</span>
    </button>
</div>

<!-- Profile Modal -->
<div id="profile-overlay" class="profile-overlay">
    <div class="profile-modal">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="profile-info">
                <h5>{{ $user->nama_lengkap ?? 'Nama Pelanggan' }}</h5>
                <p>{{ $user->whatsapp }}</p>
            </div>
        </div>

        <div class="profile-divider"></div>

        <button id="btn-logout" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar</span>
        </button>
    </div>
</div>

<!-- Modal Detail Iklan -->
<div id="iklan-modal-overlay" class="iklan-modal-overlay">
    <div class="iklan-modal-content">
        <button class="iklan-modal-close" onclick="closeIklanModal()">
            <i class="bi bi-x"></i>
        </button>
        <img id="iklan-modal-image" class="iklan-modal-image" src="" alt="" style="display: none;">
        <div class="iklan-modal-body">
            <span id="iklan-modal-badge" class="iklan-modal-badge"></span>
            <h3 id="iklan-modal-title" class="iklan-modal-title"></h3>
            <p id="iklan-modal-message" class="iklan-modal-message"></p>
            <div class="iklan-modal-footer">
                <div id="iklan-modal-time" class="iklan-modal-time">
                    <i class="bi bi-clock"></i>
                    <span></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Zoom Image Overlay -->
<div id="zoom-overlay" class="zoom-overlay" onclick="closeZoom()">
    <img id="zoom-image" src="" alt="">
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
// Profile Modal Toggle
const btnProfile = document.getElementById('btn-profile');
const overlay = document.getElementById('profile-overlay');

btnProfile.addEventListener('click', (e) => {
    e.stopPropagation();
    overlay.classList.toggle('show');
    document.body.classList.toggle('modal-open');
});

overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
        overlay.classList.remove('show');
        document.body.classList.remove('modal-open');
    }
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && overlay.classList.contains('show')) {
        overlay.classList.remove('show');
        document.body.classList.remove('modal-open');
    }
});

// Logout
document.getElementById('btn-logout').addEventListener('click', () => {
    Swal.fire({
        title: 'Keluar dari Akun?',
        text: 'Anda akan keluar dari aplikasi',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#94a3b8',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Keluar...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/customer/logout', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Anda telah keluar dari akun',
                        icon: 'success',
                        confirmButtonColor: '#0f172a',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/';
                    });
                } else {
                    throw new Error('Logout failed');
                }
            })
            .catch(() => {
                Swal.fire({
                    title: 'Error',
                    text: 'Gagal keluar dari akun',
                    icon: 'error',
                    confirmButtonColor: '#dc2626'
                });
            });
        }
    });
});

// ========== MODAL DETAIL IKLAN ==========
function openIklanModal(id, type, title, message, image, time) {
    const modal = document.getElementById('iklan-modal-overlay');
    const modalImage = document.getElementById('iklan-modal-image');
    const modalBadge = document.getElementById('iklan-modal-badge');
    const modalTitle = document.getElementById('iklan-modal-title');
    const modalMessage = document.getElementById('iklan-modal-message');
    const modalTime = document.getElementById('iklan-modal-time').querySelector('span');

    // Set badge
    let badgeIcon = '';
    let badgeText = '';

    if (type === 'maintenance') {
        badgeIcon = '<i class="bi bi-tools"></i>';
        badgeText = 'Maintenance';
    } else if (type === 'informasi') {
        badgeIcon = '<i class="bi bi-info-circle"></i>';
        badgeText = 'Informasi';
    } else {
        badgeIcon = '<i class="bi bi-megaphone"></i>';
        badgeText = 'Iklan';
    }

    modalBadge.className = 'iklan-modal-badge ' + type;
    modalBadge.innerHTML = badgeIcon + ' ' + badgeText;

    // Set content
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modalTime.textContent = time;

    // Set image
    if (image) {
        modalImage.src = image;
        modalImage.style.display = 'block';
        modalImage.onclick = function(e) {
            e.stopPropagation();
            openZoom(image);
        };
    } else {
        modalImage.style.display = 'none';
    }

    // Show modal
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeIklanModal() {
    const modal = document.getElementById('iklan-modal-overlay');
    modal.classList.remove('show');
    document.body.style.overflow = '';
}

// Close modal when clicking outside
document.getElementById('iklan-modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeIklanModal();
    }
});

// ========== IMAGE ZOOM ==========
function openZoom(imageSrc) {
    const zoomOverlay = document.getElementById('zoom-overlay');
    const zoomImage = document.getElementById('zoom-image');

    zoomImage.src = imageSrc;
    zoomOverlay.classList.add('show');
}

function closeZoom() {
    const zoomOverlay = document.getElementById('zoom-overlay');
    zoomOverlay.classList.remove('show');
}

// ESC key to close modals
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeIklanModal();
        closeZoom();
    }
});
</script>

@include('content.apps.partials.firebase-messaging', ['user' => $user])

<script>
// ========== NOTIFICATION MANAGEMENT ==========
const nomerid = "{{ $user->nomer_id }}";
const DEVICE_ID_KEY = 'device_notification_id';

function getDeviceId() {
    let deviceId = localStorage.getItem(DEVICE_ID_KEY);
    if (!deviceId) {
        deviceId = 'device_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem(DEVICE_ID_KEY, deviceId);
        console.log('?? Device ID baru dibuat:', deviceId);
    }
    return deviceId;
}

function checkNotificationStatus() {
    if (!('Notification' in window)) return;
    const permission = Notification.permission;
    const deviceId = getDeviceId();
    const hasAskedBefore = localStorage.getItem('firebase_notification_asked_' + deviceId);
    const isDisabled = localStorage.getItem('firebase_messaging_disabled') === 'true';

    if (permission === 'granted') {
        if (typeof window.syncFirebaseMessaging === 'function') {
            setTimeout(() => window.syncFirebaseMessaging(), 800);
        }
    } else if (permission === 'default' && !hasAskedBefore && !isDisabled) {
        setTimeout(() => showCustomPermissionPopup(), 3000);
    }
}

function showCustomPermissionPopup() {
    const deviceId = getDeviceId();
    Swal.fire({
        html: `
            <div class="text-center">
                <!-- Icon Bell Glass -->
                <div class="glass-icon-wrap">
                    <i class="bi bi-bell-fill"></i>
                </div>
                <h3 class="glass-modal-title">Aktifkan Notifikasi</h3>
                <p class="glass-modal-text">
                    Izinkan notifikasi agar Anda mendapatkan pemberitahuan realtime saat ada tagihan baru atau pengingat jatuh tempo langsung ke perangkat Anda.
                </p>
                <div class="glass-features-row">
                    <div class="glass-feature-tag">
                        <i class="bi bi-lightning-charge-fill" style="color: #f59e0b;"></i> Tagihan Baru
                    </div>
                    <div class="glass-feature-tag">
                        <i class="bi bi-alarm-fill" style="color: #3b82f6;"></i> Pengingat Tagihan
                    </div>
                </div>
            </div>
        `,
        confirmButtonText: '<i class="bi bi-check-circle-fill" style="margin-right: 6px;"></i> Iya, Saya Mau',
        showCancelButton: false,
        customClass: {
            popup: 'custom-modal',
            confirmButton: 'custom-btn',
            cancelButton: 'd-none'
        },
        showClass: {
            popup: 'animate__animated animate__fadeInUp animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutDown animate__faster'
        },
        allowOutsideClick: false,
        showCloseButton: true,
        backdrop: 'rgba(15,23,42,0.45)'
    }).then((result) => {
        localStorage.setItem('firebase_notification_asked_' + deviceId, 'true');
        if (result.isConfirmed) {
            requestBrowserPermission();
        }
    });
}

function requestBrowserPermission() {
    Swal.fire({
        html: `
            <div class="text-center">
                <div class="modern-spinner"></div>
                <h3 class="glass-modal-title" style="margin-bottom: 8px;">Mohon Tunggu</h3>
                <p class="glass-modal-text" style="margin-bottom: 0;">Memproses permintaan notifikasi...</p>
            </div>
        `,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        showCancelButton: false,
        customClass: {
            popup: 'custom-modal'
        }
    });

    waitForFirebaseMessaging().then(function() {
        if (typeof window.enableFirebaseMessaging !== 'function') {
            throw new Error('Firebase Messaging belum siap.');
        }

        return window.enableFirebaseMessaging();
    }).then(function(token) {
        if (token) {
            showSuccessToast();
            return;
        }

        if (Notification.permission === 'denied') {
            Swal.fire({
                icon: 'warning',
                title: 'Notifikasi Diblokir',
                html: `
                    Untuk mengaktifkan notifikasi:<br><br>
                    <strong>1.</strong> Klik ikon <i class="bi bi-lock-fill"></i> di address bar<br>
                    <strong>2.</strong> Ubah Notifikasi ke "Izinkan"<br>
                    <strong>3.</strong> Refresh halaman ini
                `,
                confirmButtonText: 'Mengerti'
            });
            return;
        }

        Swal.fire({
            icon: 'info',
            title: 'Notifikasi Belum Aktif',
            text: 'Izin notifikasi belum diberikan.',
            confirmButtonText: 'Mengerti'
        });
	    }).catch(function(error) {
	        console.error('Firebase notification failed:', error);
	        Swal.fire({
	            icon: 'error',
	            title: 'Gagal Mengaktifkan',
	            text: error?.message || 'Notifikasi belum bisa diaktifkan. Coba refresh halaman lalu ulangi.',
	            confirmButtonText: 'Mengerti'
	        });
	    });
}

function waitForFirebaseMessaging() {
    if (typeof window.enableFirebaseMessaging === 'function') {
        return Promise.resolve();
    }

    return new Promise((resolve, reject) => {
        const timeout = setTimeout(() => {
            window.removeEventListener('firebase-messaging-loaded', onReady);
            reject(new Error('Firebase Messaging timeout.'));
        }, 5000);

        function onReady() {
            clearTimeout(timeout);
            resolve();
        }

        window.addEventListener('firebase-messaging-loaded', onReady, { once: true });
    });
}

function showSuccessToast() {
    Swal.fire({
        icon: 'success',
        title: 'Notifikasi Aktif!',
        timer: 3000,
        showConfirmButton: false,
        position: 'top-end',
        toast: true
    });
}

setInterval(function() {
    const currentPermission = Notification.permission;
    const lastPermission = localStorage.getItem('last_permission_status');
    if (lastPermission && lastPermission !== currentPermission) {
        if (currentPermission === 'granted' && typeof window.syncFirebaseMessaging === 'function') {
            window.syncFirebaseMessaging();
        }
    }
    localStorage.setItem('last_permission_status', currentPermission);
}, 5000);

function getGreeting() {
    const now = new Date();
    // Konversi ke WIB (UTC+7)
    const wibOffset = 7 * 60; // offset dalam menit
    const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
    const wibTime = new Date(utc + (wibOffset * 60000));
    const hour = wibTime.getHours();

    if (hour >= 5 && hour < 11) {
        return 'Selamat Pagi! <i class="bi bi-brightness-high-fill"></i>';
    } else if (hour >= 11 && hour < 15) {
        return 'Selamat Siang! <i class="bi bi-sun-fill"></i>';
    } else if (hour >= 15 && hour < 18) {
        return 'Selamat Sore! <i class="bi bi-sunset-fill"></i>';
    } else {
        return 'Selamat Malam! <i class="bi bi-moon-stars-fill"></i>';
    }
}


// ========== POLLING UNTUK TAGIHAN BARU ==========
const userNomerId = "{{ $user->nomer_id }}";
let isModalShown = false;

function checkForNewNotifications() {
    if (isModalShown) return;
    $.get('/api/check-pending-notifications/' + userNomerId)
    .done(function(response){
        if(response.has_notification && !isModalShown){
            isModalShown = true;
            Swal.fire({
                html: `
                    <div class="modal-content-wrapper">
                        <div class="bg-circle bg-circle-1"></div>
                        <div class="bg-circle bg-circle-2"></div>
                        <div class="bg-circle bg-circle-3"></div>
                        <div class="icon-container">
                            <div class="icon-bg"></div>
                            <div class="icon-main">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                        <div class="modal-title">${getGreeting()}</div>
                        <div class="modal-subtitle">Ada tagihan yang menunggu nih!</div>
                        <div style="text-align: center;">
                            <span class="notification-badge">
                                <i class="bi bi-lightning-charge-fill"></i> Perlu Perhatian
                            </span>
                        </div>
                        <div class="feature-cards">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <div class="feature-text">Bayar Cepat</div>
                            </div>
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="feature-text">Mudah & Aman</div>
                            </div>
                        </div>
                    </div>
                `,
                confirmButtonText: '<i class="bi bi-arrow-right-circle-fill" style="margin-right: 6px;"></i> Lihat Tagihan Sekarang',
                showCancelButton: false,
                customClass: {
                    popup: 'custom-modal',
                    confirmButton: 'custom-btn'
                },
                showClass: {
                    popup: 'animate__animated animate__zoomIn animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__zoomOut animate__faster'
                },
                allowOutsideClick: true,
                showCloseButton: true,
                backdrop: 'rgba(0,0,0,0.6)'
            }).then(result=>{
                if(result.isConfirmed) {
                    window.location.href='/dashboard/customer/tagihan';
                }
                setTimeout(()=> isModalShown=false, 5000);
            });
        }
    })
    .fail(function(xhr){
        console.error('? Error polling:', xhr.responseText);
    });
}

// ========== INISIALISASI ==========
$(document).ready(function(){
    console.log('?? Aplikasi dimulai');
    console.log('?? User Nomer ID:', nomerid);
    checkNotificationStatus();

    // Polling untuk tagihan baru (setiap jam 12 siang)
    // setTimeout(checkForNewNotifications, 3000);
    // setInterval(checkForNewNotifications, 21600000);
});
</script>

</body>
</html>
