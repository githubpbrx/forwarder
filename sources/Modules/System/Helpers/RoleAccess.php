<?php
namespace Modules\System\Helpers;

use App\Http\Controllers\Controller, Session;
use Request;

class RoleAccess extends Controller{
    // Count Menu Access
    public static function whereMenuIn($id_menu){
        if (Session::has('privilege')) {
            $in = $id_menu;
            $privilege  = Session::get('privilege');
            $menu   = $privilege['menu'];
            $role   = $menu->whereIn('role_access_menu_id', $in)->count();
            if (isset($role)) {
                return $role;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    
    // return Role Access
    public static function whereMenu($id_menu){
        if (Session::has('privilege')) {
            $privilege  = Session::get('privilege');
            $menu   = $privilege['menu'];
            $role   = $menu->where('role_access_menu_id', '=', $id_menu)->first();

            if (isset($role->role_access)) {
                return $role->role_access;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

    // return Array Location
    public static function userLocation(){
        if (Session::has('privilege')) {
            $privilege  = Session::get('privilege');
            $role       = $privilege['location'];
            // dd($privilege);
            if (isset($role)) {
                return $role;
            }else{
                return [''];
            }
        }else{
            return [''];
        }
    }
}
