@php 
    $mission->loadMissing('domaine','template.domaine');
    $domaine = $mission->template_id ? $mission->template->domaine : $mission->domaine;
    switch ($domaine->id) {
        case 1: // Santé
            $color = '#0063CB';
            break;
        case 2: // Prévention et protection
            $color = '#03D3AB';
            break;
        case 3: // Culture
            $color = '#C3992A';
            break;
        case 4: // Sport
            $color = '#F9702B';
            break;
        case 5:
            $color = '#03A9F4';
            break;
        case 6: // Coopération internationale
            $color = '#5B8397';
            break;
        case 7: // Solidarité
            $color = '#FC5554';
            break;
        case 8: // Memoire et citoyenneté
            $color = '#000091';
            break;
        case 9: // Education pour tous
            $color = '#D47A65';
            break;
        case 10: // Nature
            $color = '#1FAB8D';
            break;
        case 11: // Bénévolat de compétences
            $color = '#006A6F';
            break;
        default:
            $color = '#161616';
    }
@endphp
@if($domaine)
    <span style="color: {{ $color }}">{{ $domaine->name }}</span>
@endif