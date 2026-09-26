@props(['action', 'method' => 'POST', 'breed' => null, 'submitLabel' => 'Enregistrer', 'cancel'])

<form method="post" action="{{ $action }}" class="form">
    @csrf
    @method($method)

    <x-ui.card>
        <div class="form__body">
            <div class="form__row">
                <x-form.input name="name" label="Nom" required :value="$breed?->name" placeholder="Berger australien"/>

                <x-form.select name="species" label="Espèce" required :value="$breed?->species"
                               :options="['' => '— Choisir —', 'dog' => 'Chien', 'cat' => 'Chat']"/>
            </div>

            <div class="form__actions">
                <a href="{{ $cancel }}" class="btn btn--ghost">Annuler</a>
                <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </x-ui.card>
</form>
