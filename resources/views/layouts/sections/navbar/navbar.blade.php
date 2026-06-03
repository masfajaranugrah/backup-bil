@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$containerNav = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
$navbarDetached = $navbarDetached ?? '';
@endphp

<style>
  #layout-navbar {
    height: 64px !important;
    min-height: 64px !important;
    max-height: 64px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    transform: none !important;
  }

  html.window-scrolled #layout-navbar,
  .window-scrolled #layout-navbar,
  .layout-navbar-fixed .window-scrolled #layout-navbar,
  .layout-navbar-fixed .window-scrolled .layout-navbar.navbar-detached {
    height: 64px !important;
    min-height: 64px !important;
    max-height: 64px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
    transform: none !important;
  }

  #layout-navbar > .container-fluid,
  #layout-navbar > .container-xxl,
  #layout-navbar .navbar-nav-right,
  #layout-navbar .navbar-nav {
    min-height: 64px !important;
    align-items: center !important;
  }

  #layout-navbar .navbar-nav-right {
    margin-left: auto !important;
    flex: 0 0 auto !important;
  }

  #layout-navbar .navbar-nav {
    flex-wrap: nowrap !important;
    transform: none !important;
  }

  #layout-navbar .layout-menu-toggle {
    height: 100% !important;
    min-height: inherit !important;
    align-self: stretch !important;
    display: flex !important;
    align-items: center !important;
  }

  #layout-navbar .layout-menu-toggle .nav-link {
    width: 40px !important;
    height: 40px !important;
    min-width: 40px !important;
    min-height: 40px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    align-self: center !important;
    padding: 0 !important;
    margin: 0 !important;
    line-height: 1 !important;
  }

  #layout-navbar .layout-menu-toggle .nav-link i {
    display: block !important;
    line-height: 1 !important;
  }

  #layout-navbar .nav-item,
  #layout-navbar .nav-link,
  #layout-navbar .btn-icon,
  #layout-navbar #sbNavbarCollapseBtn,
  #layout-navbar .avatar {
    flex: 0 0 auto !important;
    transform: none !important;
  }

  #layout-navbar .btn-icon {
    width: 40px !important;
    height: 40px !important;
    min-width: 40px !important;
    min-height: 40px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
  }

  #layout-navbar .navbar-dropdown,
  #layout-navbar .dropdown-user,
  #layout-navbar .dropdown-user > .nav-link {
    width: 40px !important;
    height: 40px !important;
    min-width: 40px !important;
    min-height: 40px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
  }

  #layout-navbar .dropdown-user .avatar {
    width: 38px !important;
    height: 38px !important;
    min-width: 38px !important;
  }

  @media (min-width: 992px) {
    #layout-navbar .layout-menu-toggle.d-lg-none {
      display: none !important;
    }
  }
</style>

