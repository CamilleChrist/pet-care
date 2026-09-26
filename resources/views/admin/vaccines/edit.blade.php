<x-layouts.admin :title="'Modifier '.$vaccine->name"
                 :description="trans_choice('{0} Aucune injection enregistrée|{1} :count injection enregistrée|[2,*] :count injections enregistrées', $vaccine->vaccinationRecords()->count())"
                 :back="route('admin.vaccines.index')">

    <x-admin.vaccine-form :action="route('admin.vaccines.update', $vaccine)" method="PATCH" :vaccine="$vaccine"
                          :cancel="route('admin.vaccines.index')"/>

    <footer class="page-footer">
        <button type="button" class="btn btn--danger" data-dialog-open="delete-vaccine">
            <x-ui.icon name="trash-2" class="btn__icon"/>
            Supprimer ce vaccin
        </button>
    </footer>

    <x-ui.confirm-delete id="delete-vaccine" :action="route('admin.vaccines.destroy', $vaccine)"
                         title="Supprimer ce vaccin ?"
                         description="Les injections déjà enregistrées seront conservées, sous leur nom libre."/>

</x-layouts.admin>
