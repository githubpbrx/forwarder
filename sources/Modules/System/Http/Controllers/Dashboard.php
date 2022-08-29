<?php
namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;

use Modules\System\Http\Controllers\notifikasi,
    Modules\Car\Models\modelbooking,
    Modules\System\Models\Privileges\modelmenu,
    Modules\Car\Models\modelcar,
    Modules\Sisbook\Models\modelinn,
    Modules\Sisbook\Models\modeleat,
    Modules\Sisbook\Models\modelroom,
    Modules\Sisbook\Models\modelaccommodation,
    Modules\System\Models\modelsystem,
    Modules\Sisbook\Models\modelreqroom;

class Dashboard extends Controller{

	
    public function index($id){
    	$param = modelsystem::first();
        session(['sesId'=>$id]);
        $counterkolom1 = modelcar::whereRaw('  car_location="'.session('sesId').'" ')->count();
        //1 mespanorama, 5 mesyayasan
        $iinp = modelinn::where('inn_mess_id',1)->count(); //messpanorama
        $iiny = modelinn::where('inn_mess_id',5)->count(); //messyayasan
        $counterroom = modelroom::whereRaw('  room_location="'.session('sesId').'" ')->count();
        // dd($iinp, $iiny);
        return view('system::dash/index',['id'=>$id, 'counterkolom1'=>$counterkolom1, 'counterpanorama'=>$iinp, 'counteryayasan'=>$iiny, 'counterroom'=>$counterroom, 'param'=>$param]);
    }

    public function notifkolom1(){ //mobil

    	$total = 0;
		$booking = modelbooking::where('booking_status', '=', 0)->where('booking_factory', session('sesId'))->groupby('booking_applicant')->count();

		return $booking;
    }
    public function notifkolom2(){ //runagan

    	$total = 0;
		$booking = modelreqroom::where('reqroom_status', '=', 0)->where('reqroom_location', session('sesId'))->groupby('reqroom_request_id')->count();

		return $booking;
    }
    public function notifkolom3(){ //mess

    	$total = 0;
		$booking = modelaccommodation::where('accommodation_status', '=', 0)->where('accommodation_location', session('sesId'))->groupby('accommodation_request_id')->count();

		return $booking;
    }
    public function notifkolom4(){ //makan

    	$total = 0;
		$booking = modeleat::where('eat_status', '=', 0)->where('eat_location', session('sesId'))->groupby('eat_request_id')->count();

		return $booking;
    }

