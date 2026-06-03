const config = window.firebaseMessagingConfig;
let messagingInstance;
const FIREBASE_SW_SCOPE = '/firebase-cloud-messaging-push-scope/';
const FIREBASE_SW_PATH = `${FIREBASE_SW_SCOPE}firebase-messaging-sw.js`;
const FIREBASE_DISABLED_KEY = 'firebase_messaging_disabled';
const FIREBASE_LAST_TOKEN_KEY = 'last_firebase_fcm_token';
const FIREBASE_CONFIG_SIGNATURE_KEY = 'firebase_messaging_config_signature';
const FIREBASE_BROWSER_STATE_RESET_KEY = 'firebase_messaging_browser_state_reset_signature';

function isIosSafariLike() {
  return /iPad|iPhone|iPod/.test(navigator.userAgent)
    || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
}

function isStandalonePwa() {
  return window.matchMedia?.('(display-mode: standalone)').matches
    || window.navigator.standalone === true;
}

function notificationSupportDetails() {
  const details = {
    secureContext: window.isSecureContext,
    serviceWorker: 'serviceWorker' in navigator,
    notification: 'Notification' in window,
    pushManager: 'PushManager' in window,
    indexedDb: 'indexedDB' in window,
    iosSafariLike: isIosSafariLike(),
    standalonePwa: isStandalonePwa(),
    userAgent: navigator.userAgent
  };

  if (!details.secureContext) {
    return {
      supported: false,
      reason: 'insecure-context',
      message: 'Halaman harus dibuka lewat HTTPS agar notifikasi aktif.'
    };
  }

  if (!details.serviceWorker) {
    return {
      supported: false,
      reason: 'missing-service-worker',
      message: 'Browser ini belum mendukung service worker untuk notifikasi.'
    };
  }

  if (!details.notification) {
    return {
      supported: false,
      reason: 'missing-notification-api',
      message: 'Browser ini belum menyediakan izin notifikasi untuk halaman ini.'
    };
  }

  if (!details.pushManager) {
    return {
      supported: false,
      reason: 'missing-push-manager',
      message: details.iosSafariLike && !details.standalonePwa
        ? 'Di Safari iPhone/iPad, notifikasi hanya bisa aktif setelah website ditambahkan ke Layar Utama lalu dibuka dari ikon aplikasi.'
        : 'Browser ini belum mendukung Push API untuk notifikasi.'
    };
  }

  if (!details.indexedDb) {
    return {
      supported: false,
      reason: 'missing-indexeddb',
      message: 'Browser ini belum menyediakan IndexedDB yang dibutuhkan Firebase Messaging.'
    };
  }

  return {
    supported: true,
    reason: 'supported',
    message: 'Browser mendukung notifikasi.',
    details
  };
}

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function getDeviceId() {
  const key = 'firebase_device_notification_id';
  let deviceId = localStorage.getItem(key);

  if (!deviceId) {
    deviceId = `device_${Date.now()}_${Math.random().toString(36).slice(2, 11)}`;
    localStorage.setItem(key, deviceId);
  }

  return deviceId;
}

function firebaseConfigSignature() {
  return [
    config?.projectId || '',
    config?.messagingSenderId || '',
    config?.appId || '',
    config?.vapidKey || ''
  ].join('|');
}

function firebaseAppName() {
  return `customer-messaging-${String(config?.appId || 'default').replace(/[^a-zA-Z0-9-]/g, '-')}`;
}

function deleteIndexedDbDatabase(name) {
  return new Promise((resolve) => {
    if (!('indexedDB' in window)) {
      resolve();
      return;
    }

    const request = indexedDB.deleteDatabase(name);
    request.onsuccess = () => resolve();
    request.onerror = () => resolve();
    request.onblocked = () => resolve();
  });
}

