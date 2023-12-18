@props([
    'color' => 'blue',
])
<strong class="{{ $color }}">{{ $slot }}</strong>
