<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Document;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Document $document)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, Document $document)
    {
        return false;
    }

    public function delete(User $user, Document $document)
    {
        return false;
    }
}
