@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
@vite([
  'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
  'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
])
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/style.css">
<style>
/* ========================================= */
/* MODERN DASHBOARD UI - PREMIUM AESTHETIC */
/* ========================================= */
:root {
  --radius: 1rem;
}

body {
  background: #f8fafc;
}

/* Typography & General */
.fw-bold { font-weight: 700 !important; }
.fw-semibold { font-weight: 600 !important; }
.fw-medium { font-weight: 500 !important; }

/* Filter Container */
.filter-container {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 1.5rem;
}

/* Welcome Card (Left) */
.card-welcome {
  background: #0f172a;
  border-radius: var(--radius);
  position: relative;
  overflow: hidden;
  border: none;
  box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1);
  min-height: 280px;
  display: flex;
  align-items: center;
}
.card-welcome::before {
  content: '';
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.15), transparent 50%),
              radial-gradient(circle at bottom left, rgba(56, 189, 248, 0.1), transparent 40%);
  pointer-events: none;
}
.welcome-content {
  position: relative;
  z-index: 2;
  padding: 2.5rem;
  color: #f8fafc;
}
.welcome-content h2 {
  color: #fff;
  font-size: 2.25rem;
  font-weight: 700;
  margin-bottom: 1rem;
}
.welcome-content p {
  color: #cbd5e1;
  font-size: 1rem;
  max-width: 450px;
  line-height: 1.6;
  margin-bottom: 2rem;
}
.btn-go-now {
  background: #10b981;
  color: #fff;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  font-weight: 600;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.btn-go-now:hover {
  background: #059669;
  transform: translateY(-2px);
  color: #fff;
}
.welcome-illustration {
  position: absolute;
  right: 10%;
  bottom: 0;
  height: 90%;
  z-index: 1;
}

/* Featured Card (Right) */
.card-featured {
  background: url("{{ asset('assets/img/illustrations/featured_abstract_bg.png') }}") center/cover no-repeat;
  border-radius: var(--radius);
  position: relative;
  overflow: hidden;
  border: none;
  min-height: 280px;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}
.card-featured::before {
  content: '';
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 60%, transparent 100%);
}
.featured-content {
  position: relative;
  z-index: 2;
  padding: 2rem;
}
.featured-badge {
  color: #34d399;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  margin-bottom: 0.5rem;
}
.featured-title {
  color: #fff;
  font-size: 1.5rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}
.featured-desc {
  color: #d1d5db;
  font-size: 0.875rem;
  margin: 0;
}

