@php
    $genderOptions = collect($genders)->mapWithKeys(fn ($gender) => [$gender->value => $gender->label()])->prepend('— Choisir —', '');
    $speciesOptions = ['' => '— Choisir —', 'dog' => 'Chien', 'cat' => 'Chat'];
    $breedOptions = $breeds->pluck('name', 'id')->prepend('— Choisir —', '');
    // Lu par pet-form.js pour n'afficher que les races de l'espèce choisie.
    $breedSpecies = $breeds->mapWithKeys(fn ($breed) => [$breed->id => ['data-species' => $breed->species]]);
@endphp

<form method="post" action="{{ $action }}" enctype="multipart/form-data" class="form">
    @csrf
    @method($method)

    <div class="two-columns">
        <section>
            <x-ui.card>
                <div class="form__body">
                    <div class="form__group">
                        <h2 class="form__legend">Identité</h2>

                        <div class="form__row">
                            <x-form.input name="name" label="Nom" required :value="$pet?->name"
                                          placeholder="Choupette" />

                            <x-form.select name="gender" label="Sexe" required :options="$genderOptions"
                                          :value="$pet?->gender?->value" />

                            <x-form.select name="species" label="Espèce" required :options="$speciesOptions"
                                          :value="$pet?->breed?->species" />

                            <x-form.select name="breed_id" label="Race" required :options="$breedOptions"
                                          :option-attributes="$breedSpecies" :value="$pet?->breed_id"
                                          hint="Selon l'espèce choisie." />

                            <x-form.input name="birth_date" type="date" label="Date de naissance" required
                                          :value="$pet?->birth_date" :max="now()->format('Y-m-d')"
                                          hint="Aujourd'hui au plus tard." />
                        </div>
                    </div>

                    <hr class="form__separator">

                    <div class="form__group">
                        <h2 class="form__legend">Santé</h2>

                        <x-form.input name="last_vet_visit_at" type="date" label="Dernière visite vétérinaire"
                                      :value="$pet?->last_vet_visit_at" :max="now()->format('Y-m-d')" />

                        <x-form.input name="health_notes" label="Notes de santé" :rows="4"
                                      :value="$pet?->health_notes"
                                      placeholder="Allergies, traitements en cours…" />
                    </div>

                    <div class="form__actions">
                        <a href="{{ $cancel }}" class="btn btn--ghost">Annuler</a>
                        <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
                    </div>
                </div>
            </x-ui.card>
        </section>

        <aside>
            <x-ui.card tagTitle="h2" title="Photo">
                @if ($pet?->photo_path)
                    <img class="upload__preview" src="{{ $pet->photoUrl() }}" alt="Photo de {{ $pet->name }}">
                @endif

                <label class="upload">
                    <span class="upload__icon"><x-ui.icon name="image" /></span>
                    <span class="upload__hint">JPEG ou PNG, 2 Mo maximum</span>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="upload__input">
                </label>

                @error('photo')
                    <p class="field__error">{{ $message }}</p>
                @enderror

                @if ($pet?->photo_path)
                    <x-form.switch name="remove_photo" label="Supprimer la photo" />
                @endif
            </x-ui.card>

            @unless ($pet)
                <x-ui.tip title="Ensuite">
                    Après l'enregistrement, ajoutez une première pesée puis les vaccins déjà faits.
                </x-ui.tip>
            @endunless
        </aside>
    </div>
</form>
