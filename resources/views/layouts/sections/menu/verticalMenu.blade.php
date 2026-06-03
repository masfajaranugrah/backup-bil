@php
  use Illuminate\Support\Facades\Route;
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Str;

  $user = Auth::check() ? Auth::user() : (Auth::guard('customer')->check() ? Auth::guard('customer')->user() : null);
  $currentUserRole = $user ? strtolower($user->role ?? 'customer') : '';
  $isCustomerGuard = Auth::guard('customer')->check();
  $logoutRoute = $isCustomerGuard ? 'customer.logout' : 'logout';
  $profileUrl = Route::has('profile.edit') ? route('profile.edit') : url('/');

  $menuData = $menuData ?? [];

  $muiIconMap = [
    'ri-dashboard-3-line' => 'space_dashboard',
    'ri-bill-line' => 'receipt_long',
    'ri-file-list-3-line' => 'format_list_bulleted',
    'ri-checkbox-circle-line' => 'check_circle',
    'ri-checkbox-circle-fill' => 'check_circle',
    'ri-check-double-line' => 'done_all',
    'ri-notification-3-line' => 'notifications',
    'ri-archive-drawer-line' => 'inventory_2',
    'ri-box-3-line' => 'inventory',
    'ri-inbox-line' => 'inbox',
    'ri-logout-box-line' => 'logout',
    'ri-file-chart-line' => 'insert_chart',
    'ri-list-check' => 'checklist',
    'ri-list-check-2' => 'checklist',
    'ri-group-line' => 'groups',
    'ri-user-settings-line' => 'manage_accounts',
    'ri-file-excel-2-line' => 'table_view',
    'ri-file-excel-line' => 'table_view',
    'ri-wallet-3-line' => 'account_balance_wallet',
    'ri-money-dollar-circle-line' => 'monetization_on',
    'ri-account-circle-line' => 'account_circle',
    'ri-user-search-line' => 'person_search',
    'ri-gift-line' => 'redeem',
    'ri-stack-line' => 'layers',
    'ri-admin-line' => 'admin_panel_settings',
    'ri-user-star-line' => 'verified_user',
    'ri-calendar-check-line' => 'event_available',
    'ri-calendar-todo-line' => 'event_note',
    'ri-customer-service-2-line' => 'support_agent',
    'ri-ticket-2-line' => 'confirmation_number',
    'ri-time-line' => 'history',
    'ri-file-history-line' => 'history_edu',
    'ri-login-circle-line' => 'login',
    'ri-fingerprint-line' => 'fingerprint',
    'ri-user-received-line' => 'how_to_reg',
    'ri-bar-chart-box-line' => 'bar_chart',
    'ri-receipt-line' => 'receipt',
    'ri-tools-line' => 'construction',
    'ri-task-line' => 'task',
    'ri-checkbox-multiple-line' => 'check_box',
    'ri-funds-line' => 'payments',
    'ri-arrow-down-circle-line' => 'arrow_circle_down',
    'ri-arrow-up-circle-line' => 'arrow_circle_up',
    'ri-book-open-line' => 'menu_book',
    'ri-book-mark-line' => 'bookmarks',
    'ri-calculator-line' => 'calculate',
    'ri-file-text-line' => 'description',
    'ri-file-info-line' => 'info',
    'ri-bank-card-line' => 'credit_card',
    'ri-bank-line' => 'account_balance',
    'ri-database-2-line' => 'database',
    'ri-save-3-line' => 'save',
    'ri-signal-tower-line' => 'network_cell',
    'ri-pulse-line' => 'monitor_heart',
    'ri-megaphone-line' => 'campaign',
    'ri-broadcast-line' => 'podcasts',
    'ri-user-heart-line' => 'favorite',
    'ri-contacts-line' => 'contacts',
    'ri-git-commit-line' => 'commit',
    'ri-flashlight-line' => 'flashlight_on',
    'ri-line-chart-line' => 'show_chart',
    'ri-dashboard-line' => 'dashboard',
    'ri-message-3-line' => 'chat',
    'ri-chat-3-line' => 'chat_bubble',
    'ri-cloud-line' => 'cloud',
    'ri-terminal-box-line' => 'terminal'
  ];

  $fallbackIconMap = [
    'dashboard' => 'space_dashboard',
    'tagihan' => 'receipt_long',
    'karyawan' => 'groups',
    'gaji karyawan' => 'account_balance_wallet',
    'pelanggan' => 'account_circle',
    'paket' => 'redeem',
    'akun' => 'admin_panel_settings',
    'absensi' => 'event_available',
    'laporan' => 'bar_chart',
    'administrasi' => 'payments',
    'buku besar' => 'menu_book',
    'rekening' => 'credit_card',
    'backup' => 'database',
    'status' => 'network_cell',
    'iklan' => 'campaign',
    'chat pembayaran' => 'chat',
    'cloud' => 'cloud',
    'console pushjob' => 'terminal',
  ];

  $resolveMuiIcon = function ($iconClass, $fallbackName = null) use ($muiIconMap, $fallbackIconMap) {
    if (preg_match('/ri-[a-z0-9-]+/', $iconClass, $matches)) {
      $riClass = $matches[0];
      return $muiIconMap[$riClass] ?? 'radio_button_unchecked';
    }
    $key = strtolower(trim((string) $fallbackName));
    if ($key !== '') {
      return $fallbackIconMap[$key] ?? 'radio_button_unchecked';
    }
    return 'radio_button_unchecked';
  };

  $userName = $user->name ?? $user->nama_lengkap ?? 'User';
  $userInitials = strtoupper(implode('', array_map(fn($w) => $w[0] ?? '', array_slice(explode(' ', trim($userName)), 0, 2))));

  // Role details
  $roleBadgeMap = [
    'administrator' => ['color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.1)', 'label' => 'Admin'],
    'admin'         => ['color' => '#f59e0b', 'bg' => 'rgba(245,158,11,0.1)', 'label' => 'Admin'],
    'operator'      => ['color' => '#0ea5e9', 'bg' => 'rgba(14,165,233,0.1)', 'label' => 'Operator'],
    'customer'      => ['color' => '#10b981', 'bg' => 'rgba(16,185,129,0.1)', 'label' => 'Customer'],
    'karyawan'      => ['color' => '#8b5cf6', 'bg' => 'rgba(139,92,246,0.1)', 'label' => 'Karyawan'],
  ];
  $roleMeta = $roleBadgeMap[$currentUserRole] ?? ['color' => '#6b7280', 'bg' => 'rgba(107,114,128,0.08)', 'label' => ucfirst($currentUserRole ?: 'Guest')];

  // Gather all registered submenu URLs to perform highly specific mathematical matching (Solves multi-active bug)
  $submenuUrls = [];
  if (!empty($menuData) && isset($menuData[0]->menu)) {
    foreach ($menuData[0]->menu as $m) {
      if (isset($m->submenu)) {
        foreach ($m->submenu as $sub) {
          $u = trim($sub->url ?? '', '/');
          if (!empty($u)) {
            $submenuUrls[] = '/' . $u;
          }
        }
      }
    }
  }

  // Precision Route Match Helper (Solves multiple active items matching placeholder slugs)
  $isRouteActive = function ($menuItem) use ($submenuUrls) {
    $currentPath = '/' . trim(request()->path(), '/');
    $itemUrl = trim($menuItem->url ?? '', '/');

    if (empty($itemUrl) || $itemUrl === 'javascript:void(0);' || $itemUrl === '#') {
      return false;
    }

    $itemUrl = '/' . $itemUrl;

    // 1. Exact Match
    if ($currentPath === $itemUrl) {
      return true;
    }

    // 2. Specific Prefix Match
    if (Str::startsWith($currentPath, $itemUrl . '/')) {
      // If there is any other registered URL in the menu that is a closer match (longer matching prefix),
      // then that longer/more specific URL is the active one, not this one!
      foreach ($submenuUrls as $otherUrl) {
        if ($otherUrl !== $itemUrl && Str::startsWith($currentPath, $otherUrl) && strlen($otherUrl) > strlen($itemUrl)) {
          return false;
        }
      }
      return true;
    }

    return false;
  };
