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


class ProcessShipment extends Controller
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
            'title' => 'Process Shipment',
            'menu'  => 'processshipment',
            'box'   => '',
        );

        \LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Process Shipment', $this->micro);
        return view('transaksi::process_shipment', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listshipmentprocess()
    {
        // $query = formpo::where('status', 'confirm')->where('file_bl', '=', null)->where('nomor_bl', '=', null)->where('vessel', '=', null)->where('aktif', 'Y')->get();
        $query = modelformpo::withCount(['shipment as qtyship' => function ($var) {
            $var->select(DB::raw('sum(qty_shipment)'))->groupby('idformpo');
        }])
            ->with(['shipment' => function ($stat) {
                $stat->where('statusshipment', 'full_allocated');
            }])
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'confirm')
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->groupby('po.pono')
            ->selectRaw(' formpo.*, po.id, po.pono, po.matcontents, po.colorcode, po.size, po.qtypo')
            ->get();
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('listpo', function ($query) {
                return  $query->pono;
            })
            ->addColumn('nobook', function ($query) {
                return  $query->kode_booking;
            })
            ->addColumn('qtypo', function ($query) {
                return  $query->qtypo;
            })
            ->addColumn('qtyship', function ($query) {
                $rep = str_replace('.', '', $query->qtypo);
                $qtypo = (float)$rep;

                if ($query->qtyship == null) {
                    $remain = $query->qtypo;
                } else if ($query->qtyship == $qtypo) {
                    $remain = '0';
                } else {
                    $remain = $qtypo - $query->qtyship;
                }
                return  $remain;
            })
            ->addColumn('status', function ($query) {
                if ($query->shipment == null) {
                    $status = 'No Status';
                } else {
                    if ($query->shipment->statusshipment == 'full_allocated') {
                        $status = 'Full Allocated';
                    } else {
                        $status = 'Partial Allocated';
                    }
                }

                return  $status;
            })
            ->addColumn('action', function ($query) {
                $process    = '';
                if ($query->shipment == null) {
                    $process    = '<a href="#" data-id="' . $query->id . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a>';
                } else {
                    if ($query->shipment->statusshipment == 'partial_allocated') {
                        $process    = '<a href="#" data-id="' . $query->id . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a>';
                    } else {
                        $process = '';
                    }
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
        // dd($request);

        $mydata = modelformpo::withCount(['shipment as qtyship' => function ($var) {
            $var->select(DB::raw('sum(qty_shipment)'))->groupby('idformpo');
        }])
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
            ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->where('po.id', $request->id)
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->selectRaw(' formpo.*, po.pono, po.style, po.matcontents, po.colorcode, po.size, po.qtypo, forwarder.qty_allocation, forwarder.statusforwarder')
            ->get();

        // $mydata = formpo::with(['po' => function ($var) use ($request) {
        //     $var->where('id', $request->id)->select('id', 'pono', 'style', 'matcontents', 'colorcode', 'size', 'qtypo');
        // }])
        //     ->with(['privilege' => function ($var2) {
        //         $var2->where('privilege.privilege_user_nik', Session::get('session')['user_nik']);
        //     }])
        //     ->with(['forwarder', 'shipment'])
        //     ->where('idpo', $request->id)
        //     ->where('formpo.statusformpo', 'confirm')
        //     ->get();

        // dd($mydata);
        $data = array(
            'dataku' => $mydata,
        );

        \LogActivity::addToLog('Web Forwarder :: Forwarder : Input Data Process Shipment', $this->micro);
        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    public function saveshipment(Request $request)
    {
        $decode = json_decode($request->dataid);
        // dd($decode, $request);
        // DB::beginTransaction();

        $file = $request->file('file');
        $originalName = str_replace(' ', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . $originalName;
        Storage::disk('local')->put($fileName, file_get_contents($request->file));

        foreach ($decode as $key => $val) {
            if ($file == '' || $file == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'File BL is required, please input File BL'];
                return response()->json($status, 200);
            }

            if ($request->nomorbl == '' || $request->nomorbl == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Nomor BL is required, please input Nomor BL'];
                return response()->json($status, 200);
            }

            if ($request->vessel == '' || $request->vessel == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Vessel is required, please input Vessel'];
                return response()->json($status, 200);
            }

            if ($request->invoice == '' || $request->invoice == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Invoice is required, please input Invoice'];
                return response()->json($status, 200);
            }

            if ($request->etdfix == '' || $request->etdfix == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETD Fix is required, please input ETD Fix'];
                return response()->json($status, 200);
            }

            if ($request->etafix == '' || $request->etafix == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETA Fix is required, please input ETA Fix'];
                return response()->json($status, 200);
            }

            $cekdata = modelformshipment::where('noinv', $request->invoice)->where('aktif', 'Y')->first();
            if ($cekdata != null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Invoice Already Exist'];
                return response()->json($status, 200);
            }

            $cekpo = modelpo::where('id', $val->idpo)->first();
            $rep = str_replace('.', '', $cekpo->qtypo);
            $qtypo = (float)$rep;

            $cekqtyshipment = modelformshipment::where('idformpo', $val->idformpo)->where('aktif', 'Y')->selectRaw(' sum(qty_shipment) as jml ')->first();
            $jumlahexist = ($cekqtyshipment->jml == null) ? 0 : $cekqtyshipment->jml;

            $jumlahall = $val->value + $jumlahexist;
            // dd($jumlahall, $qtypo);

            if ($jumlahall > $qtypo) {
                // DB::rollback();
                $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data Quantity Allocation Over Quantity PO'];
                return response()->json($status, 200);
            }

            if ($jumlahall == $qtypo) {
                $status = 'full_allocated';
            } else {
                $status = 'partial_allocated';
            }

            $save1 = modelformshipment::insert([
                'idformpo'     => $val->idformpo,
                'qty_shipment' => $val->value,
                'noinv'        => strtoupper($request->invoice),
                'etdfix'       => $request->etdfix,
                'etafix'       => $request->etafix,
                'file_bl'      => $fileName,
                'nomor_bl'     => strtoupper($request->nomorbl),
                'vessel'       => strtoupper($request->vessel),
                'statusshipment' => $status,
                'aktif'        => 'Y',
                'created_at'   => date('Y-m-d H:i:s'),
                'created_by'   => Session::get('session')['user_nik']
            ]);

            if ($save1) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        if (empty($gagal)) {
            // DB::commit();
            \LogActivity::addToLog('Web Forwarder :: Forwarder : Insert Shipment Process by Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            // DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }
}
