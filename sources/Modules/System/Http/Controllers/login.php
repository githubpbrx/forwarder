<?php
namespace Modules\System\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session, Crypt, DB;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Modules\System\Models\modelsystem,
    Modules\System\Models\modelfactory,
    Modules\System\Models\modelprivilege;

class login extends Controller{
    protected $ip_server;
    public function __construct(){
        $this->ip_server = config('api.url.ip_address');
    }

    public $question_1 = array(
        'What is the first film you watched in theaters',
        'What is your nickname?', 
        'What is your grandmothers maiden name?', 
        'What is the name of your favorite elementary school teacher?', 
        'Where did you meet your partner?', 
        'Where is your mothers city born?'
    );

    public $question_2 = array(
        'What is your favorite food?', 
        'What is the name of your favorite sports team?', 
        'Whats your best hero name?', 
        'What is the name of your favorite singer?', 
        'Where did your parents city meet?',
        'Where did you first work?'
    );


    public function index(){
        
        if(!Session::get('session')){
            // session(['notify'=> '']);
            return redirect('login');
        }else{
            return redirect('dashboard');
        }
    }

    public function login(){
        if (Session::has('session')) {
            Session::flash('alert', 'sweetAlert("info", "Already login")');
            return redirect('dashboard');
        } else {
            $this->checkTimeChance();
            $system = modelsystem::find(1);

            $data_notify = array(
                'stat' => $system->system_login_notify, 
                'desc' => $system->system_login_description, 
            );

            $data = array(
                'title' => 'Login',
            );
            if ($system->system_login_notify == 1) {
                Session::flash('notify', $data_notify);
            }
            // dd(session()->all());
            return view('system::login/login_form', $data);
        }
    }

    public function loginaction(Request $post){
        $nik_en     = $this->enkripsi($post->nik);
        $nik        = $post->nik;
        $password   = $post->password;

        //cek
        $ceklogin = modelprivilege::where('privilege_user_nik',$nik)->first();
        if($ceklogin==null){
            $this->loginChance();
            Session::flash('alert', 'sweetAlert("error", "Akses ditolak", "Kesempatan : '.$this->loginChance().' kali")');
            return redirect('login');
        }else{
            if($ceklogin->privilege_aktif=='N'){
                $this->loginChance();
                Session::flash('alert', 'sweetAlert("error", "User akses ditolak", "Kesempatan : '.$this->loginChance().' kali")');
                return redirect('login'); 
            }

            if($ceklogin->privilege_hrips=='N'){
                if(Hash::check($password, $ceklogin->privilege_password)){ 
                    if('password123'==$password){
                        $session = array(
                                'tmp_nik'   => $ceklogin->privilege_user_nik,
                                'tmp_nama'  => $ceklogin->privilege_user_name
                            );
                    
                        Session::put('tmp', $session);
                        
                        Session::flash('alert', 'sweetAlert("info", "Please input New Password")');
                        return redirect(url('login/newnohripspassword'));
                    }else{
                        $session = array(
                                'user_nik'   => $ceklogin->privilege_user_nik,
                                'user_nama'  => $ceklogin->privilege_user_name
                            );
                        Session::put('session', $session);
                        $this->choosemenu();
                        Session::flash('alert', 'sweetAlert("success", "Berhasil Login")');
                        return redirect('dashboard');
                    }
                    
                }else{
                    $this->loginChance();
                    Session::flash('alert', 'sweetAlert("error", "Username atau Password salah", "Kesempatan : '.$this->loginChance().' kali")');
                    return redirect('login');
                }
            }else{
                //get the data
                $login_url        = 'http://'.$this->ip_server.'/api/login.php?n='.$nik_en;
                $login_client     = new Client();
                $login_res        = $login_client->get($login_url);
                $login_data_enkripsi = json_decode(base64_decode($login_res->getBody()), TRUE);
                //decrypt the data
                foreach ($login_data_enkripsi as $key => $data_enkripsi) {
                    $login_data[$this->dekripsi($key)] = $data_enkripsi;
                }
                $detail_url        = 'http://'.$this->ip_server.'/api/detail.php?n='.$nik_en;
                $detail_client     = new Client();
                $detail_res        = $detail_client->get($detail_url);
                $detail_data_enkripsi = json_decode(base64_decode($detail_res->getBody()), TRUE);
                foreach ($detail_data_enkripsi as $key => $data_enkripsi) {
                    $detail_data[$this->dekripsi($key)] = $data_enkripsi;
                }
                
                if(array_key_exists('a', $login_data)){
                    $nik_decrypt = $this->dekripsi($login_data['a']);
                    $pass = FALSE;
                    
                    if(Hash::check($password, $login_data['d'])){
                        $pass = TRUE;
                    }

                    if(($nik_decrypt == $nik) && ($pass == TRUE)){
                        //jika password masih default
                        if(Hash::check('password123', $login_data['d'])){
                            $session = array(
                                'tmp_nik'   => $this->dekripsi($login_data['a']),
                                'tmp_nama'  => $this->dekripsi($login_data['b']),
                            );
                    
                            Session::put('tmp', $session);
                            $this->createPrivilege($this->dekripsi($login_data['a']), $this->dekripsi($login_data['b']));
                            Session::flash('alert', 'sweetAlert("info", "Please input New Password")');
                            return redirect(url('login/newpassword'));
                        }elseif(date('Y-m-d') >= date('Y-m-d', strtotime($this->dekripsi($login_data['j'])))){
                            $session = array(
                                'user_nik'   => $this->dekripsi($login_data['a']),
                                'user_nama'  => $this->dekripsi($login_data['b']),
                                'user_perusahaan' => $this->dekripsi($detail_data['b']),
                                'user_bagian'   => $this->dekripsi($detail_data['e']),
                                'user_jabatan'  => $this->dekripsi($detail_data['f']),
                                'user_sbu'      => $this->dekripsi($detail_data['g']),
                            );
                            Session::put('session', $session);
                            // dd(session()->all());
                            Session::flash('alert', 'sweetAlert("info", "Please input New Password !")');
                            // return view('system::login/login_password_expired', $data);
                            return redirect(url('login/pass_exp'));
                        }else{
                            $session = array(
                                'user_nik'   => $this->dekripsi($login_data['a']),
                                'user_nama'  => $this->dekripsi($login_data['b']),
                                
                                'user_perusahaan' => $this->dekripsi($detail_data['b']),
                                'user_bagian'   => $this->dekripsi($detail_data['e']),
                                'user_jabatan'  => $this->dekripsi($detail_data['f']),
                                'user_sbu'      => $this->dekripsi($detail_data['g']),
                            );
                    
                            Session::put('session', $session);
                            
                            $this->createPrivilege($this->dekripsi($login_data['a']), $this->dekripsi($login_data['b']));
                            Session::flash('toast', 'sweetAlert("success", "Berhasil Login")');
                            $this->choosemenu();
                            return redirect('dashboard');
                        }
                    }else{
                        $this->loginChance();
                        Session::flash('alert', 'sweetAlert("error", "Username atau Password salah", "Kesempatan : '.$this->loginChance().' kali")');
                        return redirect('login');
                    }
                }else{
                    Session::flash('alert', 'sweetAlert("error", "Akun tidak ditemukan", "Kesempatan : '.$this->loginChance().' kali")');
                    return redirect('login');
                }
            }

            
        }
        
    }

