<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\System\Helpers\LogActivity;
use Yajra\Datatables\Datatables;

use Modules\Master\Models\mastercountry as country;
use Modules\Master\Models\masterpol_city as pol_city;
use Modules\Master\Models\masterpod_city as pod_city;

class MasterPOL_City extends Controller
{
    protected $ip_server;
    protected $micro;
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
            'title'     => 'List Master POL City',
            'menu'      => 'masterpol_city'
        ];

        LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Master POL City', $this->micro);
        return view('master::masterpol_city', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listpolcity()
    {
        $data = pol_city::with(['country'])->where('aktif', 'Y')->select('id', 'id_country', 'city')->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namecountry', function ($data) {
                return $data->country->country;
            })
            ->addColumn('namecity', function ($data) {
                return $data->city;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                $button .= '<a href="#" data-tooltip="tooltip" data-id="' . encrypt($data->id) . '" id="editdata" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                $button .= '&nbsp;';
                $button .= '<a href="#" data-id="' . encrypt($data->id) . '" id="delbtn" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';

                return $button;
            })
            ->make(true);

        // return view('master::create');
    }

    public function getcountry(Request $request)
    {
        if (!$request->ajax()) return;
        $po = country::selectRaw(' id, country');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' (country like "%' . $search . '%") ');
        }

        $po = $po->where('aktif', 'Y')->paginate(10, $request->page);
        return response()->json($po);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        // dd($request);
        if ($request->idcountry == '' || $request->idcountry == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name Country is required, please input data'];
            return response()->json($status, 200);
        }

        if ($request->namecity == '' || $request->namecity == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name City is required, please input data'];
            return response()->json($status, 200);
        }

        $cekcity = pol_city::where('id_country', $request->idcountry)->where('city', $request->namecity)->where('aktif', 'Y')->first();
        if ($cekcity != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name City is available, please check again'];
            return response()->json($status, 200);
        }

        $saved = pol_city::insert([
            'id_country' => $request->idcountry,
            'city'       => strtoupper($request->namecity),
            'aktif'      => 'Y',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Session::get('session')['user_nik']
        ]);

        if ($saved) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Save Master POL City', $this->micro);
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
        $data = pol_city::with('country')->where('id', $id)->where('aktif', 'Y')->first();

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
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
        if ($request->idcountry == '' || $request->idcountry == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name Country is required, please input data'];
            return response()->json($status, 200);
        }

        if ($request->namecity == '' || $request->namecity == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name City is required, please input data'];
            return response()->json($status, 200);
        }

        $cekcity = pol_city::where('id', '!=', $request->id)->where('id_country', $request->idcountry)->where('city', $request->namecity)->where('aktif', 'Y')->first();
        if ($cekcity != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name City is available, please check again'];
            return response()->json($status, 200);
        }

        $updatecity = pol_city::where('id', $request->id)->update([
            'id_country' => $request->idcountry,
            'city'       => strtoupper($request->namecity),
            'aktif'      => 'Y',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        if ($updatecity) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Update Master POL City', $this->micro);
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

        $cek = pod_city::where('id_polcity', $id)->where('aktif', 'Y')->first();
        if ($cek) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'POL City Already Used, Can`t Delete It'];
            return response()->json($status, 200);
        }

        $delete = pol_city::where('id', $id)->update([
            'aktif'       => 'N',
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => Session::get('session')['user_nik']
        ]);

        if ($delete) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Delete Master POL City', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Deleted'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Deleted'];
            return response()->json($status, 200);
        }
    }
}
