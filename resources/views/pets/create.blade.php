<x-layouts.app title="Ajouter un animal" description="Les champs marqués d'un astérisque sont obligatoires"
               :back="route('pets.index')">

    @include('pets._partials.form', [
        'action' => route('pets.store'),
        'method' => 'POST',
        'pet' => null,
        'cancel' => route('pets.index'),
        'submitLabel' => 'Enregistrer',
    ])

</x-layouts.app>
