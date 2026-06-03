@extends('layouts/layoutMaster')

@section('title', 'Data Absensi')

@section('vendor-style')
@vite([
    'resources/css/app.css',
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
@endsection

@section('vendor-script')
@vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
])
@endsection

<style>
:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  --gray-border: #e4e4e7;
}
body { background: #f5f5f9; }
.card { border: none; border-radius: var(--border-radius); box-shadow: var(--card-shadow); background: white; }

/* Header */
.card-header-custom {
  background: #ffffff !important;
  border-bottom: 1px solid var(--gray-border);
  padding: 1.5rem 1.75rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
  position: relative;
}
.card-header-custom::before {
  display: none;
  content: none;
}
.card-header-custom h4 { font-size:1.4rem; font-weight:700; color:#18181b; margin:0; display:flex; align-items:center; gap:0.5rem; }
.card-header-custom h4 i { color: #18181b; }
.card-header-custom p  { color:#71717a; font-size:0.875rem; margin:0; }
.search-wrapper { position: relative; }
.search-wrapper .search-icon { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; font-size: 0.9rem; }
.search-wrapper .form-control { padding-left: 2.25rem; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.875rem; width: 260px; background: #f8fafc; transition: all 0.2s; }
.search-wrapper .form-control:focus { border-color: #18181b; box-shadow: 0 0 0 3px rgba(24,24,27,0.08); background: #fff; }


/* Table */
.table-modern { margin-bottom: 0; width: 100% !important; }
.table-modern thead th { background: #f8fafc; font-weight: 700; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.6px; color: #18181b; padding: 1.1rem 1.2rem; border: none; white-space: nowrap; }
.table-modern tbody tr { transition: var(--transition); border-bottom: 1px solid var(--gray-border); }
.table-modern tbody tr:hover { background-color: #f4f4f5 !important; }
.table-modern tbody td { padding: 1rem 1.2rem; vertical-align: middle; border-bottom: 1px solid var(--gray-border); color: #18181b; font-size: 0.9rem; }

/* Buttons */
.btn-outline-dark { border: 1px solid #18181b !important; color: #18181b !important; border-radius: 8px !important; font-weight: 600 !important; display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.8rem; }
.btn-outline-dark:hover { background: #18181b !important; color: #fff !important; }
.btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }

/* Badge style for table data */
.data-badge { background: #18181b; color: #fff; padding: 0.35rem 0.75rem; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
.data-badge-light { background: #f4f4f5; color: #18181b; border: 1px solid #e4e4e7; padding: 0.35rem 0.75rem; border-radius: 6px; font-weight: 600; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.4rem; }
.badge-risk { background: #7f1d1d; color: #fff; border-radius: 6px; padding: 0.35rem 0.6rem; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem; }
.badge-safe { background: #14532d; color: #fff; border-radius: 6px; padding: 0.35rem 0.6rem; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem; }

/* Modal */
.modal-backdrop { backdrop-filter: blur(8px); background-color: rgba(255, 255, 255, 0.4) !important; }
.modal-backdrop.show { opacity: 1 !important; }
.modal-content { border-radius: 12px; border: 1px solid var(--gray-border); box-shadow: 0 10px 40px rgba(0,0,0,0.15); overflow: hidden; }
.modal-header { background: #18181b !important; padding: 1.25rem 1.5rem; border-bottom: none; }
.modal-title { font-weight: 600; font-size: 1.1rem; color: #fafafa !important; margin: 0; }
.modal-header .btn-close { filter: invert(1); opacity: 0.8; }
.modal-body { padding: 1.5rem; }

/* Image wrapper */
.absensi-img-wrapper { border: 1px solid var(--gray-border); border-radius: 8px; padding: 4px; background: #fafafa; display: inline-block; }
.absensi-img { max-width: 100%; height: auto; border-radius: 4px; max-height: 200px; object-fit: contain; }

/* Custom Checkbox */
.custom-check { appearance: none; width: 22px; height: 22px; border: 1.5px solid #cbd5e1; border-radius: 5px; background: #fff; cursor: pointer; position: relative; display: block; margin: 0 auto; transition: all 0.2s; }
.custom-check:hover { border-color: #18181b; }
.custom-check:checked { background: #18181b; border-color: #18181b; }
.custom-check:checked::after { content: ''; position: absolute; top: 3px; left: 7px; width: 6px; height: 11px; border: solid white; border-width: 0 2px 2px 0; transform: rotate(45deg); }
.dense-toggle-wrap input[type="checkbox"] { width: 22px; height: 22px; accent-color: #18181b; }

/* Selection Toolbar */
.selection-toolbar { display: none; align-items: center; justify-content: space-between; background: #f8fafc; border: 1px solid #e2e8f0; color: #0f172a; padding: 0.9rem 1.2rem; border-radius: 10px; }
.selection-toolbar.active { display: flex; }
.selection-toolbar .selected-text { font-size: 1rem; font-weight: 700; color: #18181b; }
.selection-toolbar .toolbar-delete-btn { border: 0; background: #fee2e2; color: #dc2626; width: 36px; height: 36px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: none; transition: all 0.2s; }
.selection-toolbar .toolbar-delete-btn:hover { background: #fecaca; transform: translateY(-1px); }

/* Pagination */
.pagination-wrapper { display: flex; align-items: center; justify-content: center; }
.pagination-wrapper .mui-pagination { display: flex; align-items: center; gap: 0.4rem; list-style: none; padding: 0; margin: 0; }
.pagination-wrapper .mui-pagination .page-link { width: 34px !important; min-width: 34px !important; max-width: 34px !important; height: 34px !important; flex: 0 0 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50% !important; font-weight: 600; color: #18181b; background: transparent; border: 1px solid transparent; transition: all 0.2s; padding: 0 !important; font-size: 0.85rem; line-height: 1; box-sizing: border-box; }
.pagination-wrapper .mui-pagination .page-item.active .page-link { background: #18181b; color: #fff; border-color: #18181b; box-shadow: none; }
.pagination-wrapper .mui-pagination .page-link:hover:not(.active) { background: #f1f5f9; border-color: #e2e8f0; }
.pagination-wrapper .mui-pagination .page-item.disabled .page-link { color: #cbd5e1; cursor: not-allowed; }
.pagination-wrapper .mui-pagination .page-nav-icon { font-size: 1.1rem; font-weight: 700; }
.dense-toggle-wrap { display: flex; align-items: center; gap: 0.5rem; font-weight: 600; color: #334155; }
.table-modern.is-dense td, .table-modern.is-dense th { padding: 0.5rem 1rem !important; }
.absensi-toast {
  position: fixed;
  right: 24px;
  bottom: 24px;
  z-index: 11000;
  min-width: 280px;
  max-width: min(380px, calc(100vw - 32px));
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.9rem 1rem;
  border-radius: 12px;
  background: #18181b;
  color: #ffffff;
  box-shadow: 0 18px 36px rgba(15, 23, 42, 0.18);
  transform: translateY(10px);
  opacity: 0;
  pointer-events: none;
  transition: all 0.25s ease;
}
.absensi-toast.show { transform: translateY(0); opacity: 1; }
.absensi-toast i { color: #86efac; font-size: 1.25rem; }
.absensi-toast span { color: #ffffff; font-size: 0.9rem; font-weight: 700; }
</style>

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    function showAbsensiToast(message) {
        if (!message) return;
        const toast = document.createElement('div');
        toast.className = 'absensi-toast';
        toast.innerHTML = `<i class="ri-check-line"></i><span>${message}</span>`;
        document.body.appendChild(toast);
        toast.offsetHeight;
        setTimeout(() => toast.classList.add('show'), 50);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3200);
    }

    @if(session('success'))
        showAbsensiToast(@json(session('success')));
    @endif

    const pendingAbsensiToast = localStorage.getItem('absensi_toast_success');
    if (pendingAbsensiToast) {
        localStorage.removeItem('absensi_toast_success');
        showAbsensiToast(pendingAbsensiToast);
    }

    const table = $('.table-absensi').DataTable({
        responsive: true,
        searching: true,
        ordering: false,
        paging: false,
        dom: 't' // Only show the table itself, hide default search & pagination
    });

    // Link custom search input to datatable
    $('#customSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // === Detail Modal ===
    $(document).on('click', '.btn-detail', function() {
        const data = $(this).data('item');

        let jsonStr = decodeURIComponent(data.replace(/\+/g, '%20'));
        let a = JSON.parse(jsonStr);

        function getMapLink(lat, lng) {
            if (!lat || !lng) return '-';
            return `<a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" class="btn btn-sm btn-outline-dark" style="padding:0.25rem 0.5rem;font-size:0.75rem;"><i class="ri-map-pin-line me-1"></i> Buka Google Maps</a>`;
        }

        function getImgHtml(path) {
            if (!path || path === '-') return '-';
            let fullPath = path.startsWith('http') ? path : '/storage/' + path;
            return `<div class="absensi-img-wrapper mt-1"><img src="${fullPath}" class="absensi-img" alt="Foto Absen" onerror="this.outerHTML='<span class=\\'text-muted\\'>Gambar tidak ditemukan</span>'"></div>`;
        }

        function formatTime(str) {
            if (!str || str === '-') return '-';
            try {
                let d = new Date(str);
                if (!isNaN(d.getTime())) {
                    let h = d.getHours().toString().padStart(2, '0');
                    let m = d.getMinutes().toString().padStart(2, '0');
                    return `${h}:${m}`;
                }
            } catch(e) {}
            return str;
        }

        function formatDate(str) {
            if (!str || str === '-') return '-';
            try {
                let d = new Date(str);
                if (!isNaN(d.getTime())) {
                    let day = d.getDate().toString().padStart(2, '0');
                    let month = (d.getMonth() + 1).toString().padStart(2, '0');
                    let year = d.getFullYear();
                    return `${day}/${month}/${year}`;
                }
            } catch(e) {}
            return str;
        }

        function calculateDuration(startStr, endStr) {
            if (!startStr || startStr === '-' || !endStr || endStr === '-') return '0 menit';
            try {
                let d1 = new Date(startStr);
                let d2 = new Date(endStr);
                if (!isNaN(d1.getTime()) && !isNaN(d2.getTime())) {
                    let diffMs = d2 - d1;
                    if (diffMs <= 0) return '0 menit';

                    let diffMins = Math.floor(diffMs / 60000);
                    let h = Math.floor(diffMins / 60);
                    let m = diffMins % 60;

                    if (h > 0 && m > 0) return `${h} jam ${m} menit`;
                    if (h > 0) return `${h} jam`;
                    return `${m} menit`;
                }
            } catch(e) {}
            return '0 menit';
        }



        let html = `
            <div class="row g-3">
                <div class="col-12 mb-2 pb-2 border-bottom">
                    <div style="font-size:1.1rem; font-weight:700; color:#18181b;">
                        <i class="ri-user-3-fill me-2 text-primary"></i>${a.user?.name ?? '-'}
                    </div>
                    <div class="text-muted mt-1" style="font-size:0.85rem;">
                        <i class="ri-calendar-event-line me-1"></i>${formatDate(a.date)}
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-bold mb-3"><i class="ri-login-circle-line me-1 text-success"></i> Absen Masuk</h6>
                    <p class="mb-1"><strong>Jam Masuk:</strong> <span class="data-badge-light">${formatTime(a.time_in)}</span></p>
                    <p class="mb-1"><strong>Lokasi:</strong> ${getMapLink(a.lat_in, a.lng_in)}</p>
                    <p class="mb-3"><strong>Foto:</strong><br>${getImgHtml(a.photo_in)}</p>

                    <h6 class="fw-bold mb-3 border-top pt-3"><i class="ri-moon-line me-1 text-warning"></i> Lembur Masuk</h6>
                    <p class="mb-1"><strong>Jam:</strong> <span class="data-badge-light">${formatTime(a.lembur_in)}</span></p>
                    <p class="mb-1"><strong>Lokasi:</strong> ${getMapLink(a.lat_lembur_in, a.lng_lembur_in)}</p>
                    <p class="mb-3"><strong>Foto:</strong><br>${getImgHtml(a.photo_lembur_in)}</p>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-bold mb-3"><i class="ri-logout-circle-line me-1 text-danger"></i> Absen Pulang</h6>
                    <p class="mb-1"><strong>Jam Pulang:</strong> <span class="data-badge-light">${formatTime(a.time_out)}</span></p>
                    <p class="mb-1"><strong>Lokasi:</strong> ${getMapLink(a.lat_out, a.lng_out)}</p>
                    <p class="mb-3"><strong>Foto:</strong><br>${getImgHtml(a.photo_out)}</p>

                    <h6 class="fw-bold mb-3 border-top pt-3"><i class="ri-moon-clear-line me-1 text-warning"></i> Lembur Pulang</h6>
                    <p class="mb-1"><strong>Jam:</strong> <span class="data-badge-light">${formatTime(a.lembur_out)}</span></p>
                    <p class="mb-1"><strong>Lokasi:</strong> ${getMapLink(a.lat_lembur_out, a.lng_lembur_out)}</p>
                    <p class="mb-3"><strong>Foto:</strong><br>${getImgHtml(a.photo_lembur_out)}</p>
                </div>

                <div class="col-12 mt-3 pt-3 border-top">
                    <p class="mb-1"><strong><i class="ri-time-line me-1"></i>Total Jam Kerja:</strong> <span class="data-badge">${calculateDuration(a.time_in, a.time_out)}</span></p>
                    <p class="mb-1"><strong><i class="ri-history-line me-1"></i>Total Lembur:</strong> <span class="data-badge">${calculateDuration(a.lembur_in, a.lembur_out)}</span></p>
                    <p class="mb-0 mt-2"><strong><i class="ri-sticky-note-line me-1"></i>Catatan:</strong><br><span class="text-muted">${a.note ?? '-'}</span></p>
                </div>
            </div>
        `;

        $('#modalDetailBody').html(html);
        $('#modalDetail').modal('show');
    });

    // === Delete Handler ===
    $(document).on('click', '.btn-delete-absensi', function() {
        const id   = $(this).data('id');
        const name = $(this).data('name');
        const date = $(this).data('date');
        const baseUrl = '{{ url("/absensi") }}';

        Swal.fire({
            title: 'Hapus Absensi?',
            html: `Anda akan menghapus data absensi <strong>${name}</strong> pada tanggal <strong>${date}</strong>.<br><br>Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                popup: 'rounded-2xl shadow-xl bg-white border-0',
                backdrop: 'backdrop-blur-sm bg-black/40',
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl px-5 py-2.5 mx-2 shadow-sm transition-all duration-200 border-0',
                cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl px-5 py-2.5 mx-2 transition-all duration-200 border-0'
            },
            showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
            hideClass: { popup: 'animate__animated animate__fadeOutDown animate__faster' },
            confirmButtonText: '<i class="ri-delete-bin-line me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formDeleteAbsensi').attr('action', `${baseUrl}/${id}`);
                $('#formDeleteAbsensi').submit();
            }
        });
    });

    // === Bulk Delete ===
    function updateSelectionState() {
        const $all = $('.row-checkbox');
        const $checked = $('.row-checkbox:checked');
        const count = $checked.length;
        $('#selectAllAbsensi').prop('checked', $all.length > 0 && count === $all.length);
        $('#selectedCount').text(count + ' dipilih');
        $('#selectionToolbar').toggleClass('active', count > 0);
    }

    $('#selectAllAbsensi').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        updateSelectionState();
    });

    $(document).on('change', '.row-checkbox', updateSelectionState);

    $('#btnBulkDelete').on('click', async function() {
        const checked = $('.row-checkbox:checked').toArray();
        if (!checked.length) return;

        const result = await Swal.fire({
            title: 'Konfirmasi Penghapusan',
            text: `Hapus ${checked.length} data absensi terpilih?`,
            icon: 'warning',
            showCancelButton: true,
            customClass: {
                popup: 'rounded-2xl shadow-xl bg-white border-0',
                backdrop: 'backdrop-blur-sm bg-black/40',
                confirmButton: 'bg-red-500 hover:bg-red-600 text-white font-medium rounded-xl px-5 py-2.5 mx-2 shadow-sm transition-all duration-200 border-0',
                cancelButton: 'bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl px-5 py-2.5 mx-2 transition-all duration-200 border-0'
            },
            showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
            hideClass: { popup: 'animate__animated animate__fadeOutDown animate__faster' },
            confirmButtonText: 'Hapus Semua',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            buttonsStyling: false
        });

        if (!result.isConfirmed) return;

        for (const cb of checked) {
            const id = $(cb).val();
            try {
                await fetch(`{{ url('/absensi') }}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                });
            } catch (e) {}
        }
        localStorage.setItem('absensi_toast_success', `${checked.length} data absensi berhasil dihapus.`);
        location.reload();
    });

    const denseToggle = document.getElementById('densePaddingToggle');
    const tableEl = document.querySelector('.table-modern');
    if (denseToggle && tableEl) {
        const savedDense = localStorage.getItem('absensi_dense_padding') === '1';
        denseToggle.checked = savedDense;
        tableEl.classList.toggle('is-dense', savedDense);

        denseToggle.addEventListener('change', function() {
            const isDense = denseToggle.checked;
            tableEl.classList.toggle('is-dense', isDense);
            localStorage.setItem('absensi_dense_padding', isDense ? '1' : '0');
        });
    }
});
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-header-custom">
        <div>
            <h4><i class="ri-calendar-check-line"></i> Data Absensi</h4>
            <p>Kelola dan monitor data absensi karyawan</p>
        </div>
        <div class="search-wrapper">
            <i class="ri-search-line search-icon"></i>
            <input type="text" id="customSearch" class="form-control" placeholder="Cari data absensi...">
        </div>
    </div>

    <div class="table-responsive">
        <div class="selection-toolbar rounded-3 m-3 mb-0" id="selectionToolbar">
            <span class="selected-text" id="selectedCount">0 dipilih</span>
            <button type="button" class="toolbar-delete-btn" id="btnBulkDelete" title="Hapus Terpilih">
                <i class="ri-delete-bin-line fs-5"></i>
            </button>
        </div>
        <table class="table table-modern datatables-basic table-absensi">
            <thead>
                <tr>
                    <th style="width:50px; text-align:center;"><input type="checkbox" class="custom-check" id="selectAllAbsensi"></th>
                    <th>NAMA KARYAWAN</th>
                    <th>JAM MASUK</th>
                    <th>JAM PULANG</th>
                    <th>LEMBUR MASUK</th>
                    <th>LEMBUR PULANG</th>
                    <th style="text-align:center;">DETAIL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $i => $a)
                <tr>
                    <td style="text-align:center;"><input type="checkbox" class="custom-check row-checkbox" value="{{ $a->id }}"></td>
                    <td>
                        <div class="data-badge-light border-0 px-0 bg-transparent">
                            <i class="ri-user-line" style="background:#18181b; color:#fff; padding:6px; border-radius:6px;"></i>
                            <div style="color:#18181b; font-weight:700;">{{ $a->user->name ?? '-' }}</div>
                        </div>
                    </td>
                    <td>
                        @if($a->time_in)
                            <span class="data-badge">{{ \Carbon\Carbon::parse($a->time_in)->format('H:i') }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($a->time_out)
                            <span class="data-badge">{{ \Carbon\Carbon::parse($a->time_out)->format('H:i') }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($a->lembur_in)
                            <span class="data-badge">{{ \Carbon\Carbon::parse($a->lembur_in)->format('H:i') }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($a->lembur_out)
                            <span class="data-badge">{{ \Carbon\Carbon::parse($a->lembur_out)->format('H:i') }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <button
                            class="btn btn-outline-dark btn-sm btn-detail btn-icon"
                            title="Lihat Detail"
                            style="width:34px;height:34px;padding:0;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;"
                            data-item="{{ urlencode(json_encode($a)) }}">
                            <i class="ri-eye-line"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top: 1px solid #f1f5f9; background: #fafafa; border-radius: 0 0 var(--border-radius) var(--border-radius);">
      <div class="d-flex align-items-center gap-4">
        <label class="dense-toggle-wrap mb-0">
          <input type="checkbox" id="densePaddingToggle">
          <span class="small" style="font-weight:600; color:#334155;">Dense padding</span>
        </label>
      </div>
      <div class="pagination-wrapper border-top-0 p-0 bg-transparent m-0">
        @if($absensi instanceof \Illuminate\Pagination\LengthAwarePaginator && $absensi->hasPages())
          {{ $absensi->appends(request()->query())->onEachSide(1)->links('pagination.mui') }}
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

{{-- Form Delete Hidden --}}
<form id="formDeleteAbsensi" method="POST" action="" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Absensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetailBody"></div>
        </div>
    </div>
</div>
@endsection
