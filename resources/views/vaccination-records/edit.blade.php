<x-layouts.app :title="$title" :description="$description" :back="route('vaccination-records.show', $vaccinationRecord)">

    <div @class(['two-columns' => $recorded->isNotEmpty(), 'one-column' => $recorded->isEmpty()])>
        <section>
            @include('vaccination-records._partials.form', [
                'action' => route('vaccination-records.update', $vaccinationRecord),
                'method' => 'PATCH',
                'cancel' => route('vaccination-records.show', $vaccinationRecord),
                'submitLabel' => 'Enregistrer',
            ])
        </section>

        @include('vaccination-records._partials.recorded', ['recorded' => $recorded])
    </div>

</x-layouts.app>
