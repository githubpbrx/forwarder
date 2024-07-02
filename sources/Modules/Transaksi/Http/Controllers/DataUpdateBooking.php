<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Modules\System\Helpers\LogActivity;
use Modules\Transaksi\Models\modelpo;
use Modules\Transaksi\Models\modelformpo;
use Modules\Transaksi\Models\modelforwarder;
use Modules\System\Models\masterhscode;

class DataUpdateBooking extends Controller
{
    protected $micro;
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
            'title' => 'Data Update Booking',
            'menu'  => 'dataupdatebooking',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Data Update Booking', $this->micro);
        return view('transaksi::updatebooking.dataupdatebooking', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listbooking(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if ($request->ajax()) {
            $data = modelformpo::join('po', 'po.id', 'formpo.idpo')
                ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                ->where('formpo.statusformpo', '=', 'waiting')
                ->where('privilege.privilege_aktif', 'Y')->where('formpo.aktif', 'Y')
                ->groupby('formpo.kode_booking')
                ->orderByDesc('formpo.created_at')
                ->get();
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('pono', function ($data) {
                    $mydatapo = modelpo::where('id', $data->idpo)
                        ->select('pono')->groupby('pono')->pluck('pono');
                    // dd($mydatapo);
                    return  str_replace("]", "", str_replace("[", "", str_replace('"', " ", $mydatapo)));
                    // return $data->idpo;
                })
                ->addColumn('kodebook', function ($data) {
                    return $data->kode_booking;
                })
                ->addColumn('action', function ($data) {
                    $idku = $data->pono;
                    $button = '';

                    $button = '<center><a href="#" data-idforwarder="' . $data->idforwarder . '" data-kodebooking="' . $data->kode_booking . '" id="editbtn"><i data-tooltip="tooltip" title="Edit Booking" class="fa fa-edit fa-lg"></i></a></center>';
                    return $button;
                })
                ->make(true);
        }
        // return view('transaksi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function getdatabooking(Request $request)
    {
        // dd($request);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        $getgroup = modelformpo::with(['withpo' => function ($hs) {
            $hs->with(['hscode']);
        }, 'withforwarder', 'withroute', 'withportloading', 'withportdestination'])
            ->where('formpo.kode_booking', $request->kodebook)
            ->where('formpo.aktif', 'Y')
            ->get();

        $getremain = [];
        foreach ($getgroup as $key => $val) {
            $remain = modelformpo::with(['withpo'])
                ->where('formpo.idforwarder', $val->idforwarder)
                ->where('formpo.aktif', 'Y')
                ->selectRaw(' sum(qty_booking) as jmlbook ')
                ->first();

            array_push($getremain, $remain);
        }

        // dd($getremain);
        $data = [
            'booking' => $getgroup,
            'remaining' => $getremain
        ];

