<?php

namespace Modules\System\Http\Middleware;

use Closure, Session;
use Modules\System\Models\modelsystem,
    Modules\System\Models\modelfactory,
    Modules\System\Models\Privileges\modelrole_access,
    Modules\System\Models\Privileges\modelgroup_access,
    Modules\System\Models\modelprivilege;

class checklogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        if(!Session::get('session')){
            Session::flash('alert', 'sweetAlert("warning", "Please login to access")');
            return redirect('login');
        }
        $ses = Session::get('session');
        $user = $ses['user_nik'];
// dd($ses);
        $pri = modelprivilege::where('privilege_user_nik',$user)->first();
        if($pri==null){
            Session::flash('alert', 'sweetAlert("warning", "Please login to access")');
            return redirect('login');
        }

        if($pri->privilege_hrips=='N' AND $pri->kode_validate=='N'){
            return redirect()->route('aktifasiuser');
        }

        if($pri->privilege_hrips=='N' AND $pri->kode_validate=='Y' AND $pri->coc=='N'){
            return redirect()->route('validasicoc');
        }

        
        return $next($request);
    }
}
