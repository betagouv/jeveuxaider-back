@component('mail::message')
<div class="inline-mail">
{{-- Greeting --}}
@if (!empty($greeting))
@component('mail::components.headline')
{{ $greeting }}
@endcomponent
@endif
{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach
{{-- Action Button --}}
@isset($actionText)
@component('mail::components.space', ['height' => 33])@endcomponent
@component('mail::button', ['url' => $actionUrl])
{{ $actionText }}
@endcomponent
@component('mail::components.space', ['height' => 33])@endcomponent
@endisset
{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach
{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang("Si vous ne parvenez pas à cliquer sur le bouton \":actionText\", copiez-collez l'URL ci-dessous\n" . 'dans votre navigateur: [:actionURL](:actionURL)', [
'actionText' => $actionText,
'actionURL' => $actionUrl,
])
@endslot
@endisset
</div>
@endcomponent
