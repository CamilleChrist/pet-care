<x-layouts.admin :title="'Modifier '.$user->name"
                 description="Nom, adresse e-mail et rôle"
                 :back="route('admin.users.show', $user)">

    <x-ui.card>
        <form method="post" action="{{ route('admin.users.update', $user) }}" class="form">
            @csrf
            @method('PATCH')

            <div class="form__row">
                <x-form.input name="name" label="Nom" icon="user" :value="$user->name" required/>
                <x-form.input name="email" label="E-mail" type="email" icon="mail" :value="$user->email" required/>
            </div>

            <x-form.select name="role" label="Rôle" icon="lock" :value="$user->role->value"
                           :options="collect($roles)->mapWithKeys(fn ($role) => [$role->value => $role->label()])->all()"
                           hint="Un administrateur accède à ce back-office." required/>

            <div class="form__actions">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn--ghost">Annuler</a>
                <button type="submit" class="btn btn--primary">Enregistrer</button>
            </div>
        </form>
    </x-ui.card>

</x-layouts.admin>
