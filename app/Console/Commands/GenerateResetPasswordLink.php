<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;

class GenerateResetPasswordLink extends Command
{
    protected $signature = 'reset:password {userId : The ID of the user}';

    protected $description = 'generate password reset link for a given user.';

    public function handle()
    {
        $userId = $this->argument('userId');
        $user = \App\Models\User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found.");
            return;
        }

        $token = Password::createToken($user);
        $url = config('app.front_url') . '/password-reset/' . $token . '?email=' . $user->email;
        $this->output->info("Password reset link: {$url}");
    }
}
