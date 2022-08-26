<?php
namespace Modules\System\Http\Controllers\Privileges;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request, Session;
use Config, Storage, DB, Crypt;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Datatables;
use Modules\System\Models\modelsystem,
    Modules\System\Models\Privileges\modelrole_access,
    Modules\System\Models\Privileges\modelgroup_access,
    Modules\System\Models\modelfactory,
    Modules\System\Models\modelprivilege;

class privilege extends Controller{
    protected $ip_server;
       
    public function __construct(){
        $this->middleware('checklogin');
        $this->micro = microtime(true);
        $this->ip_server = config('api.url.ip_address');
    }
    
    public function index(){
        $data = array(
            'title' => 'Daftar User Akses', 
            'menu'  => '',
            'group_access_data'     => modelgroup_access::all(),
            'factory_data'          => modelfactory::whereNotNull('factory_code')->get(),
        );
        return view('system::settings/privileges/user_access_list_serverside', $data);
    }

    public function privilegedata(){
        $query = modelprivilege::with(['group_access'])
                            ->orderBy('privilege_user_nik', 'ASC')
                            ->get();

        return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('group_access', function($q){
                    if(isset($q->group_access->group_access_id)){
                        return  $q->group_access->group_access_name;
                    }else{
                        return  '<span class="badge bg-secondary">unknown</span>';
                    }
                })
                ->addColumn('user_location', function($q){
                    if(isset($q->privilege_user_location) || $q->privilege_user_location != ''){
                        $location = explode(',', $q->privilege_user_location);
                        $factory = '';
                        foreach ($location as $value) {
                            $factory .= ' <span class="badge bg-warning">'.$value.'</span>';
                        }
                        return $factory;
                    }else{
                        return  '<span class="badge bg-info">Not Set</span>';
                    }
                })
                ->addColumn('privilege_api_key', function($q){
                    if($q->privilege_api_key){
                        return $q->privilege_api_key;
                    } else {
                        return '<span class="badge bg-info">Not Set</span>';
                    }
                })
                ->addColumn('reset', function($q){
                    $pass   = '';
                    $qa     = '';
                    if (\RoleAccess::whereMenu(2) > 0 && \RoleAccess::whereMenu(2) < 3) {
                        $pass   = '<a data-toggle="tooltip" title="Reset Password" href="'.url("privilege/user_access/resetpassword/".$q->privilege_user_nik).'" onclick="return confirm(`Apakah anda yakin?`)"><i class="fas fa-key text-danger"></i></a>';
                        $qa     = '<a data-toggle="tooltip" title="Reset Pertanyaan Keamanan" href="'.url("privilege/user_access/resetqa/".$q->privilege_user_nik).'" onclick="return confirm(`Apakah anda yakin?`)"><i class="fas fa-question-circle text-primary"></i></a>';
                    }
                    
                    return $pass.' '.$qa;
                })
                ->addColumn('action', function($q){
                    $process    = '';
                    $delete     = '';

                    if (\RoleAccess::whereMenu(2) > 0 && \RoleAccess::whereMenu(2) < 3) {
                        $process    = '<a href="'.url("privilege/user_access/update/".Crypt::encrypt($q->privilege_id)).'"><i class="fas fa-edit text-orange"></i></a>';
                    }
                    
                    return $process.' '.$delete;
                })
                ->rawColumns(['group_access', 'privilege_api_key', 'user_location', 'reset', 'action'])
                ->make(true);
    }

    public function resetpassword($nik){
        app('Modules\System\Http\Controllers\login')->apiForgotPassword($nik, 'password123');

        \LogActivity::update('user [Reset Password]', $nik, $this->micro);
        Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        return redirect('privilege/user_access');
    }

    public function resetqa($nik){
        $data = array(
            'nik'   => $nik,
            'q1'    => '',
            'a1'    => '',
            'q2'    => '',
            'a2'    => '',
        );
        app('Modules\System\Http\Controllers\login')->apiInputQA($data);
        Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        return redirect('privilege/user_access');
    }

    public function getPrivilege($nik){
        $privilege      = modelprivilege::where('privilege_user_nik', '=', $nik)
                        ->first();
        if ($privilege){
            $role_access    = modelrole_access::where('role_access_group_access_id', '=', $privilege->privilege_group_access_id)
                                            ->get();
            if ($role_access) {
                $data = array(
                    'sistem'    => ',',
                    'location'  => explode(',', $privilege->privilege_user_location),
                    'menu'      => $role_access,
                );

                Session::put('privilege', $data);
            }else{
                Session::forget('privilege');
            }
        }else{
            Session::forget('privilege');
        }
    }

