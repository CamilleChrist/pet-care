@props([
    'pet',
    'compact' => false // compact : sans race ni âge (déjà dans le sous-titre mobile)
])

<span {{ $attributes->class('badge-list') }}>

    <x-ui.badge :tone="$pet->breed->species">
        <x-ui.icon :name="$pet->breed->species" class="badge__icon"/>
        {{ $pet->breed->speciesLabel() }}
    </x-ui.badge>

    <x-ui.badge>{{ $pet->gender->label() }}</x-ui.badge>

    @unless ($compact)
        <x-ui.badge>{{ $pet->breed->name }}</x-ui.badge>
    @endunless

    <x-ui.badge>
        <x-ui.icon name="cake" class="badge__icon"/>
        {{ \Illuminate\Support\Carbon::parse($pet->birth_date)->isoFormat('LL') }}
        @unless ($compact)
            · {{ $pet->age }}
        @endunless
    </x-ui.badge>
</span>
