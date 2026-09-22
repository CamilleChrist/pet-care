@if ($reminders->isNotEmpty())
    <x-ui.card subtitle="Vaccins" title="Prochains rappels">
        <ul class="vaccine-list">
            @foreach ($reminders as $record)
                <li>
                    <x-vaccine.item :record="$record" with-pet/>
                </li>
            @endforeach
        </ul>
    </x-ui.card>
@else
    <x-ui.card subtitle="Vaccins" title="Prochains rappels">
        <x-ui.alert tone="success">
            <strong>Vous êtes à jour !</strong>
        </x-ui.alert>
    </x-ui.card>
@endif
