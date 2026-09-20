<x-layouts.auth
    :step="1"
    title="Mot de passe oublié"
    headline="Reprenez la main sur votre compte."
    description="Le lien reçu par e-mail est valable 60 minutes. Les fiches de vos animaux restent intactes."
>

    <x-ui.card tagTitle="h1" title="Mot de passe oublié" description="Indiquez votre e-mail : vous recevrez un lien pour choisir un nouveau mot de passe.">

        @if (session('status'))
            <x-ui.alert tone="success">{{ session('status') }}</x-ui.alert>
        @endif

        <form method="post" action="{{ route('password.email') }}" class="form">
            @csrf
            <x-form.input name="email" label="E-mail" type="email" icon="mail" placeholder="camille@example.fr" required />

            <button type="submit" class="btn--primary btn--block">
                <x-ui.icon name="mail" class="btn__icon" />
                Envoyer le lien
            </button>
        </form>

        <x-ui.alert tone="info">Rien reçu ? Vérifiez les indésirables avant de redemander un lien.</x-ui.alert>

        <a class="auth__link" href="{{ route('login') }}">Revenir à la connexion</a>

    </x-ui.card>

</x-layouts.auth>
