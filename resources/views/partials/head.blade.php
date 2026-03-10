<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- PWA / App Install Meta Tags -->
<meta name="application-name" content="Malsnuel Enterprise">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Malsnuel Enterprise">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#18181b">

<!-- iOS App Icons -->
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon.png">

<!-- iOS App Startup Screens -->
<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" href="/apple-touch-icon.png">
<link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" href="/apple-touch-icon.png">
<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" href="/apple-touch-icon.png">
<link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" href="/apple-touch-icon.png">
<link rel="apple-touch-startup-image" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)" href="/apple-touch-icon.png">
<link rel="apple-touch-startup-image" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3)" href="/apple-touch-icon.png">

<title>
    {{ filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

<link rel="icon" href="/logo.jpg" type="image/jpeg">
<link rel="icon" href="/logo.jpg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/logo.jpg">
<link rel="manifest" href="/manifest.json">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

<script>
document.addEventListener('DOMContentLoaded', function() {
    // PWA Install Prompt
    let deferredPrompt;
    const installBanner = document.createElement('div');
    installBanner.id = 'pwa-install-banner';
    installBanner.style.cssText = 'position:fixed;bottom:0;left:0;right:0;background:#18181b;color:white;padding:16px;display:flex;align-items:center;justify-content:space-between;z-index:9999;box-shadow:0 -2px 10px rgba(0,0,0,0.1);font-family:system-ui,sans-serif;';
    installBanner.innerHTML = '<span style="font-size:14px;">Install Malsnuel Enterprise for a better experience</span><div style="display:flex;gap:8px;"><button id="pwa-install-btn" style="background:#3b82f6;color:white;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:14px;font-weight:500;">Install</button><button id="pwa-dismiss-btn" style="background:transparent;color:#a1a1aa;border:1px solid #3f3f46;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:14px;">Not now</button></div>';
    installBanner.style.display = 'none';
    document.body.appendChild(installBanner);

    // Clear previous dismissal so banner shows on refresh
    localStorage.removeItem('pwa-install-dismissed');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        installBanner.style.display = 'flex';
    });

    // Show banner after 2 seconds (in case beforeinstallprompt already fired)
    setTimeout(() => {
        installBanner.style.display = 'flex';
    }, 2000);

    document.getElementById('pwa-install-btn')?.addEventListener('click', async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            deferredPrompt = null;
            if (outcome === 'accepted') {
                installBanner.style.display = 'none';
            }
        } else {
            // Fallback for browsers without beforeinstallprompt
            alert('To install: Open this page in Chrome on Android, tap menu and select "Install App" or "Add to Home Screen"');
        }
    });

    document.getElementById('pwa-dismiss-btn')?.addEventListener('click', () => {
        installBanner.style.display = 'none';
        localStorage.setItem('pwa-install-dismissed', 'true');
    });

    // Check if already installed
    window.addEventListener('appinstalled', () => {
        installBanner.style.display = 'none';
        deferredPrompt = null;
    });
});
</script>
