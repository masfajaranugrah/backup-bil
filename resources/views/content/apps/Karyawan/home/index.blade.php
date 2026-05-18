@php
  $userName = $userName ?? 'Staff';
  $jabatan = $jabatan ?? 'Karyawan';
  $hariIni = $hariIni ?? now()->translatedFormat('l');
  $tanggalHariIni = $tanggalHariIni ?? now()->translatedFormat('j M Y');
  $jamSekarang = now()->format('H:i:s');
  $timeIn = $timeIn ?? '--:--:--';
  $timeOut = $timeOut ?? '--:--:--';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Home Karyawan')

@section('page-style')
  <style>
    :root {
      --primary: #0D6EFD;
      --bg-color: #F4F7FF;
      --text-main: #111827;
      --text-muted: #6B7280;
      --card-bg: #FFFFFF;
      --border-color: #E5E7EB;
    }

    body {
      background-color: #e5e5e5;
      margin: 0;
      font-family: 'Inter', 'Nunito', sans-serif;
    }

    .mobile-wrapper {
      max-width: 480px;
      margin: 0 auto;
      background-color: var(--bg-color);
      min-height: 100vh;
      position: relative;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
      padding-bottom: 80px;
      overflow-x: hidden;
    }

    /* Header Gradient */
    .header-gradient {
      background: linear-gradient(135deg, #0D6EFD 0%, #0043A8 100%);
      padding: 30px 24px 60px;
      border-bottom-left-radius: 32px;
      border-bottom-right-radius: 32px;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .header-gradient::after {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 150px;
      height: 150px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
    }

    /* Topbar */
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      position: relative;
      z-index: 2;
    }

    .company-brand {
      color: white;
      font-weight: 700;
      font-size: 15px;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .topbar-icons {
      display: flex;
      gap: 16px;
      color: white;
      font-size: 20px;
    }

    /* Date Time Pill */
    .datetime-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255, 255, 255, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 6px 14px;
      border-radius: 99px;
      font-size: 12px;
      color: white;
      margin-bottom: 20px;
      position: relative;
      z-index: 2;
    }

    /* Profile */
    .profile-section {
      display: flex;
      align-items: center;
      gap: 16px;
      position: relative;
      z-index: 2;
    }

    .profile-avatar {
      width: 58px;
      height: 58px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .profile-text {
      display: flex;
      flex-direction: column;
    }

    .profile-greeting {
      font-size: 13px;
      color: rgba(255, 255, 255, 0.85);
      margin-bottom: 2px;
    }

    .profile-name {
      font-weight: 800;
      color: white;
      font-size: 18px;
      margin-bottom: 3px;
      line-height: 1.2;
    }

    .profile-role {
      color: rgba(255, 255, 255, 0.9);
      font-size: 13px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* Content Area */
    .content-section {
      padding: 0 20px;
      margin-top: -30px;
      position: relative;
      z-index: 3;
    }

    /* Clock Card */
    .clock-card {
      background: var(--card-bg);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      padding: 24px;
      margin-bottom: 24px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .clock-grid {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      position: relative;
    }

    .clock-grid::after {
      content: '';
      position: absolute;
      top: 0;
      bottom: 0;
      left: 50%;
      width: 1px;
      background: var(--border-color);
    }

    .clock-item {
      text-align: center;
      flex: 1;
    }

    .clock-label {
      color: var(--text-muted);
      font-size: 13px;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .clock-value {
      font-size: 24px;
      font-weight: 800;
      color: var(--text-main);
    }

    .clock-actions {
      display: flex;
      gap: 12px;
    }

    .clock-actions form {
      flex: 1;
      margin: 0;
    }

    .btn-clock {
      flex: 1;
      background: var(--primary);
      color: white;
      border: none;
      padding: 14px;
      border-radius: 14px;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      transition: all 0.2s;
      width: 100%;
      cursor: pointer;
    }

    .btn-clock:hover {
      background: #0052cc;
      color: white;
      transform: translateY(-2px);
    }

    .btn-clock-outline {
      background: rgba(13, 110, 253, 0.1);
      color: var(--primary);
    }
    .btn-clock-outline:hover {
      background: rgba(13, 110, 253, 0.2);
      color: var(--primary);
    }

    /* Menus */
    .menu-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 16px 8px;
      margin-bottom: 32px;
    }

    .menu-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-decoration: none;
      gap: 8px;
      transition: transform 0.2s;
    }

    .menu-item:hover {
      transform: translateY(-3px);
    }

    .menu-icon-wrapper {
      width: 56px;
      height: 56px;
      background: var(--card-bg);
      border-radius: 18px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 24px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
      border: 1px solid rgba(255,255,255,0.8);
    }

    .menu-label {
      font-size: 12px;
      font-weight: 600;
      color: var(--text-main);
      text-align: center;
      line-height: 1.2;
    }

    /* Colors for icons */
    .ic-orange {
      color: #F59E0B;
    }

    .ic-green {
      color: #10B981;
    }

    .ic-pink {
      color: #EC4899;
    }

    .ic-blue {
      color: #3B82F6;
    }
    
    .quick-stats {
      background: #fff;
      border-radius: 20px;
      padding: 20px;
      margin-bottom: 24px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.03);
      border: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      gap: 16px;
    }
    
    .qs-icon {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      background: rgba(16, 185, 129, 0.1);
      color: #10B981;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
    }
    
    .qs-info h6 {
      margin: 0 0 4px 0;
      font-size: 14px;
      font-weight: 700;
      color: var(--text-main);
    }
    
    .qs-info p {
      margin: 0;
      font-size: 12px;
      color: var(--text-muted);
      line-height: 1.4;
    }



    /* Bottom Nav */
    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 100%;
      max-width: 480px;
      background: var(--card-bg);
      display: flex;
      justify-content: space-around;
      padding: 12px 24px;
      border-top: 1px solid var(--border-color);
      z-index: 10;
    }

    .nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-decoration: none;
      color: #9CA3AF;
      gap: 4px;
    }

    .nav-item.active {
      color: var(--primary);
    }

    .nav-icon {
      font-size: 22px;
    }

    .nav-label {
      font-size: 10px;
      font-weight: 600;
    }

    /* Custom Mobile Toast */
    .mobile-toast {
      position: fixed;
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      background: #ffffff;
      color: #111827;
      padding: 12px 16px;
      border-radius: 100px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.12);
      display: flex;
      align-items: center;
      gap: 12px;
      z-index: 9999;
      width: 90%;
      max-width: 360px;
      transition: top 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .mobile-toast.show {
      top: 24px;
    }

    .toast-icon {
      width: 26px;
      height: 26px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      color: #fff;
      flex-shrink: 0;
    }

    .toast-icon.success { background: #10B981; }
    .toast-icon.error { background: #EF4444; }

    .toast-message {
      font-size: 13px;
      font-weight: 600;
      margin: 0;
      line-height: 1.4;
    }
  </style>
@endsection

@section('content')
  @if(session('success'))
    <div id="custom-toast" class="mobile-toast">
      <div class="toast-icon success"><i class="ri-check-line"></i></div>
      <p class="toast-message">{{ session('success') }}</p>
    </div>
  @endif

  @if(session('error'))
    <div id="custom-toast" class="mobile-toast">
      <div class="toast-icon error"><i class="ri-close-line"></i></div>
      <p class="toast-message">{{ session('error') }}</p>
    </div>
  @endif

  <div class="mobile-wrapper">

    
    <div class="header-gradient">
      {{-- Topbar --}}
      <div class="topbar">
        <div class="company-brand">
          PT. Jernih Multi Komunikasi <i class="ri-arrow-down-s-line" style="font-size: 18px;"></i>
        </div>
        <div class="topbar-icons">
          <i class="ri-search-line"></i>
          <i class="ri-notification-3-line"></i>
        </div>
      </div>

      {{-- Date Time Pill --}}
      <div class="datetime-pill">
        <i class="ri-calendar-line"></i> {{ $hariIni }}, {{ $tanggalHariIni }} &nbsp;|&nbsp; <i class="ri-time-line"></i> <span id="realtime-clock">{{ $jamSekarang }} WIB</span>
      </div>

      {{-- Profile Section --}}
      <div class="profile-section">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($userName) }}&background=E5E7EB&color=111827&size=100"
          alt="Avatar" class="profile-avatar">
        <div class="profile-text">
          <div class="profile-greeting">Selamat Bekerja,</div>
          <div class="profile-name">{{ $userName }}</div>
          <div class="profile-role"><i class="ri-medal-fill"></i> {{ $jabatan }}</div>
        </div>
      </div>
    </div>

    <div class="content-section">
      {{-- Clock Card --}}
      <div class="clock-card">
        <div class="clock-grid">
          <div class="clock-item">
            <div class="clock-label">Absen Masuk</div>
            <div class="clock-value">{{ $timeIn }}</div>
          </div>
          <div class="clock-item">
            <div class="clock-label">Absen Keluar</div>
            <div class="clock-value">{{ $timeOut }}</div>
          </div>
        </div>
        <div class="clock-actions">
          <a href="{{ route('absensi.capture', ['action' => 'checkin']) }}" class="btn-clock"
            style="text-decoration: none;">
            <i class="ri-login-box-line" style="font-size: 18px;"></i> Masuk
          </a>
          <a href="{{ route('absensi.capture', ['action' => 'checkout']) }}" class="btn-clock btn-clock-outline"
            style="text-decoration: none;">
            <i class="ri-logout-box-line" style="font-size: 18px;"></i> Pulang
          </a>
        </div>
      </div>

      {{-- Menu Grid --}}
      <div class="menu-grid">
        <a href="{{ route('absensi.index') }}" class="menu-item">
          <div class="menu-icon-wrapper"><i class="ri-time-line ic-orange"></i></div>
          <span class="menu-label">Absensi</span>
        </a>
        <a href="{{ url('/dashboard/karyawan/jobs') }}" class="menu-item">
          <div class="menu-icon-wrapper"><i class="ri-briefcase-4-fill ic-blue"></i></div>
          <span class="menu-label">Jobs</span>
        </a>
        <a href="{{ route('karyawan.slip-gaji') }}" class="menu-item">
          <div class="menu-icon-wrapper"><i class="ri-file-text-line ic-green"></i></div>
          <span class="menu-label">Slip Gaji</span>
        </a>
        <a href="{{ route('karyawan.lembur') }}" class="menu-item">
          <div class="menu-icon-wrapper"><i class="ri-moon-line ic-pink"></i></div>
          <span class="menu-label">Lembur</span>
        </a>
      </div>
      
      {{-- Quick Stats / Info --}}
      <div class="quick-stats">
        @if(isset($activeJobsCount) && $activeJobsCount > 0)
        <div class="qs-icon" style="background: rgba(245, 158, 11, 0.1); color: #F59E0B;">
          <i class="ri-briefcase-4-line"></i>
        </div>
        <div class="qs-info">
          <h6>Status Pekerjaan</h6>
          <p>Anda masih memiliki <strong>{{ $activeJobsCount }} tugas harian</strong> yang belum diselesaikan. Yuk, selesaikan sekarang!</p>
        </div>
        @else
        <div class="qs-icon">
          <i class="ri-checkbox-circle-fill"></i>
        </div>
        <div class="qs-info">
          <h6>Status Pekerjaan</h6>
          <p>Anda sudah menyelesaikan semua tugas harian. Pertahankan kinerja yang baik!</p>
        </div>
        @endif
      </div>
    </div>


    {{-- Bottom Navigation --}}
    <nav class="bottom-nav">
      <a href="{{ route('karyawan.home') }}" class="nav-item active">
        <i class="ri-home-5-fill nav-icon"></i>
        <span class="nav-label">Beranda</span>
      </a>
      <a href="{{ url('/dashboard/karyawan/jobs') }}" class="nav-item">
        <i class="ri-briefcase-4-line nav-icon"></i>
        <span class="nav-label">Jobs</span>
      </a>
      <a href="{{ route('karyawan.profile') }}" class="nav-item">
        <i class="ri-user-3-line nav-icon"></i>
        <span class="nav-label">Profile</span>
      </a>
    </nav>

  </div>

  <script>
    function updateClock() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      document.getElementById('realtime-clock').innerText = `${hours}:${minutes}:${seconds} WIB`;
    }
    
    // Update every second
    setInterval(updateClock, 1000);

    // Custom Toast Animation
    document.addEventListener("DOMContentLoaded", function() {
      const toast = document.getElementById("custom-toast");
      if (toast) {
        // Slide down
        setTimeout(() => {
          toast.classList.add("show");
        }, 100);

        // Auto hide
        setTimeout(() => {
          toast.classList.remove("show");
        }, 3500);
      }
    });
  </script>
@endsection
