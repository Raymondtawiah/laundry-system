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
document.addEventListener('DOMContentLoaded', function() {
    // Function to detect if device is a mobile phone (strict check)
    function isMobilePhone() {
        const userAgent = navigator.userAgent || navigator.vendor || window.opera;
        
        // Check for mobile devices
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(userAgent);
        
        // Additional check: screen size should be small (phone-sized)
        const isSmallScreen = window.innerWidth <= 768;
        
        // Check if it's NOT a desktop/laptop
        const isDesktop = /Windows|Macintosh|Linux/i.test(userAgent) && !/Mobile/i.test(userAgent);
        
        // Only show on phones, not tablets or desktops
        return isMobile && isSmallScreen && !isDesktop;
    }
    
    // Skip PWA install logic on desktop or tablet
    if (!isMobilePhone()) {
        return;
    }
    
    // Check if user previously dismissed the install prompt (don't show again for 7 days)
    const dismissedTime = localStorage.getItem('pwa-install-dismissed');
    if (dismissedTime) {
        const sevenDays = 7 * 24 * 60 * 60 * 1000;
        if (Date.now() - parseInt(dismissedTime) < sevenDays) {
            return; // Don't show if dismissed within 7 days
        }
    }
    
    let deferredPrompt;
    const installBanner = document.createElement('div');
    installBanner.id = 'pwa-install-banner';
    installBanner.style.cssText = 'position:fixed;bottom:0;left:0;right:0;background:#18181b;color:white;padding:16px;display:flex;align-items:center;justify-content:space-between;z-index:9999;box-shadow:0 -2px 10px rgba(0,0,0,0.1);font-family:system-ui,sans-serif;';
    installBanner.innerHTML = '<span style="font-size:14px;">Install Malsnuel Enterprise for a better experience</span><div style="display:flex;gap:8px;"><button id="pwa-install-btn" style="background:#3b82f6;color:white;border:none;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:14px;font-weight:500;">Install</button><button id="pwa-dismiss-btn" style="background:transparent;color:#a1a1aa;border:1px solid #3f3f46;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:14px;">Not now</button></div>';
    installBanner.style.display = 'none';
    document.body.appendChild(installBanner);

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        // Show banner only when the install prompt is available
        installBanner.style.display = 'flex';
    });

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
        // Remember dismissal for 7 days
        localStorage.setItem('pwa-install-dismissed', Date.now().toString());
    });

    // Check if already installed
    window.addEventListener('appinstalled', () => {
        installBanner.style.display = 'none';
        deferredPrompt = null;
    });
});
</script>
