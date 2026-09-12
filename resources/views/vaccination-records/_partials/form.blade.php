<form method="post" action="{{ $action }}" class="flex flex-col gap-4">
    @csrf
    @method($method)

    <label class="flex flex-col gap-1">
        Vaccin :
        <select name="vaccine_id" class="border p-2">
            <option value="">— Aucun —</option>
            @foreach ($vaccines as $vaccine)
                <option value="{{ $vaccine->id }}" @selected(old('vaccine_id', $vaccinationRecord?->vaccine_id) == $vaccine->id)>
                    {{ $vaccine->name }}
                </option>
            @endforeach
        </select>
    </label>

    <label class="flex flex-col gap-1">
        Nom du vaccin si absent de la liste ci-dessus :
        <input
            name="custom_name"
            type="text"
            value="{{ old('custom_name', $vaccinationRecord?->custom_name) }}" class="border p-2">
    </label>

    <label class="flex flex-col gap-1">
        Date d'administration :*
        <input
            name="administered_at"
            type="date"
            required
            max="{{ now()->format('Y-m-d') }}"
            value="{{ old('administered_at', $vaccinationRecord?->administered_at?->format('Y-m-d')) }}"
            class="border p-2"
        >
    </label>

    <label class="flex flex-col gap-1">
        Prochain rappel :
        <input
            name="next_due_at"
            type="date"
            value="{{ old('next_due_at', $vaccinationRecord?->next_due_at?->format('Y-m-d')) }}"
            class="border p-2"
        >
    </label>

    <label class="flex flex-col gap-1">
        Vétérinaire :
        <input
            name="veterinarian_name"
            type="text"
            value="{{ old('veterinarian_name', $vaccinationRecord?->veterinarian_name) }}" class="border p-2">
    </label>

    <label class="flex flex-col gap-1">
        Clinique :
        <input
            name="clinic_name"
            type="text"
            value="{{ old('clinic_name', $vaccinationRecord?->clinic_name) }}" class="border p-2">
    </label>

    <label class="flex flex-col gap-1">
        N° de lot :
        <input
            name="lot_number"
            type="text"
            value="{{ old('lot_number', $vaccinationRecord?->lot_number) }}" class="border p-2">
    </label>

    <label class="flex flex-col gap-1">
        Notes :
        <input
            name="notes"
            type="text"
            value="{{ old('notes', $vaccinationRecord?->notes) }}" class="border p-2">
    </label>

    <button type="submit" class="bg-blue-600 text-white p-2 self-start">
        {{ $submitLabel }}
    </button>
</form>

@if ($errors->any())
    <ul class="mt-4 flex flex-col gap-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
