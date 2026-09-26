<x-layouts.admin :title="'Modifier '.$record->display_name"
                 :description="$record->pet->name.' · injection du '.$record->administered_at->isoFormat('LL')"
                 :back="route('admin.pets.show', $record->pet)">

    <x-admin.vaccination-record-form :action="route('admin.vaccination-records.update', $record)" method="PATCH"
                                     :record="$record" :pets="$pets" :vaccines="$vaccines"
                                     :cancel="route('admin.pets.show', $record->pet)"/>

    <footer class="page-footer">
        <button type="button" class="btn btn--danger" data-dialog-open="delete-vaccination-record">
            <x-ui.icon name="trash-2" class="btn__icon"/>
            Supprimer cette vaccination
        </button>
    </footer>

    <x-ui.confirm-delete id="delete-vaccination-record" :action="route('admin.vaccination-records.destroy', $record)"
                         title="Supprimer cette vaccination ?"
                         description="L'injection et son rappel seront définitivement supprimés."/>

</x-layouts.admin>
