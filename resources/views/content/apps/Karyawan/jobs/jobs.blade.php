@extends('layouts/blankLayout')

@section('title', 'Daftar Ticket Teknisi')

@section('content')
@php
    $user = auth()->user();
    
    // Generate full current month dates (1..last day)
    $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
    $endOfMonth = \Carbon\Carbon::now()->endOfMonth();
    $dates = [];
    for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
        $dates[] = $date->copy();
    }
    
    // Today's date string for default active state
    $todayStr = \Carbon\Carbon::now()->format('Y-m-d');

    // Count active jobs per assignment date for the badges
    $dateCounts = [];
    foreach($tickets as $ticket) {
        $tDate = $ticket->assignment_date ? \Carbon\Carbon::parse($ticket->assignment_date) : $ticket->created_at;
        $ticketDateStr = $tDate->format('Y-m-d');
        if($ticket->status !== 'finished') {
            if(!isset($dateCounts[$ticketDateStr])) {
                $dateCounts[$ticketDateStr] = 0;
            }
            $dateCounts[$ticketDateStr]++;
        }
    }
@endphp

<div class="mobile-wrapper mx-auto">
  {{-- Header Section (Beige background) --}}
  <div class="mobile-header">
    <a href="{{ route('karyawan.home') }}" class="btn-back-jobs">
      <i class="ri-arrow-left-line"></i> Kembali
    </a>
    <div class="mb-3">
      <p class="greeting-text mb-1">Hello {{ explode(' ', $user->name)[0] }} 👋</p>
      <h4 class="fw-bold m-0" style="color: #FFFFFF;">Pekerjaan hari ini</h4>
    </div>

    {{-- Calendar Strip --}}
    <div class="month-label mb-2">{{ \Carbon\Carbon::now()->translatedFormat('F, Y') }}</div>
    <div class="calendar-strip" id="calendarStrip">
      @foreach($dates as $date)
        @php
            $dateStr = $date->format('Y-m-d');
            $isToday = $dateStr === $todayStr;
            $jobCount = $dateCounts[$dateStr] ?? 0;
        @endphp
        <div class="calendar-item {{ $isToday ? 'active' : '' }}" data-date="{{ $dateStr }}">
          @if($jobCount > 0)
            <div class="job-badge">{{ $jobCount }}</div>
          @endif
          <span class="cal-day">{{ $date->translatedFormat('D') }}</span>
          <span class="cal-date">{{ $date->format('d') }}</span>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Body Section --}}
  <div class="mobile-body">
    
    {{-- Search --}}
    <div class="search-wrapper mb-4">
      <i class="ri-search-line search-icon"></i>
      <input type="text" id="searchInput" class="search-input" placeholder="Cari tiket atau pelanggan...">
    </div>

    {{-- Tabs --}}
    <div class="tabs-wrapper mb-4">
      <div class="tab-item" data-filter="urgent">Urgent</div>
      <div class="tab-item active" data-filter="progress">Proses</div>
      <div class="tab-item" data-filter="finished">Selesai</div>
    </div>

    <div class="ticket-count mb-3">
      <span id="resultCount" class="fw-bold" style="color: #0D6EFD;">{{ $tickets->count() }}</span> tickets found
    </div>

    {{-- Ticket List --}}
    <div id="ticketContainer" class="ticket-list">
      @forelse($tickets as $ticket)
        @php
            $tDate = $ticket->assignment_date ? \Carbon\Carbon::parse($ticket->assignment_date) : $ticket->created_at;
            $ticketDate = $tDate->format('Y-m-d');
            $ticketDay = $tDate->format('d');
            $ticketMonthShort = $tDate->translatedFormat('M');
            
            // Tentukan warna blok kiri berdasarkan status
            $leftColorClass = 'bg-blue';
            if($ticket->status == 'pending') {
                $leftColorClass = 'bg-orange';
                if(!empty($ticket->technician_note)) {
                    $leftColorClass = 'bg-red'; // Merah jika minta reschedule
                }
            }
            if($ticket->status == 'progress') $leftColorClass = 'bg-purple';
            if($ticket->priority == 'urgent') $leftColorClass = 'bg-red';
        @endphp
        
        <div class="ticket-card-ui" data-id="{{ $ticket->id }}" data-date="{{ $ticketDate }}" data-status="{{ $ticket->status }}" data-priority="{{ strtolower($ticket->priority) }}">
          
          {{-- Left Block --}}
          <div class="left-date-block {{ $leftColorClass }}">
            <div class="date-num">{{ $ticketDay }}</div>
            <div class="date-month">{{ $ticketMonthShort }}</div>
            <div class="time-badge mt-2">
              <i class="ri-time-line"></i> {{ $ticket->created_at->format('H:i') }}
            </div>
          </div>
          
          {{-- Right Block --}}
          <div class="right-content-block">
            <div class="d-flex justify-content-between align-items-start">
              <h6 class="ticket-title m-0 text-truncate">
                @if($ticket->ticket_type == 'internal')
                  {{ $ticket->title }}
                @else
                  Kendala: {{ \Illuminate\Support\Str::limit($ticket->issue_description, 25) }}
                @endif
              </h6>
              <i class="ri-arrow-right-s-line text-muted"></i>
            </div>
            
            <div class="customer-info mt-1">
              @if($ticket->ticket_type == 'internal')
                <i class="ri-tools-fill text-danger"></i> <span class="text-muted">Internal Task</span>
              @else
                <i class="ri-user-location-fill text-danger"></i> <span class="text-muted">{{ optional($ticket->pelanggan)->nama_lengkap ?? '-' }}</span>
              @endif
            </div>

            <div class="ticket-meta mt-3">
              <span class="meta-item"><i class="ri-map-pin-line"></i> {{ $ticket->ticket_type == 'internal' ? 'Lokasi Jaringan' : 'Lokasi Pelanggan' }}</span>
              <span class="meta-item"><i class="ri-flag-2-line"></i> {{ ucfirst($ticket->priority) }}</span>
            </div>

            @if($ticket->status == 'pending' && !empty($ticket->technician_note))
            <div class="reschedule-alert-realtime mt-2 p-2 rounded" style="background-color: #FEE2E2; border-left: 3px solid #EF4444;">
              <span class="d-block text-danger fw-bold" style="font-size: 11px;"><i class="ri-error-warning-fill"></i> Reschedule Note:</span>
              <span class="text-danger note-content" style="font-size: 12px;">{{ $ticket->technician_note }}</span>
            </div>
            @endif

            <div class="action-buttons mt-3 d-flex gap-2">
              @if(in_array($ticket->status, ['pending', 'assigned']))
                <button type="button" class="btn-action-primary w-100 flex-grow-1 btn-update-status"
                  data-id="{{ $ticket->id }}"
                  data-status="progress"
                  data-url="{{ route('jobs.autoUpdateStatus', $ticket->id) }}"
                  data-token="{{ csrf_token() }}">
                  <span class="btn-text">Kerjakan</span>
                </button>
              @elseif($ticket->status === 'progress')
                <button type="button" class="btn-action-primary w-100 flex-grow-1 btn-update-status"
                  data-id="{{ $ticket->id }}"
                  data-status="finished"
                  data-url="{{ route('jobs.autoUpdateStatus', $ticket->id) }}"
                  data-token="{{ csrf_token() }}">
                  <span class="btn-text">Selesai</span>
                </button>
              @endif

              {{-- Reschedule Button --}}
              <button type="button" class="btn-action-icon text-warning border border-warning" title="Minta Reschedule"
                data-id="{{ $ticket->id }}"
                data-url="{{ route('jobs.autoUpdateStatus', $ticket->id) }}"
                data-token="{{ csrf_token() }}"
                onclick="openRescheduleModal('{{ $ticket->id }}', '{{ route('jobs.autoUpdateStatus', $ticket->id) }}', '{{ csrf_token() }}')">
                <i class="ri-calendar-event-line"></i>
              </button>

              {{-- Detail Button (Opens Modal) --}}
              <button type="button" class="btn-action-icon text-primary border border-primary" title="Detail Tiket" onclick="openDetailModal({{ json_encode([
                'id' => $ticket->id,
                'title' => $ticket->ticket_type == 'internal' ? $ticket->title : 'Kendala Pelanggan',
                'customer' => $ticket->ticket_type == 'internal' ? 'Internal Task' : (optional($ticket->pelanggan)->nama_lengkap ?? '-'),
                'description' => $ticket->issue_description,
                'priority' => ucfirst($ticket->priority),
                'status' => ucfirst($ticket->status),
                'date' => $ticket->created_at->format('d M Y, H:i'),
                'note' => $ticket->additional_note ?? '-',
                'category' => $ticket->category ?? '-',
                'is_internal' => $ticket->ticket_type == 'internal'
              ]) }})">
                <i class="ri-eye-line"></i>
              </button>
            </div>
          </div>
          
        </div>
      @empty
        <div class="empty-state">
           <i class="ri-check-double-line" style="font-size: 3rem; color: #D1D5DB;"></i>
           <p class="mt-2 text-muted">Belum ada tugas.</p>
        </div>
      @endforelse
    </div>

  </div>

  <nav class="bottom-nav-jobs">
    <a href="{{ route('karyawan.home') }}" class="nav-item-jobs">
      <i class="ri-home-5-line nav-icon-jobs"></i>
      <span class="nav-label-jobs">Beranda</span>
    </a>
    <a href="{{ route('jobs.index') }}" class="nav-item-jobs active">
      <i class="ri-briefcase-4-fill nav-icon-jobs"></i>
      <span class="nav-label-jobs">Jobs</span>
    </a>
    <a href="{{ route('karyawan.profile') }}" class="nav-item-jobs">
      <i class="ri-user-3-line nav-icon-jobs"></i>
      <span class="nav-label-jobs">Profile</span>
    </a>
  </nav>
