<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function view(User $user, Note $note)
    {
        if (request()->header('Context-Role') == 'referent') {
            $note->load('notable');
            $user->load('profile');
            return $note->notable->department == $user->profile->referent_department;
        }

        return false;
    }

    public function create(User $user)
    {
        if (request()->header('Context-Role') == 'referent') {
            return true;
        }

        return false;
    }

    public function update(User $user, Note $note)
    {
        if (request()->header('Context-Role') == 'referent') {
            return $note->user_id == $user->id;
        }

        return false;
    }

    public function delete(User $user, Note $note)
    {
        return false;
    }
}
