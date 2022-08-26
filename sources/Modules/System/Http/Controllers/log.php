<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Routing\Controller;

use Yajra\Datatables\Datatables;
use Modules\System\Models\modeltlog;

class log extends Controller{
    public function __construct(){
        $this->middleware('checklogin');
    }

    public function index(){
        $data = array(
            'title'     => 'Log Activities',
            'menu'      => '',
        );
        return view('system::settings/log_activity/log_list_serverside', $data);
    }

    public function log(){
        $log_query = modeltlog::
                        orderBy('date', 'DESC')
                        ->orderBy('time', 'DESC')
                        ->get();

        return Datatables::of($log_query)
                ->addIndexColumn()
                ->addColumn('datetime', function($q){
                    return date('d M Y', strtotime($q->date)).' <span class="badge bg-warning">'. date('H:i:s', strtotime($q->time)).'</span>';                    
                })
                ->rawColumns(['datetime'])
                ->make(true);
    }
}
