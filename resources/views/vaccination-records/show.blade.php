@php($current = $injections->first())

<x-layouts.app :title="$title" :description="$description" :back="route('pets.vaccination-records.index', $pet)">

    <div class="two-columns">
        <section>
            <x-slot:actions>
                <a href="{{ route('vaccination-records.edit', $current) }}" class="btn btn--tertiary">
                    <x-ui.icon name="pencil" class="btn__icon"/>
                    Modifier
                </a>
                <a href="{{ route('pets.vaccination-records.create', $pet) }}" class="btn btn--primary">
                    <x-ui.icon name="plus" class="btn__icon"/>
                    Ajouter une injection
                </a>
            </x-slot:actions>

            <x-ui.card subtitle="Injection en cours">
                <x-slot:actions>
                    <x-ui.badge :tone="$current->status_tone">{{ $current->status_label }}</x-ui.badge>
                </x-slot:actions>

                @include('vaccination-records._partials.fields', ['record' => $current, 'separator' => true])
            </x-ui.card>
        </section>

        <aside>
            {{-- Injections remplacées par une plus récente. --}}
            @foreach ($injections->skip(1) as $previous)
                <x-ui.card subtitle="Injection précédente" class="card--sunken">
                    <x-slot:actions>
                        <x-ui.badge>Remplacée</x-ui.badge>
                    </x-slot:actions>

                    @include('vaccination-records._partials.fields', ['record' => $previous, 'separator' => false])
                </x-ui.card>
            @endforeach
        </aside>
    </div>
</x-layouts.app>
