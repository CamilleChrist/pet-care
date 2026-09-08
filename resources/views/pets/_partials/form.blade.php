<form method="post" action="{{ $action }}" class="flex flex-col gap-4">
    @csrf

    <label class="flex flex-col gap-1">
        Nom :*
        <input name="name" type="text" required value="{{ old('name', $pet?->name) }}" class="border p-2">
    </label>

    <label class="flex flex-col gap-1">
        Genre :*
        <select name="gender" required class="border p-2">
            <option value="" disabled>Choisir une option</option>

            @foreach ($genders as $gender)
                <option value="{{ $gender->value }}" @selected(old('gender', $pet?->gender?->value) === $gender->value)>
                    {{ $gender->label() }}
                </option>
            @endforeach
        </select>
    </label>

    <label class="flex flex-col gap-1">
        Espèce :*
        <select name="species" id="species" required class="border p-2">
            <option value="" disabled @selected(!old('species', $pet?->breed?->species))>
                Choisir une option
            </option>

            <option value="dog"@selected(old('species', $pet?->breed?->species) === 'dog')>
                Chien
            </option>

            <option value="cat"@selected(old('species', $pet?->breed?->species) === 'cat')>
                Chat
            </option>
        </select>
    </label>

    <label class="flex flex-col gap-1">
        Race :*
        <select name="breed_id" id="breed_id" required class="border p-2">
            <option value="" disabled @selected(!old('breed_id', $pet?->breed_id))>
                Choisir une option
            </option>

            @foreach($breeds as $breed)
                <option
                    value="{{ $breed->id }}"
                    data-species="{{ $breed->species }}"
                    @selected((string) old('breed_id', $pet?->breed_id) === (string) $breed->id)
                >
                    {{ $breed->name }}
                </option>
            @endforeach
        </select>
    </label>

    <label class="flex flex-col gap-1">
        Date de naissance :*
        <input
            name="birth_date"
            type="date"
            required
            value="{{ old('birth_date', $pet?->birth_date) }}"
            class="border p-2"
        >
    </label>

    <label class="flex flex-col gap-1">
        Photo
        <input name="photo" type="file">
    </label>

    @if(!str_contains($action, 'store'))
        <label class="flex flex-col gap-1">
            Notes
            <textarea name="health_notes" class="border p-2">{{ old('name', $pet?->health_notes) }}</textarea>
        </label>

        <label class="flex flex-col gap-1">
            Dernière visite vétérinaire
            <input
                name="last_vet_visit_at"
                type="date"
                value="{{ old('last_vet_visit_at', $pet?->last_vet_visit_at) }}"
                class="border p-2"
            >
        </label>
    @endif

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
