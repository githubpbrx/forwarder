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
            // dd($request);
            // $data = po::join('mastersupplier', 'mastersupplier.id', 'po.vendor')->where('po.vendor', $request->supplier)->get();
            $data = po::whereRaw(' vendor="' . $request->supplier . '" AND statusalokasi="' . $request->status . '" AND (podate BETWEEN "' . $request->tanggal1 . '" AND "' . $request->tanggal2 . '") ')->get();
            // dd($data);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('poku', function ($data) {
                    return $data->pono;
                })
                ->addColumn('date', function ($data) {
                    return $data->podate;
                })
                ->addColumn('material', function ($data) {
                    return $data->itemdesc;
                })
                ->addColumn('status', function ($data) {
                    if ($data->statusalokasi == 'all') {
                        $statusku = 'All';
                    } elseif ($data->statusalokasi == 'waiting') {
                        $statusku = 'Waiting';
                    } elseif ($data->statusalokasi == 'partial_allocated') {
                        $statusku = 'Partial Allocated';
                    } else {
                        $statusku = 'Full Allocated';
                    }

                    return $statusku;
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button = '<a href="#" data-id="' . $data->id . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';

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

        // dd($request);
        // $datapo = po::where('id', $request->idpo)->select('qtypo')->first();
        if ($request->qtyallocation == $request->data_qtypo) {
            $status = 'full_allocated';
        } else {
            $status = 'partial_allocated';
        }

        DB::beginTransaction();
        $submit1 = po::where('id', $request->idpo)->update([
            'statusalokasi' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $cek = fwd::where('idpo', $request->idpo)->where('aktif', 'Y')->first();
        if ($cek != null) {
            $submit2 = fwd::where('id_forwarder', $cek->id_forwarder)->update([
                'idpo' => $request->idpo,
                'idmasterfwd' => $request->forwarder,
                'qty_allocation' => $request->qtyallocation,
                'date_fwd' => date('Y-m-d H:i:s'),
                'aktif' => 'Y',
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Session::get('session')['user_nik']
            ]);
        } else {
            $submit2 = fwd::insert([
                'idpo' => $request->idpo,
                'idmasterfwd' => $request->forwarder,
                'qty_allocation' => $request->qtyallocation,
                'date_fwd' => date('Y-m-d H:i:s'),
                'aktif' => 'Y',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Session::get('session')['user_nik']
            ]);
        }

        if ($submit1 and $submit2) {
            DB::commit();
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

        $id = $request->id;
        $mydata = po::where('id', $id)->first();
        $datasup = supplier::where('id', $mydata->vendor)->first();
        // dd($mydata);

        $data = array(
            'title'  => 'Detail Allocation Forwarder',
            'menu'   => 'detailallocation',
            'box'    => '',
            'datapo' => $mydata,
            'datasup' => $datasup
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
        $po = forward::select('id', 'nama');
        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' nama like "%' . $search . '%" ');
        }

        $po = $po->where('aktif', '=', 'Y')->orderby('nama', 'asc')->get();
        // dd($po);
        return response()->json($po);

        // return view('transaksi::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
