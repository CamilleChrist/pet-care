@php($user = auth()->user())

<x-layouts.app title="Profil" description="Compte et connexion">

    <x-ui.card :title="$user->name"
               :description="$user->email.' · compte créé en '.$user->created_at->isoFormat('MMMM YYYY')">
        <form method="post" action="{{ route('profile.update') }}" class="form">
            @csrf
            @method('PATCH')

            <div class="form__row">
                <x-form.input name="name" label="Nom" icon="user" :value="$user->name" required />
                <x-form.input name="email" label="E-mail" type="email" icon="mail" :value="$user->email" required />
            </div>

            <div class="form__actions">
                <a href="{{ route('profile.password.edit') }}" class="btn btn--tertiary">
                    <x-ui.icon name="lock" class="btn__icon"/>
                    Changer de mot de passe
                </a>
                <button type="submit" class="btn btn--primary">Enregistrer</button>
            </div>
        </form>
    </x-ui.card>

</x-layouts.app>
