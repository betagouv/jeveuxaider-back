<?php

namespace App\Console\Commands;

use App\Models\Mission;
use App\Models\Structure;
use App\Notifications\MissionInDraft;
use App\Notifications\StructureInDraft;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class SendNotificationsStructureInDraft extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:structure-in-draft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notifications to Responsables when their organization is in draft since 1, 7 or  days.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = Structure::with(['responsables'])->where('state', 'Brouillon')
            ->whereDate('created_at',  Carbon::now()->add(-1, 'day'));

        foreach ($query->get() as $structure) {
            Notification::send($structure->responsables, new StructureInDraft($structure, 'j+1'));
        }

        $query = Structure::with(['responsables'])->where('state', 'Brouillon')
            ->whereDate('created_at',  Carbon::now()->add(-7, 'day'));

        foreach ($query->get() as $structure) {
            Notification::send($structure->responsables, new StructureInDraft($structure, 'j+7'));
        }

        $query = Structure::with(['responsables'])->where('state', 'Brouillon')
            ->whereDate('created_at',  Carbon::now()->add(-15, 'day'));

        foreach ($query->get() as $structure) {
            Notification::send($structure->responsables, new StructureInDraft($structure, 'j+15'));
        }
    }
}