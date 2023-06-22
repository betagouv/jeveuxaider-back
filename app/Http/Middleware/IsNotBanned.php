<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class IsNotBanned
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
        if ($user->banned_at) {
            return new Response(['message' => "Vous avez été désinscrit de la plateforme JeVeuxAider.gouv.fr car vous ne répondez pas aux conditions d’éligibilité."], 401);
        }

        return $next($request);
    }
}
