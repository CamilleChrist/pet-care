<x-layouts.admin title="Vaccins" :description="$description">

    <x-slot:actions>
        <a href="{{ route('admin.vaccines.create') }}" class="btn btn--primary">
            <x-ui.icon name="plus" class="btn__icon"/>
            Ajouter un vaccin
        </a>
    </x-slot>

    @if ($vaccines->isEmpty())
        <x-ui.empty-state title="Aucun vaccin" description="Les vaccins proposés au moment de saisir une vaccination.">
            <a href="{{ route('admin.vaccines.create') }}" class="btn btn--primary">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter un vaccin
            </a>
        </x-ui.empty-state>
    @else
        <x-ui.card>
            <x-admin.table :headers="['Nom', 'Espèce', 'Description', 'Actions']">

                @foreach ($vaccines as $vaccine)
                    <tr>
                        <th scope="row">{{ $vaccine->name }}</th>
                        <td>
                            <x-ui.badge :tone="$vaccine->species">
                                {{ $vaccine->species === 'dog' ? 'Chien' : 'Chat' }}
                            </x-ui.badge>
                        </td>
                        <td>{{ Str::limit($vaccine->description, 60) ?: '—' }}</td>
                        <td class="admin-table__actions">
                            <x-admin.row-actions :edit="route('admin.vaccines.edit', $vaccine)"
                                                 :destroy="route('admin.vaccines.destroy', $vaccine)"
                                                 :label="$vaccine->name"
                                                 dialog="delete-vaccine-{{ $vaccine->id }}"
                                                 title="Supprimer ce vaccin ?"
                                                 description="Les injections déjà enregistrées seront conservées, sous leur nom libre."/>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        </x-ui.card>

        <x-admin.pagination :paginator="$vaccines"/>
    @endif

</x-layouts.admin>
