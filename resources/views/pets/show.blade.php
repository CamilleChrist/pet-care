<x-layouts.app :title="$pet->name" :back="route('pets.index')">

    <x-slot:avatar>
        <x-pet.avatar :pet="$pet" size="lg" class="hidden-md-down"/>
    </x-slot:avatar>

    <x-slot:description>
        <span class="hidden-md-up">{{ $pet->breed->name }} · {{ $pet->age }}</span>
        <x-pet.badges :pet="$pet" class="hidden-md-down"/>
    </x-slot:description>

    <x-slot:actions>
        <x-pet.actions :pet="$pet"/>
    </x-slot:actions>

    <div class="pet-profile hidden-md-up">
        <x-pet.avatar :pet="$pet" size="lg"/>
        <x-pet.badges :pet="$pet" compact/>
    </div>

    <div class="btn-row hidden-md-up">
        <x-pet.actions :pet="$pet"/>
    </div>

    <div class="two-columns">
        <section>
            <x-ui.card>
                <x-pet.weight :pet="$pet"></x-pet.weight>
            </x-ui.card>
        </section>
        <aside>
            <x-ui.card subtitle="Vaccins" title="Prochains rappels">
                <x-pet.reminders :reminders="$reminders"/>
            </x-ui.card>
        </aside>
    </div>

</x-layouts.app>
