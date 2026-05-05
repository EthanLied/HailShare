function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');
    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) content.classList.toggle('expand');
}

// ============ SCROLL ANIMATIONS & EFFECTS ============
document.addEventListener('DOMContentLoaded', function() {
    
    // ============ INTERSECTION OBSERVER FOR SCROLL ANIMATIONS ============
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

    // Observe all scrollable elements
    const scrollElements = document.querySelectorAll('.scroll-animate, .scroll-step, .fade-in-element, .photo-card');
    scrollElements.forEach(el => observer.observe(el));

    // ============ HOW VIDEO SCROLL SYNC ============
    const howVideo = document.getElementById('howVideo');
    if (howVideo) {
        howVideo.addEventListener('loadedmetadata', () => {
            window.addEventListener('scroll', () => {
                const videoRect = howVideo.getBoundingClientRect();
                const windowHeight = window.innerHeight;
                
                // Calculate visibility ratio
                const visibleRatio = Math.max(0, Math.min(1, 
                    (windowHeight - videoRect.top) / (windowHeight + videoRect.height)
                ));
                
                // Sync video playback to scroll
                if (howVideo.duration) {
                    howVideo.currentTime = visibleRatio * howVideo.duration;
                }
            });
        });
    }

    // ============ PARALLAX SCROLL EFFECT WITH VELOCITY ============
    const heroTitle = document.querySelector('.hero-title');
    const heroSubtitle = document.querySelector('.hero-subtitle');
    const scrollIndicator = document.querySelector('.scroll-indicator');
    let lastScrollY = 0;
    let scrollVelocity = 0;
    
    window.addEventListener('scroll', function() {
        const scrollY = window.scrollY;
        scrollVelocity = scrollY - lastScrollY;
        lastScrollY = scrollY;
        
        // Parallax effect - elements move slower than scroll
        if (heroTitle) {
            heroTitle.style.transform = `translateY(${scrollY * 0.3}px) scale(${1 - scrollVelocity * 0.0001})`;
            heroTitle.style.opacity = Math.max(0, 1 - scrollY / 600);
        }
        
        if (heroSubtitle) {
            heroSubtitle.style.transform = `translateY(${scrollY * 0.4}px)`;
            heroSubtitle.style.opacity = Math.max(0, 1 - scrollY / 800);
        }
        
        if (scrollIndicator) {
            scrollIndicator.style.opacity = Math.max(0, 1 - scrollY / 400);
        }
    });

    // ============ ANIMATED NUMBER COUNTERS FOR STATS ============
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

    // Trigger counter animation when stats section is visible
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

    // ============ MOUSE PARALLAX EFFECT ON HERO ============
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

    // ============ SCROLL VELOCITY BASED BLUR ============
    window.addEventListener('scroll', () => {
        const blur = Math.min(Math.abs(scrollVelocity) * 0.1, 3);
        // Optional: apply blur effect to background
    }, { passive: true });

    // ============ FEATURE BUTTON TOGGLE ============
    document.querySelectorAll('.feature-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.feature-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ============ SMOOTH SCROLL FOR ANCHOR LINKS ============
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

    // ============ KEYBOARD NAVIGATION ============
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowDown') {
            window.scrollBy({ top: 200, behavior: 'smooth' });
        } else if (e.key === 'ArrowUp') {
            window.scrollBy({ top: -200, behavior: 'smooth' });
        }
    });

    // ============ LAZY LOAD DETECTION ============
    const videoElements = document.querySelectorAll('video');
    videoElements.forEach(video => {
        video.addEventListener('canplay', () => {
            video.style.opacity = '1';
        }, { once: true });
    });

    // ============ SCROLL PERFORMANCE OPTIMIZATION ============
    let scrollTimeout;
    let isScrolling = false;
    
    window.addEventListener('scroll', () => {
        isScrolling = true;
        document.body.style.scrollBehavior = 'auto';
        
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            isScrolling = false;
            document.body.style.scrollBehavior = 'smooth';
        }, 150);
    }, { passive: true });

});

