<form method="post" action="{{ $action }}">
    @csrf

    <label>
        Nom :*
        <input name="name" type="text" required value="{{ old('name', $pet?->name) }}">
    </label>

    <label>
        Genre :*
        <select name="gender" required>
            <option value="" disabled>Choisir une option</option>

            @foreach ($genders as $gender)
                <option value="{{ $gender->value }}" @selected(old('gender', $pet?->gender?->value) === $gender->value)>
                    {{ $gender->label() }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Espèce :*
        <select name="species" id="species" required>
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

    <label>
        Race :*
        <select name="breed_id" id="breed_id" required>
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

    <label>
        Date de naissance :*
        <input
            name="birth_date"
            type="date"
            required
            value="{{ old('birth_date', $pet?->birth_date) }}"
        >
    </label>

    <label>
        Photo
        <input name="photo" type="file">
    </label>

    @if(!str_contains($action, 'store'))
        <label>
            Notes
            <textarea name="health_notes">{{ old('name', $pet?->health_notes) }}</textarea>
        </label>

        <label>
            Dernière visite vétérinaire
            <input
                name="last_vet_visit_at"
                type="date"
                value="{{ old('last_vet_visit_at', $pet?->last_vet_visit_at) }}"
            >
        </label>
    @endif

    <button type="submit">
        {{ $submitLabel }}
    </button>
</form>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
