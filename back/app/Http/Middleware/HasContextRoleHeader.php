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
            'admin',
            'referent',
            'referent_regional',
            // 'superviseur',
            'responsable',
            'responsable_collectivity',
            'tete_de_reseau',
            'analyste',
        ];

        if (!$request->hasHeader('Context-Role') || !in_array($request->header('Context-Role'), $roles)) {
            return new Response("Missing or wrong 'Context-Role' header", 401);
        }

        if ($request->user()->profile->roles[$request->header('Context-Role')] !== true) {
            return new Response("Missing or wrong 'Context-Role' header", 401);
        }

        return $next($request);
    }
}
