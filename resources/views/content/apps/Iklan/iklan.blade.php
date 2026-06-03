@php
use Illuminate\Support\Str;

@endphp

@extends('layouts/layoutMaster')

@section('title', 'Kelola Notifikasi')

@section('vendor-style')
@vite([
  'resources/css/app.css',
  'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])

<style>
:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --card-hover-shadow: 0 4px 16px rgba(0,0,0,0.12);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --primary-color: #18181b;
  --gray-bg: #fafafa;
  --gray-border: #e4e4e7;
  --text-muted: #71717a;
}

.notification-shell {
  display: grid;
  gap: 1rem;
}

.notification-hero {
  background: linear-gradient(180deg, #ffffff 0%, #fbfbfc 100%);
  border: 1px solid var(--gray-border);
  border-radius: 16px;
  padding: 1.25rem;
  box-shadow: var(--card-shadow);
}

.notification-hero-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.notification-hero-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: #18181b;
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  flex: 0 0 auto;
}

.notification-hero h4 {
  color: #18181b;
  letter-spacing: -0.02em;
}

.notification-hero p {
  color: var(--text-muted);
}

.notification-stat-card {
  height: 100%;
  border: 1px solid var(--gray-border);
  background: #ffffff;
  border-radius: 14px;
  padding: 1rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.notification-stat-label {
  color: var(--text-muted);
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.notification-stat-value {
  color: #18181b;
  font-size: 1.35rem;
  font-weight: 800;
  line-height: 1.2;
}

.notification-card-title {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.notification-card-title-icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: #f4f4f5;
  color: #18181b;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 1px solid var(--gray-border);
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
  padding: 1.25rem 1.5rem;
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

.notification-title-cell strong {
  display: block;
  color: #18181b;
  font-size: 0.95rem;
  margin-bottom: 0.25rem;
}

.notification-title-cell small {
  line-height: 1.45;
}

.notification-empty {
  padding: 3rem 1rem;
  text-align: center;
}

.notification-empty-icon {
  width: 58px;
  height: 58px;
  margin: 0 auto 1rem;
  border-radius: 18px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #f4f4f5;
  color: #18181b;
  border: 1px solid var(--gray-border);
  font-size: 1.65rem;
}

.table-modern.is-dense th,
.table-modern.is-dense td {
  padding: 0.55rem 0.85rem !important;
}

.mui-checkbox {
  appearance: none;
  width: 20px;
  height: 20px;
  border: 2px solid #cbd5e1;
  border-radius: 5px;
  background: #fff;
  cursor: pointer;
  position: relative;
  display: inline-block;
  vertical-align: middle;
}

.mui-checkbox:checked {
  background: #18181b;
  border-color: #18181b;
}

.mui-checkbox:checked::after {
  content: '';
  position: absolute;
  top: 2px;
  left: 6px;
  width: 5px;
  height: 10px;
  border: solid #fff;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.bulk-action-bar {
  display: none;
  align-items: center;
  justify-content: space-between;
  padding: 0.85rem 1.25rem;
  background: #f8fafc;
  border-bottom: 1px solid #e4e4e7;
  color: #18181b;
  font-weight: 700;
}

.bulk-action-bar.show {
  display: flex;
}

.btn-bulk-delete {
  border: 0;
  background: #fee2e2;
  color: #dc2626;
  width: 36px;
  height: 36px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.dense-toggle-wrap {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  color: #334155;
}

.dense-toggle-wrap input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #18181b;
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
  transform: translateY(-2px) !important;
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
}

.btn-success:hover,
.btn.btn-success:hover {
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

.btn-secondary,
.btn.btn-secondary {
  background: transparent !important;
  background-color: transparent !important;
  border: 1px solid #e4e4e7 !important;
  color: #18181b !important;
}

.btn-secondary:hover,
.btn.btn-secondary:hover {
  background: #f4f4f5 !important;
  background-color: #f4f4f5 !important;
  border-color: #18181b !important;
  color: #18181b !important;
}

.action-btn {
  width: 34px;
  height: 34px;
  border: 0;
  border-radius: 8px;
  background: transparent;
  color: #94a3b8;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
}

.action-btn:hover,
.action-btn:focus {
  background: #f1f5f9;
  color: #18181b;
}

.action-btn i {
  font-size: 1.25rem;
  line-height: 1;
}

.iklan-action-menu {
  position: relative;
  min-width: 160px;
  padding: 0.7rem;
  border: 1px solid #e2e8f0 !important;
  border-radius: 14px !important;
  background: linear-gradient(115deg, #fff4f1 0%, #ffffff 50%, #f1fbff 100%) !important;
  box-shadow: 0 18px 42px rgba(15, 23, 42, 0.16) !important;
  margin-top: -62px !important;
  margin-right: 34px !important;
  overflow: visible !important;
}

.iklan-action-menu::after {
  content: '';
  position: absolute;
  top: 50%;
  right: -8px;
  width: 16px;
  height: 16px;
  background: #f7fdff;
  border-right: 1px solid #e2e8f0;
  border-top: 1px solid #e2e8f0;
  transform: translateY(-50%) rotate(45deg);
  z-index: 0;
}

.iklan-action-menu .dropdown-item {
  position: relative;
  z-index: 1;
  border-radius: 10px;
  padding: 0.55rem 0.6rem;
  font-weight: 600;
  color: #1f2937;
  gap: 0.65rem;
}

.iklan-action-menu .dropdown-item:hover {
  background: rgba(255, 255, 255, 0.72);
  color: #111827;
}

.iklan-action-menu .dropdown-item.danger-action {
  color: #ff4528 !important;
}

.iklan-action-menu .dropdown-item.danger-action:hover {
  background: rgba(255, 69, 40, 0.08) !important;
}

.iklan-action-menu .dropdown-item i {
  font-size: 1.2rem;
  line-height: 1;
}

/* ========== BADGES ========== */
.badge {
  border-radius: 9999px !important;
  font-weight: 500 !important;
  padding: 0.35rem 0.75rem !important;
}

.badge.bg-success {
  background: #18181b !important;
  color: #fafafa !important;
}

.badge.bg-secondary {
  background: #f4f4f5 !important;
  color: #71717a !important;
  border: 1px solid #e4e4e7;
}

.badge.bg-label-primary,
.badge.bg-label-info,
.badge.bg-label-success,
.badge.bg-label-warning {
  background: #f4f4f5 !important;
  color: #18181b !important;
  border: 1px solid #e4e4e7;
}

/* ========== NOTIFICATION IMAGE ========== */
.notification-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 12px;
  border: 1px solid #e4e4e7;
}

@media (max-width: 767.98px) {
  .notification-hero {
    padding: 1rem;
  }

  .notification-hero-title {
    align-items: flex-start;
  }

  .notification-hero .btn {
    width: 100%;
    margin-top: 1rem;
  }
}

/* ========== LOADING OVERLAY ========== */
.loading-overlay {
  position: fixed;
  inset: 0;
  background: rgba(24, 24, 27, 0.5);
  backdrop-filter: blur(4px);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

/* ========== PAGINATION STYLES ========== */
.pagination-wrapper {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1rem 1.25rem;
  border-top: 1px solid #e4e4e7;
  background: #fafafa;
  border-radius: 0 0 var(--border-radius) var(--border-radius);
}

.pagination-wrapper .mui-pagination {
  display: flex;
  align-items: center;
  gap: 0.45rem;
  margin: 0;
}

.pagination-wrapper .mui-pagination .page-link {
  min-width: 34px;
  height: 34px;
  border-radius: 50% !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 0 !important;
  color: #18181b !important;
  font-weight: 700;
  background: transparent !important;
  box-shadow: none !important;
  padding: 0 0.5rem;
}

.pagination-wrapper .mui-pagination .page-item.active .page-link {
  background: #18181b !important;
  color: #fff !important;
}

.pagination-wrapper .mui-pagination .page-link:hover {
  background: #f1f5f9 !important;
}

/* ========== ANIMATIONS ========== */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

.card {
  animation: fadeIn 0.3s ease-out;
}

.bottom-snackbar {
  position: fixed;
  right: 24px;
  bottom: 28px;
  transform: translateY(10px);
  z-index: 99999;
  min-width: min(360px, calc(100vw - 32px));
  max-width: min(520px, calc(100vw - 32px));
  padding: 14px 20px;
  border-radius: 18px;
  background: #111827;
  color: #ffffff;
  font-size: 1rem;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 12px;
  box-shadow: 0 16px 42px rgba(15,23,42,.32);
  opacity: 0;
  transition: all .2s ease;
}

.bottom-snackbar.is-error {
  background: #dc2626;
}

.bottom-snackbar.show {
  opacity: 1;
  transform: translateY(0);
}

.bottom-snackbar.hide {
  opacity: 0;
  transform: translateY(-10px);
}

.bottom-snackbar i {
  flex-shrink: 0;
  font-size: 1.15rem;
  line-height: 1;
}
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    function showBottomSnackbar(message, type = 'success') {
        const existing = document.querySelector('.bottom-snackbar');
        if (existing) existing.remove();

        const isError = type === 'error';

        const snackbar = document.createElement('div');
        snackbar.className = `bottom-snackbar${isError ? ' is-error' : ''}`;
        snackbar.innerHTML = `
            <i class="${isError ? 'ri-error-warning-line' : 'ri-checkbox-circle-line'}" style="color:${isError ? '#fecaca' : '#86efac'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(snackbar);
        requestAnimationFrame(() => snackbar.classList.add('show'));
        setTimeout(() => {
            snackbar.classList.add('hide');
            setTimeout(() => snackbar.remove(), 220);
        }, 3200);
    }

    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }

    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    function updateBulkActionBar() {
        const checkedCount = $('.row-checkbox:checked').length;
        $('#selectedCount').text(`${checkedCount} dipilih`);
        $('#bulkActionBar').toggleClass('show', checkedCount > 0);
        $('#selectAll').prop('checked', $('.row-checkbox').length > 0 && checkedCount === $('.row-checkbox').length);
    }

    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateBulkActionBar();
    });

    $(document).on('change', '.row-checkbox', updateBulkActionBar);

    const denseToggle = document.getElementById('densePaddingToggle');
    const tableEl = document.querySelector('.table-modern');
    if (denseToggle && tableEl) {
        const savedDense = localStorage.getItem('iklan_dense_padding') === '1';
        denseToggle.checked = savedDense;
        tableEl.classList.toggle('is-dense', savedDense);

        denseToggle.addEventListener('change', function() {
            const isDense = denseToggle.checked;
            tableEl.classList.toggle('is-dense', isDense);
            localStorage.setItem('iklan_dense_padding', isDense ? '1' : '0');
        });
    }

    let progressPoller = null;

    function stopProgressPolling() {
        if (progressPoller) {
            clearInterval(progressPoller);
            progressPoller = null;
        }
    }

    function trackSendProgress(notifId, title = 'Notifikasi') {
        stopProgressPolling();

        Swal.fire({
            title: 'Mengirim Notifikasi',
            html: `<p class="mb-1"><strong>${title}</strong></p><p class="mb-0">Menyiapkan data...</p>`,
            allowOutsideClick: false,
            showConfirmButton: false,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup'
            },
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const pullProgress = () => {
            fetch(`/dashboard/admin/iklan/${notifId}/progress`)
                .then((res) => res.json())
                .then((data) => {
                    const total = Number(data.total || 0);
                    const processed = Number(data.processed || 0);
                    const sent = Number(data.sent || 0);
                    const failed = Number(data.failed || 0);
                    const status = data.status || 'processing';

                    const progressText = total > 0
                        ? `${processed}/${total}`
                        : `${processed}`;

                    const html = `<p class="mb-1"><strong>${title}</strong></p><p class="mb-0">Progress: <strong>${progressText}</strong> | Berhasil: <strong>${sent}</strong> | Gagal: <strong>${failed}</strong></p>`;

                    if (Swal.isVisible()) {
                        Swal.update({ html });
                    }

                    if (status === 'completed') {
                        stopProgressPolling();
                        Swal.fire({
                            icon: 'success',
                            title: 'Pengiriman Selesai',
                            html: `<p class="mb-0">Berhasil: <strong>${sent}</strong> | Gagal: <strong>${failed}</strong></p>`,
                            showCancelButton: false,
                            showDenyButton: false,
                            confirmButtonText: 'Refresh Data',
                            customClass: {
                                container: 'swal-tailwind-backdrop',
                                popup: 'swal-tailwind-popup',
                                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-success'
                            },
                            buttonsStyling: false
                        }).then(() => window.location.reload());
                    }
                })
                .catch(() => {
                    stopProgressPolling();
                    if (Swal.isVisible()) {
                        Swal.close();
                    }
                    showBottomSnackbar('Gagal mengambil progress pengiriman.', 'error');
                });
        };

        pullProgress();
        progressPoller = setInterval(pullProgress, 2000);
    }

    // Kirim Notifikasi
    $(document).on('click', '.btn-send', function(e) {
        e.preventDefault();
        const notifId = $(this).data('id');
        const title = $(this).data('title');

        Swal.fire({
            title: 'Kirim Notifikasi?',
            html: `<p>Kirim "<strong>${title}</strong>" ke semua pelanggan?</p>`,
            icon: 'question',
            showCancelButton: true,
            showDenyButton: false,
            confirmButtonText: '<i class="ri-send-plane-fill me-2"></i>Ya, Kirim!',
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
                showLoading();

                fetch(`/dashboard/admin/iklan/${notifId}/send`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    hideLoading();

                    if (data.queued) {
                        showBottomSnackbar('Notifikasi sedang dikirim di background. Anda bisa lanjut bekerja.', 'success');
                        return;
                    }

                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            html: `<p><strong>${data.sent || 0}</strong> notifikasi berhasil dikirim</p>`,
                            showCancelButton: false,
                            showDenyButton: false,
                            confirmButtonText: 'OK',
                            customClass: {
                                container: 'swal-tailwind-backdrop',
                                popup: 'swal-tailwind-popup',
                                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-success'
                            },
                            buttonsStyling: false
                        });
                    }
                })
                .catch(err => {
                    hideLoading();
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan!',
                        showCancelButton: false,
                        showDenyButton: false,
                        confirmButtonText: 'OK',
                        customClass: {
                            container: 'swal-tailwind-backdrop',
                            popup: 'swal-tailwind-popup',
                            confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger'
                        },
                        buttonsStyling: false
                    });
                });
            }
        });
    });

    // Delete Notifikasi
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');

        Swal.fire({
            title: 'Hapus Notifikasi?',
            text: 'Data akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: false,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $('#btnBulkDelete').on('click', async function() {
        const checked = $('.row-checkbox:checked').toArray();
        if (!checked.length) return;

        const result = await Swal.fire({
            title: 'Hapus Notifikasi Terpilih?',
            text: `Anda akan menghapus ${checked.length} notifikasi. Data tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                container: 'swal-tailwind-backdrop',
                popup: 'swal-tailwind-popup',
                confirmButton: 'swal-tailwind-confirm swal-tailwind-confirm-danger',
                cancelButton: 'swal-tailwind-cancel'
            },
            buttonsStyling: false
        });

        if (!result.isConfirmed) return;

        showLoading();
        for (const cb of checked) {
            try {
                await fetch(`/dashboard/admin/iklan/${$(cb).val()}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
            } catch (e) {}
        }
        window.location.reload();
    });

    @if(session('iklan_progress_id'))
        showBottomSnackbar('Notifikasi sedang dikirim di background. Anda bisa lanjut membuka menu lain.', 'success');
    @endif

    @if(session('success') && !session('iklan_progress_id'))
        showBottomSnackbar(@json(session('success')), 'success');
    @endif

    @if(session('error'))
        showBottomSnackbar(@json(session('error')), 'error');
    @endif
});
</script>
@endsection

@section('content')
@php
    $notifCollection = $iklans->getCollection();
    $totalSentOnPage = $notifCollection->sum(fn ($item) => (int) ($item->total_sent ?? 0));
    $sentCountOnPage = $notifCollection->filter(fn ($item) => !empty($item->sent_at))->count();
    $maintenanceCountOnPage = $notifCollection->where('type', 'maintenance')->count();
@endphp

<div class="loading-overlay">
    <div class="spinner-border text-light" style="width: 3rem; height: 3rem;"></div>
</div>

<div class="notification-shell">
    <div class="notification-hero">
        <div class="row g-3 align-items-center">
            <div class="col-lg-6">
                <div class="notification-hero-title">
                    <span class="notification-hero-icon"><i class="ri-notification-3-line"></i></span>
                    <div>
                        <h4 class="mb-1 fw-bold">Kelola Notifikasi</h4>
                        <p class="mb-0 small">Buat informasi, maintenance, dan iklan. Sistem akan kirim FCM lalu fallback WebPushr jika token pelanggan kosong.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-2">
                    <div class="col-6 col-md-4">
                        <div class="notification-stat-card">
                            <div class="notification-stat-label">Total</div>
                            <div class="notification-stat-value">{{ number_format($iklans->total()) }}</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-4">
                        <div class="notification-stat-card">
                            <div class="notification-stat-label">Terkirim</div>
                            <div class="notification-stat-value">{{ number_format($totalSentOnPage) }}</div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="notification-stat-card">
                            <div class="notification-stat-label">Maintenance</div>
                            <div class="notification-stat-value">{{ number_format($maintenanceCountOnPage) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="card border-0 shadow-sm">
    <div class="card-header-custom">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="notification-card-title">
                <span class="notification-card-title-icon"><i class="ri-list-check-2"></i></span>
                <div>
                    <h4 class="mb-1 fw-bold">Daftar Notifikasi</h4>
                    <p class="mb-0 text-muted small">{{ $sentCountOnPage }} sudah selesai dikirim di halaman ini</p>
                </div>
            </div>
            <a href="{{ route('iklan.create')}}" class="btn btn-primary">
                <i class="ri-add-line me-2 text-white"></i>Buat Notifikasi
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="bulk-action-bar" id="bulkActionBar">
            <span id="selectedCount">0 dipilih</span>
            <button type="button" class="btn-bulk-delete" id="btnBulkDelete" title="Hapus Terpilih">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
        <div class="table-responsive p-0">
            <table class="table table-modern table-hover">
                <thead>
                    <tr>
                        <th style="width:56px; text-align:center;">
                            <input type="checkbox" class="mui-checkbox" id="selectAll" title="Pilih Semua">
                        </th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th>Terkirim</th>
                        <th>Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($iklans as $notif)
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" class="mui-checkbox row-checkbox" value="{{ $notif->id }}">
                        </td>
                        <td>
                            @if($notif->image)
                            <img src="{{ asset('storage/' . $notif->image) }}" class="notification-image" alt="Image">
                            @else
                            <div class="notification-image bg-light d-flex align-items-center justify-content-center">
                                <i class="ri-image-line text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td class="notification-title-cell">
                            <strong>{{ $notif->title }}</strong>
                            <small class="text-muted">{{ Str::limit($notif->message, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge bg-label-{{ $notif->type_color }}">
                                <i class="{{ $notif->type_icon }} me-1"></i>
                                {{ ucfirst($notif->type) }}
                            </span>
                        </td>
                        <td>
                            @if($notif->status === 'draft')
                                <span class="badge bg-secondary">Draft</span>
                            @else
                                <span class="badge bg-success">Aktif</span>
                            @endif
                        </td>
                        <td>
                            @if($notif->sent_at)
                                <span class="text-success fw-semibold">{{ number_format($notif->total_sent) }} orang</span>
                                <br>
                                <small class="text-muted">{{ $notif->sent_at->format('d M Y H:i') }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $notif->created_at->format('d M Y') }}</small>
                            <br>
                            <small class="text-muted">{{ $notif->creator->name ?? 'Admin' }}</small>
                        </td>
                        <td>
                            <div class="dropdown d-flex justify-content-center">
                                <button class="action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Aksi">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end iklan-action-menu">
                                    @if($notif->status === 'draft')
                                    <li>
                                        <a href="javascript:void(0);"
                                           class="dropdown-item d-flex align-items-center btn-send"
                                           data-id="{{ $notif->id }}"
                                           data-title="{{ $notif->title }}">
                                            <i class="ri-send-plane-fill text-success"></i>Kirim
                                        </a>
                                    </li>
                                    @endif
                                    <li>
                                        <a href="{{ route('iklan.edit', $notif->id) }}" class="dropdown-item d-flex align-items-center">
                                            <i class="ri-edit-line text-primary"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('iklan.destroy', $notif->id) }}" method="POST" class="m-0 p-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item danger-action btn-delete d-flex align-items-center w-100 border-0 bg-transparent text-start">
                                                <i class="ri-delete-bin-line"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="notification-empty">
                                <div class="notification-empty-icon"><i class="ri-notification-off-line"></i></div>
                                <h5 class="mb-1 fw-bold">Belum ada notifikasi</h5>
                                <p class="text-muted mb-3">Mulai buat informasi, maintenance, atau iklan untuk pelanggan.</p>
                                <a href="{{ route('iklan.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line text-white"></i>Buat Notifikasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-wrapper">
            <label class="dense-toggle-wrap mb-0">
                <input type="checkbox" id="densePaddingToggle">
                <span>Dense padding</span>
            </label>
            <div>
                {{ $iklans->appends(request()->query())->onEachSide(1)->links('pagination.mui') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