    public function update($privilege_id){
        $privilege_id = Crypt::decrypt($privilege_id);
        $privilege = modelprivilege::find($privilege_id);
        
        $data = array(
            'title' => 'Ubah User Akses', 
            'menu'  => '',

            'action'    => url('privilege/user_access/updateaction'),
            'group_access_data'     => modelgroup_access::all(),
            'factory_data'          => modelfactory::whereNotNull('factory_code')->get(),

            'privilege_id'          => $privilege->privilege_id,
            'privilege_user_nik'    => $privilege->privilege_user_nik,
            'privilege_user_name'   => $privilege->privilege_user_name,
            'privilege_user_location'   => $privilege->privilege_user_location,
            'privilege_group_access_id' => $privilege->privilege_group_access_id,
            'privilege_api_key' => $privilege->privilege_api_key,
        );

        return view('system::settings/privileges/user_access_form', $data);
    }

    public function updateaction(Request $post){
        $privilege_id = Crypt::decrypt($post->privilege_id);
        $privilege = modelprivilege::find($privilege_id);
        $privilege->privilege_group_access_id   = $post->privilege_group_access_id;
        $privilege->privilege_user_location     = implode(',', $post->privilege_user_location);
        $privilege->privilege_api_key   = $post->privilege_api_key;
        $privilege->save();

        \LogActivity::update('privilege', $privilege_id, $this->micro);
        Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        return redirect('privilege/user_access');
    }

    public function createsave(Request $request){
        $nik = $request->nik;
        $nama = $request->nama;
        $akses = $request->akses;
        $lokasi = $request->lokasi;
        $cekuser = modelprivilege::where('privilege_user_nik',$nik)->first();
        if($cekuser!=null){
            Session::flash('toast', 'toast("info", "User sudah ada, silahkan cek di list data user.")');
            return redirect()->back();
        }
        // dd(implode(",", $lokasi) ) ;
        if(count($lokasi)<=0){
            Session::flash('toast', 'toast("error", "Akses Lokasi belum dimasukkan silahkan masukkan akses Lokasi")');
            return redirect()->back();
        }
        $lok = implode(",", $lokasi);

        $in = modelprivilege::insert(['privilege_user_nik'=>$nik, 'privilege_user_name'=>$nama, 'privilege_user_location'=>$lok, 'privilege_group_access_id'=>$akses, 'created_at'=>date('Y-m-d H:i:s') ]);
        if($in){
            Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        }else{
            Session::flash('toast', 'toast("error", "Gagal disimpan.")');
        }
        return redirect()->back();
    }

    public function getnama(Request $req){
        $nik = $this->enkripsi($req->nik);
        $detail_url        = 'http://'.$this->ip_server.'/api/detail.php?n='.$nik;
        // dd($detail_url);
        $detail_client     = new Client();
        $detail_res        = $detail_client->get($detail_url);
        $detail_data_enkripsi = json_decode(base64_decode($detail_res->getBody()), TRUE);
        foreach ($detail_data_enkripsi as $key => $data_enkripsi) {
            $detail_data[$this->dekripsi($key)] = $this->dekripsi($data_enkripsi);
        }
        $jumlahdata= array_sum($detail_data);
        if($jumlahdata<=0){
            $nama = '';
        }else{
            $nama = $detail_data['a'];
        }

        echo $nama;
        // dd(array_sum($detail_data),$detail_data);
    }

    function dekripsi($input){
        $str = base64_decode($input);
        $str = $this->potongKarakterDepan(6, $str);
        $str = $this->potongKarakterBelakang(7, $str);
        $str1 = substr($str,0,1);
        $str2 = substr($str,7,999999);
        $str = $str1.$str2;
        $output = '';   
        for($i = 0; $i < strlen($str); $i++){
            $temp = ord($str[$i]);
            $temp = ($temp - 20) + 29;
            $temp = chr($temp);
            $output = $output . $temp;
        }
        
        return $output;
    }

   
    function potongKarakterBelakang($jml, $str){
        $str = (string)$str;
        $str = substr($str, 0, -$jml);
        return $str;
    }

    function potongKarakterDepan($jml, $str){
        $pjg = strlen($str);
        $str = (string)$str;
        $str = substr($str, $jml, $pjg);
        return $str;
    }

    function enkripsi($input){
        $output = '';

        for($i = 0; $i < strlen($input); $i++) 
        {
            $temp = ord($input[$i]);
            $temp = ($temp + 20) - 29;
            $temp = chr($temp);
            $output = $output . $temp;
        }

        $str1 = substr($output,0,1);
        $str2 = substr($output,1,999999);
        $str = $str1.mt_rand(100000, 999999).$str2;
        $str = rand(1111,9999). date('y') . $str . date('m') . rand(10101,99999);
        $str = base64_encode($str);

        return $str;
    }
}
