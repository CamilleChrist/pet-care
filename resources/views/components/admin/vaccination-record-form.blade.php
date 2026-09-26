@props(['action', 'method' => 'POST', 'record' => null, 'pet' => null, 'pets', 'vaccines', 'submitLabel' => 'Enregistrer', 'cancel'])

@php
    $petOptions = $pets->mapWithKeys(fn ($pet) => [$pet->id => $pet->name.' — '.$pet->user->name])->prepend('— Choisir —', '');
    $vaccineOptions = $vaccines->mapWithKeys(fn ($vaccine) => [$vaccine->id => $vaccine->name.' ('.($vaccine->species === 'dog' ? 'chien' : 'chat').')'])->prepend('— Aucun, nom libre —', '');
@endphp

<form method="post" action="{{ $action }}" class="form">
    @csrf
    @method($method)

    <x-ui.card>
        <div class="form__body">
            <div class="form__group">
                <h2 class="form__legend">Animal et vaccin</h2>

                <x-form.select name="pet_id" label="Animal" icon="paw-print" required :options="$petOptions"
                               :value="$record?->pet_id ?? $pet"/>

                <div class="form__row">
                    <x-form.select name="vaccine_id" label="Vaccin" icon="syringe" :options="$vaccineOptions"
                                   :value="$record?->vaccine_id"
                                   hint="Laissez vide pour saisir un nom libre."/>

                    <x-form.input name="custom_name" label="Nom libre" :value="$record?->custom_name"
                                  placeholder="Vaccin hors catalogue"/>
                </div>
            </div>

            <hr class="form__separator">

            <div class="form__group">
                <h2 class="form__legend">Injection</h2>

                <div class="form__row">
                    <x-form.input name="administered_at" type="date" label="Date d'injection" required
                                  :value="$record?->administered_at?->format('Y-m-d')" :max="now()->format('Y-m-d')"/>

                    <x-form.input name="next_due_at" type="date" label="Prochain rappel"
                                  :value="$record?->next_due_at?->format('Y-m-d')"/>

                    <x-form.input name="veterinarian_name" label="Vétérinaire" :value="$record?->veterinarian_name"/>

                    <x-form.input name="clinic_name" label="Clinique" :value="$record?->clinic_name"/>

                    <x-form.input name="lot_number" label="Numéro de lot" :value="$record?->lot_number"/>
                </div>

                <x-form.input name="notes" label="Notes" :rows="3" :value="$record?->notes"/>
            </div>

            <div class="form__actions">
                <a href="{{ $cancel }}" class="btn btn--ghost">Annuler</a>
                <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </x-ui.card>
</form>
