@if ($latest)
    <div class="pet-weight">
        <p class="pet-weight__label">Poids actuel</p>

        <p class="pet-weight__row">
            <span class="pet-weight__value">{{ $value }}</span>
            <span class="pet-weight__unit">kg</span>

            @if ($trend)
                <span class="pet-weight__trend pet-weight__trend--{{ $trend }}">
                    <x-ui.icon :name="['up' => 'trending-up', 'down' => 'trending-down', 'flat' => 'minus'][$trend]" class="pet-weight__trend-icon" />
                    {{ $trendLabel }}
                </span>
            @endif
        </p>

        <p class="pet-weight__caption">Mesuré le {{ $latest->recorded_at->isoFormat('LL') }}</p>
    </div>

    <x-weight.chart :records="$records" />
@else
    <p class="pet-weight__empty">
        Aucune pesée enregistrée.
        <a href="{{ route('pets.weight-records.create', $pet) }}" class="btn--primary">Ajouter une pesée</a>
    </p>
@endif
