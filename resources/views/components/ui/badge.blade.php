@props(['tone' => 'neutral']) {{-- neutral | success | warning | danger --}}

<span {{ $attributes->class(['badge', "badge--$tone"]) }}>{{ $slot }}</span>
