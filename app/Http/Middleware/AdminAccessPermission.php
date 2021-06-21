<?php

namespace App\Http\Middleware;

use App\Codes\Models\Role;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class AdminAccessPermission
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
        return $next($request);

        session()->flash('message', __('general.no_permission'));
        session()->flash('message_alert', 1);

        return redirect()->route('admin.profile');
    }
}
