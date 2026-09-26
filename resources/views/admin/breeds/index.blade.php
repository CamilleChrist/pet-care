<x-layouts.admin title="Races" :description="$description">

    <x-slot:actions>
        <a href="{{ route('admin.breeds.create') }}" class="btn btn--primary">
            <x-ui.icon name="plus" class="btn__icon"/>
            Ajouter une race
        </a>
    </x-slot>

    @if ($breeds->isEmpty())
        <x-ui.empty-state title="Aucune race" description="Les races proposées au moment de créer un animal.">
            <a href="{{ route('admin.breeds.create') }}" class="btn btn--primary">
                <x-ui.icon name="plus" class="btn__icon"/>
                Ajouter une race
            </a>
        </x-ui.empty-state>
    @else
        <x-ui.card>
            <x-admin.table :headers="['Nom', 'Espèce', 'Animaux', 'Actions']">
                @foreach ($breeds as $breed)
                    <tr>
                        <th scope="row">{{ $breed->name }}</th>
                        <td><x-ui.badge :tone="$breed->species">{{ $breed->speciesLabel() }}</x-ui.badge></td>
                        <td>{{ $breed->pets_count }}</td>
                        <td class="admin-table__actions">
                            <x-admin.row-actions :edit="route('admin.breeds.edit', $breed)"
                                                 :destroy="route('admin.breeds.destroy', $breed)"
                                                 :label="$breed->name"
                                                 dialog="delete-breed-{{ $breed->id }}"
                                                 title="Supprimer cette race ?"
                                                 description="Les animaux qui la portent seront conservés, mais se retrouveront sans race."/>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        </x-ui.card>

        <x-admin.pagination :paginator="$breeds"/>
    @endif

</x-layouts.admin>
