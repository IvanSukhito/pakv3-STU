<?php

namespace App\Http\Middleware;

use App\Codes\Models\Area;
use Closure;

class AdminLogin
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
        if($request->session()->has(env('APP_NAME').'admin_id'))
        {
            return $next($request);
        }
        return redirect()->route('admin.logout');
    }
}
