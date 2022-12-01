<?php

namespace Modules\Transaksi\Http\Controllers\api;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
// use Exception;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Mail;
use Mail;
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
use Modules\Transaksi\Models\modelformshipment as shipment;
use Modules\Transaksi\Models\modelprivilege as privilege;

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

    public static function sendEmail($email, $nama, $link, $subject)
    {
        // dd($email, $nama, $link, $subject);
        try {
            Mail::send('transaksi::layouts/notifpoemail', ['nama' => $nama, 'link' => $link], function ($message) use ($subject, $email) {
                // dd($subject, $email, $message);
                $message->subject($subject);
                $message->to($email);
            });
            return 1;
        } catch (Exception $e) {
            return 0;
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
        $lineid = $req->lineid;
        // $matcontents = $req->matcontents;
        // $colorcode = $req->colorcode;
        // $size = $req->size;
        $pino = $req->pino;
        $pirecdate = $req->pirecdate;
        $pideldate = $req->pideldate;
        $forwarder = $req->forwarder;
        modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '==== START CHECKING Update PI po => ' . $pono . '; lineid => ' . $lineid . '; pino =>' . $pino . '; pirecdate=>' . $pirecdate . '; pideldate=>' . $pideldate, 'status' => true, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
        if ($pono == "") {
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => 'FAILED alert => The PO your send cannot be empty', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== END PROSES => ROLLBACK ===', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "The PO Number your send cannot be empty";
            $failed['success'] = false;
            $failed['title'] = "Warning!";
            $failed['type'] = "warning";
            return response()->json($failed, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($lineid == "") {
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => 'FAILED alert => The Items your send cannot be empty', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== END PROSES => ROLLBACK ===', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "The Line Id your send cannot be empty";
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

        $update = po::where('pono', $pono)->where('line_id', $lineid)->update(['pino' => $pino, 'pirecdate' => $pirecdate, 'pideldate' => $pideldate, 'updated_at' => date('Y-m-d H:i:s')]);
        if ($update) {
            $cekforwarder = forward::where('name', $forwarder)->first();
            if ($cekforwarder == null) {
                forward::insert(['name' => $forwarder, 'aktif' => 'Y', 'created_at' => date('Y-m-d H:i:s')]);
                $forwarderku = forward::latest('id')->first();
                $insert = $forwarderku->id;
            } else {
                $insert = $cekforwarder->id;
            }

            $getqtypo = po::where('pono', $pono)->where('line_id', $lineid)->first();
            $insertdatafwd = fwd::insert(['idpo' => $getqtypo->id, 'idmasterfwd' => $insert, 'po_nomor' => $getqtypo->pono, 'qty_allocation' => $getqtypo->qtypo, 'statusforwarder' => 'full_allocated', 'aktif' => 'Y', 'created_at' => date('Y-m-d H:i:s')]);

            //for notif email
            $getemail = privilege::where('idforwarder', $insert)->where('privilege_aktif', 'Y')->first();
            $url = 'pbrx.web.id/forwarder';
            WebsupplierServices::sendEmail($getemail->privilege_user_nik, $getemail->privilege_user_name, $url, "Notification Forwarder Get PO");

            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== SUCCESS UPDATE PI NUMBER ===', 'status' => true, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "Done";
            $failed['success'] = true;
            $failed['title'] = "Success!";
            $failed['type'] = "success";
            return response()->json($failed, Response::HTTP_OK);
        } else {
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => 'FAILED alert => Data Pi Number failed update', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            modellogproses::insert(['typelog' => 'prosesupdatepi', 'activity' => '=== END PROSES => ROLLBACK ===', 'status' => false, 'datetime' => date('Y-m-d H:i:s'), 'from' => 'api_updatepi', 'created_at' => date('Y-m-d H:i:s')]);
            $failed['message'] = "Failed";
            $failed['success'] = false;
            $failed['title'] = "Warning!";
            $failed['type'] = "warning";
            return response()->json($failed, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        dd($req);
    }

    function lockshipment($inv, $namasup)
    {
        $select = supplier::where('nama', $namasup)->first();
        if ($select == null) {
            return;
        }

        $po = po::where('vendor', $select->id)->pluck('id');

        $form = formpo::wherein('idpo', $po)->pluck('id_formpo');

        $update = shipment::wherein('idformpo', $form)->where('noinv', $inv)->update(['lock' => 1]);
        return;
    }
}
