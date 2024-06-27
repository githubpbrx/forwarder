<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Modules\System\Helpers\LogActivity;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Modules\Report\Models\modelcontainership;
use Modules\Report\Models\modelpo;
use Modules\Report\Models\modelformshipment;

class ReportShipment extends Controller
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
            'title' => 'Report Ready Shipment',
            'menu'  => 'reportreadyshipment',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Ready Shipment', $this->micro);
        return view('report::readyshipment.reportshipment', $data);
    }

    public function getchartshipment(Request $request)
    {
        $where = '';
        if ($request->pono != NULL) {
            $where .= ' AND po.pono="' . $request->pono . '"';
        }
        if ($request->idmasterfwd != NULL) {
            $where .= ' AND formpo.idmasterfwd=' . $request->idmasterfwd . ' ';
        }
        if ($request->idsupplier != NULL) {
            $imp = implode("','", $request->idsupplier);
            $where .= " AND po.vendor IN ('" . $imp . "')";
        }
        if ($request->periode != NULL) {
            $periode = explode(" - ", $request->periode);
            $where .= ' AND (formshipment.etdfix BETWEEN "' . $periode[0] . '" AND "' . $periode[1] . '")';
        }

        $data = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->selectRaw(' mastersupplier.id, mastersupplier.nama, COUNT(distinct(po.pono)) as jml')
            ->whereRaw(' formpo.aktif="Y" AND mastersupplier.aktif="Y" AND masterforwarder.aktif="Y" AND formshipment.aktif="Y" AND masterforwarder.kurir IS NULL ' . $where . ' ')
            ->groupby('mastersupplier.id')
            ->get();

        return view('report::readyshipment.chartshipment', [
            'data' => $data,
        ]);
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $where = '';
            if ($request->pono != NULL) {
                $where .= ' AND po.pono="' . $request->pono . '"';
            }
            if ($request->idmasterfwd != NULL) {
                $where .= ' AND formpo.idmasterfwd=' . $request->idmasterfwd . ' ';
            }
            if ($request->idsupplier != NULL) {
                $imp = implode("','", $request->idsupplier);
                $where .= " AND po.vendor IN ('" . $imp . "')";
            }
            if ($request->periode != NULL) {
                $periode = explode(" - ", $request->periode);
                $where .= ' AND (formshipment.etdfix BETWEEN "' . $periode[0] . '" AND "' . $periode[1] . '")';
            }

            $data = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
                ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
                ->join('po', 'po.id', 'formpo.idpo')
                ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
                ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                ->whereRaw(' formpo.aktif="Y" AND forwarder.aktif="Y" AND mastersupplier.aktif="Y" AND masterforwarder.aktif="Y" AND formshipment.aktif="Y" AND masterforwarder.kurir IS NULL ' . $where . ' ')
                ->selectRaw(' formshipment.*, formpo.kode_booking, po.id, po.pono, po.matcontents, po.qtypo, po.statusalokasi, po.statusconfirm, po.podate, SUM(po.price * po.qtypo) as amount, po.curr, masterforwarder.name, mastersupplier.nama, forwarder.date_fwd')
                ->groupby('po.pono')->groupby('formshipment.noinv')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->pono;
                })
                ->addColumn('supplier', function ($data) {
                    return $data->nama;
                })
                ->addColumn('forwarder', function ($data) {
                    return $data->name;
                })
                ->addColumn('codebook', function ($data) {
                    return $data->kode_booking;
                })
                ->addColumn('invoice', function ($data) {
                    return $data->noinv;
                })
                ->addColumn('datesubmit', function ($data) {
                    return ($data->created_at == NULL) ? '' : date("Y/m/d", strtotime($data->created_at));
                })
                ->addColumn('atd', function ($data) {
                    return $data->etdfix;
                })
                ->addColumn('ata', function ($data) {
                    return $data->etafix;
                })
                ->addColumn('blnumber', function ($data) {
                    return $data->nomor_bl;
                })
                ->addColumn('action', function ($data) {
                    $process    = '';

                    $process    .= '<a href="#" data-id="' . $data->noinv . '" id="detailshipment"><i class="fa fa-info-circle"></i></a>';
                    $process    .= '&nbsp';
                    $process    .= '<a target="_BLANK" href="' . url('report/shipment/getexcelshipment', ['id' => $data->noinv]) . '"><i class="fa fa-file-excel text-success"></i></a>';

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
            ->where('masterforwarder.kurir', NULL)
            ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('formshipment.aktif', 'Y')
            ->selectRaw(' po.pono ');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' po.pono like "%' . $search . '%" ');
        }

        $po = $po->orderby('po.pono', 'asc')->groupby('po.pono')->paginate(10, $request->page);

        return response()->json($po);
    }

    public function getfwd(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->where('masterforwarder.kurir', NULL)
            ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('formshipment.aktif', 'Y')
            ->selectRaw(' masterforwarder.id, masterforwarder.name ');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' masterforwarder.name like "%' . $search . '%" ');
        }

        $po = $po->orderby('masterforwarder.name', 'asc')->groupby('formpo.idmasterfwd')->paginate(10, $request->page);

        return response()->json($po);
    }

    public function getsupp(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('masterforwarder.kurir', NULL)
            ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('formshipment.aktif', 'Y')
            ->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' mastersupplier.id, mastersupplier.nama ');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' mastersupplier.nama like "%' . $search . '%" ');
        }

        $po = $po->orderby('mastersupplier.nama', 'asc')->groupby('po.vendor')->paginate(10, $request->page);

        return response()->json($po);
    }

    function detailshipment(Request $request)
    {
        $data = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('masterroute', 'masterroute.id_route', 'formpo.idroute')
            ->join('masterportofloading', 'masterportofloading.id_portloading', 'formshipment.idportloading')
            ->join('masterportofdestination', 'masterportofdestination.id_portdestination', 'formshipment.idportdestination')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            // ->leftjoin('containershipment', 'containershipment.noinv', 'formshipment.noinv')
            ->where('formshipment.noinv', $request->id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->where('masterhscode.aktif', 'Y')->where('masterportofloading.aktif', 'Y')->where('masterportofdestination.aktif', 'Y')->where('masterroute.aktif', 'Y')
            ->where('masterforwarder.kurir', NULL)
            ->selectRaw(' formshipment.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.colorcode, po.size, po.style, po.plant, formpo.kode_booking, formpo.date_booking , masterforwarder.name, mastersupplier.nama, masterhscode.hscode, masterroute.route_code, masterroute.route_desc, masterportofloading.code_port as loadingcode, masterportofloading.name_port as loadingname, masterportofdestination.code_port as destinationcode, masterportofdestination.name_port as destinationname')
            ->get();
        // dd($data);

        $getdate = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formshipment.noinv', $request->id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->where('masterforwarder.kurir', NULL)
            ->selectRaw(' formshipment.id_shipment, formshipment.created_at, formshipment.updated_at ')
            ->latest('id_shipment')->first();
        // dd($getdate);

        $getfcl = modelcontainership::where('noinv', $data[0]->noinv)->groupby('volume')->groupby('weight')->where('aktif', 'Y')->get();

        $form = view('report::readyshipment.modalreportshipment', ['data' => $data, 'dateku' => $getdate, 'datafcl' => $getfcl]);
        return $form->render();
    }

    function excelshipment($id)
    {
        $getdata = modelformshipment::with(['container'])
            ->join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('masterroute', 'masterroute.id_route', 'formpo.idroute')
            ->join('masterportofloading', 'masterportofloading.id_portloading', 'formshipment.idportloading')
            ->join('masterportofdestination', 'masterportofdestination.id_portdestination', 'formshipment.idportdestination')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->where('formshipment.noinv', $id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->where('masterhscode.aktif', 'Y')->where('masterportofloading.aktif', 'Y')->where('masterportofdestination.aktif', 'Y')->where('masterroute.aktif', 'Y')
            ->where('masterforwarder.kurir', NULL)
            ->selectRaw(' formshipment.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.style, po.plant, po.colorcode, po.size, formpo.kode_booking, formpo.date_booking, masterforwarder.name, mastersupplier.nama, masterhscode.hscode, masterroute.route_code, masterroute.route_desc, masterportofloading.code_port as loadingcode, masterportofloading.name_port as loadingname, masterportofdestination.code_port as destinationcode, masterportofdestination.name_port as destinationname')
            ->get();

        $getdate = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formshipment.noinv', $id)
            ->where('formshipment.aktif', 'Y')->where('formpo.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formshipment.id_shipment, formshipment.created_at, formshipment.updated_at')
            ->latest('id_shipment')->first();
        // dd($getdate);

        $getfcl = modelcontainership::where('noinv', $getdata[0]->noinv)->groupby('volume')->groupby('weight')->where('aktif', 'Y')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($getdata[0]->shipmode == 'fcl') {
            $sheet->mergeCells('A2:O2');
            $sheet->getStyle('A2:O2')->getFont()->setBold(true);
        } else {
            $sheet->mergeCells('A2:M2');
            $sheet->getStyle('A2:M2')->getFont()->setBold(true);
        }

        //single header
        $sheet->setCellValue('A4', 'Invoice');
        $sheet->setCellValue('A5', 'Invoice Date');
        $sheet->setCellValue('A6', 'PO');
        $sheet->setCellValue('A7', 'Supplier');
        $sheet->getStyle('A4:A7')->getFont()->setBold(true);
        $sheet->setCellValue('C4', 'Forwarder');
        $sheet->setCellValue('C5', 'Date Submit');
        $sheet->setCellValue('C6', 'Update Data');
        $sheet->getStyle('C4:C6')->getFont()->setBold(true);

        //for header
        $cellheader = 'A9:O9';
        $sheet->setCellValue('A9', 'Code Booking');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue('B9', 'Date Booking');
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->setCellValue('C9', 'Route');
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->setCellValue('D9', 'Port Of Loading');
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->setCellValue('E9', 'Port Of Destination');
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->setCellValue('F9', 'Package');
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->setCellValue('G9', 'BL Number');
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->setCellValue('H9', 'ATD');
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->setCellValue('I9', 'ATA');
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->setCellValue('J9', 'Vessel');
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->setCellValue('K9', 'Shipmode');
        $sheet->getColumnDimension('K')->setAutoSize(true);
        if ($getdata[0]->shipmode == 'fcl') {
            $sheet->setCellValue('L9', 'Container Size');
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->setCellValue('M9', 'Volume');
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->setCellValue('N9', 'Number Of Container');
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->setCellValue('O9', 'Weight');
            $sheet->getColumnDimension('O')->setAutoSize(true);
        } else {
            $sheet->setCellValue('L9', 'Volume');
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->setCellValue('M9', 'Weight');
            $sheet->getColumnDimension('M')->setAutoSize(true);
        }
        $sheet->getStyle($cellheader)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cellheader)->getFont()->setBold(true);
        $sheet->getStyle($cellheader)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cellheader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellheader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellheader)->getFill()->getStartColor()->setARGB('ff8400');

        //for data
        $count = count($getfcl);
        if ($count > 1) {
            $mycell = (12 + $count) - 1;
        } else {
            $mycell = 12;
        }
        $celldata = 'A' . $mycell . ':G' . $mycell;
        $sheet->setCellValue('A' . $mycell, 'Material');
        $sheet->getColumnDimension('A')->setWidth(50);
        $sheet->setCellValue('B' . $mycell, 'Material Desc');
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->setCellValue('C' . $mycell, 'HS Code ');
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->setCellValue('D' . $mycell, 'Color');
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->setCellValue('E' . $mycell, 'Size');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F' . $mycell, 'Qty PO');
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->setCellValue('G' . $mycell, 'Qty Ship');
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getStyle($celldata)->getAlignment()->setWrapText(true);
        $sheet->getStyle($celldata)->getFont()->setBold(true);
        $sheet->getStyle($celldata)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($celldata)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($celldata)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($celldata)->getFill()->getStartColor()->setARGB('ff8400');

        $header = 10;
        $datafcl = 10;
        $bodydata = $mycell + 1;
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
        //title
        $sheet->setCellValue('A' . '2', strtoupper('Detail Ready Shipment'));

        //single header
        $sheet->setCellValue('B' . '4', ':' . $getdata[0]->noinv);
        $sheet->setCellValue('B' . '5', ':' . date('d F Y H:i:s', strtotime($getdata[0]->created_at)));
        $sheet->setCellValue('B' . '6', ':' . $getdata[0]->pono);
        $sheet->setCellValue('B' . '7', ':' . $getdata[0]->nama);
        $sheet->setCellValue('D' . '4', ':' . $getdata[0]->name);
        $sheet->setCellValue('D' . '5', ':' . date('d F Y H:i:s', strtotime($getdate->created_at)));
        if ($getdate->updated_at == null) {
            $stat = '';
        } else {
            $stat = date('d F Y H:i:s', strtotime($getdate->updated_at));
        }
        $sheet->setCellValue('D' . '6', ':' . $stat);

        //header
        $sheet->setCellValue('A' . $header, $getdata[0]->kode_booking);
        $sheet->setCellValue('B' . $header, date('d F Y', strtotime($getdata[0]->date_booking)));
        $sheet->setCellValue('C' . $header, $getdata[0]->route_code . ' - ' .  $getdata[0]->route_desc);
        $sheet->setCellValue('D' . $header, $getdata[0]->loadingcode . ' - ' .  $getdata[0]->loadingname);
        $sheet->setCellValue('E' . $header, $getdata[0]->destinationcode . ' - ' .  $getdata[0]->destinationname);
        $sheet->setCellValue('F' . $header, $getdata[0]->package);
        $sheet->setCellValue('G' . $header, $getdata[0]->nomor_bl);
        $sheet->setCellValue('H' . $header, date('d F Y', strtotime($getdata[0]->etdfix)));
        $sheet->setCellValue('I' . $header, date('d F Y', strtotime($getdata[0]->etafix)));
        $sheet->setCellValue('J' . $header, $getdata[0]->vessel);
        $sheet->setCellValue('K' . $header, $getdata[0]->shipmode);
        if ($getdata[0]->shipmode == 'fcl') {
            foreach ($getfcl as $key => $value) {
                // $exp = explode('-', $getdata[0]->subshipmode);
                $sheet->setCellValue('L' . $datafcl, $value->containernumber);
                $sheet->setCellValue('M' . $datafcl, $value->volume);
                $sheet->setCellValue('N' . $datafcl, $value->numberofcontainer);
                $sheet->setCellValue('O' . $datafcl, $value->weight);
                $datafcl++;
            }
        } else {
            $exp2 = explode('-', $getdata[0]->subshipmode);
            $sheet->setCellValue('L' . $header, $exp2[0]);
            $sheet->setCellValue('M' . $header, $exp2[1]);
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
            $cellheader = 'A9:O' . ($header - 1);
            $sheet->mergeCells('A10:A' . ($datafcl - 1));
            $sheet->mergeCells('B10:B' . ($datafcl - 1));
            $sheet->mergeCells('C10:C' . ($datafcl - 1));
            $sheet->mergeCells('D10:D' . ($datafcl - 1));
            $sheet->mergeCells('E10:E' . ($datafcl - 1));
            $sheet->mergeCells('F10:F' . ($datafcl - 1));
            $sheet->mergeCells('G10:G' . ($datafcl - 1));
            $sheet->mergeCells('H10:H' . ($datafcl - 1));
            $sheet->mergeCells('I10:I' . ($datafcl - 1));
            $sheet->mergeCells('J10:J' . ($datafcl - 1));
            $sheet->mergeCells('K10:K' . ($datafcl - 1));
        } else {
            $cellheader = 'A9:M' . ($header - 1);
        }

        $celldata = 'A' . $mycell . ':G' . ($bodydata - 1);
        $cellfcl = 'A10:O' . ($datafcl - 1);
        $sheet->getStyle('A2:M2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cellheader)->applyFromArray($styleArray);
        $sheet->getStyle($celldata)->applyFromArray($styleArray);
        $sheet->getStyle($cellfcl)->applyFromArray($styleArray);
        $sheet->getStyle($cellheader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($celldata)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellfcl)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Report_Ready_Shipment_" . $getdata[0]->noinv . ".xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        return;
    }

    function excelshipmentall(Request $request)
    {
        $where = '';
        if ($request->pono != NULL) {
            $where .= ' AND po.pono="' . $request->pono . '"';
        }
        if ($request->idmasterfwd != NULL) {
            $where .= ' AND formpo.idmasterfwd=' . $request->idmasterfwd . ' ';
        }
        if ($request->idsupplier != NULL) {
            $imp = implode("','", $request->idsupplier);
            $where .= " AND po.vendor IN ('" . $imp . "')";
        }
        if ($request->periode != NULL) {
            $periode = explode(" - ", $request->periode);
            $where .= ' AND (formshipment.etdfix BETWEEN "' . $periode[0] . '" AND "' . $periode[1] . '")';
        }

        $getdata = modelformshipment::join('formpo', 'formpo.id_formpo', 'formshipment.idformpo')
            ->join('forwarder', 'forwarder.id_forwarder', 'formpo.idforwarder')
            ->join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->whereRaw(' formpo.aktif="Y" AND forwarder.aktif="Y" AND mastersupplier.aktif="Y" AND masterforwarder.aktif="Y" AND formshipment.aktif="Y" AND masterforwarder.kurir IS NULL ' . $where . ' ')
            ->selectRaw(' formshipment.*, formpo.kode_booking, po.id, po.pono, po.matcontents, po.qtypo, po.statusalokasi, po.statusconfirm, po.podate, SUM(po.price * po.qtypo) as amount, po.curr, masterforwarder.name, mastersupplier.nama, forwarder.date_fwd')
            ->groupby('po.pono')->groupby('formshipment.noinv')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $cell = 'A4:I4';
        $sheet->mergeCells('A2:I2');
        $sheet->setCellValue('A4', 'PO');
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->setCellValue('B4', 'Supplier');
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->setCellValue('C4', 'Forwarder');
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->setCellValue('D4', 'Code Booking');
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->setCellValue('E4', 'Invoice');
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->setCellValue('F4', 'Date Submit');
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->setCellValue('G4', 'ATD');
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->setCellValue('H4', 'ATA');
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->setCellValue('I4', 'BL Number');
        $sheet->getColumnDimension('I')->setWidth(20);

        $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle('A2:I2')->getFont()->setBold(true);
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

        $sheet->setCellValue('A' . '2', strtoupper('Data Report Shipment'));

        foreach ($getdata as $key => $val) {
            $sheet->setCellValue('A' . $rows, $val->pono);
            $sheet->setCellValue('B' . $rows, $val->nama);
            $sheet->setCellValue('C' . $rows, $val->name);
            $sheet->setCellValue('D' . $rows, $val->kode_booking);
            $sheet->setCellValue('E' . $rows, $val->noinv);
            $sheet->setCellValue('F' . $rows, ($val->created_at == NULL) ? '' : date("Y/m/d", strtotime($val->created_at)));
            $sheet->setCellValue('G' . $rows, $val->etdfix);
            $sheet->setCellValue('H' . $rows, $val->etafix);
            $sheet->setCellValue('I' . $rows, $val->nomor_bl);
            $rows++;
        }

        $cell = 'A4:I' . ($rows - 1);
        $sheet->getStyle('A2:I2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Report_Shipment_All.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        // return;
    }
}
