@component('mail::message')
    @component('mail::components.headline')
        Bonjour {{ $notifiable->first_name }},
    @endcomponent
    @component('mail::components.paragraph')
        {{ $note->user->profile->full_name }} a posté une nouvelle note sur « {{ $note->notable->name }} »
    @endcomponent
    @component('mail::components.card-message', [
        'title' => $note->user->profile->full_name,
        'subtitle' => $note->user->profile->email,
    ])
        {{ $note->content }}
    @endcomponent
    @if ($note->notable_type == 'App\Models\Structure')
        @component('mail::components.table-organisation', ['organisation' => $notable, 'showInfos' => true])
        @endcomponent
    @endif
    @if ($note->notable_type == 'App\Models\Mission')
        @component('mail::components.table-mission', ['mission' => $notable, 'showInfos' => true])
        @endcomponent
    @endif
    @component('mail::components.space', ['height' => 33])@endcomponent
    @component('mail::button', ['url' => $url])
        Accéder à la fiche
    @endcomponent
    @component('mail::components.space', ['height' => 33])@endcomponent
@endcomponent
