<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Modules\Report\Models\modelprivilege;
use Modules\Report\Models\modelpo;
use Modules\Report\Models\modelforwarder;
use Modules\Report\Models\modelformshipment;
use Modules\Report\Models\modelformpo;

class ReportForwarder extends Controller
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->micro = microtime(true);
    }

    public function index()
    {
        $data = array(
            'title' => 'Report Forwarder',
            'menu'  => 'reportforwarder',
            'box'   => '',
        );

        \LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Report Forwarder', $this->micro);
        return view('report::reportforwarder', $data);
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            if ($request->po == null) {
                $data = modelformpo::join('po', 'po.id', 'formpo.idpo')
                    ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                    ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                    ->leftjoin('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
                    ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                    ->where('mastersupplier.aktif', 'Y')->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
                    ->where(function ($kk) {
                        $kk->where('formshipment.id_shipment', null)->orWhere('formshipment.aktif', 'Y');
                    })
                    ->groupby('formpo.kode_booking')
                    ->selectRaw('po.id, po.pono, mastersupplier.nama, formpo.kode_booking, formshipment.noinv, formshipment.nomor_bl')
                    ->get();
            } else {
                $data = modelformpo::join('po', 'po.id', 'formpo.idpo')
                    ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                    ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                    ->leftjoin('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
                    ->where('po.pono', $request->po)
                    ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                    ->where('mastersupplier.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
                    ->where(function ($kk) {
                        $kk->where('formshipment.id_shipment', null)->orWhere('formshipment.aktif', 'Y');
                    })
                    ->groupby('formpo.kode_booking')
                    ->selectRaw('po.id, po.pono, mastersupplier.nama, formpo.kode_booking, formshipment.noinv, formshipment.nomor_bl')
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    $mydatapo = modelformpo::join('po', 'po.id', 'formpo.idpo')
                        ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                        ->where('formpo.kode_booking', $data->kode_booking)
                        ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                        ->where('formpo.statusformpo', 'confirm')
                        ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
                        ->selectRaw('po.pono')
                        ->groupby('po.pono')
                        ->pluck('po.pono');

                    return  str_replace("]", "", str_replace("[", "", str_replace('"', " ", $mydatapo)));
                    // return $data->pono;
                })
                ->addColumn('supplier', function ($data) {
                    $mydatapo = modelformpo::join('po', 'po.id', 'formpo.idpo')
                        ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                        ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                        ->where('formpo.kode_booking', $data->kode_booking)
                        ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                        ->where('formpo.statusformpo', 'confirm')->where('mastersupplier.aktif', 'Y')
                        ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
                        ->selectRaw('mastersupplier.nama')
                        ->groupby('po.pono')
                        ->pluck('mastersupplier.nama');

                    return  str_replace("]", "", str_replace("[", "", str_replace('"', " ", $mydatapo)));
                    // return $data->nama;
                })
                ->addColumn('nobook', function ($data) {
                    return $data->kode_booking;
                })
                ->addColumn('invoice', function ($data) {
                    return $data->noinv;
                })
                ->addColumn('nobl', function ($data) {
                    return $data->nomor_bl;
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button .= '<a href="#" data-id="' . $data->kode_booking . '" id="detailpo"><i class="fa fa-info-circle"></i></a>';
                    $button .= '&nbsp';
                    $button .= '<a href="' . url('report/forwarder/getexcelforwarder', ['id' => $data->kode_booking]) . '" data-id="#"><i class="fa fa-file-excel text-success"></i></a>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // return view('transaksi::create');
    }

    public function getpo(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        // $po = modelpo::select('pono');
        $po = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')
            ->select('po.pono');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' po.pono like "%' . $search . '%" ');
        }

        $po = $po->orderby('po.pono', 'asc')->groupby('po.pono')->get();
        // dd($po);
        return response()->json($po);
    }

    public function detailforwarder(Request $request)
    {
        // dd($request);
        $getdata = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->leftjoin('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
            ->where('formpo.kode_booking', $request->id)
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('mastersupplier.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->where('masterhscode.aktif', 'Y')
            ->where(function ($kk) {
                $kk->where('formshipment.id_shipment', null)->orWhere('formshipment.aktif', 'Y');
            })
            ->selectRaw('formshipment.qty_shipment, formshipment.noinv, formshipment.etdfix, formshipment.etafix, formshipment.nomor_bl, formshipment.vessel, po.pono, po.qtypo, po.matcontents, po.itemdesc, po.style, po.colorcode, po.size, formpo.kode_booking, formpo.shipmode, formpo.subshipmode, mastersupplier.nama, masterhscode.hscode')
            ->get();
        $arr = [];
        foreach ($getdata as $key => $val) {
            if ($val->qty_shipment) {
                if (!$arr) {
                    array_push($arr, $val);
                }
            }
        }
        // dd($arr, $getdata);
        $getdate = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->leftjoin('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
            ->where('formpo.kode_booking', $request->id)
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('mastersupplier.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->where(function ($kk) {
                $kk->where('formshipment.id_shipment', null)->orWhere('formshipment.aktif', 'Y');
            })
            ->selectRaw(' formshipment.id_shipment, formshipment.created_at, formshipment.updated_at')
            ->latest('id_shipment')->first();

        $getposup = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formpo.kode_booking', $request->id)
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')->where('mastersupplier.aktif', 'Y')
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->selectRaw(' mastersupplier.nama, po.pono')
            ->groupby('po.pono')
            ->get();

        // dd($getposup);
        $data = array(
            'alldata' => $getdata
        );

        // return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
        $form = view('report::modalreportfwd', ['data' => $getdata, 'mydata' => $arr, 'dateku' => $getdate, 'posup' => $getposup]);
        return $form->render();
    }

    function excelforwarder($id)
    {
        $getdata = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->leftjoin('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.kode_booking', $id)
            ->where('mastersupplier.aktif', 'Y')->where('formpo.aktif', 'Y')
            ->where('privilege.privilege_aktif', 'Y')->where('masterhscode.aktif', 'Y')
            ->where(function ($kk) {
                $kk->where('formshipment.id_shipment', null)->orWhere('formshipment.aktif', 'Y');
            })
            ->selectRaw('formshipment.*, po.pono, po.style, po.qtypo, po.colorcode, po.size, po.qtypo, po.matcontents, po.itemdesc, formpo.kode_booking, formpo.shipmode, formpo.subshipmode, mastersupplier.nama, masterhscode.hscode')
            ->get();
        $arr = [];
        foreach ($getdata as $key => $val) {
            if ($val->qty_shipment) {
                if (!$arr) {
                    array_push($arr, $val);
                }
            }
        }
        // dd($arr[0]);
        $getdate = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->leftjoin('formshipment', 'formshipment.idformpo', 'formpo.id_formpo')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.kode_booking', $id)
            ->where('mastersupplier.aktif', 'Y')->where('formpo.aktif', 'Y')
            ->where('formshipment.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->selectRaw(' formshipment.id_shipment, formshipment.created_at, formshipment.updated_at')
            ->latest('id_shipment')->first();
        // dd($getdate);

        $getposup = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formpo.kode_booking', $id)
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')->where('mastersupplier.aktif', 'Y')
            ->where('formpo.aktif', 'Y')->where('privilege.privilege_aktif', 'Y')
            ->selectRaw(' mastersupplier.nama, po.pono')
            ->groupby('po.pono')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);

        //single header
        $sheet->setCellValue('A4', 'Invoice');
        $sheet->setCellValue('A5', 'Invoice Date');
        $sheet->setCellValue('A6', 'PO');
        $sheet->setCellValue('A7', 'Supplier');
        $sheet->getStyle('A4:A7')->getFont()->setBold(true);
        $sheet->setCellValue('C4', 'Input Data');
        $sheet->setCellValue('C5', 'Update Data');
        $sheet->getStyle('C4:C5')->getFont()->setBold(true);


        //for header
        $cellheader = 'A9:G9';
        $sheet->setCellValue('A9', 'Code Booking');
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->setCellValue('B9', 'BL Number');
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->setCellValue('C9', 'ATD');
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->setCellValue('D9', 'ATA');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E9', 'Shipmode');
        $sheet->getColumnDimension('E')->setWidth(20);
        if ($getdata[0]->shipmode == 'fcl') {
            $sheet->setCellValue('F9', 'Container Size');
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->setCellValue('G9', 'Volume');
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->setCellValue('H9', 'Weight');
            $sheet->getColumnDimension('H')->setWidth(20);
            $sheet->setCellValue('I9', 'Vessel');
            $sheet->getColumnDimension('I')->setWidth(20);
            $sheet->getStyle('A9:I9')->getFont()->setBold(true);
        } else {
            $sheet->setCellValue('F9', 'Volume');
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->setCellValue('G9', 'Weight');
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->setCellValue('H9', 'Vessel');
            $sheet->getColumnDimension('H')->setWidth(20);
            $sheet->getStyle('A9:H9')->getFont()->setBold(true);
        }
        $sheet->getStyle($cellheader)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cellheader)->getFont()->setBold(true);
        $sheet->getStyle($cellheader)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cellheader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellheader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellheader)->getFill()->getStartColor()->setARGB('ff8400');

        //for data
        $celldata = 'A12:G12';
        $sheet->setCellValue('A12', 'Material');
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->setCellValue('B12', 'Material Desc');
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->setCellValue('C12', 'HS Code ');
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->setCellValue('D12', 'Color');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E12', 'Size');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F12', 'Qty PO');
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->setCellValue('G12', 'Qty Ship');
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getStyle($celldata)->getAlignment()->setWrapText(true);
        $sheet->getStyle($celldata)->getFont()->setBold(true);
        $sheet->getStyle($celldata)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($celldata)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($celldata)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($celldata)->getFill()->getStartColor()->setARGB('ff8400');

        $header = 10;
        $bodydata = 13;
        // BORDER STYLE
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $styleArraytitle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        //title
        $sheet->setCellValue('A' . '2', strtoupper('Detail Forwarder'));

        //single header
        $arraypo = [];
        $arraysup = [];
        foreach ($getposup as $key => $lue) {
            array_push($arraypo, $lue->pono);
            array_push($arraysup, $lue->nama);
        }

        $inv = !$arr ? '' : $arr[0]->noinv;
        $dateinv = !$arr ? '' : (($arr[0]->created_at == null) ? '' : date('d F Y H:i:s', strtotime($arr[0]->created_at)));
        $dateinput = !$getdate ? '' : (($getdate->created_at == null) ? '' : date('d F Y H:i:s', strtotime($getdate->created_at)));
        $dateupdate = !$getdate ? '' : (($getdate->updated_at == null) ? '' : date('d F Y H:i:s', strtotime($getdate->updated_at)));
        $sheet->setCellValue('B' . '4', ':' .  $inv);
        $sheet->setCellValue('B' . '5', ':' . $dateinv);
        $sheet->setCellValue('B' . '6', ':' . implode(', ', $arraypo));
        $sheet->setCellValue('B' . '7', ':' . implode(', ', $arraysup));
        $sheet->setCellValue('D' . '4', ':' . $dateinput);
        $sheet->setCellValue('D' . '5', ':' . $dateupdate);


        //header
        $sheet->setCellValue('A' . $header, $getdata[0]->kode_booking);
        $sheet->setCellValue('B' . $header, !$arr ? '' : $arr[0]->nomor_bl);
        $sheet->setCellValue('C' . $header, !$arr ? '' : (($arr[0]->etdfix == null) ? '' : date('d F Y', strtotime($arr[0]->etdfix))));
        $sheet->setCellValue('D' . $header, !$arr ? '' : (($arr[0]->etdfix == null) ? '' : date('d F Y', strtotime($arr[0]->etafix))));
        $sheet->setCellValue('E' . $header, $getdata[0]->shipmode);
        if ($getdata[0]->shipmode == 'fcl') {
            $expfcl = explode('-', $getdata[0]->subshipmode);
            $sheet->setCellValue('F' . $header, $expfcl[0]);
            $sheet->setCellValue('G' . $header, $expfcl[1] . 'M3');
            $sheet->setCellValue('H' . $header, $expfcl[2]);
            $sheet->setCellValue('I' . $header, !$arr ? '' : $arr[0]->vessel);
        } else {
            $exp = explode('-', $getdata[0]->subshipmode);
            $sheet->setCellValue('F' . $header, $exp[0]);
            $sheet->setCellValue('G' . $header, $exp[1]);
            $sheet->setCellValue('H' . $header, !$arr ? '' : $arr[0]->vessel);
        }
        $header++;

        //data
        foreach ($getdata as $key => $value) {
            $sheet->setCellValue('A' . $bodydata, $value->matcontents);
            $sheet->setCellValue('B' . $bodydata, $value->itemdesc);
            $sheet->setCellValue('C' . $bodydata, $value->hscode);
            $sheet->setCellValue('D' . $bodydata, $value->colorcode);
            $sheet->setCellValue('E' . $bodydata, $value->size);
            $sheet->setCellValue('F' . $bodydata, $value->qtypo);
            $sheet->setCellValue('G' . $bodydata, $value->qty_shipment);
            $bodydata++;
        }

        if ($getdata[0]->shipmode == 'fcl') {
            $cellheader = 'A9:I' . ($header - 1);
        } else {
            $cellheader = 'A9:H' . ($header - 1);
        }

        $celldata = 'A12:G' . ($bodydata - 1);
        $sheet->getStyle('A2:G2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cellheader)->applyFromArray($styleArray);
        $sheet->getStyle($celldata)->applyFromArray($styleArray);
        $sheet->getStyle($cellheader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($celldata)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Detail_Forwarder_" . $getdata[0]->kode_booking . ".xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        return;
    }

    function excelforwarderall()
    {
        $getdata = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
            ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')->where('mastersupplier.aktif', 'Y')
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')
            ->groupby('formpo.kode_booking')
            ->get();
        // dd($getdata);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $cell = 'A4:E4';
        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A4', 'PO');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B4', 'Supplier');
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->setCellValue('C4', 'Code Booking');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D4', 'Invoice');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E4', 'Nomor BL');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle('A2:E2')->getFont()->setBold(true);
        $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cell)->getFill()->getStartColor()->setARGB('ff8400');

        $rows = 5;
        // BORDER STYLE
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $styleArraytitle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->setCellValue('A' . '2', strtoupper('Data Report Forwarder'));
        foreach ($getdata as $key => $value) {
            $sheet->setCellValue('A' . $rows, $value->pono);
            $sheet->setCellValue('B' . $rows, $value->nama);
            $sheet->setCellValue('C' . $rows, $value->kode_booking);
            $sheet->setCellValue('D' . $rows, $value->noinv);
            $sheet->setCellValue('E' . $rows, $value->nomor_bl);
            $rows++;
        }

        $cell = 'A4:E' . ($rows - 1);
        $sheet->getStyle('A2:E2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Data_Report_Forwarder.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        return;
    }
}
