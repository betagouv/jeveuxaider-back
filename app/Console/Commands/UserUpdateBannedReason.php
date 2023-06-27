<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UserUpdateBannedReason extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-update-banned-reason';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the field banned_reason from not_regular_resident_or_younger_than_16 to not_regular_resident';

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
        $query = User::where('banned_reason', 'not_regular_resident_or_younger_than_16');
        if ($this->confirm('Update the field banned_reason from not_regular_resident_or_younger_than_16 to not_regular_resident ?')) {
            $query->update(['banned_reason' => 'not_regular_resident']);
        }
    }
}
