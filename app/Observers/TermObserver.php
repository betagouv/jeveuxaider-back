<?php

namespace App\Observers;

use App\Models\Mission;
use App\Models\Term;
use Illuminate\Database\Eloquent\Builder;

class TermObserver
{
    /**
     * Handle the Term "created" event.
     *
     * @param  \App\Models\Term  $term
     * @return void
     */
    public function created(Term $term)
    {
        //
    }

    /**
     * Handle the Term "updated" event.
     *
     * @param  \App\Models\Term  $term
     * @return void
     */
    public function updated(Term $term)
    {
        $changes = $term->getChanges();
        if($term->vocabulary->slug == 'missions') {
            if (isset($changes['name']) || isset($changes['is_published'])) {
                $term->missions()->with(['structure'])->searchable();
            }
        }
    }

    /**
     * Handle the Term "deleted" event.
     *
     * @param  \App\Models\Term  $term
     * @return void
     */
    public function deleted(Term $term)
    {
        //
    }

    /**
     * Handle the Term "restored" event.
     *
     * @param  \App\Models\Term  $term
     * @return void
     */
    public function restored(Term $term)
    {
        //
    }

    /**
     * Handle the Term "force deleted" event.
     *
     * @param  \App\Models\Term  $term
     * @return void
     */
    public function forceDeleted(Term $term)
    {
        //
    }
}
