<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 22/01/2018
 * Time: 4:55 PM
 */

namespace Kevupton\Referrals\Middleware;

class GetReferrer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle ($request, \Closure $next)
    {
        if ($request->has('refcode')) {
            referrals()->setToken($request->get('refcode'));
            return redirect($request->url());
        }

        return $next($request);
    }
}