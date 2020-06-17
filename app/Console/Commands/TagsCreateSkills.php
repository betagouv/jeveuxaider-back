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
        $skillGroups = $this->fetchSkills();

        foreach ($skillGroups as $group => $skills) {
            foreach ($skills as $skill) {
                $tag = Tag::whereName($skill)->whereGroup($group)->first();
                if (!$tag) {
                    Tag::create([
                        'name'=> $skill,
                        'group' => $group,
                        'type' => 'competence'
                    ]);
                    $this->info($skill . ' has been created.');
                } else {
                    $this->warn($tag->name . ' already exists !');
                }
            }
        }
    }

    protected function fetchSkills()
    {
        return [
            'agriculture' => [
                'Agriculture 1',
                'Agriculture 2',
                'Agriculture 3',
                'Agriculture 4',
            ],
            'sante' => [
                'Santé 1',
                'Santé 2',
                'Santé 3',
                'Santé 4',
            ],
        ];
    }
}
