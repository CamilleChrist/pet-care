// Switch « Sur cet appareil » du profil : abonne ce navigateur aux notifications push, ou le désabonne.
// Son état est celui de l'appareil, pas une préférence du compte : un abonnement push est propre à un navigateur.
const toggle = document.querySelector('[data-push-url]');
const statusText = document.querySelector('[data-push-status]');

if (toggle) {
    init().catch((error) => {
        console.error(error);
        disable('Les notifications ne sont pas disponibles pour le moment.');
    });
}

async function init() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        return disable("Les notifications ne sont pas disponibles sur ce navigateur. Sur iPhone : Partager → « Sur l'écran d'accueil », puis ouvrez PetCare depuis l'icône.");
    }

    if (Notification.permission === 'denied') {
        return disable('Les notifications sont bloquées : autorisez-les pour ce site dans les réglages du navigateur.');
    }

    const registration = await navigator.serviceWorker.register('/sw.js');
    const subscription = await registration.pushManager.getSubscription();
    toggle.checked = Boolean(subscription);

    if (subscription) {
        // Réenregistré à chaque visite : sur un appareil partagé, l'abonnement passe au compte connecté.
        save(subscription).catch(console.error);
    }

    toggle.addEventListener('change', async () => {
        toggle.disabled = true;

        try {
            say(toggle.checked ? await subscribe() : await unsubscribe());
        } catch (error) {
            console.error(error);
            toggle.checked = !toggle.checked;
            say("Le changement n'a pas pu être appliqué, réessayez.");
        } finally {
            toggle.disabled = Notification.permission === 'denied';
        }
    });
}

async function subscribe() {
    // En premier : Safari n'accepte la demande que pendant le clic.
    if (await Notification.requestPermission() !== 'granted') {
        toggle.checked = false;
        return 'Notifications refusées : autorisez-les pour ce site dans les réglages du navigateur.';
    }

    const registration = await navigator.serviceWorker.ready;
    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(
            document.querySelector('meta[name="vapid-public-key"]').content
        ),
    });

    try {
        await save(subscription);
    } catch (error) {
        // Sinon le navigateur se croirait abonné alors que le serveur n'a rien enregistré.
        await subscription.unsubscribe();
        throw error;
    }

    return 'Notifications activées sur cet appareil.';
}

async function unsubscribe() {
    const registration = await navigator.serviceWorker.ready;
    await (await registration.pushManager.getSubscription())?.unsubscribe();

    // ponytail: la ligne push_subscriptions reste en base ; le package la supprime au prochain envoi
    // (réponse 410 du service push). Ajouter une route DELETE si elle doit partir tout de suite.
    return 'Notifications désactivées sur cet appareil.';
}

async function save(subscription) {
    const { endpoint, keys: { p256dh, auth } } = subscription.toJSON();

    const response = await fetch(toggle.dataset.pushUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            // Sans lui, une erreur de validation ou une session expirée redirige, et fetch suit jusqu'à un 200.
            Accept: 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({
            endpoint,
            key: p256dh,
            token: auth,
            encoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0],
        }),
    });

    if (!response.ok) {
        throw new Error(`Abonnement refusé (${response.status})`);
    }
}

function disable(message) {
    toggle.disabled = true;
    say(message);
}

function say(message) {
    statusText.textContent = message;
}

// VAPID keys are Base64URL-encoded; atob() requires standard Base64, so we convert first
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return Uint8Array.from([...rawData].map((c) => c.charCodeAt(0)));
}
