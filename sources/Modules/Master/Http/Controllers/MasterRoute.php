<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
// use Session, Crypt, DB;
use Illuminate\Support\Facades\Session;
use Yajra\Datatables\Datatables;
use Modules\Master\Models\masterroute as route;

class MasterRoute extends Controller
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
            'title' => 'List Master Route',
            'menu' => 'masterroute'
        ];

        \LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Master Route', $this->micro);
        return view('master::masterroute', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listroute()
    {
        $data = route::where('aktif', 'Y')->select('id_route', 'route_code', 'route_desc');
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('routecode', function ($data) {
                return $data->route_code;
            })
            ->addColumn('routedesc', function ($data) {
                return $data->route_desc;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                $button .= '<a href="#" data-tooltip="tooltip" data-id="' . encrypt($data->id_route) . '" id="editdata" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                $button .= '<a href="#" data-id="' . encrypt($data->id_route) . '" id="delbtn" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';

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
        if ($request->routecode == '' || $request->routecode == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route Code is required, please input data'];
            return response()->json($status, 200);
        }

        if ($request->routedesc == '' || $request->routedesc == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route Description is required, please input data'];
            return response()->json($status, 200);
        }

        $cekcode = route::where('route_code', $request->routecode)->where('aktif', 'Y')->first();
        if ($cekcode != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route Code is available, please check again'];
            return response()->json($status, 200);
        }

        $cekdesc = route::where('route_desc', $request->routedesc)->where('aktif', 'Y')->first();
        if ($cekdesc != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route Description is available, please check again'];
            return response()->json($status, 200);
        }

        $saved = route::insert([
            'route_code'   => strtoupper($request->routecode),
            'route_desc'   => strtoupper($request->routedesc),
            'aktif'        => 'Y',
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by'   => Session::get('session')['user_nik']
        ]);

        if ($saved) {
            \LogActivity::addToLog('Web Forwarder :: Logistik : Save Master Route', $this->micro);
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
        $dataroute = route::where('id_route', $id)->where('aktif', 'Y')->first();

        return response()->json(['status' => 200, 'data' => $dataroute, 'message' => 'Berhasil']);
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
        if ($request->routecode == '' || $request->routecode == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route Code is required, please input data'];
            return response()->json($status, 200);
        }

        if ($request->routedesc == '' || $request->routedesc == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route Description is required, please input data'];
            return response()->json($status, 200);
        }

        $cekcode = route::where('id_route', '!=', $request->id)->where('route_code', $request->routecode)->where('aktif', 'Y')->first();
        if ($cekcode != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route Code is available, please check again'];
            return response()->json($status, 200);
        }

        $cekdesc = route::where('id_route', '!=', $request->id)->where('route_desc', $request->routedesc)->where('aktif', 'Y')->first();
        if ($cekdesc != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route Description is available, please check again'];
            return response()->json($status, 200);
        }

        $updateroute = route::where('id_route', $request->id)->update([
            'route_code' => strtoupper($request->routecode),
            'route_desc' => strtoupper($request->routedesc),
            'aktif'      => 'Y',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        if ($updateroute) {
            \LogActivity::addToLog('Web Forwarder :: Logistik : Update Master Route', $this->micro);
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

        $delete = route::where('id_route', $id)->update([
            'aktif'       => 'N',
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => Session::get('session')['user_nik']
        ]);

        if ($delete) {
            \LogActivity::addToLog('Web Forwarder :: Logistik : Delete Master Route', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Deleted'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Deleted'];
            return response()->json($status, 200);
        }
    }
}
