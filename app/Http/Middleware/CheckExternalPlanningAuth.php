<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckExternalPlanningAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\never
     */
    public function handle(Request $request, Closure $next)
    {
        if(isset($_COOKIE[$request->route()->parameter('department').'CaAuth']))
        {
            return $next($request);
        }
        else
        {
            $department = $request->route()->parameter('department');
            try {
                if (DB::connection('mysql_' . $department)->getPdo()) {
                    return redirect(route('department.auth.login', $request->route()->parameter('department')));
                }
                else{
                    return abort(404);
                }
            } catch (\Exception $e) {
                return abort(404);
            }

        }



    }
}
