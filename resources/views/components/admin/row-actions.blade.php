@props(['edit', 'destroy', 'label', 'dialog', 'title', 'description' => null])

<div class="admin-table__buttons">
    <a href="{{ $edit }}" class="btn btn--ghost btn--round">
        <x-ui.icon name="pencil" class="btn__icon"/>
        <span class="sr-only">Modifier {{ $label }}</span>
    </a>

    <button type="button" class="btn btn--ghost btn--round admin-table__delete" data-dialog-open="{{ $dialog }}">
        <x-ui.icon name="trash-2" class="btn__icon"/>
        <span class="sr-only">Supprimer {{ $label }}</span>
    </button>
</div>

<x-ui.confirm-delete :id="$dialog" :action="$destroy" :title="$title" :description="$description"/>
