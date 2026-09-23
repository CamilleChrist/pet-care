@props(['id', 'action', 'title', 'description' => null])

{{-- Confirmation avant suppression : le bouton déclencheur porte data-dialog-open="{{ $id }}". --}}
<x-ui.dialog :id="$id" :title="$title" :description="$description">
    <form method="post" action="{{ $action }}">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn--danger btn--block">Supprimer</button>
    </form>

    <button type="button" class="btn btn--ghost btn--block" data-dialog-close>Annuler</button>
</x-ui.dialog>
