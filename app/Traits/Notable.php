<?php

namespace App\Traits;

use App\Models\Note;

trait Notable
{

    public function notes()
    {
        return $this->morphMany(Note::class, 'notable')->orderByDesc('created_at');
    }

    public function createNote($attributes)
    {

        $note = new Note([
            'content' => $attributes['content'],
            'context' => $attributes['context'],
            'parent_id' => isset($attributes['parent_id']) ? $attributes['parent_id'] : null,
            'user_id' => $attributes['user_id'],
            'notable_id' => $this->getKey(),
            'notable_type' => get_class(),
        ]);

        return $this->notes()->save($note);
    }

}
