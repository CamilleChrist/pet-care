<x-layouts.admin :title="'Modifier '.$pet->name" :description="'Fiche détenue par '.$pet->user->name"
                 :back="route('admin.pets.index')">

    <x-admin.pet-form :action="route('admin.pets.update', $pet)" method="PATCH" :pet="$pet" :users="$users"
                      :breeds="$breeds" :genders="$genders" :cancel="route('admin.pets.index')"/>

    <footer class="page-footer">
        <button type="button" class="btn btn--danger" data-dialog-open="delete-pet">
            <x-ui.icon name="trash-2" class="btn__icon"/>
            Supprimer cet animal
        </button>
    </footer>

    <x-ui.confirm-delete id="delete-pet" :action="route('admin.pets.destroy', $pet)"
                         title="Supprimer cet animal ?"
                         :description="'La fiche de '.$pet->name.', ses pesées et ses vaccinations seront définitivement supprimées.'"/>

</x-layouts.admin>
