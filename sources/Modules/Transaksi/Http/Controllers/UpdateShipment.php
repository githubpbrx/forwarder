<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\System\Helpers\LogActivity;
use Modules\Transaksi\Models\modelformpo;
use Modules\Transaksi\Models\modelformshipment;

class UpdateShipment extends Controller
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
            'title' => 'Data Update Shipment',
            'menu'  => 'datashipment',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Data Shipment', $this->micro);
        return view('transaksi::updateshipment.datashipment', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listshipment(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if ($request->ajax()) {
            $data = modelformpo::join('po', 'po.id', 'formpo.idpo')
                ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                ->join('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
                ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                ->where('formpo.statusformpo', '=', 'confirm')
                ->where('privilege.privilege_aktif', 'Y')->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')
                ->groupby('formpo.kode_booking')
                ->groupby('formshipment.noinv')
                ->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('pono', function ($data) {
                    $mydatapo = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
                        ->join('po', 'po.id', 'formpo.idpo')
                        ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                        ->where('formpo.kode_booking', $data->kode_booking)
                        ->where('formshipment.noinv', $data->noinv)
                        ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                        ->where('formpo.statusformpo', 'confirm')
                        ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('formshipment.aktif', 'Y')
                        ->selectRaw('po.pono')
                        ->groupby('po.pono')
                        ->pluck('po.pono');
                    return  str_replace("]", "", str_replace("[", "", str_replace('"', " ", $mydatapo)));
                })
                ->addColumn('kodebook', function ($data) {
                    return $data->kode_booking;
                })
                ->addColumn('inv', function ($data) {
                    return $data->noinv;
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button = '<center><a href="#" data-id="' . $data->noinv . '" id="detailbtn"><i data-tooltip="tooltip" title="Edit Shipment" class="fa fa-edit fa-lg"></i></a></center>';

                    return $button;
                })
                // ->rawColumns(['status'])
                ->make(true);
        }
        // return view('transaksi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function getdatashipment(Request $request)
    {
        // dd($request);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $getgroup = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('formshipment.noinv', $request->id)
            // ->where('formpo.kode_booking', $request->id)
            ->where('formpo.aktif', 'Y')
            ->where('formshipment.aktif', 'Y')
            ->groupby('formshipment.noinv')
            ->selectRaw(' formshipment.idformpo, formshipment.noinv, formshipment.nomor_bl ')
            ->get();

        $ship = [];
        foreach ($getgroup as $key => $lue) {
            $getshipment = modelformshipment::with(['withformpo' => function ($var) {
                $var->with(['withpo' => function ($hs) {
                    $hs->with(['hscode']);
                }]);
            }])
                ->where('formshipment.noinv', $lue->noinv)
                ->where('formshipment.aktif', 'Y')
                ->get();

            array_push($ship, $getshipment);
        }
        // dd($ship);

        $newship = [];
        foreach ($ship[0] as $key => $valu) {
            $shipmentnew = modelformshipment::with(['withformpo' => function ($var) {
                $var->with(['withpo' => function ($hs) {
                    $hs->with(['hscode']);
                }]);
            }])
                ->where('formshipment.idformpo', $valu->idformpo)
                ->where('formshipment.aktif', 'Y')
                ->selectRaw(' sum(qty_shipment) as qtyshipment, idformpo ')
                ->get();

            array_push($newship, $shipmentnew);
        }

        $data = [
            'shipment' => $ship,
            'groupbl' => $getgroup,
            'remaining' => $newship
        ];

        $form = view('transaksi::updateshipment.modaldatashipment', ['data' => $data]);
        return $form->render();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    public function updateshipment(Request $request)
    {
        // dd($request);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        DB::beginTransaction();
        $decode = json_decode($request->dataform);

        foreach ($decode as $key => $val) {
            $filebl = $request->file('filebl');
            if ($filebl != null) {
                $originalNamebl = str_replace(' ', '_', $filebl->getClientOriginalName());
                $fileNamebl = time() . '_' . $originalNamebl;
                Storage::disk('local')->put($fileNamebl, file_get_contents($filebl));
            } else {
                $namefilebl = modelformshipment::where('id_shipment', $val->idshipment)->where('aktif', 'Y')->first();
                $fileNamebl = $namefilebl->file_bl;
            }

            $fileinv = $request->file('fileinv');
            if ($fileinv != null) {
                $originalNameinv = str_replace(' ', '_', $fileinv->getClientOriginalName());
                $fileNameinv = time() . '_' . $originalNameinv;
                Storage::disk('local')->put($fileNameinv, file_get_contents($fileinv));
            } else {
                $namefileinv = modelformshipment::where('id_shipment', $val->idshipment)->where('aktif', 'Y')->first();
                $fileNameinv = $namefileinv->file_invoice;
            }

            $filepack = $request->file('filepacking');
            if ($filepack != null) {
                $originalNamepack = str_replace(' ', '_', $filepack->getClientOriginalName());
                $fileNamepack = time() . '_' . $originalNamepack;
                Storage::disk('local')->put($fileNamepack, file_get_contents($filepack));
            } else {
                $namefilepack = modelformshipment::where('id_shipment', $val->idshipment)->where('aktif', 'Y')->first();
                $fileNamepack = $namefilepack->file_packinglist;
            }

            $updateship = modelformshipment::where('id_shipment', $val->idshipment)->where('aktif', 'Y')->update([
                'noinv'            => $request->inv,
                'etdfix'           => $request->etd,
                'etafix'           => $request->eta,
                'file_bl'          => $fileNamebl,
                'file_invoice'     => $fileNameinv,
                'file_packinglist' => $fileNamepack,
                'nomor_bl'         => $request->nomorbl,
                'vessel'           => $request->vessel,
                'aktif'            => 'Y',
                'updated_at'       => date('Y-m-d H:i:s'),
                'updated_by'       => Session::get('session')['user_nik']
            ]);

            if ($updateship) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        if (empty($gagal)) {
            DB::commit();
            LogActivity::addToLog('Web Forwarder :: Forwarder : Save Update Shipment', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Updated'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Updated'];
            return response()->json($status, 200);
        }
    }
}
