<x-layouts.auth
    title="Créer un compte"
    description="Trois minutes suffisent. Le premier animal s'ajoute juste après."
>

    <x-ui.card tagTitle="h1" title="Créer un compte" description="Trois minutes suffisent. Le premier animal s'ajoute juste après.">

        <form method="post" action="{{ route('register.store') }}" class="form">
            @csrf
            <x-form.input name="name" label="Nom" icon="user" placeholder="Camille Marchand" required />
            <x-form.input name="email" label="E-mail" type="email" icon="mail" placeholder="camille@example.fr" required />

            <div class="form__row">
                <x-form.input name="password" label="Mot de passe" type="password" icon="lock" hint="6 caractères minimum." required />
                <x-form.input name="password_confirmation" label="Confirmer le mot de passe" type="password" icon="lock" hint="Saisissez-le une seconde fois." required />
            </div>

            <button type="submit" class="btn--primary btn--block">Créer un compte</button>
        </form>

        <p class="auth__link">Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>

    </x-ui.card>

</x-layouts.auth>
