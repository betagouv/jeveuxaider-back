@php 
    $conversionName = $conversionName ?? 'cardMail';
    $width = $width ?? 215;
    $height = $height ?? 150;
    $mission->loadMissing('template.photo');
    $imageWebpUrl = isset($mission->picture[$conversionName]) ? $mission->picture[$conversionName] : 'https://www.jeveuxaider.gouv.fr/images/card-thumbnail-default@2x.jpg';
@endphp
@if(isset($imageWebpUrl))
    <a href="{{ $url }}" style="display: block; width: {{ $width }}px; height: {{ $height }}px; overflow: hidden;">
        <img srcset="{{ $imageWebpUrl }}" alt="" style="width: {{ $width }}px; height: {{ $height }}px; object-fit: cover;" />
    </a>
@endif