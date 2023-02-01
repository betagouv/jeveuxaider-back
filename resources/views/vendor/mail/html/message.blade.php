@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.front_url')])
        @endcomponent
    @endslot

    {{-- Body --}}
    <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
    {{ $slot }}
    <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>

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
