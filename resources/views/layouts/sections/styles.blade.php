<!-- BEGIN: Theme CSS-->
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<!-- PWA -->
@php
  $isKaryawanArea = request()->is('karyawan*')
    || request()->is('dashboard/karyawan*')
    || request()->routeIs('karyawan.*')
    || request()->routeIs('jobs.*')
    || request()->routeIs('absensi.*');
@endphp
<meta name="theme-color" content="{{ $isKaryawanArea ? '#0D6EFD' : '#000' }}">
<link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
@if($isKaryawanArea)
  <link rel="manifest" href="{{ asset('manifest-lakar.json') }}?v={{ filemtime(public_path('manifest-lakar.json')) }}">
@else
  <link rel="manifest" href="{{ asset('manifest.json') }}?v={{ filemtime(public_path('manifest.json')) }}">
@endif

 
 

@vite([
  'resources/assets/vendor/fonts/remixicon/remixicon.scss',
  'resources/assets/vendor/fonts/flag-icons.scss',
  'resources/assets/vendor/libs/node-waves/node-waves.scss',
  'resources/css/app.css',
])
<!-- Core CSS -->
@vite(['resources/assets/vendor/scss'.$configData['rtlSupport'].'/core' .($configData['style'] !== 'light' ? '-' . $configData['style'] : '') .'.scss',
'resources/assets/vendor/scss'.$configData['rtlSupport'].'/' .$configData['theme'] .($configData['style'] !== 'light' ? '-' . $configData['style'] : '') .'.scss',
'resources/assets/css/demo.css'])


<!-- Vendor Styles -->
@vite([
  'resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss',
  'resources/assets/vendor/libs/typeahead-js/typeahead.scss'
])
@yield('vendor-style')

<!-- Page Styles -->
@yield('page-style')