    public function kolom1($page){
    	// $db = modelbooking::with(['driver','requests'])->where('booking_car_id',30)->whereRaw(' DATE(booking_start_date)="'.date('Y-m-d').'" ')->orderby('booking_start_date','asc')->get();
    	// dd($db);

        $counterkolom1 = modelcar::whereRaw('  car_location="'.session('sesId').'" ')->count();
        $cek = $page+5;
        $driver2 = [];
    	$user2 = [];
    	$destination2 = [];
    	$time2 = [];
    	$data2 = array();
        if($cek>$counterkolom1){
        	$dt = $cek-$counterkolom1;
        	$data2 = modelcar::whereRaw('  car_location="'.session('sesId').'" order by car_id asc LIMIT 0,'.$dt.' ')->get();
        	$driver2 = [];
	    	$user2 = [];
	    	$destination2 = [];
	    	$time2 = [];
	    	foreach($data2 as $key => $r){
	    		$db2 = modelbooking::with(['driver','request'])->where('booking_car_id',$r->car_id)->whereRaw(' DATE(booking_start_date)="'.date('Y-m-d').'" ')->orderby('booking_start_date','asc')->get();

	    		if(count($db2)==0){
	    			$driver2[] = '';
			    	$user2[] = '';
			    	$destination2[] = '';
			    	$time2[] = '';
	    		}else{
	    			$namadriver2 = [];
	    			$namareq2 = [];
	    			$jam2 = [];
	    			$tujuan2 = [];
	    			foreach($db2 as $kk => $rr){
	    				$namad = ($rr->driver==null) ? '' : $rr->driver->driver_name;
	    				if($namad!=''){
	    					$eps = explode(" ",$namad);
	    					$namad = $eps[0];
	    				}
	    				$namadriver2[] = $namad;
	    				$nam = ($rr->request==null) ? '' : $rr->request->request_user_name;
	    				if($nam!=''){
	    					$exp = explode(" ",$nam);
	    					$nam2 = '';
	    					if(isset($exp[1])){
	    						$nam2 = $exp[1];
	    					}
	    					$nam = $exp[0].' '.$nam2;
	    				}
	    				$namereq2[] = ($rr->request==null) ? '' : $nam." | ".$rr->request->request_user_position;

	    				$jam1 = date('H:i', strtotime($rr->booking_start_date));
	    				$jam_2 = date('H:i', strtotime($rr->booking_end_date));
	    				$jam2[] = $jam1.' - '.$jam_2;
	    				$tujuan2[] = $rr->booking_purpose;
	    			}

	    			$driver2[] = (count($namadriver2)==0) ? '' : implode("<br>",$namadriver2);
			    	$user2[] = (count($namereq2)==0) ? '' : implode("<br>",$namereq2);
			    	$destination2[] = (count($tujuan2)==0) ? '' : implode("<br>",$tujuan2);
			    	$time2[] = (count($jam2)==0) ? '' : implode("<br>",$jam2);
	    		}

	    		unset($namadriver2);
	    		unset($namereq2);
	    		unset($jam2);
	    		unset($tujuan2);

	    	}

        }
    	$pageku = $page-1;
    	$data = modelcar::whereRaw(' car_location="'.session('sesId').'" order by car_id asc LIMIT '.$pageku.',6 ')->get();
    	$driver = [];
    	$user = [];
    	$destination = [];
    	$time = [];
    	foreach($data as $key => $r){
    		$db = modelbooking::with(['driver','request'])->where('booking_car_id',$r->car_id)->whereRaw(' DATE(booking_start_date)="'.date('Y-m-d').'" ')->orderby('booking_start_date','asc')->get();

    		if(count($db)==0){
    			$driver[] = '';
		    	$user[] = '';
		    	$destination[] = '';
		    	$time[] = '';
    		}else{
    			$namadriver = [];
    			$namareq = [];
    			$jam = [];
    			$tujuan = [];
    			foreach($db as $kk => $rr){
    				$namad = ($rr->driver==null) ? '' : $rr->driver->driver_name;
    				if($namad!=''){
    					$eps = explode(" ",$namad);
    					$namad = $eps[0];
    				}
    				$namadriver[] = $namad;
    				$nam = ($rr->request==null) ? '' : $rr->request->request_user_name;
    				if($nam!=''){
    					$exp = explode(" ",$nam);
    					$nam2 = '';
    					if(isset($exp[1])){
    						$nam2 = $exp[1];
    					}
    					$nam = $exp[0].' '.$nam2;
    				}
    				$namereq[] = ($rr->request==null) ? '' : $nam." | ".$rr->request->request_user_position;

    				$jam1 = date('H:i', strtotime($rr->booking_start_date));
    				$jam2 = date('H:i', strtotime($rr->booking_end_date));
    				$jam[] = $jam1.' - '.$jam2;
    				$tujuan[] = $rr->booking_purpose;
    			}

    			$driver[] = (count($namadriver)==0) ? '' : implode("<br>",$namadriver);
		    	$user[] = (count($namereq)==0) ? '' : implode("<br>",$namereq);
		    	$destination[] = (count($tujuan)==0) ? '' : implode("<br>",$tujuan);
		    	$time[] = (count($jam)==0) ? '' : implode("<br>",$jam);
    		}

    		unset($namadriver);
    		unset($namereq);
    		unset($jam);
    		unset($tujuan);

    	}
    	// dd($driver);
    	// dd($data, $datadetail);
    	$form = view('system::dash/kolom1', ['data' => $data, 'driver'=>$driver, 'time'=>$time, 'user'=>$user, 'destination'=>$destination, 'data2'=>$data2, 'driver2'=>$driver2, 'time2'=>$time2, 'user2'=>$user2, 'destination2'=>$destination2]);
        return $form->render();
    }

