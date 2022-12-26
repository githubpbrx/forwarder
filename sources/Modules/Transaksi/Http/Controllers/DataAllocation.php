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

        // if ($request->ajax()) {
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

        // $datatanggal = modelforwarder::where('aktif', 'Y')->selectRaw(' DATE_FORMAT(created_at, "%Y-%m-%d") as dateku')->groupby(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))->pluck('dateku');

        // $datanull = modelforwarder::join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
        //     ->where('forwarder.statusallocation', null)
        //     ->where('forwarder.aktif', 'Y')
        //     ->where('masterforwarder.aktif', 'Y')
        //     ->groupby('forwarder.idmasterfwd')
        //     ->get();

        // $datacancel = modelforwarder::join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
        //     ->whereIn(DB::raw("DATE_FORMAT(forwarder.created_at, '%Y-%m-%d')"), $datatanggal)
        //     ->where('forwarder.statusallocation', 'cancelled')
        //     ->where('forwarder.aktif', 'Y')
        //     ->where('masterforwarder.aktif', 'Y')
        //     ->groupby('forwarder.idmasterfwd')
        //     ->groupby('forwarder.po_nomor')
        //     ->get();

        // $grouped = $datacancel->mapToGroups(function ($item, $key) {
        //     return [$item['idmasterfwd'] => $item['name']];
        // });

        // $grouped->toArray();
        // dd($grouped->all());
        // $dataconfirmed = modelforwarder::join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
        //     ->where('forwarder.statusallocation', 'confirmed')
        //     ->where('forwarder.aktif', 'Y')
        //     ->where('masterforwarder.aktif', 'Y')
        //     ->groupby('forwarder.idmasterfwd')
        //     ->get();

        // $flattennull = $datanull->flatten();
        // $flattencancel = $datacancel->flatten();
        // $flattenconfirmed = $dataconfirmed->flatten();

        // $dataAwal = $flattencancel->merge($flattennull);
        // $data = $dataAwal->merge($flattenconfirmed);

        $data = modelforwarder::join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->where('forwarder.aktif', 'Y')
            ->where('masterforwarder.aktif', 'Y')
            ->select('forwarder.id_forwarder', 'forwarder.idmasterfwd', 'forwarder.po_nomor', 'forwarder.statusallocation', 'forwarder.movetofwd', 'forwarder.created_at', 'masterforwarder.name')
            ->groupby('forwarder.po_nomor')
            ->groupby('forwarder.idmasterfwd')
            ->orderBy('forwarder.idmasterfwd', 'desc')
            ->get();

        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('poku', function ($data) {
                // $datapo = modelforwarder::where('idmasterfwd', $data->id)->groupby('po_nomor')->pluck('po_nomor');
                // return $datapo;
                return $data->po_nomor;
            })
            ->addColumn('dateallocation', function ($data) {
                $date = date('d/m/Y', strtotime($data->created_at));
                return $date;
            })
            ->addColumn('namafwd', function ($data) {
                return $data->name;
            })
            ->addColumn('status', function ($data) {
                if ($data->statusallocation == 'confirmed') {
                    $stat = 'Continued To Shipment';
                } else {
                    $stat = $data->statusallocation;
                }

                return $stat;
            })
            ->addColumn('moveto', function ($data) {
                $namefwd = masterforwarder::where('id', $data->movetofwd)->where('aktif', 'Y')->selectRaw('name')->pluck('name');

                // return $namefwd;
                return  str_replace("[", "", str_replace("]", "", str_replace('"', " ", $namefwd)));
            })
            ->addColumn('action', function ($data) {
                $button = '';

                if ($data->movetofwd || $data->statusallocation == 'confirmed') {
                    $button .= '<a href="#" data-id="' . encrypt($data->po_nomor) . '" data-fwd="' . encrypt($data->idmasterfwd) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                } else if (isset($data->statusallocation)) {
                    $button .= '<a href="#" data-id="' . encrypt($data->po_nomor) . '" data-fwd="' . encrypt($data->idmasterfwd) . '" id="editbtn"><i data-tooltip="tooltip" title="Edit Forwarder Allocation" class="fa fa-edit fa-lg"></i></a>';
                    $button .= '&nbsp';
                    $button .= '<a href="#" data-id="' . encrypt($data->po_nomor) . '" data-fwd="' . encrypt($data->idmasterfwd) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                } else {
                    $button .= '<a href="#" data-id="' . encrypt($data->po_nomor) . '" data-fwd="' . encrypt($data->idmasterfwd) . '" id="cancelbtn"><i data-tooltip="tooltip" title="Cancel Allocation" class="fa fa-ban fa-lg text-danger"></i></a>';
                    $button .= '&nbsp';
                    $button .= '<a href="#" data-id="' . encrypt($data->po_nomor) . '" data-fwd="' . encrypt($data->idmasterfwd) . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                }

                return $button;
            })
            // ->rawColumns(['poku', 'date', 'material', 'status', 'action'])
            // ->rawColumns(['status'])
            ->make(true);
        // }
        // return view('transaksi::create');
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
        $idfwd = decrypt($request->idfwd);
        // dd($id, $idfwd);
        $getdetail = modelforwarder::join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->join('po', 'po.id', 'forwarder.idpo')
            ->where('forwarder.po_nomor', $id)
            ->where('forwarder.idmasterfwd', $idfwd)
            ->where('forwarder.aktif', 'Y')
            ->where('masterforwarder.aktif', 'Y')
            ->selectRaw(' forwarder.id_forwarder, forwarder.po_nomor, masterforwarder.name, po.pono, po.line_id, po.matcontents, po.colorcode, po.size, po.qtypo')
            // ->groupby('forwarder.po_nomor')
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

    public function cancelallocation($id, $idfwd)
    {
        $id = decrypt($id);
        $idfwd = decrypt($idfwd);
        // dd($id, $idfwd);

        $cancel = modelforwarder::where('po_nomor', $id)->where('idmasterfwd', $idfwd)->update([
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
        // dd($request);
        $pono = decrypt($request->pono);
        $idmasterfwd = decrypt($request->idmasterfwd);

        $getdataold = modelforwarder::where('po_nomor', $pono)->where('idmasterfwd', $idmasterfwd)->where('statusallocation', 'cancelled')->where('movetofwd', null)->where('aktif', 'Y')->get();
        // dd($getdataold);
        DB::beginTransaction();
        $update = modelforwarder::where('po_nomor', $pono)->where('idmasterfwd', $idmasterfwd)->where('statusallocation', 'cancelled')->where('movetofwd', null)->where('aktif', 'Y')->update([
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
