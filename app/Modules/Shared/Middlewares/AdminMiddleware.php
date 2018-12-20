<?php

namespace App\Modules\Shared\Middlewares;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->is_admin !== 1) {
            return redirect('/home');
        }
        return $next($request);
    }
}
