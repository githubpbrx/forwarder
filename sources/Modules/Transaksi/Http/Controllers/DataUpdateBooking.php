<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;
use Modules\Transaksi\Models\modelpo;
use Modules\Transaksi\Models\modelformpo;
use Modules\System\Models\masterhscode;

class DataUpdateBooking extends Controller
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
            'title' => 'Data Update Booking',
            'menu'  => 'dataupdatebooking',
            'box'   => '',
        );

        \LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Data Update Booking', $this->micro);
        return view('transaksi::dataupdatebooking', $data);
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

                    $button = '<a href="#" data-id="' . $data->kode_booking . '" id="editbtn"><i data-tooltip="tooltip" title="Edit Booking" class="fa fa-edit fa-lg"></i></a>';
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
        }, 'withroute', 'withportloading', 'withportdestination'])
            ->where('formpo.kode_booking', $request->id)
            ->where('formpo.aktif', 'Y')
            ->get();

        $data = [
            'booking' => $getgroup,
        ];

        $form = view('transaksi::modalupdatebooking', ['data' => $data]);
        return $form->render();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    public function updatebooking(Request $request)
    {
        // dd($request);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
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
            $simpan = masterhscode::where('matcontent', $hs)->update([
                'hscode'      => $request->hscode[$key],
                'updated_at'  => date('Y-m-d H:i:s'),
                'updated_by'  => Session::get('session')['user_nik']
            ]);

            if ($simpan) {
                $sukses[] = "OK";
            } else {
                $gagal[] = "OK";
            }
        }

        $updatebooking = modelformpo::where('kode_booking', $request->nobook_old)->where('aktif', 'Y')->update([
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

        if (empty($gagal) && $updatebooking) {
            DB::commit();
            \LogActivity::addToLog('Web Forwarder :: Forwarder : Save Update Booking', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Updated'];
            return response()->json($status, 200);
        } else {
            DB::rollback();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Updated'];
            return response()->json($status, 200);
        }
    }
}
