<?php

namespace Modules\Transaksi\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use Modules\System\Helpers\LogActivity;
use Modules\Transaksi\Models\modelpo as po;
use Modules\Transaksi\Models\masterbuyer as buyer;
use Modules\Transaksi\Models\modelformpo as formpo;
use Modules\Transaksi\Models\modelforwarder as fwd;
use Modules\Transaksi\Models\mastersupplier as supplier;

class ApprovalConfirmation extends Controller
{
    protected $ip_server;
    protected $micro;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->micro = microtime(true);
        $this->ip_server = config('api.url.ip_address');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = array(
            'title' => 'Approval Confirmation',
            'menu'  => 'approvalconfirmation',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Approval Confirmation', $this->micro);
        return view('transaksi::approvalconfirmation', $data);
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

    public function getbuyer(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = buyer::select('id_buyer', 'nama_buyer');
        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' nama_buyer like "%' . $search . '%" ');
        }

        $po = $po->where('aktif', '=', 'Y')->orderby('nama_buyer', 'asc')->get();

        return response()->json($po);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            // $data = formpo::join('po', 'po.id', 'formpo.idpo')->whereRaw(' vendor="' . $request->supplier . '" AND status="' . $request->statusfwd . '" AND (date_booking BETWEEN "' . $request->tanggal1 . '" AND "' . $request->tanggal2 . '") AND kode_booking="' . $request->book . '" ')->get();

