<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Modules\System\Helpers\LogActivity;

use Modules\System\Models\masterforwarder;
use Modules\Transaksi\Models\modelpo;
use Modules\Transaksi\Models\modelformpo;
use Modules\Transaksi\Models\modelformshipment;
use Modules\Transaksi\Models\modelmappingratefcl;
use Modules\Transaksi\Models\modelinputratefcl;

class ResultRateAdmin extends Controller
{
    protected $micro;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->micro = microtime(true);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = array(
            'title' => 'List Result Rate FCL',
            'menu'  => 'resultratefcladmin',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Result Rate FCL Admin', $this->micro);
        return view('report::resultratefcladmin', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function getexcel(Request $request)
    {
        dd($request);
        if ($request->ajax()) {
            $data = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('aktif', 'Y')->select('id', 'id_country', 'id_polcity', 'id_podcity', 'id_shippingline', 'periodeawal', 'periodeakhir')->get();
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
                    $awal = Carbon::parse($data->periodeawal)->format('d M');
                    $akhir = Carbon::parse($data->periodeakhir)->format('d M');
                    return $awal . ' - ' . $akhir;
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button = '<a href="#" data-id="' . $data->id . '" id="detailbtn"><i class="fa fa-angle-double-right text-green"></i></a>';

                    // $button = '<a href="' . route('detail_allocation', ['id' => $idku]) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                    return $button;
                })
                // ->rawColumns(['status'])
                ->make(true);
        }
    }
}
