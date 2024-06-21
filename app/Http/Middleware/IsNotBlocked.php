<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class IsNotBlocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if ($user->isBlocked()) {
            return new Response(['message' => "Une erreur est survenue"], 401);
        }

        return $next($request);
    }
}
