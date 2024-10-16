@php
    $mission->loadMissing('template.domaine','template.photo');
    $domaineName = $mission->template_id ? $mission->template->domaine->name : $mission->domaine->name;
    $pictureUrl = isset($mission->picture['formPreview']) ? $mission->picture['formPreview'] : 'https://www.jeveuxaider.gouv.fr/images/card-thumbnail-default@2x.jpg';
@endphp
@component('mail::components.space', ['height' => 12])
@endcomponent
<table class="mail-table-card-teaser-mission" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td style="padding-right: 20px;">
                        <a href="{{ $url }}" style="display: block; width: 215px; height: 150px; overflow: hidden;">
                            <img srcset="{{ $pictureUrl }}" alt="" style="width: 215px; height: 150px; object-fit: cover;" />
                        </a>
                    </td>
                    <td>
                        <!-- <div style="font-size: 22px; font-weight: 700; color: #161616; margin-bottom: 16px;">
                            {{ $mission->id }}
                        </div> -->
                        @if($domaineName)
                        <div style="font-size: 14px; font-weight: 700; color: #161616; text-transform: uppercase; margin-bottom: 2px;">
                            {{ $domaineName }}
                        </div>
                        @endif
                        <div style="font-size: 20px; font-weight: 700; color: #161616; margin-bottom: 2px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            {{ $mission->name }}
                        </div>
                        @if($mission->commitment)
                            <div style="font-size: 16px; font-weight: 400; color: #666666; margin-bottom: 2px;">
                                {{ config('taxonomies.commitment.terms')[$mission->commitment] }}
                            </div>
                        @endif
                        <div style="font-size: 16px; font-weight: 400; color: #000091; text-decoration: underline;">
                            <a href="{{ $url }}">Ouvrir la mission</a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@component('mail::components.space', ['height' => 12])
@endcomponent