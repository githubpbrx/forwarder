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
use Modules\Report\Models\modelformpo;

class ReportForwarder extends Controller
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
    }

    public function index()
    {
        $data = array(
            'title' => 'Report Forwarder',
            'menu'  => 'reportforwarder',
            'box'   => '',
        );

        return view('report::reportforwarder', $data);
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            if ($request->po == null) {
                $data = modelformpo::join('po', 'po.id', 'formpo.idpo')
                    ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
                    ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                    ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                    ->where('formpo.statusformpo', 'confirm')
                    ->where('formpo.aktif', 'Y')
                    ->get();
            } else {
                // $data = modelpo::where('pono', $request->po)->get();
                $data = modelformpo::join('po', 'po.id', 'formpo.idpo')
                    ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
                    ->join('privilege', 'privilege.idforwarder', 'formpo.idmasterfwd')
                    ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
                    ->where('formpo.statusformpo', 'confirm')
                    ->where('formpo.aktif', 'Y')
                    ->where('po.pono', $request->po)
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('nobook', function ($data) {
                    return $data->kode_booking;
                })
                ->addColumn('material', function ($data) {
                    return $data->matcontents;
                })
                ->addColumn('qtyall', function ($data) {
                    return $data->qty_allocation;
                })
                ->addColumn('statusallocation', function ($data) {
                    if ($data->statusforwarder == 'full_allocated') {
                        $statuspo = 'Full Allocated';
                    } elseif ($data->statusforwarder == 'partial_allocated') {
                        $statuspo = 'Partial Allocation';
                    } else {
                        $statuspo = 'Waiting';
                    }

                    return $statuspo;
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button .= '<a href="#" data-id="' . $data->id_formpo . '" id="detailpo"><i class="fa fa-info-circle"></i></a>';
                    $button .= '&nbsp';
                    $button .= '<a href="' . url('report/forwarder/getexcelforwarder', ['id' => $data->id_formpo]) . '" data-id="#"><i class="fa fa-file-excel text-success"></i></a>';

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
        $po = modelpo::select('pono');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' pono like "%' . $search . '%" ');
        }

        $po = $po->orderby('pono', 'asc')->groupby('pono')->get();

        return response()->json($po);
    }

    public function detailforwarder(Request $request)
    {
        // dd($request);
        // $dataformpo = modelformpo::where('id_formpo', $request->id)->where('aktif', 'Y')->first();
        // $datapo = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
        //     ->where('po.id', $dataformpo->idpo)
        //     ->selectRaw(' po.*, mastersupplier.nama')
        //     ->first();
        // $dataforwarder = modelforwarder::where('id_forwarder', $dataformpo->idforwarder)->where('aktif', 'Y')->first();
        // dd($dataforwarder);
        $getdata = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formpo.id_formpo', $request->id)
            ->where('formpo.aktif', 'Y')->where('forwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formpo.kode_booking, formpo.noinv, formpo.etdfix, formpo.etafix, formpo.shipmode, formpo.subshipmode, formpo.nomor_bl, formpo.vessel, forwarder.qty_allocation, po.pono, po.matcontents, po.qtypo, mastersupplier.nama ')
            ->first();
        // dd($getdata);
        $data = array(
            // 'dataformpo' => $dataformpo,
            // 'datapo' => $datapo,
            // 'dataforwarder' => $dataforwarder
            'alldata' => $getdata
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    function excelforwarder($id)
    {
        $getdata = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formpo.id_formpo', $id)
            ->where('formpo.aktif', 'Y')
            ->where('formpo.statusformpo', 'confirm')
            ->selectRaw(' formpo.kode_booking, formpo.noinv, formpo.etdfix, formpo.etafix, formpo.shipmode, formpo.subshipmode, formpo.nomor_bl, formpo.vessel, forwarder.qty_allocation, po.pono, po.matcontents, po.qtypo, mastersupplier.nama ')
            ->first();
        // dd($getdata);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2:F2')->getFont()->setBold(true);

        //for supplier
        $cellsupplier = 'A5:E5';
        $sheet->setCellValue('A4', 'SUPPLIER');
        $sheet->setCellValue('A5', 'PO');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B5', 'Supplier');
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->setCellValue('C5', 'Material');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D5', 'Quantity PO');
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->setCellValue('E5', 'Quantity Allocation');
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getStyle($cellsupplier)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cellsupplier)->getFont()->setBold(true);
        $sheet->getStyle('A4')->getFont()->setBold(true);
        $sheet->getStyle($cellsupplier)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cellsupplier)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellsupplier)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellsupplier)->getFill()->getStartColor()->setARGB('ff8400');

        //for forwarder
        $cellforwarder = 'A9:F9';
        $sheet->setCellValue('A8', 'FORWARDER');
        $sheet->setCellValue('A9', 'Code Booking');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B9', 'Invoice');
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->setCellValue('C9', 'ETD');
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->setCellValue('D9', 'ETA');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E9', 'Shipmode');
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->setCellValue('F9', 'Sub Shipmode');
        $sheet->getColumnDimension('F')->setWidth(10);
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
        $sheet->setCellValue('A13', 'No BL');
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
        $sheet->setCellValue('A' . '2', strtoupper('Detail Forwarder'));

        //supplier
        $sheet->setCellValue('A' . $rows_sup, $getdata->pono);
        $sheet->setCellValue('B' . $rows_sup, $getdata->nama);
        $sheet->setCellValue('C' . $rows_sup, $getdata->matcontents);
        $sheet->setCellValue('D' . $rows_sup, $getdata->qtypo);
        $sheet->setCellValue('E' . $rows_sup, $getdata->qty_allocation);
        $rows_sup++;

        //forwarder
        $sheet->setCellValue('A' . $rows_fwd, $getdata->kode_booking);
        $sheet->setCellValue('B' . $rows_fwd, $getdata->noinv);
        $sheet->setCellValue('C' . $rows_fwd, $getdata->etdfix);
        $sheet->setCellValue('D' . $rows_fwd, $getdata->etafix);
        $sheet->setCellValue('E' . $rows_fwd, $getdata->shipmode);
        $sheet->setCellValue('F' . $rows_fwd, $getdata->subshipmode);
        $rows_fwd++;

        //forwarder
        $sheet->setCellValue('A' . $rows_ship, $getdata->nomor_bl);
        $sheet->setCellValue('B' . $rows_ship, $getdata->vessel);
        $rows_ship++;

        $cellsupplier = 'A5:E' . ($rows_sup - 1);
        $cellforwarder = 'A9:F' . ($rows_fwd - 1);
        $cellshipment = 'A13:B' . ($rows_ship - 1);
        $sheet->getStyle('A2:F2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cellsupplier)->applyFromArray($styleArray);
        $sheet->getStyle($cellforwarder)->applyFromArray($styleArray);
        $sheet->getStyle($cellshipment)->applyFromArray($styleArray);
        $sheet->getStyle($cellsupplier)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellforwarder)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellshipment)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        $fileName = "Detail_Forwarder_" . $getdata->kode_booking . ".xlsx";

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
            ->where('privilege.privilege_user_nik', Session::get('session')['user_nik'])
            ->where('formpo.statusformpo', 'confirm')
            ->where('formpo.aktif', 'Y')
            ->get();
        // dd($getdata);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $cell = 'A4:E4';
        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A4', 'PO');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B4', 'Code Booking');
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->setCellValue('C4', 'Material');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D4', 'Quantity Allocation');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E4', 'Status Allocation');
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
            $sheet->setCellValue('B' . $rows, $value->kode_booking);
            $sheet->setCellValue('C' . $rows, $value->matcontents);
            $sheet->setCellValue('D' . $rows, $value->qty_allocation);
            $sheet->setCellValue('E' . $rows, $value->statusforwarder);
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
