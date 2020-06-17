<?php

namespace App\Console\Commands;

use App\Models\Tag;
use Illuminate\Console\Command;

class TagsCreateSkills extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tags:create-skills';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create skills tags';

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
        $records = file(storage_path('app/skills.csv'), FILE_IGNORE_NEW_LINES);

        foreach ($records as $rows) {
            $skill =  explode(';', $rows);

            $tag = Tag::findFromString($skill[1], 'competence');
            if (!$tag) {
                Tag::create([
                    'name'=> $skill[1],
                    'group' => $skill[0],
                    'type' => 'competence'
                ]);
                $this->info($skill[1] . ' has been created.');
            } else {
                $this->warn($tag->name . ' already exists !');
            }
        }
    }
}
