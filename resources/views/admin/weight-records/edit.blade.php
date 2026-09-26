<x-layouts.admin :title="'Pesée de '.$record->pet->name"
                 :description="$record->formatted_weight.' kg le '.$record->recorded_at->isoFormat('LL')"
                 :back="route('admin.pets.show', $record->pet)">

    <x-admin.weight-record-form :action="route('admin.weight-records.update', $record)" method="PATCH"
                                :record="$record" :pets="$pets"
                                :cancel="route('admin.pets.show', $record->pet)"/>

    <footer class="page-footer">
        <button type="button" class="btn btn--danger" data-dialog-open="delete-weight-record">
            <x-ui.icon name="trash-2" class="btn__icon"/>
            Supprimer cette pesée
        </button>
    </footer>

    <x-ui.confirm-delete id="delete-weight-record" :action="route('admin.weight-records.destroy', $record)"
                         title="Supprimer cette pesée ?"
                         description="Cette mesure sera définitivement retirée de l'historique de l'animal."/>

</x-layouts.admin>
