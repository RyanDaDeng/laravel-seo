<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class LogLastActivity
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

        if(\Auth::check()) {
            \Cache::forever('last_activity_record', [
                'last_activity_time' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'user_name' => \Auth::user()->name,
            ]);
        }

        return $next($request);
    }
}