    public function forgotpasswordstep1(){
        $data = array(
            'title' => 'Forgot Password',

            'new_pass' => false,
            'nik'      => '',
        );

        return view('system::login/login_forgot_password', $data);
    }

    public function forgotpasswordstep2(Request $post){
        $answer1 = $post->a_1;
        $answer2 = $post->a_2;
        $nik     = $post->nik;

        $data = array(
            'title' => 'Forgot Password',

            'new_pass' => true,
            'nik'      => $nik,
        );

        $account_data = $this->getdata($nik);
        $question1 = $this->dekripsi($account_data['h']);
        $question2 = $this->dekripsi($account_data['g']);

        if ($question1 == $answer1 && $question2 == $answer2) {
            // return view('system::login/login_forgot_password', $data);
            Session::flash('alert', 'toast("success", "Yeay, berhasil")');
            return view('system::login/login_forgot_password', $data);
        } else {
            Session::flash('alert', 'sweetAlert("error", "Jawaban Salah")');
            return redirect(url('forgotpassword'));
        }
    }

    public function forgotpasswordaction(Request $post){
        $password   = $post->password;
        $nik        = $post->nik;

        if (!isset($nik) && !isset($password)) {
            Session::flash('alert', 'sweetAlert("error", "Failed, empty data")');
            return redirect(url('login'));
        } else {
            if ($password == 'password123') {
                Session::flash('alert', 'sweetAlert("error", "Gagal", "Silahkan masukkan password lain")');
                return redirect(url('forgotpassword'));
            }else{
                try {
                    $this->apiForgotPassword($nik, $password);
                    Session::flash('alert', 'sweetAlert("success", "Password changed, please login again")');
                } catch (\Exception $e) {
                    Session::flash('alert', 'sweetAlert("error", '.$e.')');
                }
                
                return redirect(url('login'));
            }
        }
    }

