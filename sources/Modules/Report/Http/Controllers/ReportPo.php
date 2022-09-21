<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;

use Modules\Report\Models\modelprivilege;
use Modules\Report\Models\modelpo;

class ReportPo extends Controller
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }

    public function index()
    {
        $data = array(
            'title' => 'Report PO',
            'menu'  => 'reportpo',
            'box'   => '',
        );

        return view('report::reportpo', $data);
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            if ($request->po == null) {
                $data = modelpo::get();
            } else {
                $data = modelpo::where('pono', $request->po)->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('material', function ($data) {
                    return $data->matcontents;
                })
                ->addColumn('allocation', function ($data) {
                    if ($data->statusalokasi == 'full_allocated') {
                        $statuspo = 'Full Allocated';
                    } elseif ($data->statusalokasi == 'partial_allocated') {
                        $statuspo = 'Partial Allocation';
                    } else {
                        $statuspo = 'Waiting';
                    }

                    return $statuspo;
                })
                ->addColumn('status', function ($data) {
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
                    $button = '';

                    $button = '<a href="#" data-id="' . $data->id . '" id="detailpo"><i class="fa fa-info-circle"></i></a>';

                    return $button;
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

    public function detailpo(Request $request)
    {
        // dd($request);

        $datapo = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('po.id', $request->id)
            ->selectRaw(' po.*, mastersupplier.nama')
            ->first();
        // dd($datapo);
        $data = array(
            'dataku' => $datapo,
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }
}
