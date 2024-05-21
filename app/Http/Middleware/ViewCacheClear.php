<?php

namespace App\Http\Middleware;
use Artisan;
use Closure;
use Illuminate\Http\Request;

class ViewCacheClear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('APP_ENV') === 'local') {
            Artisan::call('view:clear');
        }

        return $next($request);
    }
}