            if ($request->supplier == null) {
                $data = array();
            } else {
                $where = '';
                if ($request->statusfwd != "all") {
                    $where .= 'AND statusformpo="' . $request->statusfwd . '"';
                }
                if ($request->tanggal1 != "" and $request->tanggal2 != "") {
                    $where .= 'AND (date_booking BETWEEN "' . $request->tanggal1 . '" AND "' . $request->tanggal2 . '")';
                }
                if ($request->buyer != "") {
                    $where .= 'AND buyer="' . $request->buyer . '"';
                }
                if ($request->book != "") {
                    $where .= 'AND kode_booking="' . $request->book . '"';
                }
                // $data = formpo::join('po', 'po.id', 'formpo.idpo')
                //     ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                //     ->where('masterforwarder.aktif', 'Y')
                //     ->whereRaw(' vendor="' . $request->supplier . '" ' . $where . ' ')
                //     ->selectRaw(' formpo.kode_booking, formpo.date_booking, formpo.statusformpo, masterforwarder.name')
                //     ->groupby('formpo.kode_booking')
                //     ->get();
                $data = fwd::join('po', 'po.id', 'forwarder.idpo')
                    ->join('formpo', 'formpo.idpo', 'forwarder.idpo')
                    ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
                    ->where('forwarder.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')
                    ->whereRaw(' vendor="' . $request->supplier . '"' . $where . '')
                    // ->selectRaw(' forwarder.id_forwarder, po.pono, po.matcontents, formpo.kode_booking, masterforwarder.name ')
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nopo', function ($data) {
                    return $data->pono;
                })
                // ->addColumn('date', function ($data) {
                //     $date = Carbon::parse($data->date_booking)->format('d F Y');
                //     return $date;
                // })
                ->addColumn('kodebook', function ($data) {
                    return $data->kode_booking;
                })
                ->addColumn('material', function ($data) {
                    return $data->matcontents;
                })
                ->addColumn('forwarder', function ($data) {
                    return $data->name;
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button = '<a href="#" data-id="' . $data->id_forwarder . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Approval Confirmation" class="fa fa-info-circle fa-lg"></i></a>';

                    return $button;
                })
                // ->rawColumns(['poku', 'date', 'material', 'status', 'action'])
                // ->rawColumns(['status'])
                ->make(true);
        }
    }

    public function listapproval()
    {
        $data = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
            ->where('formpo.statusformpo', 'waiting')
            ->where('privilege.privilege_aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('forwarder.aktif', 'Y')
            ->groupby('formpo.kode_booking')
            ->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nomorpo', function ($data) {
                $datapo = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                    ->join('po', 'po.id', 'formpo.idpo')
                    ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                    ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
                    // ->where('po.pideldate', $data->pideldate)
                    ->where('formpo.kode_booking', $data->kode_booking)
                    ->where('formpo.statusformpo', 'waiting')
                    ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('masterforwarder.aktif', 'Y')
                    ->where('forwarder.aktif', 'Y')
                    ->groupby('po.pono')
                    ->pluck('po.pono');
                // dd($datapo);
                return str_replace("]", "", str_replace("[", "", str_replace('"', "", $datapo)));
            })
            ->addColumn('nobooking', function ($data) {
                $databook = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                    ->join('po', 'po.id', 'formpo.idpo')
                    ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                    ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
                    // ->where('po.pideldate', $data->pideldate)
                    ->where('formpo.kode_booking', $data->kode_booking)
                    ->where('formpo.statusformpo', 'waiting')
                    ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('masterforwarder.aktif', 'Y')
                    ->where('forwarder.aktif', 'Y')
                    ->groupby('po.pono')
                    ->pluck('formpo.kode_booking');
                // return $data->kode_booking;
                return str_replace("[", "", str_replace("]", "", str_replace('"', "", $databook)));
            })
            ->addColumn('action', function ($data) {
                $button = '';

                $button = '<center><a href="#" data-id="' . $data->kode_booking . '" id="prosesapproval"><i data-tooltip="tooltip" title="Proses Approval" class="fa fa-arrow-circle-right fa-lg text-green"></i></a></center>';

                return $button;
            })
            // ->rawColumns(['status'])
            ->make(true);
    }

    public function getdataapproval(Request $request)
    {
        // dd($request);

        $dataku = po::join('forwarder', 'forwarder.idpo', 'po.id')->where('forwarder.aktif', 'Y')
            ->join('formpo', 'formpo.idforwarder', 'forwarder.id_forwarder')->where('formpo.aktif', 'Y')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')->where('masterforwarder.aktif', 'Y')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')->where('privilege_aktif', 'Y')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')->where('mastersupplier.aktif', 'Y')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')->where('masterhscode.aktif', 'Y')
            ->join('masterroute', 'masterroute.id_route', 'formpo.idroute')->where('masterroute.aktif', 'Y')
            ->where('formpo.kode_booking', $request->id)
            ->where('formpo.statusformpo', 'waiting')
            ->where('privilege.leadforwarder', '1')
            ->selectRaw(' po.id, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.colorcode, po.size, po.statusalokasi, po.pino, forwarder.statusbooking, forwarder.id_forwarder, formpo.id_formpo, formpo.kode_booking, formpo.qty_booking, formpo.date_booking, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, formpo.created_by, masterforwarder.name, privilege.privilege_user_name, privilege.privilege_user_nik, mastersupplier.nama, masterhscode.hscode, masterroute.route_code, masterroute.route_desc')
            ->get();
        // dd($dataku);

        $podata = po::join('forwarder', 'forwarder.idpo', 'po.id')->where('forwarder.aktif', 'Y')
            ->join('formpo', 'formpo.idforwarder', 'forwarder.id_forwarder')->where('formpo.aktif', 'Y')
            ->join('masterroute', 'masterroute.id_route', 'formpo.idroute')->where('masterroute.aktif', 'Y')
            ->join('masterportofloading', 'masterportofloading.id_portloading', 'formpo.idportloading')->where('masterportofloading.aktif', 'Y')
            ->join('masterportofdestination', 'masterportofdestination.id_portdestination', 'formpo.idportdestination')->where('masterportofdestination.aktif', 'Y')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')->where('privilege_aktif', 'Y')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')->where('mastersupplier.aktif', 'Y')
            ->where('formpo.kode_booking', $request->id)
            ->where('formpo.statusformpo', 'waiting')
            ->groupby('po.pono')
            // ->where('privilege.nikfinance', Session::get('session')['user_nik'])
            ->selectRaw(' po.id, po.pono, po.pino, mastersupplier.nama, formpo.kode_booking, formpo.date_booking, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, formpo.package, formpo.created_by, masterroute.route_code, masterroute.route_desc, masterportofloading.code_port as loadingcode, masterportofloading.name_port as loadingname, masterportofdestination.code_port as destinationcode, masterportofdestination.name_port as destinationname')
            ->get();
        // dd($podata);

        $data = [
            'dataku' => $dataku,
            'datapo' => $podata
        ];
        // dd($data);
        LogActivity::addToLog('Web Forwarder :: Logistik : Process Approval Data PO by Logistik', $this->micro);
        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function getdetailapproval(Request $request)
    {
        // dd($request);

        $dataformpo = formpo::join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->where('formpo.idforwarder', $request->id)
            ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')
            ->first();
        // dd($dataformpo);
        // $data = [
        //     'datapo' => $datapo,
        //     'databooking' => $databooking,
        //     'dataforward' => $dataforward,
        //     'privilege' => $privilege,
        //     'approval' => $approval,
        //     'jenis'    => 'detail',
        //     'user' => $privilegeuser
        // ];

        return response()->json(['status' => 200, 'data' => $dataformpo, 'message' => 'Berhasil']);
    }

    public function statusapproval(Request $request, $approval)
    {
        // dd($request, $approval);

        if ($approval == 'disetujui') {
            DB::beginTransaction();
            foreach ($request->dataid as $key => $val) {
                $updateformpo = formpo::where('id_formpo', $val['idformpo'])->update([
                    'statusformpo' => 'confirm',
                    'user_approval' => Session::get('session')['user_nik'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Session::get('session')['user_nik']
                ]);

                $updatepo = po::where('id', $val['idpo'])->update([
                    'statusconfirm' => 'confirm',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_user' => Session::get('session')['user_nik']
                ]);

                $updatefwd = fwd::where('id_forwarder', $val['idfwd'])->update([
                    'statusapproval' => 'confirm',
                    'statusallocation' => 'confirmed',
                    'date_fwd'   => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Session::get('session')['user_nik']
                ]);

                if ($updatepo && $updatefwd && $updateformpo) {
                    $sukses[] = "OK";
                } else {
                    $gagal[] = "OK";
                }
            }

            if (empty($gagal)) {
                DB::commit();
                LogActivity::addToLog('Web Forwarder :: Logistik : Approval Confirmed', $this->micro);
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Confirmed'];
                return response()->json($status, 200);
            } else {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Confirmed'];
                return response()->json($status, 200);
            }
        } else {
            DB::beginTransaction();
            foreach ($request->dataid as $key => $val) {
                if ($request->tolak == null || $request->tolak == '') {
                    DB::rollback();
                    $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data Keterangan is required, please input keterangan'];
                    return response()->json($status, 200);
                }
                $updateformpo = formpo::where('id_formpo', $val['idformpo'])->update([
                    'statusformpo' => 'reject',
                    'ket_tolak' => $request->tolak,
                    'user_approval' => Session::get('session')['user_nik'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Session::get('session')['user_nik']
                ]);

                $updatepo = po::where('id', $val['idpo'])->update([
                    'statusconfirm' => 'reject',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_user' => Session::get('session')['user_nik']
                ]);

                $updatefwd = fwd::where('id_forwarder', $val['idfwd'])->update([
                    'statusapproval' => 'reject',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Session::get('session')['user_nik']
                ]);

                if ($updatepo && $updatefwd && $updateformpo) {
                    $sukses[] = "OK";
                } else {
                    $gagal[] = "OK";
                }
            }

            if (empty($gagal)) {
                DB::commit();
                LogActivity::addToLog('Web Forwarder :: Logistik : Approval Rejected', $this->micro);
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Rejected'];
                return response()->json($status, 200);
            } else {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Rejected'];
                return response()->json($status, 200);
            }
        }
    }

    function getkaryawan(Request $request, $id)
    {
        $login_url        = 'http://' . $this->ip_server . '/api/detailkaryawan.php?n=' . $id;
        $login_client     = new Client();
        $login_res        = $login_client->get($login_url);
        $result = json_decode(base64_decode($login_res->getBody()), TRUE);

        if (count($result) > 1) {
            $nama = $result['nama'];

            if ($result['aktif'] == 'Pasif') {
                $da = array(
                    "status" => 'no',
                    "namaasli" => '',
                    "data" => '<b style="color:red"> STATUS KARYAWAN PASIF</b>'
                );

                return $da;
            }
            $da = array(
                "status" => 'yes',
                "namaasli" => $result['nama'],
                "data" => $nama
            );
            return $da;
        }

        $da = array(
            "status" => 'no',
            "namaasli" => '',
            "data" => '<b style="color:red"> NIK TIDAK DITEMUKAN</b>'
        );

        return $da;
    }
}
