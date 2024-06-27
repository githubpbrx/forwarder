<?php

namespace Modules\Transaksi\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\System\Helpers\LogActivity;
use Modules\Transaksi\Models\modelpo;
use Modules\Transaksi\Models\modelformpo;
use Modules\Transaksi\Models\modelformshipment;

class OutstandingShipment extends Controller
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
            'title' => 'Outstanding Shipment',
            'menu'  => 'processshipment',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Process Shipment', $this->micro);
        return view('transaksi::outstandingshipment.process_shipment', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listshipmentprocess()
    {
        $query = modelformpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'confirm')
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->groupby('formpo.kode_booking')
            ->selectRaw(' formpo.*, sum(formpo.qty_booking) as qtybook, po.id, po.pono, po.matcontents, po.colorcode, po.size, sum(po.qtypo) as qtypoall, po.qtypo')
            ->get();
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('listpo', function ($query) {
                $mydatapo = modelformpo::join('po', 'po.id', 'formpo.idpo')
                    ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                    ->where('formpo.kode_booking', $query->kode_booking)
                    ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                    ->where('formpo.statusformpo', 'confirm')
                    ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
                    ->selectRaw('po.pono')
                    ->groupby('po.pono')
                    ->pluck('po.pono');
                return  str_replace("]", "", str_replace("[", "", str_replace('"', " ", $mydatapo)));
            })
            ->addColumn('nobook', function ($query) {
                return  $query->kode_booking;
            })
            ->addColumn('qtypo', function ($query) {
                return  $query->qtypoall;
            })
            ->addColumn('qtybooking', function ($query) {
                return  $query->qtybook;
            })
            ->addColumn('status', function ($query) {
                if ($query->qtypoall == $query->qtybook) {
                    return 'Full Booking';
                } else {
                    return  'Partial Booking';
                }
            })
            ->addColumn('action', function ($query) {
                $datac = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
                    ->where('formpo.kode_booking', $query->kode_booking)
                    ->where('formpo.statusformpo', 'confirm')
                    ->where('formpo.aktif', 'Y')->where('formshipment.aktif', 'Y')
                    ->selectRaw(' formpo.id_formpo, formshipment.id_shipment')
                    ->first();
                // dd($datac);
                $process    = '';
                if ($datac == NULL) {
                    $process    = '<center><a href="#" data-id="' . $query->kode_booking . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a></center>';
                } else {
                    $process = '';
                }

                return $process;
            })
            // ->rawColumns(['listpo', 'action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function formshipment(Request $request)
    {
        $mydata = modelformpo::with(['withpo' => function ($hs) {
            $hs->with(['hscode', 'supplier']);
        }, 'withforwarder' => function ($priv) {
            $priv->with(['privilege' => function ($lege) {
                $lege->where('privilege_user_nik', Session::get('session')['user_nik']);
            }]);
        }, 'withroute', 'withportloading', 'withportdestination'])
            ->where('formpo.kode_booking', $request->id)
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')
            ->get();

        $mydatapo = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formpo.kode_booking', $request->id)
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->selectRaw('po.pono, po.pino, mastersupplier.nama')
            ->groupby('po.pono')
            ->get();

        // dd($mydata);
        $data = array(
            'dataku' => $mydata,
            'datapo' => $mydatapo
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Input Data Process Shipment', $this->micro);
        $form = view('transaksi::outstandingshipment.modalshipment', ['data' => $data]);
        return $form->render();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function saveshipment(Request $request)
    {
        try {
            $decode = json_decode($request->dataid);

            DB::beginTransaction();

            $filebl = $request->file('filebl');
            $originalNamebl = str_replace(' ', '_', $filebl->getClientOriginalName());
            $fileNamebl = time() . '_' . $originalNamebl;
            Storage::disk('local')->put($fileNamebl, file_get_contents($request->filebl));

            if ($request->file('fileinv')) {
                $fileinv = $request->file('fileinv');
                $originalNameinv = str_replace(' ', '_', $fileinv->getClientOriginalName());
                $fileNameinv = time() . '_' . $originalNameinv;
                Storage::disk('local')->put($fileNameinv, file_get_contents($request->fileinv));
            }

            if ($request->file('filepack')) {
                $filepack = $request->file('filepack');
                $originalNamepack = str_replace(' ', '_', $filepack->getClientOriginalName());
                $fileNamepack = time() . '_' . $originalNamepack;
                Storage::disk('local')->put($fileNamepack, file_get_contents($request->filepack));
            }

            $cekdata = modelformshipment::where('noinv', $request->noinv)->where('aktif', 'Y')->first();
            if ($cekdata != null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Invoice Already Exist'];
                return response()->json($status, 200);
            }

            foreach ($decode as $key => $val) {
                if ($filebl == '' || $filebl == null) {
                    DB::rollback();
                    $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'File BL is required, please input File BL'];
                    return response()->json($status, 200);
                }

                if ($request->nomorbl == '' || $request->nomorbl == null) {
                    DB::rollback();
                    $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Nomor BL is required, please input Nomor BL'];
                    return response()->json($status, 200);
                }

                if ($request->vessel == '' || $request->vessel == null) {
                    DB::rollback();
                    $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Vessel is required, please input Vessel'];
                    return response()->json($status, 200);
                }

                if ($request->etdfix == '' || $request->etdfix == null) {
                    DB::rollback();
                    $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETD Fix is required, please input ETD Fix'];
                    return response()->json($status, 200);
                }

                if ($request->etafix == '' || $request->etafix == null) {
                    DB::rollback();
                    $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETA Fix is required, please input ETA Fix'];
                    return response()->json($status, 200);
                }

                $cekpo = modelpo::where('id', $val->idpo)->first();
                $rep = str_replace('.', '', $cekpo->qtypo);
                $qtypo = (float)$rep;

                $cekqtybook = modelformpo::where('idforwarder', $val->idfwd)->where('idpo', $val->idpo)->where('idmasterfwd', $val->idmasterfwd)->where('aktif', 'Y')->selectRaw(' sum(qty_booking) as qtybook ')->groupBy('kode_booking')->first();

                if ($cekqtybook->qtybook == $qtypo) {
                    $status = 'full_shipment';
                } else {
                    $status = 'partial_shipment';
                }

                if ($request->shipmode == 'fcl') {
                    $feet = ($request->fclfeet == '40hq') ? $request->fclfeet : $request->fclfeet . '"';
                    $subshipmode =  $feet . '-' .  $request->volume . 'M3' . '-' . $request->updateweight . 'KG';
                } else {
                    $subshipmode = $request->volume . 'M3' . '-' . $request->updateweight . 'KG';
                }

                $save1 = modelformshipment::insert([
                    'idformpo'          => $val->idformpo,
                    'idportloading'     => $request->portloading,
                    'idportdestination' => $request->portdestination,
                    'qty_shipment'      => $val->qty,
                    'noinv'             => strtoupper($request->noinv),
                    'etdfix'            => $request->etdfix,
                    'etafix'            => $request->etafix,
                    'file_bl'           => $fileNamebl,
                    'file_invoice'      => ($request->file('fileinv') == null) ? null : $fileNameinv,
                    'file_packinglist'  => ($request->file('filepack') == null) ? null : $fileNamepack,
                    'nomor_bl'          => strtoupper($request->nomorbl),
                    'vessel'            => strtoupper($request->vessel),
                    'statusshipment'    => $status,
                    'shipmode'          => $request->shipmode,
                    'subshipmode'       => $subshipmode,
                    'package'           => $request->package,
                    'aktif'             => 'Y',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => Session::get('session')['user_nik']
                ]);
            }

            DB::commit();
            LogActivity::addToLog('Web Forwarder :: Forwarder : Insert Shipment Process by Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Shipment'];
            return response()->json($status, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => $e->getMessage()];
            return response()->json($status, 200);
        }
    }
}
