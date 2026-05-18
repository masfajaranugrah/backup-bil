@include('content.apps.Ticket.ticket-finished', [
  'pageTitle' => $pageTitle ?? 'Daftar Ticket Selesai',
  'pageDescription' => $pageDescription ?? 'Kelola dan monitor tiket yang sudah dikonfirmasi selesai.',
  'searchRoute' => $searchRoute ?? route('approved'),
])
