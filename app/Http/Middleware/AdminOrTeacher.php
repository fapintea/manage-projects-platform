<?php

namespace Diploma\Http\Middleware;

use Closure;
use Auth;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class AdminOrTeacher
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
        if (Auth::check() && !Auth::user()->isAdmin() && 
            !Auth::user()->isTeacher() && !Auth::user()->isSuperAdmin())
            return redirect('home');

        return $next($request);
    }
}
