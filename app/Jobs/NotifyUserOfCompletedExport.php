<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\ExportReady;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NotifyUserOfCompletedExport implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $filePath;

    public function __construct(User $user, $filePath)
    {
        $this->user = $user;
        $this->filePath = $filePath;
    }

    public function handle()
    {
        $this->user->notify(new ExportReady(
            $this->user,
            Storage::disk('s3')->temporaryUrl($this->filePath, now()->addHours(12))
        ));
    }
}
