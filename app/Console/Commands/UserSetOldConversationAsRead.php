<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class UserSetOldConversationAsRead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-set-old-conversations-as-read {--user=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Passe toutes les anciennes conversation d\'un utilisateur en lu';

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
        if (empty($options['user'])) {
            $this->error('Mandatory argument: --user');
            return;
        }

        $user = User::find($options['user']);
        $conversationsQuery = $user->conversations()
            ->where('conversations.updated_at', '<=', new Carbon('-2 months'))
            ->whereHas('users', function (Builder $query) use ($user) {
                $query
                    ->where(function ($query) {
                        $query->whereRaw('conversations_users.read_at < conversations.updated_at')
                            ->orWhere('conversations_users.read_at', null);
                    })
                    ->where('conversations_users.user_id', $user->id)
                    ->where('conversations_users.status', true);
            });

        if ($this->confirm($conversationsQuery->count() . " conversation(s) vont passer en lues. Continuer ?")) {
            foreach ($conversationsQuery->get() as $conversation) {
                $user->conversations()->updateExistingPivot($conversation->id, [
                    'read_at' => Carbon::now(),
                ]);
            }
        }
    }
}
