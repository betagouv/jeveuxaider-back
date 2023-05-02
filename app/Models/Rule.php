<?php

namespace App\Models;

use App\Jobs\RuleMissionAttachTag;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Rule extends Model
{
    use LogsActivity;

    protected $table = 'rules';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'events' => 'array',
        'conditions' => 'json',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at', 'last_triggered_at', 'triggers_count'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function getTotalItemsCountAttribute()
    {
        $queryBuilder = $this->resolveQueryBuilder();
        return  $queryBuilder ?  $queryBuilder->count() : false;
    }

    protected function getPendingItemsCountAttribute()
    {
        $pendingItems = $this->pendingItems();

        return  $pendingItems ?  $pendingItems->count() : false;
    }

    public function scopeActive($query){
        return $query->where('is_active', 1);
    }

    protected function pendingItems()
    {
        $queryBuilder = $this->resolveQueryBuilder();

        if($this->action_key == 'mission_attach_tag') {
            $queryBuilder->whereDoesntHave('tags', function(Builder $query) {
                $query->where('id', $this->action_value);
            });
        }

        return  $queryBuilder ?  $queryBuilder->get() : false;
    }

    public function bulkExecute()
    {
        $this->pendingItems()->each(function($item) {
            if($this->action_key == 'mission_attach_tag') {
                RuleMissionAttachTag::dispatch($this, $item);
            }
        });
    }

    public function resolveQueryBuilder() {

        if(empty($this->conditions)){
            return false;
        }

        try {
            $queryBuilder = Mission::whereIn('state', ['Validée']);

            foreach ($this->conditions as $index => $groupCondition) {
                if ($index == 0) {
                    $queryBuilder->where(function ($query) use ($groupCondition) {
                        $this->buildConditions($query, $groupCondition['conditions'], $groupCondition['operator']);
                    });
                } else {
                    $operator = $groupCondition['operator'] == 'OR' ? 'orWhere' : 'where';
                    $queryBuilder->$operator(function ($query) use ($groupCondition) {
                        $this->buildConditions($query, $groupCondition['conditions'], $groupCondition['operator']);
                    });
                }
            }

            return $queryBuilder;
        } catch (Exception $e) {
            return false;
        }

    }

    protected function buildConditions($query, $conditions) {
        foreach ($conditions as $condition) {
            $query->where($condition['name'], $condition['operand'], $condition['value'], $condition['operator'] ?? 'AND');
        }
    }

}
