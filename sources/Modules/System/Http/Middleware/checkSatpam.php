<?php

namespace Modules\System\Http\Middleware;

use Closure, Session, Crypt;

class checkSatpam
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        $url = Crypt::encrypt($request->path());
        if (!Session::has('session')) {
            return redirect('security/login/'.$url);
        }else{
            $session = Session::get('session');
            if ($session['user_bagian'] != 'satpam') {
                return redirect('security/login/'.$url);
            }
        }
        
        return $next($request);
    }
}
