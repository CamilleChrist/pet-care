@props(['reminders', 'withPet' => false]) {{-- withPet : préfixe chaque rappel du nom de l'animal (accueil) --}}

@if ($reminders->isNotEmpty())
    <ul class="vaccine-list">
        @foreach ($reminders as $record)
            <li>
                <x-vaccine.item :record="$record" :with-pet="$withPet"/>
            </li>
        @endforeach
    </ul>
@else
    <x-ui.alert tone="success">
        <strong>Vous êtes à jour !</strong>
    </x-ui.alert>
@endif