async function resetFirebaseBrowserStateForCurrentConfig(signature) {
  if (localStorage.getItem(FIREBASE_BROWSER_STATE_RESET_KEY) === signature) {
    return;
  }

  console.info('[Firebase Messaging] Resetting stale browser Firebase state...');

  const registrations = await navigator.serviceWorker.getRegistrations();

  await Promise.all(registrations.map(async (registration) => {
    const scopePath = new URL(registration.scope).pathname;

    if (scopePath !== FIREBASE_SW_SCOPE) {
      return;
    }

    const subscription = await registration.pushManager.getSubscription();
    if (subscription) {
      await subscription.unsubscribe().catch(() => {});
    }
  }));

  await Promise.all([
    deleteIndexedDbDatabase('firebase-installations-database'),
    deleteIndexedDbDatabase('firebase-messaging-database'),
    deleteIndexedDbDatabase('fcm_token_details_db'),
    deleteIndexedDbDatabase('fcm_vapid_details_db')
  ]);

  localStorage.removeItem(FIREBASE_LAST_TOKEN_KEY);
  localStorage.setItem(FIREBASE_BROWSER_STATE_RESET_KEY, signature);
}

async function saveToken(token) {
  if (!config?.tokenEndpoint || !token) return;
  if (localStorage.getItem(FIREBASE_DISABLED_KEY) === 'true') return;

  const signature = firebaseConfigSignature();
  const lastSignature = localStorage.getItem(FIREBASE_CONFIG_SIGNATURE_KEY);

  if (
    localStorage.getItem(FIREBASE_LAST_TOKEN_KEY) === token
    && lastSignature === signature
  ) {
    return;
  }

  console.info('[Firebase Messaging] Saving token...');

  const response = await fetch(config.tokenEndpoint, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken()
    },
    body: JSON.stringify({
      fcm_token: token,
      device_id: getDeviceId()
    })
  });

  if (!response.ok) {
    const body = await response.text().catch(() => '');
    throw new Error(`Token FCM diterima, tapi gagal disimpan ke server (${response.status}). ${body.slice(0, 160)}`);
  }

  localStorage.setItem(FIREBASE_LAST_TOKEN_KEY, token);
  localStorage.setItem(FIREBASE_CONFIG_SIGNATURE_KEY, signature);
}

async function deleteTokenFromServer() {
  if (!config?.deleteTokenEndpoint) return;

  const response = await fetch(config.deleteTokenEndpoint, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': csrfToken()
    }
  });

  if (!response.ok) {
    const body = await response.text().catch(() => '');
    throw new Error(`Gagal menonaktifkan notifikasi (${response.status}). ${body.slice(0, 160)}`);
  }
}

async function unregisterStaleFirebaseWorkers() {
  const registrations = await navigator.serviceWorker.getRegistrations();

  await Promise.all(registrations.map(registration => {
    const scopePath = new URL(registration.scope).pathname;
    const scriptPath = registration.active?.scriptURL
      ? new URL(registration.active.scriptURL).pathname
      : '';

    if (
      scriptPath === '/firebase-messaging-sw.js'
      || (scriptPath.endsWith('/firebase-messaging-sw.js') && scopePath !== FIREBASE_SW_SCOPE)
    ) {
      console.info('[Firebase Messaging] Unregistering stale Firebase service worker...', registration.scope);
      return registration.unregister();
    }

    return Promise.resolve();
  }));
}

