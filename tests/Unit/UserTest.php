<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

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