/* Stat Cards */
.stat-card {
  background: #fff;
  border-radius: var(--radius);
  padding: 1.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
  transition: all 0.2s;
  height: 100%;
}
.stat-card:hover {
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
  border-color: #cbd5e1;
}
.stat-title {
  font-size: 0.875rem;
  color: #64748b;
  font-weight: 600;
  margin-bottom: 1.5rem;
}
.stat-value-wrap {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
}
.stat-value {
  font-size: 2.25rem;
  font-weight: 700;
  color: #0f172a;
  line-height: 1;
  margin-bottom: 0.75rem;
}
.stat-trend {
  display: flex;
  align-items: center;
  font-size: 0.875rem;
  color: #64748b;
}
.trend-up { color: #10b981; font-weight: 600; margin-right: 0.5rem; }
.trend-down { color: #ef4444; font-weight: 600; margin-right: 0.5rem; }
.trend-up i, .trend-down i { font-size: 1.25rem; margin-right: 0.25rem; }

/* Quick Links Grid */
.quick-links-container {
  background: #fff;
  border-radius: var(--radius);
  padding: 1.5rem;
  border: 1px solid #e2e8f0;
}
.quick-link-item {
  display: flex;
  align-items: center;
  padding: 1rem;
  border-radius: 0.75rem;
  border: 1px solid #f1f5f9;
  transition: all 0.2s;
  text-decoration: none;
  color: inherit;
  margin-bottom: 1rem;
}
.quick-link-item:hover {
  background: #f8fafc;
  border-color: #e2e8f0;
  transform: translateX(4px);
}
.ql-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  margin-right: 1rem;
}
.ql-icon.bg-primary-light { background: #e0e7ff; color: #4f46e5; }
.ql-icon.bg-success-light { background: #d1fae5; color: #10b981; }
.ql-icon.bg-info-light { background: #e0f2fe; color: #0ea5e9; }
.ql-icon.bg-warning-light { background: #fef3c7; color: #f59e0b; }

.ql-content h6 {
  font-size: 1rem;
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 0.25rem;
}
.ql-content p {
  font-size: 0.875rem;
  color: #64748b;
  margin: 0;
}

/* Animations */
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
.animate-in { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }

/* Flatpickr Modern Override */
.flatpickr-calendar { border: 1px solid #e2e8f0 !important; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1) !important; border-radius: 1rem !important; }
.flatpickr-monthSelect-month.selected { background: #0f172a !important; color: white !important; box-shadow: 0 4px 12px rgba(15,23,42,0.3) !important; }

@media (max-width: 991.98px) {
  .welcome-illustration { opacity: 0.2; }
}
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  
  <!-- Filter Periode (Top Right) -->
  <div class="filter-container animate-in">
    <form action="{{ url()->current() }}" method="GET" id="filterForm">
      <input type="hidden" name="periode" id="periodeInput" value="{{ request('periode') }}">
      <div id="periodeTrigger" class="d-flex align-items-center gap-2 px-4 py-2 bg-white border rounded-pill shadow-sm cursor-pointer" style="transition: all 0.2s;">
        <i class="ri-calendar-line text-primary"></i>
        <span class="fw-semibold text-dark" style="font-size: 0.9rem;">
          {{ request('periode') ? \Carbon\Carbon::createFromFormat('Y-m', request('periode'))->translatedFormat('F Y') : 'Filter Bulan' }}
        </span>
        <i class="ri-arrow-down-s-line text-muted ms-2"></i>
        @if(request('periode'))
          <div onclick="resetFilter(event)" class="d-flex align-items-center justify-content-center bg-danger rounded-circle text-white ms-2" style="width: 20px; height: 20px;" title="Hapus Filter">
            <i class="ri-close-line" style="font-size: 12px;"></i>
          </div>
        @endif
      </div>
    </form>
  </div>

  <!-- Hero Section -->
  <div class="row g-4 mb-4">
    <!-- Welcome Back Card -->
    <div class="col-12 col-xl-8 animate-in delay-1">
      <div class="card-welcome">
        <div class="welcome-content">
          <h2>Welcome back <span class="wave">👋</span><br>{{ auth()->user()->name ?? 'Admin' }}</h2>
          <p>If you are going to manage billing, customers, and operations, you need to be sure everything is on track. Here is your dashboard overview.</p>
          <a href="{{ route('tagihan.get') }}" class="btn-go-now">Go to Tagihan</a>
        </div>
        <!-- Using girl-with-laptop-light.png as the 3D illustration -->
        <img src="{{ asset('assets/img/illustrations/girl-with-laptop-light.png') }}" class="welcome-illustration" alt="Welcome Illustration">
      </div>
    </div>
    
    <!-- Featured Card -->
    <div class="col-12 col-xl-4 animate-in delay-1">
      <div class="card-featured">
        <div class="featured-content">
          <div class="featured-badge">System Status</div>
          <h3 class="featured-title">All Systems Operational</h3>
          <p class="featured-desc">Network infrastructure and billing engines are running smoothly.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Section -->
  <div class="row g-4 mb-4">
    <!-- Total Active Users (Simulated to Total Customer) -->
    <div class="col-12 col-md-4 animate-in delay-2">
      <div class="stat-card">
        <div class="stat-title">Total active users</div>
        <div class="stat-value-wrap">
          <div>
            <div class="stat-value">{{ number_format($totalCustomer) }}</div>
            <div class="stat-trend">
              <span class="trend-up"><i class="ri-arrow-right-up-line"></i> +2.6%</span> last 7 days
            </div>
          </div>
          <div id="chart-users"></div>
        </div>
      </div>
    </div>

    <!-- Tagihan Lunas -->
    <div class="col-12 col-md-4 animate-in delay-2">
      <div class="stat-card">
        <div class="stat-title">Tagihan Lunas</div>
        <div class="stat-value-wrap">
          <div>
            <div class="stat-value">{{ number_format($customerLunas ?? 0) }}</div>
            <div class="stat-trend">
              <span class="trend-up"><i class="ri-arrow-right-up-line"></i> +0.2%</span> last 7 days
            </div>
          </div>
          <div id="chart-lunas"></div>
        </div>
      </div>
    </div>

    <!-- Belum Lunas -->
    <div class="col-12 col-md-4 animate-in delay-2">
      <div class="stat-card">
        <div class="stat-title">Tagihan Belum Lunas</div>
        <div class="stat-value-wrap">
          <div>
            <div class="stat-value">{{ number_format($belumLunas) }}</div>
            <div class="stat-trend">
              <span class="trend-down"><i class="ri-arrow-right-down-line"></i> -0.1%</span> last 7 days
            </div>
          </div>
          <div id="chart-belum"></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom Section: Charts & Quick Links -->
  <div class="row g-4">
    <!-- Area / Donut Chart -->
    <div class="col-12 col-lg-8 animate-in delay-3">
      <div class="card h-100 border-0 shadow-sm" style="border-radius: var(--radius);">
        <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 pb-0">
          <div>
            <h5 class="mb-1 fw-bold text-dark">Status Pelanggan Aktif</h5>
            <p class="text-muted small mb-0">(+43%) than last year</p>
          </div>
        </div>
        <div class="card-body">
          <div id="chart-status-pelanggan"></div>
          
          <div class="d-flex justify-content-around mt-4">
            <div class="text-center">
              <div class="d-flex align-items-center justify-content-center mb-1">
                <span class="badge badge-dot bg-success me-2"></span>
                <span class="text-muted fw-medium">Aktif</span>
              </div>
              <h5 class="mb-0 fw-bold">{{ number_format($activeCustomers ?? 0) }}</h5>
            </div>
            <div class="text-center">
              <div class="d-flex align-items-center justify-content-center mb-1">
                <span class="badge badge-dot bg-warning me-2"></span>
                <span class="text-muted fw-medium">Tidak Aktif</span>
              </div>
              <h5 class="mb-0 fw-bold">{{ number_format($inactiveCustomers ?? 0) }}</h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Links -->
    <div class="col-12 col-lg-4 animate-in delay-3">
      <div class="quick-links-container h-100 shadow-sm">
        <h5 class="fw-bold mb-4">Quick Links</h5>
        
        <a href="{{ route('tagihan.get') }}" class="quick-link-item">
          <div class="ql-icon bg-primary-light"><i class="ri-bill-line"></i></div>
          <div class="ql-content">
            <h6>Tagihan Belum Bayar</h6>
            <p>Kelola tagihan pending</p>
          </div>
        </a>

        <a href="{{ route('tagihan.lunas') }}" class="quick-link-item">
          <div class="ql-icon bg-success-light"><i class="ri-checkbox-circle-line"></i></div>
          <div class="ql-content">
            <h6>Tagihan Lunas</h6>
            <p>Riwayat pembayaran</p>
          </div>
        </a>

        <a href="{{ route('pelanggan') }}" class="quick-link-item">
          <div class="ql-icon bg-info-light"><i class="ri-group-line"></i></div>
          <div class="ql-content">
            <h6>Manajemen Pelanggan</h6>
            <p>Kelola data pelanggan</p>
          </div>
        </a>

        <a href="{{ route('paket.index') }}" class="quick-link-item mb-0">
          <div class="ql-icon bg-warning-light"><i class="ri-box-3-line"></i></div>
          <div class="ql-content">
            <h6>Paket Internet</h6>
            <p>Total: {{ number_format($totalPaket) }} Paket</p>
          </div>
        </a>
      </div>
    </div>
  </div>

</div>
@endsection

@section('vendor-script')
@vite([
  'resources/assets/vendor/libs/flatpickr/flatpickr.js',
])
<script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
  function resetFilter(e) {
      e.stopPropagation();
      window.location.href = "{{ url()->current() }}";
  }

  document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize Flatpickr
    flatpickr('#periodeTrigger', {
        plugins: [new monthSelectPlugin({ shorthand: true, dateFormat: "Y-m", altFormat: "F Y", theme: "light" })],
        locale: "id",
        disableMobile: true,
        defaultDate: "{{ request('periode') }}",
        onChange: function(selectedDates, dateStr) {
            if (dateStr) {
                document.getElementById('periodeInput').value = dateStr;
                document.getElementById('filterForm').submit();
            }
        }
    });

    // 2. Common Sparkline Config for Mini Bar Charts
    const sparklineOptions = {
      chart: { type: 'bar', width: 80, height: 40, sparkline: { enabled: true } },
      plotOptions: { bar: { columnWidth: '60%', borderRadius: 2 } },
      tooltip: { fixed: { enabled: false }, x: { show: false }, y: { title: { formatter: function (seriesName) { return '' } } }, marker: { show: false } }
    };

    // Chart 1: Users (Green)
    new ApexCharts(document.querySelector("#chart-users"), {
      ...sparklineOptions,
      colors: ['#10b981'],
      series: [{ data: [25, 66, 41, 89, 63, 25, 44, 12, 36, 9, 54] }]
    }).render();

    // Chart 2: Lunas (Blue)
    new ApexCharts(document.querySelector("#chart-lunas"), {
      ...sparklineOptions,
      colors: ['#0ea5e9'],
      series: [{ data: [12, 14, 2, 47, 42, 15, 47, 75, 65, 19, 14] }]
    }).render();

    // Chart 3: Belum Lunas (Red/Orange)
    new ApexCharts(document.querySelector("#chart-belum"), {
      ...sparklineOptions,
      colors: ['#ef4444'],
      series: [{ data: [47, 45, 74, 14, 56, 74, 14, 11, 7, 39, 82] }]
    }).render();

    // Chart 4: Donut Chart for Status Pelanggan
    const donutOptions = {
      series: [{{ $activeCustomers ?? 0 }}, {{ $inactiveCustomers ?? 0 }}],
      labels: ['Aktif', 'Tidak Aktif'],
      chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
      colors: ['#10b981', '#f59e0b'],
      plotOptions: {
        pie: {
          donut: { size: '75%', labels: { show: true, name: { fontSize: '14px', color: '#64748b' }, value: { fontSize: '24px', fontWeight: 700, color: '#0f172a' }, total: { show: true, label: 'Total', color: '#64748b', fontSize: '14px', formatter: function (w) { return w.globals.seriesTotals.reduce((a, b) => a + b, 0) } } } }
        }
      },
      dataLabels: { enabled: false },
      legend: { show: false },
      stroke: { width: 0 }
    };
    new ApexCharts(document.querySelector("#chart-status-pelanggan"), donutOptions).render();
  });
</script>
@endsection
