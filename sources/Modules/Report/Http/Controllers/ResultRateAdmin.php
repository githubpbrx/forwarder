<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\System\Helpers\LogActivity;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Modules\Transaksi\Models\modelinputratefcl;
use Modules\Transaksi\Models\modelmappingratefcl;

class ResultRateAdmin extends Controller
{
    protected $micro;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->micro = microtime(true);
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $year = modelmappingratefcl::groupby('tgl')->where('aktif', 'Y')->selectRaw('DATE_FORMAT(periodeawal, "%Y") as tgl')->get();

        $month = [];
        for ($m = 1; $m <= 12; $m++) {
            $dt = $m;
            if ($m < 10) {
                $dt = '0' . $m;
            }
            $month[$dt] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }

        $data = array(
            'title'  => 'List Result Rate FCL',
            'menu'   => 'resultratefcladmin',
            'box'    => '',
            'year'  => $year,
            'month'  => $month
        );

        LogActivity::addToLog('Web Forwarder :: Forwarder : Access Menu Result Rate FCL Admin', $this->micro);
        return view('report::resultfcladmin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function getreport(Request $request)
    {
        // dd($request);
        $month1 = (int)$request->month . '-01';
        $date = $request->year . '-' . $month1;
        Session::put(['dateku' => $date]);

        $idfwd = modelinputratefcl::with(['masterfwd'])->groupby('id_forwarder')->where('aktif', 'Y')->pluck('id_forwarder'); //get id forwarder
        $master = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('periodeawal', 'LIKE', '%' . $date . '%')->where('aktif', 'Y')->orderby('id_country', 'asc')->get(); //get all data
        // dd($master);

        $data = [];
        foreach ($master as $keys => $val) {
            $datafcl = modelinputratefcl::whereIn('id_forwarder', $idfwd)->where('id_mappingrate', $val->id)->where('aktif', 'Y')->get();

            $datafwd = modelinputratefcl::with(['masterfwd'])->where('id_mappingrate', $val->id)->groupby('id_forwarder')->where('aktif', 'Y')->get(); //get id forwarder

            if ($datafcl) {
                foreach ($datafwd as $key2 => $val2) {
                    $da[$val2->id_forwarder] = [];
                    foreach ($datafcl as $key => $val3) {
                        if ($val3->id_forwarder == $val2->id_forwarder) {
                            $d['of_20']   = $val3->of_20;
                            $d['of_40']   = $val3->of_40;
                            $d['of_40hc'] = $val3->of_40hc;
                            $d['lb_20']   = $val3->lb_20;
                            $d['lb_40']   = $val3->lb_40;
                            $d['lb_40hc'] = $val3->lb_40hc;

                            array_push($da[$val2->id_forwarder], $d);
                            unset($d);
                        }
                    }
                }
            }
            array_push($data, $da);
            unset($da);
        }

        $datamin = array();
        foreach ($master as $keys => $vm) {
            $datainput = modelinputratefcl::with(['masterfwd'])->where('id_mappingrate', $vm->id)->where('aktif', 'Y')->get(['id_forwarder', 'of_20 as minof20', 'of_40 as minof40', 'of_40hc as minof40hc', 'lb_20 as minlb20', 'lb_40 as minlb40', 'lb_40hc as minlb40hc']);
            if ($datainput == null) {
                $db['minof_20']   = '-';
                $db['minof_40']   = '-';
                $db['minof_40hc'] = '-';
                $db['minlb_20']   = '-';
                $db['minlb_40']   = '-';
                $db['minlb_40hc'] = '-';
            } else {
                $db['minof_20']   = $this->whereData($datainput, 'minof20');
                $db['minof_40']   = $this->whereData($datainput, 'minof40');
                $db['minof_40hc'] = $this->whereData($datainput, 'minof40hc');
                $db['minlb_20']   = $this->whereData($datainput, 'minlb20');
                $db['minlb_40']   = $this->whereData($datainput, 'minlb40');
                $db['minlb_40hc'] = $this->whereData($datainput, 'minlb40hc');
            }
            // dump($db);
            array_push($datamin, $db);
            unset($db);
        }
        // dd($datamin);
        // $masters = modelmappingratefcl::where('periodeawal', 'LIKE', '%' . $date . '%')->where('aktif', 'Y')->selectraw('count(id) as jml, id_country')->groupby('id_country')->orderby('id_country', 'asc')->get();
        // dd($masters);
        // $masterpol = modelmappingratefcl::whereIn('id_country', ['1', '4'])->selectraw('count(id) as jml, id_polcity')->groupby('id_polcity')->orderby('id_polcity', 'asc')->where('aktif', 'Y')->get();
        // dd($masterpol);

        $data = array(
            'data'   => $data,
            'fwd'    => $datafwd,
            'master' => $master,
            'min'    => $datamin,
            // 'row'    => $masters,
            // 'rowpol' => $masterpol
        );

        $form = view('report::resultfcladmin.getresult', $data);
        return $form->render();
    }

    public function whereData($datainput, $label)
    {
        $datas = $datainput->where("$label", '!=', '')->sortBy("$label")->first();
        $arr = $datas ? $datas->toArray() : [];

        // if (count($arr) > 0) {
        //     $res = array_values(array_filter($datainput->toArray(), function ($val) use ($label, $arr) {
        //         return $val[$label] == $arr[$label];
        //     }));

        //     $name = [];
        //     foreach ($res as $keyR => $ress) {
        //         $name[] = $ress['masterfwd']['name'];
        //     }

        //     $implode = implode(' - ', $name);
        //     $arr['masterfwd']['name'] = $implode;
        // }

        return count($arr) > 0 ? $arr : $this->dataisNull();
    }

    public function dataisNull()
    {
        $db['masterfwd']  = null;
        $db['minof20']   = null;
        $db['minof40']   = null;
        $db['minof40hc'] = null;
        $db['minlb20']   = null;
        $db['minlb40']   = null;
        $db['minlb40hc'] = null;
        return $db;
    }

    public function getexcel()
    {
        $date = Session::get('dateku');
        $idfwd = modelinputratefcl::with(['masterfwd'])->groupby('id_forwarder')->where('aktif', 'Y')->pluck('id_forwarder');
        $master = modelmappingratefcl::with(['country', 'polcity', 'podcity', 'shipping'])->where('periodeawal', 'LIKE', '%' . $date . '%')->where('aktif', 'Y')->orderby('id_country', 'asc')->get();

        $data = [];
        foreach ($master as $keys => $val) {
            $datafcl = modelinputratefcl::whereIn('id_forwarder', $idfwd)->where('id_mappingrate', $val->id)->where('aktif', 'Y')->get();

            $datafwd = modelinputratefcl::with(['masterfwd'])->where('id_mappingrate', $val->id)->groupby('id_forwarder')->where('aktif', 'Y')->get(); //get id forwarder

            if ($datafcl) {
                foreach ($datafwd as $key2 => $val2) {
                    $da[$val2->id_forwarder] = [];
                    foreach ($datafcl as $key => $val3) {
                        if ($val3->id_forwarder == $val2->id_forwarder) {
                            $d['of_20']   = $val3->of_20;
                            $d['of_40']   = $val3->of_40;
                            $d['of_40hc'] = $val3->of_40hc;
                            $d['lb_20']   = $val3->lb_20;
                            $d['lb_40']   = $val3->lb_40;
                            $d['lb_40hc'] = $val3->lb_40hc;

                            array_push($da[$val2->id_forwarder], $d);
                            unset($d);
                        }
                    }
                }
            }
            array_push($data, $da);
            unset($da);
        }

        $datamin = array();
        foreach ($master as $keys => $vm) {
            $datainput = modelinputratefcl::with(['masterfwd'])->where('id_mappingrate', $vm->id)->where('aktif', 'Y')->get(['id_forwarder', 'of_20 as minof20', 'of_40 as minof40', 'of_40hc as minof40hc', 'lb_20 as minlb20', 'lb_40 as minlb40', 'lb_40hc as minlb40hc']);
            if ($datainput == null) {
                $db['minof_20']   = '-';
                $db['minof_40']   = '-';
                $db['minof_40hc'] = '-';
                $db['minlb_20']   = '-';
                $db['minlb_40']   = '-';
                $db['minlb_40hc'] = '-';
            } else {
                $db['minof_20']   = $this->whereData($datainput, 'minof20');
                $db['minof_40']   = $this->whereData($datainput, 'minof40');
                $db['minof_40hc'] = $this->whereData($datainput, 'minof40hc');
                $db['minlb_20']   = $this->whereData($datainput, 'minlb20');
                $db['minlb_40']   = $this->whereData($datainput, 'minlb40');
                $db['minlb_40hc'] = $this->whereData($datainput, 'minlb40hc');
            }
            // dump($db);
            array_push($datamin, $db);
            unset($db);
        }

        $data = array(
            'data'   => $data,
            'fwd'    => $datafwd,
            'master' => $master,
            'min'    => $datamin,
            // 'row'    => $masters,
            // 'rowpol' => $masterpol
        );

        return view('report::resultfcladmin.excel', $data);
        // return $form->render();

        // $spreadsheet = new Spreadsheet();
        // $sheet = $spreadsheet->getActiveSheet();
        // $cell = 'A4:E4';
        // $sheet->mergeCells('A2:E2');
        // $sheet->setCellValue('A4', 'Country');
        // $sheet->mergeCells('A4:A6');
        // $sheet->getColumnDimension('A')->setAutoSize(true);
        // $sheet->setCellValue('B4', 'POL City');
        // $sheet->mergeCells('B4:B6');
        // $sheet->getColumnDimension('B')->setAutoSize(true);
        // $sheet->setCellValue('C4', 'POD City');
        // $sheet->mergeCells('C4:C6');
        // $sheet->getColumnDimension('C')->setAutoSize(true);
        // $sheet->setCellValue('D4', 'Shipping Line');
        // $sheet->mergeCells('D4:D6');
        // $sheet->getColumnDimension('D')->setAutoSize(true);
        // $sheet->setCellValue('E4', 'Effective Date');
        // $sheet->mergeCells('E4:E5');
        // $sheet->mergeCells('F4:F5');
        // $sheet->mergeCells('E4:F4');
        // $sheet->getColumnDimension('E')->setAutoSize(true);
        // $sheet->setCellValue('E6', 'From');
        // $sheet->getColumnDimension('E')->setAutoSize(true);
        // $sheet->setCellValue('F6', 'End');
        // $sheet->getColumnDimension('F')->setAutoSize(true);


        // $sheet->getStyle($cell)->getAlignment()->setWrapText(true);
        // $sheet->getStyle($cell)->getFont()->setBold(true);
        // $sheet->getStyle('A2:E2')->getFont()->setBold(true);
        // $sheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        // $sheet->getStyle($cell)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        // $sheet->getStyle($cell)->getFill()->getStartColor()->setARGB('ff8400');

        // $rows = 5;
        // // BORDER STYLE
        // $styleArray = [
        //     'borders' => [
        //         'allBorders' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //         ],
        //     ],
        // ];

        // $styleArraytitle = [
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //     ],
        // ];

        // $sheet->setCellValue('A' . '2', strtoupper('List Result Rate FCL'));

        // // foreach ($getdata as $key => $val) {
        // //     $sheet->setCellValue('A' . $rows, $val->pono);
        // //     $sheet->setCellValue('B' . $rows, date("d/m/Y", strtotime($val->podate)));
        // //     $sheet->setCellValue('C' . $rows, round($val->amount, 3) . ' ' . $val->curr);
        // //     $sheet->setCellValue('D' . $rows, $val->nama);
        // //     if ($val['formpo']) {
        // //         $ship = $val['formpo']['shipmode'];
        // //     } else {
        // //         $ship = $val->shipmode;
        // //     }
        // //     $sheet->setCellValue('E' . $rows, $ship);
        // //     $sheet->setCellValue('F' . $rows, $val->name);
        // //     $sheet->setCellValue('G' . $rows, date("d/m/Y", strtotime($val->created_at)));
        // //     $sheet->setCellValue('H' . $rows, date("d/m/Y", strtotime($val->pideldate)));
        // //     $sheet->setCellValue('I' . $rows, ($val['formpo'] == null) ? '' : $val['formpo']->date_booking);
        // //     $sheet->setCellValue('J' . $rows, ($val->date_fwd == null) ? '' : date("d/m/Y", strtotime($val->date_fwd)));
        // //     if ($val->statusapproval == 'confirm') {
        // //         $stat = 'Confirmed';
        // //     } else if ($val->statusapproval == 'reject') {
        // //         $stat = 'Rejected';
        // //     } else if ($val->statusallocation == 'cancelled') {
        // //         $stat = 'Cancelled';
        // //     } else {
        // //         $stat = 'Waiting';
        // //     }
        // //     $sheet->setCellValue('K' . $rows, $stat);
        // //     $rows++;
        // // }

        // $cell = 'A4:E' . ($rows - 1);
        // $sheet->getStyle('A2:E2')->applyFromArray($styleArraytitle);
        // $sheet->getStyle($cell)->applyFromArray($styleArray);
        // $sheet->getStyle($cell)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // $fileName = "Report_List_Rate_FCL.xlsx";

        // $writer = new Xlsx($spreadsheet);
        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '"');
        // $writer->save('php://output');

        // return;
    }
}
