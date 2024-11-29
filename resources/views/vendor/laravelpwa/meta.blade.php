<!-- Web Application Manifest -->
<link rel="manifest" href="{{ route('laravelpwa.manifest') }}">
<!-- Chrome for Android theme color -->
<meta name="theme-color" content="{{ $config['theme_color'] }}">

<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="{{ $config['display'] == 'standalone' ? 'yes' : 'no' }}">
<meta name="application-name" content="{{ $config['short_name'] }}">
<link rel="icon" sizes="{{ data_get(end($config['icons']), 'sizes') }}" href="{{ data_get(end($config['icons']), 'src') }}">

<!-- Add to homescreen for Safari on iOS -->
<meta name="apple-mobile-web-app-capable" content="{{ $config['display'] == 'standalone' ? 'yes' : 'no' }}">
<meta name="apple-mobile-web-app-status-bar-style" content="{{  $config['status_bar'] }}">
<meta name="apple-mobile-web-app-title" content="{{ $config['short_name'] }}">
<link rel="apple-touch-icon" href="{{ data_get(end($config['icons']), 'src') }}">


<link href="{{ $config['splash']['640x1136'] }}" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['750x1334'] }}" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['1242x2208'] }}" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['1125x2436'] }}" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['828x1792'] }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['1242x2688'] }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['1536x2048'] }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['1668x2224'] }}" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['1668x2388'] }}" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
<link href="{{ $config['splash']['2048x2732'] }}" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

<!-- Tile for Win8 -->
<meta name="msapplication-TileColor" content="{{ $config['background_color'] }}">
<meta name="msapplication-TileImage" content="{{ data_get(end($config['icons']), 'src') }}">

