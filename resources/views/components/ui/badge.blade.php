@props(['tone' => 'neutral']) {{-- neutral | success | warning | danger | dog | cat --}}

<span {{ $attributes->class(['badge', "badge--$tone"]) }}>{{ $slot }}</span>
