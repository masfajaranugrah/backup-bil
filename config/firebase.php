<?php

return [
    'credentials' => env(
        'FIREBASE_CREDENTIALS',
        storage_path('firebase/layanan-bill-firebase-adminsdk-fbsvc-e740aec941.json')
    ), 

    'web' => [
        'api_key' => env('FIREBASE_API_KEY'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN', 'layanan-bill.firebaseapp.com'),
        'project_id' => env('FIREBASE_PROJECT_ID', 'layanan-bill'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'layanan-bill.firebasestorage.app'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', '630006180845'),
        'app_id' => env('FIREBASE_APP_ID', '1:630006180845:web:df02ed469cfbdca42b55e4'),
        'measurement_id' => env('FIREBASE_MEASUREMENT_ID', 'G-N5K3CWD8XH'),
        'vapid_key' => env(
            'FIREBASE_VAPID_KEY',
            'BAUuJ9X2r5edrqnWPjQntlXqUYPISgy0DxBIS_W5kcB699yc_PB8--D33ctagwCeKo3VYeMUGaI-vRftmRP1wGE'
        ),
    ],
];
