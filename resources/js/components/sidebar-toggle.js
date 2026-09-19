const sidebar = document.querySelector('.sidebar');
const toggle = document.querySelector('.sidebar__toggle');

if (sidebar && toggle) {
    // Sans préférence mémorisée, on suit le CSS : rail sous lg, étendue au-delà.
    const isLarge = matchMedia('(min-width: 1024px)'); // = $breakpoint-lg
    const stored = localStorage.getItem('sidebar-collapsed');
    let isCollapsed = stored === null ? !isLarge.matches : stored === 'true';

    const render = () => {
        sidebar.classList.toggle('sidebar--collapsed', isCollapsed);
        sidebar.classList.toggle('sidebar--expanded', !isCollapsed);
        toggle.setAttribute('aria-expanded', !isCollapsed);
        toggle.setAttribute('aria-label', isCollapsed ? 'Déplier le menu' : 'Réduire le menu');
    };

    render();

    toggle.addEventListener('click', () => {
        isCollapsed = !isCollapsed;
        localStorage.setItem('sidebar-collapsed', isCollapsed);
        render();
    });
}