    public function kolompanorama($page){
    	$counterpanorama = modelinn::where('inn_mess_id',1)->count();
        $cek = $page+7;
        $data2 = array();
        $user2 = [];
	    $tanggal2 = [];
        if($cek>$counterpanorama AND $counterpanorama>=8){
        	$dt = $cek-$counterpanorama;
        	$data2 = modelinn::whereRaw('  inn_mess_id="1" order by inn_room_number asc LIMIT 0,'.$dt.' ')->get();
        	$tgl2 = [];
	    	$requ2 = [];
	    	$user2 = [];
	    	$tanggal2 = [];
	    	foreach($data2 as $key => $r){
	    		$dd2 = modelaccommodation::with('request')->whereRaw(' accommodation_inn_id like "%'.$r->inn_id.'%" and accommodation_date="'.date('Y-m-d').'" ')->get();
	    		$tgl2 = [];
	    		$requ2 = [];
	    		
	    		foreach($dd2 as $k => $rr){
	    			$tgl2[] = ($rr->request==null) ? '' : $rr->request->request_start_date.' s.d '.$rr->request->request_end_date;
	    			$nam = ($rr->request==null) ? '' : $rr->request->request_user_name;
					if($nam!=''){
						$exp = explode(" ",$nam);
						$nam2 = '';
						if(isset($exp[1])){
							$nam2 = $exp[1];
						}
						$nam = $exp[0].' '.$nam2;
					}

	    			$requ2[] = ($rr->request==null) ? '' : $nam.' | '.$rr->request->request_user_position;
	    		}

	    		$user2[] = implode("<br>",$requ2);
	    		$tanggal2[] = implode("<br>",$tgl2);

	    		unset($tgl2);
	    		unset($requ2);
	    	}
        }
    	$pageku = $page-1;
    	if($counterpanorama<=8){
    		$pageku = 0;
    	}
    	$data = modelinn::whereRaw(' inn_mess_id="1" order by inn_room_number asc LIMIT '.$pageku.',8 ')->get();
    	$tgl = [];
    	$requ = [];
    	$user = [];
    	$tanggal = [];
    	foreach($data as $key => $r){
    		$dd = modelaccommodation::with('request')->whereRaw(' accommodation_inn_id like "%'.$r->inn_id.'%" and accommodation_date="'.date('Y-m-d').'" ')->get();
    		$tgl = [];
    		$requ = [];
    		
    		foreach($dd as $k => $rr){
    			$tgl[] = ($rr->request==null) ? '' : $rr->request->request_start_date.' s.d '.$rr->request->request_end_date;
    			$nam = ($rr->request==null) ? '' : $rr->request->request_user_name;
				if($nam!=''){
					$exp = explode(" ",$nam);
					$nam2 = '';
					if(isset($exp[1])){
						$nam2 = $exp[1];
					}
					$nam = $exp[0].' '.$nam2;
				}

    			$requ[] = ($rr->request==null) ? '' : $nam.' | '.$rr->request->request_user_position;
    		}

    		$user[] = implode("<br>",$requ);
    		$tanggal[] = implode("<br>",$tgl);

    		unset($tgl);
    		unset($requ);
    	}

    	$form = view('system::dash/panorama', ['data' => $data, 'data2'=>$data2, 'tanggal'=>$tanggal, 'user'=>$user, 'tanggal2'=>$tanggal2, 'user2'=>$user2]);
        return $form->render();
    }

