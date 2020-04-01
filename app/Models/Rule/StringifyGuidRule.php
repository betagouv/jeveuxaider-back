<?php

namespace App\Models\Rule;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StringifyGuidRule implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $hasPhp7GuidHelper = defined('\PDO::DBLIB_ATTR_STRINGIFY_UNIQUEIDENTIFIER');
        if ($hasPhp7GuidHelper):
            $model->getConnection()->getPdo()->setAttribute(\PDO::DBLIB_ATTR_STRINGIFY_UNIQUEIDENTIFIER, true);
        endif;
    }
}
