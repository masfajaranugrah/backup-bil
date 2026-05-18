@php
  $userName = $userName ?? 'Staff';
@endphp

@extends('layouts/blankLayout')

@section('title', 'Slip Gaji')

@section('page-style')
  <style>
    :root {
      --primary: #0D6EFD;
      --primary-soft: rgba(13, 110, 253, 0.1);
      --bg-color: #F8F9FA;
      --text-main: #1F2937;
      --text-muted: #6B7280;
      --card-bg: #FFFFFF;
      --border-color: #E5E7EB;
      --success: #10B981;
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
      padding-bottom: 90px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
    }

    .header-section {
      background: linear-gradient(135deg, #0D6EFD 0%, #0043A8 100%);
      padding: 30px 24px 60px;
      border-bottom-left-radius: 32px;
      border-bottom-right-radius: 32px;
      color: white;
      position: relative;
      overflow: hidden;
    }

    .header-section::after {
      content: '';
      position: absolute;
      top: -50px;
      right: -50px;
      width: 150px;
      height: 150px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
    }

    .page-title {
      font-size: 24px;
      font-weight: 800;
      margin-bottom: 8px;
    }

    .page-subtitle {
      font-size: 13px;
      opacity: 0.85;
      line-height: 1.4;
    }

    .content-section {
      padding: 0 20px;
      margin-top: -30px;
      position: relative;
      z-index: 2;
    }

    .btn-back-home {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px;
      margin-bottom: 14px;
      border-radius: 14px;
      background: #fff;
      border: 1px solid #dbe3f2;
      color: #1f2937;
      text-decoration: none;
      font-weight: 700;
      font-size: 14px;
    }

    .salary-card {
      background: var(--card-bg);
      border-radius: 20px;
      padding: 20px;
      margin-bottom: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.8);
      transition: transform 0.2s;
    }

    .salary-card:hover {
      transform: translateY(-2px);
    }

    .salary-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      padding-bottom: 16px;
      border-bottom: 1px dashed var(--border-color);
    }

    .salary-icon {
      width: 40px;
      height: 40px;
      background: var(--primary-soft);
      color: var(--primary);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }

    .salary-period-wrapper {
      flex: 1;
      margin-left: 12px;
    }

    .salary-period-label {
      font-size: 11px;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      font-weight: 700;
      margin-bottom: 4px;
    }

    .salary-period-date {
      font-size: 14px;
      font-weight: 700;
      color: var(--text-main);
    }

    .salary-status {
      background: rgba(16, 185, 129, 0.1);
      color: var(--success);
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .salary-amount-wrapper {
      margin-bottom: 20px;
    }

    .salary-amount-label {
      font-size: 12px;
      color: var(--text-muted);
      margin-bottom: 4px;
    }

    .salary-total {
      font-size: 28px;
      font-weight: 800;
      color: var(--text-main);
      letter-spacing: -0.5px;
    }

    .btn-view-slip {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      background: var(--primary);
      color: #fff;
      border-radius: 14px;
      font-size: 14px;
      font-weight: 700;
      padding: 14px;
      text-decoration: none;
      transition: all 0.2s;
      border: none;
    }

    .btn-view-slip:hover {
      background: #0052cc;
      color: #fff;
    }

    .empty-state {
      background: #fff;
      border: 1px dashed #cbd5e1;
      border-radius: 20px;
      padding: 40px 20px;
      text-align: center;
      color: var(--text-muted);
      font-size: 14px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .empty-icon {
      font-size: 48px;
      color: #cbd5e1;
      margin-bottom: 16px;
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
      padding: 12px 16px;
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
      font-size: 11px;
      font-weight: 700;
    }
  </style>
@endsection

@section('content')
  <div class="mobile-wrapper">
    <div class="header-section">
      <div class="page-title">Slip Gaji</div>
      <div class="page-subtitle">Informasi gaji untuk {{ $userName }}. Data tersinkron dari menu Gaji Admin.</div>
    </div>

    <div class="content-section">
      <a href="{{ route('karyawan.home') }}" class="btn-back-home">
        <i class="ri-arrow-left-line"></i> Kembali ke Home
      </a>

      @if (!$employee)
        <div class="empty-state">
          <i class="ri-error-warning-line empty-icon"></i>
          <div>Data karyawan belum terhubung. Pastikan nama akun sama dengan nama karyawan pada data admin.</div>
        </div>
      @elseif ($salaries->isEmpty())
        <div class="empty-state">
          <i class="ri-file-list-3-line empty-icon"></i>
          <div>Belum ada slip gaji untuk akun ini.</div>
        </div>
      @else
        @foreach ($salaries as $salary)
          <div class="salary-card">
            <div class="salary-header">
              <div class="salary-icon">
                <i class="ri-wallet-3-line"></i>
              </div>
              <div class="salary-period-wrapper">
                <div class="salary-period-label">Periode</div>
                <div class="salary-period-date">{{ optional($salary->created_at)->translatedFormat('F Y') }}</div>
              </div>
              <div class="salary-status">
                <i class="ri-checkbox-circle-fill"></i> Dibayar
              </div>
            </div>
            
            <div class="salary-amount-wrapper">
              <div class="salary-amount-label">Total Penerimaan</div>
              <div class="salary-total">Rp {{ number_format((float) $salary->grand_total, 0, ',', '.') }}</div>
            </div>
            
            <a href="{{ route('gaji.print', $salary->id) }}" target="_blank" class="btn-view-slip">
              <i class="ri-download-2-line"></i> Download Slip
            </a>
          </div>
        @endforeach
      @endif
    </div>

    <nav class="bottom-nav">
      <a href="{{ route('karyawan.home') }}" class="nav-item">
        <i class="ri-home-5-line nav-icon"></i>
        <span class="nav-label">Beranda</span>
      </a>
      <a href="{{ url('/karyawan/jobs') }}" class="nav-item">
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
