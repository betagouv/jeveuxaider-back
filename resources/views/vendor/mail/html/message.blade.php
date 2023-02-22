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

    {{-- Footer --}}
    @slot('subfooter')
        @component('mail::subfooter')
        @endcomponent
    @endslot

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer', ['url' => config('app.front_url')])
        @endcomponent
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
