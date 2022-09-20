<?php

namespace Modules\Transaksi\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;
use GuzzleHttp\Client;
use Modules\Transaksi\Models\mastersupplier as supplier;
use Modules\Transaksi\Models\masterforwarder as forwarder;
use Modules\Transaksi\Models\modelformpo as formpo;
use Modules\Transaksi\Models\modelpo as po;
use Modules\Transaksi\Models\modelforwarder as fwd;
use Modules\Transaksi\Models\masterbuyer as buyer;
use Modules\Transaksi\Models\modelapproval as approval;
use Modules\System\Models\modelprivilege as privilege;

class ApprovalConfirmation extends Controller
{
    protected $ip_server;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
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
                    $where .= ' AND status="' . $request->statusfwd . '" ';
                }
                if ($request->tanggal1 != "" and $request->tanggal2 != "") {
                    $where .= ' AND (date_booking BETWEEN "' . $request->tanggal1 . '" AND "' . $request->tanggal2 . '") ';
                }
                if ($request->buyer != "") {
                    $where .= ' AND buyer=" ' . $request->buyer . '" ';
                }
                if ($request->book != "") {
                    $where .= ' AND kode_booking=" ' . $request->book . '" ';
                }
                // $data = po::whereRaw(' vendor="' . $request->supplier . '"   ' . $where . ' ')->get();
                $data = formpo::join('po', 'po.id', 'formpo.idpo')
                    ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                    ->whereRaw(' vendor="' . $request->supplier . '" ' . $where . ' ')
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('booking', function ($data) {
                    return $data->kode_booking;
                })
                ->addColumn('date', function ($data) {
                    $date = Carbon::parse($data->date_booking)->format('d F Y');
                    return $date;
                })
                ->addColumn('forwarder', function ($data) {
                    return $data->name;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == 'all') {
                        $statusku = 'All';
                    } elseif ($data->status == 'waiting') {
                        $statusku = 'Waiting';
                    } elseif ($data->status == 'confirm') {
                        $statusku = 'Confirmed';
                    } else {
                        $statusku = 'Rejected';
                    }

                    return $statusku;
                })
                // ->addColumn('action', function ($data) {
                //     $button = '';

                //     if ($data->status == 'all') {
                //         $button = '<a href="#" data-id="' . $data->poid . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                //     } elseif ($data->status == 'waiting' && $data->idapproval == null) {
                //         $button = '<a href="#" data-id="' . $data->poid . '" id="waitbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-angle-double-right fa-lg text-green"></i></a>';
                //     } elseif ($data->status == 'confirm') {
                //         $button = '<a href="#" data-id="' . $data->poid . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                //     } else {
                //         $button = '<a href="#" data-id="' . $data->poid . '" id="detailbtn"><i data-tooltip="tooltip" title="Detail Allocation" class="fa fa-info-circle fa-lg"></i></a>';
                //     }

                //     return $button;
                // })
                // ->rawColumns(['poku', 'date', 'material', 'status', 'action'])
                // ->rawColumns(['status'])
                ->make(true);
        }
    }

    public function listapproval()
    {
        $data = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.nikfinance', Session::get('session')['user_nik'])
            ->where('formpo.status', 'waiting')
            ->where('formpo.aktif', 'Y')
            ->groupby('po.pono')
            ->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nomorpo', function ($data) {
                return $data->pono;
            })
            ->addColumn('nobooking', function ($data) {
                return $data->kode_booking;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                $button = '<a href="#" data-id="' . $data->pono . '" id="prosesapproval"><i data-tooltip="tooltip" title="Proses Approval" class="fa fa-arrow-circle-right fa-lg text-green"></i></a>';

                return $button;
            })
            // ->rawColumns(['poku', 'date', 'material', 'status', 'action'])
            // ->rawColumns(['status'])
            ->make(true);
    }

    public function getdataapproval(Request $request)
    {
        // dd($request);

        $dataku = po::join('forwarder', 'forwarder.idpo', 'po.id')->where('forwarder.aktif', 'Y')
            ->join('formpo', 'formpo.idforwarder', 'forwarder.id_forwarder')->where('formpo.aktif', 'Y')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')->where('masterforwarder.aktif', 'Y')
            ->join('privilege', 'privilege.privilege_user_nik', 'formpo.created_by')
            ->where('po.pono', $request->id)
            ->selectRaw(' po.id, po.pono, po.matcontents, po.qtypo, po.statusalokasi, forwarder.qty_allocation, formpo.id_formpo, formpo.kode_booking, formpo.date_booking, formpo.noinv, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, masterforwarder.name, privilege.privilege_user_name, privilege.privilege_user_nik')
            ->get();

        $data = [
            'dataku' => $dataku
        ];

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function getdetailapproval(Request $request)
    {
        // dd($request);

        $datapo = po::where('id', $request->id)->first();
        $databooking = formpo::where('idpo', $request->id)->where('aktif', 'Y')->first();
        $dataforward = fwd::join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')->where('forwarder.idpo', $request->id)->first();
        $privilege = privilege::where('privilege_user_nik', $databooking->created_by)->first();
        $approval = approval::where('id_approval', $databooking->idapproval)->first();
        $privilegeuser = privilege::where('privilege_user_nik', $approval->user_pengesah)->first();

        $data = [
            'datapo' => $datapo,
            'databooking' => $databooking,
            'dataforward' => $dataforward,
            'privilege' => $privilege,
            'approval' => $approval,
            'jenis'    => 'detail',
            'user' => $privilegeuser
        ];

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function statusapproval(Request $request, $approval)
    {
        // dd($request, $approval);

        if ($approval == 'disetujui') {
            DB::beginTransaction();
            foreach ($request->dataid as $key => $val) {
                $updateformpo = formpo::where('id_formpo', $val['idformpo'])->update([
                    'status' => 'confirm',
                    'user_approval' => Session::get('session')['user_nik'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Session::get('session')['user_nik']
                ]);

                $updatepo = po::where('id', $val['idpo'])->update([
                    'statusconfirm' => 'confirm',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_user' => Session::get('session')['user_nik']
                ]);

                if ($updateformpo && $updatepo) {
                    $sukses[] = "OK";
                } else {
                    $gagal[] = "OK";
                }
            }

            if (empty($gagal)) {
                DB::commit();
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
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
                    'status' => 'reject',
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

                if ($updateformpo && $updatepo) {
                    $sukses[] = "OK";
                } else {
                    $gagal[] = "OK";
                }
            }
            if (empty($gagal)) {
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
