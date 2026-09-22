@props(['pet'])

<a href="{{ route('pets.edit', $pet) }}" class="btn btn--tertiary">
    <x-ui.icon name="pencil" class="btn__icon"/>
    Modifier
</a>
<a href="{{ route('pets.weight-records.create', $pet) }}" class="btn btn--primary">
    <x-ui.icon name="plus" class="btn__icon"/>
    Ajouter un poids
</a>
