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
        $data = [
            'title'     => 'List Mapping Rate FCL',
            'menu'      => 'mappingratefcl'
        ];

        LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Mapping Rate FCL', $this->micro);
        return view('transaksi::mappingratefcl', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listmapping()
    {
        $data = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('aktif', 'Y')->select('id', 'id_country', 'id_polcity', 'id_podcity', 'id_shippingline', 'periodeawal', 'periodeakhir', 'expired_date')->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('namecountry', function ($data) {
                return $data->country->country;
            })
            ->addColumn('namepolcity', function ($data) {
                return $data->polcity->city;
            })
            ->addColumn('namepodcity', function ($data) {
                return $data->podcity->city;
            })
            ->addColumn('nameshipping', function ($data) {
                return $data->shipping->name;
            })
            ->addColumn('periode', function ($data) {
                $awal = $data->periodeawal ? Carbon::parse($data->periodeawal)->format('d M') : '';
                $akhir = $data->periodeakhir ? Carbon::parse($data->periodeakhir)->format('d M') : '';
                return $awal . ' - ' . $akhir;
            })
            ->addColumn('expireddate', function ($data) {
                $expired = $data->expired_date ? Carbon::parse($data->expired_date)->format('d M Y') : '';
                return $expired;
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

    public function getpolcity(Request $request)
    {
        $idcountry = $request->idcountry;

        $datapolcity = pol_city::where('id_country', $idcountry)->where('aktif', 'Y')->get(['id', 'id_country', 'city']);
        // dd($datapolcity);

        return response()->json($datapolcity);
    }

    public function getpodcity(Request $request)
    {
        $idpolcity = $request->idpol;

        $datapodcity = pod_city::where('id_polcity', $idpolcity)->where('aktif', 'Y')->get(['id', 'id_polcity', 'city']);
        // dd($datapodcity);

        return response()->json($datapodcity);
    }

    public function getshipping(Request $request)
    {
        $idpodcity = $request->idpod;

        $dataship = shippingline::where('id_podcity', $idpodcity)->where('aktif', 'Y')->get(['id', 'id_podcity', 'name']);
        // dd($dataship);

        return response()->json($dataship);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        // dd($request);
        $idcountry = $request->idcountry;
        $idpolcity = $request->idpol;
        $idpodcity   = $request->idpod;
        $idshipping   = $request->idship;
        $periodeawal = Carbon::now()->startOfMonth()->toDateString();
        $periodeakhir = Carbon::now()->endOfMonth()->toDateString();
        $expireddate = $request->setdate;

        if ($idcountry == '' || $idcountry == null || $idpolcity == '' || $idpolcity == null || $idpodcity == '' || $idpodcity == null || $idshipping == '' || $idshipping == null || $expireddate == '' || $expireddate == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Some Column is Empty, please input data'];
            return response()->json($status, 200);
        }

        $cekship = modelmappingratefcl::where('id_country', $idcountry)->where('id_polcity', $idpolcity)->where('id_podcity', $idpodcity)->where('id_shippingline', $idshipping)->where('aktif', 'Y')->first();
        if ($cekship != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Mapping is available, please check again'];
            return response()->json($status, 200);
        }

        $saved = modelmappingratefcl::insert([
            'id_country'      => $idcountry,
            'id_polcity'      => $idpolcity,
            'id_podcity'      => $idpodcity,
            'id_shippingline' => $idshipping,
            'periodeawal'     => $periodeawal,
            'periodeakhir'    => $periodeakhir,
            'expired_date'    => $expireddate,
            'aktif'           => 'Y',
            'created_at'      => date('Y-m-d H:i:s'),
            'created_by'      => Session::get('session')['user_nik']
        ]);

        if ($saved) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Save Mapping Rate FCL', $this->micro);
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
        $data = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('id', $id)->where('aktif', 'Y')->first();

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
        $id        = $request->id;
        $idcountry = $request->idcountry;
        $idpolcity = $request->idpol;
        $idpodcity = $request->idpod;
        $idshipping = $request->idship;
        $expireddate = $request->setdate;

        if ($idcountry == '' || $idcountry == null || $idpolcity == '' || $idpolcity == null || $idpodcity == '' || $idpodcity == null || $idshipping == '' || $idshipping == null || $expireddate == '' || $expireddate == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Some Column is Empty, please input data'];
            return response()->json($status, 200);
        }

        $cekship = modelmappingratefcl::where('id', '!=', $id)->where('id_country', $idcountry)->where('id_polcity', $idpolcity)->where('id_podcity', $idpodcity)->where('id_shippingline', $idshipping)->where('aktif', 'Y')->first();
        if ($cekship != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Shipping Line is available, please check again'];
            return response()->json($status, 200);
        }

        $updateship = modelmappingratefcl::where('id', $request->id)->update([
            'id_country'      => $idcountry,
            'id_polcity'      => $idpolcity,
            'id_podcity'      => $idpodcity,
            'id_shippingline' => $idshipping,
            'expired_date'    => $expireddate,
            'aktif'           => 'Y',
            'updated_at'      => date('Y-m-d H:i:s'),
            'updated_by'      => Session::get('session')['user_nik']
        ]);

        if ($updateship) {
            LogActivity::addToLog('Web Forwarder :: Logistik : Update Mapping Line', $this->micro);
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

        $delete = modelmappingratefcl::where('id', $id)->update([
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
