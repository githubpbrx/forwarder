<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;
use Illuminate\Support\Facades\Storage;
use Modules\Transaksi\Models\mastersupplier;
use Modules\Transaksi\Models\masterhscode;
use Modules\Transaksi\Models\modelpo;
use Modules\Transaksi\Models\modelcontainer;
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
            'title' => 'Outstanding Shipment',
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
        $query = modelformpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'confirm')
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->groupby('formpo.kode_booking')
            ->selectRaw(' formpo.*, po.id, po.pono, po.matcontents, po.colorcode, po.size, sum(po.qtypo) as qtypoall, po.qtypo')
            ->get();

        $dataqty = modelformpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'confirm')
            ->where('privilege.privilege_aktif', 'Y')->where('formpo.aktif', 'Y')->where('formshipment.aktif', 'Y')
            ->selectRaw(' sum(formshipment.qty_shipment) as qtyshipall ')
            ->first();

        // $dataqty = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
        // ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
        // ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
        // ->where('formpo.statusformpo', '=', 'confirm')
        // ->where('privilege.privilege_aktif', 'Y')->where('formpo.aktif', 'Y')->where('formshipment.aktif', 'Y')
        // ->selectRaw(' sum(formshipment.qty_shipment) as qtyshipall ')
        // ->first();

        // dd($query,  $dataqty);
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
            ->addColumn('status', function ($query) {
                // $datac = modelformpo::where('formpo.kode_booking', $query->kode_booking)
                //     ->where('formpo.statusformpo', 'confirm')
                //     ->where('formpo.aktif', 'Y')
                //     ->select('id_formpo')
                //     ->get();
                $datac = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
                    ->where('formpo.kode_booking', $query->kode_booking)
                    ->where('formpo.statusformpo', 'confirm')
                    ->where('formpo.aktif', 'Y')->where('formshipment.aktif', 'Y')
                    // ->groupby('formshipment.idformpo')
                    ->selectRaw(' sum(qty_shipment) as qtyship')
                    ->first();
                // dd($datac);
                // $arr = [];
                // $arrFull = [];
                // foreach ($datac as $key => $val) {
                //     $dd =  modelformshipment::where('idformpo', $val->id_formpo)->where('aktif', 'Y')->first();
                //     if ($dd) {
                //         $status = 'Partial Shipment';
                //         array_push($arr, $status);
                //         array_push($arrFull, $dd->qty_shipment);
                //     }
                // }
                // dd($arrFull);
                // $sum = array_sum($arrFull);
                // dd((float)$sum, $query->qtypoall);
                // if ($dataqty->qtyshipall == null) {
                // $status = 'No Status';
                // } else {
                //     if ($dataqty->qtyshipall == $query->qtypoall) {
                //         $status = 'Full Shipment';
                //     } else {
                //         $status = 'Partial Shipment';
                //     }
                // }

                if ($datac->qtyship == $query->qtypoall) {
                    return 'Full Shipment';
                } elseif ($datac->qtyship != $query->qtypoall) {
                    // return  $arr[0];
                    return  'Partial Shipment';
                } else {
                    return  'No Status';
                }
            })
            ->addColumn('action', function ($query) {
                // $datac = modelformpo::where('formpo.kode_booking', $query->kode_booking)
                //     ->where('formpo.statusformpo', 'confirm')
                //     ->where('formpo.aktif', 'Y')
                //     ->get();
                $datac = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
                    ->where('formpo.kode_booking', $query->kode_booking)
                    ->where('formpo.statusformpo', 'confirm')
                    ->where('formpo.aktif', 'Y')->where('formshipment.aktif', 'Y')
                    // ->groupby('formshipment.idformpo')
                    ->selectRaw(' sum(qty_shipment) as qtyship')
                    ->first();

                // $arr = [];
                // $arrFull = [];
                // foreach ($datac as $key => $val) {
                //     $dd =  modelformshipment::where('idformpo', $val->id_formpo)->where('aktif', 'Y')->first();
                //     if ($dd) {
                //         $status = 'Partial Shipment';
                //         array_push($arr, $status);
                //         array_push($arrFull, $dd->qty_shipment);
                //     }
                // }

                // $sum = array_sum($arrFull);

                $process    = '';

                if ($datac->qtyship != $query->qtypoall) {
                    $process    = '<a href="#" data-id="' . $query->kode_booking . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a>';
                } else {
                    // if ($dataqty->qtyshipall != $query->qtypoall) {
                    //     $process    = '<a href="#" data-id="' . $query->kode_booking . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a>';
                    // } else {
                    $process = '';
                    // }
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
        }])->with(['withpo' => function ($hs) {
            $hs->with(['hscode', 'supplier']);
        }, 'withforwarder' => function ($priv) {
            $priv->with(['privilege' => function ($lege) {
                $lege->where('privilege_user_nik', Session::get('session')['user_nik']);
            }]);
        }, 'withroute'])
            // ->join('po', 'po.id', 'formpo.idpo')
            // ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
            // ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            // ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            // ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->where('formpo.kode_booking', $request->id)
            // ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')
            // ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('masterhscode.aktif', 'Y')
            // ->selectRaw(' formpo.*, po.pono, po.matcontents, po.itemdesc, po.colorcode, po.size, po.qtypo, forwarder.qty_allocation, forwarder.statusforwarder, mastersupplier.nama, masterhscode.hscode')
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

        \LogActivity::addToLog('Web Forwarder :: Forwarder : Input Data Process Shipment', $this->micro);
        // return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
        $form = view('transaksi::modalshipment', ['data' => $data]);
        return $form->render();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    public function saveshipment(Request $request)
    {
        $decode = json_decode($request->dataid);
        $decodecont = json_decode($request->datacontainer);
        $decodeweight = json_decode($request->dataweight);
        $decodematcontent = json_decode($request->datamatcontent);
        $decodehscode = json_decode($request->datahscode);
        // dd($request);
        // DB::beginTransaction();
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

            $cekqtyshipment = modelformshipment::where('idformpo', $val->idformpo)->where('aktif', 'Y')->selectRaw(' sum(qty_shipment) as jml ')->first();
            $jumlahexist = ($cekqtyshipment->jml == null) ? 0 : $cekqtyshipment->jml;

            $jumlahall = $val->value + $jumlahexist;
            // dd($jumlahall, $qtypo);

            if ($jumlahall > $qtypo) {
                DB::rollback();
                $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Qty Ship Over Than Balance Qty'];
                return response()->json($status, 200);
            }

            if ($jumlahall == $qtypo) {
                $status = 'full_allocated';
            } else {
                $status = 'partial_allocated';
            }

            $subshipmode = $request->volume . 'M3' . '-' . $request->updateweight . 'KG';
            $save1 = modelformshipment::insert([
                'idformpo'         => $val->idformpo,
                'qty_shipment'     => $val->value,
                'noinv'            => strtoupper($request->noinv),
                'etdfix'           => $request->etdfix,
                'etafix'           => $request->etafix,
                'file_bl'          => $fileNamebl,
                'file_invoice'     => ($request->file('fileinv') == null) ? null : $fileNameinv,
                'file_packinglist' => ($request->file('filepack') == null) ? null : $fileNamepack,
                'nomor_bl'         => strtoupper($request->nomorbl),
                'vessel'           => strtoupper($request->vessel),
                'statusshipment'   => $status,
                'shipmode'         => $request->shipmode,
                'subshipmode'      => $subshipmode,
                'aktif'            => 'Y',
                'created_at'       => date('Y-m-d H:i:s'),
                'created_by'       => Session::get('session')['user_nik']
            ]);

            if ($save1) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        $getinv = modelformshipment::latest('id_shipment')->first();
        foreach ($decodecont as $key => $lue) {
            foreach ($decode as $key2 => $value) {
                $savecont = modelcontainer::insert([
                    'idformpo'          => $value->idformpo,
                    'noinv'             => $getinv->noinv,
                    'containernumber'   => ($request->fclfeet == '40hq') ? $request->fclfeet : $request->fclfeet . '"',
                    'numberofcontainer' => $lue,
                    'weight'            => $decodeweight[$key] . 'KG',
                    'aktif'             => 'Y',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'created_by'        => Session::get('session')['user_nik']
                ]);

                if ($savecont) {
                    $sukses[] = "OK";
                } else {
                    $gagal[] = "OK";
                }
            }
        }

        foreach ($decodematcontent as $keymat => $valmat) {
            $cekhs = masterhscode::where('matcontent', $valmat)->where('aktif', 'Y')->first();
            if ($cekhs) {
                $simpan = masterhscode::where('matcontent', $valmat)->update([
                    'hscode'      => $decodehscode[$keymat],
                    'matcontent'  => $valmat,
                    'updated_at'  => date('Y-m-d H:i:s'),
                    'updated_by'  => Session::get('session')['user_nik']
                ]);
            } else {
                $simpan = masterhscode::insert([
                    'hscode'      => $decodehscode[$keymat],
                    'matcontent'  => $valmat,
                    'aktif'       => 'Y',
                    'created_at'  => date('Y-m-d H:i:s'),
                    'created_by'  => Session::get('session')['user_nik']
                ]);
            }

            if ($simpan) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        if (empty($gagal)) {
            DB::commit();
            \LogActivity::addToLog('Web Forwarder :: Forwarder : Insert Shipment Process by Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }
}
