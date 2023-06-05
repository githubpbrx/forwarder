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

class privilege extends Controller
{
    protected $ip_server;

    public function __construct()
    {
        $this->middleware('checklogin');
        $this->micro = microtime(true);
        $this->ip_server = config('api.url.ip_address');
    }

    public function index()
    {
        $data = array(
            'title' => 'Daftar User Akses',
            'menu'  => '',
            'group_access_data'     => modelgroup_access::all(),
            'factory_data'          => modelfactory::whereNotNull('factory_code')->get(),
        );
        return view('system::settings/privileges/user_access_list_serverside', $data);
    }

    public function add()
    {
        $data = array(
            'title' => 'Daftar User Akses',
            'menu'  => '',
            'group_access_data'     => modelgroup_access::all(),
            "token" => Hash::make('ittetapsemangant')
        );
        return view('system::settings/privileges/user_access_formadd', $data);
    }

    public function privilegedata()
    {
        $query = modelprivilege::with(['group_access'])
            ->where('privilege_aktif', 'Y')
            ->orderBy('privilege_user_nik', 'ASC')
            ->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('group_access', function ($q) {
                if (isset($q->group_access->group_access_id)) {
                    return  $q->group_access->group_access_name;
                } else {
                    return  '<span class="badge bg-secondary">unknown</span>';
                }
            })
            ->addColumn('user_location', function ($q) {
                if (isset($q->privilege_user_location) || $q->privilege_user_location != '') {
                    $location = explode(',', $q->privilege_user_location);
                    $factory = '';
                    foreach ($location as $value) {
                        $factory .= ' <span class="badge bg-warning">' . $value . '</span>';
                    }
                    return $factory;
                } else {
                    return  '<span class="badge bg-info">Not Set</span>';
                }
            })
            ->addColumn('jenis', function ($q) {
                if ($q->emailfinance == null || $q->nikfinance == null || $q->namafinance == null) {
                    return 'Internal';
                } else {
                    return 'External';
                    // return '<span class="badge bg-info">Not Set</span>';
                }
            })
            ->addColumn('email_finance', function ($q) {
                if ($q->emailfinance) {
                    return $q->emailfinance;
                } else {
                    return '<span class="badge bg-info">Not Set</span>';
                }
            })
            ->addColumn('nik_finance', function ($q) {
                if ($q->nikfinance) {
                    return $q->nikfinance;
                } else {
                    return '<span class="badge bg-info">Not Set</span>';
                }
            })
            ->addColumn('nama_finance', function ($q) {
                if ($q->namafinance) {
                    return $q->namafinance;
                } else {
                    return '<span class="badge bg-info">Not Set</span>';
                }
            })
            ->addColumn('reset', function ($q) {
                $pass   = '';
                $qa     = '';
                if (\RoleAccess::whereMenu(2) > 0 && \RoleAccess::whereMenu(2) < 3) {
                    $pass   = '<a data-toggle="tooltip" title="Reset Password" href="' . url("privilege/user_access/resetpassword/" . $q->privilege_user_nik) . '" onclick="return confirm(`Apakah anda yakin?`)"><i class="fas fa-key text-danger"></i></a>';
                    $qa     = '<a data-toggle="tooltip" title="Reset Pertanyaan Keamanan" href="' . url("privilege/user_access/resetqa/" . $q->privilege_user_nik) . '" onclick="return confirm(`Apakah anda yakin?`)"><i class="fas fa-question-circle text-primary"></i></a>';
                }

                return $pass . ' ' . $qa;
            })
            ->addColumn('action', function ($q) {
                $process    = '';
                $delete     = '';

                if (\RoleAccess::whereMenu(2) > 0 && \RoleAccess::whereMenu(2) < 3) {
                    $process    = '<a href="' . url("privilege/user_access/update/" . Crypt::encrypt($q->privilege_id)) . '"><i class="fas fa-edit text-orange"></i></a>';
                }

                return $process . ' ' . $delete;
            })
            ->rawColumns(['group_access', 'jenis', 'email_finance', 'nik_finance', 'nama_finance', 'user_location', 'reset', 'action'])
            ->make(true);
    }

