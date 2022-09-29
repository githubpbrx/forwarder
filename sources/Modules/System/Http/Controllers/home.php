<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use File;
// use Response;
use Illuminate\Support\Facades\Response;
use Session, Crypt, DB;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;

use Modules\System\Models\modelpo as po;
use Modules\System\Models\modelprivilege as privilege;
use Modules\System\Models\modelformpo as formpo;
use Modules\System\Models\modelcoc as coc;
use Modules\System\Models\modelkyc as kyc;
use Modules\System\Models\modelforwarder as forwarder;

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
        $datauser = privilege::where('privilege_user_nik', Session::get('session')['user_nik'])->first();

        // Start For Forwarder
        $datapo = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->join('po', 'po.id', 'forwarder.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where(function ($qq) {
                $qq->where('po.statusalokasi', 'partial_allocated')->orWhere('po.statusalokasi', 'full_allocated');
            })
            ->where('forwarder.statusapproval', '=', null)
            ->groupby('po.pono')
            ->get();
        // dd($datapo);

        $totalreject = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'reject')
            ->where('formpo.file_bl', '=', null)
            ->where('formpo.nomor_bl', '=', null)
            ->where('formpo.vessel', '=', null)
            ->where('aktif', 'Y')
            ->selectRaw(' po.pono, formpo.kode_booking, formpo.date_booking, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, formpo.ket_tolak ')
            ->groupby('po.pono')
            ->get();

        $datareject = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'reject')
            ->where('formpo.file_bl', '=', null)
            ->where('formpo.nomor_bl', '=', null)
            ->where('formpo.vessel', '=', null)
            ->where('aktif', 'Y')
            ->selectRaw(' po.pono, po.matcontents, po.itemdesc, formpo.kode_booking, formpo.date_booking, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, formpo.ket_tolak ')
            ->get();
        // dd($datareject);

        $dataconfirm = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'confirm')
            ->where('formpo.file_bl', '=', null)
            ->where('formpo.nomor_bl', '=', null)
            ->where('formpo.vessel', '=', null)
            ->where('formpo.aktif', 'Y')
            ->groupby('po.pono')
            ->get();
        // dd($dataconfirm);

        $userkyc = privilege::join('kyc', 'kyc.idmasterfwd', 'privilege.idforwarder')
            ->where('nikfinance', Session::get('session')['user_nik'])
            ->where('kyc.aktif', 'Y')
            ->where('kyc.status', 'waiting')
            ->get();
        // dd($userkyc);
        // End For Forwarder

        $dataapproval = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.nikfinance', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'waiting')
            ->where('formpo.aktif', 'Y')
            ->groupby('po.pono')
            ->get();
        // dd($dataapproval);

        $data = array(
            'title'         => 'Dashboard',
            'menu'          => 'dashboard',
            'box'           => '',
            'totalpo'       => count($datapo),
            'totalconfirm'  => count($dataconfirm),
            'totalreject'   => count($totalreject),
            'datareject'    => $datareject,
            'totalapproval' => count($dataapproval),
            'datauser'      => $datauser,
            'totalkyc'      => count($userkyc),
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

    public function pageupdate()
    {
        $data = array(
            'title' => 'Data List Update Shipment',
            'menu'  => 'updateshipment',
            'box'   => '',
        );
        return view('system::dashboard/updateshipment', $data);
    }

    public function pageapproval()
    {
        $data = array(
            'title' => 'Data List Approval',
            'menu'  => 'listapproval',
            'box'   => '',
        );
        return view('transaksi::listapproval', $data);
    }

    public function pagekyc()
    {
        $data = array(
            'title' => 'Data List KYC',
            'menu'  => 'listkyc',
            'box'   => '',
        );
        return view('system::dashboard/listkyc', $data);
    }

    public function listpo()
    {
        $query = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->join('po', 'po.id', 'forwarder.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            // ->where('forwarder.statusapproval', '=', null)
            // ->where(function ($qq) {
            //     $qq->where('po.statusalokasi', 'partial_allocated')->orWhere('po.statusalokasi', 'full_allocated');
            // })
            ->where(function ($kus) {
                $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject');
            })
            ->selectRaw(' forwarder.statusforwarder, forwarder.statusapproval, po.id, po.pono, po.itemdesc ')
            ->groupby('po.pono')
            ->get();
        // dd($query);

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('listpo', function ($query) {
                return  $query->pono;
            })
            ->addColumn('itempo', function ($query) {
                return  $query->itemdesc;
            })
            ->addColumn('statusalokasi', function ($query) {
                if ($query->statusforwarder == 'full_allocated') {
                    $alokasi = 'Full Allocation';
                } else {
                    $alokasi = 'Partial Allocation';
                }
                return  $alokasi;
            })
            ->addColumn('status', function ($query) {
                // if ($query->statusconfirm == 'confirm') {
                //     $stat = 'Confirmed';
                // } else {
                //     $stat = 'Rejected';
                // }
                return  $query->statusapproval;
            })
            ->addColumn('action', function ($query) {
                $process    = '';

                $process    = '<a href="#" data-id="' . $query->pono . '" id="formpo"><i class="fa fa-angle-double-right text-orange"></i></a>';

                return $process;
            })
            // ->rawColumns(['listpo', 'action'])
            ->make(true);
    }

    public function listupdate()
    {
        // $query = formpo::where('status', 'confirm')->where('file_bl', '=', null)->where('nomor_bl', '=', null)->where('vessel', '=', null)->where('aktif', 'Y')->get();
        $query = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'confirm')
            ->where('formpo.file_bl', '=', null)
            ->where('formpo.nomor_bl', '=', null)
            ->where('formpo.vessel', '=', null)
            ->where('formpo.aktif', 'Y')
            ->groupby('po.pono')
            ->get();
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('listpo', function ($query) {
                return  $query->pono;
            })
            ->addColumn('listitem', function ($query) {
                return  $query->itemdesc;
            })
            // ->addColumn('status', function ($query) {
            //     return  $query->status;
            // })
            ->addColumn('action', function ($query) {
                $process    = '';

                $process    = '<a href="#" data-id="' . $query->id . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a>';

                return $process;
            })
            // ->rawColumns(['listpo', 'action'])
            ->make(true);
    }

    public function listkyc()
    {
        // $query = coc::where('status', '=', 'waiting')->where('aktif', 'Y')->get();
        $query = privilege::join('kyc', 'kyc.idmasterfwd', 'privilege.idforwarder')->where('nikfinance', Session::get('session')['user_nik'])->where('kyc.aktif', 'Y')->where('kyc.status', 'waiting')->get();
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                return  $query->name_kyc;
            })
            ->addColumn('namefile', function ($query) {
                return  $query->file_kyc;
            })
            ->addColumn('status', function ($query) {
                return  $query->status;
            })
            ->addColumn('action', function ($query) {
                $process    = '';

                $process    = '<a href="#" data-id="' . $query->idforwarder . '" id="processkyc"><i class="fa fa-angle-double-right text-green"></i></a>';

                return $process;
            })
            // ->rawColumns(['listpo', 'action'])
            ->make(true);
    }

    public function formpo(Request $request)
    {
        // dd($request);
        $mydata = po::where('pono', $request->id)->get();
        $dataforwarder = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('po_nomor', $request->id)->where('aktif', 'Y')->get();
        // dd($dataforwarder);
        $data = array(
            'datapo' => $mydata,
            'dataforwarder' => $dataforwarder
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function formupdate(Request $request)
    {
        // dd($request);
        // $databook = formpo::where('id_formpo', $request->id)->first();
        // $mydata = po::where('id', $databook->idpo)->first();

        $mydata = po::join('formpo', 'formpo.idpo', 'po.id')
            ->where('po.id', $request->id)
            ->where('formpo.aktif', 'Y')
            ->first();

        $dataforwarder = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('idpo', $request->id)->where('aktif', 'Y')->first();
        // dd($dataforwarder);


        // dd($mydata);
        $data = array(
            'dataku' => $mydata,
            'dataforwarder' => $dataforwarder
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function formkyc(Request $request)
    {
        // dd($request);
        $datakyc = kyc::where('idmasterfwd', $request->id)->first();

        $data = array(
            'datakyc' => $datakyc
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function saveformpo(Request $request)
    {
        // dd($request);
        DB::beginTransaction();

        if ($request->shipmode == 'fcl') {
            $submode = $request->fcl;
        } else if ($request->shipmode == 'lcl') {
            $submode = $request->lcl . ' ' . 'CBM';
        } else {
            $submode = $request->air . ' ' . 'KG';
        }

        foreach ($request->dataid as $key => $val) {

            if ($request->nobooking == '' || $request->nobooking == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Nomor Booking is required, please input Nomor Booking'];
                return response()->json($status, 200);
            }
            if ($request->datebooking == '' || $request->datebooking == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Date Booking is required, please input Date Booking'];
                return response()->json($status, 200);
            }
            if ($request->etd == '' || $request->etd == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETD is required, please input ETD'];
                return response()->json($status, 200);
            }
            if ($request->eta == '' || $request->eta == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETA is required, please input ETA'];
                return response()->json($status, 200);
            }
            if ($request->shipmode == '' || $request->shipmode == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Ship Mode is required, please input Ship Mode'];
                return response()->json($status, 200);
            }
            if ($request->shipmode == 'lcl' && $request->lcl == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'LCL is required, please input LCL'];
                return response()->json($status, 200);
            }
            if ($request->shipmode == 'air' && $request->air == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'AIR is required, please input AIR'];
                return response()->json($status, 200);
            }

            $cekpino = po::where('id', $val['idpo'])
                ->where(function ($var) {
                    $var->where('pino', '=', " ")->orWhere('pino', '=', null);
                })
                ->first();
            if ($cekpino != null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Please contact the supplier for the pino input validation process'];
                return response()->json($status, 200);
            }
            // dd($cekpino);

            $cekformpo = formpo::where('idpo', $val['idpo'])->where('idforwarder', $val['idfwd'])->where('idmasterfwd', $val['idmasterfwd'])->where('statusformpo', 'reject')->where('aktif', 'Y')->first();
            // dd($cekformpo);
            if ($cekformpo != null) {
                $del = formpo::where('id_formpo', $cekformpo->id_formpo)->update(['aktif' => 'N']);
            }

            $save1 = formpo::insert([
                'idpo'          => $val['idpo'],
                'idmasterfwd'   => $val['idmasterfwd'],
                'idforwarder'   => $val['idfwd'],
                'kode_booking'  => $request->nobooking,
                'date_booking'  => $request->datebooking,
                'etd'           => $request->etd,
                'eta'           => $request->eta,
                'shipmode'      => $request->shipmode,
                'subshipmode'   => $submode,
                'statusformpo'  => 'waiting',
                'aktif'         => 'Y',
                'created_at'    => date('Y-m-d H:i:s'),
                'created_by'    => Session::get('session')['user_nik']
            ]);

            $save2 = po::where('id', $val['idpo'])->update([
                'statusconfirm' => 'waiting'
            ]);

            $save3 = forwarder::where('id_forwarder', $val['idfwd'])->update([
                'statusapproval' => 'waiting'
            ]);

            if ($save1 && $save2 && $save3) {
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

    public function saveshipment(Request $request)
    {
        // $decode = json_decode($request->dataid);
        // dd($request);

        $file = $request->file('file');
        $originalName = str_replace(' ', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . $originalName;
        Storage::disk('local')->put($fileName, file_get_contents($request->file));

        // foreach ($decode as $key => $value) {
        if ($file == '' || $file == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'File BL is required, please input File BL'];
            return response()->json($status, 200);
        }

        if ($request->nomorbl == '' || $request->nomorbl == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Nomor BL is required, please input Nomor BL'];
            return response()->json($status, 200);
        }

        if ($request->vessel == '' || $request->vessel == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Vessel is required, please input Vessel'];
            return response()->json($status, 200);
        }

        if ($request->invoice == '' || $request->invoice == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Invoice is required, please input Invoice'];
            return response()->json($status, 200);
        }

        if ($request->etdfix == '' || $request->etdfix == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETD Fix is required, please input ETD Fix'];
            return response()->json($status, 200);
        }

        if ($request->etafix == '' || $request->etafix == null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETA Fix is required, please input ETA Fix'];
            return response()->json($status, 200);
        }

        $save1 = formpo::where('id_formpo', $request->idformpo)->update([
            'file_bl'    => $fileName,
            'nomor_bl'   => $request->nomorbl,
            'vessel'     => $request->vessel,
            'noinv'      => $request->invoice,
            'etdfix'     => $request->etdfix,
            'etafix'     => $request->etafix,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        //     if ($save1) {
        //         $sukses[] = "OK";
        //     } else {
        //         $gagal[] = "OK";
        //     }
        // }

        if ($save1) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    public function statuskyc(Request $request, $approval)
    {
        // dd($request, $approval);
        if ($approval == 'disetujui') {
            DB::beginTransaction();
            $statusupdate = kyc::where('idmasterfwd', $request->idfwd)->update([
                'status' => 'confirm',
                'user_approval' => Session::get('session')['user_nik'],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Session::get('session')['user_nik']
            ]);

            $kycupdate = privilege::where('idforwarder', $request->idfwd)->update([
                'kyc' => 'Y',
                'kyc_date' => date('Y-m-d H:i:s'),
            ]);

            if ($statusupdate && $kycupdate) {
                DB::commit();
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        } else {
            $statusupdate = kyc::where('idmasterfwd', $request->idfwd)->update([
                'status' => 'reject',
                'user_approval' => Session::get('session')['user_nik'],
                'ket_tolak' => $request->tolak,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Session::get('session')['user_nik']
            ]);

            if ($statusupdate) {
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        }
    }
}
