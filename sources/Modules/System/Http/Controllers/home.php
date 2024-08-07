<?php

namespace Modules\System\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\System\Models\modelforwarder;
use Yajra\Datatables\Datatables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\Report\Models\modelformpo;
use Modules\System\Helpers\LogActivity;

use Modules\System\Models\masterhscode;
use Modules\System\Models\mastercompany;
use Modules\System\Models\modelpo as po;
use Modules\System\Models\masterforwarder;
use Modules\System\Models\modelkyc as kyc;
use Modules\System\Models\masterportofloading;
use Modules\System\Models\masterroute as route;
use Modules\Transaksi\Models\modelinputratefcl;
use Modules\System\Models\modelformpo as formpo;
use Modules\Transaksi\Models\modelmappingratefcl;
use Modules\System\Models\masterportofdestination;
use Modules\System\Models\modelformpo as ModelsModelformpo;
use Modules\System\Models\modelforwarder as forwarder;
use Modules\System\Models\modelprivilege as privilege;
use Modules\System\Models\Privileges\modelgroup_access;
use Modules\System\Models\modelformshipment as shipment;

class home extends Controller
{
    protected $micro;
    public function __construct()
    {
        $this->middleware('checklogin');
        $this->micro = microtime(true);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }

    public function index()
    {
        $system = Session::get('system');

        $datauser = privilege::where('privilege_user_nik', Session::get('session')['user_nik'])->where('privilege_aktif', 'Y')->first();

        // -----------------------------------------------------------Start For Forwarder---------------------------------------------
        $datapo = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->join('po', 'po.id', 'forwarder.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            // ->where(function ($qq) {
            //     $qq->where('po.statusalokasi', 'partial_allocated')->orWhere('po.statusalokasi', 'full_allocated');
            // })
            ->where('po.statusalokasi', 'waiting')
            ->where('forwarder.statusapproval', null)
            ->where('forwarder.statusallocation', null)
            ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->select('privilege.privilege_user_nik', 'po.statusalokasi', 'po.pono', 'po.pideldate', 'po.pino', 'forwarder.statusapproval')
            ->groupby('po.pino')
            ->get();

        //data h-7 sebelum PI Delivery habis
        $exp = [];
        foreach ($datapo as $key => $value) {
            $datecoc =  Carbon::parse($value->pideldate)->subDays(7);
            $now =  Carbon::now();
            $bool = $now->gt($datecoc);

            if ($bool) {
                // dd($datecoc->format('Y-m-d'));
                array_push($exp, $value->pono);
            }
        }
        Session::put('datetimeout', $exp);

        $datacancel = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            // ->join('po', 'po.id', 'forwarder.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            // ->where('po.statusalokasi', 'waiting')
            ->where('forwarder.statusallocation', 'cancelled')
            ->where('viewcancel', null)
            ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->select('privilege.privilege_user_nik', 'forwarder.id_forwarder', 'forwarder.statusallocation')
            // ->groupby('po.pideldate')
            ->get();

        $totalreject = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('privilege.privilege_aktif', 'Y')
            ->where('formpo.statusformpo', '=', 'reject')
            ->where('formpo.aktif', 'Y')
            ->selectRaw(' po.pono, formpo.kode_booking, formpo.date_booking, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, formpo.ket_tolak ')
            ->groupby('po.pideldate')
            ->get();

        $datareject = formpo::join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->join('masterroute', 'masterroute.id_route', 'formpo.idroute')->where('masterroute.aktif', 'Y')
            ->join('masterportofloading', 'masterportofloading.id_portloading', 'formpo.idportloading')->where('masterportofloading.aktif', 'Y')
            ->join('masterportofdestination', 'masterportofdestination.id_portdestination', 'formpo.idportdestination')->where('masterportofdestination.aktif', 'Y')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('privilege.privilege_aktif', 'Y')
            ->where('formpo.statusformpo', '=', 'reject')
            ->where('formpo.aktif', 'Y')
            ->where('mastersupplier.aktif', 'Y')
            ->where('masterhscode.aktif', 'Y')
            ->groupby('po.pono')
            ->selectRaw(' po.pono, po.matcontents, po.itemdesc, po.qtypo, po.pino, formpo.kode_booking, formpo.date_booking, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, formpo.ket_tolak, formpo.package, mastersupplier.nama, masterhscode.hscode, masterroute.route_code, masterroute.route_desc, masterportofloading.code_port as loadingcode, masterportofloading.name_port as loadingname, masterportofdestination.code_port as destinationcode, masterportofdestination.name_port as destinationname ')
            ->get();

        $datarejecttabel = formpo::join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('privilege.privilege_aktif', 'Y')
            ->where('formpo.statusformpo', '=', 'reject')
            ->where('formpo.aktif', 'Y')
            ->where('mastersupplier.aktif', 'Y')
            ->where('masterhscode.aktif', 'Y')
            ->selectRaw(' po.pono, po.matcontents, po.itemdesc, po.qtypo, po.colorcode, po.size, po.pino, formpo.kode_booking, formpo.qty_booking, formpo.date_booking, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, formpo.ket_tolak, mastersupplier.nama, masterhscode.hscode ')
            ->get();

        //cek untuk notif shipment
        $cekshipment = formpo::join('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('formshipment.aktif', 'Y')
            // ->groupby('po.pono')
            ->select('formpo.id_formpo', 'formpo.idforwarder', 'po.pono', 'formshipment.id_shipment', 'formshipment.noinv')
            ->get()->pluck('id_formpo');

        $cekformpo = formpo::join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->whereNotIn('formpo.id_formpo', $cekshipment)
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->count();

        // cek for mapping rate

        $periodeawal = Carbon::now()->startOfMonth()->toDateString();
        $mapping = modelmappingratefcl::where('periodeawal', $periodeawal)->where('aktif', 'Y')->pluck('id');
        $idfwd = masterforwarder::where('name', Session::get('session')['user_nama'])->where('aktif', 'Y')->first('id');
        if ($idfwd != NULL) {
            $cekinput = modelinputratefcl::whereIn('id_mappingrate', $mapping)->where('id_forwarder', $idfwd->id)->where('aktif', 'Y')->count();
        } else {
            $cekinput = '';
        }

        // ---------------------------------------------End For Forwarder-----------------------------------------------------

        $userkyc = privilege::join('kyc', 'kyc.idmasterfwd', 'privilege.idforwarder')
            ->where('privilege.nikfinance', Session::get('session')['user_nik'])
            ->where('leadforwarder', '1')
            ->where('kyc.aktif', 'Y')
            ->where('kyc.status', 'waiting')
            ->where('privilege.privilege_aktif', 'Y')
            ->get();

        $grouplogistik = modelgroup_access::where('group_access_name', 'Logistik')->select('group_access_id')->first();
        $getprivilege = privilege::where('privilege_user_nik', Session::get('session')['user_nik'])->where('privilege_group_access_id', $grouplogistik->group_access_id)->where('privilege_aktif', 'Y')->get(['privilege_id']);
        if (count($getprivilege) >= 1) {
            $dataapproval = formpo::join('po', 'po.id', 'formpo.idpo')
                ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
                ->where('formpo.statusformpo', 'waiting')
                ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('forwarder.aktif', 'Y')
                ->groupby('po.pideldate')
                ->get(['formpo.id_formpo']);
        } else {
            $dataapproval = [];
        }

        // untuk notifikasi forwarder add new user
        if (count($getprivilege) >= 1) {
            $datauserfwd = privilege::where('privilege_aktif', 'N')
                ->where('status', 'waiting')
                ->where('deleted_at', null)
                ->get(['privilege_id']);
        } else {
            $datauserfwd = [];
        }

        // start untuk mengecek expired coc
        $ceklogin = privilege::where('privilege_user_nik', Session::get('session')['user_nik'])->where('privilege_aktif', 'Y')->first();
        $datecoc =  Carbon::parse($ceklogin->coc_date)->subDays(7)->addYear();
        $now =  Carbon::now();
        $bool = $now->gt($datecoc);

        //menampilkan sisa hari untuk expired
        $strnow = Carbon::now()->format('m/d/Y');
        $strdb = Carbon::parse($ceklogin->coc_date)->subDays(0)->addYear()->format('m/d/Y');
        $newDate = Carbon::createFromFormat('m/d/Y', $strdb);
        $result = Carbon::createFromFormat('m/d/Y', $strnow)->diffForHumans($newDate);
        // end expired coc

        $data = array(
            'title'           => 'Dashboard',
            'menu'            => 'dashboard',
            'box'             => '',
            'totalpo'         => count($datapo),
            'totalshipment'   => $cekformpo,
            'totalreject'     => count($totalreject),
            'datareject'      => $datareject,
            'datarejecttabel' => $datarejecttabel,
            'totalapproval'   => count($dataapproval),
            'datauser'        => $datauser,
            'totalkyc'        => count($userkyc),
            'newuser'         => count($datauserfwd),
            'cocexp'          => $bool,
            'viewdays'        => $result,
            'totaltimeout'    => count($exp),
            'totalcancel'     => count($datacancel),
            'mysystem'        => $system,
            'totalinput'    => $cekinput
        );

        LogActivity::addToLog('Web Forwarder : Access Menu Dashboard', $this->micro);
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

    public function pagepotimeout()
    {
        $data = array(
            'title' => 'Data List PO',
            'menu'  => 'pagepo',
            'box'   => '',
        );

        return view('system::dashboard/listpotimeout', $data);
    }

    public function pagecancel()
    {
        $view = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('statusallocation', 'cancelled')
            ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->select('forwarder.id_forwarder', 'forwarder.idmasterfwd', 'forwarder.po_nomor')
            ->get();
        // dd($view);
        foreach ($view as $key => $value) {
            forwarder::where('id_forwarder', $value->id_forwarder)->update(['viewcancel' => 1]);
        }

        $data = array(
            'title' => 'Data Cancelled',
            'menu'  => 'pagecancel',
            'box'   => '',
        );

        return view('system::dashboard/listcancel', $data);
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

    public function pagenewfwd()
    {
        $data = array(
            'title' => 'Data List New User Forwarder',
            'menu'  => 'listnewfwd',
            'box'   => '',
        );

        return view('system::dashboard/listnewfwd', $data);
    }
    public function getpi(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->join('po', 'po.id', 'forwarder.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where(function ($kus) {
                $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject');
            })
            ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' forwarder.statusforwarder, forwarder.statusapproval, po.id, po.pono, po.itemdesc, po.pino, po.pideldate, mastersupplier.nama ');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' po.pideldate like "%' . $search . '%" ');
        }

        $po = $po->orderby('po.pideldate', 'asc')->groupby('po.pideldate')->get();
        // dd($po);
        return response()->json($po);
    }

