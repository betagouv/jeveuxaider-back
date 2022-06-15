<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\DB;

class LastOnlineAt
{
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return $next($request);
        }

        if(!$request->headers->has('Impersonating')){
            $last_online = new Carbon(auth()->user()->last_online_at);
            if (!auth()->user()->last_online_at || ($last_online->diffInMinutes(now()) > 5)) {
                DB::table("users")
                ->where("id", auth()->user()->id)
                ->update(["last_online_at" => now()]);
            }
        }

        return $next($request);
    }
}
