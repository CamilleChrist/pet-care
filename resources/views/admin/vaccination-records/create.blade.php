<x-layouts.admin title="Ajouter une vaccination" description="Enregistrer une injection pour un animal"
                 :back="$pet ? route('admin.pets.show', $pet) : route('admin.pets.index')">

    <x-admin.vaccination-record-form :action="route('admin.vaccination-records.store')" :pet="$pet" :pets="$pets"
                                     :vaccines="$vaccines" submit-label="Enregistrer la vaccination"
                                     :cancel="$pet ? route('admin.pets.show', $pet) : route('admin.pets.index')"/>

</x-layouts.admin>
