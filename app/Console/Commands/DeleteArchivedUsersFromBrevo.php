<?php

namespace App\Console\Commands;

use App\Jobs\SendinblueDeleteUser;
use App\Models\UserArchivedDatas;
use Illuminate\Console\Command;
use Maize\Encryptable\Encryption;

class DeleteArchivedUsersFromBrevo extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'delete-archived-users-from-brevo {--debug} {--maxUserId=} {--email=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete archived users from Brevo.';

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
        $options = $this->options();
        $query = UserArchivedDatas::whereNull('brevo_deleted_at')->orderBy('user_id');
        if (!empty($options['maxUserId'])) {
            $query->where('user_id', '<=', $options['maxUserId']);
        }
        if (!empty($options['email'])) {
            $encriptedEmails = [];
            array_map(function ($email) use (&$encriptedEmails) {
                $encriptedEmails[] = Encryption::php()->encrypt($email);
            }, $options['email']);
            $query->whereIn('email', $encriptedEmails);
        }

        if ($this->confirm($query->count() . ' contacts vont être supprimés de Brevo. Continuer ?')) {
            $start = now();
            $executionTime = 0;

            foreach($query->cursor() as $archivedUserDatas) {
                $email = Encryption::php()->decrypt($archivedUserDatas->email);
                if ($options['debug']) {
                    $this->info('email: ' . $email);
                }
                SendinblueDeleteUser::dispatch($email, "user_archived");

                $time = $start->diffInSeconds(now());
                if ($executionTime !== $time && ($time - $executionTime) % 5 === 0) {
                    $this->comment("Processing... ($time seconds)");
                    $executionTime = $time;
                }
            }
        }
    }
}
