<?php
namespace Modules\System\Http\Controllers\Privileges;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;

use Modules\System\Models\Privileges\modelmenu, 
    Modules\System\Models\Privileges\modelgroup_access,
    Modules\System\Models\Privileges\modelprivilege,
    Modules\System\Models\Privileges\modelrole_access,
    Modules\System\Models\modelsystem;

class group_access extends Controller{
    public function __construct(){
        $this->middleware('checklogin');
        $this->micro = microtime(true);
    }

    public function index(){
        $data = array(
            'title'     => 'Group Access Management',
            'menu'      => '',

            'group_access_data'     => modelgroup_access::with(['role_access', 'privilege'])->get(),
        );
        return view('system::settings/privileges/group_access_list', $data);
    }

    public function checkgroupaccess(Request $get){
        $group_access = modelgroup_access::where('group_access_name', 'LIKE', '%'.$get->group_access_name.'%')
                                            ->count();

        if ($group_access > 0) {
            return 1;
        } else {
            return 0;
        }
        
    }

    public function create(){
        $group_access_id = $this->getLastID('group_access');
        $data = array(
            'title'     => 'Create Group Access',
            'menu'      => '',

            'action'    => 'createaction',
            'system_data'  => modelsystem::with([
                                    'menu' => function($q){
                                        $q->where('menu_is_active', '=', '1');
                                    }
                                ])
                                ->whereHas('menu', function($q){
                                    $q->where('menu_is_active', '=', '1');
                                    // $q->orderBy('menu_system_id', 'ASC');
                                })
                                ->where('system_id', '!=', '1')
                                ->get(),
            'tools_data'    => modelmenu::where('menu_system_id', '=', '1')
                                            ->where('menu_is_active', '=', '1')
                                            ->get(),

            'group_access_id'   => '',
            'group_access_name' => '',
            'role_access'       => '',
        );

        return view('system::settings/privileges/group_access_form', $data);
    }

    public function createaction(Request $post){
        // return $post;
        $group_access_id = $this->getLastID('group_access');

        // Insert into Group Access
        $group_access = array(
            'group_access_id'   => $group_access_id,
            'group_access_name' => $post->group_access_name,
        );
        modelgroup_access::create($group_access);
        \LogActivity::create('group_access', $group_access_id, $this->micro);

        // Insert Menu into Role Access
        foreach ($post->menu as $key => $value) {
            $data = array(
                'role_access'           => $post->menu_role[$key],
                'role_access_menu_id'   => $key,
                'role_access_group_access_id'   => $group_access_id,
            );
            modelrole_access::create($data);
            $role_access = modelrole_access::where($data)->first();
            \LogActivity::create('role_access', $role_access->role_access_id, $this->micro);
        }

        Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        return redirect('privilege/group_access');
    }

    public function update($group_access_id){
        $group_access_id = Crypt::decrypt($group_access_id);
        $group_access   = modelgroup_access::find($group_access_id);
        $role_access    = modelrole_access::with('menu')->where('role_access_group_access_id', '=', $group_access_id)->get();

        $role_data = null;
        foreach ($role_access as $key => $value) {
            $role_data[$value->role_access_menu_id]['role_access_id']       = $value->role_access_id;
            $role_data[$value->role_access_menu_id]['role_access']          = $value->role_access;
            $role_data[$value->role_access_menu_id]['role_access_menu_id']  = $value->role_access_menu_id;
        }

        // return $role_data;

        $data = array(
            'title'     => 'Create Group Access',
            'menu'      => '',

            'action'    => 'updateaction',
            'system_data'  => modelsystem::with('menu')
                                ->whereHas('menu', function($q){
                                    $q->orderBy('menu_system_id', 'ASC'); 
                                })
                                ->where('system_id', '!=', '1')
                                ->get(),

                                'system_data'  => modelsystem::with('menu')
                                ->whereHas('menu', function($q){
                                    $q->orderBy('menu_system_id', 'ASC'); 
                                })
                                ->where('system_id', '!=', '1')
                                ->get(),
            'tools_data'    => modelmenu::where('menu_system_id', '=', '1')->get(),

            'group_access_id'   => $group_access->group_access_id,
            'group_access_name' => $group_access->group_access_name,
            'role_access'   => $role_data,
        );

        return view('system::settings/privileges/group_access_form', $data);
    }
    public function updateaction(Request $post){
        // return $post;
        $group_access_id = Crypt::decrypt($post->group_access_id);
        $group_access = modelgroup_access::find($group_access_id);

        // Update into Group Access
        $group_access->group_access_name = $post->group_access_name;
        $group_access->save();
        \LogActivity::update('group_access', $group_access_id, $this->micro);

        // Delete Uncheck Menu
        $delete_menu = modelrole_access::whereNotIn('role_access_menu_id', array_keys($post->menu))
                                        ->where('role_access_group_access_id', '=', $group_access_id);
                                        // return array_keys($post->menu);
        $delete_menu->delete();
        // Update Menu into Role Access
        $update_menu = modelrole_access::select('role_access_id', 'role_access_menu_id')
                                        ->whereIn('role_access_menu_id', array_keys($post->menu))
                                        ->where('role_access_group_access_id', '=', $group_access_id)->get();
        $role_access_id = $post->role_access_id;
        $new_menu = $post->menu;
        foreach ($update_menu as $key => $value) {
            // If Exist do Update
            if (isset($role_access_id[$value->role_access_menu_id])) {
                $menu = modelrole_access::find($value->role_access_id);
                $menu->role_access           = $post->menu_role[$value->role_access_menu_id];
                $menu->save();
                
                \LogActivity::update('role_access', $value->role_access_id, $this->micro);
                
                unset($new_menu[$value->role_access_menu_id]);
            }
        }
        
        // Insert New Menu into Role Access
        foreach ($new_menu as $key => $value) {
            $data = array(
                'role_access'           => $post->menu_role[$key],
                'role_access_menu_id'   => $key,
                'role_access_group_access_id'   => $group_access_id,
            );
            modelrole_access::create($data);
            $role_access = modelrole_access::where($data)->first();
            \LogActivity::create('role_access', $role_access->role_access_id, $this->micro);
        }

        Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        return redirect('privilege/group_access/update/'.Crypt::encrypt($group_access_id));
    }

    public function delete($group_access_id){
        $group_access_id = Crypt::decrypt($group_access_id);
        // $privilege = modelprivilege::where('privilege_group_access', $group_access_id);


        $group_access = modelgroup_access::find($group_access_id);
        $role_access = modelrole_access::where('role_access_group_access_id', '=', $group_access_id);
        $role_access->delete();
        $group_access->delete();
        \LogActivity::delete('group_access', $group_access_id, $this->micro);
        
        Session::flash('toast', 'toast("success", "Berhasil dihapus.")');
        return redirect('privilege/group_access');
    }

    // Last ID
    public function getLastID($table){
        $last_ai = DB::select('
            SELECT `AUTO_INCREMENT`
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = "'.DB::connection()->getDatabaseName().'"
            AND TABLE_NAME = "'.$table.'"
        ');
        return $last_ai[0]->AUTO_INCREMENT;
    }
}
