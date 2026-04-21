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
    var deferredPrompt = null;

    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
        localStorage.setItem('pwa-installed', 'true');
    }

    if (localStorage.getItem('pwa-installed') === 'true') {
        return;
    }

    function showInstallAlert() {
        var alertBox = document.createElement('div');
        alertBox.id = 'pwa-install-alert';
        alertBox.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;color:#18181b;padding:24px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,0.2);z-index:9999;font-family:system-ui,sans-serif;max-width:320px;width:90%;text-align:center;';
        alertBox.innerHTML = '<h3 style="margin:0 0 8px;font-size:18px;font-weight:600;">Install App</h3><p style="margin:0 0 20px;color:#52525b;font-size:14px;">Install Malsnuel Enterprise for a better experience</p><div style="display:flex;gap:12px;"><button id="pwa-cancel-btn" style="flex:1;padding:10px 16px;border:1px solid #d4d4d8;border-radius:6px;background:transparent;color:#52525b;font-size:14px;cursor:pointer;">Cancel</button><button id="pwa-confirm-btn" style="flex:1;padding:10px 16px;border:none;border-radius:6px;background:#3b82f6;color:white;font-size:14px;font-weight:500;cursor:pointer;">Install</button></div>';
        document.body.appendChild(alertBox);

        document.getElementById('pwa-confirm-btn').addEventListener('click', function() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult) {
                    if (choiceResult.outcome === 'accepted') {
                        localStorage.setItem('pwa-installed', 'true');
                    }
                    deferredPrompt = null;
                });
            }
            alertBox.remove();
        });

        document.getElementById('pwa-cancel-btn').addEventListener('click', function() {
            localStorage.setItem('pwa-install-dismissed', 'true');
            alertBox.remove();
        });
    }

    window.addEventListener('beforeinstallprompt', function(e) {
        e.preventDefault();
        deferredPrompt = e;
        showInstallAlert();
    });

    window.addEventListener('appinstalled', function() {
        localStorage.setItem('pwa-installed', 'true');
        deferredPrompt = null;
    });
});
</script>
