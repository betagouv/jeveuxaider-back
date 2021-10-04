<?php

namespace App\Console\Commands;

use App\Models\NotificationTemoignage;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class NotificationTemoignageCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification-temoignage:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete notification if there is already an existing testimony.';

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
        $query = NotificationTemoignage::whereHas('participation', function (Builder $query) {
            $query->whereHas('temoignage');
        });

        $this->info("Delete notification if there is already an existing testimony.");
        $this->info($query->count() . " notification(s) will be deleted");

        if ($this->confirm('Do you wish to continue?')) {
            foreach ($query->get() as $notificationTemoignage) {
                /** @var \App\Models\notificationTemoignage $notificationTemoignage */
                $notificationTemoignage->delete();
            }
        }
    }
}