    public function listpo(Request $request)
    {
        if ($request->ajax()) {
            $where = '';
            if ($request->pidate != NULL) {
                $where .= ' AND po.pideldate="' . $request->pidate . '"';
            }

            $query = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                ->join('po', 'po.id', 'forwarder.idpo')
                ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                ->leftjoin('formpo', 'formpo.idforwarder', 'forwarder.id_forwarder')
                ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                ->where(function ($kus) {
                    $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject')->orWhere('forwarder.statusbooking', NULL)->orWhere('forwarder.statusbooking', 'partial_booking');
                })
                ->whereRaw('forwarder.aktif="Y" AND privilege.privilege_aktif="Y" AND mastersupplier.aktif="Y" ' . $where . ' ')
                ->selectRaw(' forwarder.idpo, forwarder.statusforwarder, forwarder.statusapproval, forwarder.statusbooking, po.id, po.pono, sum(po.qtypo) as qtypoku, po.itemdesc, po.pino, po.pideldate, po.company, mastersupplier.nama, sum(formpo.qty_booking) as qtybook ')
                ->groupby('po.pino')
                ->get();

            // dd($query);
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('cekbok', function ($query) {
                    $cekbox = '';
                    $cekbox = '<center><input type="checkbox" name="mycekbok" id="mycekbok" value=" ' . $query->id . '/' . $query->pino . '" ></center>';
                    return  $cekbox;
                })
                ->addColumn('pinomor', function ($query) {
                    return $query->pino;
                })
                ->addColumn('listpo', function ($query) {
                    $mypo = forwarder::join('po', 'po.id', 'forwarder.idpo')
                        ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                        ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                        ->where('po.pino', $query->pino)
                        ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                        ->where(function ($kus) {
                            $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject')->orWhere('forwarder.statusbooking', NULL)->orWhere('forwarder.statusbooking', 'partial_booking');
                        })
                        // ->where('forwarder.statusallocation', null)
                        ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
                        ->selectRaw('po.pono')
                        ->groupby('po.pono')
                        ->pluck('pono');

                    return  str_replace("]", "", str_replace("[", "", str_replace('"', "", $mypo)));
                })
                ->addColumn('pidel', function ($query) {
                    return  $query->pideldate;
                })
                ->addColumn('supplier', function ($query) {
                    return  $query->nama;
                })
                ->addColumn('company', function ($query) {
                    $getcompany = mastercompany::where('id', $query->company)->where('aktif', 'Y')->first();
                    if ($getcompany) {
                        $company = $getcompany->nama;
                    } else {
                        $company = $query->company;
                    }
                    return  $company;
                })
                ->addColumn('status', function ($query) {
                    $cekfwd = modelforwarder::where('po_nomor', $query->pono)->where('aktif', 'Y')->count();
                    $cekfwdnull = modelforwarder::where('po_nomor', $query->pono)->where('statusapproval', NULL)->where('aktif', 'Y')->count();
                    // dd($cekfwd, $cekfwdnull, $query->qtypo != $query->qtybook);
                    if ($query->statusbooking == 'partial_booking' && $query->statusapproval != 'reject') {
                        $stat = 'Partial Booking';
                    } elseif ($query->statusbooking == 'full_booking' && $query->statusapproval != 'reject') {
                        $stat = 'Not Processed';
                    } elseif ($cekfwd != $cekfwdnull && $query->statusapproval == 'reject') {
                        $stat = 'Not Processed';
                    } elseif ($query->qtypo != $query->qtybook && $query->statusapproval == 'reject') {
                        $stat = 'Partial Booking';
                    } elseif ($cekfwd != $cekfwdnull) {
                        $stat = 'Partial Booking';
                    } elseif ($query->statusapproval == 'reject') {
                        $stat = 'Not Processed';
                    } else {
                        $stat = 'Not Processed';
                    }
                    return  $stat;
                })
                // ->addColumn('statusalokasi', function ($query) {
                //     if ($query->statusforwarder == 'full_allocated') {
                //         $alokasi = 'Full Allocation';
                //     } else {
                //         $alokasi = 'Partial Allocation';
                //     }
                //     return  $alokasi;
                // })
                ->rawColumns(['cekbok'])
                ->make(true);
        }
    }

