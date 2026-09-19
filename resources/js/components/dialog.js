// <x-dialog> : [data-dialog-open="id"] ouvre, [data-dialog-close] ferme, un clic sur le fond ferme.
document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-dialog-open]');
    if (opener) {
        document.getElementById(opener.dataset.dialogOpen)?.showModal();
        return;
    }

    if (event.target.closest('[data-dialog-close]')) {
        event.target.closest('dialog').close();
        return;
    }

    // Le clic sur ::backdrop a le <dialog> pour cible : on ferme seulement s'il tombe hors de la boîte.
    if (event.target.matches('dialog.dialog')) {
        const box = event.target.getBoundingClientRect();
        const outside = event.clientX < box.left || event.clientX > box.right
            || event.clientY < box.top || event.clientY > box.bottom;

        if (outside) {
            event.target.close();
        }
    }
});