<script type="text/javascript">


    var usagePercent = 0;
    var quotaVolume =  0;
    var usageVolume = 0;
    var translations = {
                            nowOffline: "@lang('You are now offline')",
                            enableOffline: "@lang('You have enabled offline mode and all your visited pages will be cached!')",
                            explainEnable: "@lang('If you have enabled the offline mode, all the pages you visited with offline mode can be accessible but the new links cannot be reachable!')",
                            explainDisable: "@lang('If you have not enabled the offline mode, only this page will be accessible when it is offline!')", 
                            youHaveCached: "@lang('You have cached ')",    
                            percent: "@lang(' percent ')",      
                            availStorage: "@lang(' of available cache storage ')",      
                            youHaveUsed: "@lang('.You have used ')",     
                            ofof: "@lang(' of ')"                      
            };
    calculateUsage();
    function calculateUsage() {
        navigator.storage.estimate().then(function(estimate) {
        quotaVolume = estimate.quota;
        usageVolume = estimate.usage;
        usagePercent = (estimate.usage / estimate.quota * 100).toFixed(2);
            //console.log(" used about  "+usagePercent+" percent of available storage "+estimate.quota);
        });
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    document.addEventListener("DOMContentLoaded", function(event) { 
        var checkBox = document.getElementById('is-offline-mode');
        checkServiceWorkerAndEnableCheck(checkBox); //is-offline-mode-mobile
        //check if there's service worker and then offline button should be checked

        var checkBoxMobile = document.getElementById('is-offline-mode-mobile');
        checkServiceWorkerAndEnableCheck(checkBoxMobile);

        checkBox.addEventListener('change', (event) => { // if change, on and off offline mode
            if (event.currentTarget.checked) {
                //console.log('offline mode selected');
                initializeServiceWorker();
                //window.location.reload();
            } else {
                //console.log("online mode");
                //console.log('about to delete the cache ',caches.keys());
                $("#offline-alert").addClass("d-none");
                caches.keys().then(function(names) { 
                    for (let name of names) {                       
                        caches.delete(name); 
                    }
                });
                removeServiceWorker();
                localStorage.clear();
                //window.location.reload();
            }
        });

        checkBoxMobile.addEventListener('change', (event) => { // if change, on and off offline mode
            if (event.currentTarget.checked) {
                //console.log('offline mode selected');
                initializeServiceWorker();
                //window.location.reload();
            } else {
                //console.log("online mode");
               // console.log('about to delete the cache ',caches.keys());
                $("#offline-alert").addClass("d-none");
                caches.keys().then(function(names) { 
                    for (let name of names) {                       
                        caches.delete(name); 
                    }
                });
                removeServiceWorker();
                localStorage.clear();
                //window.location.reload();
            }
        });

        if (navigator.onLine) { 
            //alert('online');
            $("#offline-alert").addClass("d-none"); // hide when it's online
            //$("#offline-cache-alert").addClass("d-none");
           // $('#offline-cache-alert-usage').html('You have used '+usagePercent+" percent ("+
            //formatBytes(usageVolume)+" ) of available cache storage "+formatBytes(quotaVolume));
        } else {
            //alert('offline');            
            $("#offline-alert").removeClass("d-none"); // display when it's offline
            $('#offline-alert-title').html(translations.nowOffline);
            $('#offline-alert-text').html(translations.explainEnable+ translations.explainDisable)
            $('#offline-cache-alert-usage').html(translations.youHaveCached+usagePercent+translations.percent+
                formatBytes(usageVolume)+translations.availStorage+formatBytes(quotaVolume));
        }
    });
    
    // Initialize the service worker
    function initializeServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/serviceworker.js', {
                scope: '.'
            }).then(function (registration) {
                // Registration was successful
               // console.log('E-learning PWA: ServiceWorker registration successful with scope: ', registration.scope);
                $("#offline-alert").removeClass("d-none");
                $('#offline-alert-title').html(translations.enableOffline);
                $('#offline-alert-text').html(translations.youHaveCached+usagePercent+translations.percent+
                        translations.youHaveUsed+formatBytes(usageVolume)+translations.ofof+formatBytes(quotaVolume));
            }, function (err) {
                // registration failed :(
                //console.log('E-learning PWA: ServiceWorker registration failed: ', err); 
            });       
        }
    }
    
    function removeServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) {
                //console.log('registrations before deleting ', registrations);
                for(let registration of registrations) {
                    //registration.unregister();     
                    registration.unregister().then(function(boolean) { console.log('unregister returns ', boolean);
                        // if boolean = true, unregister is successful
                    });           
                } 
            }).catch(function(err) {
                    //console.log('E-learning PWA: ServiceWorker un-registration failed: ', err);
            });
        }
    }

    function checkServiceWorkerAndEnableCheck(checkBox) {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(function(registrations) { 
                //console.log('there are some registrations for sw ', registrations, registrations.length);
                if(registrations.length) {
                    checkBox.checked = true;
                    $("#offline-alert").removeClass("d-none");
                    if (navigator.onLine) {
                        $('#offline-alert-title').html(translations.enableOffline);
                        $('#offline-alert-text').html(translations.youHaveUsed+usagePercent+translations.percent+
                            formatBytes(usageVolume)+translations.availStorage+formatBytes(quotaVolume));
                        $('#offline-cache-alert-usage').hide();
                    } else {
                        $('#offline-alert-title').html(translations.nowOffline);
                        $('#offline-alert-text').html(translations.explainEnable+ translations.explainDisable)
                        $('#offline-cache-alert-usage').html(translations.youHaveCached+usagePercent+translations.percent+
                            formatBytes(usageVolume)+translations.availStorage+formatBytes(quotaVolume));
                    }
                    
                } else {
                    //console.log('about to delete the cache : on page load',caches.keys());
                    caches.keys().then(function(names) { 
                        for (let name of names) {                       
                            caches.delete(name); 
                        }
                    });
                }                    
            });
        }
    }

    window.addEventListener('offline', function(e) {  // listen for offline event
        // alert('offline'); 
        calculateUsage();
        $("#offline-alert").removeClass("d-none");
        $('#offline-alert-title').html(translations.nowOffline);
        $('#offline-alert-text').html(translations.explainEnable+ translations.explainDisable)
        $('#offline-cache-alert-usage').html(translations.youHaveCached+usagePercent+translations.percent+
            formatBytes(usageVolume)+translations.availStorage+formatBytes(quotaVolume));
    });

    window.addEventListener('online', function(e) { // listen for online event
        //alert('online'); 
        $("#offline-alert").addClass("d-none"); 
    });
</script>