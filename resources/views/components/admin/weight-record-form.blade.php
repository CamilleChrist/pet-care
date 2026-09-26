@props(['action', 'method' => 'POST', 'record' => null, 'pet' => null, 'pets', 'submitLabel' => 'Enregistrer', 'cancel'])

@php
    $petOptions = $pets->mapWithKeys(fn ($pet) => [$pet->id => $pet->name.' — '.$pet->user->name])->prepend('— Choisir —', '');
@endphp

<form method="post" action="{{ $action }}" class="form">
    @csrf
    @method($method)

    <x-ui.card>
        <div class="form__body">
            <x-form.select name="pet_id" label="Animal" icon="paw-print" required :options="$petOptions"
                           :value="$record?->pet_id ?? $pet"/>

            <div class="form__row">
                <x-form.input name="weight" type="number" step="0.1" min="0" max="150" label="Poids" suffix="kg"
                              required :value="$record?->weight"/>

                <x-form.input name="recorded_at" type="datetime-local" label="Date de pesée" required
                              :value="$record?->recorded_at?->format('Y-m-d\TH:i')"
                              :max="now()->format('Y-m-d\TH:i')"/>
            </div>

            <div class="form__actions">
                <a href="{{ $cancel }}" class="btn btn--ghost">Annuler</a>
                <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </x-ui.card>
</form>
