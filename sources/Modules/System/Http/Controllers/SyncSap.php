<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Crypt, DB, Mail;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Modules\System\Models\modelsystem,
    Modules\System\Models\modelfactory,
    Modules\System\Models\Privileges\modelrole_access,
    Modules\System\Models\Privileges\modelgroup_access,
    Modules\System\Models\modellogsap,
    Modules\System\Models\modelpo,
    Modules\System\Models\mastersupplier,
    Modules\System\Models\mastercompany,
    Modules\System\Models\masterplant,
    Modules\System\Models\modelprivilege;

class SyncSap extends Controller
{
    protected $ip_server;
    public function __construct()
    {
        $this->ip_server = config('api.url.ip_address');
    }

    public function logsap($mode, $folder, $file, $activity)
    {
        modellogsap::insert(['tanggal' => date("Y-m-d H:i:s"), 'mode' => $mode, 'folder' => $folder, 'namafile' => $file, 'activity' => $activity]);
    }

    public function index()
    {
        $mode = $_GET['envir'];
        $fname = $_GET['fname'];
        $tgl_input = date("Y-m-d H:i:s");
        $this->logsap($mode, "", $fname, "Start Process...");
        $root = "//192.168.100.111";

        if ($mode == 'prd') {
            // $filesdir = $root."/sapint/PRD/WEBSUPPLIER/PO/PROCESSED/";
            $filesdir = storage_path('public/WEBSUPPLIER/PO/PROCESSED/');
        } else if ($mode == 'dev' or $mode == 'qas') {
            // $filesdir = $root."/sapint/DEV/WEBSUPPLIER/PO/PROCESSED/";
            $filesdir = storage_path('public/WEBSUPPLIER/PO/PROCESSED/');
        } else {
            $this->logsap($mode, "", $fname, "FAILED :: Mode tidak ditemukan...");
            echo "FAILED :: Mode tidak ditemukan";
            return;
        }
        $file = $fname . '.txt';
        $nmfile = $filesdir . $file;

        $this->logsap($mode, $filesdir, $file, "Excecute File :  " . $nmfile);
        if (file_exists($nmfile)) {
            $txt_file = file_get_contents($nmfile);
            $rows = explode("\n", $txt_file);
            DB::beginTransaction();
            foreach ($rows as $key => $data) {

                if ($data != "") {
                    if ($key > 0) {
                        $row_data = preg_split("/[\t]/", $data);
                        $vendor = $row_data[16];
                        //cekvendor
                        $cekv = mastersupplier::where('nama', $vendor)->first();
                        if ($cekv == null) {
                            mastersupplier::where('nama', $vendor)->insert(['nama' => $vendor, 'aktif' => 'Y', 'created_at' => date('Y-m-d H:i:s'), 'created_user' => 'SAP']);
                        } else {
                            mastersupplier::where('nama', $vendor)->update(['aktif' => 'Y', 'updated_at' => date('Y-m-d H:i:s'), 'updated_user' => 'SAP']);
                        }
                        $ven = mastersupplier::where('nama', $vendor)->first();
                        $idvendor = $ven->id;

                        //cekpo
                        $p = modelpo::where('pono', $row_data[0])->first();
                        if ($p == null) {
                            modelpo::insert([
                                'pono' => $row_data[0], 'line_id' => $row_data[1], 'podate' => $row_data[2], 'deldate' => $row_data[3], 'matcontents' => $row_data[4], 'itemdesc' => $row_data[5], 'colorcode' => $row_data[6], 'size' => $row_data[7], 'qtypo' => $row_data[8], 'qtypi' => $row_data[9], 'kpno' => $row_data[10], 'curr' => $row_data[12], 'price' => (float)($row_data[13]), 'vcolor' => $row_data[14], 'pireason' => $row_data[15], 'vendor' => $idvendor, 'pino' => $row_data[18], 'pirecdate' => $row_data[19], 'pideldate' => $row_data[20], 'eta' => $row_data[21], 'etd' => $row_data[22], 'qtyetd' => $row_data[23], 'shipmode' => $row_data[24], 'invno' => $row_data[25], 'awb1' => $row_data[26], 'etd2' => $row_data[27], 'qtyetd2' => $row_data[28], 'shipmode2' => $row_data[29], 'invno2' => $row_data[30], 'awb2' => $row_data[31], 'etd3' => $row_data[32], 'qtyetd3' => $row_data[33], 'shipmode3' => $row_data[34], 'invno3' => $row_data[35], 'awb3' => $row_data[36], 'etd4' => $row_data[37], 'qtyetd4' => $row_data[38], 'shipmode4' => $row_data[39], 'invno4' => $row_data[40], 'awb4' => $row_data[41], 'etd5' => $row_data[42],
                                'qtyetd5' => $row_data[43], 'shipmode5' => $row_data[44], 'invno5' => $row_data[45], 'awb5' => $row_data[46], 'unit' => $row_data[47], 'del_flag' => $row_data[48], 'plant' => $row_data[49], 'payterm' => $row_data[50], 'style' => $row_data[51], 'season' => $row_data[52], 'createby' => $row_data[53], 'company' => $row_data[54], 'netvalue' => (float)($row_data[58]), 'mattype' => $row_data[59], 'discount' => (float)($row_data[60]), 'pricediscount' => (float)($row_data[61]), 'garmentdeldate' => $row_data[62], 'shipseqce' => $row_data[63],
                                'acctass' => $row_data[64], 'itemcat' => $row_data[65], 'reqts' => $row_data[66], 'deloflag' => $row_data[67], 'exchrate' => (float)($row_data[68]), 'buyer' => $row_data[69], 'lco' => $row_data[70], 'lcodate' => $row_data[71], 'rmarkitm' => $row_data[72], 'rmarkhdr' => $row_data[73], 'loadasl' => $row_data[74], 'loadtuj' => $row_data[75], 'shipldtime' => $row_data[76], 'customldtime' => $row_data[77], 'route' => $row_data[78], 'created_at' => date('Y-m-d H:i:s'), 'created_user' => 'SAP'
                            ]);
                        } else {
                            modelpo::where('pono', $row_data[0])->update([
                                'pono' => $row_data[0], 'line_id' => $row_data[1], 'podate' => $row_data[2], 'deldate' => $row_data[3], 'matcontents' => $row_data[4],
                                'itemdesc' => $row_data[5], 'colorcode' => $row_data[6], 'size' => $row_data[7], 'qtypo' => $row_data[8], 'qtypi' => $row_data[9], 'kpno' => $row_data[10], 'curr' => $row_data[12], 'price' => (float)($row_data[13]), 'vcolor' => $row_data[14], 'pireason' => $row_data[15], 'vendor' => $idvendor, 'pino' => $row_data[18], 'pirecdate' => $row_data[19], 'pideldate' => $row_data[20], 'eta' => $row_data[21], 'etd' => $row_data[22], 'qtyetd' => $row_data[23], 'shipmode' => $row_data[24], 'invno' => $row_data[25], 'awb1' => $row_data[26], 'etd2' => $row_data[27], 'qtyetd2' => $row_data[28], 'shipmode2' => $row_data[29], 'invno2' => $row_data[30], 'awb2' => $row_data[31], 'etd3' => $row_data[32], 'qtyetd3' => $row_data[33], 'shipmode3' => $row_data[34], 'invno3' => $row_data[35], 'awb3' => $row_data[36], 'etd4' => $row_data[37], 'qtyetd4' => $row_data[38], 'shipmode4' => $row_data[39], 'invno4' => $row_data[40], 'awb4' => $row_data[41], 'etd5' => $row_data[42], 'qtyetd5' => $row_data[43], 'shipmode5' => $row_data[44], 'invno5' => $row_data[45], 'awb5' => $row_data[46], 'unit' => $row_data[47],
                                'del_flag' => $row_data[48], 'plant' => $row_data[49], 'payterm' => $row_data[50], 'style' => $row_data[51], 'season' => $row_data[52], 'createby' => $row_data[53], 'company' => $row_data[54], 'netvalue' => (float)($row_data[58]), 'mattype' => $row_data[59], 'discount' => (float)($row_data[60]), 'pricediscount' => (float)($row_data[61]), 'garmentdeldate' => $row_data[62], 'shipseqce' => $row_data[63], 'acctass' => $row_data[64], 'itemcat' => $row_data[65], 'reqts' => $row_data[66], 'deloflag' => $row_data[67], 'exchrate' => (float)($row_data[68]),
                                'buyer' => $row_data[69], 'lco' => $row_data[70], 'lcodate' => $row_data[71], 'rmarkitm' => $row_data[72], 'rmarkhdr' => $row_data[73], 'loadasl' => $row_data[74], 'loadtuj' => $row_data[75], 'shipldtime' => $row_data[76], 'customldtime' => $row_data[77], 'route' => $row_data[78], 'created_at' => date('Y-m-d H:i:s'), 'created_user' => 'SAP'
                            ]);
                        }
                    }
                }
            } //end forewach
            DB::commit();
            echo "SUKSES";
        } else {
            $this->logsap($mode, $filesdir, $file, "FAILED :: File NOT FOUND");
            echo "FAILED :: File NOT FOUND";
            return;
        }
    }

