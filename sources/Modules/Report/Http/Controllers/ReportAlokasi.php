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
use Modules\Report\Models\modelformpo;
use Modules\Report\Models\modelformshipment;

class ReportAlokasi extends Controller
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
            'title' => 'Report Allocation',
            'menu'  => 'reportallocation',
            'box'   => '',
        );

        \LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Report Allocation', $this->micro);
        return view('report::reportalokasi', $data);
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            // dd($request);

            if ($request->po == null) {
                $data = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
                    ->join('po', 'po.id', 'formpo.idpo')
                    ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                    ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('formshipment.aktif', 'Y')
                    ->selectRaw(' formshipment.*, po.id, po.pono, po.matcontents, po.qtypo, po.statusalokasi, po.statusconfirm, masterforwarder.name ')
                    ->get();
            } else {
                $data = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
                    ->join('po', 'po.id', 'formpo.idpo')
                    ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                    ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('formshipment.aktif', 'Y')
                    ->where('po.pono', $request->po)
                    ->selectRaw(' formshipment.*, po.id, po.pono, po.matcontents, po.qtypo, po.statusalokasi, po.statusconfirm, masterforwarder.name ')
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('material', function ($data) {
                    return $data->matcontents;
                })
                ->addColumn('qtypo', function ($data) {
                    return $data->qtypo;
                })
                ->addColumn('qtyallocation', function ($data) {
                    return $data->qty_shipment;
                })
                ->addColumn('invoice', function ($data) {
                    return $data->noinv;
                })
                ->addColumn('forwarder', function ($data) {
                    return $data->name;
                })
                // ->addColumn('statusallocation', function ($data) {
                //     if ($data->statusalokasi == 'full_allocated') {
                //         $statuspo = 'Full Allocated';
                //     } elseif ($data->statusalokasi == 'partial_allocated') {
                //         $statuspo = 'Partial Allocation';
                //     } else {
                //         $statuspo = 'Waiting';
                //     }

                //     return $statuspo;
                // })
                // ->addColumn('statusconfirm', function ($data) {
                //     if ($data->statusconfirm == 'confirm') {
                //         $status = 'Confirmed';
                //     } elseif ($data->statusconfirm == 'reject') {
                //         $status = 'Rejected';
                //     } else {
                //         $status = 'Unprocessed';
                //     }

                //     return $status;
                // })
                ->addColumn('action', function ($data) {
                    $process    = '';

                    $process    .= '<a href="#" data-id="' . $data->id_shipment . '" id="detailalokasi"><i class="fa fa-info-circle"></i></a>';
                    $process    .= '&nbsp';
                    $process    .= '<a href="' . url('report/alokasi/getexcelalokasi', ['id' => $data->id_shipment]) . '"><i class="fa fa-file-excel text-success"></i></a>';

                    return $process;
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
        $po = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('formshipment.aktif', 'Y')
            ->selectRaw(' po.pono ');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' po.pono like "%' . $search . '%" ');
        }

        $po = $po->orderby('po.pono', 'asc')->groupby('po.pono')->paginate(10, $request->page);

        return response()->json($po);
    }

    function detailalokasi(Request $request)
    {
        // dd($request);

        $data = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formshipment.id_shipment', $request->id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formshipment.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.style, po.plant, formpo.*, masterforwarder.name, mastersupplier.nama ')
            ->first();

        // dd($data);
        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    function excelalokasi($id)
    {
        $getdata = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formshipment.id_shipment', $id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formshipment.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.style, po.plant, formpo.*, masterforwarder.name, mastersupplier.nama ')
            ->first();
        // dd($getdata);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2:H2')->getFont()->setBold(true);

        //for supplier
        $cellsupplier = 'A5:H5';
        $sheet->setCellValue('A4', 'SUPPLIER');
        $sheet->setCellValue('A5', 'PO');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B5', 'Supplier');
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->setCellValue('C5', 'Material');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D5', 'Material Desc');
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->setCellValue('E5', 'Style');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F5', 'Quantity Item');
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->setCellValue('G5', 'Quantity Allocation');
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->setCellValue('H5', 'Plant');
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getStyle($cellsupplier)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cellsupplier)->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle($cellsupplier)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cellsupplier)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellsupplier)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellsupplier)->getFill()->getStartColor()->setARGB('ff8400');

        //for forwarder
        $cellforwarder = 'A9:G9';
        $sheet->setCellValue('A8', 'FORWARDER');
        $sheet->setCellValue('A9', 'Code Booking');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B9', 'Forwarder');
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->setCellValue('C9', 'Invoice');
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->setCellValue('D9', 'ETD');
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->setCellValue('E9', 'ETA');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F9', 'Shipmode');
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->setCellValue('G9', 'Sub Shipmode');
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getStyle($cellforwarder)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cellforwarder)->getFont()->setBold(true);
        $sheet->getStyle('A8')->getFont()->setBold(true);
        $sheet->getStyle($cellforwarder)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cellforwarder)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellforwarder)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellforwarder)->getFill()->getStartColor()->setARGB('ff8400');

        //for shipment
        $cellshipment = 'A13:B13';
        $sheet->setCellValue('A12', 'SHIPMENT');
        $sheet->setCellValue('A13', 'BL Number');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B13', 'Vessel');
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getStyle($cellshipment)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cellshipment)->getFont()->setBold(true);
        $sheet->getStyle('A12')->getFont()->setBold(true);
        $sheet->getStyle($cellshipment)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cellshipment)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellshipment)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellshipment)->getFill()->getStartColor()->setARGB('ff8400');

        $rows_sup = 6;
        $rows_fwd = 10;
        $rows_ship = 14;
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
        $sheet->setCellValue('A' . '2', strtoupper('Detail Allocation'));

        //supplier
        $sheet->setCellValue('A' . $rows_sup, $getdata->pono);
        $sheet->setCellValue('B' . $rows_sup, $getdata->nama);
        $sheet->setCellValue('C' . $rows_sup, $getdata->matcontents);
        $sheet->setCellValue('D' . $rows_sup, $getdata->itemdesc);
        $sheet->setCellValue('E' . $rows_sup, $getdata->style);
        $sheet->setCellValue('F' . $rows_sup, $getdata->qtypo);
        $sheet->setCellValue('G' . $rows_sup, $getdata->qty_shipment);
        $sheet->setCellValue('H' . $rows_sup, $getdata->plant);
        $rows_sup++;

        //forwarder
        $sheet->setCellValue('A' . $rows_fwd, $getdata->kode_booking);
        $sheet->setCellValue('B' . $rows_fwd, $getdata->name);
        $sheet->setCellValue('C' . $rows_fwd, $getdata->noinv);
        $sheet->setCellValue('D' . $rows_fwd, $getdata->etdfix);
        $sheet->setCellValue('E' . $rows_fwd, $getdata->etafix);
        $sheet->setCellValue('F' . $rows_fwd, $getdata->shipmode);
        $sheet->setCellValue('G' . $rows_fwd, $getdata->subshipmode);
        $rows_fwd++;

        //forwarder
        $sheet->setCellValue('A' . $rows_ship, $getdata->nomor_bl);
        $sheet->setCellValue('B' . $rows_ship, $getdata->vessel);
        $rows_ship++;

        $cellsupplier = 'A5:H' . ($rows_sup - 1);
        $cellforwarder = 'A9:G' . ($rows_fwd - 1);
        $cellshipment = 'A13:B' . ($rows_ship - 1);
        $sheet->getStyle('A2:H2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cellsupplier)->applyFromArray($styleArray);
        $sheet->getStyle($cellforwarder)->applyFromArray($styleArray);
        $sheet->getStyle($cellshipment)->applyFromArray($styleArray);
        $sheet->getStyle($cellsupplier)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellforwarder)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellshipment)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Report_Allocation_" . $getdata->pono . ".xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        return;
    }

    function excelalokasiall()
    {
        $getdata = modelpo::join('forwarder', 'forwarder.idpo', 'po.id')
            ->join('formpo', 'formpo.idforwarder', 'forwarder.id_forwarder')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->where('forwarder.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')
            ->selectRaw(' po.id, po.pono, po.qtypo, po.statusalokasi, po.statusconfirm, forwarder.qty_allocation, formpo.noinv, masterforwarder.name ')
            ->get();
        // dd($getdata);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $cell = 'A4:G4';
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A4', 'PO');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B4', 'Quantity PO');
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->setCellValue('C4', 'Quantity Allocation');
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D4', 'Invoice');
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->setCellValue('E4', 'Forwarder');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F4', 'Status Allocation');
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->setCellValue('G4', 'Status Confirm');
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);
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
        ];

        $styleArraytitle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->setCellValue('A' . '2', strtoupper('Data Allocation'));

        foreach ($getdata as $key => $val) {
            $sheet->setCellValue('A' . $rows, $val->pono);
            $sheet->setCellValue('B' . $rows, $val->qtypo);
            $sheet->setCellValue('C' . $rows, $val->qty_allocation);
            $sheet->setCellValue('D' . $rows, $val->noinv);
            $sheet->setCellValue('E' . $rows, $val->name);
            $sheet->setCellValue('F' . $rows, $val->statusalokasi);
            $sheet->setCellValue('G' . $rows, $val->statusconfirm);
            $rows++;
        }

        $cell = 'A4:G' . ($rows - 1);
        $sheet->getStyle('A2:G2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Report_Allocation_All.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        return;
    }
}
