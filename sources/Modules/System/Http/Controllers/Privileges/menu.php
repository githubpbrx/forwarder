<?php
namespace Modules\System\Http\Controllers\Privileges;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;

use Modules\System\Models\Privileges\modelmenu,
    Modules\System\Models\Privileges\modelrole_access,
    Modules\System\Models\modelsystem;

class menu extends Controller{
    public function __construct(){
        $this->middleware('checklogin');
        $this->micro = microtime(true);
    }

    public function index(){
        $data = array(
            'title'     => 'Menu Management',
            'menu'      => '',

            'menu_data'     => modelmenu::with('system')->orderBy('menu_system_id')->get(),
            'system_data'   => modelsystem::all(),
        );
        return view('system::settings/privileges/menu_list', $data);
    }

    public function createaction(Request $post){
        $data = array(
            'menu_name'      => $post->menu_name,
            'menu_system_id' => $post->menu_system_id,
        );

        $chek = modelmenu::where('menu_system_id', '=', $post->menu_system_id)
                        ->where('menu_name', 'LIKE', '%'.$post->menu_name.'%')
                        ->count();
        if ($chek > 0) {
            Session::flash('toast', 'sweetAlert("warning", "Gagal, menu sudah ada")');
        } else {
            modelmenu::create($data);

            $menu = modelmenu::where($data)->first();
            \LogActivity::create('menu', $menu->menu_id, $this->micro);

            Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        }
        
        return redirect('privilege/menu');
    }

    public function updateaction(Request $post){
        $menu = modelmenu::find(Crypt::decrypt($post->menu_id));

        $chek = modelmenu::where('menu_system_id', '=', $post->menu_system_id)
                        ->where('menu_name', 'LIKE', '%'.$post->menu_name.'%')
                        ->count();
        if ($chek > 0) {
            Session::flash('toast', 'sweetAlert("warning", "Gagal, menu sudah ada")');
            return redirect()->back();
        } else {
            $menu->menu_name        = $post->menu_name;
            $menu->menu_system_id   = $post->menu_system_id;
            $menu->save();

            \LogActivity::update('menu', $menu->menu_id, $this->micro);

            Session::flash('toast', 'toast("success", "Berhasil diubah.")');
            return redirect('privilege/menu');
        }
    }

    public function delete($menu_id){
        $menu_id = Crypt::decrypt($menu_id);

        $check = modelrole_access::where('role_access_menu_id', '=', $menu_id)->get()->count();
        if ($check > 0) {
            Session::flash('toast', 'sweetAlert("warning", "Menu sedang digunakan.")');
        } else {
            $menu = modelmenu::find($menu_id);
            $menu->menu_is_active   = 0;
            $menu->save();

            \LogActivity::delete('menu', $menu->menu_id, $this->micro);
            Session::flash('toast', 'toast("success", "Berhasil diubah.")');
        }
        return redirect('privilege/menu');
    }

    public function active($menu_id){
        $menu = modelmenu::find(Crypt::decrypt($menu_id));
        $menu->menu_is_active   = 1;
        $menu->save();

        \LogActivity::update('menu', $menu->menu_id, $this->micro);
        Session::flash('toast', 'toast("success", "Berhasil diubah.")');
        return redirect('privilege/menu');
    }
}
