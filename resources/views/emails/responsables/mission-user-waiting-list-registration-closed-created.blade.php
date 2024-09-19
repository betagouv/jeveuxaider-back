@component('mail::message')
    @component('mail::components.headline')
        {{ $notifiable->first_name}}, un bénévole attend que les inscriptions ouvrent de nouveau sur votre mission
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Quelle mission ?'])
        {{ $mission->name }}
    @endcomponent
    @component('mail::components.paragraph', ['title' => 'Les inscriptions sont actuellement fermées'])
        Les bénévoles ne peuvent donc plus proposer leur aide.
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 16, 'spaceBottom' => 32])
    @endcomponent
    @component('mail::components.paragraph', ['title' => '🕵️ Vous recherchez toujours des bénévoles ?'])
        <ol>
            <li>Allez sur la fiche de votre mission</li>
            <li>Ouvrez les inscriptions</li>
            <li>Le bénévole sera automatiquement informé et pourra proposer son aide</li>
        </ol>  
    @endcomponent
    @component('mail::button', ['url' => $url])
       Ouvrir les inscriptions
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 42, 'spaceBottom' => 32])
    @endcomponent
    @component('mail::components.paragraph', ['title' => '🙌 Vous n’êtes plus en recherche de bénévoles ?'])
        <ol>
            <li><strong>Mettez à jour les participations</strong> en attente de traitement ou en cours de validation</li>
            <li><strong>Passez votre mission au statut “Terminé”.</strong> Vous pourrez, plus tard, proposer d’autres missions en fonction de vos besoins</li>
        </ol>  
    @endcomponent
    @component('mail::button', ['url' => $url])
        Changer le statut
    @endcomponent
    @component('mail::components.divider', ['spaceTop' => 32, 'spaceBottom' => 0])
    @endcomponent
    @component('mail::components.tips')
        <p><strong>Besoin d’aide ?</strong></p>
        <p >Retrouvez toutes les infos dans <a class="link" href="https://reserve-civique.crisp.help/fr/category/organisation-1u4m061/">notre centre d’aide</a>. Vous pouvez aussi répondre à cet email pour poser vos questions.</p>
    @endcomponent
@endcomponent