<a href="{{ route('pets.vaccination-records.index', $record->pet) }}" {{ $attributes->class(['vaccine-item']) }}>
    <span class="vaccine-item__icon vaccine-item__icon--{{ $status }}">
        <x-ui.icon :name="$icon" />
    </span>

    <span class="vaccine-item-body">
        <span class="vaccine-item-body__name">{{ $record->display_name }}</span>
        <span class="vaccine-item-body__description">{{ $description }}</span>
    </span>

    <x-ui.badge :tone="$tone">{{ $badge }}</x-ui.badge>

    <x-ui.icon name="chevron-right" class="vaccine-item__chevron" />
</a>
