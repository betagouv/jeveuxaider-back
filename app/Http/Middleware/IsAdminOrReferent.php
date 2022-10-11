<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class IsAdminOrReferent
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

        $roles = [
            'admin',
            'referent',
        ];

        if (! $request->hasHeader('Context-Role') || ! in_array($request->header('Context-Role'), $roles)) {
            return new Response(['message' => "Vous n'avez pas les droits administrateurs nécéssaires"], 401); 
       }

        if (array_search($request->header('Context-Role'), array_column($request->user()->roles, 'key')) === false) {
            return new Response(['message' => "Vous n'avez pas les droits administrateurs nécéssaires"], 401);
        }

        return $next($request);
    }
}
