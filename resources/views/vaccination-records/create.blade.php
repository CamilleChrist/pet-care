<x-layouts.app :title="$title" :description="$description" :back="route('pets.vaccination-records.index', $pet)">

    <div @class(['two-columns' => $recorded->isNotEmpty(), 'one-column' => $recorded->isEmpty()])>
        <section>
            @include('vaccination-records._partials.form', [
                'action' => route('pets.vaccination-records.store', $pet),
                'method' => 'POST',
                'vaccinationRecord' => null,
                'cancel' => route('pets.vaccination-records.index', $pet),
                'submitLabel' => 'Enregistrer',
            ])
        </section>

        @include('vaccination-records._partials.recorded', ['recorded' => $recorded])
    </div>

</x-layouts.app>
