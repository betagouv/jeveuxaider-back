<?php

namespace App\Console\Commands;

use App\Models\StructureTag;
use Illuminate\Console\Command;

class GenerateStructureGenericTags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-structure-generic-tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $tags = [
            'En attente d’une réponse bénévole',
            'En cours d’intégration',
            'En attente d’un retour en interne',
            'En attente d’une formation / rendez-vous',
            'En attente d’inscription en ligne',
            'En attente d’envoi de documents',
        ];

        foreach ($tags as $tag) {
            StructureTag::updateOrCreate(
                [
                    'structure_id' => null,
                    'name' => $tag
                ],
                [ 'is_generic' => true]
            );
        }
    }
}
