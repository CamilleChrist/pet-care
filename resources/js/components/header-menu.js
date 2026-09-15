const burger = document.querySelector('.header__burger');
const nav = document.querySelector('#header-nav');

if (burger && nav) {
    const setOpen = (isOpen) => {
        nav.classList.toggle('header-nav--open', isOpen);
        burger.classList.toggle('header__burger--open', isOpen);
        burger.setAttribute('aria-expanded', isOpen);
        burger.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
    };

    burger.addEventListener('click', () => {
        setOpen(!nav.classList.contains('header-nav--open'));
    });

    // Ferme le menu au clic sur un lien ou en dehors du menu.
    document.addEventListener('click', (event) => {
        if (nav.classList.contains('header-nav--open')
            && !nav.contains(event.target)
            && !burger.contains(event.target)) {
            setOpen(false);
        }
    });

    nav.addEventListener('click', (event) => {
        if (event.target.tagName === 'A') {
            setOpen(false);
        }
    });
}
