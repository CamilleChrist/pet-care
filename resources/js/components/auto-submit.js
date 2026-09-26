// <form data-auto-submit> : chaque changement de switch envoie le formulaire en AJAX
document.addEventListener('change', async (event) => {
    const form = event.target.closest('form[data-auto-submit]');

    if (!form) {
        return;
    }

    const response = await fetch(form.action, {
        method: 'POST',
        headers: { Accept: 'application/json' },
        body: new FormData(form),
    });

    if (!response.ok) {
        console.error(`Enregistrement refusé (${response.status})`);
        event.target.checked = !event.target.checked;
    }
});
