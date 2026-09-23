<form method="post" action="{{ $action }}" class="form">
    @csrf
    @method($method)

    <x-ui.card>
        <div class="form__body">
            <div class="form__group">
                <h2 class="form__legend">Pesée</h2>

                <div class="form__row">
                    <x-form.input name="weight"
                                  label="Poids"
                                  required
                                  suffix="kg"
                                  :value="$weightRecord?->weight"
                                  placeholder="21.8"
                                  inputmode="decimal" />

                    <x-form.input name="recorded_at"
                                  type="datetime-local"
                                  label="Date"
                                  required
                                  :value="$weightRecord?->recorded_at?->format('Y-m-d\TH:i')"
                                  :max="now()->format('Y-m-d\TH:i')" />
                </div>
            </div>

            <div class="form__actions">
                <a href="{{ $cancel }}" class="btn btn--ghost">Annuler</a>
                <button type="submit" class="btn btn--primary">{{ $submitLabel }}</button>
            </div>
        </div>
    </x-ui.card>
</form>
