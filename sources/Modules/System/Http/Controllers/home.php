<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;
use Yajra\Datatables\Datatables;

use Modules\System\Models\modelpo as po;

class home extends Controller
{
    public function __construct()
    {
        $this->middleware('checklogin');
    }

    public function index()
    {
        $datapo = po::where('statusalokasi', 'partial_allocated')->orWhere('statusalokasi', 'full_allocated')->get();

        $data = array(
            'title' => 'Dashboard',
            'menu'  => 'dashboard',
            'box'   => '',
            'totalpo' => count($datapo)
        );
        return view('system::dashboard/dashboard', $data);
    }

    public function pagepo()
    {
        $data = array(
            'title' => 'Data List PO',
            'menu'  => 'pagepo',
            'box'   => '',
        );
        return view('system::dashboard/listpo', $data);
    }

    public function listpo()
    {
        $query = po::where('statusalokasi', 'partial_allocated')->orWhere('statusalokasi', 'full_allocated')->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('listpo', function ($query) {
                return  $query->pono;
            })
            ->addColumn('action', function ($query) {
                $process    = '';

                $process    = '<a href="#" data-id="' . $query->id . '" id="formpo"><i class="fa fa-angle-double-right text-orange"></i></a>';

                return $process;
            })
            // ->rawColumns(['listpo', 'action'])
            ->make(true);
    }

    public function formpo(Request $request)
    {
        $mydata = po::where('id', $request->id)->first();

        $data = array(
            'datapo' => $mydata
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }
}
