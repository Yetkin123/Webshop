function changeBackgroundOnScroll(){
    window.addEventListener('scroll', function(){
        var header = document.getElementById('header-container');
        if (!header) return;
        if ((window.scrollY !== 0) && (window.innerWidth > 700)){
            header.style.backgroundColor = '#3B3838';
        } else if (window.scrollY === 0 && window.innerWidth > 700) {
            header.style.backgroundColor = 'rgba(0,0,0,0)';
        } else {
            
        }
    });
}

// Mobile hamburger: inject en toggle, alleen actief voor small screens
function initHeaderToggle(){
    const header = document.getElementById('header-container');
    const nav = document.getElementById('header-top-nav');
    if (!header || !nav) return;

    // ensure aria state exists
    if (!nav.hasAttribute('aria-hidden')) nav.setAttribute('aria-hidden','true');

    // create hamburger only if missing
    let btn = document.getElementById('hamburger');
    if (!btn) {
        btn = document.createElement('button');
        btn.id = 'hamburger';
        btn.className = 'hamburger';
        btn.setAttribute('aria-label','Menu');
        btn.setAttribute('aria-expanded','false');
        btn.innerHTML = '<span class="hamburger-box"><span class="hamburger-inner"></span></span>';
        header.insertBefore(btn, header.firstChild);
    }

    // mobile-only behavior: attach listener; stopPropagation prevents immediate document-close
    btn.addEventListener('click', function(e){
        e.stopPropagation();
        const opened = header.classList.toggle('nav-open');
        btn.setAttribute('aria-expanded', opened ? 'true' : 'false');
        nav.setAttribute('aria-hidden', opened ? 'false' : 'true');
    });

    // prevent clicks inside nav from bubbling to document (which closes the menu)
    nav.addEventListener('click', function(e){
        e.stopPropagation();
    });

    // click outside closes menu
    document.addEventListener('click', function(){
        if (!header.classList.contains('nav-open')) return;
        header.classList.remove('nav-open');
        btn.setAttribute('aria-expanded','false');
        nav.setAttribute('aria-hidden','true');
    });

    // escape closes
    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape' && header.classList.contains('nav-open')) {
            header.classList.remove('nav-open');
            btn.setAttribute('aria-expanded','false');
            nav.setAttribute('aria-hidden','true');
        }
    });

    // on resize to desktop ensure nav visible and closed mobile menu
    window.addEventListener('resize', () => {
        if (window.innerWidth > 700) {
            header.classList.remove('nav-open');
            nav.setAttribute('aria-hidden','false');
            btn.setAttribute('aria-expanded','false');
        } else {
            // on small screens keep nav hidden by default
            if (!header.classList.contains('nav-open')) {
                nav.setAttribute('aria-hidden','true');
            }
        }
    });
}

// expose functions for AJAX callback if needed
window.changeBackgroundOnScroll = changeBackgroundOnScroll;
window.initHeaderToggle = initHeaderToggle;

// auto-init if header already present
document.addEventListener('DOMContentLoaded', function(){
    initHeaderToggle();
    changeBackgroundOnScroll();
});