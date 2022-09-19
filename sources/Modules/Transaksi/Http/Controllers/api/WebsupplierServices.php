<?php

namespace Modules\Transaksi\Http\Controllers\api;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Modules\Selfservice\Http\Controllers\CutiController;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Modules\System\Models\modelsystem;
use Modules\Transaksi\Models\mastersupplier as supplier;
use Modules\Transaksi\Models\masterforwarder as forward;
use Modules\Transaksi\Models\modelpo as po;
use Modules\Transaksi\Models\modelforwarder as fwd;
use Modules\Transaksi\Models\modelformpo as formpo;

class WebsupplierServices extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $param = modelsystem::first();
        $this->token = 'bismillahsemangatbekerja';
        $this->baseurl = $param->url;
    }

    function authorization($req)
    {
        $bearer = $req->header('Bearer');
        $response['failed'] =  true;

        if ($bearer) {
            if (!Hash::check($this->token, $bearer)) {
                $response['message'] = 'TOKEN INVALID';
                $response['title'] = "WARNING!";
                $response['type'] = "error";
                $response['success'] = false;
                return $response;
            }
        } else {
            $response['message'] = 'TOKEN INVALID';
            $response['title'] = "WARNING!";
            $response['type'] = "error";
            $response['success'] = false;
            return $response;
        }
    }

    function shipping(Request $req){
        $auth = $this->authorization($req);
        if (isset($auth['failed'])) {
            return response()->json($auth, Response::HTTP_UNAUTHORIZED);
        }

        $po = $req->pono;

        $data = po::where('pono',$po)->get();
        $datapo = array();
        foreach($data as $key => $r){
            $lp['pono'] = $r->pono;
            $lp['podate'] = $r->podate;
            $lp['matcontents'] = $r->matcontents;
            $lp['item'] = $r->itemdesc;
            $lp['style'] = $r->style;
            $lp['color'] = $r->colorcode;
            $lp['size'] = $r->size;
            $lp['kpno'] = $r->kpno;
            $lp['qtypo'] = $r->qtypo;

            $fwd = fwd::with('masterforwarder')->where('idpo',$r->id)->where('aktif','Y')->get();
            $arfwd = array();
            foreach($fwd as $key => $rf){
                $af['qtyallocation'] = $rf->qty_allocation;
                $af['forwarder'] = ($rf->masterforwarder==null) ? '' : $rf->masterforwarder->name;

                //cekfwe
                $frm = formpo::where('idforwarder',$rf->id_forwarder)->first();
                if($frm==null){
                    $af['status'] = "";
                    $af['bookingcode'] = "";
                    $af['bookingdate'] = "";
                    $af['etd'] = "";
                    $af['eta'] = "";
                    $af['shipmode'] = "";
                    $af['shipdetail'] = "";
                    $af['file_bl'] = "";
                    $af['nomor_bl'] = "";
                    $af['vessel'] = "";
                }else{
                    $af['status'] = $frm->status;
                    $af['bookingcode'] = $frm->kode_booking;
                    $af['bookingdate'] = $frm->kode_booking;
                    $af['etd'] = $frm->etd;
                    $af['eta'] = $frm->eta;
                    $af['shipmode'] = $frm->shipmode;
                    $af['shipdetail'] = $frm->subshipmode;
                    if($frm->file_bl!=""){
                        $urlku = $this->baseurl.'sources/storage/app'.$frm->file_bl;
                    }else{
                        $urlku = '';
                    }
                    $af['file_bl'] = $urlku;
                    $af['nomor_bl'] = $frm->nomor_bl;
                    $af['vessel'] = $frm->vessel;
                }
                array_push($arfwd, $af);
                unset($af);
            }
            $lp['forwarder'] = $arfwd;
            array_push($datapo,$lp);
            unset($lp);
        }


        if(count($datapo)==0){
            $datasend['message'] = 'Data Not Found';
            $datasend['success'] = false;
            $datasend['title'] = "WARNING!";
            $datasend['type'] = "error";
            return response()->json($datasend, Response::HTTP_NOT_FOUND);
        }else{
            $datasend['message'] = 'Data Found';
            $datasend['success'] = true;
            $datasend['title'] = "SUCCESS!";
            $datasend['type'] = "success";
            $datasend['data'] = $datapo;
            return response()->json($datasend, Response::HTTP_OK);
        }

        dd($datapo, Hash::make('bismillahsemangatbekerja'));
    }

    function shippingconfirm(Request $req){
        $auth = $this->authorization($req);
        if (isset($auth['failed'])) {
            return response()->json($auth, Response::HTTP_UNAUTHORIZED);
        }

        $po = $req->pono;

        $dataid = po::where('pono',$po)->pluck('id');


        $fwd = formpo::wherein('idpo',$dataid)->where('status','confirm')->get();
        dd($dataid, $fwd);



        $datapo = array();
        foreach($data as $key => $r){
            $lp['pono'] = $r->pono;
            $lp['podate'] = $r->podate;
            $lp['matcontents'] = $r->matcontents;
            $lp['item'] = $r->itemdesc;
            $lp['style'] = $r->style;
            $lp['color'] = $r->colorcode;
            $lp['size'] = $r->size;
            $lp['kpno'] = $r->kpno;
            $lp['qtypo'] = $r->qtypo;

            $fwd = fwd::with('masterforwarder')->join('formpo as a', 'a.idforwarder','forwarder.forwarder_id')->where('forwarder.idpo',$r->id)->where('forwarder.aktif','Y')->get();
            $arfwd = array();
            foreach($fwd as $key => $rf){
                $af['qtyallocation'] = $rf->qty_allocation;
                $af['forwarder'] = ($rf->masterforwarder==null) ? '' : $rf->masterforwarder->name;

                //cekfwe
                $frm = formpo::where('idforwarder',$rf->id_forwarder)->first();
                if($frm==null){
                    $af['status'] = "";
                    $af['bookingcode'] = "";
                    $af['bookingdate'] = "";
                    $af['etd'] = "";
                    $af['eta'] = "";
                    $af['shipmode'] = "";
                    $af['shipdetail'] = "";
                    $af['file_bl'] = "";
                    $af['nomor_bl'] = "";
                    $af['vessel'] = "";
                }else{
                    $af['status'] = $frm->status;
                    $af['bookingcode'] = $frm->kode_booking;
                    $af['bookingdate'] = $frm->kode_booking;
                    $af['etd'] = $frm->etd;
                    $af['eta'] = $frm->eta;
                    $af['shipmode'] = $frm->shipmode;
                    $af['shipdetail'] = $frm->subshipmode;
                    if($frm->file_bl!=""){
                        $urlku = $this->baseurl.'sources/storage/app'.$frm->file_bl;
                    }else{
                        $urlku = '';
                    }
                    $af['file_bl'] = $urlku;
                    $af['nomor_bl'] = $frm->nomor_bl;
                    $af['vessel'] = $frm->vessel;
                }
                array_push($arfwd, $af);
                unset($af);
            }
            $lp['forwarder'] = $arfwd;
            array_push($datapo,$lp);
            unset($lp);
        }


        if(count($datapo)==0){
            $datasend['message'] = 'Data Not Found';
            $datasend['success'] = false;
            $datasend['title'] = "WARNING!";
            $datasend['type'] = "error";
            return response()->json($datasend, Response::HTTP_NOT_FOUND);
        }else{
            $datasend['message'] = 'Data Found';
            $datasend['success'] = true;
            $datasend['title'] = "SUCCESS!";
            $datasend['type'] = "success";
            $datasend['data'] = $datapo;
            return response()->json($datasend, Response::HTTP_OK);
        }

        dd($datapo, Hash::make('bismillahsemangatbekerja'));
    }
}