</div>

{{-- Detail Modal --}}
<div class="modal fade" id="detailTicketModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header bg-light border-0 pb-0">
        <h5 class="modal-title fw-bold text-toko-main">Detail Penugasan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="p-3 bg-white rounded shadow-sm border border-light">
          <div class="mb-3">
            <span class="d-block text-muted" style="font-size:12px;">Customer / Tipe</span>
            <strong id="modalCustomerName" class="text-dark"></strong>
          </div>
          <div class="mb-3">
            <span class="d-block text-muted" style="font-size:12px;">Judul / Masalah</span>
            <span id="modalTicketTitle" class="text-dark d-block"></span>
          </div>
          <div class="mb-3">
            <span class="d-block text-muted" style="font-size:12px;">Deskripsi Kendala</span>
            <p id="modalTicketDesc" class="mb-0 text-dark" style="font-size:14px;"></p>
          </div>
          <div class="row mb-3">
            <div class="col-6">
              <span class="d-block text-muted" style="font-size:12px;">Prioritas</span>
              <span id="modalTicketPriority" class="badge bg-danger mt-1"></span>
            </div>
            <div class="col-6">
              <span class="d-block text-muted" style="font-size:12px;">Status</span>
              <span id="modalTicketStatus" class="badge bg-secondary mt-1"></span>
            </div>
          </div>
          <div class="mb-3">
            <span class="d-block text-muted" style="font-size:12px;">Catatan CS</span>
            <p id="modalTicketNote" class="mb-0 text-dark" style="font-size:14px;"></p>
          </div>
          <div>
            <span class="d-block text-muted" style="font-size:12px;">Waktu Masuk</span>
            <span id="modalTicketDate" class="text-dark" style="font-size:13px;"></span>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light w-100 fw-bold" data-bs-dismiss="modal" style="border-radius:12px;">Tutup Detail</button>
      </div>
    </div>
  </div>
