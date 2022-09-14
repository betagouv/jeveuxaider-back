<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;

class HasApiKey
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
        if (! $key = $request->get('apikey') or $key !== config('app.api_key')) {
            return new JsonResponse('Wrong apikey argument', 401);
        }

        return $next($request);
    }
}
