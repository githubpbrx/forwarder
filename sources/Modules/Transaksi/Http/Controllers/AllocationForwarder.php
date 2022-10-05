<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;
use Modules\Transaksi\Models\mastersupplier as supplier;
use Modules\Transaksi\Models\masterforwarder as forward;
use Modules\Transaksi\Models\modelpo as po;
use Modules\Transaksi\Models\modelforwarder as fwd;

class AllocationForwarder extends Controller
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
            'title' => 'Allocation Forwarder',
            'menu'  => 'allocationforwarder',
            'box'   => '',
            'sup'   => supplier::where('aktif', 'Y')->get(),
        );

        \LogActivity::addToLog('Access Menu Allocation Forwarder', $this->micro);
        return view('transaksi::allocationforwarder', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if ($request->ajax()) {
            if ($request->supplier == null) {
                $data = array();
            } else {
                $where = '';
                if ($request->status != "all") {
                    $where .= ' AND statusalokasi="' . $request->status . '" ';
                }
                if ($request->tanggal1 != "" and $request->tanggal2 != "") {
                    $where .= ' AND (podate BETWEEN "' . $request->tanggal1 . '" AND "' . $request->tanggal2 . '") ';
                }
                $data = po::whereRaw(' vendor="' . $request->supplier . '"   ' . $where . ' ')->groupby('pono')->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('poku', function ($data) {
                    return $data->pono;
                })
                ->addColumn('date', function ($data) {
                    return date('d F Y', strtotime($data->podate));
                })
                ->addColumn('action', function ($data) {
                    $idku = $data->pono;
                    $button = '';

                    $button = '<a href="#" data-id="' . $data->pono . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';

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

        // $getpo = po::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
        //     ->join('forwarder', 'forwarder.idpo', 'po.id')
        //     ->where('po.pono', $request->id)
        //     ->where('forwarder.aktif', 'Y')
        //     ->selectRaw(' po.id, po.pono, po.qtypo, po.matcontents, po.style, mastersupplier.nama ')
        //     ->groupby('forwarder.idpo')
        //     ->get();

        $getpo = po::withCount(['forwarder as qtyall'  => function ($var) {
            $var->select(DB::raw('sum(qty_allocation)'))->groupby('idpo');
        }])
            ->with(['supplier'])
            ->where('pono', $request->id)
            ->get();

        $data = array(
            'title'  => 'Detail Allocation Forwarder',
            'menu'   => 'detailallocation',
            'box'    => '',
            'datapo' => $getpo,
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
        $po = forward::select('id', 'name');
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
}
