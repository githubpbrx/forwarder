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

class BestRateFcl extends Controller
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
        $getperiode = modelmappingratefcl::groupby('periodeawal')->groupby('periodeakhir')->where('aktif', 'Y')->get(['periodeawal', 'periodeakhir']);
        // dd($getperiode);
        $data = array(
            'title' => 'List Best Rate FCL',
            'menu'  => 'bestratefcl',
            'box'   => '',
            'periode' => $getperiode,
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Best Rate FCL', $this->micro);
        return view('report::bestratefcl.index', $data);
    }

    function getbestrate(Request $request)
    {
        // dd($request);
        $exp = explode("/", $request->periode);
        $awal = $exp[0];
        $akhir = $exp[1];

        $mapping = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('periodeawal', $awal)->where('periodeakhir', $akhir)->where('aktif', 'Y')->orderby('id_country', 'asc')->get();

        $databest = array();
        foreach ($mapping as $keys => $map) {
            $datainput = modelinputratefcl::with(['masterfwd'])->where('id_mappingrate', $map->id)->where('aktif', 'Y');
            if ($datainput == null) {
                $db['bestof_20']   = '-';
                $db['bestof_40']   = '-';
                $db['bestof_40hc'] = '-';
                $db['bestlb_20']   = '-';
                $db['bestlb_40']   = '-';
                $db['bestlb_40hc'] = '-';
            } else {
                $db['bestof_20']   = $datainput->where('of_20', '!=', '')->selectRaw(' id_forwarder,MIN(of_20) as bestof20 ')->first();
                $db['bestof_40']   = $datainput->where('of_40', '!=', '')->selectRaw(' id_forwarder,MIN(of_40) as bestof40 ')->first();
                $db['bestof_40hc'] = $datainput->where('of_40hc', '!=', '')->selectRaw(' id_forwarder,MIN(of_40hc) as bestof40hc ')->first();
                $db['bestlb_20']   = $datainput->where('lb_20', '!=', '')->selectRaw(' id_forwarder,MIN(lb_20) as bestlb20 ')->first();
                $db['bestlb_40']   = $datainput->where('lb_40', '!=', '')->selectRaw(' id_forwarder,MIN(lb_40) as bestlb40 ')->first();
                $db['bestlb_40hc'] = $datainput->where('lb_40hc', '!=', '')->selectRaw(' id_forwarder,MIN(lb_40hc) as bestlb40hc ')->first();
            }
            array_push($databest, $db);
            unset($db);
        }
        // dd($databest);
        $data = array(
            'mapping' => $mapping,
            'data'   => $databest,
        );

        $form = view('report::bestratefcl.getresult', $data);
        return $form->render();
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
