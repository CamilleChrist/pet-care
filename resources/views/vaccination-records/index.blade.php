<x-layouts.app :title="$title" :description="$description" :back="route('pets.show', $pet)">

    <x-slot:actions>
        <a href="{{ route('pets.vaccination-records.create', $pet) }}" class="btn btn--primary">
            <x-ui.icon name="plus" class="btn__icon"/>
            Ajouter un vaccin
        </a>
    </x-slot:actions>

    @if ($vaccines->isEmpty())
        <x-ui.empty-state icon="syringe" title="Aucun vaccin enregistré"
                          description="Ajoutez les vaccins de {{ $pet->name }} pour suivre les rappels.">
            <a href="{{ route('pets.vaccination-records.create', $pet) }}" class="btn btn--primary">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter un vaccin
            </a>
        </x-ui.empty-state>
    @else
        {{-- Bureau (lg et plus) : tableau des échéances. Sous lg : la même liste que la fiche animal. --}}
        <x-ui.card class="hidden-lg-down">
            <table class="vaccine-table">
                <thead>
                    <tr>
                        <th scope="col">Vaccin</th>
                        <th scope="col">Fait le</th>
                        <th scope="col">Rappel</th>
                        <th scope="col">Statut</th>
                        <th scope="col"></th> {{-- actions --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vaccines as $name => $injections)
                        @php($current = $injections->first())

                        <tr @class(['vaccine-table__row--late' => $current->status === 'late'])>
                            <th scope="row">{{ $name }}</th>
                            <td>{{ $current->administered_at->isoFormat('ll') }}</td>
                            <td class="vaccine-table__due">{{ $current->next_due_at?->isoFormat('ll') ?? '—' }}</td>
                            <td><x-ui.badge :tone="$current->status_tone">{{ $current->status_label }}</x-ui.badge></td>
                            <td>@include('vaccination-records._partials.actions', ['record' => $current])</td>
                        </tr>

                        @foreach ($injections->skip(1) as $previous)
                            <tr class="vaccine-table__row--previous">
                                <th scope="row">
                                    {{ $name }} <span class="vaccine-table__hint">Injection précédente</span>
                                </th>
                                <td>{{ $previous->administered_at->isoFormat('ll') }}</td>
                                <td class="vaccine-table__due">{{ $previous->next_due_at?->isoFormat('ll') ?? '—' }}</td>
                                <td><x-ui.badge>Remplacée</x-ui.badge></td>
                                <td>@include('vaccination-records._partials.actions', ['record' => $previous])</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </x-ui.card>

        {{-- Mobile --}}
        <x-ui.card class="hidden-lg-up">
            <ul class="vaccine-list">
                @foreach ($vaccines as $name => $injections)
                    <li>
                        <x-vaccine.item :record="$injections->first()" :injections="$injections->count()" :link="false">
                            @include('vaccination-records._partials.actions', ['record' => $injections->first()])
                        </x-vaccine.item>

                        {{-- Injections remplacées : même vaccin, administré plus tôt. --}}
                        @foreach ($injections->skip(1) as $previous)
                            <div class="vaccine-item vaccine-item--previous">
                                <span class="vaccine-item__icon">
                                    <x-ui.icon name="corner-down-right"/>
                                </span>

                                <span class="vaccine-item-body">
                                    <span class="vaccine-item-body__name">{{ $name }} · injection précédente</span>
                                    <span class="vaccine-item-body__description">
                                        Fait le {{ $previous->administered_at->isoFormat('LL') }}
                                        @if ($previous->next_due_at)
                                            · Rappel le {{ $previous->next_due_at->isoFormat('LL') }}
                                        @endif
                                    </span>
                                </span>

                                <x-ui.badge>Remplacée</x-ui.badge>

                                @include('vaccination-records._partials.actions', ['record' => $previous])
                            </div>
                        @endforeach
                    </li>
                @endforeach
            </ul>
        </x-ui.card>
        @foreach ($vaccines->flatten() as $record)
            <x-ui.confirm-delete id="delete-vaccination-record-{{ $record->id }}"
                                 :action="route('vaccination-records.destroy', $record)"
                                 title="Supprimer ce vaccin ?"
                                 description="{{ $record->display_name }} administré le {{ $record->administered_at->isoFormat('LL') }} sera définitivement supprimé." />
        @endforeach
    @endif

</x-layouts.app>
