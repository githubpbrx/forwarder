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
                    ->selectRaw(' formshipment.*, formpo.kode_booking, po.id, po.pono, po.matcontents, po.qtypo, po.statusalokasi, po.statusconfirm, masterforwarder.name ')
                    ->groupby('po.pono')->groupby('formshipment.noinv')
                    ->get();
            } else {
                $data = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
                    ->join('po', 'po.id', 'formpo.idpo')
                    ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                    ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('formshipment.aktif', 'Y')
                    ->where('po.pono', $request->po)
                    ->selectRaw(' formshipment.*, formpo.kode_booking, po.id, po.pono, po.matcontents, po.qtypo, po.statusalokasi, po.statusconfirm, masterforwarder.name ')
                    ->groupby('po.pono')->groupby('formshipment.noinv')
                    ->get();
            }

            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('invoice', function ($data) {
                    return $data->noinv;
                })
                ->addColumn('kodebook', function ($data) {
                    return $data->kode_booking;
                })
                ->addColumn('blnumber', function ($data) {
                    return $data->nomor_bl;
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

                    $process    .= '<a href="#" data-id="' . $data->noinv . '" id="detailalokasi"><i class="fa fa-info-circle"></i></a>';
                    $process    .= '&nbsp';
                    $process    .= '<a href="' . url('report/alokasi/getexcelalokasi', ['id' => $data->noinv]) . '"><i class="fa fa-file-excel text-success"></i></a>';

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
            ->where('formshipment.noinv', $request->id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formshipment.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.colorcode, po.size, po.style, po.plant, formpo.*, masterforwarder.name, mastersupplier.nama ')
            ->get();

        $getdate = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formshipment.noinv', $request->id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formshipment.id_shipment, formshipment.created_at, formshipment.updated_at ')
            ->latest('id_shipment')->first();
        // dd($getdate);
        // return response()->json(['status' => 200, 'data' => $data, 'message' => 'Berhasil']);
        $form = view('report::modalreportalokasi', ['data' => $data, 'dateku' => $getdate]);
        return $form->render();
    }

    function excelalokasi($id)
    {
        $getdata = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formshipment.noinv', $id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formshipment.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.style, po.plant, po.colorcode, po.size, formpo.*, masterforwarder.name, mastersupplier.nama ')
            ->get();

        $getdate = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formshipment.noinv', $id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formshipment.id_shipment, formshipment.created_at, formshipment.updated_at')
            ->latest('id_shipment')->first();
        // dd($getdate);

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
        $sheet->setCellValue('C4', 'Forwarder');
        $sheet->setCellValue('C5', 'Input Data');
        $sheet->setCellValue('C6', 'Update Data');
        $sheet->getStyle('C4:C6')->getFont()->setBold(true);

        //for header
        $cellheader = 'A9:G9';
        $sheet->setCellValue('A9', 'Code Booking');
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->setCellValue('B9', 'BL Number');
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->setCellValue('C9', 'ETD');
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->setCellValue('D9', 'ETA');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E9', 'Shipmode');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F9', 'Sub Shipmode');
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->setCellValue('G9', 'Vessel');
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getStyle($cellheader)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cellheader)->getFont()->setBold(true);
        $sheet->getStyle($cellheader)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cellheader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellheader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellheader)->getFill()->getStartColor()->setARGB('ff8400');

        //for data
        $celldata = 'A12:F12';
        $sheet->setCellValue('A12', 'Material');
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->setCellValue('B12', 'Style');
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->setCellValue('C12', 'Color Code ');
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->setCellValue('D12', 'Size');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E12', 'Quantity Item');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F12', 'Quantity Shipment');
        $sheet->getColumnDimension('F')->setWidth(20);
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
        $sheet->setCellValue('A' . '2', strtoupper('Detail Allocation'));

        //single header
        $sheet->setCellValue('B' . '4', ':' . $getdata[0]->noinv);
        $sheet->setCellValue('B' . '5', ':' . date('d-m-Y H:i:s', strtotime($getdata[0]->created_at)));
        $sheet->setCellValue('B' . '6', ':' . $getdata[0]->pono);
        $sheet->setCellValue('B' . '7', ':' . $getdata[0]->nama);
        $sheet->setCellValue('D' . '4', ':' . $getdata[0]->name);
        $sheet->setCellValue('D' . '5', ':' . date('d-m-Y H:i:s', strtotime($getdate->created_at)));
        if ($getdate->updated_at == null) {
            $stat = '';
        } else {
            $stat = date('d-m-Y H:i:s', strtotime($getdate->updated_at));
        }
        $sheet->setCellValue('D' . '6', ':' . $stat);

        //header
        $sheet->setCellValue('A' . $header, $getdata[0]->kode_booking);
        $sheet->setCellValue('B' . $header, $getdata[0]->nomor_bl);
        $sheet->setCellValue('C' . $header, date('d-m-Y', strtotime($getdata[0]->etdfix)));
        $sheet->setCellValue('D' . $header, date('d-m-Y', strtotime($getdata[0]->etafix)));
        $sheet->setCellValue('E' . $header, $getdata[0]->shipmode);
        $sheet->setCellValue('F' . $header, $getdata[0]->subshipmode);
        $sheet->setCellValue('G' . $header, $getdata[0]->vessel);
        $header++;

        //data
        foreach ($getdata as $key => $value) {
            $sheet->setCellValue('A' . $bodydata, $value->matcontents);
            $sheet->setCellValue('B' . $bodydata, $value->style);
            $sheet->setCellValue('C' . $bodydata, $value->colorcode);
            $sheet->setCellValue('D' . $bodydata, $value->size);
            $sheet->setCellValue('E' . $bodydata, $value->qtypo);
            $sheet->setCellValue('F' . $bodydata, $value->qty_shipment);
            $bodydata++;
        }

        $cellheader = 'A9:G' . ($header - 1);
        $celldata = 'A12:F' . ($bodydata - 1);
        $sheet->getStyle('A2:G2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cellheader)->applyFromArray($styleArray);
        $sheet->getStyle($celldata)->applyFromArray($styleArray);
        $sheet->getStyle($cellheader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($celldata)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Report_Allocation_" . $getdata[0]->noinv . ".xlsx";

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
