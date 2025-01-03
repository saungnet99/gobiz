<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="{{ $config['display'] == 'standalone' ? 'yes' : 'no' }}">

<!-- Add to homescreen for Safari on iOS -->
<meta name="apple-mobile-web-app-capable" content="{{ $config['display'] == 'standalone' ? 'yes' : 'no' }}">
<meta name="apple-mobile-web-app-status-bar-style" content="{{ $config['status_bar'] }}">

<script type="text/javascript">
    // Initialize the service worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/serviceworker.js', {
            scope: '.'
        }).then(function(registration) {
            // Registration was successful
            // console.log('PWA: ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
            // registration failed :(
            // console.log('PWA: ServiceWorker registration failed: ', err);
        });
    }

    // Check if the app is installed
    window.addEventListener('beforeinstallprompt', function(event) {
        event.preventDefault();
        // Show the "Add to Home Screen" button
        document.getElementById('pwaModal').classList.remove('hidden');
        document.getElementById('pwaModal').classList.add('show');
        document.getElementById('pwaModal').style.display = 'block';;

        $("#addToHomeScreenButton").on("click", function() {
            event.prompt();
            event.userChoice.then(function(choiceResult) {
                if (choiceResult.outcome === 'accepted') {
                    document.getElementById('pwaModal').classList.add('hidden');
                    document.getElementById('pwaModal').classList.remove('show');
                    document.getElementById('pwaModal').style.display = 'none';;
                } else {
                    document.getElementById('pwaModal').classList.add('hidden');
                    document.getElementById('pwaModal').classList.remove('show');
                    document.getElementById('pwaModal').style.display = 'none';;
                }
            });
        });

        $("#closeModal").on("click", function() {
            document.getElementById('pwaModal').classList.add('hidden');
            document.getElementById('pwaModal').classList.remove('show');
            document.getElementById('pwaModal').style.display = 'none';;
        });
    });
</script>
