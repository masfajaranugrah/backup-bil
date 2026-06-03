@extends('layouts/layoutMaster')

@section('title', 'Status Baca Tagihan')

@section('vendor-style')
<style>
/* ========================================= */
/* MODERN CLEAN STYLES - Status Baca Matrix */
/* ========================================= */
:root {
  --card-shadow: 0 2px 8px rgba(0,0,0,0.08);
  --card-hover-shadow: 0 4px 16px rgba(0,0,0,0.12);
  --border-radius: 12px;
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

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

/* Stats Card */
.stats-card {
  border-radius: var(--border-radius);
  padding: 1.5rem;
  background: #ffffff;
  color: #0f172a;
  border: 1px solid #e5e7eb;
  transition: var(--transition);
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

/* Matrix Table */
.matrix-table {
  border-collapse: separate;
  border-spacing: 0;
  min-width: 1200px;
}

.matrix-table th {
  background: #f8fafc;
  border-bottom: 2px solid #e2e8f0;
  padding: 1rem 0.5rem;
  font-weight: 600;
  color: #0f172a;
  font-size: 0.8rem;
  text-transform: uppercase;
  text-align: center;
  letter-spacing: 0.5px;
  white-space: nowrap;
}

.matrix-table th.col-pelanggan {
  text-align: left;
  padding-left: 1rem;
  min-width: 250px;
  position: sticky;
  left: 0;
  background: #f8fafc;
  z-index: 2;
  border-right: 1px solid #e2e8f0;
}

.matrix-table td {
  padding: 0.75rem 0.5rem;
  border-bottom: 1px solid #e5e7eb;
  vertical-align: middle;
  text-align: center;
}

.matrix-table td.col-pelanggan {
  text-align: left;
  padding-left: 1rem;
  position: sticky;
  left: 0;
  background: #ffffff;
  z-index: 1;
  border-right: 1px solid #e2e8f0;
}

.matrix-table tbody tr:hover td {
  background: #f1f5f9;
}

/* Status Icons */
.status-icon {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 6px;
  font-size: 1.1rem;
  cursor: help;
  transition: transform 0.2s;
}

.status-icon:hover {
  transform: scale(1.15);
}

.status-read {
  background: #f1f5f9;
  color: #18181b;
  border: 1px solid #e2e8f0;
}

.status-unread {
  background: #fff;
  color: #71717a;
  border: 1px dashed #e4e4e7;
}

.status-empty {
  color: #cbd5e1;
  font-size: 1rem;
}

/* Form Controls */
.form-select, .form-control {
  border-radius: 8px;
  border: 1px solid #e0e0e0;
  padding: 0.5rem 1rem;
  transition: var(--transition);
}

.form-select:focus, .form-control:focus {
  border-color: #111827;
  box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.12);
}

/* Empty State */
.empty-state {
  padding: 4rem 2rem;
  text-align: center;
  color: #71717a;
}

.empty-state i {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: #d4d4d8;
}

/* Search input */
.search-input {
  position: relative;
}

.search-input i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #a1a1aa;
}

.search-input input {
  padding-left: 36px !important;
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

/* Tooltip Customization */
.tooltip-inner {
  text-align: left;
  padding: 8px 12px;
  border-radius: 6px;
  font-size: 0.8rem;
}

/* PAGINATION STYLES */
.pagination-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  border-top: 1px solid #f0f0f0;
  background: transparent;
}

.dense-toggle-wrap {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  color: #334155;
}

.dense-toggle-wrap input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #0f172a;
}

.matrix-table.is-dense th {
  padding: 0.5rem 0.25rem !important;
}

.matrix-table.is-dense td {
  padding: 0.4rem 0.25rem !important;
}

.pagination {
  display: flex;
  margin: 0;
  gap: 0.5rem;
  justify-content: flex-end;
  list-style: none;
  padding-left: 0;
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
  text-decoration: none;
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
  cursor: pointer;
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
  cursor: not-allowed !important;
}

.pagination-wrapper .mui-pagination .pagination-ellipsis .page-link {
  color: #64748b !important;
  letter-spacing: 0.08em;
  cursor: default !important;
}

@media (max-width: 992px) {
  .pagination-wrapper .mui-pagination {
    transform: scale(0.85);
    transform-origin: right center;
  }
}

