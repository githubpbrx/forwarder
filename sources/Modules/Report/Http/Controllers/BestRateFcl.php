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
        // dd($request->periode);
        Session::put(['sesper' => $request->periode]);

        $exp = explode("/", $request->periode);
        $awal = $exp[0];
        $akhir = $exp[1];

        $mapping = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('periodeawal', $awal)->where('periodeakhir', $akhir)->where('aktif', 'Y')->orderby('id_country', 'asc')->get();

        $databest = array();
        foreach ($mapping as $keys => $map) {
            $datainput = modelinputratefcl::with(['masterfwd'])->where('id_mappingrate', $map->id)->where('aktif', 'Y')->get(['id_forwarder', 'of_20 as bestof20', 'of_40 as bestof40', 'of_40hc as bestof40hc', 'lb_20 as bestlb20', 'lb_40 as bestlb40', 'lb_40hc as bestlb40hc']);
            if ($datainput == null) {
                $db['bestof_20']   = '-';
                $db['bestof_40']   = '-';
                $db['bestof_40hc'] = '-';
                $db['bestlb_20']   = '-';
                $db['bestlb_40']   = '-';
                $db['bestlb_40hc'] = '-';
            } else {
                $db['bestof_20']   = $this->whereData($datainput, 'bestof20');
                $db['bestof_40']   = $this->whereData($datainput, 'bestof40');
                $db['bestof_40hc'] = $this->whereData($datainput, 'bestof40hc');
                $db['bestlb_20']   = $this->whereData($datainput, 'bestlb20');
                $db['bestlb_40']   = $this->whereData($datainput, 'bestlb40');
                $db['bestlb_40hc'] = $this->whereData($datainput, 'bestlb40hc');
            }
            // dump($db);
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

    public function whereData($datainput, $label)
    {
        $datas = $datainput->where("$label", '!=', '')->sortBy("$label")->first();
        $arr = $datas ? $datas->toArray() : [];

        // if (count($arr) > 0) {
        //     $res = array_values(array_filter($datainput->toArray(), function ($val) use ($label, $arr) {
        //         return $val[$label] == $arr[$label];
        //     }));

        //     $name = [];
        //     foreach ($res as $keyR => $ress) {
        //         $name[] = $ress['masterfwd']['name'];
        //     }

        //     $implode = implode(' - ', $name);
        //     $arr['masterfwd']['name'] = $implode;
        // }

        return count($arr) > 0 ? $arr : $this->dataisNull();

        // return $datas ? $datas->toArray() : $this->dataisNull();
    }

    public function dataisNull()
    {
        $db['masterfwd']  = null;
        $db['bestof20']   = null;
        $db['bestof40']   = null;
        $db['bestof40hc'] = null;
        $db['bestlb20']   = null;
        $db['bestlb40']   = null;
        $db['bestlb40hc'] = null;
        return $db;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function getexcel()
    {
        $periode = Session::get('sesper');
        // dd($periode);
        $exp = explode("/", $periode);
        $awal = $exp[0];
        $akhir = $exp[1];

        $mapping = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('periodeawal', $awal)->where('periodeakhir', $akhir)->where('aktif', 'Y')->orderby('id_country', 'asc')->get();

        $databest = array();
        foreach ($mapping as $keys => $map) {
            $datainput = modelinputratefcl::with(['masterfwd'])->where('id_mappingrate', $map->id)->where('aktif', 'Y')->get(['id_forwarder', 'of_20 as bestof20', 'of_40 as bestof40', 'of_40hc as bestof40hc', 'lb_20 as bestlb20', 'lb_40 as bestlb40', 'lb_40hc as bestlb40hc']);
            if ($datainput == null) {
                $db['bestof_20']   = '-';
                $db['bestof_40']   = '-';
                $db['bestof_40hc'] = '-';
                $db['bestlb_20']   = '-';
                $db['bestlb_40']   = '-';
                $db['bestlb_40hc'] = '-';
            } else {
                $db['bestof_20']   = $this->whereData($datainput, 'bestof20');
                $db['bestof_40']   = $this->whereData($datainput, 'bestof40');
                $db['bestof_40hc'] = $this->whereData($datainput, 'bestof40hc');
                $db['bestlb_20']   = $this->whereData($datainput, 'bestlb20');
                $db['bestlb_40']   = $this->whereData($datainput, 'bestlb40');
                $db['bestlb_40hc'] = $this->whereData($datainput, 'bestlb40hc');
            }
            // dump($db);
            array_push($databest, $db);
            unset($db);
        }

        // dd($databest);
        $data = array(
            'mapping' => $mapping,
            'data'   => $databest,
        );

        return view('report::bestratefcl.excel', $data);
    }
}