<!-- Navbar -->
<nav class="layout-navbar navbar navbar-expand-xl align-items-center {{ $navbarDetached }} {{ $containerNav }}" id="layout-navbar" style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); box-shadow: none;">

  <div class="{{ $containerNav }} d-flex justify-content-between align-items-center w-100">

    {{-- Left Side (Menu Toggle & Branding) --}}
    <div class="d-flex align-items-center">
      {{-- Menu Toggle --}}
      @if(!isset($navbarHideToggle))
        <div class="layout-menu-toggle navbar-nav align-items-center me-3 d-lg-none">
          <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ri-menu-fill ri-22px text-dark"></i>
          </a>
        </div>
      @endif

      <button type="button" class="d-none d-lg-inline-flex align-items-center justify-content-center me-3 border-0 bg-white text-dark shadow-sm" id="sbNavbarCollapseBtn" aria-label="Toggle sidebar" style="width: 40px; height: 40px; border-radius: 14px;">
        <span class="material-symbols-rounded" style="font-size: 22px;">left_panel_close</span>
      </button>

    </div>

    {{-- Right Side --}}
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

      <ul class="navbar-nav flex-row align-items-center ms-auto gap-1 gap-md-3">

        {{-- Search Command (Desktop) --}}
        <li class="nav-item d-none d-lg-flex align-items-center">
          <div id="navSearchTrigger" onclick="openNavSearch()" class="d-flex align-items-center bg-light border rounded-pill px-3 py-1 cursor-pointer" style="height: 38px; user-select:none;">
            <i class="ri-search-line text-muted ri-16px"></i>
            <div class="ms-2 d-flex align-items-center">
              <span class="border rounded bg-white text-muted px-1 shadow-sm d-flex align-items-center justify-content-center" style="font-size: 0.7rem; height: 20px; min-width: 20px;">⌘</span>
              <span class="border rounded bg-white text-muted px-1 shadow-sm ms-1 d-flex align-items-center justify-content-center" style="font-size: 0.7rem; height: 20px; min-width: 20px;">K</span>
            </div>
          </div>
        </li>

        {{-- User Dropdown --}}
        @php
            $user = Auth::user() ?? (Auth::guard('customer')->check() ? Auth::guard('customer')->user() : null);
            $roleInitials = '-';
            if ($user) {
                if (!empty($user->role)) {
                    $roleInitials = match(strtolower($user->role)) {
                        'customer service', 'cs' => 'CS',
                        'admin', 'ad' => 'AD',
                        'team', 'tm' => 'TM',
                        default => strtoupper(substr($user->role, 0, 2)),
                    };
                } elseif (!empty($user->nama_lengkap)) {
                    $roleInitials = strtoupper(substr($user->nama_lengkap, 0, 2));
                }
            }
        @endphp

        <li class="nav-item navbar-dropdown dropdown-user dropdown ms-1 d-none d-lg-flex">
          <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown" style="outline: none; box-shadow: none;">
            <div class="avatar avatar-online text-white d-flex justify-content-center align-items-center rounded-circle border border-2 border-success" style="width: 38px; height: 38px; background: #0f172a;">
              <span class="fw-bold" style="font-size: 0.9rem;">{{ $roleInitials }}</span>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="border-radius: 12px;">

            {{-- Profile Info --}}
            <li>
              <a class="dropdown-item" href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online text-white d-flex justify-content-center align-items-center rounded-circle" style="width: 40px; height: 40px; background: #0f172a;">
                      <span class="fw-bold">{{ $roleInitials }} </span>
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    @php
                        $displayName = $user?->name ?? $user?->nama_lengkap ?? '-';
                        $displayRole = $user?->role ?? 'customer';
                    @endphp
                    <span class="fw-bold d-block text-dark">{{ $displayName }}</span>
                    <small class="text-muted text-capitalize">{{ $displayRole }}</small>
                  </div>
                </div>
              </a>
            </li>

            <li><div class="dropdown-divider"></div></li>

            {{-- Logout / Login --}}
            @php
                $guard = null;
                if(Auth::guard('customer')->check()) {
                    $guard = 'customer';
                    $logoutRoute = '/pelanggan/jernihnet/login';
                } elseif(Auth::check()) {
                    $guard = 'web';
                    $logoutRoute = '/dashboard/login';
                }
            @endphp

            <li>
              <div class="d-grid px-3 pt-2 pb-1">
                @if($guard)
                  <a class="btn btn-dark btn-sm d-flex justify-content-center align-items-center"
                     href="{{ $logoutRoute }}"
                     onclick="event.preventDefault(); document.getElementById('logout-form-{{ $guard }}').submit();" style="border-radius: 8px;">
                     Logout <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                  </a>

                  <form id="logout-form-{{ $guard }}" method="POST" action="{{ $guard === 'customer' ? route('customer.logout') : route('logout') }}">
                    @csrf
                  </form>
                @else
                  <a class="btn btn-dark btn-sm d-flex justify-content-center align-items-center" href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}" style="border-radius: 8px;">
                    Login <i class="ri-login-box-line ms-2 ri-16px"></i>
                  </a>
                @endif
              </div>
            </li>

          </ul>
        </li>
        {{-- /User Dropdown --}}

      </ul>

    </div>
  </div>

</nav>
<!-- / Navbar -->

<!-- ===== COMMAND SEARCH MODAL ===== -->
<style>
#navSearchOverlay { animation: navFadeIn 0.15s ease; }
@keyframes navFadeIn { from { opacity:0; } to { opacity:1; } }
.nav-search-item:hover { background:#f8fafc !important; }
#navSearchInput::placeholder { color:#a0aec0; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const navbar = document.getElementById('layout-navbar');
  if (!navbar) return;

  function lockNavbarItems() {
    navbar.style.height = '64px';
    navbar.style.minHeight = '64px';
    navbar.style.maxHeight = '64px';
    navbar.style.transform = 'none';
  }

  lockNavbarItems();
  window.addEventListener('scroll', lockNavbarItems, { passive: true });

  const observer = new MutationObserver(lockNavbarItems);
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
  observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
});
</script>

