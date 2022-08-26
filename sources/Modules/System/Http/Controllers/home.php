<?php
namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;

use Modules\System\Models\Privileges\modelmenu;

class home extends Controller{
    public function __construct(){
        $this->middleware('checklogin');
    }

    public function index(){
        $data = array(
            'title' => 'Dashboard',
            'menu'  => 'dashboard',
            'box'   => '',
        );
        return view('system::dashboard/dashboard', $data);
    }
}