</div>

{{-- Camera Overlay (wajib foto saat klik "Selesai") --}}
<div id="job-camera-overlay" class="job-camera-overlay" style="display:none;">
  <video id="job-cam-video" autoplay playsinline muted></video>
  <canvas id="job-cam-canvas" style="display:none;"></canvas>

  <div class="job-cam-header">
    <span class="job-cam-title">Ambil Foto Progress</span>
    <button type="button" id="job-cam-cancel-btn" class="job-cam-close-btn">
      <i class="ri-close-line"></i>
    </button>
  </div>

  <div class="job-cam-bottom" id="job-cam-capture-wrap">
    <div class="job-cam-helper">Arahkan kamera ke hasil pekerjaan, lalu potret.</div>
    <button type="button" id="job-cam-capture-btn" class="job-cam-shutter-btn"></button>
  </div>

  <div id="job-cam-review" class="job-cam-review" style="display:none;">
    <img id="job-cam-preview" alt="Preview Foto Progress">
    <div class="d-flex gap-2 mt-3">
      <button type="button" id="job-cam-retake-btn" class="btn btn-outline-secondary w-100">Ulangi</button>
      <button type="button" id="job-cam-send-btn" class="btn btn-primary w-100">Kirim Bukti Selesai</button>
    </div>
  </div>
</div>

{{-- Reschedule Modal --}}
<div class="modal fade" id="rescheduleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius: 20px; overflow: hidden;">
      <form id="rescheduleForm" action="" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-header bg-warning border-0 pb-3">
          <h5 class="modal-title fw-bold text-dark"><i class="ri-error-warning-line me-1"></i> Minta Reschedule</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body bg-light">
          <p class="text-muted" style="font-size:14px;">Apakah Anda ingin meminta penjadwalan ulang untuk tiket ini? Silakan sertakan alasan agar CS dapat memprosesnya.</p>
          
          <input type="hidden" name="status" value="pending">
          
          <div class="mb-3">
            <label class="form-label fw-bold text-dark" style="font-size:13px;">Alasan Reschedule <span class="text-danger">*</span></label>
            <textarea name="technician_note" class="form-control border-0 shadow-sm" rows="3" placeholder="Tuliskan kendala teknis atau alasan reschedule..." required style="border-radius:12px;"></textarea>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
          <button type="button" id="rescheduleSubmitBtn" class="btn btn-warning fw-bold text-dark" style="border-radius:10px;">Kirim Permintaan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Scripts --}}
<script>
// ============ TOAST SYSTEM ============
function showJobsToast(message, type = 'success') {
  const existing = document.getElementById('jobs-toast');
  if (existing) existing.remove();

  const icons = {
    success: '<i class="ri-check-line"></i>',
    error: '<i class="ri-close-line"></i>',
    warning: '<i class="ri-time-line"></i>'
  };
  const colors = {
    success: '#10B981',
    error: '#EF4444',
    warning: '#F59E0B'
  };

  const toast = document.createElement('div');
  toast.id = 'jobs-toast';
  toast.innerHTML = `
    <div class="jobs-toast-icon" style="background:${colors[type]}">${icons[type]}</div>
    <p class="jobs-toast-msg">${message}</p>
  `;
  toast.className = 'jobs-toast-pill';
  document.body.appendChild(toast);

  setTimeout(() => toast.classList.add('show'), 50);
  setTimeout(() => {
    toast.classList.remove('show');
    setTimeout(() => toast.remove(), 400);
  }, 3500);
}

// ============ AJAX STATUS UPDATE ============
function doStatusUpdate(url, token, status, note, onSuccess) {
  const body = new URLSearchParams();
  body.append('_token', token);
  body.append('_method', 'PATCH');
  body.append('status', status);
  if (note) body.append('technician_note', note);

  return fetch(url, {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
    body: body
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      showJobsToast(data.message, 'success');
      if (onSuccess) onSuccess(data.new_status);
    } else {
      showJobsToast('Gagal memperbarui status.', 'error');
    }
  })
  .catch(() => showJobsToast('Terjadi kesalahan jaringan.', 'error'));
}

