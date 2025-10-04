// user/js/nav.js
document.addEventListener('DOMContentLoaded', () => {
    // 0) Stop anchors with href="#" from changing the URL hash on dropdown toggles
    document.querySelectorAll(
        'a.nav-link.dropdown-toggle[href="#"], a.dropdown-item.dropdown-toggle[href="#"]'
    ).forEach(el => {
        el.addEventListener('click', (e) => {
            // Let Bootstrap handle the toggle, but don't navigate to "#"
            e.preventDefault();
        });
    });

    // 1) Initialize all (top-level) Bootstrap dropdowns
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
        try { new bootstrap.Dropdown(el); } catch (e) {}
    });

    // 2) Handle nested submenus (Bootstrap 5 doesn't support them out of the box)
    document.querySelectorAll('.dropdown-submenu > .dropdown-toggle').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault(); // don’t navigate (no "#")
            e.stopPropagation(); // keep the parent menu open

            const submenu = this.nextElementSibling;
            if (!submenu) return;

            // Close any open sibling submenus
            const parentMenu = this.closest('.dropdown-menu');
            if (parentMenu) {
                parentMenu.querySelectorAll('.dropdown-menu.show').forEach(m => {
                    if (m !== submenu) m.classList.remove('show');
                });
            }

            submenu.classList.toggle('show');
        });
    });

    // 3) When a top-level dropdown hides, close all its submenus
    document.querySelectorAll('.dropdown').forEach(dd => {
        dd.addEventListener('hide.bs.dropdown', () => {
            dd.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
        });
    });

    // 4) (Optional) tiny slider for Login/Signup panel
    const slider = document.querySelector('.form-slider');
    const showSignup = document.getElementById('showSignup');
    const showLogin = document.getElementById('showLogin');
    if (slider && showSignup && showLogin) {
        showSignup.addEventListener('click', (e) => { e.preventDefault();
            slider.style.transform = 'translateX(-50%)'; });
        showLogin.addEventListener('click', (e) => { e.preventDefault();
            slider.style.transform = 'translateX(0%)'; });
    }
});