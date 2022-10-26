<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Transaksi\Models\mastersupplier;
use Modules\Transaksi\Models\masterforwarder;
use Modules\Transaksi\Models\modelpo;
use Modules\Transaksi\Models\modelforwarder;
use Modules\Transaksi\Models\modelformpo;
use Modules\Transaksi\Models\modelformshipment;


class DataShipment extends Controller
{
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
            'title' => 'Data Shipment',
            'menu'  => 'datashipment',
            'box'   => '',
        );

        \LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Data Shipment', $this->micro);
        return view('transaksi::datashipment', $data);
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
                ->groupby('po.pono')->groupby('formshipment.noinv')
                ->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('inv', function ($data) {
                    return $data->noinv;
                })
                ->addColumn('action', function ($data) {
                    $idku = $data->pono;
                    $button = '';

                    $button = '<a href="#" data-id="' . $data->noinv . '" id="detailbtn"><i data-tooltip="tooltip" title="Edit Shipment" class="fa fa-edit fa-lg"></i></a>';

                    // $button = '<a href="' . route('detail_allocation', ['id' => $idku]) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                    return $button;
                })
                // ->rawColumns(['poku', 'date', 'material', 'status', 'action'])
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

        $getshipment = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('noinv', $request->id)
            ->where('formpo.aktif', 'Y')->where('formshipment.aktif', 'Y')
            // ->selectRaw(' sum(qty_shipment) as qtyshipment, po.id, po.pono, po.matcontents, po.colorcode, po.size, po.style, po.qtypo ')
            ->get();
        // dd($getshipment);

        $arr = [];
        foreach ($getshipment as $key => $val) {
            $getremain = modelformshipment::where('idformpo', $val->idformpo)
                ->selectRaw('sum(qty_shipment) as qtyshipment')
                ->groupby('idformpo')
                ->get();

            array_push($arr, $getremain);
        }
        // dd($arr);

        $data = [
            'shipment' => $getshipment,
            'remaining' => $arr
        ];

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
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

        $decode = json_decode($request->dataform);
        // dd($request, $decode);

        // $cekshipment = modelformshipment::where('id_shipment', '!=', $request->idshipment)->where('noinv', $request->inv)->where('aktif', 'Y')->first();
        // if ($cekshipment != null) {
        //     // DB::rollback();
        //     $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Invoice is Already Exist'];
        //     return response()->json($status, 200);
        // }

        foreach ($decode as $key => $val) {
            $qtydefault = ($val->value == '') ? '0' : $val->value;
            $file = $request->file('file');
            if ($file != null) {
                $originalName = str_replace(' ', '_', $file->getClientOriginalName());
                $fileName = time() . '_' . $originalName;
                Storage::disk('local')->put($fileName, file_get_contents($request->file));
            } else {
                $namefile = modelformshipment::where('id_shipment', $val->idshipment)->where('aktif', 'Y')->first();
                $fileName = $namefile->file_bl;
            }

            $cekformpo = modelformpo::where('id_formpo', $val->idformpo)->where('aktif', 'Y')->first();
            $cekpo = modelpo::where('id', $cekformpo->idpo)->first();
            $qtypo = (float)$cekpo->qtypo;

            $cekqtyshipment = modelformshipment::where('idformpo', $val->idformpo)->where('aktif', 'Y')->selectRaw(' sum(qty_shipment) as jml ')->first();
            $jumlahexist = ($cekqtyshipment->jml == null) ? 0 : $cekqtyshipment->jml;

            $jumlahall = $qtydefault + $jumlahexist;

            $cekdata = modelformshipment::where('id_shipment', $val->idshipment)->where('aktif', 'Y')->select('qty_shipment')->first();
            $jumlahfix = $jumlahall - $cekdata->qty_shipment;
            // dd($qtypo, $jumlahall, $jumlahfix);

            if ($jumlahfix > $qtypo) {
                $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data Quantity Allocation Over Quantity PO'];
                return response()->json($status, 200);
            }

            if ($jumlahfix == $qtypo) {
                $status = 'full_allocated';
            } else {
                $status = 'partial_allocated';
            }

            if ($qtydefault == '0') {
                $updateship = modelformshipment::where('id_shipment', $val->idshipment)->where('aktif', 'Y')->update([
                    'noinv'        => $request->inv,
                    'qty_shipment' => $qtydefault,
                    'etdfix'       => $request->etd,
                    'etafix'       => $request->eta,
                    'file_bl'      => $fileName,
                    'nomor_bl'     => $request->nomorbl,
                    'vessel'       => $request->vessel,
                    'statusshipment' => $status,
                    'aktif'        => 'N',
                    'updated_at'   => date('Y-m-d H:i:s'),
                    'updated_by'   => Session::get('session')['user_nik']
                ]);
            } else {
                $updateship = modelformshipment::where('id_shipment', $val->idshipment)->where('aktif', 'Y')->update([
                    'noinv'        => $request->inv,
                    'qty_shipment' => $val->value,
                    'etdfix'       => $request->etd,
                    'etafix'       => $request->eta,
                    'file_bl'      => $fileName,
                    'nomor_bl'     => $request->nomorbl,
                    'vessel'       => $request->vessel,
                    'statusshipment' => $status,
                    'aktif'        => 'Y',
                    'updated_at'   => date('Y-m-d H:i:s'),
                    'updated_by'   => Session::get('session')['user_nik']
                ]);
            }

            if ($updateship) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        if (empty($gagal)) {
            \LogActivity::addToLog('Web Forwarder :: Forwarder : Save Update Shipment', $this->micro);
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
    // public function getforwarder(Request $request)
    // {
    //     header("Access-Control-Allow-Origin: *");
    //     header("Access-Control-Allow-Headers: *");

    //     if (!$request->ajax()) return;
    //     $po = forward::select('id', 'name');
    //     if ($request->has('q')) {
    //         $search = $request->q;
    //         $po = $po->whereRaw(' name like "%' . $search . '%" ');
    //     }

    //     $po = $po->where('aktif', '=', 'Y')->orderby('name', 'asc')->get();
    //     // dd($po);
    //     return response()->json($po);

    //     // return view('transaksi::edit');
    // }

    // public function getsupplier(Request $request)
    // {
    //     header("Access-Control-Allow-Origin: *");
    //     header("Access-Control-Allow-Headers: *");

    //     if (!$request->ajax()) return;
    //     $po = supplier::select('id', 'nama');
    //     if ($request->has('q')) {
    //         $search = $request->q;
    //         $po = $po->whereRaw(' nama like "%' . $search . '%" ');
    //     }

    //     $po = $po->where('aktif', '=', 'Y')->orderby('nama', 'asc')->get();

    //     return response()->json($po);
    // }
}
