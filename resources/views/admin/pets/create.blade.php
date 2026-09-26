<x-layouts.admin title="Ajouter un animal" description="Créer une fiche pour le compte d'un utilisateur"
                 :back="route('admin.pets.index')">

    <x-admin.pet-form :action="route('admin.pets.store')" :users="$users" :breeds="$breeds" :genders="$genders"
                      submit-label="Créer l'animal" :cancel="route('admin.pets.index')"/>

</x-layouts.admin>
