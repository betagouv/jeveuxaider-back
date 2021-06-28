<?php

namespace App\Console\Commands;

use App\Models\Document;
use Illuminate\Console\Command;

class DocumentsUpdateTypeToFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:update-type-to-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update documents type to file';

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
     * @return int
     */
    public function handle()
    {

        $query = Document::whereNull('type');
        $this->info($query->count() . ' document will be updated');

        if ($this->confirm('Do you wish to continue?')) {
            $query->update(['type' => 'file']);
        }
    }
}