document.addEventListener('DOMContentLoaded', function() {
  const calendarItems = document.querySelectorAll('.calendar-item');
  const tabs = document.querySelectorAll('.tab-item');
  const searchInput = document.getElementById('searchInput');
  const resultCount = document.getElementById('resultCount');

  let currentDateFilter = "{{ $todayStr }}";
  let currentTabFilter = 'progress';
  let searchQuery = '';

  function updateCalendarBadges() {
    document.querySelectorAll('.calendar-item').forEach(item => {
      let badge = item.querySelector('.job-badge');
      if (badge) badge.remove();
    });

    const counts = {};
    document.querySelectorAll('.ticket-card-ui').forEach(card => {
      const status = card.getAttribute('data-status');
      if (status !== 'finished') {
        const date = card.getAttribute('data-date');
        if (date) counts[date] = (counts[date] || 0) + 1;
      }
    });

    for (const [date, count] of Object.entries(counts)) {
      const calItem = document.querySelector(`.calendar-item[data-date="${date}"]`);
      if (calItem && count > 0) {
        const badge = document.createElement('div');
        badge.className = 'job-badge';
        badge.textContent = count;
        calItem.prepend(badge);
      }
    }
  }

  function filterCards() {
    let count = 0;
    const liveCards = document.querySelectorAll('.ticket-card-ui');
    liveCards.forEach(card => {
      const cardDate = card.getAttribute('data-date');
      const cardStatus = card.getAttribute('data-status');
      const cardText = card.innerText.toLowerCase();
      const cardPriority = card.getAttribute('data-priority');
      const matchDate = (cardDate === currentDateFilter);
      let matchTab = false;
      if (currentTabFilter === 'urgent') {
        matchTab = cardPriority === 'urgent';
      } else if (currentTabFilter === 'progress') {
        matchTab = ['pending', 'assigned', 'progress'].includes(cardStatus);
      } else {
        matchTab = cardStatus === currentTabFilter;
      }
      const matchSearch = (cardText.includes(searchQuery));
      if (matchDate && matchTab && matchSearch) {
        card.style.display = 'flex';
        count++;
      } else {
        card.style.display = 'none';
      }
    });
    resultCount.innerText = count;
    updateCalendarBadges();
  }

  filterCards();

  document.addEventListener('poll-update', filterCards);
  document.addEventListener('calendar-changed', function(e) {
    currentDateFilter = e.detail;
    filterCards();
  });

  // Calendar Click
  calendarItems.forEach(item => {
    item.addEventListener('click', () => {
      calendarItems.forEach(i => i.classList.remove('active'));
      item.classList.add('active');
      currentDateFilter = item.getAttribute('data-date');
      filterCards();
    });
  });

  // Tab Click
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      currentTabFilter = tab.getAttribute('data-filter');
      filterCards();
    });
  });

  // ============ AJAX: Tombol Kerjakan / Selesai (Event Delegation) ============
  // State kamera
  let jobCamStream = null;
  let pendingJobUpdate = null;

  const jobCamOverlay = document.getElementById('job-camera-overlay');
  const jobCamVideo   = document.getElementById('job-cam-video');
  const jobCamCanvas  = document.getElementById('job-cam-canvas');
  const jobCamCapture = document.getElementById('job-cam-capture-btn');
  const jobCamCaptureWrap = document.getElementById('job-cam-capture-wrap');
  const jobCamCancel  = document.getElementById('job-cam-cancel-btn');
  const jobCamReview  = document.getElementById('job-cam-review');
  const jobCamPreview = document.getElementById('job-cam-preview');
  const jobCamRetake  = document.getElementById('job-cam-retake-btn');
  const jobCamSend    = document.getElementById('job-cam-send-btn');

  let capturedBlob = null;

  function openJobCamera(pendingData) {
    if (!jobCamOverlay || !jobCamVideo || !jobCamCapture || !jobCamReview || !jobCamPreview) {
      showJobsToast('Komponen kamera belum siap. Hubungi admin.', 'error');
      pendingData.btnEl.disabled = false;
      return;
    }
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
      showJobsToast('Browser tidak mendukung akses kamera.', 'error');
      pendingData.btnEl.disabled = false;
      return;
    }

    pendingJobUpdate = pendingData;
    jobCamOverlay.style.display = 'flex';
    jobCamReview.style.display  = 'none';
    jobCamVideo.style.display   = 'block';
    if (jobCamCaptureWrap) jobCamCaptureWrap.style.display = 'flex';
    capturedBlob = null;

    getBackCameraStream()
    .then(stream => {
      jobCamStream = stream;
      jobCamVideo.srcObject = stream;
    })
    .catch(() => {
      closeJobCamera();
      showJobsToast('Tidak dapat membuka kamera. Izinkan akses kamera terlebih dahulu.', 'error');
    });
  }

  async function getBackCameraStream() {
    const attempts = [
      { video: { facingMode: { exact: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }, audio: false },
      { video: { facingMode: { ideal: 'environment' }, width: { ideal: 1280 }, height: { ideal: 720 } }, audio: false },
      { video: true, audio: false }
    ];
    for (const constraint of attempts) {
      try {
        return await navigator.mediaDevices.getUserMedia(constraint);
      } catch (_) {}
    }
    throw new Error('camera_unavailable');
  }

  function stopJobCamera() {
    if (jobCamStream) {
      jobCamStream.getTracks().forEach(t => t.stop());
      jobCamStream = null;
    }
  }

  function closeJobCamera() {
    stopJobCamera();
    jobCamOverlay.style.display = 'none';
    if (pendingJobUpdate) {
      pendingJobUpdate.btnEl.disabled = false;
      const textEl = pendingJobUpdate.btnEl.querySelector('.btn-text');
      if (textEl) textEl.textContent = 'Selesai';
      pendingJobUpdate = null;
    }
  }

  async function makePortraitBlobFromVideo(videoEl) {
    const srcW = videoEl.videoWidth || 1280;
    const srcH = videoEl.videoHeight || 720;
    const targetW = 1080;
    const targetH = 1440; // 3:4 portrait
    const targetRatio = targetW / targetH;

    const offCanvas = document.createElement('canvas');
    offCanvas.width = targetW;
    offCanvas.height = targetH;
    const ctx = offCanvas.getContext('2d');

    const srcRatio = srcW / srcH;
    let cropW = srcW;
    let cropH = srcH;
    let cropX = 0;
    let cropY = 0;

    if (srcRatio > targetRatio) {
      cropW = Math.round(srcH * targetRatio);
      cropX = Math.round((srcW - cropW) / 2);
    } else if (srcRatio < targetRatio) {
      cropH = Math.round(srcW / targetRatio);
      cropY = Math.round((srcH - cropH) / 2);
    }

    ctx.drawImage(videoEl, cropX, cropY, cropW, cropH, 0, 0, targetW, targetH);

    return await new Promise(resolve => {
      offCanvas.toBlob(resolve, 'image/jpeg', 0.9);
    });
  }

  // Tombol potret
  if (jobCamCapture) jobCamCapture.addEventListener('click', () => {
    makePortraitBlobFromVideo(jobCamVideo).then(blob => {
      if (!blob) {
        showJobsToast('Gagal mengambil foto.', 'error');
        return;
      }
      capturedBlob = blob;
      const url = URL.createObjectURL(blob);
      jobCamPreview.src = url;

      stopJobCamera();
      jobCamVideo.style.display   = 'none';
      if (jobCamCaptureWrap) jobCamCaptureWrap.style.display = 'none';
      jobCamReview.style.display  = 'flex';
    });
  });

  // Potret ulang
  if (jobCamRetake) jobCamRetake.addEventListener('click', () => {
    jobCamReview.style.display  = 'none';
    jobCamVideo.style.display   = 'block';
    if (jobCamCaptureWrap) jobCamCaptureWrap.style.display = 'flex';
    capturedBlob = null;

    getBackCameraStream()
    .then(stream => { jobCamStream = stream; jobCamVideo.srcObject = stream; })
    .catch(() => showJobsToast('Gagal membuka kamera ulang.', 'error'));
  });

  // Kirim foto
  if (jobCamSend) jobCamSend.addEventListener('click', () => {
    if (!capturedBlob || !pendingJobUpdate) return;

    const { url, token, status, btnEl } = pendingJobUpdate;
    const textEl = btnEl.querySelector('.btn-text');

    jobCamSend.disabled = true;
    jobCamSend.textContent = 'Mengunggah...';

    const formData = new FormData();
    formData.append('_token', token);
    formData.append('_method', 'PATCH');
    formData.append('status', status);
    formData.append('technician_attachment', capturedBlob, 'job_photo.jpg');

    fetch(url, {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      jobCamOverlay.style.display = 'none';
      capturedBlob = null;
      pendingJobUpdate = null;
      if (data.success) {
        showJobsToast(data.message || 'Pekerjaan selesai! Foto terkirim.', 'success');
        pollJobStatus();
      } else {
        showJobsToast('Gagal memperbarui status.', 'error');
        btnEl.disabled = false;
        if (textEl) textEl.textContent = 'Selesai';
      }
    })
    .catch(() => {
      jobCamOverlay.style.display = 'none';
      showJobsToast('Terjadi kesalahan jaringan.', 'error');
      btnEl.disabled = false;
      if (textEl) textEl.textContent = 'Selesai';
    })
    .finally(() => {
      jobCamSend.disabled = false;
      jobCamSend.textContent = 'Kirim Bukti Selesai';
    });
  });

  // Batal
  if (jobCamCancel) jobCamCancel.addEventListener('click', closeJobCamera);

  // Event delegation tombol Kerjakan / Selesai
  document.addEventListener('click', function(e) {
    const btnEl = e.target.closest('.btn-update-status');
    if (!btnEl) return;

    const url      = btnEl.dataset.url;
    const token    = btnEl.dataset.token;
    const newStatus = btnEl.dataset.status;

    if (newStatus === 'finished') {
      btnEl.disabled = true;
      const textEl = btnEl.querySelector('.btn-text');
      if (textEl) textEl.textContent = 'Buka Kamera...';
      openJobCamera({ url, token, status: newStatus, btnEl });
      return;
    }

    btnEl.disabled = true;
    const textEl = btnEl.querySelector('.btn-text');
    if (textEl) textEl.textContent = 'Memproses...';

    doStatusUpdate(url, token, newStatus, null, function(status) {
      pollJobStatus();
    });
  });

  // Detail Modal
  window.openDetailModal = function(data) {
    document.getElementById('modalCustomerName').innerText = data.customer;
    if(data.is_internal) {
      document.getElementById('modalCustomerName').innerHTML = '<span class="badge bg-secondary me-1">Internal</span> ' + data.customer;
    }
    document.getElementById('modalTicketTitle').innerText = data.title;
    document.getElementById('modalTicketDesc').innerText = data.description;
    document.getElementById('modalTicketPriority').innerText = data.priority;
    document.getElementById('modalTicketStatus').innerText = data.status;
    document.getElementById('modalTicketNote').innerText = data.note;
    document.getElementById('modalTicketDate').innerText = data.date;
    var detailModal = new bootstrap.Modal(document.getElementById('detailTicketModal'));
    detailModal.show();
  };

  // Reschedule Modal (AJAX)
  let _reschedUrl = '';
  let _reschedToken = '';

  window.openRescheduleModal = function(ticketId, url, token) {
    _reschedUrl = url;
    _reschedToken = token;
    var reschModal = new bootstrap.Modal(document.getElementById('rescheduleModal'));
    reschModal.show();
  };

  document.getElementById('rescheduleSubmitBtn').addEventListener('click', function() {
    const note = document.querySelector('#rescheduleForm textarea[name="technician_note"]').value.trim();
    if (!note) {
      showJobsToast('Tuliskan alasan reschedule terlebih dahulu.', 'warning');
      return;
    }

    this.disabled = true;
    this.textContent = 'Mengirim...';
    const btn = this;

    doStatusUpdate(_reschedUrl, _reschedToken, 'pending', note, function() {
      bootstrap.Modal.getInstance(document.getElementById('rescheduleModal')).hide();
      document.querySelector('#rescheduleForm textarea[name="technician_note"]').value = '';
      btn.disabled = false;
      btn.textContent = 'Kirim Permintaan';
    });
  });

  // Search Input
  searchInput.addEventListener('input', (e) => {
    searchQuery = e.target.value.toLowerCase();
    filterCards();
  });

  // Center active calendar item
  const activeCal = document.querySelector('.calendar-item.active');
  if(activeCal) {
    activeCal.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
  }
});