<div id="navSearchOverlay" onclick="closeNavSearch()" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.5); backdrop-filter:blur(8px); -webkit-backdrop-filter:blur(8px); z-index:99999; align-items:flex-start; justify-content:center; padding-top:72px;">
  <div onclick="event.stopPropagation()" style="background:#ffffff; border-radius:12px; width:100%; max-width:600px; box-shadow:0 32px 64px -12px rgba(0,0,0,0.25), 0 0 0 1px rgba(0,0,0,0.06); overflow:hidden; margin:0 16px;">

    <!-- Search Input Row -->
    <div style="display:flex; align-items:center; padding:0 18px; height:56px; border-bottom:1px solid #f1f5f9; gap:12px;">
      <i class="ri-search-line" style="color:#64748b; font-size:1.1rem; flex-shrink:0;"></i>
      <input id="navSearchInput" type="text" placeholder="Cari menu atau halaman..."
        oninput="filterNavSearch()" onkeydown="handleNavSearchKey(event)"
        style="flex:1; border:none; outline:none; font-size:0.975rem; color:#0f172a; background:transparent; font-weight:400; letter-spacing:-0.01em;">
      <kbd onclick="closeNavSearch()" style="background:#f1f5f9; border:1px solid #e2e8f0; border-radius:6px; padding:3px 8px; font-size:0.72rem; color:#64748b; cursor:pointer; font-family:inherit; white-space:nowrap; flex-shrink:0;">Esc</kbd>
    </div>

    <!-- Results List -->
    <div id="navSearchResults" style="max-height:380px; overflow-y:auto;"></div>

    <!-- Footer Hints -->
    <div style="display:flex; align-items:center; gap:20px; padding:9px 18px; border-top:1px solid #f1f5f9; background:#fafafa;">
      <span style="display:flex; align-items:center; gap:5px; font-size:0.75rem; color:#64748b;">
        <kbd style="display:inline-flex; align-items:center; justify-content:center; background:#fff; border:1px solid #e2e8f0; border-bottom-width:2px; border-radius:5px; padding:2px 6px; font-size:0.7rem; color:#374151; font-family:inherit; line-height:1.4;">↑↓</kbd>
        <span>Navigasi</span>
      </span>
      <span style="display:flex; align-items:center; gap:5px; font-size:0.75rem; color:#64748b;">
        <kbd style="display:inline-flex; align-items:center; justify-content:center; background:#fff; border:1px solid #e2e8f0; border-bottom-width:2px; border-radius:5px; padding:2px 6px; font-size:0.7rem; color:#374151; font-family:inherit; line-height:1.4;">↵</kbd>
        <span>Buka</span>
      </span>
      <span style="display:flex; align-items:center; gap:5px; font-size:0.75rem; color:#64748b;">
        <kbd style="display:inline-flex; align-items:center; justify-content:center; background:#fff; border:1px solid #e2e8f0; border-bottom-width:2px; border-radius:5px; padding:2px 6px; font-size:0.7rem; color:#374151; font-family:inherit; line-height:1.4;">⌘K</kbd>
        <span>Tutup</span>
      </span>
    </div>

  </div>
</div>


<script>
@php
  $navUserRole = null;
  $navUser = Auth::user() ?? (Auth::guard('customer')->check() ? Auth::guard('customer')->user() : null);
  if ($navUser) {
    $navUserRole = strtolower($navUser->role ?? 'customer');
  }
@endphp
const CURRENT_USER_ROLE = '{{ $navUserRole ?? "" }}';

