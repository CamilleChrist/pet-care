@php($record = $vaccinationRecord ?? null)
@php($vaccineOptions = $vaccines->pluck('name', 'id')->prepend('— Aucun —', ''))

<form method="post" action="{{ $action }}" class="form">
    @csrf
    @method($method)

    <x-ui.card>
        <div class="form__body">
            <div class="form__group">
                <h2 class="form__legend">Vaccin</h2>

                <x-form.input name="vaccine_id" label="Vaccin" :options="$vaccineOptions"
                              :value="$record?->vaccine_id" hint="Référentiel de l'espèce de {{ $pet->name }}." />

                <x-form.input name="custom_name" label="Autre vaccin" :value="$record?->custom_name"
                              placeholder="Leptospirose" hint="À remplir si le vaccin n'est pas dans la liste." />

                <div class="form__row">
                    <x-form.input name="administered_at" type="date" label="Date d'administration" required
                                  :value="$record?->administered_at?->format('Y-m-d')"
                                  :max="now()->format('Y-m-d')" hint="Aujourd'hui au plus tard." />

                    <x-form.input name="next_due_at" type="date" label="Date du rappel"
                                  :value="$record?->next_due_at?->format('Y-m-d')"
                                  hint="À laisser vide si aucun rappel n'est prévu." />
                </div>
            </div>

            <hr class="form__separator">

            <div class="form__group">
                <h2 class="form__legend">Praticien</h2>

                <div class="form__row">
                    <x-form.input name="veterinarian_name" label="Vétérinaire" :value="$record?->veterinarian_name"
                                  placeholder="Dr Lemoine" />

                    <x-form.input name="clinic_name" label="Clinique" :value="$record?->clinic_name"
                                  placeholder="Clinique des Lilas" />
                </div>

                <x-form.input name="lot_number" label="N° de lot" :value="$record?->lot_number"
                              placeholder="LP-4471-B" />
            </div>

            <hr class="form__separator">

            <x-form.input name="notes" label="Notes" :rows="4" :value="$record?->notes"
                          placeholder="Réaction, remarque du vétérinaire…" />

            <div class="form__actions">
                <a href="{{ $cancel }}" class="btn btn--ghost">Annuler</a>
                <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </x-ui.card>
</form>