    public function kolomyayasan($page){
    	$counterpanorama = modelinn::where('inn_mess_id',5)->count();
        $cek = $page+7;
        $data2 = array();
        $user2 = [];
	    $tanggal2 = [];
        if($cek>$counterpanorama AND $counterpanorama>=8){
        	$dt = $cek-$counterpanorama;
        	$data2 = modelinn::whereRaw('  inn_mess_id="5" order by inn_room_number asc LIMIT 0,'.$dt.' ')->get();
        	$tgl2 = [];
	    	$requ2 = [];
	    	$user2 = [];
	    	$tanggal2 = [];
	    	foreach($data2 as $key => $r){
	    		$dd2 = modelaccommodation::with('request')->whereRaw(' accommodation_inn_id like "%'.$r->inn_id.'%" and accommodation_date="'.date('Y-m-d').'" ')->get();
	    		$tgl2 = [];
	    		$requ2 = [];
	    		
	    		foreach($dd2 as $k => $rr){
	    			$tgl2[] = ($rr->request==null) ? '' : $rr->request->request_start_date.' s.d '.$rr->request->request_end_date;
	    			$nam = ($rr->request==null) ? '' : $rr->request->request_user_name;
					if($nam!=''){
						$exp = explode(" ",$nam);
						$nam2 = '';
						if(isset($exp[1])){
							$nam2 = $exp[1];
						}
						$nam = $exp[0].' '.$nam2;
					}

	    			$requ2[] = ($rr->request==null) ? '' : $nam.' | '.$rr->request->request_user_position;
	    		}

	    		$user2[] = implode("<br>",$requ2);
	    		$tanggal2[] = implode("<br>",$tgl2);

	    		unset($tgl2);
	    		unset($requ2);
	    	}
        }
    	$pageku = $page-1;
    	if($counterpanorama<=8){
    		$pageku = 0;
    	}
    	$data = modelinn::whereRaw(' inn_mess_id="5" order by inn_room_number asc LIMIT '.$pageku.',8 ')->get();
    	$tgl = [];
    	$requ = [];
    	$user = [];
    	$tanggal = [];
    	foreach($data as $key => $r){
    		$dd = modelaccommodation::with('request')->whereRaw(' accommodation_inn_id like "%'.$r->inn_id.'%" and accommodation_date="'.date('Y-m-d').'" ')->get();
    		$tgl = [];
    		$requ = [];
    		
    		foreach($dd as $k => $rr){
    			$tgl[] = ($rr->request==null) ? '' : $rr->request->request_start_date.' s.d '.$rr->request->request_end_date;
    			$nam = ($rr->request==null) ? '' : $rr->request->request_user_name;
				if($nam!=''){
					$exp = explode(" ",$nam);
					$nam2 = '';
					if(isset($exp[1])){
						$nam2 = $exp[1];
					}
					$nam = $exp[0].' '.$nam2;
				}

    			$requ[] = ($rr->request==null) ? '' : $nam.' | '.$rr->request->request_user_position;
    		}

    		$user[] = implode("<br>",$requ);
    		$tanggal[] = implode("<br>",$tgl);

    		unset($tgl);
    		unset($requ);
    	}

    	$form = view('system::dash/yayasan', ['data' => $data, 'data2'=>$data2, 'tanggal'=>$tanggal, 'user'=>$user, 'tanggal2'=>$tanggal2, 'user2'=>$user2]);
        return $form->render();
    }


