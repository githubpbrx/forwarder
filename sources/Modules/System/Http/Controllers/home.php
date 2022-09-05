<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;
use Yajra\Datatables\Datatables;

use Modules\System\Models\modelpo as po;
use Modules\System\Models\modelprivilege as privilege;
use Modules\System\Models\modelformpo as formpo;

class home extends Controller
{
    public function __construct()
    {
        $this->middleware('checklogin');

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }

    public function index()
    {
        $datapo = po::where('statusalokasi', 'partial_allocated')->orWhere('statusalokasi', 'full_allocated')->where('statusconfirm', '=', null)->get();
        $datauser = privilege::where('privilege_user_nik', Session::get('session')['user_nik'])->first();

        $data = array(
            'title' => 'Dashboard',
            'menu'  => 'dashboard',
            'box'   => '',
            'totalpo' => count($datapo),
            'datauser' => $datauser,
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
        $query = po::where('statusalokasi', 'partial_allocated')->orWhere('statusalokasi', 'full_allocated')->where('statusconfirm', '=', null)->get();

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

    public function saveformpo(Request $request)
    {
        // dd($request);

        if ($request->shipmode == 'fcl') {
            $submode = $request->fcl;
        } else if ($request->shipmode == 'lcl') {
            $submode = $request->lcl;
        } else {
            $submode = $request->air;
        }

        DB::beginTransaction();
        $save1 = formpo::insert([
            'idpo'          => $request->idpo,
            'kode_booking'  => $request->nobooking,
            'date_booking'  => $request->datebooking,
            'etd'           => $request->etd,
            'eta'           => $request->eta,
            'shipmode'      => $request->shipmode,
            'subshipmode'   => $submode,
            'status'        => 'waiting',
            'aktif'         => 'Y',
            'created_at'    => date('Y-m-d H:i:s'),
            'created_by'    => Session::get('session')['user_nik']
        ]);

        $save2 = po::where('id', $request->idpo)->update([
            'statusconfirm' => 'not confirmed'
        ]);

        if ($save1 && $save2) {
            DB::commit();
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }
}
