function changeBackgroundOnScroll(){
    window.addEventListener('scroll', function(){
        var header = document.getElementById('header-container');
        if (!header) return;
        if ((window.scrollY !== 0) && (window.innerWidth > 700)){
            header.style.backgroundColor = '#3B3838';
        } else if (window.scrollY === 0 && window.innerWidth > 700) {
            header.style.backgroundColor = 'rgba(0,0,0,0)';
        }
    });
}

function initHeaderToggle(){
    const header = document.getElementById('header-container');
    const nav = document.getElementById('header-top-nav');
    
    if (!header || !nav) return;

    if (!nav.hasAttribute('aria-hidden')) nav.setAttribute('aria-hidden','true');

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

    btn.addEventListener('click', function(e){
        e.stopPropagation();
        const opened = header.classList.toggle('nav-open');
        btn.setAttribute('aria-expanded', opened ? 'true' : 'false');
        nav.setAttribute('aria-hidden', opened ? 'false' : 'true');
    });

    btn.addEventListener('touchend', function(e){
        e.preventDefault();
        e.stopPropagation();
        const opened = header.classList.toggle('nav-open');
        btn.setAttribute('aria-expanded', opened ? 'true' : 'false');
        nav.setAttribute('aria-hidden', opened ? 'false' : 'true');
    }, { passive: false });

    header.addEventListener('touchend', function(e){
        const hb = e.target.closest && e.target.closest('.hamburger');
        if (!hb) return;
        e.preventDefault();
        e.stopPropagation();
        const opened = header.classList.toggle('nav-open');
        hb.setAttribute('aria-expanded', opened ? 'true' : 'false');
        nav.setAttribute('aria-hidden', opened ? 'false' : 'true');
    }, { passive: false, capture: true });

    header.addEventListener('click', function(e){
        const hb = e.target.closest && e.target.closest('.hamburger');
        if (!hb) return;
        e.stopPropagation();
        const opened = header.classList.toggle('nav-open');
        hb.setAttribute('aria-expanded', opened ? 'true' : 'false');
        nav.setAttribute('aria-hidden', opened ? 'false' : 'true');
    }, true);

    nav.addEventListener('click', function(e){
        e.stopPropagation();
    });

    document.addEventListener('click', function(){
        if (!header.classList.contains('nav-open')) return;
        header.classList.remove('nav-open');
        btn.setAttribute('aria-expanded','false');
        nav.setAttribute('aria-hidden','true');
    });

    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape' && header.classList.contains('nav-open')) {
            header.classList.remove('nav-open');
            btn.setAttribute('aria-expanded','false');
            nav.setAttribute('aria-hidden','true');
        }
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 700) {
            header.classList.remove('nav-open');
            nav.setAttribute('aria-hidden','false');
            btn.setAttribute('aria-expanded','false');
        } else {
            if (!header.classList.contains('nav-open')) {
                nav.setAttribute('aria-hidden','true');
            }
        }
    });
}

window.changeBackgroundOnScroll = changeBackgroundOnScroll;
window.initHeaderToggle = initHeaderToggle;