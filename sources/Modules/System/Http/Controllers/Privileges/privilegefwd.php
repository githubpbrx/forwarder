<?php

namespace Modules\System\Http\Controllers\Privileges;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request, Session;
use Config, Storage, DB, Crypt;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Modules\Report\Models\modelformpo;
use Modules\System\Models\modelforwarder;
use Yajra\Datatables\Datatables;
use Modules\System\Models\modelprivilege,
    Modules\System\Models\masterforwarder,
    Modules\System\Models\Privileges\modelgroup_access,
    Modules\System\Models\modelfactory;

class privilegefwd extends Controller
{
    protected $ip_server;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->micro = microtime(true);
        $this->ip_server = config('api.url.ip_address');
    }

    public function index()
    {
        $data = array(
            'title' => 'Manage User Forwarder',
            'menu'  => '',
            // 'group_access_data'     => modelgroup_access::all(),
            // 'factory_data'          => modelfactory::whereNotNull('factory_code')->get(),
        );

        \LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Manage User Forwarder', $this->micro);
        return view('system::settings/privileges/user_access_fwd', $data);
    }

    public function datatablefwd()
    {
        $getidfwd = modelprivilege::where('privilege_user_nik', Session::get('session')['user_nik'])->where('privilege_aktif', 'Y')->first();

        $query = modelprivilege::with(['group_access'])
            ->where('idforwarder', $getidfwd->idforwarder)
            ->where('leadforwarder', null)
            ->where('deleted_at', null)
            // ->orderBy('privilege_user_nik', 'ASC')
            // ->where('privilege_aktif', 'Y')
            ->get();
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('privilege_user_nik', function ($q) {
                return  $q->privilege_user_nik;
            })
            ->addColumn('privilege_user_name', function ($q) {
                return  $q->privilege_user_name;
            })
            ->addColumn('nama_finance', function ($q) {
                if ($q->namafinance) {
                    return $q->namafinance;
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
            ->addColumn('email_finance', function ($q) {
                if ($q->emailfinance) {
                    return $q->emailfinance;
                } else {
                    return '<span class="badge bg-info">Not Set</span>';
                }
            })
            ->addColumn('status', function ($q) {
                if ($q->status == 'confirm') {
                    return '<span class="badge bg-success">Confirmed</span>';
                } else if ($q->status == 'reject') {
                    return '<span class="badge bg-danger">Rejected</span>';
                } else {
                    return '<span class="badge bg-info">Waiting</span>';
                }
            })
            ->addColumn('action', function ($q) {
                // $process    = '';

                // $process    = '<a href="' . url("privilege/user_access/update/" . Crypt::encrypt($q->privilege_id)) . '"><i class="fas fa-edit text-orange"></i></a>';

                // $process    = '<a href="#"><i class="fas fa-trash text-red"></i></a>';

                // return $process;

                $button = '';

                if ($q->status == 'reject') {
                    $button .= '<a href="#" data-tooltip="tooltip" data-id="' . $q->privilege_id . '" id="edituserfwd" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                    $button .= '&nbsp;';
                    $button .= '<a href="#" data-id="' . encrypt($q->privilege_id) . '" id="deleteuser" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';
                    $button .= '&nbsp;';
                    $button .= '<a href="#" data-id="' . $q->privilege_id . '" id="detailuser" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-info-circle fa-lg text-blue actiona"></i></a>';
                } else {
                    $button .= '<a href="#" data-tooltip="tooltip" data-id="' . $q->privilege_id . '" id="edituserfwd" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                    $button .= '&nbsp;';
                    $button .= '<a href="#" data-id="' . encrypt($q->privilege_id) . '" id="deleteuser" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';
                }

                return $button;
            })
            ->rawColumns(['nama_finance', 'nik_finance', 'email_finance', 'status', 'action'])
            ->make(true);
    }

    public function saveuserfwd(Request $request)
    {
        // dd($request);
        //make password
        $pass = Hash::make('password123');

        //make kode
        $kode = rand(11111, 99999);

        //make token
        $token = Hash::make('ittetapsemangant');

        $cekemail = modelprivilege::where('privilege_user_nik', $request->emailuser)->where('deleted_at', null)->first();
        if ($cekemail) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'User Email is available, please check again'];
            return response()->json($status, 200);
        }

        $getid = masterforwarder::where('name', $request->namefwd)->where('aktif', 'Y')->first();

        $getprivbefore = modelprivilege::where('privilege_user_name', $request->namefwd)->where('privilege_aktif', 'Y')->first();

        $save = modelprivilege::insert([
            'privilege_user_nik'        => $request->emailuser,
            'privilege_user_name'       => $request->namefwd,
            'privilege_password'        => $pass,
            'privilege_group_access_id' => '1',
            'privilege_aktif'           => 'N',
            'privilege_hrips'           => 'N',
            'created_at'                => date('Y-m-d H:i:s'),
            'coc'                       => $getprivbefore->coc,
            'coc_date'                  => $getprivbefore->coc_date,
            'kyc'                       => $getprivbefore->kyc,
            'kyc_date'                  => $getprivbefore->kyc_date,
            'kode'                      => $kode,
            'kode_validate'             => 'N',
            'token'                     => $token,
            'emailfinance'              => $getprivbefore->emailfinance,
            'nikfinance'                => $getprivbefore->nikfinance,
            'namafinance'               => $getprivbefore->namafinance,
            'idforwarder'               => $getid->id,
            'status'                    => 'waiting'
        ]);

        if ($save) {
            \LogActivity::addToLog('Web Forwarder :: Forwarder : Save Add User Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    public function edituserfwd(Request $request)
    {
        $cekprivilege = modelprivilege::where('privilege_id', $request->id)->where('privilege_aktif', 'Y')->first();

        return response()->json(['status' => 200, 'data' => $cekprivilege, 'message' => 'Berhasil']);
    }

    public function updateuserfwd(Request $request)
    {
        // dd($request);

        $cekuseremail = modelprivilege::where('privilege_id', '!=', $request->id)->where('privilege_user_nik', $request->emailuser)->where('privilege_aktif', 'Y')->first();
        if ($cekuseremail) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'User Email is available, please check again'];
            return response()->json($status, 200);
        }

        $update = modelprivilege::where('privilege_id', $request->id)->update([
            'privilege_user_nik' => $request->emailuser,
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        if ($update) {
            \LogActivity::addToLog('Web Forwarder :: Forwarder : Update User Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Updated'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Updated'];
            return response()->json($status, 200);
        }
    }

    public function deleteuserfwd($id)
    {
        // dd(decrypt($id));

        $delete = modelprivilege::where('privilege_id', decrypt($id))->update(['privilege_aktif' => 'N', 'updated_at' => date("Y-m-d H:i:s"), 'deleted_at' => date("Y-m-d H:i:s")]);

        if ($delete) {
            \LogActivity::addToLog('Web Forwarder :: Forwarder : Delete User Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Delete'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Delete'];
            return response()->json($status, 200);
        }
    }

    public function detailuserfwd(Request $request)
    {
        // dd($request);

        $detail = modelprivilege::where('privilege_id', $request->id)->first();

        return response()->json(['status' => 200, 'data' => $detail, 'message' => 'Berhasil']);
    }
}
