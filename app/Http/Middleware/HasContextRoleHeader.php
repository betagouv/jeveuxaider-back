<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class HasContextRoleHeader
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
            'volontaire',
            'admin',
            'referent',
            'referent_regional',
            'responsable',
            'responsable_territoire',
            'tete_de_reseau',
        ];

        if (! $request->hasHeader('Context-Role') || ! in_array($request->header('Context-Role'), $roles)) {
            return new Response("Missing or wrong 'Context-Role' header", 401);
        }

        if (array_search($request->header('Context-Role'), array_column($request->user()->roles, 'key')) === false) {
            return new Response("Missing or wrong 'Context-Role' header", 401);
        }

        // if ($request->user()->profiles->roles[$request->header('Context-Role')] !== true) {
        //     return new Response("Missing or wrong 'Context-Role' header", 401);
        // }

        return $next($request);
    }
}
