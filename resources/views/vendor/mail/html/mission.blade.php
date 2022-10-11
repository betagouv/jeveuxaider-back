<table class="table mission"  width="100%" cellpadding="40" cellspacing="0" >
<tr><td style="padding: 10px 0"></td></tr>
<tr>
<td style="border-right: 1px solid #DDDDDD; padding: 20px; width: 50px;" valign="top">
<svg width="35" height="31" viewBox="0 0 35 31" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M32.8748 0.125C33.8183 0.125 34.5832 0.889847 34.5832 1.83333V29.1667C34.5832 30.1102 33.8183 30.875 32.8748 30.875H2.12484C1.18135 30.875 0.416504 30.1102 0.416504 29.1667V1.83333C0.416504 0.889847 1.18135 0.125 2.12484 0.125H32.8748ZM31.1665 13.7917H3.83317V27.4583H31.1665V13.7917ZM10.6665 15.5V24.0417H5.5415V15.5H10.6665ZM31.1665 3.54167H3.83317V10.375H31.1665V3.54167ZM8.95817 5.25V8.66667H5.5415V5.25H8.95817ZM15.7915 5.25V8.66667H12.3748V5.25H15.7915Z" fill="#000091"/>
</svg>
</td>
<td style="padding: 0 20px;">
<div class="divider"><div class="label">La mission</div> <hr></div>
<div class="title-blue">{{ $mission->name }}</div>

<!-- ADDRESSE -->
<table>
<tr>
<td style="padding-right: 10px; width: 50px; font-size: 28px; line-height: 36px;" valign="top">📍</td>
<td>
@if($mission->is_autonomy)
<div class="text-label">Mission en autonomie</div>
<div class="text-value">
@foreach ($mission->autonomy_zips as $item)
{{ $item['city'] }} {{ $item['zip'] }} {{ $loop->last ? '' : ' • ' }}
@endforeach
@endif
@if($mission->type == 'Mission en présentiel' && !$mission->is_autonomy)
<div class="text-label">{{ $mission->city }} {{ $mission->zip }}</div>
<div class="text-value">{{ $mission->address }}</div>
@endif
@if($mission->type == 'Mission à distance')
<div class="text-label">Mission à distance</div>
@endif
</td>
</tr>
</table>

<!-- DATES -->
<table>
<tr>
<td style="padding-right: 10px; width: 50px; font-size: 28px; line-height: 36px;" valign="top">🗓️</td>
<td>
<div class="text-label">
@if($mission->start_date && $mission->end_date)
@if($mission->start_date == $mission->end_date)
{{ Utils::formatDate($mission->start_date) }}
@else
Entre le {{ Utils::formatDate($mission->start_date) }} et le {{ Utils::formatDate($mission->end_date) }}
@endif
@else
@if($mission->date_type == 'recurring')
Mission récurrente à partir du {{ Utils::formatDate($mission->start_date) }}
@else
{{ Utils::formatDate($mission->start_date) }}
@endif
@endif
</div>
<div class="text-value">
@if($mission->commitment__time_period)
{{ Utils::labelFromValue($mission->commitment__duration,'duration') }} par {{ Utils::labelFromValue($mission->commitment__time_period,'time_period') }}
@else
{{ Utils::labelFromValue($mission->commitment__duration,'duration') }}
@endif
</div>
</td>
</tr>
</table>

<!-- DATES -->
@if(count($mission->publics_beneficiaires) > 0)
<div class="divider"><div class="label">Publics aidés</div> <hr></div>
<div class="tags">
@foreach ($mission->publics_beneficiaires as $item)
@component('mail::tag', ['variant' => 'default'])
{{ Utils::labelFromValue($item , 'mission_publics_beneficiaires')}}
@endcomponent
@endforeach
</div>
@endif


</td>
</tr>
<tr><td style="padding: 10px 0"></td></tr>

</table>
