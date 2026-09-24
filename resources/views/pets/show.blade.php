@php use Illuminate\Support\Carbon; @endphp

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
            <x-ui.card title="Courbe de poids">
                <x-slot:actions>
                    <a href="{{ route('pets.weight-records.create', $pet) }}" class="btn btn--ghost btn--sm">
                        <x-ui.icon name="plus" class="btn__icon"/>
                        Ajouter
                    </a>
                </x-slot:actions>
                <x-pet.weight :pet="$pet"></x-pet.weight>
            </x-ui.card>


            @if($weightRecords->isNotEmpty())
                <x-ui.card title="Historique des pesées">
                    <x-slot:actions>
                        <a href="{{ route('pets.weight-records.create', $pet) }}" class="btn btn--ghost btn--sm">
                            <x-ui.icon name="plus" class="btn__icon"/>
                            Ajouter
                        </a>
                    </x-slot:actions>

                    <div class="weight-history">

                        <ul class="weight-history-list">
                            @foreach($weightRecords as $record)
                                <li>
                                    <span>{{ $record->formatted_weight }}&nbsp;kg</span>
                                    <span>{{ $record->recorded_at->isoFormat('ll') }}</span>
                                    <button type="button" class="btn btn--ghost-danger"
                                            data-dialog-open="delete-weight-record-{{ $record->id }}"
                                            aria-label="Supprimer la pesée du {{ $record->recorded_at->isoFormat('LL') }}">
                                        <x-ui.icon name="trash-2" class="btn__icon"/>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        @if($weightRecords->hasPages())
                            @php($onFirst = $weightRecords->onFirstPage())
                            @php($onLast = !$weightRecords->hasMorePages())

                            <nav class="weight-history-pagination" aria-label="Pagination de l'historique">
                                <a @unless($onFirst) href="{{ $weightRecords->url(1) }}"
                                   @endunless aria-label="Première page">
                                    <x-ui.icon name="chevrons-left"/>
                                </a>

                                <a @unless($onFirst) href="{{ $weightRecords->previousPageUrl() }}"
                                   @endunless class="weight-history-pagination__link" aria-label="Page précédente">
                                    <x-ui.icon name="chevron-left"/>
                                </a>

                                <span>
                                    Page {{ $weightRecords->currentPage() }} sur {{ $weightRecords->lastPage() }}
                                </span>

                                <a @unless($onLast) href="{{ $weightRecords->nextPageUrl() }}"
                                   @endunless aria-label="Page suivante">
                                    <x-ui.icon name="chevron-right"/>
                                </a>

                                <a @unless($onLast) href="{{ $weightRecords->url($weightRecords->lastPage()) }}"
                                   @endunless aria-label="Dernière page">
                                    <x-ui.icon name="chevrons-right"/>
                                </a>
                            </nav>
                        @endif

                        @foreach($weightRecords as $record)
                            <x-ui.confirm-delete id="delete-weight-record-{{ $record->id }}"
                                                 :action="route('weight-records.destroy', $record)"
                                                 title="Supprimer cette pesée ?"
                                                 description="La pesée de {{ $record->formatted_weight }} kg du {{ $record->recorded_at->isoFormat('LL') }} sera définitivement supprimée."/>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </section>
        <aside>
            <x-ui.card title="Vaccins">
                <x-slot:actions>
                    <a href="{{ route('pets.vaccination-records.index', $pet) }}" class="btn btn--ghost btn--sm">
                        <x-ui.icon name="chevron-right" class="btn__icon"/>
                        Tout voir
                    </a>
                </x-slot:actions>
                <x-pet.reminders :reminders="$reminders"/>
            </x-ui.card>

            @if($pet->health_notes)
                <x-ui.card title="Notes de santé">
                    <p>{{ $pet->health_notes }}</p>
                </x-ui.card>
            @endif

            @if($pet->last_vet_visit_at)
                <x-ui.card title="Dernière visite véterinaire">
                    <strong>{{ Carbon::parse($pet->last_vet_visit_at)->isoFormat('LL') }}</strong>
                </x-ui.card>
            @endif

        </aside>
    </div>

    <div class="page-footer">
        <button type="button" class="btn btn--danger" data-dialog-open="delete-pet">
            <x-ui.icon name="trash-2" class="btn__icon"/>
            Supprimer la fiche
        </button>
    </div>

    <x-ui.confirm-delete id="delete-pet" :action="route('pets.destroy', $pet)"
                         title="Supprimer {{ $pet->name }} ?"
                         description="La fiche, les pesées et les vaccins de {{ $pet->name }} seront définitivement supprimés." />

</x-layouts.app>
