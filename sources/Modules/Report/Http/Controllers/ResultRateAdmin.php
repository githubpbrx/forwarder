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
        $getyear = modelmappingratefcl::groupby('periodeawal')->groupby('periodeakhir')->where('aktif', 'Y')->get();
        $year = [];
        foreach ($getyear as $key => $yr) {
            $get = date('Y', strtotime($yr->periodeawal));
            array_push($year, $get);
        }

        $month = [];
        for ($m = 1; $m <= 12; $m++) {
            $dt = $m;
            if ($m < 10) {
                $dt = '0' . $m;
            }
            $month[$dt] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }


        // dd(count($master));

        // $grupcountry =  modelmappingratefcl::with(['country'])->groupby('id_country')->where('aktif', 'Y')->get();
        // $listct = [];
        // foreach ($grupcountry as $key => $gct) {
        //     $listcountry = modelmappingratefcl::with(['country'])->where('id_country', $gct->id_country)->where('aktif', 'Y')->get();
        //     array_push($listct, $gct->id_country);
        // }
        // dd($listct);

        $data = array(
            'title'  => 'List Result Rate FCL',
            'menu'   => 'resultratefcladmin',
            'box'    => '',
            'year'  => $year,
            'month'  => $month
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Result Rate FCL Admin', $this->micro);
        return view('report::resultfcladmin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function getreport(Request $request)
    {
        // dd($request);
        $date = $request->year . '-' . $request->month;

        $datafwd = modelinputratefcl::with(['masterfwd'])->groupby('id_forwarder')->where('aktif', 'Y')->get(); //get id forwarder
        $master = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('periodeawal', 'LIKE', '%' . $date . '%')->where('aktif', 'Y')->orderby('id_country', 'asc')->get(); //get all data
        // dd($master);

        foreach ($datafwd as $key => $val) {
            $data[$val->id_forwarder] = array();
            foreach ($master as $keys => $val2) {
                $datafcl = modelinputratefcl::where('id_forwarder', $val->id_forwarder)->where('id_mappingrate', $val2->id)->where('aktif', 'Y')->first();
                // dd($datafcl);
                if ($datafcl == null) {
                    $d['of_20']   = '-';
                    $d['of_40']   = '-';
                    $d['of_40hc'] = '-';
                    $d['lb_20']   = '-';
                    $d['lb_40']   = '-';
                    $d['lb_40hc'] = '-';
                } else {
                    $d['of_20']   = $datafcl->of_20;
                    $d['of_40']   = $datafcl->of_40;
                    $d['of_40hc'] = $datafcl->of_40hc;
                    $d['lb_20']   = $datafcl->lb_20;
                    $d['lb_40']   = $datafcl->lb_40;
                    $d['lb_40hc'] = $datafcl->lb_40hc;
                }
                array_push($data[$val->id_forwarder], $d);
                unset($d);
            }
        }

        $datamin = array();
        foreach ($master as $keys => $vm) {
            $seldatamin = modelinputratefcl::with(['masterfwd'])->where('id_mappingrate', $vm->id)->where('aktif', 'Y');
            if ($seldatamin == null) {
                $dm['minof_20']   = '-';
                $dm['minof_40']   = '-';
                $dm['minof_40hc'] = '-';
                $dm['minlb_20']   = '-';
                $dm['minlb_40']   = '-';
                $dm['minlb_40hc'] = '-';
            } else {
                $dm['minof_20']   = $seldatamin->where('of_20', '!=', '')->selectRaw(' id_forwarder,MIN(of_20) as minof20 ')->first();
                $dm['minof_40']   = $seldatamin->where('of_40', '!=', '')->selectRaw(' id_forwarder,MIN(of_40) as minof40 ')->first();
                $dm['minof_40hc'] = $seldatamin->where('of_40hc', '!=', '')->selectRaw(' id_forwarder,MIN(of_40hc) as minof40hc ')->first();
                $dm['minlb_20']   = $seldatamin->where('lb_20', '!=', '')->selectRaw(' id_forwarder,MIN(lb_20) as minlb20 ')->first();
                $dm['minlb_40']   = $seldatamin->where('lb_40', '!=', '')->selectRaw(' id_forwarder,MIN(lb_40) as minlb40 ')->first();
                $dm['minlb_40hc'] = $seldatamin->where('lb_40hc', '!=', '')->selectRaw(' id_forwarder,MIN(lb_40hc) as minlb40hc ')->first();
            }
            array_push($datamin, $dm);
            unset($dm);
        }

        // $masters = modelmappingratefcl::where('periodeawal', 'LIKE', '%' . $date . '%')->where('aktif', 'Y')->selectraw('count(id) as jml, id_country')->groupby('id_country')->orderby('id_country', 'asc')->get();
        // dd($masters);
        // $masterpol = modelmappingratefcl::whereIn('id_country', ['1', '4'])->selectraw('count(id) as jml, id_polcity')->groupby('id_polcity')->orderby('id_polcity', 'asc')->where('aktif', 'Y')->get();
        // dd($masterpol);

        $data = array(
            'data'   => $data,
            'fwd'    => $datafwd,
            'master' => $master,
            'min'    => $datamin,
            // 'row'    => $masters,
            // 'rowpol' => $masterpol
        );

        $form = view('report::resultfcladmin.getresult', $data);
        return $form->render();
    }
}
