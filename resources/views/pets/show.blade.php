<x-layouts.app :title="$pet->name" :back="route('pets.index')">

    <x-slot:avatar>
        <x-pet.avatar :pet="$pet" size="lg" class="hidden-md-down"/>
    </x-slot:avatar>

    {{-- Mobile : sous-titre texte (topbar). Desktop : badges sous le titre. --}}
    <x-slot:description>
        <span class="hidden-md-up">{{ $pet->breed->name }} · {{ $pet->age }}</span>
        <span class="badge-list hidden-md-down">
            <x-ui.badge :tone="$pet->breed->species">
                <x-ui.icon :name="$pet->breed->species" class="badge__icon"/>
                {{ $pet->breed->speciesLabel() }}
            </x-ui.badge>
            <x-ui.badge>{{ $pet->gender->label() }}</x-ui.badge>
            <x-ui.badge>{{ $pet->breed->name }}</x-ui.badge>
            <x-ui.badge>
                <x-ui.icon name="cake" class="badge__icon"/>
                {{ \Illuminate\Support\Carbon::parse($pet->birth_date)->isoFormat('LL') }} · {{ $pet->age }}
            </x-ui.badge>
        </span>
    </x-slot:description>

    <x-slot:actions>
        <a href="{{ route('pets.edit', [$pet->id]) }}" class="btn btn--tertiary">
            <x-ui.icon name="pencil" class="btn__icon"/>
            Modifier
        </a>
        <a href="{{ route('pets.weight-records.create', [$pet->id]) }}" class="btn btn--primary">
            <x-ui.icon name="plus" class="btn__icon"/>
            Ajouter un poids
        </a>
    </x-slot:actions>

    <div class="pet-profile hidden-md-up">
        <x-pet.avatar :pet="$pet" size="lg"/>
        <span class="badge-list">
            <x-ui.badge :tone="$pet->breed->species">
                <x-ui.icon :name="$pet->breed->species" class="badge__icon"/>
                {{ $pet->breed->speciesLabel() }}
            </x-ui.badge>
            <x-ui.badge>{{ $pet->gender->label() }}</x-ui.badge>
            <x-ui.badge>
                <x-ui.icon name="cake" class="badge__icon"/>
                {{ \Illuminate\Support\Carbon::parse($pet->birth_date)->isoFormat('LL') }}
            </x-ui.badge>
        </span>
    </div>

    <div class="btn-row hidden-md-up">
        <a href="{{ route('pets.edit', [$pet->id]) }}" class="btn btn--tertiary">
            <x-ui.icon name="pencil" class="btn__icon"/>
            Modifier
        </a>
        <a href="{{ route('pets.weight-records.create', [$pet->id]) }}" class="btn btn--primary">
            <x-ui.icon name="plus" class="btn__icon"/>
            Ajouter un poids
        </a>
    </div>

</x-layouts.app>
