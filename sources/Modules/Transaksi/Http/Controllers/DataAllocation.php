<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;
use Modules\Transaksi\Models\mastersupplier;
use Modules\Transaksi\Models\masterforwarder;
use Modules\Transaksi\Models\modelpo;
use Modules\Transaksi\Models\modelforwarder;

class DataAllocation extends Controller
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
            'title' => 'Data Allocation',
            'menu'  => 'data_allocation',
            'box'   => '',
            // 'sup'   => supplier::where('aktif', 'Y')->get(),
        );

        \LogActivity::addToLog('Access Menu Data Allocation', $this->micro);
        return view('transaksi::data_allocation', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function datatables(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if ($request->ajax()) {
            // if ($request->supplier == null) {
            //     $data = array();
            // } else {
            //     $where = '';
            //     if ($request->status != "all") {
            //         $where .= ' AND statusalokasi="' . $request->status . '" ';
            //     }
            //     if ($request->tanggal1 != "" and $request->tanggal2 != "") {
            //         $where .= ' AND (podate BETWEEN "' . $request->tanggal1 . '" AND "' . $request->tanggal2 . '") ';
            //     }
            //     $data = po::whereRaw(' vendor="' . $request->supplier . '"   ' . $where . ' ')->groupby('pono')->get();
            // }

            $data = modelforwarder::join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
                ->where('statusapproval', null)
                ->where('forwarder.aktif', 'Y')
                ->where('masterforwarder.aktif', 'Y')
                ->groupby('forwarder.idmasterfwd')
                ->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('poku', function ($data) {
                    // $datapo = modelforwarder::where('idmasterfwd', $data->id)->groupby('po_nomor')->pluck('po_nomor');
                    // return $datapo;
                    return $data->po_nomor;
                })
                ->addColumn('namafwd', function ($data) {
                    return $data->name;
                })
                ->addColumn('status', function ($data) {
                    return $data->statusallocation;
                })
                ->addColumn('moveto', function ($data) {
                    $namefwd = masterforwarder::where('id', $data->movetofwd)->where('aktif', 'Y')->selectRaw('name')->pluck('name');

                    // return $namefwd;
                    return  str_replace("[", "", str_replace("]", "", str_replace('"', " ", $namefwd)));
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    if ($data->movetofwd) {
                        $button .= '<a href="#" data-id="' . encrypt($data->idmasterfwd) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                    } else if (isset($data->statusallocation)) {
                        $button .= '<a href="#" data-id="' . encrypt($data->idmasterfwd) . '" id="editbtn"><i data-tooltip="tooltip" title="Edit Forwarder Allocation" class="fa fa-edit fa-lg"></i></a>';
                        $button .= '&nbsp';
                        $button .= '<a href="#" data-id="' . encrypt($data->idmasterfwd) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                    } else {
                        $button .= '<a href="#" data-id="' . encrypt($data->idmasterfwd) . '" id="cancelbtn"><i data-tooltip="tooltip" title="Cancel Allocation" class="fa fa-ban fa-lg text-danger"></i></a>';
                        $button .= '&nbsp';
                        $button .= '<a href="#" data-id="' . encrypt($data->idmasterfwd) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                    }

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
    public function store_detail(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        // dd($request->arrayqty);

        DB::beginTransaction();
        if ($request->forwarder == null or $request->forwarder == "") {
            DB::rollback();
            $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Forwarder is required, please select one forwarder'];
            return response()->json($status, 200);
        }

        foreach ($request->arrayqty as $key => $val) {
            // dd($val['value']);
            // if (!$val->value) {

            // if ($request->qtyallocation == null or $request->qtyallocation == "") {
            //     DB::rollback();
            //     $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Qty allocation is required, please input qty allocation'];
            //     return response()->json($status, 200);
            // }

            $datapo = po::where('id', $val['id'])->first();
            if ($datapo == null) {
                DB::rollback();
                $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data PO Not Found, please check your data'];
                return response()->json($status, 200);
            }
            $qtypo = (float)$datapo->qtypo;

            $cek = fwd::where('idpo', $val['id'])->selectRaw(' sum(qty_allocation) as jml, id_forwarder  ')->where('aktif', 'Y')->first();
            $jumlahexist = ($cek == null) ? 0 : $cek->jml;

            $jumlahall = $val['value'] + $jumlahexist;
            // dd($jumlahall, $qtypo, $jumlahexist);

            if ($jumlahall > $qtypo) {
                DB::rollback();
                $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data Quantity Allocation Over Quantity PO'];
                return response()->json($status, 200);
            }

            if ($jumlahall == $qtypo) {
                $status = 'full_allocated';
            } else {
                $status = 'partial_allocated';
            }

            $submit2 = fwd::insert([
                'idpo' => $val['id'],
                'idmasterfwd' => $request->forwarder,
                'po_nomor'    => $val['pono'],
                'qty_allocation' => $val['value'],
                'statusforwarder' => $status,
                'date_fwd' => date('Y-m-d H:i:s'),
                'aktif' => 'Y',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Session::get('session')['user_nik']
            ]);

            $submit1 = po::where('id', $val['id'])->update([
                'statusalokasi' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($submit1 and $submit2) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }

            // }
        }

        if (empty($gagal)) {
            DB::commit();
            \LogActivity::addToLog('Save Allocation Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show_detail(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $id = decrypt($request->id);

        $getdetail = modelforwarder::join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->where('forwarder.idmasterfwd', $id)
            ->where('forwarder.aktif', 'Y')
            ->where('masterforwarder.aktif', 'Y')
            ->selectRaw(' forwarder.id_forwarder, forwarder.po_nomor, masterforwarder.name')
            ->groupby('forwarder.po_nomor')
            ->get();
        // dd($getdetail);

        $data = array(
            'title'  => 'Detail Allocation Forwarder',
            'menu'   => 'detailallocation',
            'box'    => '',
            'datadetail' => $getdetail,
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
        // return view('transaksi::detailallocation', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function getforwarder(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = masterforwarder::select('id', 'name');
        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' name like "%' . $search . '%" ');
        }

        $po = $po->where('aktif', '=', 'Y')->orderby('name', 'asc')->get();
        // dd($po);
        return response()->json($po);

        // return view('transaksi::edit');
    }

    public function getsupplier(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = supplier::select('id', 'nama');
        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' nama like "%' . $search . '%" ');
        }

        $po = $po->where('aktif', '=', 'Y')->orderby('nama', 'asc')->get();

        return response()->json($po);
    }

    public function cancelallocation($id)
    {
        $id = decrypt($id);
        // dd($id);

        $cancel = modelforwarder::where('idmasterfwd', $id)->update([
            'statusallocation' => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        if ($cancel) {
            \LogActivity::addToLog('Cancel Data Allocation', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Cancelled'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Cancelled'];
            return response()->json($status, 200);
        }
    }

    public function movefwd(Request $request)
    {

        $idmasterfwd = decrypt($request->idmasterfwd);

        $getdataold = modelforwarder::where('idmasterfwd', $idmasterfwd)->where('statusallocation', 'cancelled')->where('movetofwd', null)->where('aktif', 'Y')->get();
        // dd($getdataold);
        DB::beginTransaction();
        $update = modelforwarder::where('idmasterfwd', $idmasterfwd)->where('statusallocation', 'cancelled')->where('movetofwd', null)->where('aktif', 'Y')->update([
            'movetofwd' => $request->datamasterfwd,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        foreach ($getdataold as $key => $value) {
            $move = modelforwarder::insert([
                'idpo' => $value->idpo,
                'idmasterfwd' => $request->datamasterfwd,
                'po_nomor' => $value->po_nomor,
                'qty_allocation' => $value->qty_allocation,
                'statusforwarder' => $value->statusforwarder,
                'aktif' => 'Y',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Session::get('session')['user_nik']
            ]);

            if ($move) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        if ($update && empty($gagal)) {
            DB::commit();
            \LogActivity::addToLog('Save Allocation Move To Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }
}
