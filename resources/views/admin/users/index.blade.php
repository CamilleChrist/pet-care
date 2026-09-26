<x-layouts.admin title="Utilisateurs" :description="$description">

    @if ($users->isEmpty())
        <x-ui.empty-state title="Aucun inscrit" description="Les comptes créés depuis l'inscription apparaîtront ici."/>
    @else
        <x-ui.card>
            <x-admin.table class="admin-table--users" :headers="['Nom', 'E-mail', 'Rôle', 'Animaux', 'Inscrit le', 'Actions']">
                @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ $user->name }}</th>
                        <td>{{ $user->email }}</td>
                        <td>
                            <x-ui.badge :tone="$user->isAdmin() ? 'warning' : 'neutral'">
                                {{ $user->role->label() }}
                            </x-ui.badge>
                        </td>
                        <td>{{ $user->pets_count }}</td>
                        <td>{{ $user->created_at->isoFormat('LL') }}</td>
                        <td class="admin-table__actions">
                            <div class="admin-table__buttons">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn--ghost btn--round">
                                    <x-ui.icon name="eye" class="btn__icon"/>
                                    <span class="sr-only">Voir {{ $user->name }}</span>
                                </a>

                                @unless ($user->is(auth()->user()))
                                    <button type="button" class="btn btn--ghost btn--round admin-table__delete"
                                            data-dialog-open="delete-user-{{ $user->id }}">
                                        <x-ui.icon name="trash-2" class="btn__icon"/>
                                        <span class="sr-only">Supprimer le compte de {{ $user->name }}</span>
                                    </button>
                                @endunless
                            </div>

                            @unless ($user->is(auth()->user()))
                                <x-ui.confirm-delete id="delete-user-{{ $user->id }}"
                                                     :action="route('admin.users.destroy', $user)"
                                                     title="Supprimer ce compte ?"
                                                     :description="'Le compte de '.$user->name.', ses animaux, leurs pesées et leurs vaccinations seront définitivement supprimés.'"/>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        </x-ui.card>

        <x-admin.pagination :paginator="$users"/>
    @endif

</x-layouts.admin>