async function registerFirebaseMessaging(options = {}) {
  const requestPermission = options.requestPermission !== false;

  if (!requestPermission && localStorage.getItem(FIREBASE_DISABLED_KEY) === 'true') {
    return null;
  }

  if (requestPermission) {
    localStorage.removeItem(FIREBASE_DISABLED_KEY);
  }

  if (!config?.apiKey || !config?.vapidKey || !config?.projectId) {
    throw new Error('Konfigurasi Firebase belum lengkap.');
  }

  const support = notificationSupportDetails();
  if (!support.supported) {
    console.warn('[Firebase Messaging] Browser notification support check failed:', support);
    throw new Error(support.message);
  }

  if (!window.firebase?.messaging) {
    throw new Error('Firebase Messaging SDK belum termuat.');
  }

  if (!(await firebase.messaging.isSupported())) {
    console.warn('[Firebase Messaging] Firebase support check failed:', notificationSupportDetails());
    throw new Error(isIosSafariLike() && !isStandalonePwa()
      ? 'Di Safari iPhone/iPad, notifikasi hanya bisa aktif setelah website ditambahkan ke Layar Utama lalu dibuka dari ikon aplikasi.'
      : 'Firebase Cloud Messaging belum didukung di browser ini.');
  }

  const permission = Notification.permission === 'granted'
    ? 'granted'
    : (requestPermission ? await Notification.requestPermission() : Notification.permission);

  if (permission !== 'granted') {
    return null;
  }

  const firebaseOptions = {
    apiKey: config.apiKey,
    authDomain: config.authDomain,
    projectId: config.projectId,
    storageBucket: config.storageBucket,
    messagingSenderId: config.messagingSenderId,
    appId: config.appId,
    measurementId: config.measurementId
  };

  const signature = firebaseConfigSignature();
  if (localStorage.getItem(FIREBASE_CONFIG_SIGNATURE_KEY) !== signature) {
    localStorage.removeItem(FIREBASE_LAST_TOKEN_KEY);
  }

  if (localStorage.getItem(FIREBASE_CONFIG_SIGNATURE_KEY) !== signature) {
    await resetFirebaseBrowserStateForCurrentConfig(signature);
    messagingInstance = null;
  }

  const appName = firebaseAppName();
  const app = firebase.apps.find(existingApp => existingApp.name === appName)
    || firebase.initializeApp(firebaseOptions, appName);

  await unregisterStaleFirebaseWorkers();

  const swUrl = FIREBASE_SW_PATH;
  console.info('[Firebase Messaging] Registering service worker...', swUrl);

  const registration = await navigator.serviceWorker.register(swUrl, {
    scope: FIREBASE_SW_SCOPE,
    updateViaCache: 'none'
  });

  await registration.update();
  console.info('[Firebase Messaging] Service worker registered.', registration.scope);

  messagingInstance = messagingInstance || firebase.messaging(app);
  console.info('[Firebase Messaging] Requesting FCM token...');

  const token = await messagingInstance.getToken({
    vapidKey: config.vapidKey,
    serviceWorkerRegistration: registration
  });

  console.info('[Firebase Messaging] FCM token received.');

  await saveToken(token);

  if (!window.firebaseMessagingForegroundHandlerRegistered) {
    window.firebaseMessagingForegroundHandlerRegistered = true;
    messagingInstance.onMessage(payload => {
      const notification = payload.notification || {};
      const data = payload.data || {};

      if (Notification.permission === 'granted' && notification.title) {
        new Notification(notification.title, {
          body: notification.body || '',
          icon: data.icon || '/images/icons/icon-192x192.png',
          data: {
            url: data.url || '/'
          }
        });
      }
    });
  }

  return token;
}

window.enableFirebaseMessaging = function () {
  return registerFirebaseMessaging({ requestPermission: true });
};

window.disableFirebaseMessaging = async function () {
  await deleteTokenFromServer();
  localStorage.setItem(FIREBASE_DISABLED_KEY, 'true');
  localStorage.removeItem(FIREBASE_LAST_TOKEN_KEY);
  localStorage.removeItem(FIREBASE_CONFIG_SIGNATURE_KEY);
  localStorage.removeItem(FIREBASE_BROWSER_STATE_RESET_KEY);
  return true;
};

window.syncFirebaseMessaging = function () {
  return registerFirebaseMessaging({ requestPermission: false });
};

window.firebaseMessagingSupportDetails = notificationSupportDetails;

function syncGrantedPermission() {
  if (!('Notification' in window)) return;
  if (Notification.permission !== 'granted') return;

  window.syncFirebaseMessaging().catch(error => {
    console.error('[Firebase Messaging] Registration failed:', {
      name: error?.name,
      code: error?.code,
      message: error?.message,
      stack: error?.stack,
      error
    });
  });
}

window.firebaseMessagingLoaded = true;
window.dispatchEvent(new CustomEvent('firebase-messaging-loaded'));

if (document.readyState === 'loading') {
  window.addEventListener('load', syncGrantedPermission);
} else {
  syncGrantedPermission();
}
