<x-layouts.app title="Changer de mot de passe"
               description="Le mot de passe actuel est demandé pour confirmer que c'est bien vous"
               :back="route('profile.edit')">

    <form method="post" action="{{ route('profile.password.update') }}" class="form">
        @csrf
        @method('PATCH')

        <x-ui.card>
            <div class="form__body">
                <x-form.input name="current_password" label="Mot de passe actuel" type="password" icon="lock" required/>

                <div class="form__row">
                    <x-form.input name="password"
                                  label="Nouveau mot de passe"
                                  type="password"
                                  icon="lock"
                                  hint="6 caractères minimum."
                                  required/>

                    <x-form.input name="password_confirmation"
                                  label="Confirmer le mot de passe"
                                  type="password"
                                  icon="lock"
                                  hint="Saisissez-le une seconde fois."
                                  required/>
                </div>

                <div class="form__actions">
                    <a href="{{ route('profile.edit') }}" class="btn btn--ghost">Annuler</a>
                    <button type="submit" class="btn btn--primary">Enregistrer</button>
                </div>
            </div>
        </x-ui.card>
    </form>

</x-layouts.app>
