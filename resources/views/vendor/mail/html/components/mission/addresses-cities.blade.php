@php 
    $cities = collect($mission->addresses)->pluck('city')->unique()->toArray();
    $hasMore = count($cities) > 1;
    $hasMoreCount = count($cities) - 1;
@endphp
<span>{{ $mission->addresses[0]['city'] }}</span>
@if($hasMore)
    @if($hasMoreCount > 1)
        <span>+ {{ $hasMoreCount }} villes</span>
    @else
        <span>+ 1 ville</span>
    @endif
@endif