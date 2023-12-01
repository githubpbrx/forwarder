<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

use Modules\System\Helpers\LogActivity;
use Modules\Transaksi\Models\modelmappingratefcl;
use Modules\Master\Models\mastercountry as country;
use Modules\Master\Models\masterpod_city as pod_city;
use Modules\Master\Models\masterpol_city as pol_city;
use Modules\Master\Models\mastershippingline as shippingline;

class MappingRate extends Controller
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
        $periodeawal = Carbon::now()->startOfMonth()->toDateString();
        $periodeakhir = Carbon::now()->endOfMonth()->toDateString();
        $periode[] = date('d M Y', strtotime($periodeawal)) . ' - ' . date('d M Y', strtotime($periodeakhir));
        // dd($periode);
        $data = [
            'title'     => 'List Mapping Rate FCL',
            'menu'      => 'mappingratefcl',
            'periode'   => $periode
        ];

        LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Mapping Rate FCL', $this->micro);
        return view('transaksi::mappingrate.mappingratefcl', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listmapping()
    {
        $data = modelmappingratefcl::where('aktif', 'Y')->select('periodeawal', 'periodeakhir', 'expired_date')->groupby('periodeawal')->groupby('periodeakhir')->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('periode', function ($data) {
                $awal = $data->periodeawal ? Carbon::parse($data->periodeawal)->format('d M Y') : '';
                $akhir = $data->periodeakhir ? Carbon::parse($data->periodeakhir)->format('d M Y') : '';
                return $awal . ' - ' . $akhir;
            })
            ->addColumn('expireddate', function ($data) {
                $expired = $data->expired_date ? Carbon::parse($data->expired_date)->format('d M Y') : '';
                return $expired;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                $button .= '<a href="#" data-tooltip="tooltip" data-id="' . encrypt($data->periodeawal) . '" id="infodata" title="Info Data"><i class="fa fa-info fa-lg text-info actiona"></i></a>';
                $button .= '&nbsp;&nbsp;';
                $button .= '<a href="#" data-tooltip="tooltip" data-id="' . encrypt($data->periodeawal) . '" id="editdata" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                $button .= '&nbsp;';
                $button .= '<a href="#" data-id="' . encrypt($data->periodeawal) . '" id="delbtn" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';

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
        $periodeawal = Carbon::now()->startOfMonth()->toDateString();
        $periodeakhir = Carbon::now()->endOfMonth()->toDateString();
        $expireddate = $request->setdate;

        if ($expireddate == '' || $expireddate == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Some Column is Empty, please input data'];
            return response()->json($status, 200);
        }

        $cekship = modelmappingratefcl::where('periodeawal', $periodeawal)->where('periodeakhir', $periodeakhir)->where('aktif', 'Y')->first();
        if ($cekship != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Mapping is available, please check again'];
            return response()->json($status, 200);
        }

        try {
            $getship = shippingline::where('aktif', 'Y')->get();
            foreach ($getship as $key => $val) {
                modelmappingratefcl::insert([
                    'id_country'      => $val->id_country,
                    'id_polcity'      => $val->id_polcity,
                    'id_podcity'      => $val->id_podcity,
                    'id_shippingline' => $val->id,
                    'periodeawal'     => $periodeawal,
                    'periodeakhir'    => $periodeakhir,
                    'expired_date'    => $expireddate,
                    'aktif'           => 'Y',
                    'created_at'      => date('Y-m-d H:i:s'),
                    'created_by'      => Session::get('session')['user_nik']
                ]);
            }

            LogActivity::addToLog('Web Forwarder :: Logistik : Save Mapping Rate FCL', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } catch (\Exception $e) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved "' . $e . '" '];
            return response()->json($status, 200);
        }
    }

    public function info(Request $request)
    {
        $periodeawal = decrypt($request->id);

        $getdata = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('aktif', 'Y')->select('id', 'id_country', 'id_polcity', 'id_podcity', 'id_shippingline', 'periodeawal', 'periodeakhir', 'expired_date')->where('periodeawal', $periodeawal)->get();

        $form = view('transaksi::mappingrate.modalinfo', ['data' => $getdata]);
        return $form->render();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request)
    {
        $id = decrypt($request->id);
        // dd($id);
        // $data = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('id', $id)->where('aktif', 'Y')->first();

        return response()->json(['status' => 200, 'data' => $id, 'message' => 'Berhasil']);
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
        $id        = $request->id;
        $expireddate = $request->setdate;

        if ($expireddate == '' || $expireddate == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Some Column is Empty, please input data'];
            return response()->json($status, 200);
        }

        // $cekship = modelmappingratefcl::where('id', '!=', $id)->where('id_country', $idcountry)->where('id_polcity', $idpolcity)->where('id_podcity', $idpodcity)->where('id_shippingline', $idshipping)->where('aktif', 'Y')->first();
        // if ($cekship != null) {
        //     $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Shipping Line is available, please check again'];
        //     return response()->json($status, 200);
        // }

        $updateship = modelmappingratefcl::where('periodeawal', $id)->where('aktif', 'Y')->update([
            'expired_date'    => $expireddate,
            'aktif'           => 'Y',
            'updated_at'      => date('Y-m-d H:i:s'),
            'updated_by'      => Session::get('session')['user_nik']
        ]);

        if ($updateship) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Update Mapping Rate', $this->micro);
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

        $delete = modelmappingratefcl::where('periodeawal', $id)->where('aktif', 'Y')->update([
            'aktif'       => 'N',
            'updated_at'  => date('Y-m-d H:i:s'),
            'updated_by'  => Session::get('session')['user_nik']
        ]);

        if ($delete) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Delete Mapping Rate FCL', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Deleted'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Deleted'];
            return response()->json($status, 200);
        }
    }
}
