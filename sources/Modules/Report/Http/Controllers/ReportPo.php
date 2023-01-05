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
            'title' => 'Outstanding PO',
            'menu'  => 'outstandingpo',
            'box'   => '',
        );

        \LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Outstanding PO', $this->micro);
        return view('report::reportpo', $data);
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            if ($request->po == null) {
                $data = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                    ->where('mastersupplier.aktif', 'Y')
                    ->select('po.id', 'po.pono', 'po.matcontents', 'po.podate', 'mastersupplier.nama')
                    ->selectRaw(' count(po.id) as amount ')
                    ->groupby('po.pono')
                    ->get();
            } else {
                $data = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                    ->where('pono', $request->po)
                    ->where('mastersupplier.aktif', 'Y')
                    ->select('po.id', 'po.pono', 'po.matcontents', 'po.podate', 'mastersupplier.nama')
                    ->selectRaw(' count(po.id) as amount ')
                    ->groupby('po.pono')
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('date', function ($data) {
                    return date("d/m/Y", strtotime($data->podate));
                })
                ->addColumn('amount', function ($data) {
                    return $data->amount;
                })
                ->addColumn('supplier', function ($data) {
                    return $data->nama;
                })
                // ->addColumn('allocation', function ($data) {
                //     if ($data->statusalokasi == 'full_allocated') {
                //         $statuspo = 'Full Allocated';
                //     } elseif ($data->statusalokasi == 'partial_allocated') {
                //         $statuspo = 'Partial Allocation';
                //     } else {
                //         $statuspo = 'Waiting';
                //     }

                //     return $statuspo;
                // })
                // ->addColumn('status', function ($data) {
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
                    $button = '';

                    $button .= '<a href="#" data-id="' . $data->pono . '" id="detailpo"><i class="fa fa-info-circle"></i></a>';
                    $button .= '&nbsp';
                    // $button .= '<a href="' . url('report/po/getexcelpo', ['id' => $data->id]) . '" data-id="#"><i class="fa fa-file-excel text-success"></i></a>';

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
            $po = $po->whereRaw(' (pono like "%' . $search . '%") ');
        }

        $po = $po->orderby('pono', 'asc')->groupby('pono')->paginate(10, $request->page);

        return response()->json($po);
    }

    public function detailpo(Request $request)
    {
        // dd($request);

        $datapo = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('po.pono', $request->id)
            ->selectRaw(' po.pono, po.matcontents, po.itemdesc, po.colorcode, po.size, po.qtypo, po.statusconfirm, mastersupplier.nama')
            ->get();
        // dd($datapo);

        return response()->json(['status' => 200, 'data' => $datapo, 'message' => 'Berhasil']);
        // $form = view('report::modalreportalokasi', ['data' => $datapo]);
        // return $form->render();
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

        $cell = 'A4:K4';
        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue('A4', 'PO');
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->setCellValue('B4', 'Material');
        $sheet->getColumnDimension('B')->setWidth(40);
        $sheet->setCellValue('C4', 'Material Desc');
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->setCellValue('D4', 'Color Code');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E4', 'Size');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F4', 'Quantity PO');
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->setCellValue('G4', 'Price');
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->setCellValue('H4', 'Supplier');
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->setCellValue('I4', 'Plant');
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->setCellValue('J4', 'Style');
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->setCellValue('K4', 'Buyer');
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle('A2:K2')->getFont()->setBold(true);
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
        $sheet->setCellValue('B' . $rows, $getdata->matcontents);
        $sheet->setCellValue('C' . $rows, $getdata->itemdesc);
        $sheet->setCellValue('D' . $rows, $getdata->colorcode);
        $sheet->setCellValue('E' . $rows, $getdata->size);
        $sheet->setCellValue('F' . $rows, $getdata->qtypo);
        $sheet->setCellValue('G' . $rows, $getdata->price . ' ' . $getdata->curr);
        $sheet->setCellValue('H' . $rows, $getdata->nama);
        $sheet->setCellValue('I' . $rows, $getdata->plant);
        $sheet->setCellValue('J' . $rows, $getdata->style);
        $sheet->setCellValue('K' . $rows, $getdata->buyer);
        $rows++;

        $cell = 'A4:K' . ($rows - 1);
        $sheet->getStyle('A2:K2')->applyFromArray($styleArraytitle);
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
