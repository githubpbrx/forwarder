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
            if($request->supplier==null){
                $data = array();
            }else{
                $where = '';
                if($request->status!="all"){
                    $where .= ' AND statusalokasi="' . $request->status . '" ';
                }
                if($request->tanggal1!= "" AND $request->tanggal2 !=""){
                    $where .= ' AND (podate BETWEEN "' . $request->tanggal1 . '" AND "' . $request->tanggal2 . '") ';
                }
                $data = po::whereRaw(' vendor="' . $request->supplier . '"   '.$where.' ')->get();
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('poku', function ($data) {
                    return $data->pono;
                })
                ->addColumn('date', function ($data) {
                    return date('d F Y', strtotime($data->podate));
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

        if($request->qtyallocation==null OR $request->qtyallocation==""){
            DB::rollback();
            $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Qty allocation is required, please input qty allocation'];
            return response()->json($status, 200);
        }
        if($request->forwarder==null OR $request->forwarder==""){
            DB::rollback();
            $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Forwarder is required, please select one forwarder'];
            return response()->json($status, 200);
        }

        $datapo = po::where('id',$request->idpo)->first();
        if($datapo==null){
            DB::rollback();
            $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data PO Not Found, please check your data'];
            return response()->json($status, 200);
        }
        $qtypo = $datapo->qtypo;

        $cek = fwd::where('idpo', $request->idpo)->selectRaw(' sum(qty_allocation) as jml, id_forwarder  ')->where('aktif', 'Y')->first();
        $jumlahexist = ($cek==null) ? 0 : $cek->jml;
        
        $jumlahall = $request->qtyallocation+$jumlahexist;
        if($jumlahall>$qtypo){
            DB::rollback();
            $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data Quantity Allocation Over Quantity PO'];
            return response()->json($status, 200);
        }

        if ($jumlahall==$qtypo) {
            $status = 'full_allocated';
        } else {
            $status = 'partial_allocated';
        }


        $submit2 = fwd::insert([
            'idpo' => $request->idpo,
            'idmasterfwd' => $request->forwarder,
            'qty_allocation' => $request->qtyallocation,
            'date_fwd' => date('Y-m-d H:i:s'),
            'aktif' => 'Y',
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Session::get('session')['user_nik']
        ]);
        
        $submit1 = po::where('id', $request->idpo)->update([
            'statusalokasi' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

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

        $dd = fwd::with('masterforwarder')->where('idpo',$id)->where('aktif','Y')->get();
        
        if(count($dd)==0){
            $html = '';
        }else{
            $html = "<b style='font-size:14pt'>Details of the data that has been Partial  Allocated</b><br><table border='1' style='width:100%' class='table table-bordered table-striped table-hover'><tr style='width:100%'><td>To forwarder</td><td>Qty Allocation</td><td>Date Allocation</td></tr>";
            foreach($dd as $key => $r){
                $namafw = ($r->masterforwarder==null) ? '' : $r->masterforwarder->nama;
                $html .= "<tr><td>".$namafw."</td><td>".$r->qty_allocation."</td><td>".$r->date_fwd."</td></tr>";
            }
            $html .= "</table>";
        }
        
        $data = array(
            'title'  => 'Detail Allocation Forwarder',
            'menu'   => 'detailallocation',
            'box'    => '',
            'datapo' => $mydata,
            'datasup' => $datasup,
            'detail' => $html
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
