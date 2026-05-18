@extends('layouts/layoutMaster')

@section('title', 'Daftar Ticket Teknisi')

@section('content')
@php
    $user = auth()->user();
    
    // Generate dates for calendar strip (e.g., 3 days ago to 7 days ahead)
    $dates = [];
    for ($i = -3; $i <= 7; $i++) {
        $dates[] = \Carbon\Carbon::now()->addDays($i);
    }
    
    // Today's date string for default active state
    $todayStr = \Carbon\Carbon::now()->format('Y-m-d');
@endphp

<div class="mobile-wrapper mx-auto">
  {{-- Header Section (Beige background) --}}
  <div class="mobile-header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <p class="greeting-text mb-1">Hello {{ explode(' ', $user->name)[0] }} ??</p>
        <h4 class="fw-bold m-0" style="color: #2D3142;">Ready For Today's Tasks?</h4>
      </div>
      <div class="notification-icon">
        <i class="ri-notification-3-line"></i>
        <span class="notification-dot"></span>
      </div>
    </div>

    {{-- Calendar Strip --}}
    <div class="month-label mb-2">{{ \Carbon\Carbon::now()->translatedFormat('F, Y') }}</div>
    <div class="calendar-strip" id="calendarStrip">
      @foreach($dates as $date)
        @php
            $dateStr = $date->format('Y-m-d');
            $isToday = $dateStr === $todayStr;
        @endphp
        <div class="calendar-item {{ $isToday ? 'active' : '' }}" data-date="{{ $dateStr }}">
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
      <div class="tab-item active" data-filter="all">Semua Penugasan</div>
      <div class="tab-item" data-filter="pending">Pending</div>
      <div class="tab-item" data-filter="progress">Progress</div>
    </div>

    <div class="ticket-count mb-3">
      <span id="resultCount" class="fw-bold" style="color: #F48C06;">{{ $tickets->count() }}</span> tickets found
    </div>

    {{-- Ticket List --}}
    <div id="ticketContainer" class="ticket-list">
      @forelse($tickets as $ticket)
        @php
            $ticketDate = $ticket->created_at->format('Y-m-d');
            $ticketDay = $ticket->created_at->format('d');
            $ticketMonthShort = $ticket->created_at->translatedFormat('M');
            
            // Tentukan warna blok kiri berdasarkan status
            $leftColorClass = 'bg-blue';
            if($ticket->status == 'pending') $leftColorClass = 'bg-orange';
            if($ticket->status == 'progress') $leftColorClass = 'bg-purple';
            if($ticket->priority == 'urgent') $leftColorClass = 'bg-red';
        @endphp
        
        <div class="ticket-card-ui" data-date="{{ $ticketDate }}" data-status="{{ $ticket->status }}">
          
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

            <div class="action-buttons mt-3 d-flex gap-2">
              @if(in_array($ticket->status, ['pending', 'assigned']))
                <form action="{{ route('jobs.autoUpdateStatus', $ticket->id) }}" method="POST" class="flex-grow-1">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="status" value="progress">
                  <button type="submit" class="btn-action-primary w-100">Kerjakan</button>
                </form>
              @elseif($ticket->status === 'progress')
                <form action="{{ route('jobs.autoUpdateStatus', $ticket->id) }}" method="POST" class="flex-grow-1">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="status" value="finished">
                  <button type="submit" class="btn-action-primary w-100">Selesai</button>
                </form>
              @endif
              <a href="{{ route('jobs.edit', $ticket->id) }}" class="btn-action-icon"><i class="ri-eye-line"></i></a>
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
</div>

{{-- Scripts --}}
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarItems = document.querySelectorAll('.calendar-item');
  const tabs = document.querySelectorAll('.tab-item');
  const searchInput = document.getElementById('searchInput');
  const cards = document.querySelectorAll('.ticket-card-ui');
  const resultCount = document.getElementById('resultCount');

  let currentDateFilter = "{{ $todayStr }}";
  let currentTabFilter = 'all';
  let searchQuery = '';

  function filterCards() {
    let count = 0;
    
    cards.forEach(card => {
      const cardDate = card.getAttribute('data-date');
      const cardStatus = card.getAttribute('data-status');
      const cardText = card.innerText.toLowerCase();

      // For calendar logic: if user clicks a date, show ONLY that date's tickets.
      // If you prefer showing ALL tickets from today onwards, adjust here.
      // Currently strictly matching the selected date:
      const matchDate = (cardDate === currentDateFilter);
      
      const matchTab = (currentTabFilter === 'all' || cardStatus === currentTabFilter);
      const matchSearch = (cardText.includes(searchQuery));

      if (matchDate && matchTab && matchSearch) {
        card.style.display = 'flex';
        count++;
      } else {
        card.style.display = 'none';
      }
    });

    resultCount.innerText = count;
  }

  // Initial Filter
  filterCards();

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
</script>

{{-- Styles --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

body {
  background-color: #F8F9FD !important;
}

.mobile-wrapper {
  max-width: 480px;
  background-color: #F8F9FD;
  min-height: 100vh;
  font-family: 'Plus Jakarta Sans', sans-serif;
  box-shadow: 0 0 40px rgba(0,0,0,0.05);
  position: relative;
  overflow: hidden;
}

/* Header Section */
.mobile-header {
  background-color: #FDF5E6; /* Soft beige */
  padding: 30px 20px 25px 20px;
  border-bottom-left-radius: 30px;
  border-bottom-right-radius: 30px;
}

.greeting-text {
  color: #8C8C8C;
  font-size: 14px;
  font-weight: 500;
}

.notification-icon {
  width: 40px;
  height: 40px;
  background-color: #FFFFFF;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  color: #2D3142;
  position: relative;
  box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}

.notification-dot {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 8px;
  height: 8px;
  background-color: #F48C06;
  border-radius: 50%;
  border: 2px solid #FFFFFF;
}

/* Calendar Strip */
.month-label {
  font-weight: 700;
  color: #2D3142;
  font-size: 16px;
  margin-top: 10px;
}

.calendar-strip {
  display: flex;
  gap: 12px;
  overflow-x: auto;
  padding-bottom: 10px;
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
.calendar-strip::-webkit-scrollbar {
  display: none;
}

.calendar-item {
  min-width: 55px;
  height: 75px;
  background-color: #FFFFFF;
  border-radius: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.calendar-item .cal-day {
  font-size: 12px;
  color: #8C8C8C;
  font-weight: 600;
  text-transform: uppercase;
  margin-bottom: 4px;
}

.calendar-item .cal-date {
  font-size: 16px;
  font-weight: 800;
  color: #2D3142;
}

.calendar-item.active {
  background-color: #F48C06; /* Vibrant Orange */
  transform: translateY(-2px);
  box-shadow: 0 8px 15px rgba(244, 140, 6, 0.3);
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
  background-color: #F48C06;
  border-radius: 3px;
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
</style>
@endsection