const NAV_MENU_ITEMS = [
  { name: 'Dashboard', url: '/dashboard', parent: 'Dashboard', roles: ['administrator','admin'] },
  { name: 'List Tagihan', url: '/dashboard/admin/tagihan', parent: 'Tagihan', roles: ['administrator','admin'] },
  { name: 'Verifikasi Tagihan', url: '/dashboard/admin/tagihan/proses', parent: 'Tagihan', roles: ['administrator','admin','verifikasi'] },
  { name: 'List Pembayaran Lunas', url: '/dashboard/admin/tagihan/lunas', parent: 'Tagihan', roles: ['administrator','admin'] },
  { name: 'Outstanding Tagihan', url: '/dashboard/admin/tagihan/ostanding', parent: 'Tagihan', roles: ['administrator','admin'] },
  { name: 'Push Notification', url: '/tagihan/push-notification', parent: 'Tagihan', roles: ['administrator','admin'] },
  { name: 'Status Baca Tagihan', url: '/dashboard/admin/tagihan/status-baca', parent: 'Tagihan', roles: ['administrator','admin'] },
  { name: 'List Karyawan', url: '/dashboard/admin/employees', parent: 'Karyawan', roles: ['administrator'] },
  { name: 'Upload Excel Karyawan', url: '/dashboard/admin/employees/upload/data', parent: 'Karyawan', roles: ['administrator'] },
  { name: 'List Gaji', url: '/dashboard/admin/salary', parent: 'Gaji Karyawan', roles: ['administrator'] },
  { name: 'List Pelanggan', url: '/dashboard/admin/pelanggan', parent: 'Pelanggan', roles: ['administrator','admin'] },
  { name: 'Upload Excel Pelanggan', url: '/dashboard/admin/pelanggan/upload/data', parent: 'Pelanggan', roles: ['administrator','admin'] },
  { name: 'List Paket', url: '/dashboard/admin/paket', parent: 'Paket', roles: ['administrator','admin'] },
  { name: 'List Akun', url: '/dashboard/admin/users', parent: 'Akun', roles: ['administrator'] },
  { name: 'List Absensi', url: '/dashboard/admin/data/absensi', parent: 'Absensi', roles: ['administrator','admin'] },
  { name: 'List Ticket', url: '/dashboard/cs/tickets', parent: 'Ticket', roles: ['customer_service'] },
  { name: 'Proses Ticket', url: '/dashboard/cs/tickets/finished', parent: 'Ticket', roles: ['customer_service'] },
  { name: 'Ticket Selesai', url: '/dashboard/cs/tickets/approved', parent: 'Ticket', roles: ['customer_service'] },
  { name: 'History Ticket', url: '/dashboard/admin/history/tickets', parent: 'History Ticket', roles: ['customer_service'] },
  { name: 'History Login', url: '/dashboard/admin/history/login', parent: 'History Ticket', roles: ['customer_service'] },
  { name: 'Laporan Tagihan', url: '/dashboard/admin/laporan/tagihan', parent: 'Laporan', roles: ['administrator','admin'] },
  { name: 'Laporan Kwitansi', url: '/dashboard/admin/laporan/tagihan/kwitansi', parent: 'Laporan', roles: ['administrator','admin'] },
  { name: 'Laporan Harian', url: '/dashboard/admin/laporan/harian', parent: 'Laporan', roles: ['administrator','admin'] },
  { name: 'Pekerjaan', url: '/dashboard/teknisi/jobs', parent: 'Pekerjaan', roles: ['team'] },
  { name: 'Pekerjaan Selesai', url: '/dashboard/teknisi/jobs/approved-jobs', parent: 'Pekerjaan Selesai', roles: ['team'] },
  { name: 'Laba Masuk', url: '/dashboard/admin/incomes', parent: 'Administrasi', roles: ['administrator','admin'] },
  { name: 'Laba Keluar', url: '/dashboard/admin/expenses', parent: 'Administrasi', roles: ['administrator','admin'] },
  { name: 'Kas Registrasi', url: '/dashboard/admin/kas-registrasi', parent: 'Administrasi', roles: ['administrator','admin'] },
  { name: 'Buku Pembantu', url: '/dashboard/admin/pembukuan/masuk', parent: 'Buku Besar', roles: ['administrator'] },
  { name: 'Tutup Buku', url: '/dashboard/admin/pembukuan/total', parent: 'Buku Besar', roles: ['administrator'] },
  { name: 'Status Pelanggan', url: '/dashboard/admin/pelanggan/status', parent: 'Status', roles: ['administrator','admin'] },
  { name: 'Push Iklan', url: '/dashboard/admin/iklan', parent: 'Iklan', roles: ['administrator'] },
  { name: 'Rekening', url: '/dashboard/admin/rekenings', parent: 'Rekening', roles: ['administrator'] },
  { name: 'Backup Database', url: '/dashboard/admin/backup', parent: 'Backup', roles: ['administrator'] },
  { name: 'Chat', url: '/dashboard/admin/chat', parent: 'Chat Admin', roles: ['customer_service'] },
  { name: 'Barang', url: '/dashboard/admin/barangs', parent: 'Barang', roles: ['logistic'] },
  { name: 'Barang Masuk', url: '/dashboard/admin/barang-masuks', parent: 'Barang', roles: ['logistic'] },
  { name: 'Barang Keluar', url: '/dashboard/admin/barang-keluar', parent: 'Barang', roles: ['logistic'] },
  { name: 'Laporan Kabel', url: '/dashboard/logistik/laporan-kabel', parent: 'Laporan Kabel', roles: ['logistic'] },
  { name: 'List Pelanggan Marketing', url: '/dashboard/marketing/pelanggan', parent: 'Pelanggan', roles: ['marketing'] },
  { name: 'Belum Diproses', url: '/dashboard/marketing/pelanggan?progres=Belum%20Diproses', parent: 'Pelanggan', roles: ['marketing'] },
  { name: 'Tarik Kabel', url: '/dashboard/marketing/pelanggan?progres=Tarik%20Kabel', parent: 'Pelanggan', roles: ['marketing'] },
  { name: 'Aktivasi', url: '/dashboard/marketing/pelanggan?progres=Aktivasi', parent: 'Pelanggan', roles: ['marketing'] },
  { name: 'Registrasi', url: '/dashboard/marketing/pelanggan?progres=Registrasi', parent: 'Pelanggan', roles: ['marketing'] },
  { name: 'Absensi Karyawan', url: '/dashboard/karyawan/absensi', parent: 'Absensi', roles: ['karyawan'] },
  { name: 'Verifikasi Tagihan (Kasir)', url: '/dashboard/verifikasi/tagihan', parent: 'Verifikasi', roles: ['verifikasi'] },
];