    public function listpotimeout(Request $request)
    {
        if ($request->ajax()) {
            // dd(Session::get('datetimeout'));
            $datadate = Session::get('datetimeout');
            // dd($datadate);
            if ($request->pidate == null) {
                $query = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                    ->join('po', 'po.id', 'forwarder.idpo')
                    ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                    ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                    ->where(function ($kus) {
                        $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject');
                    })
                    ->whereIn('po.pono', $datadate)
                    ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
                    ->selectRaw(' forwarder.statusforwarder, forwarder.statusapproval, po.id, po.pono, po.itemdesc, po.pino, po.pideldate, mastersupplier.nama ')
                    ->groupby('po.pino')
                    ->get();
            } else {
                $query = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                    ->join('po', 'po.id', 'forwarder.idpo')
                    ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                    ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                    ->where(function ($kus) {
                        $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject');
                    })
                    ->where('pideldate', $request->pidate)
                    ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
                    ->selectRaw(' forwarder.statusforwarder, forwarder.statusapproval, po.id, po.pono, po.itemdesc, po.pino, po.pideldate, mastersupplier.nama ')
                    ->groupby('po.pino')
                    ->get();
            }

            // dd($query);
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('cekbok', function ($query) {
                    $cekbox = '';
                    $cekbox = '<center><input type="checkbox" name="mycekbok" id="mycekbok" value="' . $query->pino . '" ></center>';
                    return  $cekbox;
                })
                ->addColumn('listpo', function ($query) {
                    $mypo = forwarder::join('po', 'po.id', 'forwarder.idpo')
                        ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                        ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                        ->where('po.pino', $query->pino)
                        ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                        ->where(function ($kus) {
                            $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject');
                        })
                        ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
                        ->selectRaw('po.pono')
                        ->groupby('po.pono')
                        ->pluck('pono');

                    return  str_replace("]", "", str_replace("[", "", str_replace('"', "", $mypo)));
                })
                ->addColumn('pinomor', function ($query) {
                    $mypo = forwarder::join('po', 'po.id', 'forwarder.idpo')
                        ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                        ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                        ->where('po.pino', $query->pino)
                        ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                        ->where(function ($kus) {
                            $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject');
                        })
                        ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
                        ->selectRaw('po.pino')
                        ->groupby('po.pono')
                        ->pluck('pino');

                    return  str_replace("]", "", str_replace("[", "", str_replace('"', "", $mypo)));
                })
                ->addColumn('pidel', function ($query) {
                    return  $query->pideldate;
                })
                ->addColumn('supplier', function ($query) {
                    $mypo = forwarder::join('po', 'po.id', 'forwarder.idpo')
                        ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                        ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                        ->where('po.pino', $query->pino)
                        ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                        ->where(function ($kus) {
                            $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject');
                        })
                        ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
                        ->selectRaw('mastersupplier.nama')
                        ->groupby('po.pono')
                        ->pluck('nama');

                    return  str_replace("]", "", str_replace("[", "", str_replace('"', "", $mypo)));
                })
                // ->addColumn('statusalokasi', function ($query) {
                //     if ($query->statusforwarder == 'full_allocated') {
                //         $alokasi = 'Full Allocation';
                //     } else {
                //         $alokasi = 'Partial Allocation';
                //     }
                //     return  $alokasi;
                // })
                // ->addColumn('action', function ($query) {
                //     $process    = '';

                //     $process    = '<a href="#" data-id="' . $query->pono . '" id="formpo"><i class="fa fa-angle-double-right text-orange"></i></a>';

                //     return $process;
                // })
                ->rawColumns(['cekbok'])
                ->make(true);
        }
    }

