<?php
namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Crypt, DB, File;

use Modules\System\Models\modelfactory,
    Modules\System\Models\modelsbu,
    Modules\System\Models\modelsecurity,
    Modules\System\Models\modelprivilege;
use Modules\Car\Models\modelcar;
use Modules\Sisbook\Models\modeldriver,
    Modules\Sisbook\Models\modelroom,
    Modules\Sisbook\Models\modelinn,
    Modules\Sisbook\Models\modelmess;

class factory extends Controller{
    public function __construct(){
        $this->middleware('checklogin');
    }

    public function index(){
        $data = array(
            'title'     => 'Factory Management',
            'menu'      => '',

            'factory_data' => modelfactory::paginate(10),
        );
        return view('system::settings/factory/factory_list', $data);
    }

    public function create(){
        $data = array(
            'title'     => 'Tambah Factory',
            'menu'      => '',

            'action'    => url('factory/createaction'),

            'factory_id'                => '',
            'factory_code'              => '',
            'factory_name'              => '',
            'factory_company_name'      => '',
            'factory_company_address'   => '',
            'factory_logo'              => '',
            'factory_email'             => ''
        );
        return view('system::settings/factory/factory_form', $data);
    }

    public function createaction(Request $post){
        $check = modelfactory::where(function($q) use($post){
                                $q->where('factory_code', '=', $post->factory_code);
                                $q->orWhere('factory_name', '=', $post->factory_name);
                            })
                            ->count();

        if ($check > 0) {
            Session::flash('toast', 'sweetAlert("warning", "Kode / Nama sudah ada")');
            return redirect()->back();
        }

        $file       = $post->file('factory_logo');
        $file_name  = $post->factory_code.'_'.date('d_M_Y').'.'.$file->getClientOriginalExtension();
        
        if ($file->move('public/uploads/images/factory', $file_name)) {
            $data = array(
                'factory_id'                => $post->factory_id,
                'factory_code'              => $post->factory_code,
                'factory_name'              => $post->factory_name,
                'factory_company_name'      => $post->factory_company_name,
                'factory_company_address'   => $post->factory_company_address,
                'factory_logo'              => $file_name,
            );

            $factory = modelfactory::create($data);
            \LogActivity::create('factory', $factory->factory_id, microtime(true));
            Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        }else{
            Session::flash('toast', 'sweetAlert("error", "Gagal, gambar gagal diupload.")');
        }

        return redirect('factory');
    }

    public function update($factory_id){
        $factory_id = Crypt::decrypt($factory_id);
        $factory = modelfactory::find($factory_id);

        $data = array(
            'title'     => 'Ubah Factory',
            'menu'      => '',

            'action'    => url('factory/updateaction'),
            
            'factory_id'                => $factory->factory_id,
            'factory_code'              => $factory->factory_code,
            'factory_name'              => $factory->factory_name,
            'factory_company_name'      => $factory->factory_company_name,
            'factory_company_address'   => $factory->factory_company_address,
            'factory_logo'              => $factory->factory_logo,
            'factory_email'             => $factory->factory_email
        );
        return view('system::settings/factory/factory_form', $data);
    }

    public function updateaction(Request $post){
        // return $post->factory_logo;
        $factory_id = Crypt::decrypt($post->factory_id);
        $check = modelfactory::where(function($q) use($post){
                                $q->where('factory_code', '=', $post->factory_code);
                                $q->orWhere('factory_name', '=', $post->factory_name);
                            })
                            ->where('factory_id', '!=', $factory_id)
                            ->count();
        if ($check > 0) {
            Session::flash('toast', 'toast("warning", "Kode / Nama sudah ada")');
            return redirect()->back();
        }

        $factory = modelfactory::find($factory_id);

        if (isset($post->factory_logo)) {
            $file       = $post->file('factory_logo');
            $file_name  = $post->factory_code.'_'.date('d_M_Y').'.'.$file->getClientOriginalExtension();
            // Upload New File
            $file->move('public/uploads/images/factory/', $file_name);

            // Delete Old File
            if (isset($factory->factory_logo)) {
                File::delete('public/uploads/images/factory/'.$factory->factory_logo);
            }

            //Save to DB
            $factory->factory_logo      = $file_name;
        }

        $factory->factory_code              = $post->factory_code;
        $factory->factory_name              = $post->factory_name;
        $factory->factory_company_name      = $post->factory_company_name;
        $factory->factory_company_address   = $post->factory_company_address;
        $factory->factory_email             = $post->factory_company_email;
        if($factory->isDirty()){
            $factory->save();
            \LogActivity::update('factory', $factory->factory_id, microtime(true));
            Session::flash('toast', 'toast("success", "Berhasil diubah.")');
        }else{
            Session::flash('toast', 'toast("success", "Tidak ada data yang diubah.")');
        }
        
        return redirect('factory');
    }

    public function delete($factory_id){
        $factory = modelfactory::find(Crypt::decrypt($factory_id));

        // Check all relations
        $sbu    = modelsbu::where('sbu_location', $factory->factory_name)->count();
        $security   = modelsecurity::where('security_location', $factory->factory_name)->count();
        $privilege  = modelprivilege::where('privilege_user_nik', 'like', $factory->factory_code.'%')->count();
        $car    = modelcar::where('car_location', $factory->factory_name)->count();
        $driver = modeldriver::where('driver_location', $factory->factory_name)->count();
        $room   = modelroom::where('room_location', $factory->factory_name)->count();
        $inn    = modelinn::where('inn_location', $factory->factory_name)->count();
        $mess   = modelmess::where('mess_location', $factory->factory_name)->count();

        $total  = $sbu + $security + $privilege + $car + $driver + $room + $inn + $mess;
        if ($total > 0) {
            Session::flash('toast', 'sweetAlert("warning", "Gagal, factory digunakan.")');
        }else{
            File::delete('public/uploads/images/factory/'.$factory->factory_logo);
            $factory->delete();

            Session::flash('toast', 'toast("success", "Berhasil dihapus.")');
        }

        return redirect('factory');
    }

    function fetch_data(Request $request){
        if($request->ajax()){
            $sort_by = $request->get('sortby');
            $sort_type = $request->get('sorttype');
            $query = $request->get('query');
            $query = str_replace(" ", "%", $query);
            $factory_data = DB::table('factory')
                        ->where('factory_id', 'like', '%'.$query.'%')
                        ->orWhere('factory_name', 'like', '%'.$query.'%')
                        ->orderBy($sort_by, $sort_type)
                        ->paginate(10);

            return view('system::settings/factory/pagination_data', compact('factory_data'))->render();
        }
    }
}
