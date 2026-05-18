@extends('layouts/layoutMaster')

@section('title', 'Data Absensi')

@section('vendor-style')
@vite([
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
  background: #fff !important;
  border-bottom: 1px solid var(--gray-border);
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
}
.card-header-custom h4 { font-size:1.4rem; font-weight:700; color:#18181b; margin:0; display:flex; align-items:center; gap:0.5rem; }
.card-header-custom p  { color:#71717a; font-size:0.875rem; margin:0; }

/* Table */
.table-modern { margin-bottom: 0; width: 100% !important; }
.table-modern thead th { background: #f8fafc; font-weight: 600; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.5px; color: #18181b; padding: 1rem; border: none; white-space: nowrap; }
.table-modern tbody tr { transition: var(--transition); border-bottom: 1px solid var(--gray-border); }
.table-modern tbody tr:hover { background-color: #f4f4f5 !important; }
.table-modern tbody td { padding: 0.85rem 1rem; vertical-align: middle; border-bottom: 1px solid var(--gray-border); color: #18181b; font-size: 0.9rem; }

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
</style>

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function () {

    const table = $('.table-absensi').DataTable({
        responsive: true,
        searching: true,
        ordering: true,
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
            confirmButtonColor: '#18181b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="ri-delete-bin-line me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formDeleteAbsensi').attr('action', `${baseUrl}/${id}`);
                $('#formDeleteAbsensi').submit();
            }
        });
    });

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
        <table class="table table-modern datatables-basic table-absensi">
            <thead>
                <tr>
                    <th style="width:50px; text-align:center;"># NO</th>
                    <th><i class="ri-user-3-line me-1"></i> NAMA KARYAWAN</th>
                    <th><i class="ri-login-circle-line me-1"></i> JAM MASUK</th>
                    <th><i class="ri-logout-circle-line me-1"></i> JAM PULANG</th>
                    <th><i class="ri-moon-line me-1"></i> LEMBUR MASUK</th>
                    <th><i class="ri-moon-clear-line me-1"></i> LEMBUR PULANG</th>
                    <th style="text-align:center;"><i class="ri-settings-3-line me-1"></i> AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensi as $i => $a)
                <tr>
                    <td style="text-align:center; font-weight:600; color:#71717a;">{{ $i+1 }}</td>
                    <td>
                        <div class="data-badge-light border-0 px-0 bg-transparent">
                            <i class="ri-user-line" style="background:#18181b; color:#fff; padding:6px; border-radius:6px;"></i>
                            <div>
                                <div style="color:#18181b; font-weight:700;">{{ $a->user->name ?? '-' }}</div>
                                <div style="color:#71717a; font-size:0.75rem; font-weight:500;">
                                    <i class="ri-calendar-event-line"></i> {{ \Carbon\Carbon::parse($a->date)->translatedFormat('d M Y') }}
                                </div>
                            </div>
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
                            class="btn btn-outline-dark btn-sm btn-detail btn-icon me-1"
                            title="Lihat Detail"
                            data-item="{{ urlencode(json_encode($a)) }}">
                            <i class="ri-eye-line"></i>
                        </button>
                        <button
                            class="btn btn-sm btn-icon btn-delete-absensi"
                            title="Hapus Absensi"
                            style="width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;border:1px solid #ef4444;color:#ef4444;border-radius:8px;background:transparent;"
                            data-id="{{ $a->id }}"
                            data-name="{{ $a->user->name ?? '-' }}"
                            data-date="{{ \Carbon\Carbon::parse($a->date)->translatedFormat('d M Y') }}">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
