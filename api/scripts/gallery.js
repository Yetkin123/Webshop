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
    slidesWrap.addEventListener('touchstart', e => startX = e.touches[0].clientX, { passive: true });
    slidesWrap.addEventListener('touchend', e => {
        if (startX === null) return;
        const dx = e.changedTouches[0].clientX - startX;
        startX = null;
        if (Math.abs(dx) > 40) { dx < 0 ? next() : prev(); resetInterval(); }
    });

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