@media (max-width: 768px) {
  .pagination-wrapper .mui-pagination {
    transform: scale(0.72);
  }
}
</style>
@endsection
@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="fw-bold mb-1" style="color: #18181b;">
      <i class="ri-table-alt-line me-2"></i>Status Baca Tagihan
    </h4>
    <p class="text-muted mb-0" style="font-size: 0.875rem;">
      Rekap status baca tagihan per pelanggan dalam satu tahun (Tabel Matriks)
    </p>
  </div>
</div>

{{-- Stats Cards --}}
<div class="row mb-4" id="statsRow">
  <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
    <div class="stats-card">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <p class="mb-1 text-muted" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Total Pelanggan JMK-GK</p>
          <h2 class="mb-0 fw-bold" id="statTotal" style="font-size: 2rem;">0</h2>
        </div>
        <div class="stats-icon">
          <i class="ri-file-list-3-line"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
    <div class="stats-card">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <p class="mb-1 text-muted" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Pelanggan Sudah Baca</p>
          <h2 class="mb-0 fw-bold" id="statRead" style="font-size: 2rem; color: #18181b;">0</h2>
        </div>
        <div class="stats-icon" style="background: #f4f4f5; color: #18181b;">
          <i class="ri-check-double-line"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
    <div class="stats-card">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <p class="mb-1 text-muted" style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">Pelanggan Belum Baca</p>
          <h2 class="mb-0 fw-bold" id="statUnread" style="font-size: 2rem; color: #18181b;">0</h2>
        </div>
        <div class="stats-icon" style="background: #f4f4f5; color: #71717a;">
          <i class="ri-eye-off-line"></i>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Main Table Card --}}
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3" style="background: transparent; padding: 1.5rem; border-bottom: 1px solid #f0f0f0;">
    <div class="d-flex align-items-center gap-3 flex-wrap">
      {{-- Year Filter --}}
      <div class="d-flex align-items-center gap-2">
        <label class="form-label mb-0 fw-semibold" style="font-size: 0.875rem; white-space: nowrap;">Tahun:</label>
        <select id="filterYear" class="form-select" style="width: 120px; font-weight: 600;">
          @php $currentYear = date('Y'); @endphp
          @for($y = $currentYear + 1; $y >= 2023; $y--)
            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
        </select>
      </div>

    </div>

    {{-- Search --}}
    <div class="search-input">
      <i class="ri-search-line"></i>
      <input type="text" id="searchInput" class="form-control" placeholder="Cari ID / Nama..." style="width: 250px;">
    </div>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive" style="max-height: 70vh;">
      <table class="table matrix-table mb-0" id="tagihanReadTable">
        <thead>
          <tr>
            <th style="width: 50px; text-align: center; position: sticky; left: 0; background: #f8fafc; z-index: 3;">No</th>
            <th class="col-pelanggan">Pelanggan</th>
            <th>Jan</th>
            <th>Feb</th>
            <th>Mar</th>
            <th>Apr</th>
            <th>Mei</th>
            <th>Jun</th>
            <th>Jul</th>
            <th>Ags</th>
            <th>Sep</th>
            <th>Okt</th>
            <th>Nov</th>
            <th>Des</th>
          </tr>
        </thead>
        <tbody id="tagihanReadBody">
          {{-- Data akan diisi dari JS --}}
        </tbody>
      </table>
    </div>
    
    {{-- Pagination Controls --}}
    <div class="pagination-wrapper" id="paginationControlsContainer" style="display: none !important;">
      <div class="d-flex align-items-center gap-4">
        <label class="dense-toggle-wrap mb-0">
          <input type="checkbox" id="densePaddingToggle">
          <span>Dense padding</span>
        </label>
      </div>
      <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="pagination pagination-sm mb-0 mui-pagination" id="paginationList">
        </ul>
      </nav>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
  let allData = [];
  let currentPage = 1;
  let lastPage = 1;
  let perPage = 40;
  let searchTimeout = null;

  
  // Inisialisasi tooltip dari bootstrap
  function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl, { html: true });
    });
  }

  function showLoading() {
    // Loading animation disabled by request.
  }

  function hideLoading() {
    // Loading animation disabled by request.
  }

  function getStatusIcon(data) {
    if (!data.ada) {
      return '<span class="status-empty">-</span>';
    }
    
    if (data.is_read) {
      const tooltipMsg = `Lunas/Belum: <b>${data.status_pembayaran}</b><br>Dibaca pd: <b>${data.read_at}</b>`;
      return `<span class="status-icon status-read" data-bs-toggle="tooltip" data-bs-placement="top" title="${tooltipMsg}"><i class="ri-check-line"></i></span>`;
    }
    
    const tooltipMsg = `Lunas/Belum: <b>${data.status_pembayaran}</b><br><i>Belum dibaca</i>`;
    return `<span class="status-icon status-unread" data-bs-toggle="tooltip" data-bs-placement="top" title="${tooltipMsg}"><i class="ri-close-line"></i></span>`;
  }

  function renderTable(data) {
    const tbody = document.getElementById('tagihanReadBody');

    if (!data || data.length === 0) {
      tbody.innerHTML = `
        <tr class="empty-state-row">
          <td colspan="14">
            <div class="empty-state">
              <i class="ri-folder-user-line d-block"></i>
              <h6 class="fw-semibold mb-1">Tidak ada data</h6>
              <p class="mb-0 text-muted">Tidak ditemukan pelanggan untuk pencarian/filter ini.</p>
            </div>
          </td>
        </tr>`;
      
      document.getElementById('paginationControlsContainer').style.setProperty('display', 'none', 'important');
      return;
    }

    tbody.innerHTML = data.map((item, idx) => `
      <tr>
        <td style="text-align: center; color: #71717a; font-weight: 600; position: sticky; left: 0; background: #fff; z-index: 1;">${((currentPage - 1) * perPage) + idx + 1}</td>
        <td class="col-pelanggan">
          <div class="fw-semibold" style="color: #18181b;">${item.nama_lengkap}</div>
          <div style="font-size: 0.75rem; color: #71717a; font-family: monospace;">${item.nomer_id} &bull; ${item.no_whatsapp}</div>
        </td>
        ${[1,2,3,4,5,6,7,8,9,10,11,12].map(m => `
          <td>${getStatusIcon(item.tagihans_matrix[m])}</td>
        `).join('')}
      </tr>
    `).join('');

    initTooltips();
  }

  function getVisiblePageItems(current, total) {
    const rawPages = [1, current - 2, current - 1, current, current + 1, current + 2, total];
    const filtered = rawPages.filter(page => page >= 1 && page <= total);
    const unique = [...new Set(filtered)];
    unique.sort((a, b) => a - b);
    return unique;
  }

  function renderPagination() {
    const listContainer = document.getElementById('paginationList');
    if (!listContainer) return;

    let html = '';

    // 1. First Page Link («)
    if (currentPage === 1) {
      html += `
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link" aria-hidden="true">&laquo;</span>
        </li>`;
    } else {
      html += `
        <li class="page-item">
          <a class="page-link" data-page="1" aria-label="First page">&laquo;</a>
        </li>`;
    }

    // 2. Previous Page Link (‹)
    if (currentPage === 1) {
      html += `
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link" aria-hidden="true">&lsaquo;</span>
        </li>`;
    } else {
      html += `
        <li class="page-item">
          <a class="page-link" data-page="${currentPage - 1}" rel="prev" aria-label="Previous page">&lsaquo;</a>
        </li>`;
    }

    // 3. Page Numbers and Ellipses
    const visiblePages = getVisiblePageItems(currentPage, lastPage);
    let previousPage = null;

    for (const page of visiblePages) {
      if (previousPage !== null && page > previousPage + 1) {
        html += `
          <li class="page-item disabled pagination-ellipsis" aria-disabled="true">
            <span class="page-link">&hellip;</span>
          </li>`;
      }

      if (page === currentPage) {
        html += `
          <li class="page-item active" aria-current="page">
            <span class="page-link">${page}</span>
          </li>`;
      } else {
        html += `
          <li class="page-item">
            <a class="page-link" data-page="${page}">${page}</a>
          </li>`;
      }

      previousPage = page;
    }

    // 4. Next Page Link (›)
    if (currentPage === lastPage) {
      html += `
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link" aria-hidden="true">&rsaquo;</span>
        </li>`;
    } else {
      html += `
        <li class="page-item">
          <a class="page-link" data-page="${currentPage + 1}" rel="next" aria-label="Next page">&rsaquo;</a>
        </li>`;
    }

    // 5. Last Page Link (»)
    if (currentPage === lastPage) {
      html += `
        <li class="page-item disabled" aria-disabled="true">
          <span class="page-link" aria-hidden="true">&raquo;</span>
        </li>`;
    } else {
      html += `
        <li class="page-item">
          <a class="page-link" data-page="${lastPage}" aria-label="Last page">&raquo;</a>
        </li>`;
    }

    listContainer.innerHTML = html;
  }

  function updatePagination(pagination) {
    currentPage = pagination.current_page;
    lastPage = pagination.last_page;
    perPage = pagination.per_page || perPage;
    
    const container = document.getElementById('paginationControlsContainer');
    
    if (pagination.total === 0 || lastPage <= 1) {
      container.style.setProperty('display', 'none', 'important');
      return;
    }
    
    container.style.setProperty('display', 'flex', 'important');
    
    renderPagination();
  }

  function updateStats(stats) {
    document.getElementById('statTotal').textContent = stats.total;
    document.getElementById('statRead').textContent = stats.sudah_baca;
    document.getElementById('statUnread').textContent = stats.belum_baca;
  }

  function fetchData(page = 1) {
    showLoading();
    const year = document.getElementById('filterYear').value;
    const search = document.getElementById('searchInput').value;
    
    const url = new URL(window.location.origin + '/dashboard/admin/tagihan/status-baca/data');
    url.searchParams.append('year', year);
    url.searchParams.append('page', page);
    if (search) {
      url.searchParams.append('search', search);
    }
    
    fetch(url, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(async (res) => {
        if (res.redirected) {
          window.location.href = res.url;
          return null;
        }

        const contentType = res.headers.get('content-type') || '';
        const bodyText = await res.text();

        if (!res.ok) {
          throw new Error(`HTTP ${res.status}`);
        }

        if (!contentType.includes('application/json')) {
          throw new Error('Response bukan JSON');
        }

        try {
          return JSON.parse(bodyText);
        } catch (e) {
          throw new Error('JSON tidak valid');
        }
      })
      .then(json => {
        if (!json) return;
        if (json.status) {
          const pagination = json.data.pagination || {};
          const rows = json.data.pelanggans || [];

          // Jika halaman diminta sudah tidak valid (mis. habis filter/delete), lompat ke halaman terakhir.
          if (rows.length === 0 && (pagination.total || 0) > 0 && (pagination.current_page || 1) > (pagination.last_page || 1)) {
            fetchData(pagination.last_page || 1);
            return;
          }

          allData = json.data.pelanggans;
          updateStats(json.data.statistics);
          renderTable(allData);
          updatePagination(pagination);
        } else {
          throw new Error(json.message || 'Data gagal dimuat');
        }
        hideLoading();
      })
      .catch(err => {
        console.error('Error fetching data:', err);
        const tbody = document.getElementById('tagihanReadBody');
        tbody.innerHTML = `
          <tr class="empty-state-row">
            <td colspan="14">
              <div class="empty-state">
                <i class="ri-alert-line d-block"></i>
                <h6 class="fw-semibold mb-1">Gagal memuat data</h6>
                <p class="mb-0 text-muted">${err.message || 'Terjadi kesalahan saat mengambil data.'}</p>
              </div>
            </td>
          </tr>`;
        document.getElementById('paginationControlsContainer').style.setProperty('display', 'none', 'important');
        hideLoading();
      });
  }

  // Events
  document.getElementById('filterYear').addEventListener('change', () => fetchData(1));
  
  // Debounce search
  document.getElementById('searchInput').addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      fetchData(1);
    }, 500);
  });
  
  // Pagination clicks (delegated)
  document.getElementById('paginationList').addEventListener('click', (e) => {
    const pageLink = e.target.closest('[data-page]');
    if (!pageLink) return;
    const targetPage = Number(pageLink.getAttribute('data-page'));
    if (!Number.isNaN(targetPage) && targetPage >= 1 && targetPage <= lastPage && targetPage !== currentPage) {
      fetchData(targetPage);
    }
  });

  // Dense padding checkbox toggle
  const denseToggle = document.getElementById('densePaddingToggle');
  const tableEl = document.querySelector('.matrix-table');
  if (denseToggle && tableEl) {
      const savedDense = localStorage.getItem('tagihan_read_dense_padding') === '1';
      denseToggle.checked = savedDense;
      tableEl.classList.toggle('is-dense', savedDense);

      denseToggle.addEventListener('change', function() {
          const isDense = denseToggle.checked;
          tableEl.classList.toggle('is-dense', isDense);
          localStorage.setItem('tagihan_read_dense_padding', isDense ? '1' : '0');
      });
  }

  // Initial load
  fetchData(1);
});
</script>
@endsection
