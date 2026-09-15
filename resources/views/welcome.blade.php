<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PetCare - Application suivi de santé pour chiens et chats</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/landing.scss', 'resources/js/app.js'])
</head>
<body>

<!-- Header -->
<header class="header">
    <div class="container">
        <h1 class="header__logo"><a href="{{ route('welcome') }}"><span>P</span> PetCare</a></h1>

        <button type="button" class="header__burger" aria-expanded="false" aria-controls="header-nav"
                aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="header-nav" id="header-nav">
            <ul class="header-nav-list">
                <li class="header-nav-list__item">
                    <a href="{{ route('welcome') }}">Accueil</a>
                </li>
                <li class="header-nav-list__item">
                    <a href="#how-it-works">Comment ça marche</a>
                </li>
                <li class="header-nav-list__item">
                    <a href="#features">Fonctionnalités</a>
                </li>
                <li class="header-nav-list__item hidden-md-up">
                    <a href="#features">Se connecter</a>
                </li>
            </ul>
        </nav>

        <a href="{{ route('register') }}" class="btn--primary">S'inscrire</a>
        <a href="{{ route('login') }}" class="link hidden-md-down">Se connecter</a>
    </div>
</header>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero__content">
                <p class="hero__subtitle">Carnet de santé chien & chat</p>
                <h2 class="hero__title">Tu n'oublieras plus jamais un rappel de vaccin.</h2>
                <p class="hero__description">
                    PetCare garde le carnet de santé de tes animaux à jour : vaccins, pesées, notes et visites chez le
                    vétérinaire. La prochaine échéance s'affiche dès que tu ouvres ton tableau de bord.
                </p>

                <div class="hero__buttons">
                    <a href="{{ route('register') }}" class="btn--primary">Commencer - c'est gratuit</a>
                    <a href="#how-it-works" class="btn--tertiary">Voir comment ça marche</a>
                </div>
                <p class="hero__tagline">Sans pub. Autant d'animaux que tu veux dans un seul compte.</p>
            </div>

            <img class="hero__visual" src="https://placehold.co/560x420?text=Aper%C3%A7u+du+tableau+de+bord"
                 alt="Aperçu du tableau de bord PetCare" width="560" height="420" loading="lazy">
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works">
        <div class="container">
            <p class="how-it-works__subtitle">Comment ça marche ?</p>
            <h2 class="how-it-works__title">
                Trois minutes pour créer le carnet. Puis tu n'y touches qu'à chaque nouvelle info.
            </h2>
            <div class="how-it-works__steps">
                <div class="step">
                    <p class="step__number">1</p>
                    <h3 class="step__title">Tu crées la fiche</h3>
                    <p class="step__description">
                        Nom, espèce, race, date de naissance, sexe, photo. Un animal, une fiche — et autant de fiches
                        que de pensionnaires.
                    </p>
                </div>
                <div class="step">
                    <p class="step__number">2</p>
                    <h3 class="step__title">Tu notes au fil de l'eau</h3>
                    <p class="step__description">
                        Une pesée après le bain, un vaccin au retour du véto, une note quand quelque chose te semble
                        bizarre. Tout s'empile dans l'historique.
                    </p>
                </div>
                <div class="step">
                    <p class="step__number">3</p>
                    <h3 class="step__title">Tu vois ce qui arrive</h3>
                    <p class="step__description">
                        Le tableau de bord affiche le poids actuel et le prochain rappel de chaque animal. Les échéances
                        dépassées se repèrent d'un coup d'œil.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="container">
            <p class="features__subtitle">Fonctionalités</p>
            <h2 class="features__title">Tout le suivi santé, à un seul endroit.</h2>
            <p class="features__text">Poids, vaccins, visites et notes du quotidien — pour chaque animal du
                foyer.</p>
            <div class="features__list">
                <div class="feature">
                    <p class="feature__icon">
                        <x-icon name="file"/>
                    </p>
                    <h3 class="feature__title">Fiche</h3>
                    <p class="feature__description">Une fiche par animal : nom, espèce, race, date de naissance,
                        sexe et photo. Chien ou chat, autant de fiches que tu veux.</p>
                </div>
                <div class="feature">
                    <p class="feature__icon">
                        <x-icon name="scale"/>
                    </p>
                    <h3 class="feature__title">Historique de pesées</h3>
                    <p class="feature__description">
                        Chaque pesée est datée. Le poids affiché sur la fiche est toujours le plus récent, jamais une
                        valeur oubliée.
                    </p>
                </div>
                <div class="feature">
                    <p class="feature__icon">
                        <x-icon name="syringe"/>
                    </p>
                    <h3 class="feature__title">Vaccins et rappels</h3>
                    <p class="feature__description">Date d'administration, date du prochain rappel. Les échéances
                        proches et dépassées se distinguent.
                    </p>
                </div>
                <div class="feature">
                    <p class="feature__icon">
                        <x-icon name="heart-pulse"/>
                    </p>
                    <h3 class="feature__title">Notes et visites vétérinaires</h3>
                    <p class="feature__description">Les observations du quotidien et la date de la dernière
                        consultation, au même endroit que le reste.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta__watermark" aria-hidden="true">
            <x-icon name="paw-print" class="cta__paw cta__paw--top"/>
            <x-icon name="paw-print" class="cta__paw cta__paw--bottom"/>
        </div>

        <div class="container">
            <div class="panel">
                <div class="panel__content">
                    <p class="panel__subtitle">Inscription</p>
                    <h2 class="panel__title">
                        Le carnet de santé de tes animaux, à jour pour de bon.
                    </h2>
                    <p class="panel__description">
                        Trois minutes suffisent. Ensuite, tu ne notes que ce qui change.
                    </p>
                    <a href="{{ route('register') }}" class="btn--primary">
                        Commencer - C'est gratuit
                    </a>
                </div>

                <img class="panel__image" src="https://placehold.co/560x420?text=Apercu" alt="Aperçu de PetCare"
                     loading="lazy">
            </div>
        </div>
    </section>
</main>

<footer class="footer">
    <div class="container">
        <div class="footer__row">
            <div class="footer__col">
                <p class="footer__logo"><span>P</span> PetCare</p>
                <p class="footer__description">Le suivi de santé de tes chiens et chats : vaccins, poids, notes, visites véto.</p>
            </div>

            <div class="footer__col">
                <ul class="list">
                    <li class="list__item list__item--heading">Produit</li>
                    <li class="list__item">Comment ça marche</li>
                    <li class="list__item">Fonctionalités</li>
                </ul>
            </div>
            <div class="footer__col">
                <ul class="list">
                    <li class="list__item list__item--heading">Compte</li>
                    <li class="list__item">Crée un compte</li>
                    <li class="list__item">Se connecter</li>
                    <li class="list__item">Mot de passe oublié</li>
                </ul>
            </div>
        </div>

        <div class="footer-legales">
            <ul class="footer-legales__list">
                <li>© 2026 PetCare</li>
                <li><a href="">Mentions légales</a></li>
                <li><a href="">CGU</a></li>
                <li><a href="">Politique de confidentialité</a></li>
                <li><a href="">Cookies</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
