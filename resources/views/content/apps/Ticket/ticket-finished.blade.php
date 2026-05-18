@extends('layouts/layoutMaster')

@section('title', 'Daftar Ticket Progres')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
])
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
}
.card-header-custom h4 { font-size:1.4rem; font-weight:700; color:#18181b; margin:0; }
.card-header-custom p  { color:#71717a; font-size:0.875rem; margin:0; }

/* Buttons */
.btn { border-radius: 8px !important; font-weight: 500 !important; font-size: 0.875rem !important; display: inline-flex !important; align-items: center !important; gap: 0.4rem !important; }
.btn-primary, .btn.btn-primary { background: #18181b !important; color: #fafafa !important; border: 1px solid #18181b !important; box-shadow: 0 4px 12px rgba(24,24,27,0.25) !important; }
.btn-primary:hover { background: #27272a !important; }
.btn-primary i { color: #fff !important; }
.btn-add { padding: 0.65rem 1.25rem !important; }
.btn-warning, .btn.btn-warning { background: #18181b !important; color: #fafafa !important; border: 1px solid #18181b !important; }
.btn-warning i { color: #fff !important; }
.btn-danger, .btn.btn-danger { background: #18181b !important; color: #fafafa !important; border: 1px solid #18181b !important; }
.btn-danger i { color: #fff !important; }
.btn-secondary, .btn.btn-secondary { background: #18181b !important; color: #fafafa !important; border: 1px solid #18181b !important; }
.btn-secondary i { color: #fff !important; }
.btn-outline-secondary, .btn.btn-outline-secondary { background: transparent !important; border: 1px solid var(--gray-border) !important; color: #18181b !important; }
.btn-outline-secondary:hover { background: #18181b !important; color: #fff !important; border-color: #18181b !important; }
.btn-icon { width: 32px !important; height: 32px !important; padding: 0 !important; }

/* Table */
.table-modern { margin-bottom: 0; }
.table-modern thead th { background: #f8fafc; font-weight: 600; text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.5px; color: #18181b; padding: 1rem; border: none; white-space: nowrap; }
.table-modern tbody tr { transition: var(--transition); border-bottom: 1px solid var(--gray-border); }
.table-modern tbody tr:hover { background-color: #f4f4f5 !important; }
.table-modern tbody td { padding: 0.85rem 1rem; vertical-align: middle; border-bottom: 1px solid var(--gray-border); color: #18181b; }

/* Badge */
.badge { border-radius: 9999px !important; font-weight: 500 !important; padding: 0.35rem 0.75rem !important; }

/* Pagination */
.pagination-wrapper { display: flex; justify-content: space-between; align-items: center; padding: 1.25rem 1.5rem; border-top: 1px solid #f0f0f0; background: #fafafa; border-radius: 0 0 var(--border-radius) var(--border-radius); }
.pagination-info { color: #71717a; font-size: 0.875rem; font-weight: 500; }

/* Override Bootstrap 5 pagination - circular black/white */
nav[aria-label] ul.pagination,
.pagination {
  margin: 0 !important;
  gap: 0.25rem !important;
  display: flex !important;
  align-items: center !important;
  list-style: none !important;
  padding: 0 !important;
}
nav[aria-label] ul.pagination li,
.pagination .page-item {
  margin: 0 2px !important;
}
nav[aria-label] ul.pagination li a,
nav[aria-label] ul.pagination li span,
.pagination .page-item .page-link {
  border-radius: 50% !important;
  width: 40px !important;
  height: 40px !important;
  padding: 0 !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  border: 1px solid #e4e4e7 !important;
  color: #18181b !important;
  font-weight: 600 !important;
  font-size: 0.875rem !important;
  background-color: #fff !important;
  transition: all 0.2s ease !important;
  text-decoration: none !important;
  line-height: 1 !important;
}
nav[aria-label] ul.pagination li a:hover,
.pagination .page-item .page-link:hover {
  background-color: #f4f4f5 !important;
  border-color: #18181b !important;
  color: #18181b !important;
}
nav[aria-label] ul.pagination li.active span,
nav[aria-label] ul.pagination li.active a,
.pagination .page-item.active .page-link {
  background-color: #18181b !important;
  border-color: #18181b !important;
  color: #fafafa !important;
}
nav[aria-label] ul.pagination li.disabled span,
nav[aria-label] ul.pagination li.disabled a,
.pagination .page-item.disabled .page-link {
  background-color: #f4f4f5 !important;
  border-color: #e4e4e7 !important;
  color: #a1a1aa !important;
  cursor: not-allowed !important;
  pointer-events: none !important;
}
/* Hide the "..." ellipsis page-link border */
.pagination .page-item:not(.active) span.page-link:not([aria-label]) {
  border: 1px solid #e4e4e7 !important;
  background: #fff !important;
  color: #71717a !important;
}

/* Modal */
.modal-backdrop { backdrop-filter: blur(8px); background-color: rgba(24,24,27,0.4) !important; }
.modal-backdrop.show { opacity: 1 !important; }
.modal-content { border-radius: 12px; border: 1px solid var(--gray-border); box-shadow: 0 10px 40px rgba(0,0,0,0.15); overflow: hidden; }
.modal-header { background: #18181b !important; padding: 1.25rem 1.5rem; border-bottom: none; }
.modal-title { font-weight: 600; font-size: 1.1rem; color: #fafafa !important; margin: 0; }
.modal-header .btn-close { filter: invert(1); opacity: 0.8; }
.modal-body { padding: 1.5rem; max-height: 72vh; overflow-y: auto; }
.modal-footer { padding: 1rem 1.5rem; border-top: 1px solid var(--gray-border); background: #fafafa; }

/* Detail sections in modal */
.detail-section { background: white; border: 1px solid var(--gray-border); border-radius: 10px; padding: 1.25rem; margin-bottom: 1rem; }
.detail-section:hover { border-color: #18181b; box-shadow: 0 2px 8px rgba(24,24,27,0.1); }
.detail-section h6 { color: #18181b !important; font-weight: 700; margin-bottom: 0.9rem; font-size: 0.78rem; text-transform: uppercase; padding-bottom: 0.65rem; border-bottom: 2px solid #18181b; display: flex; align-items: center; gap: 0.4rem; }
.detail-item { display: flex; padding: 0.6rem 0; border-bottom: 1px solid #f0f0f0; }
.detail-item:last-child { border-bottom: none; padding-bottom: 0; }
.detail-label { color: #71717a; font-weight: 600; min-width: 130px; font-size: 0.83rem; display: flex; align-items: flex-start; gap: 0.35rem; padding-top: 1px; }
.detail-value { color: #18181b; font-size: 0.875rem; flex: 1; word-break: break-word; }
.ticket-header-info { text-align: center; padding: 1.1rem; background: #fafafa; border-radius: 10px; margin-bottom: 1rem; border: 1px solid var(--gray-border); }
.ticket-name { font-size: 1.2rem; font-weight: 700; color: #18181b; margin-bottom: 0.4rem; }

/* Search */
.search-wrapper { position: relative; min-width: 260px; }
.search-wrapper .search-icon { position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%); color: #71717a; pointer-events: none; font-size: 1rem; }
.search-wrapper input { padding-left: 2.25rem !important; border: 1px solid var(--gray-border) !important; border-radius: 8px !important; font-size: 0.875rem !important; height: 40px !important; background: #fafafa !important; }
.search-wrapper input:focus { border-color: #18181b !important; background: #fff !important; box-shadow: none !important; }
.ticket-toast {
  position: fixed;
  top: 24px;
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
  opacity: 0;
  transform: translateY(-8px);
  transition: all 0.25s ease;
}
.ticket-toast.show { opacity: 1; transform: translateY(0); }
.ticket-toast i { color: #8ee05f; font-size: 1.25rem; }

@keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
.card { animation: fadeIn 0.3s ease-out; }
</style>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
])
@endsection

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function() {

    // ============================================================
    // TOMBOL DETAIL → buka modal dengan data dari data-* attribute
    // ============================================================
    $(document).on('click', '.btn-detail', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const tr = $(this).closest('tr');

        const nama      = tr.attr('data-nama')      || '-';
        const problem   = tr.attr('data-problem')   || '-';
        const kategori  = tr.attr('data-kategori')  || '-';
        const prioritas = tr.attr('data-prioritas') || '-';
        const status    = tr.attr('data-status')    || '-';
        const teknisi   = tr.attr('data-teknisi')   || '-';
        const tanggal   = tr.attr('data-tanggal')   || '-';
        const catatan   = tr.attr('data-catatan')   || '-';
        const dibuat    = tr.attr('data-dibuat')    || '-';
        const phone     = tr.attr('data-phone')     || '';
        const lokasi    = tr.attr('data-lokasi')    || '';
        const photo     = tr.attr('data-photo')     || '';

        // Status badge style
        const statusColors = {
            pending:  { bg: '#d97706', text: '#fff' },
            assigned: { bg: '#0ea5e9', text: '#fff' },
            progress: { bg: '#2563eb', text: '#fff' },
            finished: { bg: '#16a34a', text: '#fff' },
            approved: { bg: '#16a34a', text: '#fff' },
            rejected: { bg: '#dc2626', text: '#fff' },
        };
        const sc = statusColors[status] || { bg: '#6b7280', text: '#fff' };

        const phoneHtml = phone
            ? `<a href="https://wa.me/${phone.replace(/[^0-9]/g,'')}" target="_blank" style="color:#16a34a;"><i class="ri-whatsapp-line me-1"></i>${phone}</a>`
            : '-';

        const lokasiHtml = lokasi
            ? `<a href="${lokasi}" target="_blank" style="color:#2563eb;"><i class="ri-map-pin-line me-1"></i>Lihat di Google Maps</a>`
            : '-';
        const photoHtml = photo
            ? `<a href="${photo}" target="_blank" title="Buka gambar penuh di tab baru" style="display:inline-block;text-decoration:none;">
                 <img src="${photo}" style="width:110px;height:150px;object-fit:cover;border-radius:10px;border:1px solid #e4e4e7;display:block;" alt="Foto Progress Kerja">
                 <small style="display:block;margin-top:6px;color:#2563eb;font-weight:600;">Klik untuk buka penuh</small>
               </a>`
            : '<span class="text-muted">Belum ada foto progress.</span>';

        const html = `
        <div class="ticket-header-info">
            <div style="font-size:2.2rem; margin-bottom:0.5rem;">
                <i class="ri-ticket-2-line" style="color:#18181b;"></i>
            </div>
            <div class="ticket-name">${nama}</div>
            <span style="display:inline-block; margin-top:0.3rem; padding:0.3rem 0.9rem; background:${sc.bg}; color:${sc.text}; border-radius:20px; font-size:0.8rem; font-weight:600;">
                ${status.charAt(0).toUpperCase() + status.slice(1)}
            </span>
        </div>

        <div class="detail-section">
            <h6><i class="ri-file-list-3-line"></i> Informasi Tiket</h6>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-user-3-line"></i> Pelanggan</span>
                <span class="detail-value"><strong>${nama}</strong></span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-bug-line"></i> Problem</span>
                <span class="detail-value">${problem}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-bookmark-line"></i> Kategori</span>
                <span class="detail-value">${kategori}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-alarm-warning-line"></i> Prioritas</span>
                <span class="detail-value">${prioritas}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-map-pin-2-line"></i> Lokasi</span>
                <span class="detail-value">${lokasiHtml}</span>
            </div>
        </div>

        <div class="detail-section">
            <h6><i class="ri-team-line"></i> Penanganan</h6>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-tools-line"></i> Teknisi</span>
                <span class="detail-value">${teknisi}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-smartphone-line"></i> No WA</span>
                <span class="detail-value">${phoneHtml}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-sticky-note-line"></i> Catatan</span>
                <span class="detail-value">${catatan}</span>
            </div>
            <div class="detail-item" style="display:block;">
                <span class="detail-label d-block mb-1"><i class="ri-image-line"></i> Foto Progress Kerja</span>
                <span class="detail-value d-block">${photoHtml}</span>
            </div>
        </div>

        <div class="detail-section">
            <h6><i class="ri-time-line"></i> Waktu & Pembuat</h6>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-calendar-line"></i> Tanggal</span>
                <span class="detail-value">${tanggal}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label"><i class="ri-user-3-line"></i> Dibuat Oleh</span>
                <span class="detail-value">${dibuat}</span>
            </div>
        </div>
        `;

        document.querySelector('#detailModal .modal-body').innerHTML = html;
        const modalEl = document.getElementById('detailModal');
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    });

    // ============================================================
    // KONFIRMASI HAPUS
    // ============================================================
    function refreshTicketRowNumbers() {
        const table = document.querySelector('.datatables-tickets');
        const startNumber = parseInt(table?.dataset.pageStart || '1', 10);
        const rows = document.querySelectorAll('.datatables-tickets tbody tr');
        rows.forEach((row, index) => {
            const numberCell = row.querySelector('td:first-child');
            if (numberCell) numberCell.textContent = startNumber + index;
        });

        if (table) {
            const total = Math.max(parseInt(table.dataset.total || '0', 10) - 1, 0);
            table.dataset.total = total;
            const firstEl = document.querySelector('[data-pagination-first]');
            const lastEl = document.querySelector('[data-pagination-last]');
            const totalEl = document.querySelector('[data-pagination-total]');
            if (firstEl) firstEl.textContent = rows.length ? startNumber : 0;
            if (lastEl) lastEl.textContent = rows.length ? startNumber + rows.length - 1 : 0;
            if (totalEl) totalEl.textContent = total;
        }
    }

    function removeTicketRow(row) {
        row.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
        row.style.opacity = '0';
        row.style.transform = 'translateX(-12px)';
        setTimeout(() => {
            row.remove();
            refreshTicketRowNumbers();
        }, 260);
    }

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const row = this.closest('tr');
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data ticket akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: false,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak',
            customClass: {
                confirmButton: 'btn btn-dark me-2 px-4',
                cancelButton: 'btn btn-outline-secondary px-4',
                denyButton: 'd-none'
            },
            buttonsStyling: false
        }).then((result) => {
            if (!result.isConfirmed) return;

            fetch(form.attr('action'), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(form[0]),
            })
            .then(response => {
                if (!response.ok) throw new Error('delete_failed');
                return response.json();
            })
            .then(data => {
                if (!data.success) throw new Error('delete_failed');
                removeTicketRow(row);
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Ticket gagal dihapus. Silakan coba lagi.',
                    showCancelButton: false,
                    showDenyButton: false,
                    confirmButtonText: 'Tutup',
                    customClass: {
                        confirmButton: 'btn btn-dark px-4',
                        denyButton: 'd-none',
                        cancelButton: 'd-none'
                    },
                    buttonsStyling: false
                });
            });
        });
    });

    // ============================================================
    // REAL-TIME POLLING: MENDETEKSI TIKET SELESAI BARU
    // ============================================================
    var adminPollUrl = window.location.protocol + '//' + window.location.host + '/dashboard/cs/tickets/poll-status';
    var maxKnownTs = 0;

    // Kumpulkan timestamp dari tabel saat ini untuk inisialisasi
    document.querySelectorAll('.datatables-tickets tbody tr').forEach(function(tr) {
        var id = tr.getAttribute('id');
        if (id) {
            var ts = parseInt(tr.getAttribute('data-ts') || '0', 10);
            if (ts > maxKnownTs) maxKnownTs = ts;
        }
    });

    function pollFinishedTickets() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', adminPollUrl, true);
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.withCredentials = true;
      xhr.timeout = 10000;

      xhr.onload = function() {
        if (xhr.status === 200) {
          try {
            var tickets = JSON.parse(xhr.responseText);
            var hasNewFinishedTicket = false;

            tickets.forEach(function(ticket) {
              if (['finished', 'approved', 'rejected'].includes(ticket.status)) {
                  var row = document.getElementById('ticket-row-' + ticket.id);
                  if (!row && ticket.updated_at > maxKnownTs) {
                      hasNewFinishedTicket = true;
                      maxKnownTs = ticket.updated_at;
                  }
                  
                  // Update data attributes jika barisnya sudah ada
                  if (row && ticket.updated_at > maxKnownTs) {
                      row.setAttribute('data-status', ticket.status);
                      row.setAttribute('data-catatan', ticket.technician_note || '-');
                      row.setAttribute('data-teknisi', ticket.teknisi || '-');
                      if (ticket.photo) {
                          row.setAttribute('data-photo', ticket.photo);
                      }
                      maxKnownTs = ticket.updated_at;
                  }
              }
            });

            // Jika ada tiket baru yang selesai, update tabel di background (tanpa reload)
            if (hasNewFinishedTicket) {
                fetch(window.location.href)
                  .then(response => response.text())
                  .then(html => {
                      var parser = new DOMParser();
                      var doc = parser.parseFromString(html, 'text/html');
                      var newTbody = doc.querySelector('.datatables-tickets tbody');
                      var newPagination = doc.querySelector('.pagination-wrapper');
                      
                      if (newTbody) {
                          document.querySelector('.datatables-tickets tbody').innerHTML = newTbody.innerHTML;
                          
                          // Re-init ID & TS untuk polling selanjutnya
                          document.querySelectorAll('.datatables-tickets tbody tr').forEach(function(tr) {
                              var ts = parseInt(tr.getAttribute('data-ts') || '0', 10);
                              if (ts > maxKnownTs) maxKnownTs = ts;
                          });
                      }
                      if (newPagination) {
                          document.querySelector('.pagination-wrapper').innerHTML = newPagination.innerHTML;
                      }
                      console.log('[Admin Poll] Tabel di-update dengan tiket selesai terbaru!');
                  })
                  .catch(err => console.error('[Admin Poll] Gagal fetch halaman:', err));
            }

          } catch(e) {
            console.error('[Admin Poll] JSON parse error:', e.message);
          }
        }
      };
      
      xhr.onerror = function() {};
      xhr.send();
    }

    // Jalankan polling setiap 8 detik
    setInterval(pollFinishedTickets, 8000);

});
</script>
@endsection


@section('content')
@php
  $pageTitle = $pageTitle ?? 'Daftar Ticket Progres';
  $pageDescription = $pageDescription ?? 'Kelola dan monitor tiket yang sudah selesai dikerjakan.';
  $searchRoute = $searchRoute ?? route('finished');
@endphp
<div class="card">

  {{-- Header --}}
  <div class="card-header-custom d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
      <h4><i class="ri-ticket-2-line me-2"></i>{{ $pageTitle }}</h4>
      <p>{{ $pageDescription }}</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <form action="{{ $searchRoute }}" method="GET" class="mb-0">
        <div class="search-wrapper">
          <i class="ri-search-line search-icon"></i>
          <input type="text" name="search" value="{{ request('search') }}"
            class="form-control" placeholder="Cari nama, problem, kategori...">
        </div>
      </form>
    </div>
  </div>

  {{-- Session success toast --}}
  @if(session('success'))
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const toast = document.createElement('div');
    toast.className = 'ticket-toast';
    toast.innerHTML = '<i class="ri-check-line"></i><span>{{ addslashes(session("success")) }}</span>';
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 50);
    setTimeout(() => {
      toast.classList.remove('show');
      setTimeout(() => toast.remove(), 250);
    }, 3000);
  });
  </script>
  @endif

  {{-- Tabel --}}
  <div class="table-responsive">
    <table class="datatables-tickets table table-modern" data-page-start="{{ $tickets->firstItem() ?? 1 }}" data-total="{{ $tickets->total() }}">
      <thead>
        <tr>
          <th style="width:55px; text-align:center;"># No</th>
          <th style="width:55px; text-align:center;"><i class="ri-eye-line"></i></th>
          <th><i class="ri-user-3-line me-1"></i> Nama Pelanggan</th>
          <th><i class="ri-map-pin-line me-1"></i> Alamat</th>
          <th><i class="ri-bug-line me-1"></i> Problem</th>
          <th style="text-align:center;"><i class="ri-whatsapp-line me-1"></i> WA</th>
          <th><i class="ri-bookmark-line me-1"></i> Kategori</th>
          <th><i class="ri-alarm-warning-line me-1"></i> Prioritas</th>
          <th><i class="ri-checkbox-circle-line me-1"></i> Status</th>
          <th style="text-align:center;"><i class="ri-settings-3-line me-1"></i> Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($tickets as $index => $ticket)
        <tr
          id="ticket-row-{{ $ticket->id }}"
          data-ts="{{ $ticket->updated_at->timestamp }}"
          data-nama="{{ optional($ticket->pelanggan)->nama_lengkap ?? $ticket->title ?? '-' }}"
          data-problem="{{ $ticket->issue_description }}"
          data-kategori="{{ $ticket->category }}"
          data-prioritas="{{ ucfirst($ticket->priority) }}"
          data-status="{{ $ticket->status }}"
          data-teknisi="{{ $ticket->user->name ?? '-' }}"
          data-tanggal="{{ $ticket->created_at->format('d M Y') }}"
          data-catatan="{{ $ticket->technician_note ?? '-' }}"
          data-dibuat="{{ $ticket->creator->name ?? '-' }}"
          data-phone="{{ $ticket->phone }}"
          data-lokasi="{{ $ticket->location_link }}"
          data-photo="{{ $ticket->technician_attachment ? asset('storage/' . $ticket->technician_attachment) : '' }}"
        >
          <td style="text-align:center; color:#71717a; font-weight:600;">
            {{ $tickets->firstItem() + $index }}
          </td>
          <td style="text-align:center;">
            <button class="btn btn-icon btn-outline-secondary btn-sm btn-detail" title="Lihat Detail">
              <i class="ri-eye-line"></i>
            </button>
          </td>
          <td style="font-weight:600;">
            @if($ticket->ticket_type == 'internal')
                <span class="badge bg-secondary mb-1" style="font-size:0.7rem;"><i class="ri-tools-line"></i> Internal</span><br>
                {{ $ticket->title }}
            @else
                {{ optional($ticket->pelanggan)->nama_lengkap ?? '-' }}
            @endif
          </td>
          <td style="max-width:200px; white-space:normal; font-size:0.85rem; line-height:1.4;">
            {{ optional($ticket->pelanggan)->alamat_jalan ?? '-' }}
            @if($ticket->location_link)
              <div class="mt-1">
                <a href="{{ $ticket->location_link }}" target="_blank" style="color:#2563eb; font-weight:600; font-size:0.8rem;">
                  <i class="ri-map-pin-line"></i> Google Maps
                </a>
              </div>
            @endif
          </td>
          <td style="max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $ticket->issue_description }}">
            {{ $ticket->issue_description }}
          </td>
          <td style="text-align:center;">
            @if($ticket->phone)
              <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ticket->phone) }}" target="_blank" class="text-success fs-5">
                <i class="ri-whatsapp-line"></i>
              </a>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>
          <td>{{ $ticket->category ?? '-' }}</td>
          <td>
            <span class="badge
              @if($ticket->priority == 'urgent') bg-danger
              @elseif($ticket->priority == 'medium') bg-warning text-dark
              @else bg-secondary
              @endif">
              {{ ucfirst($ticket->priority) }}
            </span>
          </td>
          <td>
            <span class="badge
              @if($ticket->status == 'pending') bg-warning text-dark
              @elseif($ticket->status == 'assigned') bg-info text-dark
              @elseif($ticket->status == 'progress') bg-primary
              @elseif(in_array($ticket->status, ['finished', 'approved'])) bg-success
              @elseif($ticket->status == 'rejected') bg-danger
              @else bg-secondary
              @endif">
              {{ ucfirst($ticket->status) }}
            </span>
          </td>
          <td style="text-align:center;">
            <div class="d-flex align-items-center justify-content-center gap-1">
              <a href="{{ route('tiket.edit', $ticket->id) }}" class="btn btn-icon btn-sm btn-warning" title="Edit">
                <i class="ri-pencil-line"></i>
              </a>
              <form action="{{ route('tickets.destroys', $ticket->id) }}" method="POST" class="m-0 p-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-icon btn-sm btn-danger btn-delete" title="Hapus">
                  <i class="ri-delete-bin-line"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  <div class="pagination-wrapper">
    <div class="pagination-info">
      Menampilkan <strong data-pagination-first>{{ $tickets->firstItem() ?? 0 }}</strong> &ndash; <strong data-pagination-last>{{ $tickets->lastItem() ?? 0 }}</strong>
      dari <strong data-pagination-total>{{ $tickets->total() }}</strong> tiket
    </div>
    <div>
      @if ($tickets->hasPages())
        {{ $tickets->links('pagination::bootstrap-5') }}
      @else
        <nav aria-label="Pagination Navigation">
          <ul class="pagination">
            <li class="page-item disabled" aria-disabled="true">
              <span class="page-link" aria-hidden="true">&lsaquo;</span>
            </li>
            <li class="page-item active" aria-current="page">
              <span class="page-link">1</span>
            </li>
            <li class="page-item disabled" aria-disabled="true">
              <span class="page-link" aria-hidden="true">&rsaquo;</span>
            </li>
          </ul>
        </nav>
      @endif
    </div>
  </div>

</div>

{{-- Detail Modal --}}
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailModalLabel">
          <i class="ri-ticket-2-line me-2"></i>Detail Ticket
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="ri-close-line me-1"></i>Tutup
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