// ============ REAL-TIME POLLING (every 8 seconds) ============
(function() {
  // Auto-detect base URL from current page
  var baseUrl = window.location.protocol + '//' + window.location.host;
  var pollUrl = baseUrl + '/karyawan/jobs/poll-status';

  console.log('[Jobs Poll] URL:', pollUrl);

  var knownTs = {};
  var allCards = document.querySelectorAll('.ticket-card-ui[data-id]');
  allCards.forEach(function(c) { knownTs[c.dataset.id] = 0; });

  var statusMap = {
    pending:  { text: 'Pending',    cls: 'bg-warning text-dark' },
    assigned: { text: 'Ditugaskan', cls: 'bg-info text-dark'    },
    progress: { text: 'Dikerjakan', cls: 'bg-primary'            },
    finished: { text: 'Selesai',    cls: 'bg-success'            },
    approved: { text: 'Approved',   cls: 'bg-success'            }
  };

  var colorMap = {
    pending: 'bg-orange', assigned: 'bg-blue', progress: 'bg-purple', finished: 'bg-blue'
  };

  function updateCard(card, t) {
    card.setAttribute('data-status', t.status);

    // Badge
    var badge = card.querySelector('.badge');
    if (badge && statusMap[t.status]) {
      badge.textContent = statusMap[t.status].text;
      badge.className = 'badge ' + statusMap[t.status].cls;
    }

    // Left color
    var lb = card.querySelector('.left-date-block');
    if (lb) {
      lb.classList.remove('bg-blue','bg-orange','bg-purple','bg-red');
      if (t.status === 'pending' && t.technician_note) {
        lb.classList.add('bg-red');
      } else {
        lb.classList.add(colorMap[t.status] || 'bg-blue');
      }
    }

    // Buttons
    var area = card.querySelector('.action-buttons');
    if (area) {
      var btn = area.querySelector('.btn-update-status');
      var done = area.querySelector('.done-label');
      if (t.status === 'finished') {
        if (btn) btn.remove();
        if (!done) {
          var d = document.createElement('div');
          d.className = 'w-100 flex-grow-1 text-center text-success fw-bold done-label';
          d.style.fontSize = '13px';
          d.innerHTML = '<i class="ri-checkbox-circle-fill me-1"></i>Tiket Selesai';
          area.prepend(d);
        }
      } else if (t.status === 'progress' && btn) {
        btn.dataset.status = 'finished';
        var tx = btn.querySelector('.btn-text');
        if (tx) tx.textContent = 'Selesai';
        btn.disabled = false;
      } else if ((t.status === 'pending' || t.status === 'assigned') && btn) {
        btn.dataset.status = 'progress';
        var tx2 = btn.querySelector('.btn-text');
        if (tx2) tx2.textContent = 'Kerjakan';
        btn.disabled = false;
      }
    }

    // Reschedule alert
    var ra = card.querySelector('.reschedule-alert-realtime');
    if (t.status === 'pending' && t.technician_note) {
      if (!ra) {
        var al = document.createElement('div');
        al.className = 'reschedule-alert-realtime mt-2 p-2 rounded';
        al.style.cssText = 'background-color:#FEE2E2;border-left:3px solid #EF4444;';
        al.innerHTML = '<span class="d-block text-danger fw-bold" style="font-size:11px;"><i class="ri-error-warning-fill"></i> Reschedule Note:</span><span class="text-danger note-content" style="font-size:12px;">' + t.technician_note + '</span>';
        var rb = card.querySelector('.right-content-block');
        // Append before the action-buttons div for neat layout
        var ab = rb.querySelector('.action-buttons');
        if (ab) rb.insertBefore(al, ab);
        else rb.appendChild(al);
      } else {
        var nc = ra.querySelector('.note-content');
        if (nc) nc.textContent = t.technician_note;
      }
    } else {
      if (ra) ra.remove();
    }
  }

  function doPoll() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', pollUrl, true);
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.withCredentials = true;
    xhr.timeout = 10000;

    xhr.onload = function() {
      if (xhr.status === 200) {
        try {
          var data = JSON.parse(xhr.responseText);
          var changed = false;
          data.forEach(function(t) {
            var card = document.querySelector('.ticket-card-ui[data-id="' + t.id + '"]');
            if (!card) return;
            var last = knownTs[t.id] || 0;
            if (t.updated_at > last) {
              knownTs[t.id] = t.updated_at;
              updateCard(card, t);
              
              // Handle date change for real-time calendar jump
              if (t.date) {
                  var oldDate = card.getAttribute('data-date');
                  if (oldDate && oldDate !== t.date) {
                      card.setAttribute('data-date', t.date);
                      var cItem = document.querySelector('.calendar-item[data-date="' + t.date + '"]');
                      if (cItem) {
                          cItem.click(); // Auto-switch to the admin's new scheduled date
                      }
                  }
              }
              
              changed = true;
            }
          });
          if (changed) {
            try { document.dispatchEvent(new Event('poll-update')); } catch(e) {}
          }
          console.log('[Jobs Poll] OK - ' + data.length + ' tickets, changed: ' + changed);
        } catch(e) {
          console.log('[Jobs Poll] JSON parse error:', e.message);
          console.log('[Jobs Poll] Response preview:', xhr.responseText.substring(0, 200));
        }
      } else {
        console.log('[Jobs Poll] HTTP ' + xhr.status);
      }
    };
    xhr.onerror = function() { console.log('[Jobs Poll] Network error'); };
    xhr.ontimeout = function() { console.log('[Jobs Poll] Timeout'); };
    xhr.send();
  }

  // Expose globally for AJAX button callback
  window.pollJobStatus = doPoll;

  // Start
  setInterval(doPoll, 8000);
  setTimeout(doPoll, 1500);
})();
</script>

