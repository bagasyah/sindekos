document.addEventListener('DOMContentLoaded', function() {
    const loadingOverlay = document.getElementById('loading-overlay');
    
    function showLoadingOverlay() {
        loadingOverlay.style.display = 'flex';
    }

    function hideLoadingOverlay() {
        loadingOverlay.style.display = 'none';
    }

    document.addEventListener('click', function(event) {
        var target = event.target;
        if (target.tagName === 'A' && !target.getAttribute('href').startsWith('#') && !target.getAttribute('href').startsWith('javascript:')) {
            showLoadingOverlay();
        }
    });

    document.addEventListener('submit', function(event) {
        showLoadingOverlay();
    });

    window.addEventListener('load', hideLoadingOverlay);

    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            hideLoadingOverlay();
        }
    });
});
