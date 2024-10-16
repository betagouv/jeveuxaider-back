@php
    $domaine = $mission->template?->domaine ? $mission->template->domaine : $mission->domaine;
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
                        <div style="font-size: 14px; font-weight: 700; color: #161616; text-transform: uppercase; margin-bottom: 4px;">
                            {{ $domaine->name }}
                        </div>
                        <div style="font-size: 20px; font-weight: 700; color: #161616; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis;">
                            {{ $mission->name }}
                        </div>
                        @if($mission->commitment)
                            <div style="font-size: 16px; font-weight: 400; color: #666666;">
                                {{ config('taxonomies.commitment.terms')[$mission->commitment] }}
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@component('mail::components.space', ['height' => 12])
@endcomponent