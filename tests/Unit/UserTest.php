<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_user_has_structures()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(Collection::class, $user->structures);
    }

    /** @test */
    public function a_user_has_missions()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(Collection::class, $user->missions);
    }
}
