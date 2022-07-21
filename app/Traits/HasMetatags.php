<?php

namespace App\Traits;

use App\Models\Metatag;

trait HasMetatags
{
    public function metatags()
    {
        return $this->morphOne(Metatag::class, 'metatagable');
    }

    public function delete()
    {
        $res = parent::delete();
        if ($res == true && $this->metatags) {
            $this->metatags->delete();
        }
    }
}