@endphp

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">

<style>
/* ================================================================
   PREMIUM LIGHT SIDEBAR — Aesthetic SaaS Dashboard (Style 1)
   Ultra Clean · Lavender/Soft Purple Ambient Glow · Minimalist
   ================================================================ */

:root {
  --sb-font: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif;
  --sb-layout-expanded: 16.5rem;
  --sb-layout-collapsed: 5rem;
}

@media (min-width: 1200px) {
  html .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-container {
    min-height: 100vh;
  }

  html:not(.layout-menu-collapsed) .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-page {
    margin-left: 0 !important;
    padding-left: var(--sb-layout-expanded) !important;
    width: 100% !important;
    transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }

  html.layout-menu-collapsed .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-page {
    margin-left: 0 !important;
    padding-left: var(--sb-layout-collapsed) !important;
    width: 100% !important;
    transition: padding-left 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }

  html .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-container:has(> #layout-menu.sb-clean-light.sb-collapsed) .layout-page {
    margin-left: 0 !important;
    padding-left: var(--sb-layout-collapsed) !important;
    width: 100% !important;
  }

  html .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-container:has(> #layout-menu.sb-clean-light:not(.sb-collapsed)) .layout-page {
    margin-left: 0 !important;
    padding-left: var(--sb-layout-expanded) !important;
    width: 100% !important;
  }

  html.layout-menu-collapsed .layout-wrapper.layout-content-navbar:not(.layout-without-menu) #layout-menu.sb-clean-light {
    width: var(--sb-layout-collapsed) !important;
    min-width: var(--sb-layout-collapsed) !important;
    max-width: var(--sb-layout-collapsed) !important;
  }

  html.layout-menu-hover.layout-menu-collapsed .layout-wrapper.layout-content-navbar:not(.layout-without-menu) #layout-menu.sb-clean-light,
  html.layout-menu-hover .layout-wrapper.layout-content-navbar:not(.layout-without-menu) #layout-menu.sb-clean-light.sb-collapsed {
    width: var(--sb-layout-collapsed) !important;
    min-width: var(--sb-layout-collapsed) !important;
    max-width: var(--sb-layout-collapsed) !important;
    overflow: hidden !important;
  }

  html.layout-menu-hover.layout-menu-collapsed .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-page {
    margin-left: 0 !important;
    padding-left: var(--sb-layout-collapsed) !important;
    width: 100% !important;
  }

  html.layout-menu-hover.layout-menu-collapsed .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-navbar {
    left: var(--sb-layout-collapsed) !important;
    width: calc(100% - var(--sb-layout-collapsed)) !important;
    margin-left: 0 !important;
  }

  html:not(.layout-menu-collapsed) .layout-wrapper.layout-content-navbar:not(.layout-without-menu) #layout-menu.sb-clean-light {
    width: var(--sb-layout-expanded) !important;
    min-width: var(--sb-layout-expanded) !important;
    max-width: var(--sb-layout-expanded) !important;
  }

  html.layout-menu-collapsed .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-navbar {
    left: var(--sb-layout-collapsed) !important;
    width: calc(100% - var(--sb-layout-collapsed)) !important;
    margin-left: 0 !important;
    transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }

  html .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-container:has(> #layout-menu.sb-clean-light.sb-collapsed) .layout-navbar {
    left: var(--sb-layout-collapsed) !important;
    width: calc(100% - var(--sb-layout-collapsed)) !important;
    margin-left: 0 !important;
  }

  html .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-container:has(> #layout-menu.sb-clean-light:not(.sb-collapsed)) .layout-navbar {
    left: var(--sb-layout-expanded) !important;
    width: calc(100% - var(--sb-layout-expanded)) !important;
    margin-left: 0 !important;
  }

  html:not(.layout-menu-collapsed) .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-navbar {
    left: var(--sb-layout-expanded) !important;
    width: calc(100% - var(--sb-layout-expanded)) !important;
    margin-left: 0 !important;
    transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
  }
}

@media (max-width: 1199.98px) {
  html .layout-wrapper.layout-content-navbar:not(.layout-without-menu) .layout-page {
    padding-left: 0 !important;
  }

  html:not(.layout-menu-expanded) #layout-menu.sb-clean-light {
    transform: translateX(-100%) !important;
    visibility: hidden !important;
    pointer-events: none !important;
  }

  html.layout-menu-expanded #layout-menu.sb-clean-light {
    transform: translateX(0) !important;
    visibility: visible !important;
    pointer-events: auto !important;
    z-index: 1081 !important;
  }

  html.layout-menu-expanded .layout-overlay {
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    z-index: 1080 !important;
  }
}

/* ---- Core Variables ---- */
#layout-menu.sb-clean-light {
  --sb-w: 16.5rem;
  --sb-w-mini: 5rem;

  /* Frosted glass theme palette */
  --sb-bg-base:    #eef4fb;
  --sb-bg-card:    rgba(255, 255, 255, 0.54);
  --sb-bg-hover:   rgba(255, 255, 255, 0.58);
  --sb-bg-active:  rgba(255, 255, 255, 0.72);
  --sb-border:     rgba(148, 163, 184, 0.28);

  --sb-txt-1:  #111827;
  --sb-txt-2:  #334155;
  --sb-txt-3:  #64748b;

  --sb-accent:      #6366f1;
  --sb-ease: cubic-bezier(0.4, 0, 0.2, 1);
  --sb-dur:  0.22s;
  --sb-dur-slow: 0.3s;

  /* Layout */
  width: var(--sb-w) !important;
  height: 100% !important;
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  z-index: 1030 !important;
  display: flex !important;
  flex-direction: column !important;
  overflow: hidden !important;

  /* Frosted panel backdrop so glass states have visible contrast */
  background:
    linear-gradient(135deg, rgba(14, 165, 233, 0.18) 0%, rgba(255, 255, 255, 0.1) 33%, rgba(99, 102, 241, 0.14) 68%, rgba(20, 184, 166, 0.16) 100%),
    linear-gradient(180deg, rgba(248, 250, 252, 0.82) 0%, rgba(226, 232, 240, 0.68) 48%, rgba(241, 245, 249, 0.86) 100%),
    #e8eef7 !important;
  border-right: 1px solid var(--sb-border) !important;
  box-shadow:
    inset -1px 0 0 rgba(255, 255, 255, 0.5),
    8px 0 30px rgba(15, 23, 42, 0.08) !important;
  backdrop-filter: blur(18px) saturate(155%);
  -webkit-backdrop-filter: blur(18px) saturate(155%);

  font-family: var(--sb-font) !important;
  color: var(--sb-txt-1) !important;
  transition: width var(--sb-dur-slow) var(--sb-ease), box-shadow var(--sb-dur-slow) var(--sb-ease) !important;
}

/* Shell */
#layout-menu.sb-clean-light .sb-shell {
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  height: 100%;
  min-height: 0;
}

/* ================================================================
   BRAND / LOGO AREA
   ================================================================ */
#layout-menu.sb-clean-light .sb-brand {
  display: flex;
  align-items: center;
  gap: 0.8rem;
  padding: 1.25rem 1.05rem 1rem;
  flex-shrink: 0;
  transition: padding var(--sb-dur-slow) var(--sb-ease), justify-content var(--sb-dur-slow) var(--sb-ease);
}

