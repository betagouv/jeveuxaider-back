<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class StructureTag extends Model
{
    use LogsActivity;

    protected $table = 'structures_tags';

    protected $fillable = ['name', 'structure_id', 'is_generic'];

    protected $casts = [
        'is_generic' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

}
