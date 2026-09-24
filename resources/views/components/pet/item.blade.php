{{-- Ligne d'un animal dans le listing : avatar, nom + race · âge, poids actuel + état vaccinal, chevron. --}}
<a href="{{ route('pets.show', $pet) }}" {{ $attributes->class(['card', 'pet-item']) }}>
    <x-pet.avatar :pet="$pet" size="sm"/>

    <span class="pet-item-body">
        <h3 class="pet-item-body__name">{{ $pet->name }}</h3>
        <span class="pet-item-body__meta">{{ $pet->breed->name }} · {{ $pet->age }}</span>
    </span>

    <span class="pet-item-side">
        @if ($pet->latestWeightRecord)
            <span class="pet-item-side__weight">{{ $pet->latestWeightRecord->formatted_weight }} kg</span>
        @endif

        @if ($reminder)
            <x-ui.badge :tone="$tone">
                <x-ui.icon name="syringe" class="badge__icon"/>
                <span class="sr-only">Vaccins :</span>
                {{ $reminder->status_label }}
            </x-ui.badge>
        @endif
    </span>

    <x-ui.icon name="chevron-right" class="pet-item__chevron"/>
</a>
