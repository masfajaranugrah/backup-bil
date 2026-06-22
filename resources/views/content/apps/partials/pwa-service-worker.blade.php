@php
  $isKaryawanArea = request()->is('karyawan*')
    || request()->is('dashboard/karyawan*')
    || request()->routeIs('karyawan.*')
    || request()->routeIs('jobs.*')
    || request()->routeIs('absensi.*');
@endphp

<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      var swUrl = @json($isKaryawanArea ? '/service-worker-lakar.js' : '/service-worker.js');
      var swScope = @json($isKaryawanArea ? '/karyawan/' : '/');

      navigator.serviceWorker.register(swUrl, { scope: swScope })
        .then(function (registration) {
          registration.update().catch(function (error) {
            console.warn('SW update skipped:', error);
          });
        })
        .catch(function (error) {
          console.log('SW registration failed:', error);
        });
    });
  }
</script>
