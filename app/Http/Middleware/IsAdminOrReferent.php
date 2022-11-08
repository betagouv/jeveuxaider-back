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
        if (! $request->hasHeader('Context-Role') || ! in_array($request->header('Context-Role'), ['admin', 'referent'])) {
            return new Response(['message' => "Vous n'avez pas les droits administrateurs nécéssaires"], 401); 
       }

        if ($request->user()->hasRole('admin') || $request->user()->hasRole('referent')) {
            return $next($request);
        }

        return new Response(['message' => "Vous n'avez pas les droits administrateurs nécéssaires"], 401);
    }
}
