<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- PWA / App Install Meta Tags -->
<meta name="application-name" content="Malsnuel Enterprise">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Malsnuel Enterprise">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#ffffff">

<!-- iOS App Icons - Using logo.jpg -->
<link rel="apple-touch-icon" sizes="180x180" href="/logo.jpg?v=3">
<link rel="apple-touch-icon" sizes="152x152" href="/logo.jpg?v=3">
<link rel="apple-touch-icon" sizes="120x120" href="/logo.jpg?v=3">
<link rel="apple-touch-icon" sizes="76x76" href="/logo.jpg?v=3">

<!-- iOS App Startup Screens -->
<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="/logo.jpg?v=3">
<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="/logo.jpg?v=3">
<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" href="/logo.jpg?v=3">
<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" href="/logo.jpg?v=3">
<link rel="apple-touch-startup-image" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)" href="/logo.jpg?v=3">
<link rel="apple-touch-startup-image" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3)" href="/logo.jpg?v=3">

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Malsnuel Enterprise') : config('app.name', 'Malsnuel Enterprise') }}
</title>

<!-- Favicon - Using logo.jpg -->
<link rel="icon" type="image/jpeg" href="/logo.jpg?v=3">
<link rel="shortcut icon" type="image/jpeg" href="/logo.jpg?v=3">
<link rel="apple-touch-icon" href="/logo.jpg?v=3">
<link rel="manifest" href="/manifest.json?v=2">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Force Light Mode for Flux UI - Must be before @fluxAppearance -->
<script>
    // Immediately set localStorage to light before page loads
    localStorage.setItem('flux-theme', 'light');
    localStorage.setItem('color-theme', 'light');
    
    // Override any dark class immediately
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        localStorage.setItem('flux-theme', 'light');
        localStorage.setItem('color-theme', 'light');
    }
</script>
@fluxAppearance

<script>
// Ensure light mode is enforced after page loads
document.addEventListener('DOMContentLoaded', function() {
    // Force remove dark class and add light
    document.documentElement.classList.remove('dark');
    document.documentElement.classList.add('light');
    localStorage.setItem('flux-theme', 'light');
    localStorage.setItem('color-theme', 'light');
});
</script>

<script>
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').catch(function(err) {
        console.log('Service Worker registration failed:', err);
    });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Detect if running as installed PWA
    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
        localStorage.setItem('pwa-installed', 'true');
    }

    // Permanently hide if app is already installed
    if (localStorage.getItem('pwa-installed') === 'true') {
        return;
    }

    // Check if user previously dismissed the install prompt (don't show again for 7 days)
    var dismissedTime = localStorage.getItem('pwa-install-dismissed');
    if (dismissedTime) {
        var sevenDays = 7 * 24 * 60 * 60 * 1000;
        if (Date.now() - parseInt(dismissedTime) < sevenDays) {
            return;
        }
    }

    var deferredPrompt = null;

    // Detect browser for install instructions
    function getInstallInstructions() {
        var ua = navigator.userAgent;
        if (/iPhone|iPad|iPod/.test(ua)) {
            return 'Tap the Share button, then "Add to Home Screen"';
        }
        if (/Android/.test(ua)) {
            return 'Tap the menu (three dots), then "Install App" or "Add to Home Screen"';
        }
        if (/Chrome/.test(ua) && !/Edg/.test(ua)) {
            return 'Click the install icon in the address bar, or go to menu (three dots) > "Install Malsnuel Enterprise"';
        }
        if (/Edg/.test(ua)) {
            return 'Click the install icon in the address bar, or go to menu (three dots) > "Apps" > "Install this site as an app"';
        }
        if (/Firefox/.test(ua)) {
            return 'Click the install icon in the address bar, or go to menu > "Install"';
        }
        return 'Open the browser menu and select "Install App" or "Add to Home Screen"';
    }

    // Create the install banner - always visible by default
    var installBanner = document.createElement('div');
    installBanner.id = 'pwa-install-banner';
    installBanner.style.cssText = 'position:fixed;bottom:0;left:0;right:0;background:#18181b;color:white;padding:16px;display:flex;align-items:center;justify-content:space-between;z-index:9999;box-shadow:0 -2px 10px rgba(0,0,0,0.1);font-family:system-ui,sans-serif;gap:12px;flex-wrap:wrap;';
    installBanner.innerHTML = '<span style="font-size:14px;flex:1;min-width:200px;">Install Malsnuel Enterprise for a better experience</span><div style="display:flex;gap:8px;flex-shrink:0;"><button id="pwa-install-btn" style="background:#3b82f6;color:white;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:14px;font-weight:500;">Install</button><button id="pwa-dismiss-btn" style="background:transparent;color:#a1a1aa;border:1px solid #3f3f46;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:14px;">Not now</button></div>';
    document.body.appendChild(installBanner);

    // Listen for native install prompt availability
    window.addEventListener('beforeinstallprompt', function(e) {
        e.preventDefault();
        deferredPrompt = e;
    });

    document.getElementById('pwa-install-btn').addEventListener('click', function() {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function(choiceResult) {
                if (choiceResult.outcome === 'accepted') {
                    installBanner.style.display = 'none';
                    localStorage.setItem('pwa-installed', 'true');
                }
                deferredPrompt = null;
            });
        } else {
            alert(getInstallInstructions());
        }
    });

    document.getElementById('pwa-dismiss-btn').addEventListener('click', function() {
        installBanner.style.display = 'none';
        localStorage.setItem('pwa-install-dismissed', Date.now().toString());
    });

    window.addEventListener('appinstalled', function() {
        installBanner.style.display = 'none';
        localStorage.setItem('pwa-installed', 'true');
        deferredPrompt = null;
    });
});
</script>
