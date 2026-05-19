function toggleNavbar() {
    const navbar = document.getElementById('navbar');
    const navbarItems = document.querySelectorAll('.navbarItem');
    const content = document.getElementById('content');

    navbar.classList.toggle('expand');
    navbarItems.forEach(item => item.classList.toggle('expand'));
    if (content) {
        content.classList.toggle('expand');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    [
        'admin_firstName',
        'admin_lastName',
        'admin_email',
        'admin_phone',
        'admin_dobDay',
        'admin_dobMonth',
        'admin_dobYear',
        'admin_securityQuestion',
        'admin_securityAnswer'
    ].forEach(function(cookieName) {
        document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
    });
});
