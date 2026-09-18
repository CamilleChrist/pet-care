<x-layouts.auth
    :step="2"
    title="Nouveau mot de passe"
    headline="Reprenez la main sur votre compte."
    description="Le lien reçu par e-mail est valable 60 minutes. Les fiches de vos animaux restent intactes."
>

    <x-card tagTitle="h1" title="Nouveau mot de passe" :description="$email ? 'Pour '.$email.'.' : null">

        {{-- Le jeton et l'e-mail viennent du lien reçu : leurs erreurs (lien expiré, e-mail inconnu) n'ont pas de champ visible --}}
        @error('email')
            <x-alert tone="danger">{{ $message }}</x-alert>
        @enderror

        <form method="post" action="{{ route('password.update') }}" class="form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ old('email', $email) }}">

            <x-form.input name="password" label="Mot de passe" type="password" icon="lock" hint="6 caractères minimum." required />
            <x-form.input name="password_confirmation" label="Confirmer le mot de passe" type="password" icon="lock" hint="Saisissez-le une seconde fois." required />

            <button type="submit" class="btn--primary btn--block">Réinitialiser le mot de passe</button>
        </form>

        <a class="auth__link" href="{{ route('login') }}">Revenir à la connexion</a>

    </x-card>

</x-layouts.auth>
