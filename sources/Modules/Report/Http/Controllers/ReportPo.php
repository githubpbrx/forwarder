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

class ReportPo extends Controller
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
            'title' => 'Report PO',
            'menu'  => 'reportpo',
            'box'   => '',
        );

        \LogActivity::addToLog('Access Menu Report PO', $this->micro);
        return view('report::reportpo', $data);
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {
            if ($request->po == null) {
                $data = modelpo::get();
            } else {
                $data = modelpo::where('pono', $request->po)->get();
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
                ->addColumn('allocation', function ($data) {
                    if ($data->statusalokasi == 'full_allocated') {
                        $statuspo = 'Full Allocated';
                    } elseif ($data->statusalokasi == 'partial_allocated') {
                        $statuspo = 'Partial Allocation';
                    } else {
                        $statuspo = 'Waiting';
                    }

                    return $statuspo;
                })
                ->addColumn('status', function ($data) {
                    if ($data->statusconfirm == 'confirm') {
                        $status = 'Confirmed';
                    } elseif ($data->statusconfirm == 'reject') {
                        $status = 'Rejected';
                    } else {
                        $status = 'Unprocessed';
                    }

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button .= '<a href="#" data-id="' . $data->id . '" id="detailpo"><i class="fa fa-info-circle"></i></a>';
                    $button .= '&nbsp';
                    $button .= '<a href="' . url('report/po/getexcelpo', ['id' => $data->id]) . '" data-id="#"><i class="fa fa-file-excel text-success"></i></a>';

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

    public function detailpo(Request $request)
    {
        // dd($request);

        $datapo = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('po.id', $request->id)
            ->selectRaw(' po.*, mastersupplier.nama')
            ->first();
        // dd($datapo);
        $data = array(
            'dataku' => $datapo,
        );

        return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
    }

    function excelpo($id)
    {
        $getdata = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('po.id', $id)
            ->selectRaw(' po.*, mastersupplier.nama')
            ->first();
        // dd($getdata);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $cell = 'A4:H4';
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A4', 'PO');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B4', 'Supplier');
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->setCellValue('C4', 'Material');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D4', 'Material Desc');
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->setCellValue('E4', 'Style');
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->setCellValue('F4', 'Quantity PO');
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->setCellValue('G4', 'Price');
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->setCellValue('H4', 'Plant');
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle('A2:H2')->getFont()->setBold(true);
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

        $sheet->setCellValue('A' . '2', strtoupper('Detail PO'));
        $sheet->setCellValue('A' . $rows, $getdata->pono);
        $sheet->setCellValue('B' . $rows, $getdata->nama);
        $sheet->setCellValue('C' . $rows, $getdata->matcontents);
        $sheet->setCellValue('D' . $rows, $getdata->itemdesc);
        $sheet->setCellValue('E' . $rows, $getdata->style);
        $sheet->setCellValue('F' . $rows, $getdata->qtypo);
        $sheet->setCellValue('G' . $rows, $getdata->price . ' ' . $getdata->curr);
        $sheet->setCellValue('H' . $rows, $getdata->plant);
        $rows++;

        $cell = 'A4:H' . ($rows - 1);
        $sheet->getStyle('A2:H2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Detail_PO_" . $getdata->pono . ".xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        return;
    }

    function excelpoall()
    {
        $getdata = modelpo::get();
        // dd($getdata);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $cell = 'A4:D4';
        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A4', 'PO');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B4', 'Material');
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->setCellValue('C4', 'Status Allocation');
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->setCellValue('D4', 'Status Confirm');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle('A2:D2')->getFont()->setBold(true);
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

        $sheet->setCellValue('A' . '2', strtoupper('Data PO'));
        foreach ($getdata as $key => $value) {
            $sheet->setCellValue('A' . $rows, $value->pono);
            $sheet->setCellValue('B' . $rows, $value->matcontents);
            $sheet->setCellValue('C' . $rows, $value->statusalokasi);
            $sheet->setCellValue('D' . $rows, $value->statusconfirm);
            $rows++;
        }

        $cell = 'A4:D' . ($rows - 1);
        $sheet->getStyle('A2:D2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Data_PO.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        return;
    }
}