    public function newpassword(){
        $session    = Session::get('tmp');

        $data = array(
            'title'     => 'New Password',

            'action'    => url('newpasswordaction'),
            'nik'       => isset($session['tmp_nik']) ? $session['tmp_nik'] : '',
            'nama'      => isset($session['tmp_nama']) ? $session['tmp_nama'] : '',
            'question_1'=> $this->question_1,
            'question_2'=> $this->question_2,
        );
        return view('system::login/login_new_password', $data);
    }

    public function newnohripspassword(){
        $session    = Session::get('tmp');

        $data = array(
            'title'     => 'New Password',

            'action'    => url('newnohripspasswordaction'),
            'nik'       => isset($session['tmp_nik']) ? $session['tmp_nik'] : '',
            'nama'      => isset($session['tmp_nama']) ? $session['tmp_nama'] : '',
            'question_1'=> '',
            'question_2'=> '',
            'ses' => $session
        );
        return view('system::login/login_new_nohrips_password', $data);
    }

    public function newpasswordaction(Request $post){
        $data = array(
            'nik'       => $post->nik,
            'password'  => $post->password,
            'q1'        => $post->q_1,
            'a1'        => $post->a_1,
            'q2'        => $post->q_2,
            'a2'        => $post->a_2,
        );

        if (isset($post->nik) && isset($post->password) && isset($post->q_1) && isset($post->a_1) && isset($post->q_2) && isset($post->a_2)) {
            $this->apiQaAndPassword($data);
            Session::forget('tmp');

            $detail_data = $this->apiDetail($post->nik);

            $session = array(
                'user_nik'   => $post->nik,
                'user_nama'  => $post->nama,

                'user_perusahaan'   => $detail_data['b'],
                'user_bagian'       => $detail_data['e'],
                'user_jabatan'      => $detail_data['f'],
            );

            Session::put('session', $session);
            $this->choosemenu();
            return redirect(url(''));
        } else {
            Session::flash('toast', 'toast("error", "Error : Empty Data")');
            return redirect(url('login/newpassword'));
        }
    }

    public function newnohripspasswordaction(Request $post){
        $data = array(
            'nik'       => $post->nik,
            'password'  => $post->password
        );

        if ( isset($post->nik) && isset($post->password) ) {
            
            $exesql = modelprivilege::where('privilege_user_nik',$post->nik)->update(['privilege_password'=>Hash::make($post->password)]);
            if($exesql){
                $ceklogin =  modelprivilege::where('privilege_user_nik',$post->nik)->first();
                $session = array(
                                'user_nik'   => $ceklogin->privilege_user_nik,
                                'user_nama'  => $ceklogin->privilege_user_name
                            );
                Session::put('session', $session);
                $this->choosemenu();
                return redirect(url(''));
            }else{
                Session::flash('toast', 'toast("error", "Error : Password gagal diubah")');
                return redirect(url('login/newnohripspassword'));
            }
            
        } else {
            Session::flash('toast', 'toast("error", "Error : Empty Data")');
            return redirect(url('login/newnohripspassword'));
        }
    }

    public function exp_password(){
        $nik = Session::get('session')['user_nik'];
        $data    = $this->getdata($nik);
        $data = array(
            'title' => 'Password Expired !',
            'nik'   => $this->dekripsi($data['a']),
            'tgl_exp'  => $this->dekripsi($data['j']),
        );
        return view('system::login/login_password_expired', $data);
    }

    public function exp_password_action(Request $post){
        // dd($post);
        $nik     = $post->nik;
        $newpass = $post->new_pass;
        $newpass1= $post->retype_new_pass;
        $data    = $this->getdata($nik);
        $passku  = $this->dekripsi($data['d']);
        $oldpass = $post->old_pass;
        $pass    = FALSE;
        if(Hash::check($oldpass, $data['d'])){
            $pass = TRUE;
        }
        // dd($pass);
        if($newpass == 'password123' || $newpass1 == 'password123'){
            Session::flash('alert', 'sweetAlert("error", "Error : New Password is Default! Please Change Again !")');
            return redirect(url('login/pass_exp'));
        }elseif($pass == FALSE){
            Session::flash('alert', 'sweetAlert("error", "Error : Old Password is Wrong !")');
            return redirect(url('login/pass_exp'));
        }else{
            $this->apiForgotPassword($nik, $newpass);
            Session::flash('alert', 'sweetAlert("success", "Please Login with New Password !")');
            return redirect('login');
        }
    }

