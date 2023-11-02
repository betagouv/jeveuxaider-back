@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.front_url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    @component('mail::components.space', ['height' => 33])@endcomponent
    {{ $slot }}
    @component('mail::components.space', ['height' => 33])@endcomponent

    {{-- Signture --}}
    @slot('signature')
        @if(isset($signature))
            {{ $signature }}
        @else
            @component('mail::signature')
            @endcomponent
        @endif
    @endslot

    {{-- Sub Footer --}}
    @slot('subfooter')
        @component('mail::subfooter')
        @endcomponent
    @endslot

    {{-- Footer --}}
    @slot('footer')
        @if(isset($footer))
            {{ $footer }}
        @else
            @component('mail::footer', ['url' => config('app.front_url')])
            @endcomponent
        @endif
    @endslot

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset
@endcomponent
