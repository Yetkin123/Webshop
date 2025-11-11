(function(){
    if (window.__asideInitted) return;
    window.__asideInitted = true;

    function initAside(){
        var asideList = document.getElementById('aside-content-list');
        var asideLogo = document.getElementById('aside-logo');
        var asideToggle = document.getElementById('aside-toggle');
        var asideContainer = document.querySelector('.aside-container');
        if (!asideContainer) return;

        // collect text elements
        var textEls = Array.prototype.slice.call(asideContainer.querySelectorAll('.aside-content-list-item-text'));

        // config: match this to your width-transition duration + buffer (ms)
        var WIDTH_TRANSITION_FALLBACK = 1100; // adjust to desired delay (e.g. 1500 for longer)

        var showTimeout = null;
        var transListener = null;

        function hideTextImmediate(){
            clearTimeout(showTimeout);
            if (transListener) {
                asideContainer.removeEventListener('transitionend', transListener);
                transListener = null;
            }
            textEls.forEach(function(el){
                el.style.transition = 'opacity 120ms ease';
                el.style.transitionDelay = '0s';
                el.style.opacity = '0';
                el.style.visibility = 'hidden';
                el.style.pointerEvents = 'none';
            });
        }

        function showTextNow(){
            clearTimeout(showTimeout);
            textEls.forEach(function(el){
                // ensure transition is present for fade-in
                el.style.transition = 'opacity 300ms ease';
                el.style.transitionDelay = '0s';
                el.style.visibility = 'visible';
                el.style.pointerEvents = '';
                // trigger paint then fade in
                requestAnimationFrame(function(){
                    el.style.opacity = '1';
                });
            });
        }

        function scheduleShowTextAfterWidth(){
            // ensure hidden first
            textEls.forEach(function(el){
                el.style.opacity = '0';
                el.style.visibility = 'hidden';
                el.style.pointerEvents = 'none';
            });

            // cleanup previous listener/timeout
            if (transListener) asideContainer.removeEventListener('transitionend', transListener);
            clearTimeout(showTimeout);

            transListener = function(e){
                if (e.propertyName === 'width') {
                    showTextNow();
                    asideContainer.removeEventListener('transitionend', transListener);
                    transListener = null;
                }
            };
            asideContainer.addEventListener('transitionend', transListener);

            // fallback in case transitionend doesn't fire
            showTimeout = setTimeout(function(){
                showTextNow();
                if (transListener) {
                    asideContainer.removeEventListener('transitionend', transListener);
                    transListener = null;
                }
            }, WIDTH_TRANSITION_FALLBACK);
        }

        // initial state
        if (!asideToggle || !asideToggle.checked) {
            // expanded: show text (after small tick)
            setTimeout(showTextNow, 60);
        } else {
            hideTextImmediate();
        }

        // respond to toggle changes
        if (asideToggle) {
            asideToggle.addEventListener('change', function(){
                if (asideToggle.checked) {
                    // collapsed
                    hideTextImmediate();
                } else {
                    // expanded
                    scheduleShowTextAfterWidth();
                }
            });
        }

        // existing bindings: logo toggles aside
        if (asideLogo && !asideLogo.__bound) {
            asideLogo.addEventListener('click', function(e){
                e.preventDefault();
                var t = document.getElementById('aside-toggle');
                if (t) t.click();
            });
            asideLogo.__bound = true;
        }

        // existing bindings: click -> smooth scroll
        if (asideList && !asideList.__bound) {
            asideList.addEventListener('click', function(ev){
                var li = ev.target.closest && ev.target.closest('li[data-target]');
                if (!li) return;
                var targetId = li.getAttribute('data-target');
                if (!targetId) return;
                var targetEl = document.getElementById(targetId);
                if (!targetEl) return;

                ev.preventDefault();
                targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                try { history.replaceState(null, '', '#' + targetId); } catch (e) {}
                var asideToggle = document.getElementById('aside-toggle');
                if (asideToggle && window.innerWidth <= 700) asideToggle.checked = false;
            }, false);

            asideList.__bound = true;
        }
    }

    document.addEventListener('DOMContentLoaded', initAside);
    window.initAside = initAside;
})();