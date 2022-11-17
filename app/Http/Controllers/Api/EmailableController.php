<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Emailable;

class EmailableController extends Controller
{
    public function verify(String $email)
    {
        return Emailable::verify($email);
    }
}
