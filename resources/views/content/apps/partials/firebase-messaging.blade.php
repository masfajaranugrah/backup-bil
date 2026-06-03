@php
    $firebaseWebConfig = config('firebase.web');
    $firebaseCustomer = $user ?? auth('customer')->user();
@endphp

@if($firebaseCustomer)
    <script>
        window.firebaseMessagingConfig = {
            apiKey: @json($firebaseWebConfig['api_key']),
            authDomain: @json($firebaseWebConfig['auth_domain']),
            projectId: @json($firebaseWebConfig['project_id']),
            storageBucket: @json($firebaseWebConfig['storage_bucket']),
            messagingSenderId: @json($firebaseWebConfig['messaging_sender_id']),
            appId: @json($firebaseWebConfig['app_id']),
            measurementId: @json($firebaseWebConfig['measurement_id']),
            vapidKey: @json($firebaseWebConfig['vapid_key']),
            workerVersion: @json(filemtime(public_path('firebase-cloud-messaging-push-scope/firebase-messaging-sw.js'))),
            tokenEndpoint: @json('/pelanggan/' . $firebaseCustomer->nomer_id . '/update-fcm-token'),
            deleteTokenEndpoint: @json('/pelanggan/' . $firebaseCustomer->nomer_id . '/delete-fcm-token')
        };
    </script>
    <script defer src="/vendor/firebase/firebase-app-compat-10.12.5.js"></script>
    <script defer src="/vendor/firebase/firebase-messaging-compat-10.12.5.js"></script>
    <script defer src="/firebase-messaging.js?v={{ filemtime(public_path('firebase-messaging.js')) }}"></script>
@endif
