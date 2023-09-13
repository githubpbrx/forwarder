<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\System\Helpers\LogActivity;
use Yajra\Datatables\Datatables;

use Modules\Master\Models\masterportofdestination as destination;
use Modules\Master\Models\mastercountry as country;

class MasterCountry extends Controller
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
            'title' => 'List Master Country',
            'menu' => 'mastercountry'
        ];

        LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Master Country', $this->micro);
        return view('master::mastercountry', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listcountry()
    {
        $data = country::where('aktif', 'Y')->select('id', 'country');
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namecountry', function ($data) {
                return $data->country;
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

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        // dd($request);
        if ($request->namecountry == '' || $request->namecountry == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name Country is required, please input data'];
            return response()->json($status, 200);
        }

        $cekcountry = country::where('country', $request->namecountry)->where('aktif', 'Y')->first();
        if ($cekcountry != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name Country is available, please check again'];
            return response()->json($status, 200);
        }

        $saved = country::insert([
            'country'    => strtoupper($request->namecountry),
            'aktif'      => 'Y',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Session::get('session')['user_nik']
        ]);

        if ($saved) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Save Master Country', $this->micro);
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
        $data = country::where('id', $id)->where('aktif', 'Y')->first();

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
        if ($request->namecountry == '' || $request->namecountry == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name Country is required, please input data'];
            return response()->json($status, 200);
        }

        $cekcountry = country::where('id', '!=', $request->id)->where('country', $request->namecountry)->where('aktif', 'Y')->first();
        if ($cekcountry != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Name Country is available, please check again'];
            return response()->json($status, 200);
        }

        $updatecountry = country::where('id', $request->id)->update([
            'country'    => strtoupper($request->namecountry),
            'aktif'      => 'Y',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        if ($updatecountry) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Update Master Country', $this->micro);
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

        $delete = country::where('id', $id)->update([
            'aktif'       => 'N',
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => Session::get('session')['user_nik']
        ]);

        if ($delete) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Delete Master Country', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Deleted'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Deleted'];
            return response()->json($status, 200);
        }
    }
}
