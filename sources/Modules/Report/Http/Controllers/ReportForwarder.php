<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;

use Modules\Report\Models\modelprivilege;
use Modules\Report\Models\modelpo;

class ReportForwarder extends Controller
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }

    public function index()
    {
        $data = array(
            'title' => 'Report Forwarder',
            'menu'  => 'reportforwarder',
            'box'   => '',
        );

        return view('report::reportforwarder', $data);
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            // dd($request);
            $ses = Session::get('session');
            $user = $ses['user_nik'];
            $nama = $ses['user_nama'];

            if ($request->po == null) {
                $data = modelpo::get();
            } else {
                $data = modelpo::where('id', $request->po)->get();
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
                ->addColumn('plant', function ($data) {
                    return $data->plant;
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
                ->rawColumns(['allocation'])
                ->make(true);
        }
        // return view('transaksi::create');
    }

    public function getpo(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        // $ses = Session::get('session');
        // $user = $ses['user_nik'];
        // $nama = $ses['user_nama'];

        if (!$request->ajax()) return;
        $po = modelpo::select('id', 'pono');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' pono like "%' . $search . '%" ');
        }

        $po = $po->orderby('pono', 'asc')->get();

        return response()->json($po);
    }
}
