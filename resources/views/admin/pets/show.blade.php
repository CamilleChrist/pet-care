<x-layouts.admin :title="$pet->name"
                 :description="$pet->breed?->name.' · '.$pet->user->name"
                 :back="route('admin.pets.index')">

    <x-slot:actions>
        <a href="{{ route('admin.pets.edit', $pet) }}" class="btn btn--primary">
            <x-ui.icon name="pencil" class="btn__icon"/>
            Modifier
        </a>
    </x-slot>

    <x-ui.card title="Fiche">
        <dl class="admin-details">
            <div>
                <dt>Propriétaire</dt>
                <dd><a href="{{ route('admin.users.show', $pet->user) }}">{{ $pet->user->name }}</a></dd>
            </div>
            <div>
                <dt>Espèce</dt>
                <dd>
                    <x-ui.badge :tone="$pet->breed?->species ?? 'neutral'">
                        {{ $pet->breed?->speciesLabel() ?? '—' }}
                    </x-ui.badge>
                </dd>
            </div>
            <div>
                <dt>Race</dt>
                <dd>{{ $pet->breed?->name ?? '—' }}</dd>
            </div>
            <div>
                <dt>Sexe</dt>
                <dd>{{ $pet->gender->label() }}</dd>
            </div>
            <div>
                <dt>Naissance</dt>
                <dd>{{ \Illuminate\Support\Carbon::parse($pet->birth_date)->isoFormat('LL') }}</dd>
            </div>
            <div>
                <dt>Dernière visite</dt>
                <dd>{{ $pet->last_vet_visit_at ? \Illuminate\Support\Carbon::parse($pet->last_vet_visit_at)->isoFormat('LL') : '—' }}</dd>
            </div>
        </dl>
    </x-ui.card>

    <x-ui.card title="Vaccinations" description="Les injections enregistrées pour cet animal.">
        <x-slot:actions>
            <a href="{{ route('admin.vaccination-records.create', ['pet' => $pet->id]) }}" class="btn btn--tertiary btn--sm">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter
            </a>
        </x-slot>

        @if ($vaccinationRecords->isEmpty())
            <p class="admin-empty">Aucune vaccination enregistrée.</p>
        @else
            <x-admin.table :headers="['Vaccin', 'Injection', 'Rappel', 'État', 'Actions']">
                @foreach ($vaccinationRecords as $record)
                    <tr>
                        <th scope="row">{{ $record->display_name }}</th>
                        <td>{{ $record->administered_at->isoFormat('LL') }}</td>
                        <td>{{ $record->next_due_at?->isoFormat('LL') ?? '—' }}</td>
                        <td><x-ui.badge :tone="$record->status_tone">{{ $record->status_label }}</x-ui.badge></td>
                        <td class="admin-table__actions">
                            <x-admin.row-actions :edit="route('admin.vaccination-records.edit', $record)"
                                                 :destroy="route('admin.vaccination-records.destroy', $record)"
                                                 :label="$record->display_name"
                                                 dialog="delete-vaccination-record-{{ $record->id }}"
                                                 title="Supprimer cette vaccination ?"
                                                 description="L'injection et son rappel seront définitivement supprimés."/>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-ui.card>

    <x-ui.card title="Pesées" description="L'historique de poids de cet animal.">
        <x-slot:actions>
            <a href="{{ route('admin.weight-records.create', ['pet' => $pet->id]) }}" class="btn btn--tertiary btn--sm">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter
            </a>
        </x-slot>

        @if ($weightRecords->isEmpty())
            <p class="admin-empty">Aucune pesée enregistrée.</p>
        @else
            <x-admin.table :headers="['Poids', 'Pesé le', 'Actions']">
                @foreach ($weightRecords as $record)
                    <tr>
                        <th scope="row">{{ $record->formatted_weight }} kg</th>
                        <td>{{ $record->recorded_at->isoFormat('LL à HH:mm') }}</td>
                        <td class="admin-table__actions">
                            <x-admin.row-actions :edit="route('admin.weight-records.edit', $record)"
                                                 :destroy="route('admin.weight-records.destroy', $record)"
                                                 :label="'la pesée du '.$record->recorded_at->isoFormat('LL')"
                                                 dialog="delete-weight-record-{{ $record->id }}"
                                                 title="Supprimer cette pesée ?"
                                                 description="Cette mesure sera définitivement retirée de l'historique de l'animal."/>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-ui.card>

    <footer class="page-footer">
        <button type="button" class="btn btn--danger" data-dialog-open="delete-pet">
            <x-ui.icon name="trash-2" class="btn__icon"/>
            Supprimer cet animal
        </button>
    </footer>

    <x-ui.confirm-delete id="delete-pet" :action="route('admin.pets.destroy', $pet)"
                         title="Supprimer cet animal ?"
                         :description="'La fiche de '.$pet->name.', ses pesées et ses vaccinations seront définitivement supprimées.'"/>

</x-layouts.admin>
