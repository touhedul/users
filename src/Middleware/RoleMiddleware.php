<?php

namespace Properos\Users\Middleware;

use Auth;

use Closure;
use Properos\Base\Classes\Api;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $roles = explode('|', $role);

        if (Auth::guest()) {
            return redirect('/');
        }

        if (count($roles) > 0) {
            if (!$request->user()->hasAnyRole($roles)) {
                foreach ($roles as $key => $_role) {
                    if ($request->user()->isRole($_role)) {
                        return $next($request);
                    }
                }
                if($request->ajax()){
                    return Api::error('403', ['Access Denied/Forbidden!']);
                }else{
                    abort(403);
                }
            }
        }

        /* if (!$request->user()->can($permission)) {
            abort(403);
        } */

        return $next($request);
    }
}
