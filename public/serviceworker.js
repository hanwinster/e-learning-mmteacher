
// const staticCacheName = 'site-static-v4';
// const dynamicCacheName = 'site-dynamic-v4'; 
var timeNow = new Date().getTime();      
const staticCacheName = 'site-static_ver-'+timeNow;
const dynamicCacheName = 'site-dynamic_ver-'+timeNow;   
const assets = [    
    '/',  
    '/offline',  
    '/assets/img/logos/favicon.ico',
    '/assets/img/logos/E_learning.png',
    '/assets/fonts/fonts/Pyidaungsu-2.5.3_Regular.ttf',
    '/assets/fonts/fonts/zawgyi.ttf',
    '/css/app.css',
    '/js/app.js',
    '/assets/js/page.min.js',
    '/assets/js/scripts.js',
    '/assets/js/font-awesome_5.15.4.js',
    '/assets/videos/About-Mm-Teacher-Platform_2.mp4',
     // 'https://www.google.com/recaptcha/api.js', //got error if we include this!!
    'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap',
    //'https://use.fontawesome.com/releases/v5.15.4/js/all.js',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js',
     // 'https://www.youtube.com/watch?v=Y_9t3eQFmU4' // this will make static cache errorenous
    'assets/backend/adminlte/plugins/daterangepicker/daterangepicker.css',
    'assets/backend/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
    'assets/backend/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
    'assets/backend/adminlte/adminlte.min.css',
    'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css', 
    'css/admin.css',
    'assets/backend/adminlte/plugins/jquery/jquery.min.js',
    'assets/backend/adminlte/plugins/jquery-ui/jquery-ui.min.js',
    'assets/backend/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js',
    'assets/backend/adminlte/plugins/chart.js/Chart.min.js',
    'assets/backend/adminlte/plugins/moment/moment.min.js',
    'assets/backend/adminlte/plugins/daterangepicker/daterangepicker.js',
    'assets/backend/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
    'assets/backend/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
    'assets/backend/adminlte/adminlte.min.js',
    'https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js'
    // '/images/icons/icon-72x72.png',
    // '/images/icons/icon-96x96.png',
    // '/images/icons/icon-128x128.png',
    // '/images/icons/icon-144x144.png',
    // '/images/icons/icon-152x152.png',
    // '/images/icons/icon-192x192.png',
    // '/images/icons/icon-384x384.png',
    // '/images/icons/icon-512x512.png',
];

// cache size limit function 
const limitCacheSize = (name, size) => {
  caches.open(name).then(cache => {
    cache.keys().then(keys => {
      if(keys.length > size){
        cache.delete(keys[0]).then(limitCacheSize(name, size));
      }
    });
  });
};

// install event
self.addEventListener('install', evt => {
    //console.log('service worker installed');
    evt.waitUntil(
      caches.open(staticCacheName).then((cache) => {
        console.log('caching static assets');
        cache.addAll(assets);
      })
    );
  });
  
  // activate event
  self.addEventListener('activate', evt => {
    //console.log('service worker activated');
    evt.waitUntil(
      caches.keys().then(keys => {
        //console.log(keys);
        return Promise.all(keys
          .filter(key => key !== staticCacheName && key !== dynamicCacheName)
          .map(key => caches.delete(key))
        );
      })
    );
  });
  
  // fetch event
  self.addEventListener('fetch', evt => {
    //console.log('fetch event', evt);
    evt.respondWith(
      caches.match(evt.request).then(cacheRes => {
        return cacheRes || fetch(evt.request).then(fetchRes => {
          return caches.open(dynamicCacheName).then(cache => {
            cache.put(evt.request.url, fetchRes.clone());
            // check cached items size
            //limitCacheSize(dynamicCacheName, 15);
            return fetchRes;
          })
        });
      }).catch(() => {
        if (evt.request.url.indexOf('.html') > -1) { 
          return caches.match('offline');
        }
    })
    );
  });









// Cache on install
// self.addEventListener("install", event => {
//     //this.skipWaiting();
//     event.waitUntil(
//         caches.open(staticCacheName)
//             .then(cache => { console.log('caching static assets ', cache);
//                 cache.addAll(filesToCache);
//             })
//     )
// });

// Clear cache on activate
// self.addEventListener('activate', event => {
//     console.log('activate event started ', event)
//     event.waitUntil(
//         caches.keys().then(cacheNames => { console.log('cache names to be activated ',cacheNames);
//             return Promise.all(
//                 cacheNames
//                     .filter(cacheName => (cacheName.startsWith("pwa-v")))
//                     .filter(cacheName => (cacheName !== staticCacheName))
//                     .map(cacheName => caches.delete(cacheName))
//             );
//         })
//     );
// });

// Serve from Cache
// self.addEventListener("fetch", event => {
//     console.log('fetched event statrted', event);
//     event.respondWith(
//         caches.match(event.request)
//             .then(response => { console.log('about to return fetched cache = ', response)
//                 return response || fetch(event.request);
//             })
//             .catch(() => { console.log('about to return offline');
//                 return caches.match('offline');
//             })
//     )
// });