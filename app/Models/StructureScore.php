<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StructureScore extends Model
{
    protected $table = 'score_structure';
    protected $primaryKey = 'structure_id';
    protected $guarded = [];
    public $incrementing = false;
    protected $appends = ['average_testimony_grade'];

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure', 'structure_id', 'id');
    }

    public function getTotalPointsAttribute($value)
    {
        return round($value);
    }

    public function getReactivityPointsAttribute($value)
    {
        return round($value);
    }

    public function getEngagementPointsAttribute($value)
    {
        return round($value);
    }

    public function getBonusPointsAttribute($value)
    {
        return round($value);
    }

    public function getProcessedParticipationsRateAttribute($value)
    {
        return round($value);
    }

    public function getAverageTestimonyGradeAttribute()
    {
        return round(Temoignage::ofStructure($this->structure->id)->avg('grade'), 1);
    }
}
