<x-layouts.app :title="$title" :description="$description" :back="route('pets.show', $pet)">

    <div @class(['two-columns' => $recorded->isNotEmpty(), 'one-column' => $recorded->isEmpty()])>
        <section>
            @include('weight-records._partials.form', [
                'action' => route('pets.weight-records.store', $pet),
                'method' => 'POST',
                'weightRecord' => null,
                'cancel' => route('pets.show', $pet),
                'submitLabel' => 'Enregistrer',
            ])
        </section>

        @if ($recorded->isNotEmpty())
            <aside>
                <x-ui.card tagTitle="h2" title="Dernières pesées">
                    <div class="weight-history">
                        <ul class="weight-history-list">
                            @foreach ($recorded as $record)
                                <li>
                                    <span>{{ $record->formatted_weight }}&nbsp;kg</span>
                                    <span>{{ $record->recorded_at->isoFormat('ll') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </x-ui.card>
            </aside>
        @endif

    </div>

</x-layouts.app>
