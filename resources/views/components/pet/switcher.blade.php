@props(['pets']) {{-- Rangée d'avatars défilante (mobile) : un lien par animal + « Ajouter » --}}

<nav {{ $attributes->class(['pet-switcher']) }} aria-label="Mes animaux">
    @foreach ($pets as $pet)
        <a href="{{ route('pets.show', $pet) }}" class="pet-switcher__item">
            <x-pet.avatar :pet="$pet" size="md" />
            <span class="pet-switcher__label">{{ $pet->name }}</span>
        </a>
    @endforeach

    <a href="{{ route('pets.create') }}" class="pet-switcher__item">
        <span class="pet-switcher__add"><x-ui.icon name="plus" /></span>
        <span class="pet-switcher__label">Ajouter</span>
    </a>
</nav>
