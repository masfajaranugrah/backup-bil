@php
  $userName = $user->name ?? 'Karyawan';
  $userNik = $user->nik ?? 'ID-' . $user->id;
  $jamSekarang = now()->format('H:i:s');

  $btnText = 'Absens Masuk';
  if ($action === 'checkout')
    $btnText = 'Absens Pulang';
  if ($action === 'lembur_in')
    $btnText = 'Mulai Lembur';
  if ($action === 'lembur_out')
    $btnText = 'Selesai Lembur';

  $needTimePicker = in_array($action, ['checkout', 'lembur_in', 'lembur_out']);
@endphp

@extends('layouts/blankLayout')

@section('title', $title)

@section('page-style')
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html,
    body {
      width: 100%;
      height: 100%;
    }

    .layout-wrapper,
    .layout-page,
    .content-wrapper,
    .content-body,
    .container-xxl,
    .container-p-y {
      margin: 0 !important;
      padding: 0 !important;
      max-width: none !important;
    }

    body {
      background: #000;
      margin: 0;
      font-family: 'Inter', 'Nunito', sans-serif;
      overflow: hidden;
      height: 100svh;
    }

    /* ─── CAMERA VIEW ──────────────────── */
    #camera-view {
      position: fixed;
      inset: 0;
      background: #000;
      display: flex;
      flex-direction: column;
      z-index: 100;
      height: 100svh;
      width: 100vw;
    }

    #video-preview {
      width: 100%;
      flex: 1;
      object-fit: cover;
      display: block;
      background: #000;
    }

    .cam-header {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 18px;
      padding-top: calc(20px + env(safe-area-inset-top, 0px));
      background: linear-gradient(180deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0) 100%);
      z-index: 10;
    }

    .cam-header-title {
      font-weight: 700;
      color: #fff;
      font-size: 2rem;
      text-shadow: 0 1px 4px rgba(0, 0, 0, 0.4);
    }

    .cam-close-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      border: none;
      background: rgba(255, 255, 255, 0.18);
      backdrop-filter: blur(8px);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      text-decoration: none;
      transition: background 0.2s;
    }

    .cam-close-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      color: #fff;
    }

    .cam-bottom {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 12px;
      padding: 24px;
      padding-bottom: calc(24px + env(safe-area-inset-bottom, 0px) + 70px);
      background: linear-gradient(0deg, rgba(0, 0, 0, 0.7) 0%, transparent 100%);
      z-index: 10;
    }

    .cam-controls {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 32px;
    }

    .cam-flip-btn {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      border: none;
      background: rgba(255, 255, 255, 0.18);
      backdrop-filter: blur(8px);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      cursor: pointer;
      transition: background 0.2s, transform 0.3s;
    }

    .cam-flip-btn:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    .cam-flip-btn:active {
      transform: rotate(180deg);
    }

    .cam-helper {
      color: rgba(255, 255, 255, 0.92);
      font-size: 1rem;
      font-weight: 500;
      text-align: center;
      text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
    }

    .shutter-btn {
      width: 76px;
      height: 76px;
      border-radius: 50%;
      background: #fff;
      border: 5px solid rgba(255, 255, 255, 0.4);
      box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.2), 0 8px 28px rgba(0, 0, 0, 0.5);
      cursor: pointer;
      position: relative;
      transition: transform 0.15s;
      flex-shrink: 0;
    }

    .shutter-btn::before {
      content: '';
      position: absolute;
      inset: 5px;
      border-radius: 50%;
      background: #fff;
      transition: all 0.15s;
    }

    .shutter-btn:active {
      transform: scale(0.9);
    }

    .shutter-btn:active::before {
      inset: 10px;
    }

    /* ─── TIME PICKER VIEW (iOS-style) ──────────────── */
    #timepicker-view {
      position: fixed;
      inset: 0;
      background: #fff;
      display: none;
      flex-direction: column;
      z-index: 100;
      height: 100svh;
      width: 100vw;
    }

    .tp-header {
      display: flex;
      align-items: center;
      padding: 14px 20px;
      padding-top: calc(14px + env(safe-area-inset-top, 0px));
      background: #fff;
      border-bottom: 1px solid #E5E7EB;
    }

    .tp-header a {
      font-size: 22px;
      color: #111827;
      text-decoration: none;
      margin-right: 14px;
    }

    .tp-header h1 {
      font-size: 16px;
      font-weight: 700;
      color: #111827;
      margin: 0;
    }

    .tp-body {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px 20px;
      gap: 20px;
    }

    .tp-photo-thumb {
      width: 90px;
      height: 120px;
      object-fit: cover;
      border-radius: 10px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }

    .tp-label {
      font-size: 14px;
      color: #6B7280;
      font-weight: 500;
    }

    .tp-picker-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
      position: relative;
      background: #F3F4F6;
      border-radius: 16px;
      padding: 0 20px;
      overflow: hidden;
    }

    .tp-col {
      width: 72px;
      height: 220px;
      overflow-y: scroll;
      scroll-snap-type: y mandatory;
      -webkit-overflow-scrolling: touch;
      scrollbar-width: none;
      z-index: 1;
    }

    .tp-col::-webkit-scrollbar {
      display: none;
    }

    .tp-spacer {
      height: 44px;
      scroll-snap-align: center;
      flex-shrink: 0;
    }

    .tp-item {
      height: 44px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      font-weight: 600;
      color: rgba(0, 0, 0, 0.18);
      scroll-snap-align: center;
      user-select: none;
      cursor: pointer;
      flex-shrink: 0;
      transition: color 0.15s, font-size 0.15s;
    }

    .tp-item.active {
      color: #111827;
      font-size: 28px;
      font-weight: 800;
    }

    .tp-item.near {
      color: rgba(0, 0, 0, 0.35);
      font-size: 20px;
    }

    .tp-sep {
      font-size: 28px;
      font-weight: 800;
      color: #111827;
      z-index: 3;
      padding: 0 2px;
    }

    .tp-highlight {
      position: absolute;
      left: 12px;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      height: 44px;
      background: rgba(255, 255, 255, 0.85);
      border-radius: 10px;
      pointer-events: none;
      z-index: 0;
    }

    .tp-mask-t,
    .tp-mask-b {
      position: absolute;
      left: 0;
      right: 0;
      height: 80px;
      pointer-events: none;
      z-index: 2;
    }

    .tp-mask-t {
      top: 0;
      background: linear-gradient(180deg, rgba(243, 244, 246, 0.95), transparent);
      border-radius: 16px 16px 0 0;
    }

    .tp-mask-b {
      bottom: 0;
      background: linear-gradient(0deg, rgba(243, 244, 246, 0.95), transparent);
      border-radius: 0 0 16px 16px;
    }

    .tp-bottom {
      padding: 16px 20px;
      padding-bottom: calc(16px + env(safe-area-inset-bottom, 0px));
      background: #fff;
      border-top: 1px solid #E5E7EB;
    }

    .btn-next {
      width: 100%;
      background: #0D6EFD;
      color: white;
      border: none;
      padding: 14px;
      border-radius: 99px;
      font-weight: 600;
      font-size: 15px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-next:hover {
      background: #0b5ed7;
    }

    /* ─── REVIEW VIEW ──────────────────── */
    #review-view {
      position: fixed;
      inset: 0;
      background: #fff;
      display: none;
      flex-direction: column;
      z-index: 100;
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
      height: 100svh;
      width: 100vw;
    }

    .review-header {
      display: flex;
      align-items: center;
      padding: 14px 20px;
      padding-top: calc(14px + env(safe-area-inset-top, 0px));
      background: #fff;
      border-bottom: 1px solid #E5E7EB;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .review-header a {
      font-size: 22px;
      color: #111827;
      text-decoration: none;
      margin-right: 14px;
    }

    .review-header h1 {
      font-size: 16px;
      font-weight: 700;
      color: #111827;
      margin: 0;
    }

    .review-body {
      flex: 1;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .review-user {
      text-align: center;
      margin-bottom: 16px;
    }

    .review-user-name {
      font-weight: 800;
      font-size: 16px;
      color: #111827;
    }

    .review-user-nik {
      color: #6B7280;
      font-size: 13px;
      margin-top: 2px;
    }

    .review-photo {
      width: 160px;
      height: 210px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
      margin-bottom: 20px;
    }

    .review-time {
      text-align: center;
      margin-bottom: 16px;
    }

    .review-time-label {
      color: #6B7280;
      font-size: 13px;
      margin-bottom: 6px;
    }

    .review-time-value {
      font-size: 28px;
      font-weight: 800;
      color: #111827;
    }

    .review-coords {
      background: #EEF2FF;
      border-radius: 10px;
      padding: 12px 16px;
      text-align: center;
      font-size: 12px;
      color: #4338CA;
      font-family: 'JetBrains Mono', monospace;
      width: 100%;
      max-width: 340px;
      margin-bottom: 8px;
    }

    .review-coords a {
      color: #4338CA;
      text-decoration: underline;
    }

    .review-status {
      text-align: center;
      font-size: 12px;
      color: #6B7280;
      margin-bottom: 6px;
    }

    .review-refresh {
      text-align: center;
      font-size: 12px;
      margin-bottom: 20px;
    }

    .review-refresh a {
      color: #0D6EFD;
      font-weight: 600;
      text-decoration: none;
    }

    .review-submit {
      padding: 16px 20px;
      padding-bottom: calc(16px + env(safe-area-inset-bottom, 0px));
      background: #fff;
      border-top: 1px solid #E5E7EB;
      position: sticky;
      bottom: 0;
      z-index: 10;
    }

    .btn-submit {
      width: 100%;
      background: #0D6EFD;
      color: white;
      border: none;
      padding: 14px;
      border-radius: 99px;
      font-weight: 600;
      font-size: 15px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      transition: background 0.2s;
    }

    .btn-submit:hover {
      background: #0b5ed7;
    }

    .btn-submit:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
  </style>
@endsection

@section('content')

  {{-- ═══ CAMERA VIEW ═══ --}}
  <div id="camera-view">
    <video id="video-preview" autoplay playsinline muted></video>

    <div class="cam-header">
      <span class="cam-header-title">{{ $title }}</span>
      <a href="{{ route('karyawan.home') }}" class="cam-close-btn"><i class="ri-close-line"></i></a>
    </div>

    <div class="cam-bottom">
      <div class="cam-helper">Arahkan kamera ke wajah Anda, lalu tekan potret.</div>
      <div class="cam-controls">
        <div class="cam-flip-btn" id="btn-flip"><i class="ri-camera-switch-line"></i></div>
        <div class="shutter-btn" id="btn-capture"></div>
        <div style="width:48px"></div>
      </div>
    </div>
  </div>

  {{-- ═══ TIME PICKER VIEW (checkout, lembur_in, lembur_out) ═══ --}}
  @if($needTimePicker)
    <div id="timepicker-view">
      <div class="tp-header">
        <a href="#" id="btn-tp-back"><i class="ri-arrow-left-line"></i></a>
        <h1>Pilih Jam</h1>
      </div>
      <div class="tp-body">
        <img id="tp-photo-thumb" src="" alt="Foto" class="tp-photo-thumb">
        <div class="tp-label">
          @if($action === 'checkout') Pilih jam pulang
          @elseif($action === 'lembur_in') Pilih jam mulai lembur
          @else Pilih jam selesai lembur
          @endif
        </div>
        <div class="tp-picker-wrap">
          <div class="tp-mask-t"></div>
          <div class="tp-mask-b"></div>
          <div class="tp-highlight"></div>
          <div class="tp-col" id="hour-col"></div>
          <div class="tp-sep">:</div>
          <div class="tp-col" id="minute-col"></div>
        </div>
      </div>
      <div class="tp-bottom">
        <button type="button" class="btn-next" id="btn-tp-next">
          <i class="ri-arrow-right-line"></i> Lanjut
        </button>
      </div>
    </div>
  @endif

  {{-- ═══ REVIEW VIEW ═══ --}}
  <div id="review-view">
    <div class="review-header">
      <a href="#" id="btn-retake"><i class="ri-arrow-left-line"></i></a>
      <h1>{{ $title }}</h1>
    </div>

    <div class="review-body">
      <div class="review-user">
        <div class="review-user-name">{{ $userName }}</div>
        <div class="review-user-nik">{{ $userNik }}</div>
      </div>

      <img id="photo-result" src="" alt="Foto" class="review-photo">

      <div class="review-time">
        <div class="review-time-label">Jam {{ str_replace('Catat Jam ', '', $title) }}</div>
        <div class="review-time-value" id="current-time">{{ $jamSekarang }}</div>
      </div>

      <div class="review-coords" id="location-coords" style="display: none;">
        📍 <span id="coords-text"></span>
        <br>
        <a href="#" id="gmaps-link" target="_blank">🗺️ Lihat di Google Maps</a>
      </div>
      <div class="review-status" id="location-status">Mendapatkan lokasi...</div>
      <div class="review-refresh">
        <a href="#" id="btn-refresh-loc">🔄 Perbaharui lokasi</a>
      </div>
    </div>

    <form id="attendance-form" action="{{ route('absensi.kirim') }}" method="POST" enctype="multipart/form-data"
      class="review-submit">
      @csrf
      <input type="hidden" name="action" value="{{ $action }}">
      <input type="hidden" name="redirect_to_home" value="1">
      <input type="hidden" name="latitude" id="lat-input">
      <input type="hidden" name="longitude" id="lng-input">
      <input type="hidden" name="gps_accuracy" id="gps-accuracy-input">
      <input type="hidden" name="is_mock_location" id="is-mock-location-input" value="0">
      <input type="hidden" name="manual_time" id="manual-time-input">
      <input type="file" name="photo" id="photo-input" style="display: none;">

      <button type="submit" class="btn-submit" id="btn-submit" disabled>
        <i class="ri-login-box-line"></i> {{ $btnText }}
      </button>
    </form>
  </div>

  <script>
    const video = document.getElementById('video-preview');
    const photoResult = document.getElementById('photo-result');
    const cameraView = document.getElementById('camera-view');
    const reviewView = document.getElementById('review-view');
    const btnCapture = document.getElementById('btn-capture');
    const btnRetake = document.getElementById('btn-retake');
    const btnSubmit = document.getElementById('btn-submit');
    const photoInput = document.getElementById('photo-input');
    const latInput = document.getElementById('lat-input');
    const lngInput = document.getElementById('lng-input');
    const gpsAccuracyInput = document.getElementById('gps-accuracy-input');
    const isMockLocationInput = document.getElementById('is-mock-location-input');
    const locationCoords = document.getElementById('location-coords');
    const coordsText = document.getElementById('coords-text');
    const gmapsLink = document.getElementById('gmaps-link');
    const locationStatus = document.getElementById('location-status');
    const btnRefreshLoc = document.getElementById('btn-refresh-loc');
    const manualTimeInput = document.getElementById('manual-time-input');

    let stream = null;
    let watchId = null;
    let capturedDataUrl = null;
    let useFrontCamera = true;
    const ACTION = '{{ $action }}';
    const NEED_TP = {{ $needTimePicker ? 'true' : 'false' }};

    // ─── Camera ───────────────────────────────────────
    async function startCamera() {
      try {
        stream = await navigator.mediaDevices.getUserMedia({
          video: { facingMode: useFrontCamera ? 'user' : 'environment', width: { ideal: 1280 }, height: { ideal: 720 } },
          audio: false
        });
        video.srcObject = stream;
      } catch (err) {
        alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin.");
      }
    }

    function stopCamera() {
      if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    }

    // ─── Flip Camera ──────────────────────────────────
    document.getElementById('btn-flip').addEventListener('click', () => {
      useFrontCamera = !useFrontCamera;
      stopCamera();
      startCamera();
    });

    // ─── Capture ──────────────────────────────────────
    btnCapture.addEventListener('click', () => {
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

      capturedDataUrl = canvas.toDataURL('image/jpeg', 0.85);
      photoResult.src = capturedDataUrl;

      fetch(capturedDataUrl).then(r => r.blob()).then(blob => {
        const dt = new DataTransfer();
        dt.items.add(new File([blob], "attendance.jpg", { type: "image/jpeg" }));
        photoInput.files = dt.files;
      });

      stopCamera();
      cameraView.style.display = 'none';

      if (NEED_TP) {
        // Show time picker first
        document.getElementById('tp-photo-thumb').src = capturedDataUrl;
        document.getElementById('timepicker-view').style.display = 'flex';
        initTimePicker();
      } else {
        // Checkin: go straight to review
        reviewView.style.display = 'flex';
        document.getElementById('current-time').innerText = new Date().toLocaleTimeString('id-ID', { hour12: false });
      }
    });

    // ─── Retake ───────────────────────────────────────
    btnRetake.addEventListener('click', (e) => {
      e.preventDefault();
      reviewView.style.display = 'none';
      cameraView.style.display = 'flex';
      startCamera();
    });

    // ═══ iOS-STYLE TIME PICKER ════════════════════════
    if (NEED_TP) {
      const ITEM_H = 44;
      let selectedHour = new Date().getHours();
      let selectedMinute = new Date().getMinutes();

      function pad(n) { return String(n).padStart(2, '0'); }

      function populateColumn(col, count) {
        col.innerHTML = '';
        // 2 spacers top
        for (let i = 0; i < 2; i++) {
          const s = document.createElement('div');
          s.className = 'tp-spacer';
          col.appendChild(s);
        }
        for (let i = 0; i < count; i++) {
          const el = document.createElement('div');
          el.className = 'tp-item';
          el.dataset.value = i;
          el.textContent = pad(i);
          el.addEventListener('click', () => {
            col.scrollTo({ top: i * ITEM_H, behavior: 'smooth' });
          });
          col.appendChild(el);
        }
        // 2 spacers bottom
        for (let i = 0; i < 2; i++) {
          const s = document.createElement('div');
          s.className = 'tp-spacer';
          col.appendChild(s);
        }
      }

      function getSelectedValue(col) {
        return Math.round(col.scrollTop / ITEM_H);
      }

      function updateStyles(col, count) {
        const sel = getSelectedValue(col);
        const items = col.querySelectorAll('.tp-item');
        items.forEach(item => {
          const v = parseInt(item.dataset.value);
          item.classList.remove('active', 'near');
          if (v === sel) item.classList.add('active');
          else if (Math.abs(v - sel) === 1) item.classList.add('near');
        });
      }

      function initTimePicker() {
        const hCol = document.getElementById('hour-col');
        const mCol = document.getElementById('minute-col');

        selectedHour = new Date().getHours();
        selectedMinute = new Date().getMinutes();

        populateColumn(hCol, 24);
        populateColumn(mCol, 60);

        // Scroll to current time
        requestAnimationFrame(() => {
          hCol.scrollTop = selectedHour * ITEM_H;
          mCol.scrollTop = selectedMinute * ITEM_H;
          updateStyles(hCol, 24);
          updateStyles(mCol, 60);
        });

        // Debounced scroll handlers
        let hTimer, mTimer;
        hCol.addEventListener('scroll', () => {
          clearTimeout(hTimer);
          hTimer = setTimeout(() => {
            selectedHour = getSelectedValue(hCol);
            if (selectedHour < 0) selectedHour = 0;
            if (selectedHour > 23) selectedHour = 23;
            updateStyles(hCol, 24);
          }, 80);
        });
        mCol.addEventListener('scroll', () => {
          clearTimeout(mTimer);
          mTimer = setTimeout(() => {
            selectedMinute = getSelectedValue(mCol);
            if (selectedMinute < 0) selectedMinute = 0;
            if (selectedMinute > 59) selectedMinute = 59;
            updateStyles(mCol, 60);
          }, 80);
        });
      }

      // Back from time picker to camera
      document.getElementById('btn-tp-back').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('timepicker-view').style.display = 'none';
        cameraView.style.display = 'flex';
        startCamera();
      });

      // Next: go to review with selected time
      document.getElementById('btn-tp-next').addEventListener('click', () => {
        const timeStr = pad(selectedHour) + ':' + pad(selectedMinute);
        manualTimeInput.value = timeStr;
        document.getElementById('current-time').innerText = timeStr + ':00';
        document.getElementById('timepicker-view').style.display = 'none';
        reviewView.style.display = 'flex';
      });
    }

    // ─── GPS ──────────────────────────────────────────
    function setLocation(lat, lng, accuracy = null, mocked = false) {
      latInput.value = lat;
      lngInput.value = lng;
      gpsAccuracyInput.value = accuracy ?? '';
      isMockLocationInput.value = mocked ? '1' : '0';
      locationCoords.style.display = 'block';
      const accText = accuracy ? ` (akurasi ${Math.round(accuracy)}m)` : '';
      coordsText.innerText = `${lat.toFixed(7)}, ${lng.toFixed(7)}${accText}`;
      gmapsLink.href = `https://www.google.com/maps?q=${lat.toFixed(7)},${lng.toFixed(7)}&z=20`;
      locationStatus.innerText = '✓ Lokasi berhasil didapat';
      locationStatus.style.color = '#065F46';
      btnSubmit.disabled = false;
    }

    function grabLocation() {
      locationStatus.innerText = 'Mendapatkan lokasi...';
      locationStatus.style.color = '';
      locationCoords.style.display = 'none';
      if (watchId) navigator.geolocation.clearWatch(watchId);

      if (!navigator.geolocation) {
        locationStatus.innerText = 'GPS tidak tersedia.';
        btnSubmit.disabled = false;
        return;
      }

      let bestAcc = Infinity;

      navigator.geolocation.getCurrentPosition(
        (pos) => {
          const { latitude: lat, longitude: lng, accuracy: acc } = pos.coords;
          const mocked = !!(pos?.coords?.mocked || pos?.mocked);
          bestAcc = acc;
          setLocation(lat, lng, acc, mocked);

          watchId = navigator.geolocation.watchPosition(
            (p) => {
              if (p.coords.accuracy < bestAcc) {
                bestAcc = p.coords.accuracy;
                const watchMocked = !!(p?.coords?.mocked || p?.mocked);
                setLocation(p.coords.latitude, p.coords.longitude, p.coords.accuracy, watchMocked);
              }
              if (bestAcc <= 5) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
              }
            },
            () => { },
            { enableHighAccuracy: true, maximumAge: 0, timeout: 15000 }
          );

          setTimeout(() => {
            if (watchId) { navigator.geolocation.clearWatch(watchId); watchId = null; }
          }, 15000);
        },
        (err) => {
          locationStatus.innerText = 'Gagal mendapatkan lokasi. Pastikan GPS aktif.';
          locationStatus.style.color = '#991B1B';
          btnSubmit.disabled = false;
        },
        { enableHighAccuracy: true, maximumAge: 0, timeout: 8000 }
      );
    }

    btnRefreshLoc.addEventListener('click', (e) => { e.preventDefault(); grabLocation(); });

    // ─── Start ────────────────────────────────────────
    startCamera();
    grabLocation();

    window.addEventListener('beforeunload', () => {
      if (watchId) navigator.geolocation.clearWatch(watchId);
      stopCamera();
    });
  </script>
@endsection