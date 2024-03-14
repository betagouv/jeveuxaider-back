<?php

namespace App\Jobs;

use App\Models\Profile;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class ProfileFaker implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $profile;
    protected $faker;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->faker = Factory::create('fr_FR');
        $this->onQueue('low-tasks');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!in_array(config('app.env'), ['local', 'staging'])) {
            return;
        }

        if ($this->profile->user->hasRole('admin')) {
            return;
        }

        $firstname = $this->faker->firstName;
        $lastname = $this->faker->lastName;
        $email = Str::slug($firstname . '-' . $lastname) . '-' . $this->profile->id . '@fake.test';

        $this->profile->timestamps = false;
        $this->profile->user->timestamps = false;

        $this->profile->first_name = $firstname;
        $this->profile->last_name = $lastname;

        $this->profile->user->email = $email;
        $this->profile->user->name = $email;
        $this->profile->email = $email;

        $this->profile->birthday = $this->faker->dateTimeBetween('-70 years', '-16 years');

        $this->profile->mobile = $this->faker->numerify('##########');
        $this->profile->phone = null;

        $this->profile->avatar?->delete();

        $this->profile->saveQuietly();
        $this->profile->user->saveQuietly();
    }
}
