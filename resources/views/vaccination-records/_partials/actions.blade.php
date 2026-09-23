<span class="vaccine-item__actions">
    <a href="{{ route('vaccination-records.edit', $record) }}" class="btn btn--ghost btn--round"
       aria-label="Modifier {{ $record->display_name }} du {{ $record->administered_at->isoFormat('LL') }}">
        <x-ui.icon name="pencil" class="btn__icon"/>
    </a>

    {{-- Le formulaire de suppression vit dans le dialog de confirmation, rendu une seule fois en bas de page. --}}
    <button type="button" class="btn btn--ghost-danger btn--round"
            data-dialog-open="delete-vaccination-record-{{ $record->id }}"
            aria-label="Supprimer {{ $record->display_name }} du {{ $record->administered_at->isoFormat('LL') }}">
        <x-ui.icon name="trash-2" class="btn__icon"/>
    </button>
</span>
