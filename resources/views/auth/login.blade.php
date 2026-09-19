<x-layouts.auth title="Se connecter">

    <x-card tagTitle="h1" title="Se connecter" description="Retrouvez le carnet de vos animaux.">

        @if (session('status'))
            <x-alert tone="success">{{ session('status') }}</x-alert>
        @endif

        <form method="post" action="{{ route('login.attempt') }}" class="form">
            @csrf
            <x-form.input name="email" label="E-mail" type="email" icon="mail" placeholder="camille@example.fr" required />
            <x-form.input name="password" label="Mot de passe" type="password" icon="lock" required />

            <x-form.switch name="remember" label="Rester connecté" checked />

            <button type="submit" class="btn--primary btn--block">Se connecter</button>
        </form>

        <a class="auth__link" href="{{ route('password.request') }}">Mot de passe oublié ?</a>

        <p class="auth__divider">ou</p>

        <a class="btn--tertiary btn--block" href="{{ route('register') }}">
            <x-icon name="plus" class="btn__icon" />
            Créer un compte
        </a>

    </x-card>

</x-layouts.auth>
