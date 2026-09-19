@props([
    'id',
    'title',
    'description' => null,
])

{{-- Ouvert par [data-dialog-open="{{ $id }}"], fermé par [data-dialog-close], Échap ou clic sur le fond (resources/js/components/dialog.js). --}}
<dialog id="{{ $id }}" {{ $attributes->class(['dialog']) }} aria-labelledby="{{ $id }}-title">
    <p id="{{ $id }}-title" class="dialog__title">{{ $title }}</p>

    @if ($description)
        <p class="dialog__description">{{ $description }}</p>
    @endif

    <div class="dialog__actions">
        {{ $slot }}
    </div>
</dialog>
