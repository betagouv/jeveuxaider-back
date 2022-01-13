<?php

namespace App\Observers;

use App\Models\Reseau;

class ReseauObserver
{

    public function saving(Reseau $reseau)
    {
        $reseau->saveMissingFields();
    }

}