#layout-menu.sb-clean-light .sb-logo-mark {
  width: 2.55rem;
  height: 2.55rem;
  min-width: 2.55rem;
  border-radius: 0.95rem;
  display: inline-flex !important;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  background: #ffffff;
  border: 1px solid rgba(226, 232, 240, 0.9);
  box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
  transition: transform var(--sb-dur) var(--sb-ease);
}

#layout-menu.sb-clean-light .sb-logo-img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  padding: 0.35rem;
}

#layout-menu.sb-clean-light .sb-logo-circle {
  background: #FACC15;
  width: 1.6rem;
  height: 1.6rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

#layout-menu.sb-clean-light .sb-logo-bolt {
  width: 0.95rem;
  height: 0.95rem;
  color: #111111;
}

#layout-menu.sb-clean-light .sb-logo-mark:hover {
  transform: scale(1.05);
}

#layout-menu.sb-clean-light .sb-brand-body {
  flex: 1;
  min-width: 0;
  overflow: hidden;
  transition: opacity var(--sb-dur-slow) var(--sb-ease), width var(--sb-dur-slow) var(--sb-ease);
}

#layout-menu.sb-clean-light .sb-brand-name {
  display: block;
  color: var(--sb-txt-1);
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.2;
}

#layout-menu.sb-clean-light .sb-brand-sub {
  display: none !important;
}

/* Collapse Panel Button (Styling based on Image 2) */
#layout-menu.sb-clean-light .sb-icon-btn {
  width: 2.1rem;
  height: 2.1rem;
  min-width: 2.1rem;
  border: 1px solid var(--sb-border);
  padding: 0;
  background: #ffffff;
  color: var(--sb-txt-2);
  border-radius: 0.6rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all var(--sb-dur) var(--sb-ease);
  flex-shrink: 0;
  box-shadow: 0 2px 6px rgba(0,0,0,0.02);
}

#layout-menu.sb-clean-light .sb-icon-btn:hover {
  background: var(--sb-bg-hover);
  color: var(--sb-txt-1);
  border-color: rgba(0, 0, 0, 0.1);
  transform: translateY(-1px);
}

#layout-menu.sb-clean-light .sb-icon-btn .material-symbols-rounded {
  font-size: 20px;
  font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
}

#layout-menu.sb-clean-light .sb-icon-btn.sb-mobile-close {
  display: none;
}

#layout-menu.sb-clean-light .sb-icon-btn.sb-desktop-only {
  display: none !important;
}

/* ================================================================
   USER PROFILE SECTION (Clean integration)
   ================================================================ */
#layout-menu.sb-clean-light .sb-profile {
  display: none !important;
}

#layout-menu.sb-clean-light .sb-profile:hover {
  background: var(--sb-bg-hover);
}

#layout-menu.sb-clean-light .sb-avatar {
  width: 2.1rem;
  height: 2.1rem;
  min-width: 2.1rem;
  border-radius: 0.65rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
  color: #4f46e5;
  background: #e0e7ff;
  flex-shrink: 0;
  text-transform: uppercase;
}

#layout-menu.sb-clean-light .sb-profile-info {
  flex: 1;
  min-width: 0;
  overflow: hidden;
  transition: opacity var(--sb-dur-slow) var(--sb-ease), width var(--sb-dur-slow) var(--sb-ease);
}

#layout-menu.sb-clean-light .sb-profile-name {
  display: block;
  color: var(--sb-txt-1);
  font-size: 0.8rem;
  font-weight: 700;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  line-height: 1.25;
}

#layout-menu.sb-clean-light .sb-role-badge {
  display: inline-flex;
  align-items: center;
  margin-top: 0.15rem;
  padding: 0.05rem 0.4rem;
  border-radius: 999px;
  font-size: 0.58rem;
  font-weight: 700;
  letter-spacing: 0.03em;
  text-transform: uppercase;
  white-space: nowrap;
}

/* ================================================================
   NAVIGATION MENU SECTION
   ================================================================ */
#layout-menu.sb-clean-light .menu-inner-shadow {
  display: none !important;
}

#layout-menu.sb-clean-light .menu-inner {
  flex: 1;
  min-height: 0;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 0.2rem 0.8rem 1rem !important;
  margin: 0 !important;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

#layout-menu.sb-clean-light .menu-inner::-webkit-scrollbar {
  width: 0;
  height: 0;
  display: none;
}

/* Section Header - Extremely Clean & Minimal */
#layout-menu.sb-clean-light .menu-header {
  margin: 1.4rem 0.5rem 0.4rem;
  padding: 0;
  list-style: none;
}

#layout-menu.sb-clean-light .menu-header-text {
  color: var(--sb-txt-3) !important;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.02em;
  white-space: nowrap;
  overflow: hidden;
  display: block;
}

/* ================================================================
   MENU ITEM & LINK
   ================================================================ */
#layout-menu.sb-clean-light .menu-item {
  margin: 0.15rem 0;
  list-style: none;
  position: relative;
}

#layout-menu.sb-clean-light .menu-link {
  display: flex;
  align-items: center;
  gap: 0;
  padding: 0.5rem 0.75rem;
  min-height: 2.5rem;
  border-radius: 0.7rem;
  border: 1px solid transparent;
  background: rgba(255, 255, 255, 0.10);
  color: var(--sb-txt-2) !important;
  text-decoration: none !important;
  font-size: 0.9rem;
  font-weight: 500;
  letter-spacing: 0;
  line-height: 1.25;
  position: relative;
  isolation: isolate;
  overflow: hidden;
  transition:
    color var(--sb-dur) var(--sb-ease),
    background var(--sb-dur) var(--sb-ease),
    box-shadow var(--sb-dur) var(--sb-ease),
    transform var(--sb-dur) var(--sb-ease),
    border-color var(--sb-dur) var(--sb-ease);
  cursor: pointer;
}

#layout-menu.sb-clean-light .menu-link::before {
  content: '';
  position: absolute;
  inset: 1px;
  z-index: -1;
  border-radius: inherit;
  background:
    linear-gradient(135deg, rgba(255,255,255,0.92), rgba(255,255,255,0.26) 46%, rgba(255,255,255,0.72)),
    radial-gradient(circle at 18% 0%, rgba(255,255,255,0.92), transparent 36%),
    radial-gradient(circle at 100% 100%, rgba(14,165,233,0.28), transparent 46%);
  opacity: 0;
  transition: opacity var(--sb-dur) var(--sb-ease);
  pointer-events: none;
}

/* Hover State */
#layout-menu.sb-clean-light .menu-link:hover {
  color: var(--sb-txt-1) !important;
  background:
    linear-gradient(135deg, rgba(255,255,255,0.76), rgba(255,255,255,0.36)),
    rgba(14, 165, 233, 0.10) !important;
  border-color: rgba(255, 255, 255, 0.86) !important;
  box-shadow:
    inset 0 1px 0 rgba(255, 255, 255, 0.95),
    inset 0 -1px 0 rgba(15, 23, 42, 0.04),
    0 12px 30px rgba(14, 165, 233, 0.12),
    0 8px 22px rgba(15, 23, 42, 0.10) !important;
  transform: translateY(-1px);
  backdrop-filter: blur(16px) saturate(155%);
  -webkit-backdrop-filter: blur(16px) saturate(155%);
}

#layout-menu.sb-clean-light .menu-link:hover::before {
  opacity: 1;
}

