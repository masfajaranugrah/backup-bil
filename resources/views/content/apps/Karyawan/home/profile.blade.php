@extends('layouts/blankLayout')

@section('title', 'Profile Karyawan')

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
  body { background: #e5e5e5; font-family: 'Inter', 'Nunito', sans-serif; }
  .mobile-wrapper {
    max-width: 480px;
    margin: 0 auto;
    background: var(--bg-color);
    min-height: 100vh;
    padding: 20px 18px 90px;
  }
  .profile-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 4px 16px rgba(13, 110, 253, 0.08);
  }
  .avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    margin-bottom: 12px;
  }
  .name { font-size: 20px; font-weight: 800; color: var(--text-main); }
  .role { color: var(--text-muted); font-size: 13px; margin-bottom: 16px; }
  .info-line { font-size: 13px; color: var(--text-main); margin-bottom: 8px; }
  .info-line b { color: var(--text-muted); font-weight: 600; }
  .logout-form { margin-top: 16px; }
  .btn-logout {
    width: 100%;
    border: 1px solid #fecaca;
    background: #fff1f2;
    color: #be123c;
    font-size: 14px;
    font-weight: 700;
    border-radius: 12px;
    padding: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
  }

  .bottom-nav {
    position: fixed;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    max-width: 480px;
    background: #fff;
    display: flex;
    justify-content: space-around;
    padding: 10px 16px;
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
  .nav-item.active { color: var(--primary); }
  .nav-icon { font-size: 22px; }
  .nav-label { font-size: 11px; font-weight: 700; }
</style>
@endsection

@section('content')
<div class="mobile-wrapper">
  <div class="profile-card">
    <img class="avatar" src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Staff') }}&background=0D6EFD&color=ffffff&size=160" alt="Avatar">
    <div class="name">{{ $user->name ?? 'Staff' }}</div>
    <div class="role">{{ $employee->jabatan ?? ucfirst($user->role ?? 'karyawan') }}</div>

    <div class="info-line"><b>Email:</b> {{ $user->email ?? '-' }}</div>
    <div class="info-line"><b>NIK:</b> {{ $employee->nik ?? '-' }}</div>
    <div class="info-line"><b>No HP:</b> {{ $employee->no_hp ?? '-' }}</div>
    <div class="info-line"><b>Tanggal Masuk:</b> {{ $employee?->tanggal_masuk ? \Illuminate\Support\Carbon::parse($employee->tanggal_masuk)->translatedFormat('d F Y') : '-' }}</div>

    <form action="{{ route('logout') }}" method="POST" class="logout-form">
      @csrf
      <button type="submit" class="btn-logout">
        <i class="ri-logout-box-r-line"></i> Logout
      </button>
    </form>
  </div>

  <nav class="bottom-nav">
    <a href="{{ route('karyawan.home') }}" class="nav-item">
      <i class="ri-home-5-line nav-icon"></i>
      <span class="nav-label">Beranda</span>
    </a>
    <a href="{{ route('jobs.index') }}" class="nav-item">
      <i class="ri-briefcase-4-line nav-icon"></i>
      <span class="nav-label">Jobs</span>
    </a>
    <a href="{{ route('karyawan.profile') }}" class="nav-item active">
      <i class="ri-user-3-fill nav-icon"></i>
      <span class="nav-label">Profile</span>
    </a>
  </nav>
</div>
@endsection