    public function listcancel(Request $request)
    {
        if ($request->ajax()) {

            // if ($request->pidate == null) {
            $query = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                ->join('po', 'po.id', 'forwarder.idpo')
                ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                ->where('statusallocation', 'cancelled')
                ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
                ->selectRaw(' forwarder.statusallocation, forwarder.statusapproval, po.id, po.pono,  po.matcontents, po.itemdesc, po.pino, po.pideldate, mastersupplier.nama ')
                // ->groupby('po.pino')
                ->get();
            // } else {
            //     $query = forwarder::join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            //         ->join('po', 'po.id', 'forwarder.idpo')
            //         ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            //         ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            //         ->where(function ($kus) {
            //             $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject');
            //         })
            //         ->where('pideldate', $request->pidate)
            //         ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            //         ->selectRaw(' forwarder.statusforwarder, forwarder.statusapproval, po.id, po.pono, po.itemdesc, po.pino, po.pideldate, mastersupplier.nama ')
            //         ->groupby('po.pino')
            //         ->get();
            // }

            // dd($query);
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('listpo', function ($query) {
                    return $query->pono;
                })
                ->addColumn('material', function ($query) {
                    return $query->matcontents;
                })
                ->addColumn('pidel', function ($query) {
                    return  $query->pideldate;
                })
                ->addColumn('supplier', function ($query) {
                    return $query->nama;
                })
                ->addColumn('status', function ($query) {
                    return $query->statusallocation;
                })
                // ->addColumn('statusalokasi', function ($query) {
                //     if ($query->statusforwarder == 'full_allocated') {
                //         $alokasi = 'Full Allocation';
                //     } else {
                //         $alokasi = 'Partial Allocation';
                //     }
                //     return  $alokasi;
                // })
                // ->addColumn('action', function ($query) {
                //     $process    = '';

                //     $process    = '<a href="#" data-id="' . $query->pono . '" id="formpo"><i class="fa fa-angle-double-right text-orange"></i></a>';

                //     return $process;
                // })
                // ->rawColumns(['cekbok'])
                ->make(true);
        }
    }