{{-- Styles --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

body {
  background-color: #E9EDF7 !important;
}

.mobile-wrapper {
  max-width: 480px;
  background-color: #F4F7FF;
  min-height: 100vh;
  padding-bottom: 90px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  box-shadow: 0 0 40px rgba(0,0,0,0.05);
  position: relative;
  overflow-x: hidden;
  overflow-y: visible;
}

.job-camera-overlay {
  position: fixed;
  inset: 0;
  background: #000;
  z-index: 3000;
  display: flex;
  flex-direction: column;
  height: 100svh;
  width: 100vw;
}
#job-cam-video {
  width: 100%;
  flex: 1;
  object-fit: cover;
  background: #000;
}
.job-cam-header {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px;
  padding-top: calc(18px + env(safe-area-inset-top, 0px));
  background: linear-gradient(180deg, rgba(0,0,0,0.54) 0%, rgba(0,0,0,0) 100%);
  z-index: 10;
}
.job-cam-title {
  font-weight: 700;
  color: #fff;
  font-size: 1.2rem;
  text-shadow: 0 1px 4px rgba(0,0,0,0.4);
}
.job-cam-close-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: none;
  background: rgba(255,255,255,0.2);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
}
.job-cam-bottom {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 10;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  padding: 24px;
  padding-bottom: calc(24px + env(safe-area-inset-bottom, 0px) + 70px);
  background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, transparent 100%);
}
.job-cam-helper {
  color: rgba(255,255,255,0.92);
  font-size: 1rem;
  font-weight: 500;
  text-align: center;
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
}
.job-cam-shutter-btn {
  width: 76px;
  height: 76px;
  border-radius: 50%;
  background: #fff;
  border: 5px solid rgba(255,255,255,0.4);
  box-shadow: 0 0 0 5px rgba(255,255,255,0.2), 0 8px 28px rgba(0,0,0,0.5);
  cursor: pointer;
  transition: transform 0.15s;
  flex-shrink: 0;
}
.job-cam-shutter-btn::before {
  content: '';
  position: absolute;
  inset: 5px;
  border-radius: 50%;
  background: #fff;
  transition: all 0.15s;
}
.job-cam-shutter-btn:active { transform: scale(0.9); }
.job-cam-shutter-btn:active::before { inset: 10px; }
.job-cam-review {
  position: fixed;
  inset: 0;
  z-index: 20;
  background: #fff;
  padding: 16px;
  display: flex;
  flex-direction: column;
  justify-content: center;
}
#job-cam-preview { width: 100%; max-height: 76vh; object-fit: contain; border-radius: 12px; border: 1px solid #dbe3f0; }

