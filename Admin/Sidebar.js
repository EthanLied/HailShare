function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');

    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) content.classList.toggle('expand');
}

// Highlight current page in navbar
document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname;
    const links = document.querySelectorAll('#navbar a');
    links.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href.split('/').pop()) && href !== '#') {
            const item = link.querySelector('.navbarItem');
            if (item) item.style.backgroundColor = '#e0e0e0';
        }
    });
});