<x-layouts.admin :title="'Modifier '.$breed->name"
                 :description="trans_choice('{0} Aucun animal de cette race|{1} :count animal de cette race|[2,*] :count animaux de cette race', $breed->pets()->count())"
                 :back="route('admin.breeds.index')">

    <x-admin.breed-form :action="route('admin.breeds.update', $breed)" method="PATCH" :breed="$breed"
                        :cancel="route('admin.breeds.index')"/>

    <footer class="page-footer">
        <button type="button" class="btn btn--danger" data-dialog-open="delete-breed">
            <x-ui.icon name="trash-2" class="btn__icon"/>
            Supprimer cette race
        </button>
    </footer>

    <x-ui.confirm-delete id="delete-breed" :action="route('admin.breeds.destroy', $breed)"
                         title="Supprimer cette race ?"
                         description="Les animaux qui la portent seront conservés, mais se retrouveront sans race."/>

</x-layouts.admin>