/* Active State - glass surface with subtle iOS-style depth */
#layout-menu.sb-clean-light .menu-item.active > .menu-link {
  color: var(--sb-txt-1) !important;
  background:
    linear-gradient(135deg, rgba(255,255,255,0.86), rgba(255,255,255,0.44)),
    linear-gradient(135deg, rgba(14,165,233,0.22), rgba(99,102,241,0.16)) !important;
  font-weight: 600;
  border: 1px solid rgba(255, 255, 255, 0.92) !important;
  box-shadow:
    inset 0 1px 0 rgba(255, 255, 255, 0.95),
    inset 0 -1px 0 rgba(15, 23, 42, 0.05),
    0 16px 38px rgba(14, 165, 233, 0.16),
    0 10px 26px rgba(15, 23, 42, 0.12),
    0 0 0 1px rgba(255, 255, 255, 0.28) !important;
  backdrop-filter: blur(18px) saturate(170%);
  -webkit-backdrop-filter: blur(18px) saturate(170%);
}

#layout-menu.sb-clean-light .menu-item.active > .menu-link::before {
  opacity: 1;
}

/* Open State */
#layout-menu.sb-clean-light .menu-item.open > .menu-link {
  color: var(--sb-txt-2) !important;
  background: transparent !important;
  font-weight: 500;
}

#layout-menu.sb-clean-light .menu-item.active.open > .menu-link {
  color: var(--sb-txt-1) !important;
  background:
    linear-gradient(135deg, rgba(255,255,255,0.86), rgba(255,255,255,0.44)),
    linear-gradient(135deg, rgba(14,165,233,0.22), rgba(99,102,241,0.16)) !important;
  font-weight: 600;
}

/* ================================================================
   MENU ICONS - Thin outlines (Weight 300)
   ================================================================ */
#layout-menu.sb-clean-light .sb-icon {
  width: 1.8rem;
  height: 1.8rem;
  min-width: 1.8rem;
  border-radius: 0.5rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.65rem;
  flex-shrink: 0;
  color: var(--sb-txt-2) !important;
  transition: color var(--sb-dur) var(--sb-ease), margin var(--sb-dur-slow) var(--sb-ease);
}

#layout-menu.sb-clean-light .sb-icon .material-symbols-rounded {
  font-size: 1.14rem;
  font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
}

#layout-menu.sb-clean-light .menu-link:hover .sb-icon {
  color: var(--sb-txt-1) !important;
  opacity: 1 !important;
}

#layout-menu.sb-clean-light .menu-link:hover .sb-icon .material-symbols-rounded {
  opacity: 1 !important;
  visibility: visible !important;
}

#layout-menu.sb-clean-light .menu-item.active > .menu-link .sb-icon {
  color: var(--sb-txt-1) !important;
}

/* Label */
#layout-menu.sb-clean-light .sb-label {
  flex: 1;
  min-width: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  transition: opacity var(--sb-dur-slow) var(--sb-ease), width var(--sb-dur-slow) var(--sb-ease);
}

/* Chevron indicator */
#layout-menu.sb-clean-light .sb-chevron {
  color: var(--sb-txt-3) !important;
  font-size: 16px !important;
  font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 20;
  transition: transform var(--sb-dur-slow) var(--sb-ease);
  margin-left: 0.25rem;
  flex-shrink: 0;
}

#layout-menu.sb-clean-light .menu-item.open > .menu-link .sb-chevron {
  transform: rotate(90deg);
  color: var(--sb-txt-1) !important;
}

/* Badge */
#layout-menu.sb-clean-light .sb-badge {
  font-size: 0.62rem;
  font-weight: 700;
  padding: 0.15rem 0.45rem;
  border-radius: 999px;
  margin-left: 0.3rem;
}

/* ================================================================
   SUBMENU — TREE INDICATOR SYSTEM (Image 2 style)
   ================================================================ */
#layout-menu.sb-clean-light .menu-sub {
  list-style: none !important;
  margin: 0.1rem 0.65rem 0.1rem 1.85rem !important; /* Indent left to align, and pad right to prevent touching the edge */
  padding: 0 !important;
  position: relative !important;
}

/* Vertical line connecting all submenu items, stopping at the last branch precisely */
#layout-menu.sb-clean-light .menu-sub::before {
  content: '' !important;
  position: absolute !important;
  left: 0 !important;
  top: 0 !important;
  bottom: 1.15rem !important; /* Stops center-aligned with the last sub-item horizontal branch line */
  width: 1px !important;
  background: rgba(0, 0, 0, 0.08) !important;
  pointer-events: none !important;
}

/* Submenu Item */
#layout-menu.sb-clean-light .menu-sub .menu-item {
  position: relative !important;
  margin: 0 !important;
  padding: 0 !important;
  list-style: none !important;
}

/* L-shaped curved branch line for each sub-item */
#layout-menu.sb-clean-light .menu-sub .menu-item::before {
  content: '' !important;
  position: absolute !important;
  left: 0 !important;
  top: 0 !important;
  width: 0.85rem !important;  /* branch width matching Image 2 */
  height: 1.15rem !important; /* branch height extending to item center */
  border-left: 1px solid rgba(0, 0, 0, 0.08) !important;
  border-bottom: 1px solid rgba(0, 0, 0, 0.08) !important;
  border-bottom-left-radius: 0.45rem !important; /* Smooth curved connection */
  pointer-events: none !important;
}

/* Disable active/bullet overlaps or dots completely */
#layout-menu.sb-clean-light .menu-sub .menu-item::after {
  display: none !important;
  content: none !important;
}

/* Submenu links style */
#layout-menu.sb-clean-light .menu-sub .menu-link {
  min-height: 2.3rem !important;
  padding: 0.35rem 0.55rem 0.35rem 1.45rem !important; /* Shift text to the right to clear room for branch line */
  border-radius: 0.6rem !important;
  color: var(--sb-txt-2) !important;
  font-size: 0.87rem !important;
  font-weight: 500 !important;
  background: transparent !important;
  display: flex !important;
  align-items: center !important;
  border: none !important;
}

/* Disable any template framework bullet dot overlay on submenu link completely */
#layout-menu.sb-clean-light .menu-sub .menu-link::before,
#layout-menu.sb-clean-light .menu-sub .menu-link::after {
  display: none !important;
  content: none !important;
}

/* Submenu active item - compact glass capsule */
#layout-menu.sb-clean-light .menu-sub .menu-item.active > .menu-link {
  color: var(--sb-txt-1) !important;
  background:
    linear-gradient(135deg, rgba(255,255,255,0.82), rgba(255,255,255,0.42)),
    linear-gradient(135deg, rgba(14,165,233,0.18), rgba(99,102,241,0.12)) !important;
  font-weight: 600 !important;
  border: 1px solid rgba(255, 255, 255, 0.88) !important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.9),
    0 10px 24px rgba(14, 165, 233, 0.12),
    0 8px 18px rgba(15, 23, 42, 0.09) !important;
  backdrop-filter: blur(14px) saturate(160%);
  -webkit-backdrop-filter: blur(14px) saturate(160%);
}

/* ================================================================
   SIDEBAR FOOTER
   ================================================================ */
#layout-menu.sb-clean-light .sb-footer {
  display: none !important;
}

#layout-menu.sb-clean-light .sb-footer-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.4rem;
}

#layout-menu.sb-clean-light .sb-foot-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  min-height: 2.2rem;
  padding: 0.4rem 0.6rem;
  border: 1px solid var(--sb-border);
  border-radius: 0.65rem;
  background: #ffffff;
  color: var(--sb-txt-2);
  font-family: var(--sb-font);
  font-size: 0.78rem;
  font-weight: 500;
  text-decoration: none;
  width: 100%;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

#layout-menu.sb-clean-light .sb-foot-btn .material-symbols-rounded {
  font-size: 16px;
  font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 20;
}

