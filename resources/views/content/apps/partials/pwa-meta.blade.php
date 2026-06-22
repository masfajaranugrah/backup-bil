@php
  $isKaryawanArea = request()->is('karyawan*')
    || request()->is('dashboard/karyawan*')
    || request()->routeIs('karyawan.*')
    || request()->routeIs('jobs.*')
    || request()->routeIs('absensi.*');

  $manifestFile = $isKaryawanArea ? 'manifest-lakar.json' : 'manifest.json';
  $themeColor = $isKaryawanArea ? '#0D6EFD' : '#f8fafc';
@endphp

<meta name="theme-color" content="{{ $themeColor }}">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ $isKaryawanArea ? 'Lakar' : 'JMK' }}">
<link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
<link rel="manifest" href="{{ asset($manifestFile) }}?v={{ filemtime(public_path($manifestFile)) }}">

<link href="{{ asset('images/icons/pwa-splash-640x1136.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-640x1136.png')) }}" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-750x1334.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-750x1334.png')) }}" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1242x2208.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1242x2208.png')) }}" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1125x2436.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1125x2436.png')) }}" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1170x2532.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1170x2532.png')) }}" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1179x2556.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1179x2556.png')) }}" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-828x1792.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-828x1792.png')) }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1242x2688.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1242x2688.png')) }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1284x2778.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1284x2778.png')) }}" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1290x2796.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1290x2796.png')) }}" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1536x2048.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1536x2048.png')) }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1668x2224.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1668x2224.png')) }}" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-1668x2388.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-1668x2388.png')) }}" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
<link href="{{ asset('images/icons/pwa-splash-2048x2732.png') }}?v={{ filemtime(public_path('images/icons/pwa-splash-2048x2732.png')) }}" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image">
