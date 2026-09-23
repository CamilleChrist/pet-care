<x-layouts.app :title="'Modifier ' . $pet->name" description="Les champs marqués d'un astérisque sont obligatoires"
               :back="route('pets.show', $pet)">

    @include('pets._partials.form', [
        'action' => route('pets.update', $pet),
        'method' => 'PATCH',
        'cancel' => route('pets.show', $pet),
        'submitLabel' => 'Enregistrer',
    ])

    <div class="form__actions">
        <button type="button" class="btn btn--ghost-danger" data-dialog-open="delete-pet">
            <x-ui.icon name="trash-2" class="btn__icon"/>
            Supprimer la fiche
        </button>
    </div>

    <x-ui.confirm-delete id="delete-pet" :action="route('pets.destroy', $pet)"
                         title="Supprimer {{ $pet->name }} ?"
                         description="La fiche, les pesées et les vaccins de {{ $pet->name }} seront définitivement supprimés." />

</x-layouts.app>