#layout-menu.sb-clean-light .sb-foot-btn:hover {
  background: var(--sb-bg-hover);
  color: var(--sb-txt-1);
  border-color: rgba(0, 0, 0, 0.1);
}

#layout-menu.sb-clean-light .sb-foot-btn.sb-logout:hover {
  background: rgba(239, 68, 68, 0.05);
  color: #ef4444;
  border-color: rgba(239, 68, 68, 0.15);
}

/* ================================================================
   COLLAPSED STATE & HOVER EXPANSION (Image 2 and 3 Fixes)
   ================================================================ */
#layout-menu.sb-clean-light.sb-collapsed {
  width: var(--sb-w-mini) !important;
}

/* Collapsed Hidden Elements (Always completely hidden in collapsed state) */
#layout-menu.sb-clean-light.sb-collapsed .sb-brand-body,
#layout-menu.sb-clean-light.sb-collapsed .sb-profile-info,
#layout-menu.sb-clean-light.sb-collapsed .sb-label,
#layout-menu.sb-clean-light.sb-collapsed .sb-badge,
#layout-menu.sb-clean-light.sb-collapsed .menu-header,
#layout-menu.sb-clean-light.sb-collapsed .sb-footer {
  display: none !important;
  width: 0 !important;
  opacity: 0 !important;
}

#layout-menu.sb-clean-light.sb-collapsed .sb-logo-mark {
  display: flex !important;
  width: auto !important;
  opacity: 1 !important;
}

/* Center items in collapsed state */
#layout-menu.sb-clean-light.sb-collapsed .sb-brand {
  justify-content: center !important;
  padding: 1.15rem 0.5rem 1rem !important;
}

/* Center Panel Collapse Button perfectly when collapsed */
#layout-menu.sb-clean-light.sb-collapsed .sb-icon-btn.sb-desktop-only {
  display: none !important;
}

#layout-menu.sb-clean-light.sb-collapsed .sb-profile {
  margin: 0.2rem 0.5rem 0.5rem;
  padding: 0.5rem;
  justify-content: center;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-inner {
  overflow-y: auto !important;
  overflow-x: hidden !important;
  padding: 0.45rem 0.48rem 1rem !important;
  scrollbar-width: none;
  -ms-overflow-style: none;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-inner::-webkit-scrollbar {
  width: 0;
  height: 0;
  display: none;
}

#layout-menu.sb-clean-light.sb-collapsed .sb-icon {
  margin-right: 0 !important;
  margin-bottom: 0 !important;
  color: var(--sb-txt-2) !important;
  opacity: 1 !important;
  visibility: visible !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-link:hover .sb-icon,
#layout-menu.sb-clean-light.sb-collapsed .menu-item.flyout-open > .menu-link .sb-icon,
#layout-menu.sb-clean-light.sb-collapsed .menu-item.active > .menu-link .sb-icon {
  color: var(--sb-txt-1) !important;
  opacity: 1 !important;
  visibility: visible !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-link:hover .sb-icon .material-symbols-rounded,
#layout-menu.sb-clean-light.sb-collapsed .menu-item.flyout-open > .menu-link .sb-icon .material-symbols-rounded,
#layout-menu.sb-clean-light.sb-collapsed .menu-item.active > .menu-link .sb-icon .material-symbols-rounded {
  opacity: 1 !important;
  visibility: visible !important;
  color: inherit !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-link {
  width: 3.65rem !important;
  max-width: 3.65rem !important;
  margin: 0 auto !important;
  justify-content: center !important;
  flex-direction: row;
  min-height: 3.4rem;
  padding: 0 !important;
  border-radius: 1rem !important;
  text-align: center;
  overflow: hidden !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-link:hover,
#layout-menu.sb-clean-light.sb-collapsed .menu-item.flyout-open > .menu-link {
  background:
    linear-gradient(135deg, rgba(255,255,255,0.84), rgba(255,255,255,0.38)),
    linear-gradient(135deg, rgba(14,165,233,0.18), rgba(99,102,241,0.12)) !important;
  border-color: rgba(255,255,255,0.9) !important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.95),
    inset 0 -1px 0 rgba(15,23,42,0.04),
    0 16px 34px rgba(14, 165, 233, 0.16),
    0 10px 24px rgba(15, 23, 42, 0.13) !important;
  backdrop-filter: blur(18px) saturate(175%);
  -webkit-backdrop-filter: blur(18px) saturate(175%);
}

#layout-menu.sb-clean-light.sb-collapsed .menu-inner > .menu-item > .menu-link .sb-icon {
  position: relative !important;
  inset: auto !important;
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 2.4rem !important;
  height: 2.4rem !important;
  min-width: 2.4rem !important;
  max-width: 2.4rem !important;
  transform: none !important;
  margin: 0 !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-inner > .menu-item > .menu-link .sb-icon .material-symbols-rounded {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  width: 1.35rem !important;
  height: 1.35rem !important;
  font-family: 'Material Symbols Rounded' !important;
  font-size: 1.35rem !important;
  line-height: 1 !important;
  opacity: 1 !important;
  visibility: visible !important;
}

#layout-menu.sb-clean-light.sb-collapsed .sb-label {
  display: none !important;
  width: 0 !important;
  max-width: 0 !important;
  opacity: 0 !important;
  visibility: hidden !important;
  pointer-events: none !important;
}

html.layout-menu-hover #layout-menu.sb-clean-light.sb-collapsed .sb-brand-body,
html.layout-menu-hover #layout-menu.sb-clean-light.sb-collapsed .sb-profile-info,
html.layout-menu-hover #layout-menu.sb-clean-light.sb-collapsed .sb-label,
html.layout-menu-hover #layout-menu.sb-clean-light.sb-collapsed .sb-badge,
html.layout-menu-hover #layout-menu.sb-clean-light.sb-collapsed .menu-header,
html.layout-menu-hover #layout-menu.sb-clean-light.sb-collapsed .sb-footer {
  display: none !important;
  width: 0 !important;
  max-width: 0 !important;
  opacity: 0 !important;
  visibility: hidden !important;
}

html.layout-menu-hover #layout-menu.sb-clean-light.sb-collapsed .sb-icon,
html.layout-menu-hover #layout-menu.sb-clean-light.sb-collapsed .sb-icon .material-symbols-rounded {
  display: inline-flex !important;
  opacity: 1 !important;
  visibility: visible !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-item.active > .menu-link .sb-label,
#layout-menu.sb-clean-light.sb-collapsed .menu-link:hover .sb-label {
  color: var(--sb-txt-1) !important;
}

#layout-menu.sb-clean-light.sb-collapsed .sb-chevron {
  display: none !important;
  width: 0 !important;
  opacity: 0 !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-item.open > .menu-link .sb-chevron {
  transform: translateY(-50%);
}

#layout-menu.sb-clean-light.sb-collapsed .menu-item.has-submenu > .menu-link {
  padding: 0 !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-item.has-submenu.flyout-open > .menu-link {
  background: rgba(255, 255, 255, 0.72) !important;
  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.09) !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-sub {
  display: none !important;
  position: fixed !important;
  left: calc(var(--sb-w-mini) + 0.45rem) !important;
  top: var(--sb-flyout-top, 8rem) !important;
  z-index: 99999 !important;
  width: 14.5rem !important;
  max-height: min(25rem, calc(100vh - 2rem)) !important;
  overflow-y: auto !important;
  margin: 0 !important;
  padding: 0.7rem !important;
  border: 1px solid rgba(226, 232, 240, 0.86) !important;
  border-radius: 1rem !important;
  background:
    radial-gradient(circle at 100% 0%, rgba(14, 165, 233, 0.13), transparent 36%),
    radial-gradient(circle at 0% 100%, rgba(251, 146, 60, 0.11), transparent 34%),
    rgba(255, 255, 255, 0.96) !important;
  box-shadow: 0 18px 45px rgba(15, 23, 42, 0.13) !important;
  backdrop-filter: blur(14px);
}

