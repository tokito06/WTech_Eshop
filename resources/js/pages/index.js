export default function initIndexPage() {
    const bannerScroll = document.getElementById('home-banner-scroll');

    if (bannerScroll) {
        const banners = bannerScroll.querySelectorAll('.home-banner-card');
        let bannerIndex = 0;
        let bannerTimer = null;

        const goToBanner = index => {
            bannerScroll.scrollTo({
                left: index * bannerScroll.clientWidth,
                behavior: 'smooth',
            });
        };
        const stopAuto = () => {
            if (bannerTimer) {
                clearInterval(bannerTimer);
                bannerTimer = null;
            }
        };
        const startAuto = () => {
            if (banners.length < 2) {
                return;
            }
            stopAuto();
            bannerTimer = setInterval(() => {
                bannerIndex = (bannerIndex + 1) % banners.length;
                goToBanner(bannerIndex);
            }, 3600);
        };

        setTimeout(startAuto, 1500);
        bannerScroll.addEventListener('scroll', () => {
            const page = Math.round(bannerScroll.scrollLeft / Math.max(1, bannerScroll.clientWidth));
            bannerIndex = Math.max(0, Math.min(banners.length - 1, page));
        });
        bannerScroll.addEventListener('pointerdown', stopAuto);
        bannerScroll.addEventListener('mouseenter', stopAuto);
        bannerScroll.addEventListener('mouseleave', () => setTimeout(startAuto, 1200));
        window.addEventListener('resize', () => goToBanner(bannerIndex));
    }

    const catScroll = document.getElementById('cat-scroll');
    const dots = document.querySelectorAll('.cat-dot');
    const prevBtn = document.getElementById('cat-prev');
    const nextBtn = document.getElementById('cat-next');
    const dotsWrap = document.querySelector('.cat-dots');

    if (!catScroll || !prevBtn || !nextBtn || dots.length === 0) {
        return;
    }

    function positionArrows() {
        const top = catScroll.offsetTop;
        const center = top + catScroll.offsetHeight / 2 - prevBtn.offsetHeight / 2;
        prevBtn.style.top = center + 'px';
        nextBtn.style.top = center + 'px';
    }

    function updateScrollState() {
        const canScroll = catScroll.scrollWidth > catScroll.clientWidth + 2;
        catScroll.classList.toggle('no-scroll', !canScroll);
        prevBtn.classList.toggle('hidden', !canScroll || catScroll.scrollLeft <= 0);
        nextBtn.classList.toggle(
            'hidden',
            !canScroll || catScroll.scrollLeft >= catScroll.scrollWidth - catScroll.clientWidth - 2
        );
        if (dotsWrap) {
            dotsWrap.style.visibility = canScroll ? 'visible' : 'hidden';
        }
    }

    prevBtn.addEventListener('click', () => {
        catScroll.scrollBy({ left: -catScroll.offsetWidth * 0.8, behavior: 'smooth' });
    });
    nextBtn.addEventListener('click', () => {
        catScroll.scrollBy({ left: catScroll.offsetWidth * 0.8, behavior: 'smooth' });
    });

    catScroll.addEventListener('scroll', () => {
        const max = catScroll.scrollWidth - catScroll.clientWidth;
        const ratio = max > 0 ? catScroll.scrollLeft / max : 0;
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === (ratio < 0.5 ? 0 : 1));
        });
        updateScrollState();
    });

    dots.forEach(dot => {
        dot.addEventListener('click', () => {
            catScroll.scrollTo({
                left: parseInt(dot.dataset.page, 10) * (catScroll.scrollWidth - catScroll.clientWidth),
                behavior: 'smooth',
            });
        });
    });

    positionArrows();
    updateScrollState();
    window.addEventListener('resize', () => {
        positionArrows();
        updateScrollState();
    });
}
