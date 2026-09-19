<x-layouts.app title="Dashboard" :description="$description">

    <x-slot:actions>
        <a href="{{ route('pets.create') }}" class="btn btn--primary">
            <x-icon name="plus" class="btn__icon" />
            Ajouter un animal
        </a>
    </x-slot>

    <p>Bienvenue {{ auth()->user()->name }}</p>

</x-layouts.app>
