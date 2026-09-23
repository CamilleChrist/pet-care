{{-- Rappel des vaccins déjà saisis : sous le formulaire, à côté à partir de xl. --}}
@if ($recorded->isNotEmpty())
    <aside>
        <x-ui.card tagTitle="h2" title="Déjà enregistrés">
            <ul class="vaccine-list">
                @foreach ($recorded as $injections)
                    <li>
                        <x-vaccine.item :record="$injections->first()" :injections="$injections->count()" />
                    </li>
                @endforeach
            </ul>
        </x-ui.card>
    </aside>
@endif