    public function listupdate()
    {
        // $query = formpo::where('status', 'confirm')->where('file_bl', '=', null)->where('nomor_bl', '=', null)->where('vessel', '=', null)->where('aktif', 'Y')->get();
        $query = formpo::withCount(['shipment as qtyship' => function ($var) {
            $var->select(DB::raw('sum(qty_shipment)'))->groupby('idformpo');
        }])
            ->with(['shipment' => function ($stat) {
                $stat->where('statusshipment', 'full_allocated');
            }])
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('po', 'po.id', 'formpo.idpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', '=', 'confirm')
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->groupby('po.pono')
            ->selectRaw(' formpo.*, po.id, po.pono, po.matcontents, po.colorcode, po.size, po.qtypo')
            ->get();
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('listpo', function ($query) {
                return  $query->pono;
            })
            ->addColumn('nobook', function ($query) {
                return  $query->kode_booking;
            })
            ->addColumn('qtypo', function ($query) {
                return  $query->qtypo;
            })
            ->addColumn('qtyship', function ($query) {
                $rep = str_replace('.', '', $query->qtypo);
                $qtypo = (float)$rep;

                if ($query->qtyship == null) {
                    $remain = $query->qtypo;
                } else if ($query->qtyship == $qtypo) {
                    $remain = '0';
                } else {
                    $remain = $qtypo - $query->qtyship;
                }
                return  $remain;
            })
            ->addColumn('status', function ($query) {
                if ($query->shipment == null) {
                    $status = 'No Status';
                } else {
                    if ($query->shipment->statusshipment == 'full_allocated') {
                        $status = 'Full Allocated';
                    } else {
                        $status = 'Partial Allocated';
                    }
                }

                return  $status;
            })
            ->addColumn('action', function ($query) {
                $process    = '';
                if ($query->shipment == null) {
                    $process    = '<a href="#" data-id="' . $query->id . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a>';
                } else {
                    if ($query->shipment->statusshipment == 'partial_allocated') {
                        $process    = '<a href="#" data-id="' . $query->id . '" id="updateship"><i class="fa fa-angle-double-right text-green"></i></a>';
                    } else {
                        $process = '';
                    }
                }

                return $process;
            })
            // ->rawColumns(['listpo', 'action'])
            ->make(true);
    }

    public function listkyc()
    {
        // $query = coc::where('status', '=', 'waiting')->where('aktif', 'Y')->get();
        $query = privilege::join('kyc', 'kyc.idmasterfwd', 'privilege.idforwarder')
            ->where('privilege.nikfinance', Session::get('session')['user_nik'])
            ->where('privilege.leadforwarder', '1')
            ->where('kyc.aktif', 'Y')
            ->where('kyc.status', 'waiting')
            ->where('privilege.privilege_aktif', 'Y')
            ->get();
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

    public function listnewfwd()
    {
        $grouplogistik = modelgroup_access::where('group_access_name', 'Logistik')->select('group_access_id')->first();
        $getprivilege = privilege::where('privilege_user_nik', Session::get('session')['user_nik'])->where('privilege_group_access_id', $grouplogistik->group_access_id)->where('privilege_aktif', 'Y')->get();
        if (count($getprivilege) >= 1) {
            $query = privilege::join('masterforwarder', 'masterforwarder.id', 'privilege.idforwarder')
                // ->where('nikfinance', Session::get('session')['user_nik'])
                ->where('privilege.privilege_aktif', 'N')
                ->where('masterforwarder.aktif', 'Y')
                ->where('privilege.status', 'waiting')
                ->where('privilege.deleted_at', null)
                ->get();
        } else {
            $query = [];
        }
        // dd($query);
        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('name', function ($query) {
                return  $query->privilege_user_nik;
            })
            ->addColumn('namefwd', function ($query) {
                return  $query->name;
            })
            ->addColumn('action', function ($query) {
                $process    = '';

                $process    = '<a href="#" data-id="' . $query->privilege_id . '" id="processnew"><i class="fa fa-angle-double-right text-green"></i></a>';

                return $process;
            })
            // ->rawColumns(['listpo', 'action'])
            ->make(true);
    }

    public function formpo(Request $request)
    {
        $datapo = [];
        $idpo = [];
        $pino = [];
        foreach ($request->dataku as $key => $val) {
            $mydatapo = explode("/", $val);
            $dataidpo = $mydatapo[0];
            $datapino = $mydatapo[1];

            $mydata = forwarder::withCount(['withformpo as qtybook' => function ($var) {
                $var->select(DB::raw('sum(qty_booking)'))->where('statusformpo', '!=', 'reject')->groupby('idforwarder');
            }])
                ->join('po', 'po.id', 'forwarder.idpo')
                ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
                ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                ->leftjoin('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
                ->where('po.pino', $datapino)
                ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                // ->where(function ($kus) {
                //     $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject')->orWhere('forwarder.statusbooking', NULL)->orWhere('forwarder.statusbooking', 'partial_booking');
                // })
                ->where('forwarder.aktif', 'Y')
                ->where(function ($hs) {
                    $hs->where('masterhscode.aktif', null)->orWhere('masterhscode.aktif', 'N')->orWhere('masterhscode.aktif', 'Y');
                })
                ->where('privilege.privilege_aktif', 'Y')
                ->where('mastersupplier.aktif', 'Y')
                ->selectRaw(' forwarder.*, po.id, po.pono, po.matcontents, po.itemdesc, po.colorcode, po.size, po.qtypo, po.pideldate, po.pino, mastersupplier.nama, masterhscode.hscode')
                ->get();
            array_push($datapo, $mydata);
            array_push($idpo, $dataidpo);
            array_push($pino, $datapino);
        }
        // dd($datapo);

        $mypo = forwarder::join('po', 'po.id', 'forwarder.idpo')
            ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->whereIn('po.pino', $pino)
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            // ->where(function ($kus) {
            //     $kus->where('forwarder.statusapproval', null)->orWhere('forwarder.statusapproval', 'reject')->orWhere('forwarder.statusbooking', NULL)->orWhere('forwarder.statusbooking', 'partial_booking');
            // })
            ->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' forwarder.*, po.id, po.pono, po.matcontents, po.itemdesc, po.colorcode, po.size, po.qtypo, po.pino, mastersupplier.nama')
            ->groupby('po.pono')
            ->get();

        // dd($mypo);
        $remain = formpo::whereIn('idpo', $idpo)->selectRaw(' id_formpo, idforwarder, qty_booking ')->where('aktif', 'Y')->get();

        LogActivity::addToLog('Web Forwarder :: Forwarder : Process Input Data Approval PO', $this->micro);
        $form = view('system::dashboard.modal_listpo', ['data' => $datapo, 'mypo' => $mypo, 'remaining' => $remain]);
        return $form->render();
    }

    public function getroute(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = route::selectRaw(' id_route, route_code, route_desc');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' (route_desc like "%' . $search . '%") ');
        }

