function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');
    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) content.classList.toggle('expand');
}

// Scroll-controlled video
const video = document.getElementById('scrollVideo');
if (video) {
    video.addEventListener('loadedmetadata', () => {
        window.addEventListener('scroll', () => {
            const videoRect = video.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            const visibleRatio = Math.max(0, Math.min(1, (windowHeight - videoRect.top) / (windowHeight + videoRect.height)));
            if (video.duration) video.currentTime = visibleRatio * video.duration;
        });
    });
}

// Feature button toggle
document.querySelectorAll('.feature-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.feature-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Scroll-based photo animation
document.addEventListener('DOMContentLoaded', function() {
    const photoCards = document.querySelectorAll('.scroll-animate');
    
    // Use Intersection Observer for efficient scroll detection
    const observerOptions = {
        threshold: 0.3,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add visible class when element comes into view
                entry.target.classList.add('visible');
                // Optional: unobserve to stop watching after animation
                // observer.unobserve(entry.target);
            } else {
                // Remove visible class when element leaves view (for re-animation on scroll up)
                entry.target.classList.remove('visible');
            }
        });
    }, observerOptions);

    photoCards.forEach(card => {
        observer.observe(card);
    });
});