#layout-menu.sb-clean-light.sb-collapsed .menu-item.flyout-open > .menu-sub {
  display: none !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-sub::before,
#layout-menu.sb-clean-light.sb-collapsed .menu-sub .menu-item::before {
  display: none !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-sub .menu-link {
  flex-direction: row !important;
  justify-content: flex-start !important;
  min-height: 2.55rem !important;
  padding: 0.55rem 0.75rem !important;
  text-align: left !important;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-sub .sb-label {
  display: block !important;
  width: auto !important;
  opacity: 1 !important;
  max-width: none;
  text-align: left;
  white-space: nowrap;
  font-size: 0.9rem;
  font-weight: 600;
}

#layout-menu.sb-clean-light.sb-collapsed .menu-item.has-submenu.flyout-open > .menu-link,
#layout-menu.sb-clean-light.sb-collapsed .menu-item.has-submenu > .menu-link:hover {
  background:
    linear-gradient(135deg, rgba(255,255,255,0.84), rgba(255,255,255,0.38)),
    linear-gradient(135deg, rgba(14,165,233,0.18), rgba(99,102,241,0.12)) !important;
  border-color: rgba(255,255,255,0.9) !important;
  box-shadow:
    inset 0 1px 0 rgba(255,255,255,0.95),
    inset 0 -1px 0 rgba(15,23,42,0.04),
    0 16px 34px rgba(14, 165, 233, 0.16),
    0 10px 24px rgba(15, 23, 42, 0.13) !important;
  backdrop-filter: blur(18px) saturate(175%);
  -webkit-backdrop-filter: blur(18px) saturate(175%);
}

.sb-submenu-modal-backdrop {
  position: fixed;
  left: calc(var(--sb-layout-collapsed) + 0.85rem);
  top: var(--sb-popover-top, 6rem);
  z-index: 1090;
  display: none;
  width: min(360px, calc(100vw - var(--sb-layout-collapsed) - 1.75rem));
  padding: 0;
  background: transparent;
  transform: translateY(-50%);
  pointer-events: none;
}

.sb-submenu-modal-backdrop.is-open {
  display: block;
}

.sb-submenu-modal {
  position: relative;
  width: 100%;
  max-height: min(620px, calc(100vh - 2rem));
  overflow: hidden;
  border: 1px solid rgba(226, 232, 240, 0.95);
  border-radius: 1.5rem;
  background:
    radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.08), transparent 38%),
    #ffffff;
  box-shadow: 0 26px 70px rgba(15, 23, 42, 0.22);
  pointer-events: auto;
}

.sb-submenu-modal::before {
  content: '';
  position: absolute;
  left: -9px;
  top: 50%;
  width: 18px;
  height: 18px;
  background: #ffffff;
  border-left: 1px solid rgba(226, 232, 240, 0.95);
  border-bottom: 1px solid rgba(226, 232, 240, 0.95);
  transform: translateY(-50%) rotate(45deg);
  z-index: 1;
}

.sb-submenu-modal-head {
  position: relative;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1.1rem 1.25rem;
  border-bottom: 1px solid #eef2f7;
  background:
    radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.10), transparent 42%),
    #ffffff;
}

.sb-submenu-modal-title {
  margin: 0;
  color: #0f172a;
  font-size: 1rem;
  font-weight: 800;
  line-height: 1.2;
}

.sb-submenu-modal-subtitle {
  margin: 0.2rem 0 0;
  color: #64748b;
  font-size: 0.78rem;
  font-weight: 600;
}

.sb-submenu-modal-close {
  width: 2.35rem;
  height: 2.35rem;
  border: 1px solid #e2e8f0;
  border-radius: 999px;
  background: #ffffff;
  color: #334155;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.sb-submenu-modal-list {
  position: relative;
  z-index: 2;
  display: grid;
  gap: 0.55rem;
  max-height: min(460px, calc(100vh - 11rem));
  overflow-y: auto;
  padding: 0.85rem;
}

.sb-submenu-modal-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  min-height: 3rem;
  padding: 0.75rem 0.85rem;
  border: 1px solid #eef2f7;
  border-radius: 1rem;
  background: #f8fafc;
  color: #0f172a;
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 750;
  transition: background 0.16s ease, border-color 0.16s ease, transform 0.16s ease;
}

.sb-submenu-modal-link:hover {
  background: #ffffff;
  border-color: #cbd5e1;
  color: #0f172a;
  transform: translateY(-1px);
}

.sb-submenu-modal-link.is-active {
  background: #0f172a;
  border-color: #0f172a;
  color: #ffffff;
}

.sb-submenu-modal-link .material-symbols-rounded {
  font-size: 1.25rem;
}

/* ================================================================
   MOBILE
   ================================================================ */
@media (max-width: 1199.98px) {
  #layout-menu.sb-clean-light {
    width: min(18rem, 86vw) !important;
    min-width: min(18rem, 86vw) !important;
    max-width: min(18rem, 86vw) !important;
    z-index: 1081 !important;
    transform: translateX(-100%) !important;
    transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.28s !important;
    box-shadow: 8px 0 32px rgba(0, 0, 0, 0.08) !important;
  }

  #layout-menu.sb-clean-light .sb-icon-btn.sb-mobile-close {
    display: inline-flex;
  }

  #layout-menu.sb-clean-light .sb-icon-btn.sb-desktop-only {
    display: none !important;
  }
}

body.swal2-shown #layout-menu.sb-clean-light,
body.modal-open #layout-menu.sb-clean-light,
body.swal2-shown .layout-navbar,
body.modal-open .layout-navbar {
  z-index: 1030 !important;
}

