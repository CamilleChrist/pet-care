// Bouton « Activer sur cet appareil » du profil : abonne ce navigateur aux notifications push.
const button = document.querySelector('[data-push-url]');
const statusText = document.querySelector('[data-push-status]');

if (button) {
    init().catch((error) => {
        console.error(error);
        done('Les notifications ne sont pas disponibles pour le moment.');
    });
}

async function init() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        return done("Les notifications ne sont pas disponibles sur ce navigateur. Sur iPhone : Partager → « Sur l'écran d'accueil », puis ouvrez PetCare depuis l'icône.");
    }

    if (Notification.permission === 'denied') {
        return done('Les notifications sont bloquées : autorisez-les pour ce site dans les réglages du navigateur.');
    }

    const registration = await navigator.serviceWorker.register('/sw.js');
    const subscription = await registration.pushManager.getSubscription();

    if (subscription) {
        // Réenregistré à chaque visite : sur un appareil partagé, l'abonnement passe au compte connecté.
        save(subscription).catch(console.error);
        return done('Notifications activées sur cet appareil.');
    }

    button.addEventListener('click', subscribe);
}

async function subscribe() {
    button.disabled = true;

    try {
        // En premier : Safari n'accepte la demande que pendant le clic.
        if (await Notification.requestPermission() !== 'granted') {
            button.disabled = Notification.permission === 'denied';
            return say('Notifications refusées : autorisez-les pour ce site dans les réglages du navigateur.');
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

        done('Notifications activées sur cet appareil.');
    } catch (error) {
        console.error(error);
        button.disabled = false;
        say("L'activation a échoué, réessayez.");
    }
}

async function save(subscription) {
    const { endpoint, keys: { p256dh, auth } } = subscription.toJSON();

    const response = await fetch(button.dataset.pushUrl, {
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

function done(message) {
    button.hidden = true;
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