    public function index_syncsap()
    {
        $mode = $_GET['envir'];
        $fname = $_GET['fname'];
        $tgl_input = date("Y-m-d H:i:s");
        $this->logsap($mode, "", $fname, "Start Process...");
        $root = "//192.168.11.249";

        if ($mode == '0') {
            // $filesdir = $root."/sapint/PRD/WEBSUPPLIER/PO/PROCESSED/";
            $filesdir = storage_path('public/WEBSUPPLIER/PO/PROCESSED/');
        } else if ($mode == '1') {
            $filesdir = $root . "/Web_Sup/Dev/Archive/";
            // $filesdir = $root."/sapint/DEV/WEBSUPPLIER/PO/PROCESSED/";
            // $filesdir = storage_path('public/WEBSUPPLIER/PO/PROCESSED/');
        } else {
            $this->logsap($mode, "", $fname, "FAILED :: Mode tidak ditemukan...");
            echo "FAILED :: Mode tidak ditemukan";
            return;
        }

        $file = $fname . '.txt';
        $nmfile = $filesdir . $file;

        $this->logsap($mode, $filesdir, $file, "Excecute File :  " . $nmfile);
        if (file_exists($nmfile)) {
            $txt_file = file_get_contents($nmfile);
            $rows = explode("\n", $txt_file);
            DB::beginTransaction();
            foreach ($rows as $key => $data) {

                if ($data != "") {
                    if ($key > 0) {
                        $row_data = preg_split("/[\t]/", $data);
                        $vendor = $row_data[16];
                        //cekvendor
                        $cekv = mastersupplier::where('nama', $vendor)->first();
                        if ($cekv == null) {
                            mastersupplier::where('nama', $vendor)->insert(['nama' => $vendor, 'aktif' => 'Y', 'created_at' => date('Y-m-d H:i:s'), 'created_user' => 'SAP']);
                        } else {
                            mastersupplier::where('nama', $vendor)->update(['aktif' => 'Y', 'updated_at' => date('Y-m-d H:i:s'), 'updated_user' => 'SAP']);
                        }
                        $ven = mastersupplier::where('nama', $vendor)->first();
                        $idvendor = $ven->id;

                        //cekpo
                        $p = modelpo::where('pono', $row_data[0])->first();
                        if ($p == null) {
                            modelpo::insert(['pono' => $row_data[0], 'line_id' => $row_data[1], 'podate' => $row_data[2], 'deldate' => $row_data[3], 'matcontents' => $row_data[4], 'itemdesc' => $row_data[5], 'colorcode' => ($row_data[6] == '?') ? '' : $row_data[6], 'size' => ($row_data[7] == '?') ? '' : $row_data[7], 'qtypo' => (float)($row_data[8]), 'qtypi' => $row_data[9], 'kpno' => $row_data[10], 'curr' => $row_data[12], 'price' => (float)($row_data[13]), 'vcolor' => $row_data[14], 'pireason' => $row_data[15], 'vendor' => $idvendor, 'pino' => $row_data[18], 'pirecdate' => $row_data[19], 'pideldate' => $row_data[20], 'eta' => $row_data[21], 'etd' => $row_data[22], 'qtyetd' => $row_data[23], 'shipmode' => $row_data[24], 'invno' => $row_data[25], 'awb1' => $row_data[26], 'etd2' => $row_data[27], 'qtyetd2' => $row_data[28], 'shipmode2' => $row_data[29], 'invno2' => $row_data[30], 'awb2' => $row_data[31], 'etd3' => $row_data[32], 'qtyetd3' => $row_data[33], 'shipmode3' => $row_data[34], 'invno3' => $row_data[35], 'awb3' => $row_data[36], 'etd4' => $row_data[37], 'qtyetd4' => $row_data[38], 'shipmode4' => $row_data[39], 'invno4' => $row_data[40], 'awb4' => $row_data[41], 'etd5' => $row_data[42], 'qtyetd5' => $row_data[43], 'shipmode5' => $row_data[44], 'invno5' => $row_data[45], 'awb5' => $row_data[46], 'unit' => $row_data[47], 'del_flag' => $row_data[48], 'plant' => $row_data[49], 'payterm' => $row_data[50], 'style' => $row_data[51], 'season' => $row_data[52], 'createby' => $row_data[53], 'company' => $row_data[54], 'netvalue' => (float)($row_data[58]), 'mattype' => $row_data[59], 'discount' => (float)($row_data[60]), 'pricediscount' => (float)($row_data[61]), 'garmentdeldate' => $row_data[62], 'lco' => $row_data[63], 'created_at' => date('Y-m-d H:i:s'), 'created_user' => 'SAP']);
                        } else {
                            modelpo::where('pono', $row_data[0])->update(['pono' => $row_data[0], 'line_id' => $row_data[1], 'podate' => $row_data[2], 'deldate' => $row_data[3], 'matcontents' => $row_data[4], 'itemdesc' => $row_data[5], 'colorcode' => ($row_data[6] == '?') ? '' : $row_data[6], 'size' => ($row_data[7] == '?') ? '' : $row_data[7], 'qtypo' => (float)($row_data[8]), 'qtypi' => $row_data[9], 'kpno' => $row_data[10], 'curr' => $row_data[12], 'price' => (float)($row_data[13]), 'vcolor' => $row_data[14], 'pireason' => $row_data[15], 'vendor' => $idvendor, 'pino' => $row_data[18], 'pirecdate' => $row_data[19], 'pideldate' => $row_data[20], 'eta' => $row_data[21], 'etd' => $row_data[22], 'qtyetd' => $row_data[23], 'shipmode' => $row_data[24], 'invno' => $row_data[25], 'awb1' => $row_data[26], 'etd2' => $row_data[27], 'qtyetd2' => $row_data[28], 'shipmode2' => $row_data[29], 'invno2' => $row_data[30], 'awb2' => $row_data[31], 'etd3' => $row_data[32], 'qtyetd3' => $row_data[33], 'shipmode3' => $row_data[34], 'invno3' => $row_data[35], 'awb3' => $row_data[36], 'etd4' => $row_data[37], 'qtyetd4' => $row_data[38], 'shipmode4' => $row_data[39], 'invno4' => $row_data[40], 'awb4' => $row_data[41], 'etd5' => $row_data[42], 'qtyetd5' => $row_data[43], 'shipmode5' => $row_data[44], 'invno5' => $row_data[45], 'awb5' => $row_data[46], 'unit' => $row_data[47], 'del_flag' => $row_data[48], 'plant' => $row_data[49], 'payterm' => $row_data[50], 'style' => $row_data[51], 'season' => $row_data[52], 'createby' => $row_data[53], 'company' => $row_data[54], 'netvalue' => (float)($row_data[58]), 'mattype' => $row_data[59], 'discount' => (float)($row_data[60]), 'pricediscount' => (float)($row_data[61]), 'garmentdeldate' => $row_data[62], 'lco' => $row_data[63], 'created_at' => date('Y-m-d H:i:s'), 'created_user' => 'SAP']);
                        }
                    }
                }
            } //end forewach
            DB::commit();
            echo "SUKSES";
        } else {
            $this->logsap($mode, $filesdir, $file, "FAILED :: File NOT FOUND");
            echo "FAILED :: File NOT FOUND";
            return;
        }
    }
}
