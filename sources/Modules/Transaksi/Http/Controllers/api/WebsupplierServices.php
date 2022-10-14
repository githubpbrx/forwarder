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
use Modules\System\Models\modellogproses;
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

    function shipping(Request $post)
    {
        $auth = $this->authorization($post);
        if (isset($auth['failed'])) {
            return response()->json($auth, Response::HTTP_UNAUTHORIZED);
        }

        $noinv = $post->noinv;
        $sup = $post->supplier;
        // dd($noinv);
        modellogproses::insert(['typelog' => 'api', 'activity' => '==== START CHECKING get data Shipping, inv no => ' . $noinv . ' supplier => ' . $sup, 'status' => true, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_shipping', 'created_at' => date('Y-m-d H:i:s')]);
        $data = formpo::where('noinv', $noinv)->where('statusformpo', 'confirm')->where('aktif', 'Y')->get();
        $datasup = supplier::where('nama', $sup)->where('aktif', 'Y')->first();
        $datapo = array();

        if (count($data) == 0 || $datasup == null) {
            modellogproses::insert(['typelog' => 'api', 'activity' => 'failed alert : Data Not found', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_shipping', 'created_at' => date('Y-m-d H:i:s')]);
            $datasend['message'] = 'Data Not Found';
            $datasend['success'] = false;
            $datasend['title'] = "WARNING!";
            $datasend['type'] = "error";
            return response()->json($datasend, Response::HTTP_NOT_FOUND);
        }

        foreach ($data as $key => $r) {
            $po = po::where('id', $r->idpo)->where('vendor', $datasup->id)->first();
            if ($po == null) {
                $lp = [];
            } else {
                $lp['pono'] = $po->pono;
                $lp['matcontents'] = $po->matcontents;
                $lp['colorcode'] = $po->colorcode;
                $lp['size'] = $po->size;

                // $sup = supplier::where('id', $po->vendor)->where('aktif', 'Y')->first();
                // $lp['supplier'] = $r->noinv . '_' . $sup->nama;

                $fw = forward::where('id', $r->idmasterfwd)->first();
                $lp['forwardername'] = $fw->name;

                $all = fwd::where('id_forwarder', $r->idforwarder)->first();

                $lp['qtyallocation'] = $all->qty_allocation;
                $lp['statusallocation'] = $all->statusforwarder;

                $lp['kodebooking'] = $r->kode_booking;
                $lp['datebooking'] = $r->date_booking;
                $lp['estimasietd'] = $r->etd;
                $lp['estimasieta'] = $r->eta;
                $lp['shipmode'] = $r->shipmode;
                $lp['subshipmode'] = $r->subshipmode;
                $lp['etd'] = $r->etdfix;
                $lp['eta'] = $r->etafix;
                $lp['nomor_bl'] = $r->nomor_bl;
                $lp['vessel'] = $r->vessel;
                $sys = modelsystem::first();
                $url = $sys->url . 'sources/storage/app/' . $r->file_bl;
                $lp['file_bl'] = $url;

                modellogproses::insert(['typelog' => 'api', 'activity' => json_encode($lp), 'status' => true, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_shipping', 'created_at' => date('Y-m-d H:i:s')]);
                array_push($datapo, $lp);
                unset($lp);
            }
        }

        // dd($datapo);
        if (count($datapo) == 0) {
            modellogproses::insert(['typelog' => 'api', 'activity' => 'failed alert : Data Not found (array null)', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_shipping', 'created_at' => date('Y-m-d H:i:s')]);
            $datasend['message'] = 'Data Not Found';
            $datasend['success'] = false;
            $datasend['title'] = "WARNING!";
            $datasend['type'] = "error";
            return response()->json($datasend, Response::HTTP_NOT_FOUND);
        } else {
            modellogproses::insert(['typelog' => 'api', 'activity' => '=== SUCCESSS ===', 'status' => true, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_shipping', 'created_at' => date('Y-m-d H:i:s')]);
            $datasend['message'] = 'Data Found';
            $datasend['success'] = true;
            $datasend['title'] = "SUCCESS!";
            $datasend['type'] = "success";
            $datasend['data'] = $datapo;
            return response()->json($datasend, Response::HTTP_OK);
        }
    }



    public function updatepi(Request $req)
    {
        // $2y$10$gpwr15S9I67MHEx0gCD0jeIYovjwl6ymv7zfu4QaaZjVEufbXItl6
        $auth = $this->authorization($req);
        if (isset($auth['failed'])) {
            return response()->json($auth, Response::HTTP_UNAUTHORIZED);
        }

        $pono = $req->pono;
        $matcontents = $req->matcontents;
        $colorcode = $req->colorcode;
        $size = $req->size;
        $pino = $req->pino;
        $pirecdate = $req->pirecdate;
        $pideldate = $req->pideldate;
        $forwarder = $req->forwarder;
        modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '==== START CHECKING Update PI po => ' . $pono . '; matcontents => ' . $matcontents . '; colorcode=>' . $colorcode . '; size=>' . $size . '; pino =>' . $pino . '; pirecdate=>' . $pirecdate . '; pideldate=>' . $pideldate, 'status' => true, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
        if ($pono == "") {
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => 'FAILED alert => The PO your send cannot be empty', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== END PROSES => ROLLBACK ===', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "The PO your send cannot be empty";
            $failed['success'] = false;
            $failed['title'] = "Warning!";
            $failed['type'] = "warning";
            return response()->json($failed, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($matcontents == "") {
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => 'FAILED alert => The Items your send cannot be empty', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== END PROSES => ROLLBACK ===', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "The Items your send cannot be empty";
            $failed['success'] = false;
            $failed['title'] = "Warning!";
            $failed['type'] = "warning";
            return response()->json($failed, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($pino == "") {
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => 'FAILED alert => The PI Number your send cannot be empty', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== END PROSES => ROLLBACK ===', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "The PI Number your send cannot be empty";
            $failed['success'] = false;
            $failed['title'] = "Warning!";
            $failed['type'] = "warning";
            return response()->json($failed, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if ($pirecdate == "" || $pideldate == "") {
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => 'FAILED alert => The PI Rec Date/PI Delivery Date your send cannot be empty', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== END PROSES => ROLLBACK ===', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "The PI Rec Date/PI Delivery Date your send cannot be empty";
            $failed['success'] = false;
            $failed['title'] = "Warning!";
            $failed['type'] = "warning";
            return response()->json($failed, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $update = po::where('pono', $pono)->where('matcontents', $matcontents)->where('colorcode', $colorcode)->where('size', $size)->update(['pino' => $pino, 'pirecdate' => $pirecdate, 'pideldate' => $pideldate]);
        if ($update) {
            $cekforwarder = forward::where('name', $forwarder)->first();
            if ($cekforwarder == null) {
                forward::insert(['name' => $forwarder, 'aktif' => 'Y', 'created_at' => date('Y-m-d H:i:s')]);
                $forwarderku = forward::latest('id')->first();
                $insert = $forwarderku->id;
            } else {
                $insert = $cekforwarder->id;
            }

            $getqtypo = po::where('pono', $pono)->where('matcontents', $matcontents)->where('colorcode', $colorcode)->where('size', $size)->first();
            $insertdatafwd = fwd::insert(['idpo' => $getqtypo->id, 'idmasterfwd' => $insert, 'po_nomor' => $getqtypo->pono, 'qty_allocation' => $getqtypo->qtypo, 'statusforwarder' => 'full_allocated', 'aktif' => 'Y', 'created_at' => date('Y-m-d H:i:s')]);

            // dd($getqtypo);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== SUCCESS UPDATE PI NUMBER ===', 'status' => true, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "Data Pi Number Successfully Updated";
            $failed['success'] = true;
            $failed['title'] = "Success!";
            $failed['type'] = "success";
            return response()->json($failed, Response::HTTP_OK);
        } else {
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => 'FAILED alert => Data Pi Number failed update', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== END PROSES => ROLLBACK ===', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "Data Pi Number failed update";
            $failed['success'] = false;
            $failed['title'] = "Warning!";
            $failed['type'] = "warning";
            return response()->json($failed, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        dd($req);
    }
}
