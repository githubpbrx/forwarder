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
        $datapo = po::join('privilege', 'privilege.idforwarder', 'po.idmasterfwd')->where('privilege_user_nik', Session::get('session')['user_nik'])->where('statusalokasi', 'full_allocated')->where('statusconfirm', '=', null)->get();
        // $datapo = po::whereRaw(' (statusalokasi="partial_allocated" OR statusalokasi="full_allocated") AND statusconfirm="' . null . '" ')->get();
        // dd($datapo);
        $datauser = privilege::where('privilege_user_nik', Session::get('session')['user_nik'])->first();

        $dataconfirm = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')->where('privilege_user_nik', Session::get('session')['user_nik'])->where('status', '=', 'confirm')->where('file_bl', '=', null)->where('nomor_bl', '=', null)->where('vessel', '=', null)->where('aktif', 'Y')->get();
        // dd($dataconfirm);
        $dataapproval = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')->where('nikfinance', Session::get('session')['user_nik'])->where('status', '=', 'waiting')->where('aktif', 'Y')->get();
        // dd($dataapproval);
        $usercoc = privilege::join('coc', 'coc.idmasterfwd', 'privilege.idforwarder')->where('nikfinance', Session::get('session')['user_nik'])->where('coc.aktif', 'Y')->where('coc.status', 'waiting')->get();
        // dd($usercoc);
        // $datacoc = coc::where('status', '=', 'waiting')->where('aktif', 'Y')->get();

        $userkyc = privilege::join('kyc', 'kyc.idmasterfwd', 'privilege.idforwarder')->where('nikfinance', Session::get('session')['user_nik'])->where('kyc.aktif', 'Y')->where('kyc.status', 'waiting')->get();
        // dd($userkyc);
        $data = array(
            'title' => 'Dashboard',
            'menu'  => 'dashboard',
            'box'   => '',
            'totalpo' => count($datapo),
            'totalconfirm' => count($dataconfirm),
            'totalapproval' => count($dataapproval),
            'datauser' => $datauser,
            'totalcoc' => count($usercoc),
            'totalkyc' => count($userkyc),
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

    public function pagecoc()
    {
        $data = array(
            'title' => 'Data List CoC',
            'menu'  => 'listcoc',
            'box'   => '',
        );
        return view('system::dashboard/listcoc', $data);
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
        $query = po::join('privilege', 'privilege.idforwarder', 'po.idmasterfwd')->where('privilege_user_nik', Session::get('session')['user_nik'])->where('statusalokasi', 'full_allocated')->where('statusconfirm', '=', null)->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('listpo', function ($query) {
                return  $query->pono;
            })
            ->addColumn('itempo', function ($query) {
                return  $query->itemdesc;
            })
            ->addColumn('action', function ($query) {
                $process    = '';

                $process    = '<a href="#" data-id="' . $query->id . '" id="formpo"><i class="fa fa-angle-double-right text-orange"></i></a>';

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
            ->where('privilege_user_nik', Session::get('session')['user_nik'])->where('status', '=', 'confirm')->where('file_bl', '=', null)->where('nomor_bl', '=', null)->where('vessel', '=', null)->where('aktif', 'Y')->get();
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('listpo', function ($query) {
                return  $query->pono;
            })
            ->addColumn('listitem', function ($query) {
                return  $query->itemdesc;
            })
            ->addColumn('status', function ($query) {
                return  $query->status;
            })
            ->addColumn('action', function ($query) {
                $process    = '';

                $process    = '<a href="#" data-id="' . $query->id_formpo . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a>';

                return $process;
            })
            // ->rawColumns(['listpo', 'action'])
            ->make(true);
    }

    public function listcoc()
    {
        // $query = coc::where('status', '=', 'waiting')->where('aktif', 'Y')->get();
        $query = privilege::join('coc', 'coc.idmasterfwd', 'privilege.idforwarder')->where('nikfinance', Session::get('session')['user_nik'])->where('coc.aktif', 'Y')->where('coc.status', 'waiting')->get();
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                return  $query->name_coc;
            })
            ->addColumn('company', function ($query) {
                return  $query->company_coc;
            })
            ->addColumn('status', function ($query) {
                return  $query->status;
            })
            ->addColumn('action', function ($query) {
                $process    = '';

                $process    = '<a href="#" data-id="' . $query->idforwarder . '" id="processcoc"><i class="fa fa-angle-double-right text-green"></i></a>';

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
        $mydata = po::where('id', $request->id)->first();

        $data = array(
            'datapo' => $mydata
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function formupdate(Request $request)
    {
        // dd($request);
        $databook = formpo::where('id_formpo', $request->id)->first();
        $mydata = po::where('id', $databook->idpo)->first();

        $data = array(
            'datapo' => $mydata,
            'databook' => $databook
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }


    public function formcoc(Request $request)
    {
        // dd($request);
        $datacoc = coc::where('idmasterfwd', $request->id)->first();

        $data = array(
            'datacoc' => $datacoc
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

        if ($request->shipmode == 'fcl') {
            $submode = $request->fcl;
        } else if ($request->shipmode == 'lcl') {
            $submode = $request->lcl . ' ' . 'CBM';
        } else {
            $submode = $request->air . ' ' . 'KG';
        }

        DB::beginTransaction();
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

        $save1 = formpo::insert([
            'idpo'          => $request->idpo,
            'idmasterfwd'   => $request->idfwd,
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
            'statusconfirm' => 'waiting'
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

    public function saveshipment(Request $request)
    {
        $file = $request->file('file');
        $originalName = str_replace(' ', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . $originalName;
        Storage::disk('local')->put($fileName, file_get_contents($request->file));

        // dd($request);
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

        $save1 = formpo::where('id_formpo', $request->idformpo)->update([
            'file_bl'    => $fileName,
            'nomor_bl'   => $request->nomorbl,
            'vessel'     => $request->vessel,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        if ($save1) {
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    public function statuscoc(Request $request, $approval)
    {
        // dd($request, $approval);
        if ($approval == 'disetujui') {
            DB::beginTransaction();
            $statusupdate = coc::where('idmasterfwd', $request->idfwd)->where('aktif', 'Y')->update([
                'status' => 'confirm',
                'user_approval' => Session::get('session')['user_nik'],
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Session::get('session')['user_nik']
            ]);

            $cocupdate = privilege::where('idforwarder', $request->idfwd)->update([
                'coc' => 'Y',
                'coc_date' => date('Y-m-d H:i:s'),
            ]);

            if ($statusupdate && $cocupdate) {
                DB::commit();
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        } else {
            $statusupdate = coc::where('idmasterfwd', $request->idfwd)->update([
                'status' => 'reject',
                'ket_tolak' => $request->tolak,
                'user_approval' => Session::get('session')['user_nik'],
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

    public function downloadkyc(Request $request)
    {
        // dd($request);

        // $file = Storage::disk('local')->get($request->filekyc);
        // dd($file);
        $path = storage_path() . '/' . 'app' . '/' . $request->filekyc;
        // $path = Storage::disk('local')->get('1663126567_PO_sup.xlsx');
        return Response::download($path);
        // return Storage::download('1663126567_PO_sup.xlsx');
        // dd($path);
        // if (file_exists($path)) {
        //     // dd('exist');
        //     // return Response::download($path);
        //     return response()->download($path);
        // }
    }
}
