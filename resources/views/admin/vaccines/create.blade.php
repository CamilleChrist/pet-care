<x-layouts.admin title="Ajouter un vaccin" description="Proposé au moment de saisir une vaccination"
                 :back="route('admin.vaccines.index')">

    <x-admin.vaccine-form :action="route('admin.vaccines.store')" submit-label="Créer le vaccin"
                          :cancel="route('admin.vaccines.index')"/>

</x-layouts.admin>
