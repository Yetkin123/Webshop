document.addEventListener('DOMContentLoaded', () => {
    const gallery = document.querySelector('.hero-gallery');
    if (!gallery) { window.__heroGalleryLoaded = false; return; }

    const slidesWrap = gallery.querySelector('.slides');
    const slides = Array.from(gallery.querySelectorAll('.slide'));
    const prevBtn = gallery.querySelector('.gallery-prev');
    const nextBtn = gallery.querySelector('.gallery-next');
    const dots = Array.from(gallery.querySelectorAll('.dot'));

    if (!slidesWrap || slides.length === 0) { window.__heroGalleryLoaded = false; return; }

    let index = 0;
    const total = slides.length;
    let interval = null;
    const delay = 5000;

    function getContainerWidth() {
        const w = Math.round(gallery.getBoundingClientRect().width);
        if (w && w > 0) return w;
        // fallback: use first slide width if available
        const s0 = slides[0];
        const s0w = s0 ? Math.round(s0.getBoundingClientRect().width) : 0;
        if (s0w && s0w > 0) return s0w;
        // final fallback
        return window.innerWidth || 1024;
    }

    function layout() {
        const containerWidth = getContainerWidth();
        slides.forEach(s => {
            s.style.minWidth = `${containerWidth}px`;
            s.style.width = `${containerWidth}px`;
            s.style.flex = '0 0 auto';
        });
        slidesWrap.style.width = `${containerWidth * total}px`;
        slidesWrap.style.transition = 'transform 0.7s ease';
        slidesWrap.style.transform = `translateX(-${index * containerWidth}px)`;
    }

    function goTo(i) {
        index = ((i % total) + total) % total;
        const containerWidth = getContainerWidth();
        slidesWrap.style.transform = `translateX(-${index * containerWidth}px)`;
        if (dots.length) dots.forEach((d, idx) => d.classList.toggle('active', idx === index));
    }

    if (nextBtn) nextBtn.addEventListener('click', () => { goTo(index + 1); resetInterval(); });
    if (prevBtn) prevBtn.addEventListener('click', () => { goTo(index - 1); resetInterval(); });
    dots.forEach((dot, idx) => dot.addEventListener('click', () => { goTo(idx); resetInterval(); }));

    function next() { goTo(index + 1); }
    function prev() { goTo(index - 1); }

    function startInterval() { interval = setInterval(next, delay); }
    function resetInterval() { clearInterval(interval); startInterval(); }

    let startX = null;
    let startY = null;
    let isHorizontal = false;

    slidesWrap.addEventListener('touchstart', function(e) {
        if (!e.touches || e.touches.length === 0) return;
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        isHorizontal = false;
    }, { passive: true });

    slidesWrap.addEventListener('touchmove', function(e) {
        if (startX === null || startY === null) return;
        const dx = e.touches[0].clientX - startX;
        const dy = e.touches[0].clientY - startY;
        // horizontale beweging groter dan verticale -> behandel als swipe en voorkom page-scroll
        if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 6) {
            isHorizontal = true;
            e.preventDefault(); // vereist non-passive listener om in Safari te werken
        }
    }, { passive: false });

    slidesWrap.addEventListener('touchend', function(e) {
        if (startX === null) return;
        const endX = (e.changedTouches && e.changedTouches[0]) ? e.changedTouches[0].clientX : null;
        const dx = endX !== null ? endX - startX : 0;
        startX = startY = null;
        if (isHorizontal && Math.abs(dx) > 40) {
            dx < 0 ? next() : prev();
            resetInterval();
        }
        isHorizontal = false;
    }, { passive: true });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') { prev(); resetInterval(); }
        if (e.key === 'ArrowRight') { next(); resetInterval(); }
    });

    window.addEventListener('resize', () => layout());

    // init
    layout();
    goTo(0);
    startInterval();
    window.__heroGalleryLoaded = true;
});