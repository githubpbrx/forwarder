<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Modules\System\Helpers\LogActivity;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Modules\Report\Models\modelforwarder;
use Modules\Report\Models\modelformpo;
use Modules\Report\Models\modelformshipment;

class ReportAlokasi extends Controller
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
            'title' => 'Report Ready Allocation',
            'menu'  => 'reportreadyallocation',
            'box'   => '',
        );

        LogActivity::addToLog('Web Forwarder :: Logistik : Access Menu Ready Allocation', $this->micro);
        return view('report::readyallocation.reportalokasi', $data);
    }

    public function getchartalokasi(Request $request)
    {
        $where = '';
        if ($request->pino != NULL) {
            $where .= ' AND po.pino="' . $request->pino . '"';
        }
        if ($request->pono != NULL) {
            $where .= ' AND po.pono="' . $request->pono . '"';
        }
        if ($request->idmasterfwd != NULL) {
            $where .= ' AND forwarder.idmasterfwd=' . $request->idmasterfwd . ' ';
        }
        if ($request->idsupplier != NULL) {
            $imp = implode("','", $request->idsupplier);
            $where .= " AND po.vendor IN ('" . $imp . "')";
        }
        if ($request->periode != NULL) {
            $periode = explode(" - ", $request->periode);
            $where .= ' AND (forwarder.created_at BETWEEN "' . $periode[0] . '" AND "' . $periode[1] . '")';
        }

        $data = modelforwarder::join('po', 'po.id', 'forwarder.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->selectRaw(' mastersupplier.id, mastersupplier.nama, COUNT(distinct(po.pono)) as jml')
            ->whereRaw(' forwarder.aktif="Y" AND mastersupplier.aktif="Y" AND masterforwarder.aktif="Y" AND masterforwarder.kurir IS NULL ' . $where . ' ')
            ->groupby('mastersupplier.id')
            ->get();

        return view('report::readyallocation.chartalokasi', [
            'data' => $data,
        ]);
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $where = '';
            if ($request->pino != NULL) {
                $where .= ' AND po.pino="' . $request->pino . '"';
            }
            if ($request->pono != NULL) {
                $where .= ' AND po.pono="' . $request->pono . '"';
            }
            if ($request->idmasterfwd != NULL) {
                $where .= ' AND forwarder.idmasterfwd=' . $request->idmasterfwd . ' ';
            }
            if ($request->idsupplier != NULL) {
                $imp = implode("','", $request->idsupplier);
                $where .= " AND po.vendor IN ('" . $imp . "')";
            }
            if ($request->periode != NULL) {
                $periode = explode(" - ", $request->periode);
                $where .= ' AND (forwarder.created_at BETWEEN "' . $periode[0] . '" AND "' . $periode[1] . '")';
            }

            $data = modelforwarder::with(['formpo'])
                ->join('po', 'po.id', 'forwarder.idpo')
                ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
                ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
                ->selectRaw(' forwarder.*, po.id, po.pono, po.pino, po.podate, po.shipmode, po.curr, po.vendor, po.price, po.qtypo, SUM(po.price * po.qtypo) as amount, po.pideldate, mastersupplier.nama, masterforwarder.name')
                ->whereRaw(' forwarder.aktif="Y" AND mastersupplier.aktif="Y" AND masterforwarder.aktif="Y" AND masterforwarder.kurir IS NULL ' . $where . ' ')
                ->groupby('forwarder.po_nomor')->groupby('forwarder.idmasterfwd')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('pi', function ($data) {
                    return $data->pino;
                })
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
                ->addColumn('shipmode', function ($data) {
                    if ($data['formpo']) {
                        $ship = $data['formpo']['shipmode'];
                    } else {
                        $ship = $data->shipmode;
                    }
                    return $ship;
                })
                ->addColumn('forwarder', function ($data) {
                    return $data->name;
                })
                ->addColumn('dateallocation', function ($data) {
                    if ($data->created_at) {
                        $dateall = date("Y/m/d", strtotime($data->created_at));
                    } else {
                        $dateall = '';
                    }

                    return $dateall;
                })
                ->addColumn('pidelivery', function ($data) {
                    if ($data->pideldate) {
                        $dateall = date("Y/m/d", strtotime($data->pideldate));
                    } else {
                        $dateall = '';
                    }

                    return $dateall;
                })
                ->addColumn('datebook', function ($data) {
                    if ($data['formpo']) {
                        $bookdate = date("Y/m/d", strtotime($data['formpo']['date_booking']));
                    } else {
                        $bookdate = '';
                    }

                    return $bookdate;
                })
                ->addColumn('dateconfirm', function ($data) {
                    if ($data->date_fwd) {
                        $confirmdate = date("Y/m/d", strtotime($data->date_fwd));
                    } else {
                        $confirmdate = '';
                    }

                    return $confirmdate;
                })
                ->addColumn('dateshipment', function ($data) {
                    $dateshipment2 = '';
                    if ($data['formpo'] != NULL) {
                        $idformpo = $data['formpo']['id_formpo'];
                        $getshipment = modelformshipment::where('idformpo', $idformpo)->where('aktif', 'Y')->pluck('created_at');
                        $dateshipment1 = str_replace("[", "", str_replace("]", "", str_replace('"', "", $getshipment)));
                        $dateshipment2 = $dateshipment1 == NULL ? '' : date("Y/m/d", strtotime($dateshipment1));
                    }
                    return $dateshipment2;
                })
                ->addColumn('status', function ($data) {
                    $stat = '';
                    $cekshipment = 0;
                    if ($data->statusapproval == NULL) {
                        $stat = 'Waiting';
                    }
                    if ($data->statusapproval == 'waiting' || $data->statusapproval == 'reject') {
                        $stat = 'Booked';
                    }
                    if ($data->statusapproval == 'confirm') {
                        $stat = 'Confirmed';
                    }

                    //cekshipment
                    if ($data['formpo'] != NULL) {
                        $idformpo = $data['formpo']['id_formpo'];
                        $cekshipment = modelformshipment::where('idformpo', $idformpo)->where('aktif', 'Y')->count();
                    }
                    if ($cekshipment > 0) {
                        $stat = 'Shipment';
                    }

                    // if ($data->statusapproval == 'confirm') {
                    //     $stat = 'Confirmed';
                    // } else if ($data->statusapproval == 'reject') {
                    //     $stat = 'Rejected';
                    // } else if ($data->statusallocation == 'cancelled') {
                    //     $stat = 'Cancelled';
                    // } else {
                    //     $stat = 'Waiting';
                    // }
                    return $stat;
                })
                ->addColumn('action', function ($data) {
                    $process    = '';

                    $process    .= '<center><a href="#" data-id="' . $data->po_nomor . '" data-idmasterfwd="' . $data->idmasterfwd . '" id="detailalokasi"><i class="fa fa-info-circle"></i></a></center>';
                    // $process    .= '&nbsp';
                    // $process    .= '<a href="' . url('report/alokasi/getexcelalokasi', ['id' => $data->po_nomor]) . '"><i class="fa fa-file-excel text-success"></i></a>';

                    return $process;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getpi(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;

        $po = modelforwarder::join('po', 'po.id', 'forwarder.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->selectRaw(' forwarder.id_forwarder, forwarder.po_nomor, po.pino')
            ->where('masterforwarder.kurir', NULL)
            ->where('forwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')->where('masterforwarder.aktif', 'Y');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' po.pino like "%' . $search . '%" ');
        }

        $po = $po->orderby('po.pino', 'asc')->groupby('po.pino')->groupby('forwarder.idmasterfwd')->paginate(10, $request->page);

        return response()->json($po);
    }

    public function getpo(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;

        $po = modelforwarder::join('po', 'po.id', 'forwarder.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->selectRaw(' forwarder.id_forwarder, forwarder.po_nomor')
            ->where('masterforwarder.kurir', NULL)
            ->where('forwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')->where('masterforwarder.aktif', 'Y');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' forwarder.po_nomor like "%' . $search . '%" ');
        }

        $po = $po->orderby('forwarder.po_nomor', 'asc')->groupby('forwarder.po_nomor')->groupby('forwarder.idmasterfwd')->paginate(10, $request->page);

        return response()->json($po);
    }

    public function getfwd(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = modelforwarder::join('po', 'po.id', 'forwarder.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->selectRaw(' masterforwarder.id, masterforwarder.name')
            ->where('masterforwarder.kurir', NULL)
            ->where('forwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')->where('masterforwarder.aktif', 'Y');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' masterforwarder.name like "%' . $search . '%" ');
        }

        $po = $po->orderby('masterforwarder.name', 'asc')->groupby('forwarder.idmasterfwd')->paginate(10, $request->page);

        return response()->json($po);
    }

    public function getsupplier(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = modelforwarder::join('po', 'po.id', 'forwarder.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->selectRaw(' mastersupplier.id, mastersupplier.nama')
            ->where('masterforwarder.kurir', NULL)
            ->where('forwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')->where('masterforwarder.aktif', 'Y');

        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' mastersupplier.nama like "%' . $search . '%" ');
        }

        $po = $po->orderby('mastersupplier.nama', 'asc')->groupby('po.vendor')->paginate(10, $request->page);

        return response()->json($po);
    }

    function detailalokasi(Request $request)
    {
        // $data = modelforwarder::with(['formpo' => function ($var) {
        //     $var->with(['route', 'loading', 'destination']);
        // }])
        //     ->join('po', 'po.id', 'forwarder.idpo')
        //     ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
        //     ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
        //     ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
        //     ->where('forwarder.po_nomor', $request->id)
        //     ->where('forwarder.idmasterfwd', $request->idmasterfwd)
        //     ->where('masterforwarder.kurir', NULL)
        //     ->selectRaw(' forwarder.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.colorcode, po.size, po.style, po.plant, masterforwarder.name, mastersupplier.nama, masterhscode.hscode ')
        //     ->where('forwarder.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')->where('masterhscode.aktif', 'Y')
        //     ->get();

        $getbook = modelforwarder::join('po', 'po.id', 'forwarder.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->join('formpo', 'formpo.idforwarder', 'forwarder.id_forwarder')
            ->join('masterroute', 'masterroute.id_route', 'formpo.idroute')
            ->join('masterportofloading', 'masterportofloading.id_portloading', 'formpo.idportloading')
            ->join('masterportofdestination', 'masterportofdestination.id_portdestination', 'formpo.idportdestination')
            ->where('forwarder.po_nomor', $request->id)
            ->where('forwarder.idmasterfwd', $request->idmasterfwd)
            ->where('masterforwarder.kurir', NULL)
            ->selectRaw(' forwarder.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.colorcode, po.size, po.style, po.plant, masterforwarder.name, mastersupplier.nama, masterhscode.hscode, formpo.kode_booking, formpo.date_booking, formpo.etd, formpo.eta, formpo.shipmode, formpo.subshipmode, formpo.package, formpo.created_at, formpo.updated_at, masterroute.route_code, masterroute.route_desc, masterportofloading.code_port, masterportofloading.name_port, masterportofdestination.code_port, masterportofdestination.name_port ')
            ->where('forwarder.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')->where('masterhscode.aktif', 'Y')->where('formpo.aktif', 'Y')->where('masterroute.aktif', 'Y')->where('masterportofloading.aktif', 'Y')->where('masterportofdestination.aktif', 'Y')
            ->groupBy('kode_booking')
            ->get();

        $getperbook = [];
        foreach ($getbook as $key1 => $val1) {
            $getpb = modelformpo::join('po', 'po.id', 'formpo.idpo')
                ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
                ->where('po.pono', $request->id)
                ->where('formpo.idmasterfwd', $val1->idmasterfwd)
                ->where('formpo.kode_booking', $val1->kode_booking)
                ->where('formpo.aktif', 'Y')->where('masterhscode.aktif', 'Y')
                ->selectRaw(' po.matcontents, po.itemdesc, po.qtypo, po.colorcode, po.size, masterhscode.hscode, formpo.qty_booking')
                ->get();
            array_push($getperbook, $getpb);
        }

        $getdata = modelforwarder::withCount(['formpo as qtybook' => function ($var) {
            $var->select(DB::raw('sum(qty_booking)'));
        }])
            ->join('po', 'po.id', 'forwarder.idpo')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->where('forwarder.po_nomor', $request->id)
            ->where('forwarder.idmasterfwd', $request->idmasterfwd)
            ->where('forwarder.aktif', 'Y')->where('masterhscode.aktif', 'Y')
            ->selectRaw(' forwarder.id_forwarder, forwarder.po_nomor, forwarder.idmasterfwd, po.matcontents, po.itemdesc, po.qtypo, po.colorcode, po.size, masterhscode.hscode')
            ->get();

        $form = view('report::readyallocation.modalreportalokasi', ['data' => $getdata, 'getbooking' => $getbook, 'getperbooking' => $getperbook]);
        return $form->render();
    }

    function excelalokasi($id)
    {
        $getdata = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('masterforwarder', 'masterforwarder.id', 'formpo.idmasterfwd')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
            ->where('formpo.kode_booking', $id)
            ->where('formpo.aktif', 'Y')->where('masterforwarder.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->where('masterhscode.aktif', 'Y')
            ->where('masterforwarder.kurir', NULL)
            ->selectRaw(' formpo.*, po.pono, po.matcontents, po.itemdesc, po.qtypo, po.style, po.plant, po.colorcode, po.size, masterforwarder.name, mastersupplier.nama, masterhscode.hscode ')
            ->get();

        $getdate = modelformpo::join('po', 'po.id', 'formpo.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->where('formpo.kode_booking', $id)
            ->where('formpo.aktif', 'Y')->where('mastersupplier.aktif', 'Y')
            ->selectRaw(' formpo.id_formpo, formpo.created_at, formpo.updated_at')
            ->latest('id_formpo')->first();
        // dd($getdate);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2:G2')->getFont()->setBold(true);

        //single header
        $sheet->setCellValue('A4', 'PO');
        $sheet->setCellValue('A5', 'Supplier');
        $sheet->getStyle('A4:A5')->getFont()->setBold(true);
        $sheet->setCellValue('C4', 'Forwarder');
        $sheet->setCellValue('C5', 'Input Data');
        $sheet->setCellValue('C6', 'Update Data');
        $sheet->getStyle('C4:C6')->getFont()->setBold(true);

        //for header
        $cellheader = 'A9:G9';
        $sheet->setCellValue('A9', 'Code Booking');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue('B9', 'ETD');
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->setCellValue('C9', 'ETA');
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->setCellValue('D9', 'Shipmode');
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->setCellValue('E9', '');
        $sheet->getColumnDimension('E')->setAutoSize(true);
        if ($getdata[0]->shipmode == 'fcl') {
            $sheet->setCellValue('E9', 'Container Size');
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->setCellValue('F9', 'Volume');
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->setCellValue('G9', 'Weight');
            $sheet->getColumnDimension('G')->setAutoSize(true);
        } else {
            $sheet->setCellValue('E9', 'Volume');
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->setCellValue('F9', 'Weight');
            $sheet->getColumnDimension('F')->setAutoSize(true);
        }
        $sheet->getStyle($cellheader)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cellheader)->getFont()->setBold(true);
        $sheet->getStyle($cellheader)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($cellheader)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($cellheader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($cellheader)->getFill()->getStartColor()->setARGB('ff8400');

        //for data
        $celldata = 'A12:F12';
        $sheet->setCellValue('A12', 'Material');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue('B12', 'Material Desc');
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->setCellValue('C12', 'HS Code ');
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->setCellValue('D12', 'Color');
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->setCellValue('E12', 'Size');
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->setCellValue('F12', 'Qty PO');
        $sheet->getColumnDimension('F')->setAutoSize(true);
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
        $sheet->setCellValue('A' . '2', strtoupper('Detail Ready Allocation'));

        //single header
        $sheet->setCellValue('B' . '4', ':' . $getdata[0]->pono);
        $sheet->setCellValue('B' . '5', ':' . $getdata[0]->nama);
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
        $sheet->setCellValue('B' . $header, date('d F Y', strtotime($getdata[0]->etd)));
        $sheet->setCellValue('C' . $header, date('d F Y', strtotime($getdata[0]->eta)));
        $sheet->setCellValue('D' . $header, $getdata[0]->shipmode);
        if ($getdata[0]->shipmode == 'fcl') {
            $exp = explode('-', $getdata[0]->subshipmode);
            $sheet->setCellValue('E' . $header, ($exp[0] == '40hq') ? '40hq' : $exp[0] . '"');
            $sheet->setCellValue('F' . $header, $exp[1] . 'M3');
            $sheet->setCellValue('G' . $header, $exp[2]);
        } else {
            $exp2 = explode('-', $getdata[0]->subshipmode);
            $sheet->setCellValue('E' . $header, $exp2[0]);
            $sheet->setCellValue('F' . $header, $exp2[1]);
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
            $bodydata++;
        }

        if ($getdata[0]->shipmode == 'fcl') {
            $cellheader = 'A9:G' . ($header - 1);
        } else {
            $cellheader = 'A9:F' . ($header - 1);
        }

        $celldata = 'A12:F' . ($bodydata - 1);
        $sheet->getStyle('A2:G2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cellheader)->applyFromArray($styleArray);
        $sheet->getStyle($celldata)->applyFromArray($styleArray);
        $sheet->getStyle($cellheader)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle($celldata)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Report_Ready_Allocation_" . $getdata[0]->kode_booking . ".xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');

        return;
    }

    function excelalokasiall(Request $request)
    {
        $where = '';
        if ($request->pino != NULL) {
            $where .= ' AND po.pino="' . $request->pino . '"';
        }
        if ($request->pono != NULL) {
            $where .= ' AND po.pono="' . $request->pono . '"';
        }
        if ($request->idmasterfwd != NULL) {
            $where .= ' AND forwarder.idmasterfwd=' . $request->idmasterfwd . ' ';
        }
        if ($request->idsupplier != NULL) {
            $imp = implode("','", $request->idsupplier);
            $where .= " AND po.vendor IN ('" . $imp . "')";
        }
        if ($request->periode != NULL) {
            $periode = explode(" - ", $request->periode);
            $where .= ' AND (forwarder.created_at BETWEEN "' . $periode[0] . '" AND "' . $periode[1] . '")';
        }

        $getdata = modelforwarder::with(['formpo'])
            ->join('po', 'po.id', 'forwarder.idpo')
            ->join('mastersupplier', 'mastersupplier.id', 'po.vendor')
            ->join('masterforwarder', 'masterforwarder.id', 'forwarder.idmasterfwd')
            ->selectRaw(' forwarder.*,  po.pono,  po.pino, po.podate, po.shipmode, po.curr, po.vendor, po.price, po.qtypo, SUM(po.price * po.qtypo) as amount, po.pideldate, mastersupplier.nama, masterforwarder.name')
            ->whereRaw(' forwarder.aktif="Y" AND mastersupplier.aktif="Y" AND masterforwarder.aktif="Y" AND masterforwarder.kurir IS NULL ' . $where . ' ')
            ->groupby('forwarder.po_nomor')->groupby('forwarder.idmasterfwd')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $cell = 'A4:M4';
        $sheet->mergeCells('A2:M2');
        $sheet->setCellValue('A4', 'PI');
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->setCellValue('B4', 'PO');
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->setCellValue('C4', 'Date');
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->setCellValue('D4', 'Amount');
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->setCellValue('E4', 'Supplier');
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->setCellValue('F4', 'Shipmode');
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->setCellValue('G4', 'Forwarder');
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->setCellValue('H4', 'Date Allocation');
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->setCellValue('I4', 'PI Delivery');
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->setCellValue('J4', 'Date Booking');
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->setCellValue('K4', 'Date Confirm');
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->setCellValue('L4', 'Date Shipment');
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->setCellValue('M4', 'Status');
        $sheet->getColumnDimension('M')->setAutoSize(true);

        $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        $sheet->getStyle($cell)->getFont()->setBold(true);
        $sheet->getStyle('A2:M2')->getFont()->setBold(true);
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

        $sheet->setCellValue('A' . '2', strtoupper('Report Ready Allocation'));

        foreach ($getdata as $key => $val) {
            $sheet->setCellValue('A' . $rows, $val->pino);
            $sheet->setCellValue('B' . $rows, $val->pono);
            $sheet->setCellValue('C' . $rows, date("d/m/Y", strtotime($val->podate)));
            $sheet->setCellValue('D' . $rows, round($val->amount, 3) . ' ' . $val->curr);
            $sheet->setCellValue('E' . $rows, $val->nama);
            if ($val['formpo']) {
                $ship = $val['formpo']['shipmode'];
            } else {
                $ship = $val->shipmode;
            }
            $sheet->setCellValue('F' . $rows, $ship);
            $sheet->setCellValue('G' . $rows, $val->name);
            $sheet->setCellValue('H' . $rows, ($val->created_at == null) ? '' : date("d/m/Y", strtotime($val->created_at)));
            $sheet->setCellValue('I' . $rows, date("d/m/Y", strtotime($val->pideldate)));
            $sheet->setCellValue('J' . $rows, ($val['formpo'] == null) ? '' : $val['formpo']->date_booking);
            $sheet->setCellValue('K' . $rows, ($val->date_fwd == null) ? '' : date("d/m/Y", strtotime($val->date_fwd)));

            $shipdate = '';
            if ($val['formpo'] != NULL) {
                $idformpo = $val['formpo']['id_formpo'];
                $getshipment = modelformshipment::where('idformpo', $idformpo)->where('aktif', 'Y')->pluck('created_at');
                $dateshipment = str_replace("[", "", str_replace("]", "", str_replace('"', "", $getshipment)));
                $shipdate = $dateshipment == '' ? '' : date("d/m/Y", strtotime($dateshipment));
            }
            $sheet->setCellValue('L' . $rows, $shipdate);

            $stat = '';
            $cekshipment = 0;
            if ($val->statusapproval == NULL) {
                $stat = 'Waiting';
            }
            if ($val->statusapproval == 'waiting' || $val->statusapproval == 'reject') {
                $stat = 'Booked';
            }
            if ($val->statusapproval == 'confirm') {
                $stat = 'Confirmed';
            }

            //cekshipment
            if ($val['formpo'] != NULL) {
                $idformpo = $val['formpo']['id_formpo'];
                $cekshipment = modelformshipment::where('idformpo', $idformpo)->where('aktif', 'Y')->count();
            }
            if ($cekshipment > 0) {
                $stat = 'Shipment';
            }
            $sheet->setCellValue('M' . $rows, $stat);
            $rows++;
        }

        $cell = 'A4:M' . ($rows - 1);
        $sheet->getStyle('A2:M2')->applyFromArray($styleArraytitle);
        $sheet->getStyle($cell)->applyFromArray($styleArray);
        $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $fileName = "Report_Ready_Allocation_All.xlsx";

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        $writer->save('php://output');
    }
}
