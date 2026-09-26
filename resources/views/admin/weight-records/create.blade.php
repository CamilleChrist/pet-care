<x-layouts.admin title="Ajouter une pesée" description="Enregistrer un poids pour un animal"
                 :back="$pet ? route('admin.pets.show', $pet) : route('admin.pets.index')">

    <x-admin.weight-record-form :action="route('admin.weight-records.store')" :pet="$pet" :pets="$pets"
                                submit-label="Enregistrer la pesée"
                                :cancel="$pet ? route('admin.pets.show', $pet) : route('admin.pets.index')"/>

</x-layouts.admin>
