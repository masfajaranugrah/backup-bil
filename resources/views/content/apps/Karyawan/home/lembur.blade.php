@php
  $lemburIn = $lemburIn ?? '--:--:--';
  $lemburOut = $lemburOut ?? '--:--:--';
  $lemburList = $lemburList ?? collect();
@endphp

@extends('layouts/blankLayout')

@section('title', 'Menu Lembur')

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
    padding: 16px 20px 90px;
  }

  .page-head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
  }

  .btn-back {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: 1px solid var(--border-color);
    background: #fff;
    color: #111827;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 18px;
  }

  .page-title {
    font-size: 19px;
    font-weight: 800;
    color: #111827;
  }

  .lembur-card {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
  }

  .lembur-title {
    font-size: 18px;
    font-weight: 800;
    color: var(--text-main);
    margin-bottom: 4px;
  }

  .lembur-desc {
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 12px;
  }

  .lembur-actions {
    display: flex;
    gap: 10px;
    margin-bottom: 14px;
  }

  .btn-lembur {
    flex: 1;
    border-radius: 10px;
    padding: 10px;
    font-size: 16px;
    font-weight: 700;
    text-align: center;
    text-decoration: none;
    border: 1px solid #dbe3f2;
    color: var(--text-main);
    background: #fff;
  }

  .btn-lembur-primary {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
  }

  .lembur-times {
    border: 1px solid var(--border-color);
    border-radius: 999px;
    padding: 10px 14px;
    color: #326fb8;
    font-size: 12px;
    text-align: center;
    font-weight: 600;
  }

  .lembur-list {
    margin-bottom: 18px;
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 14px;
  }

  .lembur-list-title {
    font-size: 14px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 10px;
  }

  .lembur-row {
    font-size: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
  }

  .lembur-row:last-child {
    border-bottom: none;
  }

  .lembur-date {
    color: #475569;
    font-weight: 600;
  }

  .lembur-hour {
    color: #0D6EFD;
    font-weight: 700;
  }

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
</style>
@endsection

@section('content')
<div class="mobile-wrapper">
  <div class="page-head">
    <a href="{{ route('karyawan.home') }}" class="btn-back"><i class="ri-arrow-left-line"></i></a>
    <div class="page-title">Lembur</div>
  </div>

  <div class="lembur-card">
    <div class="lembur-title">Menu Lembur</div>
    <div class="lembur-desc">Absensi mulai lembur dan selesai lembur.</div>
    <div class="lembur-actions">
      <a href="{{ route('absensi.capture', ['action' => 'lembur_in']) }}" class="btn-lembur btn-lembur-primary">Masuk</a>
      <a href="{{ route('absensi.capture', ['action' => 'lembur_out']) }}" class="btn-lembur">Keluar</a>
    </div>
    <div class="lembur-times">
      Mulai: {{ $lemburIn }} WIB | Selesai: {{ $lemburOut }} WIB
    </div>
  </div>

  <div class="lembur-list">
    <div class="lembur-list-title">List Lemburan</div>
    @forelse ($lemburList as $item)
      @php
        $jamLemburIn = $item->lembur_in ? \Illuminate\Support\Carbon::parse($item->lembur_in)->setTimezone('Asia/Jakarta')->format('H:i') : '--:--';
        $jamLemburOut = $item->lembur_out ? \Illuminate\Support\Carbon::parse($item->lembur_out)->setTimezone('Asia/Jakarta')->format('H:i') : '--:--';
      @endphp
      <div class="lembur-row">
        <div class="lembur-date">{{ \Illuminate\Support\Carbon::parse($item->date)->translatedFormat('d M Y') }}</div>
        <div class="lembur-hour">Masuk lembur: {{ $jamLemburIn }} | Pulang lembur: {{ $jamLemburOut }}</div>
      </div>
    @empty
      <div class="lembur-row">
        <div class="lembur-date">Belum ada data lembur.</div>
      </div>
    @endforelse
  </div>

  <nav class="bottom-nav">
    <a href="{{ route('karyawan.home') }}" class="nav-item active">
      <i class="ri-home-5-fill nav-icon"></i>
      <span class="nav-label">Beranda</span>
    </a>
    <a href="{{ url('/karyawan/jobs') }}" class="nav-item">
      <i class="ri-briefcase-4-line nav-icon"></i>
      <span class="nav-label">Jobs</span>
    </a>
    <a href="{{ route('karyawan.profile') }}" class="nav-item">
      <i class="ri-user-3-line nav-icon"></i>
      <span class="nav-label">Profile</span>
    </a>
  </nav>
</div>
@endsection
