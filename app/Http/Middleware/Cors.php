<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin', config('app.front_url'));
        $response->headers->set('Access-Control-Allow-Methods', ['GET', 'POST', 'PUT', 'OPTIONS', 'DELETE']);
        $response->headers->set('Access-Control-Allow-Headers', ['X-REQUESTED-WITH', 'CONTENT-TYPE', 'Authorization', 'Context-Role', 'Impersonating']);

        return $response;
    }
}
