<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;
use Yajra\Datatables\Datatables;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;


use Modules\Master\Models\masterforwarder as forwarder;
use Modules\Master\Models\modelprivilege as privilege;

class MasterForwarder extends Controller
{
    protected $ip_server;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->ip_server = config('api.url.ip_address');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'title' => 'List Master Forwarder',
            'menu' => 'masterforwarder'
        ];
        return view('master::masterforwarder', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listforwarder()
    {
        $data = forwarder::where('aktif', 'Y')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namefwd', function ($data) {
                return $data->name;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                $button .= '<a href="#" data-tooltip="tooltip" data-id="' . $data->id . '" id="editfwd" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';

                $button .= '<a href="#" data-id="' . $data->id . '" id="delbtn" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';

                return $button;
            })
            ->make(true);

        // return view('master::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function savefwd(Request $request)
    {
        // dd($request);
        //make password
        $pass = Hash::make('password123');

        //make kode
        $kode = rand(11111, 99999);

        //make token
        $token = Hash::make('ittetapsemangant');

        DB::beginTransaction();
        if ($request->namefwd == '' || $request->position == '' || $request->company == '' || $request->address == '' || $request->emailfwd == '' || $request->namefinance == '' || $request->nikfinance == '' || $request->emailfinance == '') {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data is required, please input data'];
            return response()->json($status, 200);
        }

        $savedfwd = forwarder::insert([
            'name'         => strtoupper($request->namefwd),
            'position'     => $request->position,
            'company'      => $request->company,
            'address'      => $request->address,
            'aktif'        => 'Y',
            'created_at'   => date('Y-m-d H:i:s'),
            'created_user' => Session::get('session')['user_nik']
        ]);

        $idfwd = forwarder::latest('id')->first();
        $saveprivilege = privilege::insert([
            'privilege_user_nik'        => $request->emailfwd,
            'privilege_user_name'       => strtoupper($request->namefwd),
            'privilege_password'        => $pass,
            'privilege_group_access_id' => '1',
            'privilege_aktif'           => 'Y',
            'privilege_hrips'           => 'N',
            'created_at'                => date('Y-m-d H:i:s'),
            'coc'                       => 'N',
            'kyc'                       => 'N',
            'kode'                      => $kode,
            'kode_validate'             => 'N',
            'token'                     => $token,
            'emailfinance'              => $request->emailfinance,
            'nikfinance'                => $request->nikfinance,
            'namafinance'               => $request->namefinance,
            'idforwarder'               => $idfwd->id
        ]);

        if ($savedfwd && $saveprivilege) {
            DB::commit();
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('master::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function editfwd(Request $request)
    {
        // dd($request);

        $datafwd = forwarder::where('id', $request->id)->where('aktif', 'Y')->first();
        $dataprivilege = privilege::where('idforwarder', $request->id)->first();

        $data = [
            'datafwd' => $datafwd,
            'datapriv' => $dataprivilege,
        ];

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
        // return view('master::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function updatefwd(Request $request)
    {
        // dd($request);

        DB::beginTransaction();
        if ($request->namefwdedit == '' || $request->positionedit == '' || $request->companyedit == '' || $request->addressedit == '' || $request->emailfwdedit == '' || $request->namefinanceedit == '' || $request->nikfinanceedit == '' || $request->emailfinanceedit == '') {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data is required, please input data'];
            return response()->json($status, 200);
        }

        $updatefwd = forwarder::where('id', $request->id)->update([
            'name'         => strtoupper($request->namefwdedit),
            'position'     => $request->positionedit,
            'company'      => $request->companyedit,
            'address'      => $request->addressedit,
            'aktif'        => 'Y',
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_user' => Session::get('session')['user_nik']
        ]);

        $updateprivilege = privilege::where('idforwarder', $request->id)->update([
            'privilege_user_nik'        => $request->emailfwdedit,
            'privilege_user_name'       => strtoupper($request->namefwdedit),
            'emailfinance'              => $request->emailfinanceedit,
            'nikfinance'                => $request->nikfinanceedit,
            'namafinance'               => $request->namefinanceedit
        ]);

        if ($updatefwd && $updateprivilege) {
            DB::commit();
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroyfwd($id)
    {
        // dd($id);

        $delete = forwarder::where('id', $id)->update([
            'aktif' => 'N',
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_user' => Session::get('session')['user_nik']
        ]);

        if ($delete) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Deleted'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Deleted'];
            return response()->json($status, 200);
        }
    }

    function getkaryawan(Request $request, $id)
    {
        $login_url        = 'http://' . $this->ip_server . '/api/detailkaryawan.php?n=' . $id;
        $login_client     = new Client();
        $login_res        = $login_client->get($login_url);
        $result = json_decode(base64_decode($login_res->getBody()), TRUE);

        if (count($result) > 1) {
            $nama = $result['nama'];

            if ($result['aktif'] == 'Pasif') {
                $da = array(
                    "status" => 'no',
                    "namaasli" => '',
                    "data" => 'STATUS KARYAWAN PASIF'
                );

                return $da;
            }
            $da = array(
                "status" => 'yes',
                "namaasli" => $result['nama'],
                "data" => $nama
            );
            return $da;
        }

        $da = array(
            "status" => 'no',
            "namaasli" => '',
            "data" => 'NIK TIDAK DITEMUKAN'
        );

        return $da;
    }
}
