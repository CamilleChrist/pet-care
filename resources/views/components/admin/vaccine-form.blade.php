@props(['action', 'method' => 'POST', 'vaccine' => null, 'submitLabel' => 'Enregistrer', 'cancel'])

<form method="post" action="{{ $action }}" class="form">
    @csrf
    @method($method)

    <x-ui.card>
        <div class="form__body">
            <div class="form__row">
                <x-form.input name="name" label="Nom" required :value="$vaccine?->name" placeholder="Rage"/>

                <x-form.select name="species" label="Espèce" required :value="$vaccine?->species"
                               :options="['' => '— Choisir —', 'dog' => 'Chien', 'cat' => 'Chat']"/>
            </div>

            <x-form.input name="description" label="Description" :rows="4" :value="$vaccine?->description"
                          placeholder="À quoi sert ce vaccin, rythme des rappels…"/>

            <div class="form__actions">
                <a href="{{ $cancel }}" class="btn btn--ghost">Annuler</a>
                <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </x-ui.card>
</form>