/* Header Section */
.mobile-header {
  background: linear-gradient(150deg, #0D6EFD 0%, #1F7CFF 100%);
  padding: calc(38px + env(safe-area-inset-top, 0px)) 20px 28px 20px;
  border-bottom-left-radius: 30px;
  border-bottom-right-radius: 30px;
  overflow: visible;
}

.greeting-text {
  color: rgba(255, 255, 255, 0.85);
  font-size: 14px;
  font-weight: 500;
}

.btn-back-jobs {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #fff;
  text-decoration: none;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 14px;
  background: rgba(255, 255, 255, 0.18);
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 999px;
  padding: 6px 12px;
}

/* Calendar Strip */
.month-label {
  font-weight: 700;
  color: #FFFFFF;
  font-size: 16px;
  margin-top: 10px;
}

.calendar-strip {
  display: flex;
  gap: 12px;
  overflow-x: auto;
  padding: 8px 8px 14px;
  margin: 0 -8px;
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
.calendar-strip::-webkit-scrollbar {
  display: none;
}

.calendar-item {
  min-width: 55px;
  height: 122px;
  background-color: #FFFFFF;
  border-radius: 999px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  position: relative;
}

.job-badge {
  position: absolute;
  top: -5px;
  right: -5px;
  background-color: #EF4444; /* Red color */
  color: #FFFFFF;
  font-size: 11px;
  font-weight: 800;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 3px solid #0D6EFD; /* Matches header blue */
  box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
  z-index: 2;
}

.calendar-item.active .job-badge {
  border-color: #0B5ED7;
}

.calendar-item .cal-day {
  font-size: 12px;
  color: #8C8C8C;
  font-weight: 600;
  text-transform: uppercase;
  margin-bottom: 10px;
}

.calendar-item .cal-date {
  font-size: 16px;
  font-weight: 800;
  color: #2D3142;
}

.calendar-item.active {
  background-color: #0B5ED7;
  transform: translateY(-2px);
  box-shadow: 0 8px 15px rgba(13, 110, 253, 0.35);
}

.calendar-item.active .cal-day,
.calendar-item.active .cal-date {
  color: #FFFFFF;
}

/* Body Section */
.mobile-body {
  padding: 20px;
}

/* Search Input */
.search-wrapper {
  position: relative;
  width: 100%;
}

.search-icon {
  position: absolute;
  left: 15px;
  top: 50%;
  transform: translateY(-50%);
  color: #A0AEC0;
  font-size: 18px;
}

.search-input {
  width: 100%;
  padding: 14px 15px 14px 45px;
  background-color: #FFFFFF;
  border: none;
  border-radius: 16px;
  font-size: 14px;
  color: #2D3142;
  box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  outline: none;
}
.search-input::placeholder { color: #A0AEC0; }

/* Tabs */
.tabs-wrapper {
  display: flex;
  gap: 20px;
  border-bottom: 2px solid #E2E8F0;
  padding-bottom: 8px;
}

.tab-item {
  font-size: 14px;
  font-weight: 600;
  color: #A0AEC0;
  cursor: pointer;
  position: relative;
  transition: color 0.3s ease;
}

.tab-item.active {
  color: #2D3142;
}

.tab-item.active::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 100%;
  height: 3px;
  background-color: #0D6EFD;
  border-radius: 3px;
}

.bottom-nav-jobs {
  position: fixed;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  max-width: 480px;
  background: #fff;
  border-top: 1px solid #E5E7EB;
  display: flex;
  justify-content: space-around;
  padding: 10px 16px;
  z-index: 20;
}

.nav-item-jobs {
  text-decoration: none;
  color: #9CA3AF;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
}

.nav-item-jobs.active {
  color: #0D6EFD;
}

.nav-icon-jobs {
  font-size: 22px;
}

.nav-label-jobs {
  font-size: 11px;
  font-weight: 700;
}

/* Ticket Cards */
.ticket-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.ticket-card-ui {
  background-color: #FFFFFF;
  border-radius: 20px;
  padding: 8px;
  display: flex; /* hidden initially until filtered */
  gap: 12px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.03);
  transition: transform 0.2s ease;
}

.ticket-card-ui:hover {
  transform: translateY(-2px);
}

.left-date-block {
  width: 75px;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #FFFFFF;
  padding: 15px 5px;
}

.bg-blue { background: linear-gradient(135deg, #4F46E5, #3B82F6); }
.bg-purple { background: linear-gradient(135deg, #9333EA, #A855F7); }
.bg-orange { background: linear-gradient(135deg, #EA580C, #F97316); }
.bg-red { background: linear-gradient(135deg, #E11D48, #F43F5E); }

.date-num {
  font-size: 22px;
  font-weight: 800;
  line-height: 1;
}

.date-month {
  font-size: 12px;
  font-weight: 500;
  text-transform: uppercase;
  margin-top: 2px;
}

.time-badge {
  background-color: rgba(255,255,255,0.2);
  padding: 2px 8px;
  border-radius: 10px;
  font-size: 10px;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 3px;
}

.right-content-block {
  flex: 1;
  padding: 8px 8px 8px 0;
  min-width: 0; /* for truncation */
}

.ticket-title {
  font-size: 15px;
  font-weight: 700;
  color: #2D3142;
}

.customer-info {
  font-size: 13px;
  display: flex;
  align-items: center;
  gap: 6px;
}

.ticket-meta {
  display: flex;
  gap: 12px;
}

.meta-item {
  font-size: 12px;
  color: #718096;
  display: flex;
  align-items: center;
  gap: 4px;
  font-weight: 500;
}

.btn-action-primary {
  background-color: #2D3142;
  color: #FFFFFF;
  border: none;
  padding: 8px 0;
  border-radius: 10px;
  font-size: 13px;
  font-weight: 600;
  transition: background 0.2s;
}

.btn-action-primary:hover {
  background-color: #1A202C;
}

.btn-action-icon {
  background-color: #F1F5F9;
  color: #2D3142;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  text-decoration: none;
  font-size: 16px;
  transition: background 0.2s;
}
.btn-action-icon:hover {
  background-color: #E2E8F0;
}

.empty-state {
  text-align: center;
  padding: 40px 0;
}

/* ====== Jobs Toast Pill ====== */
.jobs-toast-pill {
  position: fixed;
  top: -100px;
  left: 50%;
  transform: translateX(-50%);
  background: #fff;
  padding: 10px 18px 10px 10px;
  border-radius: 100px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.12);
  display: flex;
  align-items: center;
  gap: 12px;
  z-index: 99999;
  width: 90%;
  max-width: 360px;
  transition: top 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  pointer-events: none;
}
.jobs-toast-pill.show {
  top: 20px;
}
.jobs-toast-icon {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 15px;
  flex-shrink: 0;
}
.jobs-toast-msg {
  font-size: 13px;
  font-weight: 600;
  color: #111827;
  margin: 0;
  line-height: 1.4;
}

/* ====== Jobs Camera Overlay ====== */
.job-camera-overlay {
  position: fixed;
  inset: 0;
  background: #000;
  z-index: 10000;
  flex-direction: column;
  height: 100svh;
  width: 100vw;
}

#job-cam-video {
  width: 100%;
  flex: 1;
  object-fit: cover;
  display: block;
  background: #000;
}

.job-cam-header {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 18px;
  padding-top: calc(20px + env(safe-area-inset-top, 0px));
  background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 100%);
  z-index: 10;
}

.job-cam-title {
  font-weight: 700;
  color: #fff;
  font-size: 1.25rem;
  text-shadow: 0 1px 4px rgba(0,0,0,0.4);
}

.job-cam-close-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: none;
  background: rgba(255,255,255,0.18);
  backdrop-filter: blur(8px);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  transition: background 0.2s;
}

.job-cam-bottom {
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
  background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, transparent 100%);
  z-index: 10;
}

.job-cam-helper {
  color: rgba(255,255,255,0.92);
  font-size: 1rem;
  font-weight: 500;
  text-align: center;
  text-shadow: 0 1px 3px rgba(0,0,0,0.5);
  margin-bottom: 10px;
}

.job-cam-shutter-btn {
  width: 76px;
  height: 76px;
  border-radius: 50%;
  background: #fff;
  border: 5px solid rgba(255,255,255,0.4);
  box-shadow: 0 0 0 5px rgba(255,255,255,0.2), 0 8px 28px rgba(0,0,0,0.5);
  cursor: pointer;
  position: relative;
  transition: transform 0.15s;
  flex-shrink: 0;
}

.job-cam-shutter-btn::before {
  content: '';
  position: absolute;
  inset: 5px;
  border-radius: 50%;
  background: #fff;
  transition: all 0.15s;
}

.job-cam-shutter-btn:active { transform: scale(0.9); }
.job-cam-shutter-btn:active::before { inset: 10px; }

.job-cam-review {
  position: fixed;
  inset: 0;
  background: #fff;
  z-index: 10001;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

#job-cam-preview {
  width: 100%;
  max-width: 400px;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}
</style>
@endsection
