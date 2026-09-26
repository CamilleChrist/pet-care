<x-layouts.admin title="Ajouter une race" description="Proposée au moment de créer un animal de cette espèce"
                 :back="route('admin.breeds.index')">

    <x-admin.breed-form :action="route('admin.breeds.store')" submit-label="Créer la race"
                        :cancel="route('admin.breeds.index')"/>

</x-layouts.admin>
