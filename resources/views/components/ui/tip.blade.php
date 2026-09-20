@props(['title']) {{-- slot = texte du conseil --}}

<x-ui.card {{ $attributes->class(['tip']) }} subtitle="Conseil" :title="$title">
    <p>{{ $slot }}</p>
</x-ui.card>
