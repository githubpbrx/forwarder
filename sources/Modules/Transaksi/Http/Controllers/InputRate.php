<?php

namespace Modules\Transaksi\Http\Controllers;

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

class InputRate extends Controller
{
    protected $micro;
    public function __construct()
    {
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Origin: *");
        $this->micro = microtime(true);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = array(
            'title' => 'List Input Rate FCL',
            'menu'  => 'inputratefcl',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Input Rate FCL', $this->micro);
        return view('transaksi::inputratefcl', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listinput(Request $request)
    {
        if ($request->ajax()) {
            // dd($data);
            $cekdata = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('aktif', 'Y')->select('id', 'id_country', 'id_polcity', 'id_podcity', 'id_shippingline', 'periodeawal', 'periodeakhir', 'expired_date')->groupby('periodeawal', 'periodeakhir')->get();

            $data = [];
            foreach ($cekdata as $key => $val) {
                $datenow = Carbon::parse(Carbon::now()->format('Y-m-d'));
                $expired = Carbon::parse(Carbon::parse($val->expired_date)->format('Y-m-d'));
                $cekdate = $datenow->lte($expired);
                if ($cekdate) {
                    array_push($data, $val);
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                // ->addColumn('namecountry', function ($data) {
                //     return $data->country->country;
                // })
                // ->addColumn('namepolcity', function ($data) {
                //     return $data->polcity->city;
                // })
                // ->addColumn('namepodcity', function ($data) {
                //     return $data->podcity->city;
                // })
                // ->addColumn('nameshipping', function ($data) {
                //     return $data->shipping->name;
                // })
                ->addColumn('periode', function ($data) {
                    $awal = Carbon::parse($data->periodeawal)->format('d M');
                    $akhir = Carbon::parse($data->periodeakhir)->format('d M');
                    return $awal . ' - ' . $akhir;
                })
                ->addColumn('expireddate', function ($data) {
                    $dateku = Carbon::parse($data->expired_date)->format('d M Y');
                    return $dateku;
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button = '<a href="#" data-id="' . $data->periodeawal . '/' . $data->periodeakhir . '" id="detailbtn"><i class="fa fa-angle-double-right text-green"></i></a>';

                    // $button = '<a href="' . route('detail_allocation', ['id' => $idku]) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                    return $button;
                })
                // ->rawColumns(['status'])
                ->make(true);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function getdatainputrate(Request $request)
    {
        $exp = explode('/', $request->id);
        $awal = $exp[0];
        $akhir = $exp[1];

        $cekfwd = masterforwarder::where('name', Session::get('session')['user_nama'])->where('aktif', 'Y')->first('id');

        $getmapping = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('periodeawal', $awal)->where('periodeakhir', $akhir)->where('aktif', 'Y')->select('id', 'id_country', 'id_polcity', 'id_podcity', 'id_shippingline', 'periodeawal', 'periodeakhir')->get();
        // dd($getmapping);

        $arr = [];
        foreach ($getmapping as $key => $val) {
            $cekinput = modelinputratefcl::where('id_mappingrate', $val->id)->where('id_forwarder', $cekfwd->id)->where('aktif', 'Y')->first();
            if ($cekinput) {
                array_push($arr, $cekinput);
            }
        }
        // dd($getmapping, $arr);
        $nama = Session::get('session')['user_nama'];
        $awal = Carbon::parse($awal)->format('d M');
        $akhir = Carbon::parse($akhir)->format('d M');

        $form = view('transaksi::modalinputratefcl', ['data' => $getmapping, 'datainput' => $arr, 'nama' => $nama, 'awal' => $awal, 'akhir' => $akhir]);
        return $form->render();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    public function add(Request $request)
    {
        $decode = json_decode($request->mydata);
        $statusinput = $request->datainput;
        // dd($statusinput);
        // if ($request->of20 == null || $request->of40 == null || $request->of40hc == null || $request->lb20 == null || $request->lb40 == null || $request->lb40hc == null) {
        //     $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Some Data is Empty, Please Check Your Data'];
        //     return response()->json($status, 200);
        // }

        try {
            $cekfwd = masterforwarder::where('name', Session::get('session')['user_nama'])->where('aktif', 'Y')->first('id');

            $arr = [];
            foreach ($decode as $key1 => $val1) {
                if ($val1->of20 || $val1->of40 || $val1->of40hc || $val1->lb20 || $val1->lb40 || $val1->lb40hc) {
                    $arr[] = 1;
                } else {
                    $arr[] = 0;
                }
            }

            if (array_sum($arr) < 1) {
                throw new \Exception("Please Fill Data", 1);
            }

            foreach ($decode as $key => $val) {
                if ($statusinput == 'not') {
                    $insert = modelinputratefcl::insert([
                        'id_mappingrate' => $val->idmappingrate,
                        'id_forwarder'   => $cekfwd->id,
                        'of_20'          => $val->of20,
                        'of_40'          => $val->of40,
                        'of_40hc'        => $val->of40hc,
                        'lb_20'          => $val->lb20,
                        'lb_40'          => $val->lb40,
                        'lb_40hc'        => $val->lb40hc,
                        'aktif'          => 'Y',
                        'created_at'     => date('Y-m-d H:i:s'),
                        'created_by'     => Session::get('session')['user_nik']
                    ]);
                } else {
                    $update = modelinputratefcl::where('id_mappingrate', $val->idmappingrate)->where('id_forwarder', $cekfwd->id)->where('aktif', 'Y')->update([
                        'of_20'          => $val->of20,
                        'of_40'          => $val->of40,
                        'of_40hc'        => $val->of40hc,
                        'lb_20'          => $val->lb20,
                        'lb_40'          => $val->lb40,
                        'lb_40hc'        => $val->lb40hc,
                        'updated_at'     => date('Y-m-d H:i:s'),
                        'updated_by'     => Session::get('session')['user_nik']
                    ]);
                }
            }

            LogActivity::addToLog('Web Forwarder :: Forwarder : Save Input Rate FCL', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } catch (\Exception $e) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => $e->getMessage()];
            return response()->json($status, 200);
        }
    }
}
