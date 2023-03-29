@component('mail::message')
    @component('mail::components.headline')
        {{ $notifiable->profile->first_name }}, comment était votre mission chez {{ $organisation->name }} ? 💬
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Merci pour votre engagement sur la mission'])
        {{ $mission->name }}
    @endcomponent
    @component('mail::components.paragraph')
        Saviez-vous que vous pouviez décupler votre impact en nous racontant votre expérience ? Votre témoignage est précieux
        pour encourager de futurs bénévoles à s’engager.
    @endcomponent
    @component('mail::button', ['url' => $url])
        Partagez votre témoignage
    @endcomponent
@endcomponent
