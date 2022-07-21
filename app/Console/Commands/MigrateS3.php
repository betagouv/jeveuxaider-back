<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MigrateS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's3:migrate
    {source : Name of the Filesystem disk you want to copy from}
    {destination : Name of the Filesystem disk you want to copy to}
    {--d|delete : Delete files on destination disk which aren\'t on the source disk}
    {--o|overwrite : If files already exist on destination disk, overwrite them instead of skip}
    {--l|log : Log all actions into Laravel log}
    {--O|output : Output all actions}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all files from S3 to other S3';

    protected $log = [];

    protected $count = ['copied' => 0, 'skipped' => 0, 'deleted' => 0, 'file_not_exist'];

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
        $this->info('Copying...');
        $source = $this->argument('source');
        $sourceFiles = Storage::disk($source)->allFiles();
        $count = count($sourceFiles);
        $progress = $this->output->createProgressBar($count);
        $progress->start();
        $destination = $this->argument('destination');
        $destinationFiles = Storage::disk($destination)->allFiles();

        // Delete files in destination which aren't in source
        // if ($this->option('delete')) {
        //     if ($difference = array_diff($destinationFiles, $sourceFiles)) {
        //         $count += count($difference);
        //         $progress->setMaxSteps($count);

        //         foreach ($difference as $file) {
        //             Storage::disk($destination)->delete($file);
        //             $this->countOutputLog('deleted', $file);
        //             $progress->advance();
        //         }
        //     }
        // }

        foreach ($sourceFiles as $file) {
            if (env('MEDIA_DISK') == 'public') {
                $localFile = str_replace('public/production/', '', $file);
            } else {
                $localFile = $file;
            }

            // If file already exists in destination
            if (in_array($localFile, $destinationFiles)) {
                // Overwrite file if argument is present
                if ($this->option('overwrite')) {
                    $content = Storage::disk($source)->get($file);
                    if ($content) {
                        $visibility = Storage::disk($source)->getVisibility($file);
                        Storage::disk($destination)->put($file, $content, $visibility);
                        $this->countOutputLog('copied', $file);
                    } else {
                        ray('file not exist', $file);
                        ray('content', $content);
                        $this->countOutputLog('file_not_exist', $file);
                    }
                } else { // Skip file
                    $this->countOutputLog('skipped', $file);
                }
            } else {
                $visibility = Storage::disk($source)->getVisibility($file);
                $content = Storage::disk($source)->get($file);

                if (env('MEDIA_DISK') == 'public') {
                    $file = str_replace('public/production/', '', $file);
                }

                Storage::disk($destination)->put($file, $content, $visibility);
                $this->countOutputLog('copied', $file);
            }

            $progress->advance();
        }

        $progress->finish();
        $this->info("\nDone! {$this->count['copied']} files copied, {$this->count['skipped']} files skipped, {$this->count['deleted']} files deleted.");
    }

    public function countOutputLog($action, $file)
    {
        $this->count[$action]++;
        $this->log[$action][] = $file;

        $this->info("\n".strtoupper($action).": $file");
        // Log::debug(strtoupper($action).": $file");
    }
}