</style>

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme sb-clean-light" data-sidebar-custom="true">
  <div class="sb-shell">

    {{-- ===== BRAND / LOGO AREA ===== --}}
    @if (!isset($navbarFull))
    <div class="sb-brand">
      <div class="sb-logo-mark">
        <img src="{{ asset('logo.png') }}" alt="JMK" class="sb-logo-img">
      </div>

      <div class="sb-brand-body">
        <span class="sb-brand-name">JMK</span>
        <span class="sb-brand-sub"></span>
      </div>

      {{-- Mobile close --}}
      <button type="button" class="sb-icon-btn sb-mobile-close" id="sbMobileCloseBtn" aria-label="Close Menu">
        <span class="material-symbols-rounded">close</span>
      </button>
    </div>
    @endif

    {{-- ===== USER PROFILE AREA ===== --}}
    <div class="sb-profile">
      <span class="sb-avatar">{{ $userInitials }}</span>
      <div class="sb-profile-info">
        <span class="sb-profile-name">{{ $userName }}</span>
        <span class="sb-role-badge" style="color: {{ $roleMeta['color'] }}; background: {{ $roleMeta['bg'] }};">
          {{ $roleMeta['label'] }}
        </span>
      </div>
    </div>

    {{-- ===== NAVIGATION LIST ===== --}}
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
      @if (!empty($menuData) && isset($menuData[0]->menu))
        @foreach ($menuData[0]->menu as $menu)
          @php
            $menuRoles = $menu->roles ?? null;
            $isAllowed = true;
            if ($menuRoles) {
              if (is_array($menuRoles)) {
                $isAllowed = in_array($currentUserRole, array_map('strtolower', $menuRoles));
              } else {
                $isAllowed = strtolower($menuRoles) === $currentUserRole;
              }
            }
          @endphp

          @if ($isAllowed)
            @if (isset($menu->menuHeader))
              <li class="menu-header mt-3">
                <span class="menu-header-text"><span>{{ __($menu->menuHeader) }}</span></span>
              </li>
            @else
              @php
                $activeClass = '';
                $hasActiveChild = false;

                if (isset($menu->submenu)) {
                  foreach ($menu->submenu as $sub) {
                    if ($isRouteActive($sub)) {
                      $hasActiveChild = true;
                      break;
                    }
                  }
                }

                if ($isRouteActive($menu)) {
                  $activeClass = 'active';
                } elseif ($hasActiveChild) {
                  $activeClass = 'active open';
                }

                $menuId = 'menu-' . Str::slug($menu->name ?? 'item');
              @endphp

              <li class="menu-item {{ $activeClass }} {{ isset($menu->submenu) ? 'has-submenu' : '' }}" data-menu-id="{{ $menuId }}">
                <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}"
                   class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}"
                   @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>

                  <span class="sb-icon" aria-hidden="true">
                    <span class="material-symbols-rounded">{{ $resolveMuiIcon($menu->icon ?? '', $menu->name ?? '') }}</span>
                  </span>

                  <span class="sb-label">{{ $menu->name ?? '' }}</span>

                  @isset($menu->badge)
                    <span class="sb-badge badge bg-{{ $menu->badge[0] }} rounded-pill ms-auto">{{ $menu->badge[1] }}</span>
                  @endisset

                  @isset($menu->submenu)
                    <span class="material-symbols-rounded sb-chevron ms-auto">chevron_right</span>
                  @endisset
                </a>

                @isset($menu->submenu)
                  <ul class="menu-sub">
                    @foreach ($menu->submenu as $submenu)
                      @php
                        $submenuRoles = $submenu->roles ?? null;
                        $submenuAllowed = true;
                        $submenuId = 'submenu-' . Str::slug($submenu->name ?? ($submenu->slug ?? 'subitem'));
                        if ($submenuRoles) {
                          if (is_array($submenuRoles)) {
                            $submenuAllowed = in_array($currentUserRole, array_map('strtolower', $submenuRoles));
                          } else {
                            $submenuAllowed = strtolower($submenuRoles) === $currentUserRole;
                          }
                        }
                      @endphp
                      @if ($submenuAllowed)
                        <li class="menu-item {{ $isRouteActive($submenu) ? 'active' : '' }}" data-submenu-id="{{ $submenuId }}">
                          <a href="{{ url($submenu->url) }}" class="menu-link">
                            <span class="sb-sub-icon d-none" data-submenu-icon="{{ $resolveMuiIcon($submenu->icon ?? '', $submenu->name ?? '') }}"></span>
                            <span class="sb-label">{{ $submenu->name }}</span>
                          </a>
                        </li>
                      @endif
                    @endforeach
                  </ul>
                @endisset
              </li>
            @endif
          @endif
        @endforeach
      @else
        <li class="menu-item">
          <a href="#" class="menu-link">
            <span class="sb-icon">
              <span class="material-symbols-rounded">warning</span>
            </span>
            <span class="sb-label">No menu data found</span>
          </a>
        </li>
      @endif
    </ul>

    {{-- ===== FOOTER ===== --}}
    <div class="sb-footer">
      <div class="sb-footer-row">
        <a href="{{ $profileUrl }}" class="sb-foot-btn">
          <span class="material-symbols-rounded">manage_accounts</span>
          <span>Profil</span>
        </a>
        <form action="{{ route($logoutRoute) }}" method="POST" class="m-0 p-0" style="display: contents;">
          @csrf
          <button type="submit" class="sb-foot-btn sb-logout">
            <span class="material-symbols-rounded">logout</span>
            <span>Keluar</span>
          </button>
        </form>
      </div>
    </div>

  </div>
</aside>

<div class="sb-submenu-modal-backdrop" id="sbSubmenuModal" aria-hidden="true">
  <div class="sb-submenu-modal" role="dialog" aria-modal="true" aria-labelledby="sbSubmenuModalTitle">
    <div class="sb-submenu-modal-head">
      <div>
        <h3 class="sb-submenu-modal-title" id="sbSubmenuModalTitle">Pilih Menu</h3>
        <p class="sb-submenu-modal-subtitle" id="sbSubmenuModalSubtitle">Pilih halaman yang ingin dibuka</p>
      </div>
      <button type="button" class="sb-submenu-modal-close" id="sbSubmenuModalClose" aria-label="Tutup pilihan menu">
        <span class="material-symbols-rounded">close</span>
      </button>
    </div>
    <div class="sb-submenu-modal-list" id="sbSubmenuModalList"></div>
  </div>
</div>

