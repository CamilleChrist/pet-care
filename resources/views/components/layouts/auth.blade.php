@props([
    'title' => '',
    'headline' => 'Vaccins, pesées et visites au même endroit.',
    'description' => "La prochaine échéance s'affiche dès l'ouverture du tableau de bord.",
    'step' => null,
])

<x-layouts.base :title="$title" class="auth">

    <aside>
        <div class="auth-header">
            <x-logo class="logo--dark"/>
            <p class="auth-header__tagline">Carnet de santé chien &amp; chat</p>
        </div>
        <h2 class="auth__headline">{{ $headline }}</h2>
        <p class="auth__description">{{ $description }}</p>

        @if ($step)
            <ol class="auth-steps">
                <li class="auth-steps__item">
                    <span class="auth-steps__number @if ($step === 1) auth-steps__number--active @endif">1</span>
                    <span class="auth-steps__title">Demander le lien</span>
                    <span class="auth-steps__description">Avec l'e-mail du compte.</span>
                </li>
                <li class="auth-steps__item">
                    <span class="auth-steps__number @if ($step === 2) auth-steps__number--active @endif">2</span>
                    <span class="auth-steps__title">Choisir un mot de passe</span>
                    <span class="auth-steps__description">8 caractères minimum.</span>
                </li>
                <li class="auth-steps__item">
                    <span class="auth-steps__number @if ($step === 3) auth-steps__number--active @endif">3</span>
                    <span class="auth-steps__title">Se reconnecter</span>
                    <span class="auth-steps__description">Retour au tableau de bord.</span>
                </li>
            </ol>
            <a class="auth__back" href="{{ route('login') }}">Revenir à la connexion</a>
        @else
            <ul class="auth-features">
                <li class="auth-features__item">
                    <x-icon name="scale" class="auth-features__icon" />
                    <span class="auth-features__title">Suivi du poids</span>
                    <span class="auth-features__description">Chaque pesée datée, la courbe et le poids cible.</span>
                </li>
                <li class="auth-features__item">
                    <x-icon name="syringe" class="auth-features__icon" />
                    <span class="auth-features__title">Vaccins et rappels</span>
                    <span class="auth-features__description">Les échéances dépassées apparaissent en premier.</span>
                </li>
                <li class="auth-features__item">
                    <x-icon name="file" class="auth-features__icon" />
                    <span class="auth-features__title">Une fiche par animal</span>
                    <span class="auth-features__description">Race, date de naissance, notes de santé.</span>
                </li>
            </ul>
        @endif
    </aside>

    <main>
        {{ $slot }}
    </main>

</x-layouts.base>