    public function kolom2($page){
    	// return 'SINI';
    	$counterroom = modelroom::whereRaw('  room_location="'.session('sesId').'" ')->count();
        $cek = $page+5;
        $data2 = array();
        $batasatas2 = [];
    	$batasbawah2 = [];
    	$user2 = [];
    	$use2 = [];

        if($cek>$counterroom AND $counterroom>=6){
        	$dt = $cek-$counterroom;
        	$data2 = modelroom::whereRaw(' room_location="'.session('sesId').'" order by room_name asc LIMIT 0,'.$dt.' ')->get();
        	$batasatas2 = [];
	    	$batasbawah2 = [];
	    	$user2 = [];
	    	$use2 = [];
	    	foreach($data2 as $key => $r){
	    		$dd = modelreqroom::with('request')->selectRaw(" reqroom_request_id, reqroom_start_time,reqroom_end_time,TIMEDIFF(reqroom_end_time,reqroom_start_time) AS selisih,CASE WHEN reqroom_start_time BETWEEN '07:00' AND '07:59' THEN 1 WHEN reqroom_start_time BETWEEN '08:00' AND '08:59' THEN 2 WHEN reqroom_start_time BETWEEN '09:00' AND '09:59' THEN 3 WHEN reqroom_start_time BETWEEN '10:00' AND '10:59' THEN 4 WHEN reqroom_start_time BETWEEN '11:00' AND '11:59' THEN 5 WHEN reqroom_start_time BETWEEN '12:00' AND '12:59' THEN 6 WHEN reqroom_start_time BETWEEN '13:00' AND '13:59' THEN 7 WHEN reqroom_start_time BETWEEN '14:00' AND '14:59' THEN 8 WHEN reqroom_start_time BETWEEN '15:00' AND '15:59' THEN 9 WHEN reqroom_start_time BETWEEN '16:00' AND '16:59' THEN 10 WHEN reqroom_start_time BETWEEN '17:00' AND '18:00' THEN 11 ELSE 0 END AS mulai, CASE WHEN reqroom_end_time BETWEEN '07:00' AND '07:59' THEN 1 WHEN reqroom_end_time BETWEEN '08:00' AND '08:59' THEN 2 WHEN reqroom_end_time BETWEEN '09:00' AND '09:59' THEN 3 WHEN reqroom_end_time BETWEEN '10:00' AND '10:59' THEN 4 WHEN reqroom_end_time BETWEEN '11:00' AND '11:59' THEN 5 WHEN reqroom_end_time BETWEEN '12:00' AND '12:59' THEN 6 WHEN reqroom_end_time BETWEEN '13:00' AND '13:59' THEN 7 WHEN reqroom_end_time BETWEEN '14:00' AND '14:59' THEN 8 WHEN reqroom_end_time BETWEEN '15:00' AND '15:59' THEN 9 WHEN reqroom_end_time BETWEEN '16:00' AND '16:59' THEN 10 WHEN reqroom_end_time BETWEEN '17:00' AND '23:59' THEN 11 ELSE 0 END AS selesai ")->whereRaw(' reqroom_date = "'.date('Y-m-d').'" AND reqroom_status = 1 AND reqroom_room_id = "'.$r->room_id.'" ORDER BY reqroom_start_time ASC ')->get();
	    		if(count($dd)>0){
	    			foreach($dd as $k => $rr){
		    			$min2[] = $rr->mulai;
		    			//ceks
		    			$j = explode(":",$rr->selisih);
		    			if((int)$j[1]>0){
		    				$jj = (int)$j[0]+1;
		    			}else{
		    				$jj = (int)$j[0];
		    			}
		    			$max2[] = $jj;
		    			$nam = ($rr->request==null) ? '' : $rr->request->request_user_name;
						if($nam!=''){
							$exp = explode(" ",$nam);
							$nam2 = '';
							if(isset($exp[1])){
								$nam2 = $exp[1];
							}
							$nam = $exp[0].' '.$nam2;
						}

						if(date('H:i:s')>=$rr->reqroom_start_time AND date('H:i:s')<=$rr->reqroom_end_time){
							$uses2[] = 'yes';
						}else{
							$uses2[] = 'no';
						}
		    			$requ2[] = ($rr->request==null) ? '' : $nam.' | '.$rr->request->request_user_position;
		    		}
	    		}else{
	    			$min2[] = '';
	    			$max2[] = '';
	    			$requ2[] = '';
	    			$uses2[] = '';
	    		}
	    		
	    		

	    		$user2[] = $requ2;
	    		$use2[] = $uses2;
	    		$batasatas2[] = $min2;
	    		$batasbawah2[] = $max2;

	    		unset($min2);
	    		unset($max2);
	    		unset($requ2);
	    		unset($uses2);
	    	}
        }
    	$pageku = $page-1;
    	if($counterroom<=6){
    		$pageku = 0;
    	}
    	$data = modelroom::whereRaw(' room_location="'.session('sesId').'" order by room_name asc LIMIT '.$pageku.',6 ')->get();
    	$batasatas = [];
    	$batasbawah = [];
    	$user = [];
    	$use = [];
    	foreach($data as $key => $r){
    		$dd = modelreqroom::with('request')->selectRaw(" reqroom_request_id, reqroom_start_time,reqroom_end_time,TIMEDIFF(reqroom_end_time,reqroom_start_time) AS selisih,CASE WHEN reqroom_start_time BETWEEN '07:00' AND '07:59' THEN 1 WHEN reqroom_start_time BETWEEN '08:00' AND '08:59' THEN 2 WHEN reqroom_start_time BETWEEN '09:00' AND '09:59' THEN 3 WHEN reqroom_start_time BETWEEN '10:00' AND '10:59' THEN 4 WHEN reqroom_start_time BETWEEN '11:00' AND '11:59' THEN 5 WHEN reqroom_start_time BETWEEN '12:00' AND '12:59' THEN 6 WHEN reqroom_start_time BETWEEN '13:00' AND '13:59' THEN 7 WHEN reqroom_start_time BETWEEN '14:00' AND '14:59' THEN 8 WHEN reqroom_start_time BETWEEN '15:00' AND '15:59' THEN 9 WHEN reqroom_start_time BETWEEN '16:00' AND '16:59' THEN 10 WHEN reqroom_start_time BETWEEN '17:00' AND '18:00' THEN 11 ELSE 0 END AS mulai, CASE WHEN reqroom_end_time BETWEEN '07:00' AND '07:59' THEN 1 WHEN reqroom_end_time BETWEEN '08:00' AND '08:59' THEN 2 WHEN reqroom_end_time BETWEEN '09:00' AND '09:59' THEN 3 WHEN reqroom_end_time BETWEEN '10:00' AND '10:59' THEN 4 WHEN reqroom_end_time BETWEEN '11:00' AND '11:59' THEN 5 WHEN reqroom_end_time BETWEEN '12:00' AND '12:59' THEN 6 WHEN reqroom_end_time BETWEEN '13:00' AND '13:59' THEN 7 WHEN reqroom_end_time BETWEEN '14:00' AND '14:59' THEN 8 WHEN reqroom_end_time BETWEEN '15:00' AND '15:59' THEN 9 WHEN reqroom_end_time BETWEEN '16:00' AND '16:59' THEN 10 WHEN reqroom_end_time BETWEEN '17:00' AND '23:59' THEN 11 ELSE 0 END AS selesai ")->whereRaw(' reqroom_date = "'.date('Y-m-d').'" AND reqroom_status = 1 AND reqroom_room_id = "'.$r->room_id.'" ORDER BY reqroom_start_time ASC ')->get();
    		if(count($dd)>0){
    			foreach($dd as $k => $rr){
	    			$min[] = $rr->mulai;
	    			//ceks
	    			$j = explode(":",$rr->selisih);
	    			if((int)$j[1]>0){
	    				$jj = (int)$j[0]+1;
	    			}else{
	    				$jj = (int)$j[0];
	    			}
	    			$max[] = $jj;
	    			$nam = ($rr->request==null) ? '' : $rr->request->request_user_name;
					if($nam!=''){
						$exp = explode(" ",$nam);
						$nam2 = '';
						if(isset($exp[1])){
							$nam2 = $exp[1];
						}
						$nam = $exp[0].' '.$nam2;
					}

					if(date('H:i:s')>=$rr->reqroom_start_time AND date('H:i:s')<=$rr->reqroom_end_time){
						$uses[] = 'yes';
					}else{
						$uses[] = 'no';
					}
	    			$requ[] = ($rr->request==null) ? '' : $nam.' | '.$rr->request->request_user_position;
	    		}
    		}else{
    			$min[] = '';
    			$max[] = '';
    			$requ[] = '';
    			$uses[] = '';
    		}
    		
    		

    		$user[] = $requ;
    		$use[] = $uses;
    		$batasatas[] = $min;
    		$batasbawah[] = $max;

    		unset($min);
    		unset($max);
    		unset($requ);
    		unset($uses);
    	}

    	$form = view('system::dash/ruangan', ['data' => $data, 'data2'=>$data2, 'user'=>$user, 'batasatas'=>$batasatas, 'batasbawah'=>$batasbawah, 'use'=>$use, 'user2'=>$user2, 'batasatas2'=>$batasatas2, 'batasbawah2'=>$batasbawah2, 'use2'=>$use2]);
        return $form->render();
    }

    function kolom4($pages){
    	$data = array();
    	$now = date('Y-m-d');
    	$tgl1 = date('Y-m-d', strtotime('-1 days', strtotime($now)));
    	$tgl2 = date('Y-m-d', strtotime('+5 days', strtotime($now)));
    	$dt = array();
    	while (strtotime($tgl1) <= strtotime($tgl2)) {
    		$dt[] = modeleat::with('request')->whereRaw(' eat_date="'.$tgl1.'" and eat_status=1 ')->get();

		 	$data[] = $tgl1;
		 	$tgl1 = date ("Y-m-d", strtotime("+1 day", strtotime($tgl1)));
		}


    	$form = view('system::dash/makan', ['data' => $data, 'list'=>$dt]);
        return $form->render();
    }
}
