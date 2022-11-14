<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RemoveUserFromConversation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove-user-from-conversations {userId} {--excludeConversationIds=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove user from conversations
        {userId : the user to remove}
        {--excludeConversationIds : comma separated list of conversationIds}
    ';

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
        $user = User::find($this->argument('userId'));

        $query = Conversation::whereHas('users', function (Builder $subquery) use ($user) {
            $subquery->where('users.id', $user->id);
        });

        $excludeConversationIds = $this->option('excludeConversationIds');
        if (!empty($excludeConversationIds)) {
            if (!is_array($excludeConversationIds)) {
                $excludeConversationIds = explode(',', $excludeConversationIds);
            }
            if (count($excludeConversationIds) === 1 && Str::contains($excludeConversationIds[0], ',')) {
                $excludeConversationIds = explode(',', $excludeConversationIds[0]);
            }
            $query->whereNotIn('id', $excludeConversationIds);
        }

        if ($this->confirm($user->profile->full_name . ' will be removed from ' . $query->count() . ' conversations. Continue ?')) {
            $conversations = [];
            $bar = $this->output->createProgressBar($query->count());
            $bar->start();

            foreach ($query->cursor() as $conversation) {
                $conversations[] = $conversation->id;
                $conversation->users()->detach($user->id);
                $bar->advance();
            }

            $bar->finish();

            $this->info(PHP_EOL . PHP_EOL . $user->profile->full_name . ' has been removed from : ' . implode(',', $conversations));
        }
    }
}
