<x-layouts.admin title="Animaux" :description="$description">

    <x-slot:actions>
        <a href="{{ route('admin.pets.create') }}" class="btn btn--primary">
            <x-ui.icon name="plus" class="btn__icon"/>
            Ajouter un animal
        </a>
    </x-slot>

    @if ($owner)
        <div class="admin-toolbar">
            <p>Filtré sur un propriétaire.</p>
            <a href="{{ route('admin.pets.index') }}" class="btn btn--ghost btn--sm">Voir tous les animaux</a>
        </div>
    @endif

    @if ($pets->isEmpty())
        <x-ui.empty-state title="Aucun animal" description="Les fiches créées par les utilisateurs apparaîtront ici.">
            <a href="{{ route('admin.pets.create') }}" class="btn btn--primary">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter un animal
            </a>
        </x-ui.empty-state>
    @else
        <x-ui.card>
            <x-admin.table :headers="['Nom', 'Propriétaire', 'Espèce', 'Race', 'Naissance', 'Actions']">
                @foreach ($pets as $pet)
                    <tr>
                        <th scope="row">{{ $pet->name }}</th>
                        <td>
                            <a href="{{ route('admin.users.show', $pet->user) }}">{{ $pet->user->name }}</a>
                        </td>
                        <td>
                            <x-ui.badge :tone="$pet->breed?->species ?? 'neutral'">
                                {{ $pet->breed?->speciesLabel() ?? '—' }}
                            </x-ui.badge>
                        </td>
                        <td>{{ $pet->breed?->name ?? '—' }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($pet->birth_date)->isoFormat('LL') }}</td>
                        <td class="admin-table__actions">
                            <x-admin.row-actions :edit="route('admin.pets.edit', $pet)"
                                                 :destroy="route('admin.pets.destroy', $pet)"
                                                 :label="$pet->name"
                                                 dialog="delete-pet-{{ $pet->id }}"
                                                 title="Supprimer cet animal ?"
                                                 :description="'La fiche de '.$pet->name.', ses pesées et ses vaccinations seront définitivement supprimées.'"/>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        </x-ui.card>

        <x-admin.pagination :paginator="$pets"/>
    @endif

</x-layouts.admin>
