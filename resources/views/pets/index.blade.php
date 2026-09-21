<x-layouts.app title="Mes animaux" :description="$description">

    @if ($pets->isNotEmpty())
        <x-slot:actions>
            <a href="{{ route('pets.create') }}" class="btn btn--primary">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter un animal
            </a>
        </x-slot>
    @endif

    @if ($pets->isEmpty())
        <x-ui.empty-state
            title="Aucun animal enregistré"
            description="Créez une première fiche : nom, espèce, race, date de naissance et sexe."
        >
            <a href="{{ route('pets.create') }}" class="btn btn--primary">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter un animal
            </a>
        </x-ui.empty-state>
    @else
        <ul class="pet-list">
            @foreach ($pets as $pet)
                <li>
                    <x-pet.item :pet="$pet"/>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('pets.create') }}" class="btn btn--primary btn--block hidden-md-up">
            <x-ui.icon name="plus" class="btn__icon"/>
            Ajouter un animal
        </a>
    @endif

</x-layouts.app>
