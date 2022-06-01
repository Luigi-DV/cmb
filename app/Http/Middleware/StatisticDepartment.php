<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StatisticDepartment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Application|Factory|View|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->session()->get('department')) {
            return $next($request);
        }
        else {
           return response()->view('app.department.statistics.select-department', [
               'url' => $request->url()
           ]);
        }
    }
}
