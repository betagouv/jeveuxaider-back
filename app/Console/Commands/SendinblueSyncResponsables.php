<?php

namespace App\Console\Commands;

use App\Jobs\SendinblueSyncUser;
use App\Models\User;
use Illuminate\Console\Command;

class SendinblueSyncResponsables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendinblue:sync-responsables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync responsables in Sendinblue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $query = User::role(['responsable'])
            ->with(['structures', 'profile.participations', 'profile.activities', 'structures.missions']);

        $this->info($query->count() . ' responsables vont être resynchronisés côté Brevo.');

        if ($this->confirm('Continuer ?')) {
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $user) {
                SendinblueSyncUser::dispatch($user);
                $bar->advance();
            }

            $bar->finish();
        }
    }
}
