<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Modules\System\Helpers\LogActivity;
use Modules\Report\Models\modelpo;
use Modules\Report\Models\mastersupplier;

class ReportPo extends Controller
{
    protected $micro;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->micro = microtime(true);
    }

    public function index()
    {
        $data = array(
            'title' => 'Report Outstanding PO',
            'menu'  => 'reportoutstandingpo',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Outstanding PO', $this->micro);
        return view('report::outstandingpo.reportpo', $data);
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $where = '';
            if ($request->supplier != NULL) {
                $imp = implode("','", $request->supplier);
                $where .= " and mastersupplier.id IN ('" . $imp . "')";
            }
            if ($request->periode != NULL) {
                $periode = explode(" - ", $request->periode);
                $where .= ' and (po.podate BETWEEN "' . $periode[0] . '" AND "' . $periode[1] . '")';
            }
            if ($request->po != NULL) {
                $where .= ' and po.pono="' . $request->po . '"';
            }

            $data = modelpo::with(['postatus'])->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                // ->where(function ($var) {
                //     $var->where('po.statusconfirm', '!=', 'confirm')->orWhere('po.statusconfirm', null);
                // })
                ->whereRaw(' mastersupplier.aktif="Y" ' . $where . '')
                ->selectRaw(' po.id, po.pono, po.matcontents, po.podate, sum(po.price * po.qtypo) as amount, po.curr, po.shipmode, po.statusconfirm, mastersupplier.nama ')
                ->groupby('po.pono')
                ->orderby('po.podate', 'DESC')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('date', function ($data) {
                    return date("Y/m/d", strtotime($data->podate));
                })
                ->addColumn('amount', function ($data) {
                    return round($data->amount, 3) . ' ' . $data->curr;
                })
                ->addColumn('supplier', function ($data) {
                    return $data->nama;
                })
                ->addColumn('status', function ($data) {
                    if ($data->postatus == null) {
                        return '';
                    } else {
                        $dt = $data->postatus->pluck('statusconfirm');
                        $col = collect($dt);
                        $arr = $col->toArray();
                        $uq = array_unique($arr);
                        if (count($uq) == 1) {
                            if ($uq[0] == null) {
                                return 'Un Processed';
                            }
                            return $uq[0];
                        } else {
                            return 'On Going';
                        }
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = '';

                    $button .= '<center><a href="#" data-id="' . $data->pono . '" id="detailpo"><i class="fa fa-info-circle"></i></a></center>';

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
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

    public function getsupplier(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = mastersupplier::select('id', 'nama')->where('aktif', 'Y');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' (nama like "%' . $search . '%") ');
        }

        $po = $po->orderby('nama', 'asc')->groupby('nama')->paginate(10, $request->page);

        return response()->json($po);
    }

    public function detailpo(Request $request)
    {
        $datapo = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('po.pono', $request->id)
            ->selectRaw(' po.pono, po.matcontents, po.itemdesc, po.colorcode, po.size, po.qtypo, po.statusconfirm, mastersupplier.nama')
            ->get();

        // return response()->json(['status' => 200, 'data' => $datapo, 'message' => 'Berhasil']);
        $form = view('report::outstandingpo.modalreportpo', ['data' => $datapo]);
        return $form->render();
    }

    function excelpo($id)
    {
        $getdata = modelpo::join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('po.id', $id)
            ->selectRaw(' po.*, mastersupplier.nama')
            ->first();

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

    function excelpoall(Request $request)
    {
        $where = '';
        if ($request->supplier != NULL) {
            $imp = implode("','", $request->supplier);
            $where .= " and mastersupplier.id IN ('" . $imp . "')";
        }
        if ($request->periode != NULL) {
            $periode = explode(" - ", $request->periode);
            $where .= ' and (po.podate BETWEEN "' . $periode[0] . '" AND "' . $periode[1] . '")';
        }
        if ($request->po != NULL) {
            $where .= ' and po.pono="' . $request->po . '"';
        }

        $data = modelpo::with(['postatus'])->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            // ->where(function ($var) {
            //     $var->where('po.statusconfirm', '!=', 'confirm')->orWhere('po.statusconfirm', null);
            // })
            ->whereRaw(' mastersupplier.aktif="Y" ' . $where . '')
            ->selectRaw(' po.id, po.pono, po.matcontents, po.podate, sum(po.price * po.qtypo) as amount, po.curr, po.shipmode, po.statusconfirm, mastersupplier.nama ')
            ->groupby('po.pono')
            ->orderby('po.podate', 'DESC')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $cell = 'A4:E4';
        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A4', 'PO');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue('B4', 'Date');
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->setCellValue('C4', 'Amount');
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->setCellValue('D4', 'Supplier');
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->setCellValue('E4', 'Status');
        $sheet->getColumnDimension('E')->setAutoSize(true);
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

        $sheet->setCellValue('A' . '2', strtoupper('Data Outstanding PO'));
        foreach ($data as $key => $value) {
            if ($value->postatus == null) {
                $stat = '';
            } else {
                $dt = $value->postatus->pluck('statusconfirm');
                $col = collect($dt);
                $arr = $col->toArray();
                $uq = array_unique($arr);
                if (count($uq) == 1) {
                    if ($uq[0] == null) {
                        $stat = 'Un Processed';
                    } else {
                        $stat = $uq[0];
                    }
                } else {
                    $stat = 'On Going';
                }
            }

            $sheet->setCellValue('A' . $rows, $value->pono);
            $sheet->setCellValue('B' . $rows, date("Y/m/d", strtotime($value->podate)));
            $sheet->setCellValue('C' . $rows, $value->amount . ' ' . $value->curr);
            $sheet->setCellValue('D' . $rows, $value->nama);
            $sheet->setCellValue('E' . $rows, $stat);
            $rows++;
        }

        $cell = 'A4:E' . ($rows - 1);
        $sheet->getStyle('A2:E2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Data_Outstanding_PO.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
        // return;
    }
}
