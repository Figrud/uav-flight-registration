const CACHE_NAME = 'uav-dashboard-v1.0.0';
const urlsToCache = [
  '/flight-registration-wordpress/wp-admin/admin.php?page=flight-dashboard',
  '/flight-registration-wordpress/wp-admin/admin.php?page=flight-registration',
  '/flight-registration-wordpress/wp-content/plugins/flight-registration/assets/css/dashboard-style.css',
  '/flight-registration-wordpress/wp-content/plugins/flight-registration/assets/js/script.js',
  '/flight-registration-wordpress/wp-includes/css/dashicons.min.css',
  '/flight-registration-wordpress/wp-admin/css/common.min.css'
];

// Install Service Worker
self.addEventListener('install', event => {
  console.log('🚁 UAV Dashboard SW: Installing...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('🚁 UAV Dashboard SW: Caching files');
        return cache.addAll(urlsToCache);
      })
      .catch(err => {
        console.log('🚁 UAV Dashboard SW: Cache failed', err);
      })
  );
});

// Fetch Strategy: Network First, fallback to Cache
self.addEventListener('fetch', event => {
  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Valid response - update cache
        if (response.status === 200) {
          const responseClone = response.clone();
          caches.open(CACHE_NAME)
            .then(cache => {
              cache.put(event.request, responseClone);
            });
        }
        return response;
      })
      .catch(() => {
        // Network failed - try cache
        return caches.match(event.request)
          .then(response => {
            if (response) {
              return response;
            }
            // Return offline page for navigation requests
            if (event.request.destination === 'document') {
              return caches.match('/offline.html');
            }
          });
      })
  );
});

// Activate Service Worker
self.addEventListener('activate', event => {
  console.log('🚁 UAV Dashboard SW: Activating...');
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            console.log('🚁 UAV Dashboard SW: Deleting old cache', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Background Sync για offline submissions
self.addEventListener('sync', event => {
  if (event.tag === 'flight-submission') {
    console.log('🚁 UAV Dashboard SW: Syncing flight data...');
    event.waitUntil(syncFlightData());
  }
});

// Push Notifications
self.addEventListener('push', event => {
  const options = {
    body: event.data ? event.data.text() : 'Νέα ενημέρωση UAV Dashboard',
    icon: '/wp-content/plugins/flight-registration/assets/icons/icon-192x192.png',
    badge: '/wp-content/plugins/flight-registration/assets/icons/icon-72x72.png',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    },
    actions: [
      {
        action: 'explore',
        title: 'Άνοιγμα Dashboard',
        icon: '/wp-content/plugins/flight-registration/assets/icons/icon-96x96.png'
      },
      {
        action: 'close',
        title: 'Κλείσιμο',
        icon: '/wp-content/plugins/flight-registration/assets/icons/icon-96x96.png'
      }
    ]
  };

  event.waitUntil(
    self.registration.showNotification('UAV Dashboard', options)
  );
});

// Helper Functions
async function syncFlightData() {
  // Sync pending flight submissions when back online
  try {
    const pendingData = await getStoredFlightData();
    for (const flightData of pendingData) {
      await submitFlightData(flightData);
    }
    await clearStoredFlightData();
  } catch (error) {
    console.error('🚁 UAV Dashboard SW: Sync failed', error);
  }
}

async function getStoredFlightData() {
  // Get data from IndexedDB
  return [];
}

async function submitFlightData(data) {
  // Submit to server
  return fetch('/wp-admin/admin-ajax.php', {
    method: 'POST',
    body: data
  });
}

async function clearStoredFlightData() {
  // Clear IndexedDB
}