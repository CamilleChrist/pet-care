@php($user = auth()->user())

<x-layouts.app title="Profil" description="Compte et connexion">

    <div class="two-columns">
        <section>
            <x-ui.card :title="$user->name"
                       :description="$user->email.' · compte créé en '.$user->created_at->isoFormat('MMMM YYYY')">
                <form method="post" action="{{ route('profile.update') }}" class="form">
                    @csrf
                    @method('PATCH')

                    <div class="form__row">
                        <x-form.input name="name" label="Nom" icon="user" :value="$user->name" required/>
                        <x-form.input name="email" label="E-mail" type="email" icon="mail" :value="$user->email"
                                      required/>
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
        </section>

        <aside>
            <x-ui.card title="Notifications"
                       description="Les rappels de vaccin arrivent 7 jours avant l'échéance, puis le jour même.">
                <div class="form">
                    <form method="post" action="{{ route('profile.notifications.update') }}" data-auto-submit>
                        @csrf
                        @method('PATCH')

                        <x-form.switch name="mail_notifications" label="Par e-mail"
                                       :checked="$user->mail_notifications"/>
                    </form>

                    <div class="field">
                        <x-form.switch name="push" label="Sur cet appareil" aria-describedby="push-status"
                                       :data-push-url="route('profile.push-subscription.store')"/>
                        <p id="push-status" class="field__hint" aria-live="polite" data-push-status></p>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card title="Compte"
                       description="La suppression du compte efface les fiches de vos animaux et tout leur historique.">

                <button type="button" class="btn btn--danger" data-dialog-open="delete-account">
                    <x-ui.icon name="trash-2" class="btn__icon"/>
                    Supprimer mon compte
                </button>

                <button type="button" class="btn btn--ghost" data-dialog-open="logout-dialog">
                    <x-ui.icon name="log-out" class="btn__icon"/>
                    Se déconnecter

                </button>
            </x-ui.card>
        </aside>
    </div>

    <x-ui.confirm-delete id="delete-account" :action="route('profile.destroy')"
                         title="Supprimer votre compte ?"
                         description="Votre compte, les fiches de vos animaux, leurs pesées et leurs vaccins seront définitivement supprimés."/>

</x-layouts.app>
