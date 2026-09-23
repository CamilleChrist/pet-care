@props(['records']) {{-- WeightRecord, du plus ancien au plus récent --}}

@php
    // Repère 100×100 étiré au conteneur (preserveAspectRatio="none") pour les tracés ;
    // points et dates sont positionnés en % dans le HTML pour ne pas être déformés.
    $records = $records->values();
    $last = $records->count() - 1;
    $weights = $records->map(fn ($record) => (float) $record->weight);
    $span = ($weights->max() - $weights->min()) ?: 1;
    [$low, $high] = [$weights->min() - $span * .25, $weights->max() + $span * .25];

    $x = fn (int $i) => round(3 + $i * 94 / max($last, 1), 1);
    $y = fn (float $weight) => round(8 + (1 - ($weight - $low) / ($high - $low)) * 74, 1);

    $line = $weights->map(fn ($weight, $i) => ($i ? 'L' : 'M').$x($i).','.$y($weight))->implode(' ');
@endphp

<div {{ $attributes->class(['weight-chart']) }}>
    <svg viewBox="0 0 100 100" preserveAspectRatio="none" role="img" aria-label="Courbe de poids en kg">
        <line class="weight-chart__grid" x1="3" x2="97" y1="82" y2="82" />
        <path class="weight-chart__area" d="{{ $line }} L{{ $x($last) }},82 L{{ $x(0) }},82 Z" />
        <path class="weight-chart__line" d="{{ $line }}" />
    </svg>

    @foreach ($records as $i => $record)
        <span @class(['weight-chart__point', 'weight-chart__point--last' => $i === $last]) style="left: {{ $x($i) }}%; top: {{ $y($weights[$i]) }}%"></span>

        @if ($i === 0 || $i === $last || $last < 5)
            <span @class(['weight-chart__axis', 'weight-chart__axis--start' => $i === 0, 'weight-chart__axis--end' => $i === $last]) style="left: {{ $x($i) }}%">{{ $record->recorded_at->isoFormat('D MMM') }}</span>
        @endif
    @endforeach
</div>
