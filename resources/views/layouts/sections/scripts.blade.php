<!-- BEGIN: Vendor JS-->

@vite([
  'resources/assets/vendor/libs/jquery/jquery.js',
  'resources/assets/vendor/libs/popper/popper.js',
  'resources/assets/vendor/js/bootstrap.js',
  'resources/assets/vendor/libs/node-waves/node-waves.js',
  'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js',
  'resources/assets/vendor/libs/hammer/hammer.js',
  'resources/assets/vendor/libs/typeahead-js/typeahead.js',
  'resources/assets/vendor/js/menu.js'
])

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
@vite(['resources/assets/js/main.js'])

<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

 
<!-- PushAlert -->
<!-- PushAlert Onsite Messaging -->
<script type="text/javascript">
    (function(d, t) {
        var g = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
        g.src = "https://cdn.inwebr.com/inwebr_19792c764e233b96de78dca4477c58ce.js";
        s.parentNode.insertBefore(g, s);
    }(document, "script"));
</script>
<!-- End PushAlert Onsite Messaging -->

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
          var lastUpdate = Number(localStorage.getItem('sw_last_update') || 0);
          var now = Date.now();
          if (now - lastUpdate > 21600000) {
            localStorage.setItem('sw_last_update', String(now));
            registration.update().catch(function (error) {
              console.warn('SW update skipped:', error);
            });
          }
        })
        .catch(function (error) {
          console.log('SW registration failed:', error);
        });
    });
  }
</script>
