@props([
    'action',
    'method' => 'POST',
    'pet' => null,
    'users',
    'breeds',
    'genders',
    'submitLabel' => 'Enregistrer',
    'cancel',
])

@php
    $userOptions = $users->pluck('name', 'id')->prepend('— Choisir —', '');
    $genderOptions = collect($genders)->mapWithKeys(fn ($gender) => [$gender->value => $gender->label()])->prepend('— Choisir —', '');
    $speciesOptions = ['' => '— Choisir —', 'dog' => 'Chien', 'cat' => 'Chat'];
    $breedOptions = $breeds->pluck('name', 'id')->prepend('— Choisir —', '');
    $breedSpecies = $breeds->mapWithKeys(fn ($breed) => [$breed->id => ['data-species' => $breed->species]]);
@endphp

<form method="post" action="{{ $action }}" class="form">
    @csrf
    @method($method)

    <x-ui.card>
        <div class="form__body">
            <div class="form__group">
                <h2 class="form__legend">Propriétaire</h2>

                <x-form.select name="user_id" label="Compte" icon="user" required :options="$userOptions"
                               :value="$pet?->user_id"
                               hint="Le compte auquel cette fiche appartient."/>
            </div>

            <hr class="form__separator">

            <div class="form__group">
                <h2 class="form__legend">Identité</h2>

                <div class="form__row">
                    <x-form.input name="name" label="Nom" required :value="$pet?->name" placeholder="Choupette"/>

                    <x-form.select name="gender" label="Sexe" required :options="$genderOptions"
                                   :value="$pet?->gender?->value"/>

                    <x-form.select name="species" label="Espèce" required :options="$speciesOptions"
                                   :value="$pet?->breed?->species"/>

                    <x-form.select name="breed_id" label="Race" required :options="$breedOptions"
                                   :option-attributes="$breedSpecies" :value="$pet?->breed_id"
                                   hint="Selon l'espèce choisie."/>

                    <x-form.input name="birth_date" type="date" label="Date de naissance" required
                                  :value="$pet?->birth_date" :max="now()->format('Y-m-d')"/>
                </div>
            </div>

            <hr class="form__separator">

            <div class="form__group">
                <h2 class="form__legend">Santé</h2>

                <x-form.input name="last_vet_visit_at" type="date" label="Dernière visite vétérinaire"
                              :value="$pet?->last_vet_visit_at" :max="now()->format('Y-m-d')"/>

                <x-form.input name="health_notes" label="Notes de santé" :rows="4" :value="$pet?->health_notes"
                              placeholder="Allergies, traitements en cours…"/>
            </div>

            @if ($pet?->photo_path)
                <hr class="form__separator">

                <div class="form__group">
                    <h2 class="form__legend">Photo</h2>
                    <img class="upload__preview" src="{{ $pet->photoUrl() }}" alt="Photo de {{ $pet->name }}">
                    <x-form.switch name="remove_photo" label="Supprimer la photo"/>
                </div>
            @endif

            <div class="form__actions">
                <a href="{{ $cancel }}" class="btn btn--ghost">Annuler</a>
                <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </x-ui.card>
</form>
