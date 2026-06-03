@extends('layouts/layoutMaster')

@section('title', 'Data Rekening')

@section('vendor-style')
@vite([
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
  --gray-border: #e4e4e7;
}

.card {
  border: none;
  border-radius: var(--border-radius);
  box-shadow: var(--card-shadow);
  background: white;
  transition: var(--transition);
}
.card:hover { box-shadow: var(--card-hover-shadow); }

.card-header-custom {
  background: #ffffff !important;
  border-bottom: 1px solid var(--gray-border);
  padding: 1.5rem;
  border-radius: var(--border-radius) var(--border-radius) 0 0;
  color: #18181b;
}
.card-header-custom h4 { color: #18181b !important; }
.card-header-custom p { color: #71717a !important; }
.card-header-custom i { color: #18181b !important; }

.btn-primary, .btn.btn-primary, .btn-add {
  background: #18181b !important;
  color: #fafafa !important;
  border: 1px solid #18181b !important;
  padding: 10px 24px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s;
  box-shadow: 0 4px 12px rgba(24,24,27,0.2);
}
.btn-primary:hover, .btn-add:hover {
  background: #27272a !important;
  border-color: #27272a !important;
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(24,24,27,0.3);
}
.btn-add i { margin-right: 8px; }

.card-datatable {
  padding: 1rem !important;
  position: relative;
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

.modern-table tr.row-selected td {
  background: #edf4fd !important;
}

.modern-table.is-dense th { padding: 0.7rem 1rem; }
.modern-table.is-dense td { padding: 0.65rem 1rem; }
.modern-table th.select-column,
.modern-table td.select-column {
  width: 52px;
  min-width: 52px;
  text-align: center;
}

.custom-check {
  appearance: none;
  width: 18px;
  height: 18px;
  border-radius: 4px;
  border: 1px solid #cbd5e1;
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
  border: solid #fff;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.mui-code-chip {
  display: inline-flex;
  align-items: center;
  min-height: 28px;
  padding: 0 0.65rem;
  border-radius: 6px;
  background: #f5f5f5 !important;
  color: #18181b !important;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
  font-size: 0.875rem;
  font-weight: 600;
}

.mui-bank-icon {
  width: 32px;
  min-width: 32px;
  height: 32px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #18181b;
  color: #fff;
}

.action-btn {
  width: 34px;
  height: 34px;
  padding: 0;
  border-radius: 50% !important;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 0 !important;
  background: transparent !important;
  color: #374151 !important;
  box-shadow: none !important;
}

.action-btn:hover {
  background: rgba(17, 24, 39, 0.08) !important;
  color: #18181b !important;
}

.datatables-rekenings thead .sorting::before,
.datatables-rekenings thead .sorting::after,
.datatables-rekenings thead .sorting_asc::before,
.datatables-rekenings thead .sorting_asc::after,
.datatables-rekenings thead .sorting_desc::before,
.datatables-rekenings thead .sorting_desc::after,
.datatables-rekenings thead .sorting_disabled::before,
.datatables-rekenings thead .sorting_disabled::after {
  display: none !important;
  content: none !important;
}

.selection-toolbar {
  display: none;
  align-items: center;
  justify-content: space-between;
  background: #e7f0fb;
  border-bottom: 1px solid #d6e4f3;
  padding: 0.85rem 1.25rem;
  color: #0f172a;
}

.selection-toolbar.active {
  display: flex;
}

.selection-toolbar .selected-text {
  font-size: 1rem;
  font-weight: 700;
}

.clear-btn {
  width: 38px;
  height: 38px;
  padding: 0;
  border: 0;
  border-radius: 50%;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: transparent !important;
  color: #64748b !important;
  font-size: 1.25rem;
}

.clear-btn:hover {
  background: #dbe8f6 !important;
  color: #1e293b !important;
}

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
.spinner-border-custom { width: 3rem; height: 3rem; border-width: 0.3rem; }

.badge.bg-label-primary { background: #18181b !important; color: #fafafa !important; font-weight: 600; }
.badge.bg-label-info { background: #18181b !important; color: #fafafa !important; font-weight: 600; }

.icon-wrapper.bg-label-primary { background: #18181b !important; }
.icon-wrapper.bg-label-primary i { color: #fafafa !important; }

.btn-outline-primary, .btn-outline-danger {
  background: transparent !important;
  border: 1px solid #18181b !important;
  color: #18181b !important;
}
.btn-outline-primary:hover, .btn-outline-danger:hover {
  background: #18181b !important;
  color: #fafafa !important;
}

code {
  background: #18181b !important;
  color: #fafafa !important;
  padding: 4px 12px;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 600;
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

.dense-toggle-wrap {
  display: inline-flex;
  align-items: center;
  gap: 0.55rem;
  color: #374151;
  font-weight: 600;
}

.dense-toggle-wrap input {
  width: 18px;
  height: 18px;
  accent-color: #18181b;
}

.pagination-info {
  color: #71717a;
  font-size: 0.875rem;
  font-weight: 500;
}

.pagination,
.mui-pagination {
  margin: 0;
  gap: 0.5rem;
  justify-content: flex-end;
}

.pagination .page-item .page-link,
.mui-pagination .page-item .page-link {
  border-radius: 999px !important;
  width: 34px !important;
  min-width: 34px !important;
  max-width: 34px !important;
  height: 34px !important;
  min-height: 34px !important;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e4e4e7;
  color: #18181b;
  font-weight: 600;
  background-color: #fff;
  margin: 0 2px;
  transition: all 0.3s ease;
  line-height: 1;
  aspect-ratio: 1 / 1;
}
.pagination .page-item .page-link:hover { background-color: #f4f4f5; border-color: #18181b; }
.pagination .page-item.active .page-link { background-color: #18181b !important; border-color: #18181b !important; color: #fafafa !important; }
.pagination .page-item.disabled .page-link { background-color: #f4f4f5; border-color: #e4e4e7; color: #a1a1aa; cursor: not-allowed; }

/* Hide default Laravel pagination results text */
.pagination-wrapper .pagination + div,
.pagination-wrapper nav + div,
.pagination-wrapper div:has(> nav) > p,
.pagination-wrapper > div > nav ~ *:not(.pagination),
.pagination-wrapper > div:last-child p,
nav[role="navigation"] > div:first-child,
nav[role="navigation"] > div > p {
  display: none !important;
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

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", function() {
    @if(session('success'))
      Swal.fire({
        toast: true,
        position: 'bottom-end',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        timer: 3200,
        timerProgressBar: true
      });
    @endif
    // Helper function untuk loading overlay
    function showLoading() {
        $('.loading-overlay').css('display', 'flex');
    }
    
    function hideLoading() {
        $('.loading-overlay').fadeOut(300);
    }

    // Inisialisasi DataTable
    // DataTable for ordering only - pagination is handled by Laravel
    const dtRekeningTable = $('.datatables-rekenings').DataTable({
        paging: false,
        searching: false,
        ordering: false,
        info: false,
        responsive: false,
        dom: 'rt',
        columnDefs: [
            { orderable: false, targets: [0, -1] }
        ]
    });

    function updateBulkDeleteState() {
        const totalChecked = $('.rekening-checkbox:checked').length;
        $('#selected-count').text(totalChecked + ' dipilih');
        $('#selection-toolbar').toggleClass('active', totalChecked > 0);
        $('#btn-bulk-delete').prop('disabled', totalChecked === 0);
        $('.rekening-row').removeClass('row-selected');
        $('.rekening-checkbox:checked').closest('.rekening-row').addClass('row-selected');
        $('#select-all-rekenings').prop(
            'checked',
            totalChecked > 0 && totalChecked === $('.rekening-checkbox').length
        );
        $('#select-all-rekenings').prop(
            'indeterminate',
            totalChecked > 0 && totalChecked < $('.rekening-checkbox').length
        );
    }

    $(document).on('change', '#select-all-rekenings', function() {
        $('.rekening-checkbox').prop('checked', $(this).is(':checked'));
        updateBulkDeleteState();
    });

    $(document).on('change', '.rekening-checkbox', updateBulkDeleteState);

    $(document).on('submit', '#bulk-delete-form', function(e) {
        const totalChecked = $('.rekening-checkbox:checked').length;
        if (totalChecked === 0) {
            e.preventDefault();
            return;
        }

        e.preventDefault();
        Swal.fire({
            title: 'Hapus Rekening Terpilih',
            text: `Yakin ingin menghapus ${totalChecked} rekening? Data tidak dapat dikembalikan!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f5365c',
            cancelButtonColor: '#8898aa',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-secondary'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                $('#btn-bulk-delete')
                    .prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');
                showLoading();
                e.target.submit();
            }
        });
    });

    const denseToggle = document.getElementById('dense-padding-toggle');
    const tableEl = document.querySelector('.modern-table');
    if (denseToggle && tableEl) {
        const savedDense = localStorage.getItem('rekening_dense_padding') === '1';
        denseToggle.checked = savedDense;
        tableEl.classList.toggle('is-dense', savedDense);

        denseToggle.addEventListener('change', function() {
            const isDense = denseToggle.checked;
            tableEl.classList.toggle('is-dense', isDense);
            localStorage.setItem('rekening_dense_padding', isDense ? '1' : '0');
        });
    }

});
</script>
@endsection

@section('content')
<!-- Loading Overlay -->
<div class="loading-overlay">
    <div class="spinner-border spinner-border-custom text-light" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<form id="bulk-delete-form" action="{{ route('rekenings.bulkDestroy') }}" method="POST">
    @csrf
</form>

<!-- Rekening List Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header-custom">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold">
                    <i class="ri-bank-card-2-line me-2"></i>Data Rekening
                </h4>
                <p class="mb-0 opacity-75 small">Kelola dan monitor data rekening bank</p>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0 align-items-center">
                <a href="{{ route('rekenings.add') }}" class="btn btn-primary btn-add">
                    <i class="ri-add-line text-white" style="color: #fff !important;"></i>
                    Tambah Rekening
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="card-datatable table-responsive">
            <div class="selection-toolbar rounded-3" id="selection-toolbar">
                <span class="selected-text" id="selected-count">0 dipilih</span>
                <button type="submit"
                        form="bulk-delete-form"
                        id="btn-bulk-delete"
                        class="clear-btn"
                        title="Hapus Terpilih"
                        disabled>
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>

            <table class="datatables-rekenings modern-table">
                <thead>
                    <tr>
                        <th class="select-column">
                            <input type="checkbox" id="select-all-rekenings" class="custom-check" aria-label="Pilih semua rekening">
                        </th>
                        <th>Nama Bank</th>
                        <th>Nomor Rekening</th>
                        <th>Nama Pemilik</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekenings as $rekening)
                    <tr class="rekening-row">
                        <td class="select-column">
                            <input type="checkbox"
                                   class="custom-check rekening-checkbox"
                                   name="ids[]"
                                   value="{{ $rekening->id }}"
                                   form="bulk-delete-form"
                                   aria-label="Pilih rekening {{ $rekening->nama_bank }}">
                        </td>
                        
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="mui-bank-icon me-2">
                                    <i class="ri-bank-line" style="font-size: 1.25rem;"></i>
                                </div>
                                <span class="fw-semibold">{{ $rekening->nama_bank }}</span>
                            </div>
                        </td>
                        
                        <td>
                            <span class="mui-code-chip">
                                {{ $rekening->nomor_rekening }}
                            </span>
                        </td>
                        
                        <td>
                            <span class="badge bg-label-info" style="padding: 8px 16px; font-size: 0.8rem;">
                                <i class="ri-user-line me-1"></i>{{ $rekening->nama_pemilik }}
                            </span>
                        </td>
                        
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('rekenings.edit', $rekening->id) }}" 
                                   class="action-btn"
                                   title="Edit">
                                    <i class="ri-edit-2-line"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination-wrapper">
        <label class="dense-toggle-wrap mb-0">
            <input type="checkbox" id="dense-padding-toggle">
            <span>Dense padding</span>
        </label>
        <div>
            @if($rekenings->hasPages())
                {{ $rekenings->onEachSide(1)->links('pagination.mui') }}
            @else 
                <nav>
                    <ul class="pagination mui-pagination mb-0">
                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                        <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                    </ul>
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection
