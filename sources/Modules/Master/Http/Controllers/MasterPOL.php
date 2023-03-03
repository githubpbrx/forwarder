<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Modules\Master\Models\masterportofloading as loading;

class MasterPOL extends Controller
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
            'title' => 'List Master Port Of Loading',
            'menu' => 'masterportofloading'
        ];

        \LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Master Port Of Loading', $this->micro);
        return view('master::masterportloading', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listpol()
    {
        $data = loading::where('aktif', 'Y')->select('id_portloading', 'code_port', 'name_port');
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('codeport', function ($data) {
                return $data->code_port;
            })
            ->addColumn('nameport', function ($data) {
                return $data->name_port;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                $button .= '<a href="#" data-tooltip="tooltip" data-id="' . encrypt($data->id_portloading) . '" id="editdata" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                $button .= '<a href="#" data-id="' . encrypt($data->id_portloading) . '" id="delbtn" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';

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
    public function add(Request $request)
    {
        // dd($request);
        if ($request->codeport == '' || $request->codeport == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Code is required, please input data'];
            return response()->json($status, 200);
        }

        if ($request->nameport == '' || $request->nameport == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Name is required, please input data'];
            return response()->json($status, 200);
        }

        $cekcode = loading::where('code_port', $request->codeport)->where('aktif', 'Y')->first();
        if ($cekcode != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Code is available, please check again'];
            return response()->json($status, 200);
        }

        $cekdesc = loading::where('name_port', $request->nameport)->where('aktif', 'Y')->first();
        if ($cekdesc != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Name is available, please check again'];
            return response()->json($status, 200);
        }

        $saved = loading::insert([
            'code_port'   => strtoupper($request->codeport),
            'name_port'   => strtoupper($request->nameport),
            'aktif'        => 'Y',
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by'   => Session::get('session')['user_nik']
        ]);

        if ($saved) {
            \LogActivity::addToLog('Web Forwarder :: Logistik : Save Master Port Of Loading', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request)
    {
        $id = decrypt($request->id);
        $datapol = loading::where('id_portloading', $id)->where('aktif', 'Y')->first();

        return response()->json(['status' => 200, 'data' => $datapol, 'message' => 'Berhasil']);
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
        if ($request->codeport == '' || $request->codeport == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Code is required, please input data'];
            return response()->json($status, 200);
        }

        if ($request->nameport == '' || $request->nameport == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Name is required, please input data'];
            return response()->json($status, 200);
        }

        $cekcode = loading::where('id_portloading', '!=', $request->id)->where('code_port', $request->codeport)->where('aktif', 'Y')->first();
        if ($cekcode != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Code is available, please check again'];
            return response()->json($status, 200);
        }

        $cekdesc = loading::where('id_portloading', '!=', $request->id)->where('name_port', $request->nameport)->where('aktif', 'Y')->first();
        if ($cekdesc != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Name is available, please check again'];
            return response()->json($status, 200);
        }

        $updateloading = loading::where('id_portloading', $request->id)->update([
            'code_port' => strtoupper($request->codeport),
            'name_port' => strtoupper($request->nameport),
            'aktif'      => 'Y',
            'updated_at' => date('Y-m-d H:i:s'),
            'update_by' => Session::get('session')['user_nik']
        ]);

        if ($updateloading) {
            \LogActivity::addToLog('Web Forwarder :: Logistik : Update Master Port Of Loading', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Updated'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Updated'];
            return response()->json($status, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $id = decrypt($id);

        $delete = loading::where('id_portloading', $id)->update([
            'aktif'       => 'N',
            'updated_at'  => date('Y-m-d H:i:s'),
            'update_by'  => Session::get('session')['user_nik']
        ]);

        if ($delete) {
            \LogActivity::addToLog('Web Forwarder :: Logistik : Delete Master Port Of Loading', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Deleted'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Deleted'];
            return response()->json($status, 200);
        }
    }
}
