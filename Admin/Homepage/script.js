function toggleNavbar() {}

document.addEventListener('DOMContentLoaded', function() {
    const profileMenu = document.querySelector('.profile-menu');
    const profileTrigger = document.querySelector('.profile-trigger');
    const profileDropdown = document.querySelector('.profile-dropdown');

    if (profileMenu && profileTrigger && profileDropdown) {
        profileTrigger.addEventListener('click', () => {
            const isOpen = profileMenu.classList.toggle('open');
            profileTrigger.setAttribute('aria-expanded', String(isOpen));
            profileDropdown.hidden = !isOpen;
        });

        document.addEventListener('click', (event) => {
            if (!profileMenu.contains(event.target)) {
                profileMenu.classList.remove('open');
                profileTrigger.setAttribute('aria-expanded', 'false');
                profileDropdown.hidden = true;
            }
        });
    }

    const observerOptions = {
        threshold: 0.3,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            } else {
                entry.target.classList.remove('visible');
            }
        });
    }, observerOptions);

    const scrollElements = document.querySelectorAll('.scroll-animate, .scroll-step, .fade-in-element');
    scrollElements.forEach(el => observer.observe(el));

    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    let lastScrollY = 0;
    let scrollVelocity = 0;

    window.addEventListener('scroll', function() {
        const scrollY = window.scrollY;
        scrollVelocity = scrollY - lastScrollY;
        lastScrollY = scrollY;

        if (heroTitle) {
            heroTitle.style.transform = `translateY(${scrollY * 0.3}px) scale(${1 - scrollVelocity * 0.0001})`;
            heroTitle.style.opacity = Math.max(0, 1 - scrollY / 600);
        }

        if (heroSubtitle) {
            heroSubtitle.style.transform = `translateY(${scrollY * 0.4}px)`;
            heroSubtitle.style.opacity = Math.max(0, 1 - scrollY / 800);
        }
    });

    const animateCounter = (el) => {
        const target = parseInt(el.textContent.replace(/\D/g, ''), 10);
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                el.textContent = el.textContent.replace(/\d+/, target);
                clearInterval(timer);
            } else {
                el.textContent = el.textContent.replace(/\d+/, Math.floor(current));
            }
        }, 16);
    };

    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(el => {
                    if (!el.hasAttribute('data-animated')) {
                        animateCounter(el);
                        el.setAttribute('data-animated', 'true');
                    }
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) statsObserver.observe(statsSection);

    const heroContent = document.querySelector('.hero-content');
    document.addEventListener('mousemove', (e) => {
        if (window.scrollY < window.innerHeight) {
            const x = (e.clientX / window.innerWidth) * 20 - 10;
            const y = (e.clientY / window.innerHeight) * 20 - 10;

            if (heroContent) {
                heroContent.style.transform = `perspective(1200px) rotateX(${y * 0.05}deg) rotateY(${x * 0.05}deg)`;
            }
        }
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowDown') {
            window.scrollBy({ top: 200, behavior: 'smooth' });
        } else if (e.key === 'ArrowUp') {
            window.scrollBy({ top: -200, behavior: 'smooth' });
        }
    });

    const videoElements = document.querySelectorAll('video');
    videoElements.forEach(video => {
        video.addEventListener('canplay', () => {
            video.style.opacity = '1';
        }, { once: true });
    });

    let scrollTimeout;

    window.addEventListener('scroll', () => {
        document.body.style.scrollBehavior = 'auto';

        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            document.body.style.scrollBehavior = 'smooth';
        }, 150);
    }, { passive: true });
});