    public function checknik(Request $get){
        $login_data = $this->getdata($get->nik);

        if(array_key_exists('a', $login_data)){
            $data = array(
                'q_1' => $this->dekripsi($login_data['e']), 
                // 'a_1' => $login_data['h'],
                'q_2' => $this->dekripsi($login_data['f']),
                // 'a_2' => $login_data['g'],
                'nik' => $get->nik,
            );
            return $data;
        }else{
            return 0;
        }
    }

    public function checkbirthday(Request $get){
        // dd($get);
        $birthday   = $get->birthday;
        $nik        = $get->nik;

        $data = $this->getdata($nik);
        $data_birth = $this->dekripsi($data['c']);
        if (strtotime($data_birth) == strtotime($birthday)) {
            return '1';
        } else {
            return '0';
        }
    }

    public function apiForgotPassword($nik, $password){
        $nik_en     = $this->enkripsi($nik);
        $password_en= $this->enkripsi($password);

        $url     = 'http://'.$this->ip_server.'/api/forgotpassword.php/?n='.$nik_en.
                                                                '&p='.$password_en;

        $client     = new Client();
        $request    = $client->post($url);
    }

    public function apiChangePassword($nik, $password, $a1, $a2){
        $nik_en      = $this->enkripsi($nik);
        $password_en = $this->enkripsi($password);
        $a_1         = $this->enkripsi($a1);
        $a_2         = $this->enkripsi($a2);

        $url     = 'http://'.$this->ip_server.'/api/changepassword.php/?n='.$nik_en.
                                                            '&p='.$password_en.
                                                            '&a1='.$a_1.
                                                            '&a2='.$a_2;

        $client     = new Client();
        $request    = $client->post($url);
    }

    public function apiQaAndPassword($data){
        $nik_en      = $this->enkripsi($data['nik']);
        $password_en = $this->enkripsi($data['password']);
        $q_1         = $this->enkripsi($data['q1']);
        $a_1         = $this->enkripsi($data['a1']);
        $q_2         = $this->enkripsi($data['q2']);
        $a_2         = $this->enkripsi($data['a2']);

        $url_input_qa       = 'http://'.$this->ip_server.'/api/question.php/?n='.$nik_en.
                                                                '&q1='.$q_1.
                                                                '&q2='.$q_2.
                                                                '&a1='.$a_1.
                                                                '&a2='.$a_2;

        $url_change_pass    = 'http://'.$this->ip_server.'/api/changepassword.php/?n='.$nik_en.
                                                                '&p='.$password_en.
                                                                '&a1='.$a_1.
                                                                '&a2='.$a_2;

        $client     = new Client();
        $client->post($url_input_qa);
        $client->post($url_change_pass);
    }

    public function apiInputQA($data){
        $nik_en      = $this->enkripsi($data['nik']);
        $q_1         = $this->enkripsi($data['q1']);
        $a_1         = $this->enkripsi($data['a1']);
        $q_2         = $this->enkripsi($data['q2']);
        $a_2         = $this->enkripsi($data['a2']);

        $url_input_qa       = 'http://'.$this->ip_server.'/api/question.php/?n='.$nik_en.
                                                                '&q1='.$q_1.
                                                                '&q2='.$q_2.
                                                                '&a1='.$a_1.
                                                                '&a2='.$a_2;
        $client     = new Client();
        $client->post($url_input_qa);
    }

    public function apiDetail($nik){
        $nik_en     = $this->enkripsi($nik);
        $url        = 'http://'.$this->ip_server.'/api/detail.php?n='.$nik_en;
        $client     = new Client();
        $res        = $client->get($url);
        $data_enkripsi = json_decode(base64_decode($res->getBody()), TRUE);
        foreach ($data_enkripsi as $key => $data_enkripsi) {
            $data[$this->dekripsi($key)] = $this->dekripsi($data_enkripsi);
        }

        return $data;
    }

    public function getdata($nik){
        $nik_en     = $this->enkripsi($nik);

        //get the data
        $login_url        = 'http://'.$this->ip_server.'/api/login.php?n='.$nik_en;
        $login_client     = new Client();
        $login_res        = $login_client->get($login_url);
        $login_data_enkripsi = json_decode(base64_decode($login_res->getBody()), TRUE);
        //decrypt the data
        foreach ($login_data_enkripsi as $key => $data_enkripsi) {
            $login_data[$this->dekripsi($key)] = $data_enkripsi;
        }

        return $login_data;
    }

