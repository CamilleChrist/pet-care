<x-layouts.app :title="'Modifier ' . $pet->name" description="Les champs marqués d'un astérisque sont obligatoires"
               :back="route('pets.show', $pet)">

    @include('pets._partials.form', [
        'action' => route('pets.update', $pet),
        'method' => 'PATCH',
        'cancel' => route('pets.show', $pet),
        'submitLabel' => 'Enregistrer',
    ])

</x-layouts.app>