// Filter items by current user's role
const ALLOWED_MENU_ITEMS = CURRENT_USER_ROLE
  ? NAV_MENU_ITEMS.filter(item => item.roles.includes(CURRENT_USER_ROLE))
  : [];

let navSearchActive = -1;

function openNavSearch() {
  const overlay = document.getElementById('navSearchOverlay');
  overlay.style.display = 'flex';
  setTimeout(() => document.getElementById('navSearchInput').focus(), 50);
  navSearchActive = -1;
  renderNavResults(ALLOWED_MENU_ITEMS);
}

function closeNavSearch() {
  document.getElementById('navSearchOverlay').style.display = 'none';
  document.getElementById('navSearchInput').value = '';
  navSearchActive = -1;
}

function filterNavSearch() {
  const q = document.getElementById('navSearchInput').value.toLowerCase().trim();
  navSearchActive = -1;
  const filtered = q ? ALLOWED_MENU_ITEMS.filter(item =>
    item.name.toLowerCase().includes(q) || item.parent.toLowerCase().includes(q)
  ) : ALLOWED_MENU_ITEMS;
  renderNavResults(filtered);
}

function renderNavResults(items) {
  const container = document.getElementById('navSearchResults');
  if (!items.length) {
    container.innerHTML = `
      <div style="padding:32px 20px; text-align:center;">
        <i class="ri-search-line" style="font-size:2rem; color:#cbd5e1; display:block; margin-bottom:8px;"></i>
        <div style="color:#94a3b8; font-size:0.875rem;">Tidak ada hasil ditemukan</div>
      </div>`;
    return;
  }
  container.innerHTML = `<div style="padding:6px 0;">` + items.map((item, i) => `
    <a href="${item.url}" class="nav-search-item" data-index="${i}"
      style="display:flex; align-items:center; gap:12px; padding:9px 16px; text-decoration:none; color:#0f172a; border-left:2px solid transparent; transition:all 0.1s;"
      onmouseenter="setNavActive(${i})"
      onclick="closeNavSearch()">
      <div style="width:34px; height:34px; border-radius:8px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
        <i class="ri-link" style="color:#64748b; font-size:0.95rem;"></i>
      </div>
      <div style="flex:1; min-width:0;">
        <div style="font-weight:500; font-size:0.875rem; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.name}</div>
        <div style="font-size:0.75rem; color:#94a3b8; margin-top:1px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.url}</div>
      </div>
      <span style="background:#f1f5f9; border:1px solid #e2e8f0; border-radius:5px; padding:2px 8px; font-size:0.7rem; font-weight:500; color:#475569; white-space:nowrap; flex-shrink:0;">${item.parent}</span>
    </a>
  `).join('') + `</div>`;
}

function setNavActive(index) {
  navSearchActive = index;
  document.querySelectorAll('.nav-search-item').forEach((el, i) => {
    el.style.background = i === index ? '#f8fafc' : '';
    el.style.borderLeft = i === index ? '3px solid #0f172a' : '3px solid transparent';
  });
}

function handleNavSearchKey(e) {
  const items = document.querySelectorAll('.nav-search-item');
  if (e.key === 'ArrowDown') { e.preventDefault(); setNavActive(Math.min(navSearchActive + 1, items.length - 1)); }
  else if (e.key === 'ArrowUp') { e.preventDefault(); setNavActive(Math.max(navSearchActive - 1, 0)); }
  else if (e.key === 'Enter' && navSearchActive >= 0) { items[navSearchActive].click(); }
  else if (e.key === 'Escape') { closeNavSearch(); }
}

document.addEventListener('keydown', function(e) {
  if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
    e.preventDefault();
    openNavSearch();
  }
  if (e.key === 'Escape' && document.getElementById('navSearchOverlay').style.display === 'flex') {
    closeNavSearch();
  }
});
</script>