        $form = view('transaksi::updatebooking.modalupdatebooking', ['data' => $data]);
        return $form->render();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    public function updatebooking(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        // dd($request);
        try {
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

            foreach ($request->matcontent as $key => $hs) {
                $cekhs = masterhscode::where('matcontent', $hs)->where('aktif', 'Y')->first();

                if ($cekhs) {
                    $simpan = masterhscode::where('matcontent', $hs)->update([
                        'hscode'      => $request->hscode[$key],
                        'matcontent'  => $hs,
                        'updated_at'  => date('Y-m-d H:i:s'),
                        'updated_by'  => Session::get('session')['user_nik']
                    ]);
                } else {
                    $simpan = masterhscode::insert([
                        'hscode'      => $request->hscode[$key],
                        'matcontent'  => $hs,
                        'aktif'       => 'Y',
                        'created_at'  => date('Y-m-d H:i:s'),
                        'created_by'  => Session::get('session')['user_nik']
                    ]);
                }
            }

            foreach ($request->dataid as $key => $val) {
                $qtydefault = ($val['value'] == '') ? 0 : $val['value'];
                $cekpo = modelpo::where('id', $val['idpo'])->first();
                $qtypo = (float)$cekpo->qtypo;

                //mengambil data qty_booking berdasarkan idforwarder
                $qtybooking = modelformpo::where('idforwarder', $val['idforwarder'])->where('statusformpo', 'waiting')->where('aktif', 'Y')->selectRaw(' sum(qty_booking) as jml ')->first();

                //mengambil data qty_booking berdasarkan idforwarder dan kodebooking
                $qtybooking2 = modelformpo::where('idforwarder', $val['idforwarder'])->where('kode_booking', '!=', $val['kodebooking'])->where('statusformpo', 'waiting')->where('aktif', 'Y')->selectRaw(' sum(qty_booking) as jml, status_booking ')->first();
                $qtybookingfix = ($qtybooking2 == NULL) ? 0 : $qtybooking2->jml;

                if ($qtydefault == $qtybooking->jml) {
                    $jumlahall = (int)$qtybooking->jml;
                    $mybook = (int)$qtybooking->jml;
                } elseif ($qtydefault == $qtybookingfix || $qtydefault < $qtybookingfix || $qtydefault > $qtybookingfix) {
                    $jumlahall = (int)$qtydefault + (int)$qtybookingfix;
                    $mybook = (int)$qtydefault;
                } elseif ($qtypo == $qtybooking->jml) {
                    $jumlahall =  (int)$qtydefault + (int)$qtybookingfix;
                    $mybook =  (int)$qtydefault;
                }

                // dd($qtypo, $jumlahall, $mybook, $qtybooking->jml, $qtybooking2->jml);
                if ($jumlahall > $qtypo) {
                    DB::rollback();
                    $status = ['title' => 'Error!', 'status' => 'error', 'message' => 'Data Quantity Booking Over Quantity PO'];
                    return response()->json($status, 200);
                }

                if ($jumlahall == $qtypo) {
                    $status = 'full_booking';
                } else {
                    $status = 'partial_booking';
                }
                // dd($cekbok, $status);

                // dd($status, $jumlahall, $mybook);
                $updatefwd = modelforwarder::where('id_forwarder', $val['idforwarder'])->where('aktif', 'Y')->update([
                    'statusbooking' => $status,
                    'updated_at'    => date('Y-m-d H:i:s'),
                    'updated_by'    => Session::get('session')['user_nik']
                ]);

                // $cekbok = modelformpo::where('idforwarder', $val['idforwarder'])->where('idmasterfwd', $val['idmasterfwd'])->where('statusformpo', 'waiting')->where('aktif', 'Y')->count('id_formpo');
                // if ($cekbok > 1) {
                //     $cekbok2 = modelformpo::where('idforwarder', $val['idforwarder'])->where('idmasterfwd', $val['idmasterfwd'])->where('status_booking', 'partial_booking')->where('statusformpo', 'waiting')->where('kode_booking', $val['kodebooking'])->where('aktif', 'Y')->first();
                //     $status = ($cekbok2 == NULL) ? $status : 'partial_booking';
                // }

                $updatebooking = modelformpo::where('id_formpo', $val['idformpo'])->where('idforwarder', $val['idforwarder'])->where('kode_booking', $request->nobook_old)->where('statusformpo', 'waiting')->where('aktif', 'Y')->update([
                    'status_booking'    => $status,
                    'qty_booking'       => $mybook,
                    'kode_booking'      => $request->nobooking,
                    'date_booking'      => $request->datebooking,
                    'etd'               => $request->etd,
                    'eta'               => $request->eta,
                    'shipmode'          => $request->shipmode,
                    'subshipmode'       => $submode,
                    'package'           => $request->package,
                    'idroute'           => $request->route,
                    'idportloading'     => $request->portloading,
                    'idportdestination' => $request->portdestination,
                    'updated_at'        => date('Y-m-d H:i:s'),
                    'updated_by'        => Session::get('session')['user_nik']
                ]);
            }

            DB::commit();
            LogActivity::addToLog('Web Forwarder :: Forwarder : Save Update Booking', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Updated'];
            return response()->json($status, 200);
        } catch (\Exception $e) {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => $e->getMessage()];
            return response()->json($status, 200);
        }
    }
}
