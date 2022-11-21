<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;
use Yajra\Datatables\Datatables;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Modules\Master\Models\modelpo;
use Modules\Master\Models\masterhscode as hscode;

class MasterHscode extends Controller
{
    protected $ip_server;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->micro = microtime(true);
        $this->ip_server = config('api.url.ip_address');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'title' => 'List Master HS Code',
            'menu' => 'masterhscode'
        ];

        \LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Master HsCode', $this->micro);
        return view('master::masterhscode', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listhscode()
    {
        $data = modelpo::with(['hscodeku'])
            // leftjoin('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            //     ->where('masterhscode.aktif', 'Y')
            ->selectRaw(' po.id, po.pono, po.matcontents')
            ->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('pono', function ($data) {
                return $data->pono;
            })
            ->addColumn('hscode', function ($data) {
                return $data->hscodeku['hscode'];
            })
            ->addColumn('action', function ($data) {
                $button = '';
                if ($data->hscodeku['id_hscode'] == null) {
                    $button .= '<a href="#" data-tooltip="tooltip" data-id="' . $data->id . '##' . $data->matcontents . '" id="editdata" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                } else {
                    $button .= '<a href="#" data-tooltip="tooltip" data-id="' . $data->id . '##' . $data->matcontents . '" id="editdata" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                    $button .= '<a href="#" data-id="' . $data->matcontents . '" id="delbtn" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';
                }

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

        $cekfwd = forwarder::where('name', $request->namefwd)->where('aktif', 'Y')->first();
        if ($cekfwd != null) {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Name Forwarder is available, please check again'];
            return response()->json($status, 200);
        }

        $cekprivilege = privilege::where('privilege_user_nik', $request->emailfwd)->where('privilege_aktif', 'Y')->first();
        if ($cekprivilege != null) {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Email Forwarder is available, please check again'];
            return response()->json($status, 200);
        }

        $cekprivilege2 = privilege::where('privilege_user_name', strtoupper($request->namefwd))->where('privilege_aktif', 'Y')->first();
        if ($cekprivilege2 != null) {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Name Forwarder is available, please check again'];
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
            \LogActivity::addToLog('Web Forwarder :: Logistik : Save Master Forwarder', $this->micro);
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
    public function edit(Request $request)
    {
        $exp1 = explode('##', $request->id);
        $cekhscode = hscode::where('matcontent', $exp1[1])->where('aktif', 'Y')->first();
        // dd($cekhscode);

        if ($cekhscode == null) {
            $exp = explode('##', $request->id);
            $datahscode = modelpo::where('id', $exp[0])->first();
        } else {
            $exp2 = explode('##', $request->id);
            $datahscode = modelpo::leftjoin('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
                ->where('po.id', $exp2[0])
                ->where('masterhscode.matcontent', $exp2[1])
                ->where('masterhscode.aktif', 'Y')
                ->selectRaw(' po.id, po.pono, po.matcontents, masterhscode.id_hscode, masterhscode.hscode')
                ->first();
        }

        // dd($datahscode);

        return response()->json(['status' => 200, 'data' => $datahscode, 'message' => 'Berhasil']);
        // return view('master::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        // dd($request);
        // dd($cekprivilege);
        if ($request->id == null) {

            if ($request->hscode == '') {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data is required, please input data'];
                return response()->json($status, 200);
            }

            $cekhscode = hscode::where('hscode', $request->hscode)->where('aktif', 'Y')->first();
            if ($cekhscode != null) {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data HSCode is available, please check again'];
                return response()->json($status, 200);
            }

            $inserthscode = hscode::insert([
                'hscode'         => strtoupper($request->hscode),
                'matcontent'     => $request->matcontents,
                'aktif'        => 'Y',
                'created_at'   => date('Y-m-d H:i:s'),
                'created_by' => Session::get('session')['user_nik']
            ]);

            if ($inserthscode) {
                \LogActivity::addToLog('Web Forwarder :: Logistik : Insert Master HSCode', $this->micro);
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        } else {

            if ($request->hscode == '') {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data is required, please input data'];
                return response()->json($status, 200);
            }

            $cekhscode = hscode::where('id_hscode', '!=', $request->id)->where('hscode', $request->hscode)->where('aktif', 'Y')->first();
            if ($cekhscode != null) {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data HSCode is available, please check again'];
                return response()->json($status, 200);
            }

            $updatehscode = hscode::where('id_hscode', $request->id)->update([
                'hscode'         => strtoupper($request->hscode),
                'aktif'        => 'Y',
                'updated_at'   => date('Y-m-d H:i:s'),
                'updated_by' => Session::get('session')['user_nik']
            ]);

            if ($updatehscode) {
                \LogActivity::addToLog('Web Forwarder :: Logistik : Update Master HSCode', $this->micro);
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        // dd($id);

        $delete = hscode::where('matcontent', $id)->update([
            'aktif' => 'N',
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        if ($delete) {
            \LogActivity::addToLog('Web Forwarder :: Logistik : Delete Master HS Code', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Deleted'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Deleted'];
            return response()->json($status, 200);
        }
    }
}