    public function resetpassword($nik)
    {
        $cek =  modelprivilege::where('privilege_user_nik', $nik)->first();
        if ($cek->privilege_hrips == 'Y') {
            app('Modules\System\Http\Controllers\login')->apiForgotPassword($nik, 'password123');
        } else {
            $pass = Hash::make('password123');
            modelprivilege::where('privilege_user_nik', $nik)->where('privilege_aktif', 'Y')->update([
                'privilege_password' => $pass,
                'updated_at'         => date('Y-m-d H:i:s')
            ]);
        }

        \LogActivity::update('user [Reset Password]', $nik, $this->micro);
        Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        return redirect('privilege/user_access');
    }

    public function resetqa($nik)
    {
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

    public function getPrivilege($nik)
    {
        // dd('sini');
        $privilege      = modelprivilege::where('privilege_user_nik', '=', $nik)
            ->first();
        // dd($nik);
        if ($privilege) {
            $role_access    = modelrole_access::where('role_access_group_access_id', '=', $privilege->privilege_group_access_id)
                ->get();
            if ($role_access) {
                $data = array(
                    'sistem'    => ',',
                    'location'  => explode(',', $privilege->privilege_user_location),
                    'menu'      => $role_access,
                );

                Session::put('privilege', $data);
            } else {
                Session::forget('privilege');
            }
        } else {
            Session::forget('privilege');
        }
    }

    public function update($privilege_id)
    {
        $privilege_id = Crypt::decrypt($privilege_id);
        $privilege = modelprivilege::find($privilege_id);

        if ($privilege->emailfinance == null || $privilege->namafinance == null || $privilege->nikfinance == null) {
            $jenis = 'internal';
        } else {
            $jenis = 'external';
        }

        $data = array(
            'title' => 'Ubah User Akses',
            'menu'  => '',

            'action'    => url('privilege/user_access/updateaction'),
            'group_access_data'     => modelgroup_access::all(),
            'factory_data'          => modelfactory::whereNotNull('factory_code')->get(),

            'privilege_id'          => $privilege->privilege_id,
            'privilege_user_nik'    => $privilege->privilege_user_nik,
            'privilege_user_name'    => $privilege->privilege_user_name,
            'jenisku'                 => $jenis,
            'nama_finance'   => $privilege->namafinance,
            'privilege_group_access_id' => $privilege->privilege_group_access_id,
            'nik_finance' => $privilege->nikfinance,
            'email_finance' => $privilege->emailfinance,
        );

        return view('system::settings/privileges/user_access_form', $data);
    }

    public function updateaction(Request $post)
    {
        $privilege_id = Crypt::decrypt($post->privilege_id);
        $privilege = modelprivilege::find($privilege_id);
        // dd($post, $privilege_id, $privilege);
        $cekinternal = modelprivilege::where('privilege_id', '!=', $privilege_id)->where('privilege_user_nik', $post->internalnik)->where('privilege_aktif', 'Y')->first();
        $cekexternal = modelprivilege::where('privilege_id', '!=', $privilege_id)->where('privilege_user_nik', $post->externalemail)->where('privilege_aktif', 'Y')->first();
        // dd($cekinternal, $cekexternal);

        if ($cekinternal != null || $cekexternal != null) {
            Session::flash('toast', 'toast("info", "User sudah ada, silahkan cek di list data user.")');
            return redirect()->back();
        } else {
            if ($post->jenis == 'internal') {
                $update1 = modelprivilege::where('privilege_id', $privilege_id)->update(['privilege_user_nik' => $post->internalnik, 'privilege_user_name' => $post->internalnama, 'privilege_group_access_id' => $post->privilege_group_access_id, 'emailfinance' => null, 'nikfinance' => null, 'namafinance' => null, 'updated_at' => date('Y-m-d H:i:s')]);
                if ($update1) {
                    \LogActivity::update('privilege', $privilege_id, $this->micro);
                    Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
                } else {
                    Session::flash('toast', 'toast("error", "Gagal disimpan.")');
                }
                return redirect('privilege/user_access');
            } else {
                $update2 = modelprivilege::where('privilege_id', $privilege_id)->update(['privilege_user_nik' => $post->externalemail, 'privilege_user_name' => $post->externalnama, 'emailfinance' => $post->emailfinance, 'nikfinance' => $post->nikfinance, 'namafinance' => $post->namafinance, 'privilege_group_access_id' => $post->privilege_group_access_id, 'updated_at' => date('Y-m-d H:i:s')]);
                if ($update2) {
                    \LogActivity::update('privilege', $privilege_id, $this->micro);
                    Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
                } else {
                    Session::flash('toast', 'toast("error", "Gagal disimpan.")');
                }
                return redirect('privilege/user_access');
            }
        }

        // \LogActivity::update('privilege', $privilege_id, $this->micro);
        // Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        // return redirect('privilege/user_access');
    }

    public function createsave(Request $request)
    {
        $jenis = $request->jenis;
        $token = $request->privilege_api_key;
        if ($jenis == 'internal') {
            $nik = $request->internalnik;
            $nama = $request->internalnama;
            $akses = $request->privilege_group_access_id;
            $cekuser = modelprivilege::where('privilege_user_nik', $nik)->where('privilege_aktif', 'Y')->first();
            if ($cekuser != null) {
                Session::flash('toast', 'toast("info", "User sudah ada, silahkan cek di list data user.")');
                return redirect()->back();
            } else {
                $in = modelprivilege::insert(['privilege_user_nik' => $nik, 'privilege_user_name' => $nama, 'privilege_aktif' => 'Y', 'privilege_hrips' => 'Y', 'coc' => 'Y', 'kyc' => 'Y', 'kode_validate' => 'Y', 'privilege_group_access_id' => $akses, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')]);
                if ($in) {
                    $privilegeid = $this->getLastID('privilege');
                    \LogActivity::create('privilege', $privilegeid, $this->micro);
                    Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
                } else {
                    Session::flash('toast', 'toast("error", "Gagal disimpan.")');
                }
                return redirect()->back();
            }
        } elseif ($jenis == 'external') {
            $nik = $request->externalemail;
            $nama = $request->externalnama;
            $akses = $request->privilege_group_access_id;
            $cekuser = modelprivilege::where('privilege_user_nik', $nik)->where('privilege_aktif', 'Y')->first();
            if ($cekuser != null) {
                Session::flash('toast', 'toast("info", "User sudah ada, silahkan cek di list data user.")');
                return redirect()->back();
            } else {
                $in = modelprivilege::insert(['privilege_user_nik' => $nik, 'privilege_user_name' => $nama, 'privilege_aktif' => 'Y', 'privilege_hrips' => 'N', 'coc' => 'N', 'kyc' => 'N', 'kode_validate' => 'N', 'kode' => rand(11111, 99999), 'privilege_password' => Hash::make('password123'), 'emailfinance' => $request->emailfinance, 'nikfinance' => $request->nikfinance, 'namafinance' => $request->namafinance, 'privilege_group_access_id' => $akses, 'token' => $token, 'created_at' => date('Y-m-d H:i:s')]);
                if ($in) {
                    $privilegeid = $this->getLastID('privilege');
                    \LogActivity::create('privilege', $privilegeid, $this->micro);
                    Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
                } else {
                    Session::flash('toast', 'toast("error", "Gagal disimpan.")');
                }
                return redirect()->back();
            }
        } else {
            abort(401);
        }
        dd($request);
        $nik = $request->nik;
        $nama = $request->nama;
        $akses = $request->akses;
        $lokasi = $request->lokasi;
        $cekuser = modelprivilege::where('privilege_user_nik', $nik)->first();
        if ($cekuser != null) {
            Session::flash('toast', 'toast("info", "User sudah ada, silahkan cek di list data user.")');
            return redirect()->back();
        }
        // dd(implode(",", $lokasi) ) ;
        if (count($lokasi) <= 0) {
            Session::flash('toast', 'toast("error", "Akses Lokasi belum dimasukkan silahkan masukkan akses Lokasi")');
            return redirect()->back();
        }
        $lok = implode(",", $lokasi);

        $in = modelprivilege::insert(['privilege_user_nik' => $nik, 'privilege_user_name' => $nama, 'privilege_user_location' => $lok, 'privilege_group_access_id' => $akses, 'created_at' => date('Y-m-d H:i:s')]);
        if ($in) {
            Session::flash('toast', 'toast("success", "Berhasil disimpan.")');
        } else {
            Session::flash('toast', 'toast("error", "Gagal disimpan.")');
        }
        return redirect()->back();
    }

    public function getnama(Request $req)
    {
        $nik = $this->enkripsi($req->nik);
        $detail_url        = 'http://' . $this->ip_server . '/api/detail.php?n=' . $nik;
        // dd($detail_url);
        $detail_client     = new Client();
        $detail_res        = $detail_client->get($detail_url);
        $detail_data_enkripsi = json_decode(base64_decode($detail_res->getBody()), TRUE);
        foreach ($detail_data_enkripsi as $key => $data_enkripsi) {
            $detail_data[$this->dekripsi($key)] = $this->dekripsi($data_enkripsi);
        }
        // dd($detail_data);
        $jumlahdata = count($detail_data);
        // dd($detail_data, $jumlahdata);
        if ($jumlahdata <= 1) {
            $nama = '';
        } else {
            $nama = $detail_data['a'];
        }

        echo $nama;
        // dd(array_sum($detail_data),$detail_data);
    }

    function dekripsi($input)
    {
        $str = base64_decode($input);
        $str = $this->potongKarakterDepan(6, $str);
        $str = $this->potongKarakterBelakang(7, $str);
        $str1 = substr($str, 0, 1);
        $str2 = substr($str, 7, 999999);
        $str = $str1 . $str2;
        $output = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $temp = ord($str[$i]);
            $temp = ($temp - 20) + 29;
            $temp = chr($temp);
            $output = $output . $temp;
        }

        return $output;
    }


    function potongKarakterBelakang($jml, $str)
    {
        $str = (string)$str;
        $str = substr($str, 0, -$jml);
        return $str;
    }

    function potongKarakterDepan($jml, $str)
    {
        $pjg = strlen($str);
        $str = (string)$str;
        $str = substr($str, $jml, $pjg);
        return $str;
    }

    function enkripsi($input)
    {
        $output = '';

        for ($i = 0; $i < strlen($input); $i++) {
            $temp = ord($input[$i]);
            $temp = ($temp + 20) - 29;
            $temp = chr($temp);
            $output = $output . $temp;
        }

        $str1 = substr($output, 0, 1);
        $str2 = substr($output, 1, 999999);
        $str = $str1 . mt_rand(100000, 999999) . $str2;
        $str = rand(1111, 9999) . date('y') . $str . date('m') . rand(10101, 99999);
        $str = base64_encode($str);

        return $str;
    }
    public function getLastID($table)
    {
        $last_ai = DB::select('
            SELECT `AUTO_INCREMENT`
            FROM INFORMATION_SCHEMA.TABLES
            WHERE TABLE_SCHEMA = "' . DB::connection()->getDatabaseName() . '"
            AND TABLE_NAME = "' . $table . '"
        ');
        return $last_ai[0]->AUTO_INCREMENT;
    }
}
