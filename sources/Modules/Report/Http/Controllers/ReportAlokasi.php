<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;

use Modules\Report\Models\modelprivilege;
use Modules\Report\Models\modelpo;

class ReportAlokasi extends Controller
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }

    public function index()
    {
        $data = array(
            'title' => 'Report Allocation',
            'menu'  => 'reportallocation',
            'box'   => '',
        );

        return view('report::reportalokasi', $data);
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            // dd($request);

            if ($request->po == null) {
                $data = modelpo::join('forwarder', 'forwarder.idpo', 'po.id')
                    ->join('formpo', 'formpo.idforwarder', 'forwarder.id_forwarder')
                    ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                    ->where('forwarder.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')
                    ->selectRaw(' po.id, po.pono, po.qtypo, po.statusalokasi, po.statusconfirm, forwarder.qty_allocation, formpo.noinv, masterforwarder.name ')
                    ->get();
            } else {
                $data = modelpo::join('forwarder', 'forwarder.idpo', 'po.id')
                    ->join('formpo', 'formpo.idforwarder', 'forwarder.id_forwarder')
                    ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                    ->where('forwarder.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')
                    ->where('po.pono', $request->po)
                    ->selectRaw(' po.id, po.pono, po.qtypo, po.statusalokasi, po.statusconfirm, forwarder.qty_allocation, formpo.noinv, masterforwarder.name ')
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('qtypo', function ($data) {
                    return $data->qtypo;
                })
                ->addColumn('qtyallocation', function ($data) {
                    return $data->qty_allocation;
                })
                ->addColumn('invoice', function ($data) {
                    return $data->noinv;
                })
                ->addColumn('forwarder', function ($data) {
                    return $data->name;
                })
                ->addColumn('statusallocation', function ($data) {
                    if ($data->statusalokasi == 'full_allocated') {
                        $statuspo = 'Full Allocated';
                    } elseif ($data->statusalokasi == 'partial_allocated') {
                        $statuspo = 'Partial Allocation';
                    } else {
                        $statuspo = 'Waiting';
                    }

                    return $statuspo;
                })
                ->addColumn('statusconfirm', function ($data) {
                    if ($data->statusconfirm == 'confirm') {
                        $status = 'Confirmed';
                    } elseif ($data->statusconfirm == 'reject') {
                        $status = 'Rejected';
                    } else {
                        $status = 'Unprocessed';
                    }

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    $process    = '';

                    $process    = '<a href="#" data-id="' . $data->id . '" id="detailalokasi"><i class="fa fa-info-circle"></i></a>';

                    return $process;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // return view('transaksi::create');
    }

    public function getpo(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = modelpo::select('pono');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' pono like "%' . $search . '%" ');
        }

        $po = $po->orderby('pono', 'asc')->groupby('pono')->get();

        return response()->json($po);
    }

    function detailalokasi(Request $request)
    {
        dd($request);
    }
}