        $po = $po->where('aktif', 'Y')->orderby('route_code', 'asc')->paginate(10, $request->page);
        // dd($po);
        return response()->json($po);
    }

    public function getportloading(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = masterportofloading::selectRaw(' id_portloading, code_port, name_port');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' (name_port like "%' . $search . '%") ');
        }

        $po = $po->where('aktif', 'Y')->paginate(10, $request->page);
        // dd($po);
        return response()->json($po);
    }

    public function getportdestination(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = masterportofdestination::selectRaw(' id_portdestination, code_port, name_port');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' (name_port like "%' . $search . '%") ');
        }

        $po = $po->where('aktif', 'Y')->paginate(10, $request->page);
        // dd($po);
        return response()->json($po);
    }

    public function formupdate(Request $request)
    {
        // dd($request);

        $mydata = formpo::withCount(['shipment as qtyship' => function ($var) {
            $var->select(DB::raw('sum(qty_shipment)'))->groupby('idformpo');
        }])
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
            ->join('privilege', 'privilege.idforwarder', 'forwarder.idmasterfwd')
            ->where('po.id', $request->id)
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')->where('forwarder.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->selectRaw(' formpo.*, po.pono, po.style, po.matcontents, po.colorcode, po.size, po.qtypo, forwarder.qty_allocation, forwarder.statusforwarder')
            ->get();

        // dd($mydata);
        $data = array(
            'dataku' => $mydata,
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Process Input Data Shipment', $this->micro);
        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function formkyc(Request $request)
    {
        // dd($request);
        $datakyc = kyc::where('idmasterfwd', $request->id)->where('aktif', 'Y')->first();

        $data = array(
            'datakyc' => $datakyc
        );

        LogActivity::addToLog('Web Forwarder :: Logistik : Process Approval KYC by Logistik', $this->micro);
        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function formnewfwd(Request $request)
    {
        // dd($request);
        $datanewuser = privilege::join('masterforwarder', 'masterforwarder.id', 'privilege.idforwarder')->where('masterforwarder.aktif', 'Y')->where('privilege_id', $request->id)->first();

        $data = array(
            'datanewuser' => $datanewuser
        );

        LogActivity::addToLog('Web Forwarder :: Logistik : Process Approval New User Forwarder by Logistik', $this->micro);
        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    public function saveformpo(Request $request)
    {
        DB::beginTransaction();

        if ($request->shipmode == 'fcl') {
            $submode = $request->fcl . '-' . $request->fclvol . '-' . $request->fclweight . 'KG';
        } else if ($request->shipmode == 'lcl') {
            $submode = $request->lcl . 'M3' . '-' . $request->lclweight . 'KG';
        } else if ($request->shipmode == 'air') {
            $submode = $request->air . 'M3' . '-' . $request->airweight . 'KG';
        } else {
            $submode = $request->cfscyvol . 'M3' . '-' . $request->cfscyweight . 'KG';
        }

        $cekdoublebooking = forwarder::where('idmasterfwd', $request->dataid[0]['idmasterfwd'])->where('po_nomor', $request->dataid[0]['pono'])->where('statusapproval', 'waiting')->where('aktif', 'Y')->count();
        if ($cekdoublebooking > 0) {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Previous booking data has been submitted to Logistics, Please resubmit after receiving approval for the previous booking'];
            return response()->json($status, 200);
        }

        $val_matcontent = $request->matcontent;
        $val_hscode = $request->hscode;
        foreach ($val_matcontent as $key => $hs) {
            $cekhs = masterhscode::where('matcontent', $hs)->where('aktif', 'Y')->first();

            if ($cekhs) {
                if ($cekhs->hscode != $val_hscode[$key]) {
                    $simpan = masterhscode::where('matcontent', $hs)->update([
                        'hscode'      => $val_hscode[$key],
                        'matcontent'  => $hs,
                        'updated_at'  => date('Y-m-d H:i:s'),
                        'updated_by'  => Session::get('session')['user_nik']
                    ]);
                } else {
                    $simpan = true;
                }
            } else {
                $simpan = masterhscode::insert([
                    'hscode'      => $val_hscode[$key],
                    'matcontent'  => $hs,
                    'aktif'       => 'Y',
                    'created_at'  => date('Y-m-d H:i:s'),
                    'created_by'  => Session::get('session')['user_nik']
                ]);
            }

            if ($simpan) {
                $sukses[] = "OK hscode";
            } else {
                $gagal[] = "gagal hscode";
            }
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
            if ($request->route == '' && $request->route == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Route is required, please input Route'];
                return response()->json($status, 200);
            }
            if ($request->portloading == '' && $request->portloading == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Of Loading is required, please input Port Of Loading'];
                return response()->json($status, 200);
            }
            if ($request->portdestination == '' && $request->portdestination == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Port Of Destination is required, please input Port Of Destination'];
                return response()->json($status, 200);
            }
            if ($request->package == '' && $request->package == null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Package is required, please input Package'];
                return response()->json($status, 200);
            }

            $cekpino = po::where('id', $val['idpo'])
                ->where(function ($var) {
                    $var->where('pino', '=', " ")->orWhere('pino', '=', null);
                })->first();
            if ($cekpino != null) {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Please contact the supplier for the pino input validation process'];
                return response()->json($status, 200);
            }

            $cekformpo = formpo::where('idpo', $val['idpo'])->where('idforwarder', $val['idforwarder'])->where('idmasterfwd', $val['idmasterfwd'])->where('statusformpo', 'reject')->where('aktif', 'Y')->pluck('id_formpo');
            if (count($cekformpo) > 0) {
                $del = formpo::whereIn('id_formpo', $cekformpo)->where('aktif', 'Y')->update([
                    'aktif' => 'N',
                    'updated_at'     => date('Y-m-d H:i:s'),
                    'updated_by'     => Session::get('session')['user_nik']
                ]);
            }

            $cekpo = po::where('id', $val['idpo'])->first();
            $save1 = false;
            if ($cekpo != null) {
                if ($cekpo->statusconfirm != 'waiting') {
                    $save1 = po::where('id', $val['idpo'])->update([
                        'statusconfirm' => 'waiting',
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    $save1 = true;
                }
            }

            $getqtybooking = formpo::where('idpo', $val['idpo'])->where('idforwarder', $val['idforwarder'])->where('idmasterfwd', $val['idmasterfwd'])->where('statusformpo', '!=', 'reject')->selectRaw('sum(qty_booking) as totalbooking ')->where('aktif', 'Y')->first();
            $totbook = $getqtybooking->totalbooking == NULL ? 0 : $getqtybooking->totalbooking;
            $jmlbook = $val['qtybook'] + $totbook;
            if ($cekpo->qtypo == $val['qtybook'] || $cekpo->qtypo == $jmlbook) {
                $statbook = 'full_booking';
            } else {
                $statbook = 'partial_booking';
            }

            $save2 = forwarder::where('id_forwarder', $val['idforwarder'])->update([
                'statusapproval' => 'waiting',
                'statusbooking'  => $statbook,
                'updated_at'     => date('Y-m-d H:i:s'),
                'updated_by'     => Session::get('session')['user_nik']
            ]);

            $save3 = formpo::insert([
                'idpo'              => $val['idpo'],
                'idmasterfwd'       => $val['idmasterfwd'],
                'idforwarder'       => $val['idforwarder'],
                'idroute'           => $request->route,
                'idportloading'     => $request->portloading,
                'idportdestination' => $request->portdestination,
                'status_booking'    => $statbook,
                'qty_booking'       => $val['qtybook'],
                'kode_booking'      => strtoupper($request->nobooking),
                'date_booking'      => $request->datebooking,
                'etd'               => $request->etd,
                'eta'               => $request->eta,
                'shipmode'          => $request->shipmode,
                'subshipmode'       => $submode,
                'package'           => $request->package,
                'statusformpo'      => 'waiting',
                'aktif'             => 'Y',
                'created_at'        => date('Y-m-d H:i:s'),
                'created_by'        => Session::get('session')['user_nik']
            ]);

            $save2 = ($save2 == 1) ? true : $save2;
            if ($save1) {
                $sukses[] = "OK frompo 1";
            } else {
                $gagal[] = "gagal formpo 1";
            }

            if ($save2) {
                $sukses[] = "OK frompo 2";
            } else {
                $gagal[] = "gagal formpo 2";
            }

            if ($save3) {
                $sukses[] = "OK frompo 3";
            } else {
                $gagal[] = "gagal formpo 3";
            }
        }

        if (empty($gagal)) {
            DB::commit();
            LogActivity::addToLog('Web Forwarder :: Forwarder : Insert Data Booking PO by Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Booking'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Booking'];
            return response()->json($status, 200);
        }
    }

    public function saveshipment(Request $request)
    {
        $decode = json_decode($request->dataid);
        // dd($decode, $request);
        // DB::beginTransaction();

        $file = $request->file('file');
        $originalName = str_replace(' ', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . $originalName;
        Storage::disk('local')->put($fileName, file_get_contents($request->file));

        foreach ($decode as $key => $val) {
            if ($file == '' || $file == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'File BL is required, please input File BL'];
                return response()->json($status, 200);
            }

            if ($request->nomorbl == '' || $request->nomorbl == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Nomor BL is required, please input Nomor BL'];
                return response()->json($status, 200);
            }

            if ($request->vessel == '' || $request->vessel == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Vessel is required, please input Vessel'];
                return response()->json($status, 200);
            }

            if ($request->invoice == '' || $request->invoice == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Invoice is required, please input Invoice'];
                return response()->json($status, 200);
            }

            if ($request->etdfix == '' || $request->etdfix == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETD Fix is required, please input ETD Fix'];
                return response()->json($status, 200);
            }

            if ($request->etafix == '' || $request->etafix == null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'ETA Fix is required, please input ETA Fix'];
                return response()->json($status, 200);
            }

            $cekdata = shipment::where('noinv', $request->invoice)->where('aktif', 'Y')->first();
            if ($cekdata != null) {
                // DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Invoice Already Exist'];
                return response()->json($status, 200);
            }

            $cekpo = po::where('id', $val->idpo)->first();
            $rep = str_replace('.', '', $cekpo->qtypo);
            $qtypo = (float)$rep;

            $cekqtyshipment = shipment::where('idformpo', $val->idformpo)->where('aktif', 'Y')->selectRaw(' sum(qty_shipment) as jml ')->first();
            $jumlahexist = ($cekqtyshipment->jml == null) ? 0 : $cekqtyshipment->jml;

            $jumlahall = $val->value + $jumlahexist;
            // dd($jumlahall, $qtypo);

            if ($jumlahall > $qtypo) {
                // DB::rollback();
                $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data Quantity Allocation Over Quantity PO'];
                return response()->json($status, 200);
            }

            if ($jumlahall == $qtypo) {
                $status = 'full_allocated';
            } else {
                $status = 'partial_allocated';
            }

            $save1 = shipment::insert([
                'idformpo'     => $val->idformpo,
                'qty_shipment' => $val->value,
                'noinv'        => strtoupper($request->invoice),
                'etdfix'       => $request->etdfix,
                'etafix'       => $request->etafix,
                'file_bl'      => $fileName,
                'nomor_bl'     => strtoupper($request->nomorbl),
                'vessel'       => strtoupper($request->vessel),
                'statusshipment' => $status,
                'aktif'        => 'Y',
                'created_at'   => date('Y-m-d H:i:s'),
                'created_by'   => Session::get('session')['user_nik']
            ]);

            // $update1 = formpo::where('id_formpo', $value->idformpo)->update([
            //     'statusupdateshipment' => 'has updated',
            //     'updated_at' => date('Y-m-d H:i:s'),
            //     'updated_by' => Session::get('session')['user_nik']
            // ]);

            if ($save1) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        if (empty($gagal)) {
            // DB::commit();
            LogActivity::addToLog('Web Forwarder :: Forwarder : Insert Data Shipment by Forwarder', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            // DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    public function statuskyc(Request $request, $approval)
    {
        // dd($request, $approval);
        if ($approval == 'disetujui') {
            DB::beginTransaction();
            $statusupdate = kyc::where('idmasterfwd', $request->idfwd)->where('aktif', 'Y')->update([
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
                LogActivity::addToLog('Web Forwarder :: Logistik : Status KYC Confirmed by Logistik', $this->micro);
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                DB::rollback();
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        } else {
            $statusupdate = kyc::where('idmasterfwd', $request->idfwd)->where('aktif', 'Y')->update([
                'status' => 'reject',
                'user_approval' => Session::get('session')['user_nik'],
                'ket_tolak' => $request->tolak,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Session::get('session')['user_nik']
            ]);

            if ($statusupdate) {
                LogActivity::addToLog('Web Forwarder :: Logistik : Status KYC Rejected by Logistik', $this->micro);
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        }
    }

    public function statusnewfwd(Request $request, $approval)
    {
        // dd($request, $approval);
        if ($approval == 'disetujui') {
            $statusupdate = privilege::where('privilege_id', $request->idfwd)->update([
                'privilege_aktif' => 'Y',
                'status' => 'confirm',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            if ($statusupdate) {
                LogActivity::addToLog('Web Forwarder :: Logistik : Status KYC Confirmed by Logistik', $this->micro);
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        } else {
            $statusupdate = privilege::where('privilege_id', $request->idfwd)->update([
                'status' => 'reject',
                'ket_tolak' => $request->tolak,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            if ($statusupdate) {
                LogActivity::addToLog('Web Forwarder :: Logistik : Status KYC Rejected by Logistik', $this->micro);
                $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
                return response()->json($status, 200);
            } else {
                $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
                return response()->json($status, 200);
            }
        }
    }
}
