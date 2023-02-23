<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;


trait HasCompositePrimaryKey
{
    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey()
    {
        $fields = $this->getKeyName();
        $keys = [];
        array_map(function ($key) use (&$keys) {
            $keys[] = $this->getAttribute($key);
        }, $fields);
        return $keys;
    }

    protected function setKeysForSaveQuery($query)
    {
        $keys = $this->getKeyName();
        return !is_array($keys) ? parent::setKeysForSaveQuery($query) : $query->where(function ($q) use ($keys) {
            foreach ($keys as $key) {
                $q->where($key, '=', $this->getAttribute($key));
            }
        });
    }

    public static function find(array $ids)
    {
        $modelClass = self::class;
        $model = new $modelClass();
        $keys = $model->primaryKey;
        $query = $model->where(function ($query) use ($ids, $keys) {
            foreach ($keys as $key) {
                if (isset($ids[$key])) {
                    $query->where($key, $ids[$key]);
                } else {
                    $query->whereNull($key);
                }
            }
        });

        return $query->first();
    }

    public function findOrFail(array $ids)
    {
        if (!isset($this)) {
            $modelClass = self::class;
            $model = new $modelClass();
        } else {
            $model = $this;
        }
        $record = $model->find($ids);
        if (!$record) {
            throw new ModelNotFoundException;
        }
        return $record;
    }
}