    public function getKaryawan($nik){
        $data_karyawan = $this->getdata($nik);

        if (isset($data_karyawan[""]) && $data_karyawan[""] == 'nodata') {
            return 0;
        }else{
            return $data = [
                'nik'   => $this->dekripsi($data_karyawan['a']),
                'nama'  => $this->dekripsi($data_karyawan['b']),
            ];
        }
    }

    public function logout(){
        $this->middleware('checklogin');
        
        Session::flush();
        return redirect('login')->with('alert','Kamu sudah logout');
    }

    public function getdetailbynik($nik){
        $nik_en     = $this->enkripsi($nik);
        $url        = 'http://'.$this->ip_server.'/api/detail.php?n='.$nik_en;
        $client     = new Client();
        $res        = $client->get($url);
        $data_enkripsi = json_decode(base64_decode($res->getBody()), TRUE);
        foreach ($data_enkripsi as $key => $data_enkripsi) {
            $data[$this->dekripsi($key)] = $this->dekripsi($data_enkripsi);
        }

        return view('car::booking/applicant_detail', $data);
    }

    public function choosemenu($menu = ''){
       
        //Sidebar Change
        Session::put('menu', $menu);


        $system = modelsystem::find(1);
        $system_data = array(
            'program_name'  => $system->system_program_name,
            'copyright'     => $system->system_copyright,
            'sidebar_title' => $system->system_sidebar_title,
        );

        Session::put('system', $system_data);
        $user   = Session::get('session');

        app()->call('Modules\System\Http\Controllers\Privileges\privilege@getPrivilege', [$user['user_nik']]);
       
        return redirect(url('dashboard'));
    }

    public function createPrivilege($nik, $nama = null){
        $privilege = modelprivilege::where('privilege_user_nik', '=', $nik)->first();
        if (!isset($privilege->privilege_user_nik)) {
            modelprivilege::create([
                'privilege_user_nik'    => $nik,
                'privilege_user_name'   => $nama,
                'privilege_aktif'       => 'Y',
                'privilege_hrips'       => 'Y',
            ]);
        }
    }

    public function loginChance(){
        if(Session::has('login_chance')){
            $login_chance = Session::get('login_chance');

            if ($login_chance['chance'] > 0) {
                $chance = $login_chance['chance'] - 1;
                $data = array(
                    'chance'        => $chance,
                    'time_start'    => time(),
                );
                Session::put('login_chance', $data);
                
                return $chance;
            } elseif ($login_chance['time_start'] > (15)) {
                Session::forget('login_chance');
            } elseif ($login_chance['chance'] == 0) {
                $data = array(
                    'chance'        => 0,
                    'time_start'    => time(),
                );
                Session::put('login_chance', $data);
            }
        }else{
            $data = array(
                'chance'        => 5,
                'time_start'    => time(),
            );
            Session::put('login_chance', $data);

            return 5;
        }
    }

    function checkTimeChance(){
        if(Session::has('login_chance')){
            $login_chance = Session::get('login_chance');
            if ($login_chance['chance'] == 0) {
                $chance = date('H:i:s', strtotime('+30 second', $login_chance['time_start']));
                if (time() >= strtotime($chance)) {
                    Session::forget('login_chance');
                }else{
                    Session::put('time_chance', strtotime('+30 second', $login_chance['time_start']) - time());
                }
            }
        }
    }

    function enkripsi($input){
		$output = '';

		for($i = 0; $i < strlen($input); $i++) 
		{
			$temp = ord($input[$i]);
			$temp = ($temp + 20) - 29;
			$temp = chr($temp);
			$output = $output . $temp;
		}

		$str1 = substr($output,0,1);
		$str2 = substr($output,1,999999);
		$str = $str1.mt_rand(100000, 999999).$str2;
		$str = rand(1111,9999). date('y') . $str . date('m') . rand(10101,99999);
		$str = base64_encode($str);

		return $str;
	}

    function dekripsi($input){
		$str = base64_decode($input);
		$str = $this->potongKarakterDepan(6, $str);
		$str = $this->potongKarakterBelakang(7, $str);
		$str1 = substr($str,0,1);
		$str2 = substr($str,7,999999);
		$str = $str1.$str2;
		$output = '';	
		for($i = 0; $i < strlen($str); $i++){
			$temp = ord($str[$i]);
			$temp = ($temp - 20) + 29;
			$temp = chr($temp);
			$output = $output . $temp;
		}
		
		return $output;
	}
   
    function potongKarakterBelakang($jml, $str){
        $str = (string)$str;
        $str = substr($str, 0, -$jml);
        return $str;
    }

    function potongKarakterDepan($jml, $str){
        $pjg = strlen($str);
        $str = (string)$str;
        $str = substr($str, $jml, $pjg);
        return $str;
    }
}
