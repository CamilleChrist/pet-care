<x-layouts.admin :title="$user->name"
                 :description="$user->email.' · inscrit le '.$user->created_at->isoFormat('LL')"
                 :back="route('admin.users.index')">

    <x-slot:actions>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn--primary">
            <x-ui.icon name="pencil" class="btn__icon"/>
            Modifier
        </a>
    </x-slot>

    <x-ui.card title="Compte">
        <dl class="admin-details">
            <div>
                <dt>Rôle</dt>
                <dd>
                    <x-ui.badge :tone="$user->isAdmin() ? 'warning' : 'neutral'">{{ $user->role->label() }}</x-ui.badge>
                </dd>
            </div>
            <div>
                <dt>E-mail vérifié</dt>
                <dd>{{ $user->email_verified_at?->isoFormat('LL') ?? 'Non' }}</dd>
            </div>
            <div>
                <dt>Animaux</dt>
                <dd>{{ $pets->count() }}</dd>
            </div>
        </dl>
    </x-ui.card>

    <x-ui.card title="Animaux" description="Les fiches détenues par ce compte.">
        @if ($pets->isEmpty())
            <p class="admin-empty">Ce compte n'a enregistré aucun animal.</p>
        @else
            <x-admin.table :headers="['Nom', 'Race', 'Dernier poids', 'Vaccinations', 'Actions']">
                @foreach ($pets as $pet)
                    <tr>
                        <th scope="row">
                            <a href="{{ route('admin.pets.show', $pet) }}">{{ $pet->name }}</a>
                        </th>
                        <td>{{ $pet->breed?->name ?? '—' }}</td>
                        <td>{{ $pet->latestWeightRecord?->formatted_weight ?? '—' }}</td>
                        <td>{{ $pet->vaccinationRecords->count() }}</td>
                        <td class="admin-table__actions">
                            <x-admin.row-actions :show="route('admin.pets.show', $pet)"
                                                 :edit="route('admin.pets.edit', $pet)"
                                                 :destroy="route('admin.pets.destroy', $pet)"
                                                 :label="$pet->name"
                                                 dialog="delete-pet-{{ $pet->id }}"
                                                 title="Supprimer cet animal ?"
                                                 :description="'La fiche de '.$pet->name.', ses pesées et ses vaccinations seront définitivement supprimées.'"/>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </x-ui.card>

    @unless ($user->is(auth()->user()))
        <footer class="page-footer">
            <button type="button" class="btn btn--danger" data-dialog-open="delete-user">
                <x-ui.icon name="trash-2" class="btn__icon"/>
                Supprimer ce compte
            </button>
        </footer>

        <x-ui.confirm-delete id="delete-user" :action="route('admin.users.destroy', $user)"
                             title="Supprimer ce compte ?"
                             :description="'Le compte de '.$user->name.', ses animaux, leurs pesées et leurs vaccinations seront définitivement supprimés.'"/>
    @endunless

</x-layouts.admin>
