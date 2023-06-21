@component('mail::message')
    @component('mail::components.headline')
        Bonjour,
    @endcomponent
    @component('mail::components.paragraph')
        Vous avez été désinscrit de la plateforme JeVeuxAider.gouv.fr car vous ne répondez pas aux conditions d’éligibilité.
    @endcomponent
    @component('mail::components.paragraph')
        En effet, pour proposer votre aide à une mission de bénévolat, vous devez résider régulièrement sur le territoire français et être âgé de 16 ans ou plus.
    @endcomponent
    @component('mail::components.tips', ['title' => 'Des questions ?'])
        En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !
    @endcomponent

    @slot('signature')
        @component('mail::signature', ['regards' => 'Bien à vous,'])
        @endcomponent
    @endslot

    {{-- @slot('footer')
        <table cellpadding="0" cellspacing="0" border="0" width="100%"
            style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
            <tr>
                <td align="center" valign="top">
                    @component('mail::components.space', ['height' => 16, 'color' => '#f3f3f3'])
                    @endcomponent
                </td>
            </tr>
        </table>
    @endslot --}}
@endcomponent
