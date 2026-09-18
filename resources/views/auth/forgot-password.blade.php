<x-layouts.auth
    :step="1"
    title="Mot de passe oublié"
    headline="Reprenez la main sur votre compte."
    description="Le lien reçu par e-mail est valable 60 minutes. Les fiches de vos animaux restent intactes."
>

    <x-card tagTitle="h1" title="Mot de passe oublié" description="Indiquez votre e-mail : vous recevrez un lien pour choisir un nouveau mot de passe.">

        @if (session('status'))
            <x-alert tone="success">{{ session('status') }}</x-alert>
        @endif

        <form method="post" action="{{ route('password.email') }}" class="form">
            @csrf
            <x-form.input name="email" label="E-mail" type="email" icon="mail" placeholder="camille@example.fr" required />

            <button type="submit" class="btn--primary btn--block">
                <x-icon name="mail" class="btn__icon" />
                Envoyer le lien
            </button>
        </form>

        <x-alert tone="info">Rien reçu ? Vérifiez les indésirables avant de redemander un lien.</x-alert>

        <a class="auth__link" href="{{ route('login') }}">Revenir à la connexion</a>

    </x-card>

</x-layouts.auth>
