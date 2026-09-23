<x-layouts.app :title="$title" :description="$description" :no-breadcrumb="true">

    @if ($pets->isNotEmpty())
        <x-slot:actions>
            <a href="{{ route('pets.create') }}" class="btn btn--primary">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter un animal
            </a>
        </x-slot>
    @endif

    @if ($pets->isEmpty())
        <x-ui.empty-state title="Aucun animal enregistré"
                          description="Créez une première fiche : nom, espèce, race, date de naissance et sexe. Le suivi du poids et des vaccins s'affichera ici.">
            <a href="{{ route('pets.create') }}" class="btn btn--primary">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter un animal
            </a>
        </x-ui.empty-state>
    @else
        <div class="dashboard two-columns">
            <x-pet.switcher :pets="$pets" class="hidden-md-up"/>

            @if ($overdue)
                <x-ui.alert tone="danger" class="dashboard__alert">
                    <strong>Rappel dépassé</strong><br>{{ $overdue->display_name }} - {{ $overdue->pet->name }},
                    échéance du {{ $overdue->next_due_at->isoFormat('LL') }}.
                </x-ui.alert>
            @endif

            <section aria-labelledby="dashboard-pets">
                <h2 class="dashboard__label" id="dashboard-pets">Mes animaux</h2>

                @foreach ($pets as $pet)
                    <x-ui.card class="pet-card">
                        <header>
                            <x-pet.avatar :pet="$pet" size="md"/>

                            <div class="pet-card-identity">
                                <h3 class="pet-card-identity__name">{{ $pet->name }}</h3>
                                <p class="pet-card-identity__meta">{{ $pet->breed->name }} · {{ $pet->age }}</p>
                            </div>

                            <a href="{{ route('pets.show', $pet) }}" class="btn btn--ghost btn--sm"
                               aria-label="Ouvrir la fiche de {{ $pet->name }}">
                                <span class="hidden-md-down">Ouvrir la fiche</span>
                                <x-ui.icon name="chevron-right" class="btn__icon"/>
                            </a>
                        </header>

                        <x-pet.weight :pet="$pet"/>
                    </x-ui.card>
                @endforeach
            </section>

            <aside>
                <x-ui.card subtitle="Vaccins" title="Prochains rappels">
                    <x-pet.reminders :reminders="$reminders" with-pet/>
                </x-ui.card>

                <x-ui.tip title="Pesez au même moment">
                    Une pesée par mois, à la même heure, suffit pour repérer une variation anormale.
                </x-ui.tip>
            </aside>
        </div>
    @endif

</x-layouts.app>