<script>
(function () {
  'use strict';

  var sidebar  = document.getElementById('layout-menu');
  if (!sidebar) return;

  var collapseBtn    = document.getElementById('sbNavbarCollapseBtn') || document.getElementById('sbCollapseBtn');
  var mobileCloseBtn = document.getElementById('sbMobileCloseBtn');
  var submenuModal   = document.getElementById('sbSubmenuModal');
  var submenuTitle   = document.getElementById('sbSubmenuModalTitle');
  var submenuList    = document.getElementById('sbSubmenuModalList');
  var submenuClose   = document.getElementById('sbSubmenuModalClose');
  var htmlEl         = document.documentElement;
  var STORE_KEY      = 'sb_clean_light_collapsed';

  function restoreActiveOpenState() {
    if (sidebar.classList.contains('sb-collapsed')) return;
    sidebar.querySelectorAll('.menu-item.has-submenu.open:not(.active)').forEach(function (item) {
      item.classList.remove('open');
    });
    sidebar.querySelectorAll('.menu-item.active.has-submenu').forEach(function (item) {
      item.classList.add('open');
    });
  }

  function isolateFromTemplateMenu() {
    if (!sidebar.classList.contains('sb-clean-light')) return;
    htmlEl.classList.remove('layout-menu-hover');
    if (sidebar.menuInstance && typeof sidebar.menuInstance.destroy === 'function') {
      try {
        sidebar.menuInstance.destroy();
      } catch (e) {}
    }
    sidebar.menuInstance = null;
    sidebar.classList.add('menu-no-animation');
    if (window.innerWidth < 1200) {
      sidebar.classList.remove('active');
    }
    restoreActiveOpenState();
    updateToggleIcon();
  }

  // Toggle Collapse Icon Symbol Dynamically based on state (left_panel_open / left_panel_close)
  function updateToggleIcon() {
    collapseBtn = document.getElementById('sbNavbarCollapseBtn') || document.getElementById('sbCollapseBtn') || collapseBtn;
    if (!collapseBtn) return;
    var iconEl = collapseBtn.querySelector('.material-symbols-rounded');
    if (!iconEl) return;
    if (sidebar.classList.contains('sb-collapsed')) {
      iconEl.textContent = 'left_panel_open';
    } else {
      iconEl.textContent = 'left_panel_close';
    }
  }

  function setCollapsedState(collapsed, persist) {
    sidebar.classList.toggle('sb-collapsed', collapsed);
    htmlEl.classList.toggle('layout-menu-collapsed', collapsed);
    sidebar.querySelectorAll('.menu-item.flyout-open').forEach(function (openItem) {
      openItem.classList.remove('flyout-open');
    });
    if (!collapsed) restoreActiveOpenState();
    if (collapsed) closeSubmenuModal();
    if (persist) {
      localStorage.setItem(STORE_KEY, collapsed ? '1' : '0');
    }
    updateToggleIcon();
  }

  setCollapsedState(localStorage.getItem(STORE_KEY) === '1', false);
  restoreActiveOpenState();

  if (collapseBtn) {
    collapseBtn.removeAttribute('hidden');
    collapseBtn.removeAttribute('aria-hidden');
    collapseBtn.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      setCollapsedState(!sidebar.classList.contains('sb-collapsed'), true);
    });
  }

  document.addEventListener('click', function (e) {
    var navbarToggle = e.target.closest ? e.target.closest('#sbNavbarCollapseBtn') : null;
    if (!navbarToggle) return;
    e.preventDefault();
    e.stopPropagation();
    collapseBtn = navbarToggle;
    setCollapsedState(!sidebar.classList.contains('sb-collapsed'), true);
  });

  document.addEventListener('DOMContentLoaded', updateToggleIcon);

  var htmlClassObserver = new MutationObserver(function () {
    if (sidebar.classList.contains('sb-collapsed') && htmlEl.classList.contains('layout-menu-hover')) {
      htmlEl.classList.remove('layout-menu-hover');
    }
    if (htmlEl.classList.contains('layout-menu-collapsed') !== sidebar.classList.contains('sb-collapsed')) {
      htmlEl.classList.toggle('layout-menu-collapsed', sidebar.classList.contains('sb-collapsed'));
    }
  });
  htmlClassObserver.observe(htmlEl, { attributes: true, attributeFilter: ['class'] });

  ['mouseenter', 'mouseover', 'mousemove'].forEach(function (eventName) {
    sidebar.addEventListener(eventName, function () {
      if (sidebar.classList.contains('sb-collapsed')) {
        htmlEl.classList.remove('layout-menu-hover');
      }
    }, true);
  });

  // Mobile Close
  if (mobileCloseBtn) {
    mobileCloseBtn.addEventListener('click', function () {
      sidebar.classList.remove('active');
      htmlEl.classList.remove('layout-menu-expanded');
    });
  }

  var layoutOverlay = document.querySelector('.layout-overlay');
  if (layoutOverlay) {
    layoutOverlay.addEventListener('click', function () {
      sidebar.classList.remove('active');
      htmlEl.classList.remove('layout-menu-expanded');
    });
  }

  // Accordion & Toggle (Saves open state and auto-closes siblings correctly)
  sidebar.querySelectorAll('.menu-link.menu-toggle').forEach(function (toggle) {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      if (sidebar.classList.contains('sb-collapsed')) {
        var collapsedItem = this.closest('.menu-item');
        if (!collapsedItem) return;
        sidebar.querySelectorAll('.menu-item.flyout-open').forEach(function (openItem) {
          if (openItem !== collapsedItem) openItem.classList.remove('flyout-open');
        });
        openSubmenuModal(collapsedItem);
        return;
      }

      var item   = this.closest('.menu-item');
      if (!item) return;
      var isOpen = item.classList.contains('open');

      // Auto-close other parent menus and move the active indicator to the clicked menu.
      sidebar.querySelectorAll('.menu-inner > .menu-item.has-submenu').forEach(function (menuItem) {
        if (menuItem !== item) {
          menuItem.classList.remove('open');
          menuItem.classList.remove('active');
        }
      });

      item.classList.add('active');
      item.classList.toggle('open', !isOpen);
    });
  });

  function positionFlyout(item, link) {
    if (!item || !link) return;
    var rect = link.getBoundingClientRect();
    var flyout = item.querySelector(':scope > .menu-sub');
    if (!flyout) return;
    var top = Math.max(12, Math.min(rect.top, window.innerHeight - 420));
    item.style.setProperty('--sb-flyout-top', top + 'px');
  }

  function closeSubmenuModal() {
    if (!submenuModal) return;
    submenuModal.classList.remove('is-open');
    submenuModal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('sb-submenu-modal-open');
    sidebar.querySelectorAll('.menu-item.flyout-open').forEach(function (openItem) {
      openItem.classList.remove('flyout-open');
    });
  }

  function openSubmenuModal(item) {
    if (!submenuModal || !submenuTitle || !submenuList || !item) return;
    var parentLink = item.querySelector(':scope > .menu-link');
    if (parentLink) {
      var rect = parentLink.getBoundingClientRect();
      var modalHalf = Math.min(310, (window.innerHeight - 32) / 2);
      var top = rect.top + (rect.height / 2);
      top = Math.max(32 + Math.min(180, modalHalf), Math.min(top, window.innerHeight - 32 - Math.min(180, modalHalf)));
      submenuModal.style.setProperty('--sb-popover-top', top + 'px');
    }
    var parentLabel = item.querySelector(':scope > .menu-link .sb-label');
    var parentName = parentLabel ? parentLabel.textContent.trim() : 'Pilih Menu';
    submenuTitle.textContent = parentName;
    submenuList.innerHTML = '';

    item.querySelectorAll(':scope > .menu-sub > .menu-item > .menu-link').forEach(function (link) {
      var labelEl = link.querySelector('.sb-label');
      var iconEl = link.querySelector('.sb-sub-icon');
      var iconName = iconEl ? iconEl.getAttribute('data-submenu-icon') : 'radio_button_unchecked';
      var modalLink = document.createElement('a');
      modalLink.href = link.href;
      var linkItem = link.closest('.menu-item');
      modalLink.className = 'sb-submenu-modal-link' + (linkItem && linkItem.classList.contains('active') ? ' is-active' : '');
      modalLink.innerHTML =
        '<span class="material-symbols-rounded">' + iconName + '</span>' +
        '<span>' + (labelEl ? labelEl.textContent.trim() : 'Menu') + '</span>';
      submenuList.appendChild(modalLink);
    });

    item.classList.add('flyout-open');
    submenuModal.classList.add('is-open');
    submenuModal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('sb-submenu-modal-open');
  }

  sidebar.querySelectorAll('.menu-item.has-submenu').forEach(function (item) {
    var link = item.querySelector(':scope > .menu-link');
    var flyout = item.querySelector(':scope > .menu-sub');
    var closeTimer = null;
    if (!link || !flyout) return;

    function openFlyout() {
      return;
      clearTimeout(closeTimer);
      sidebar.querySelectorAll('.menu-item.flyout-open').forEach(function (openItem) {
        if (openItem !== item) openItem.classList.remove('flyout-open');
      });
      positionFlyout(item, link);
      item.classList.add('flyout-open');
    }

    function queueClose() {
      clearTimeout(closeTimer);
      closeTimer = setTimeout(function () {
        item.classList.remove('flyout-open');
      }, 180);
    }

    link.addEventListener('mouseenter', openFlyout);
    flyout.addEventListener('mouseenter', function () {
      clearTimeout(closeTimer);
    });
    flyout.addEventListener('click', function (e) {
      e.stopPropagation();
    });
    link.addEventListener('mouseleave', queueClose);
    flyout.addEventListener('mouseleave', queueClose);
  });

  document.addEventListener('click', function (e) {
    if (!sidebar.classList.contains('sb-collapsed')) return;
    if (submenuModal && submenuModal.contains(e.target)) return;
    if (sidebar.contains(e.target)) return;
    sidebar.querySelectorAll('.menu-item.flyout-open').forEach(function (openItem) {
      openItem.classList.remove('flyout-open');
    });
  });

  if (submenuClose) {
    submenuClose.addEventListener('click', closeSubmenuModal);
  }
  if (submenuModal) {
    submenuModal.addEventListener('click', function (e) {
      if (e.target === submenuModal) closeSubmenuModal();
    });
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeSubmenuModal();
  });

  window.addEventListener('load', function () {
    setTimeout(isolateFromTemplateMenu, 150);
    setTimeout(isolateFromTemplateMenu, 500);
  });

})();
</script>
