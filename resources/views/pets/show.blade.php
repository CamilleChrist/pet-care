<x-layouts.app :title="$pet->name" :back="route('pets.index')">

    <x-slot:avatar>
        <x-pet.avatar :pet="$pet" size="lg" class="hidden-md-down"/>
    </x-slot:avatar>

    <x-slot:description>
        <span class="hidden-md-up">{{ $pet->breed->name }} · {{ $pet->age }}</span>
        <x-pet.badges :pet="$pet" class="hidden-md-down"/>
    </x-slot:description>

    <x-slot:actions>
        <x-pet.actions :pet="$pet"/>
    </x-slot:actions>

    <div class="pet-profile hidden-md-up">
        <x-pet.avatar :pet="$pet" size="lg"/>
        <x-pet.badges :pet="$pet" compact/>
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
