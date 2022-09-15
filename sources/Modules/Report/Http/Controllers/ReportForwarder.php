<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;

use Modules\Report\Models\modelprivilege;

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

    public function datatablefwd(Request $request)
    {

        if ($request->ajax()) {
            // dd($request);
            $ses = Session::get('session');
            $user = $ses['user_nik'];
            $nama = $ses['user_nama'];

            if ($request->forwarder == null) {
                $data = modelprivilege::with(['to_masterfwd', 'to_kyc', 'to_formpo'])
                    ->where('nikfinance', $user)->where('namafinance', $nama)
                    ->get();
            } else {
                $data = modelprivilege::with(['to_masterfwd', 'to_kyc', 'to_formpo'])
                    ->where('nikfinance', $user)->where('namafinance', $nama)
                    ->where('idforwarder', $request->forwarder)
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('fwd', function ($data) {
                    return $data->to_masterfwd->name;
                })
                ->addColumn('kyc', function ($data) {
                    return $data->to_kyc->status;
                })
                ->addColumn('allocation', function ($data) {
                    if ($data->to_formpo == null) {
                        $formpo = 'Forwarder Has Not Entered Data Shipment';
                    } else {
                        $formpo = $data->to_formpo->status;
                    }

                    return $formpo;
                })
                ->addColumn('shipment', function ($data) {
                    if ($data->to_formpo == null) {
                        $datashipment = 'Forwarder Has Not Entered Data';
                    } elseif ($data->to_formpo != null && $data->to_formpo->file_bl == null && $data->to_formpo->nomor_bl == null && $data->to_formpo->vessel == null) {
                        $datashipment = 'Forwarder Has Not Entered Data Shipment';
                    } else {
                        $datashipment = 'Data Shipment is Done';
                    }

                    return $datashipment;
                })
                ->rawColumns(['allocation'])
                ->make(true);
        }
        // return view('transaksi::create');
    }

    public function getforwarder(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $ses = Session::get('session');
        $user = $ses['user_nik'];
        $nama = $ses['user_nama'];

        if (!$request->ajax()) return;
        $priv = modelprivilege::join('masterforwarder', 'masterforwarder.id', 'privilege.idforwarder')->where('nikfinance', $user)->where('namafinance', $nama)->select('id', 'name');

        if ($request->has('q')) {
            $search = $request->q;
            $priv = $priv->whereRaw(' name like "%' . $search . '%" ');
        }

        $priv = $priv->where('aktif', 'Y')->orderby('name', 'asc')->get();

        return response()->json($priv);
    }
}
