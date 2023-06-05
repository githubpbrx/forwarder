<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request, Session, Crypt;
use Illuminate\Support\Facades\Hash;
use Config, Storage, DB;

use Modules\System\Http\Controllers\login;

use Modules\System\Models\modelsystem,
    Modules\System\Models\Privileges\modelmenu,
    Modules\System\Models\Privileges\modelrole_access,
    Modules\System\Models\modelprivilege,
    Modules\System\Models\modelfactory,
    Modules\System\Models\Privileges\modelgroup_access;

class settings extends Controller
{
    public function __construct()
    {
        $this->middleware('checklogin');
        $this->micro = microtime(true);
    }

    public function changepassword()
    {
        $login = new login;

        $session    = Session::get('session');
        $cek = modelprivilege::where('privilege_user_nik', $session['user_nik'])->first();
        if ($cek->privilege_hrips == 'Y') {
            $login_data = $login->getdata($session['user_nik']);
            $data = array(
                'title'     => 'Change Password',
                'menu'      => '',

                'action'    => url('changepasswordaction'),

                'nik'       => $session['user_nik'],
                'question_1' => $login->question_1,
                'question_2' => $login->question_2,
                'q1'        => $login->dekripsi($login_data['e']),
                'a1'        => $login->dekripsi($login_data['h']),
                'q2'        => $login->dekripsi($login_data['f']),
                'a2'        => $login->dekripsi($login_data['g']),
            );
            return view('system::settings/change_password', $data);
        } else {
            $data = array(
                'title'     => 'Change Password',
                'menu'      => '',
                'action'    => url('changepasswordactionfwd'),
                'nik'       => $session['user_nik'],
            );
            return view('system::settings/change_passwordfwd', $data);
        }
    }

    public function changepasswordaction(Request $post)
    {
        if ($post->password == 'password123') {
            Session::flash('toast', 'sweetAlert("error", "Gagal", "Anda tidak dapat mengganti dengan password default")');
        } else {
            $login = new login;

            $data = array(
                'nik'       => $post->nik,
                'password'  => $post->password,
                'q1'        => $post->q_1,
                'a1'        => $post->a1,
                'q2'        => $post->q_2,
                'a2'        => $post->a2,
            );

            $privilege  = $post->privilege;

            try {
                if ($privilege > 0 && $privilege < 3) {
                    $login->apiQaAndPassword($data);
                } else {
                    $login->apiChangePassword($data['nik'], $data['password'], $data['a1'], $data['a2']);
                }

                Session::flash('toast', 'toast("success", "Password berhasil diubah")');
            } catch (\Exception $e) {
                Session::flash('toast', 'toast("error", "Server Error : API truncated")');
            }
        }

        return redirect(url('changepassword'));
    }

    public function changepasswordactionfwd(Request $post)
    {
        if ($post->password == 'password123') {
            Session::flash('toast', 'sweetAlert("error", "Gagal", "Anda tidak dapat mengganti dengan password default")');
        } else {
            $nik       = $post->nik;
            $password  = Hash::make($post->password);

            $change = modelprivilege::where('privilege_user_nik', $nik)->update([
                'privilege_password' => $password,
                'updated_at'         => date('Y-m-d H:i:s')
            ]);

            if ($change) {
                Session::flash('toast', 'toast("success", "Password Berhasil Diubah")');
            } else {
                Session::flash('toast', 'toast("error", "Password Gagal Diubah")');
            }
        }

        return redirect(url('changepassword'));
    }

    public function application()
    {
        $data = array(
            'title' => 'Manage Application',
            'menu'  => 'application',

            'action' => url('settings/applicationupdateaction'),
            'system_data' => modelsystem::all(),
        );

        return view('system::settings/application', $data);
    }

    public function applicationdata($system_id)
    {
        $application = modelsystem::find($system_id);
        if (isset($application->system_email_notify) && $application->system_email_notify != '') {
            $email = json_encode(explode(',', $application->system_email_notify));
        } else {
            $email  = '[]';
        }
        $data = array(
            'system_id'                 => $application->system_id,
            'system_program_name'       => $application->system_program_name,
            'system_sidebar_title'      => $application->system_sidebar_title,
            'system_copyright'          => $application->system_copyright,
            'system_email_notify'       => $email,
            'system_login_notify'       => $application->system_login_notify,
            'system_login_description'  => $application->system_login_description,
            'system_default_language'   => $application->system_default_language,
        );

        return $data;
    }

    public function applicationupdateaction(Request $post)
    {
        $login = $post->system_login_notify;

        $application = modelsystem::find($post->system_id);
        $application->system_program_name       = $post->system_program_name;
        $application->system_sidebar_title      = $post->system_sidebar_title;
        $application->system_copyright          = $post->system_copyright;
        $application->system_email_notify       = implode(',', json_decode($post->system_email_notify));
        $application->system_login_notify       = isset($login) ? '1' : '0';
        $application->system_login_description  = $post->system_login_description;
        $application->system_default_language   = $post->system_default_language;
        $application->save();

        $system_data = array(
            'program_name'  => $post->system_program_name,
            'copyright'     => $post->system_copyright,
            'sidebar_title' => $post->system_sidebar_title,
        );

        // return $system_data;
        Session::put('system', $system_data);

        Session::flash('toast', 'toast("success", "Application changed")');
        return redirect(url('settings/application'));
    }

    public function useraccess($nik = null)
    {
        if (isset($nik)) {
            // $login_data = app()->call('Modules\System\Http\Controllers\login@getdata', [$nik]);

            // if(array_key_exists('a', $login_data)){
            $privilege = modelprivilege::where('privilege_user_nik', '=', $nik)->first();
            if ($privilege) {
                $data = array(
                    'title' => 'Manage User Access',
                    'menu'  => '',

                    'action' => url('settings/useraccessaction'),
                    'nik'   => $nik,
                    'location'          => explode(',', $privilege->privilege_user_location),
                    'group_access_id'   => $privilege->privilege_group_access_id,

                    'privilege'         => $privilege,
                    'factory_data'      => modelfactory::all(),
                    'group_access_data' => modelgroup_access::all(),
                    'system_data'       => modelsystem::with('menu')
                        ->whereHas('menu', function ($q) {
                            $q->orderBy('menu_system_id', 'ASC');
                        })
                        ->where('system_id', '!=', '1')
                        ->get(),

                    'system_data'  => modelsystem::with('menu')
                        ->whereHas('menu', function ($q) {
                            $q->orderBy('menu_system_id', 'ASC');
                        })
                        ->where('system_id', '!=', '1')
                        ->get(),
                    'tools_data'        => modelmenu::where('menu_system_id', '=', '1')->get(),
                );
            } else {
                $data = array(
                    'title' => 'Manage User Access',
                    'menu'  => '',

                    'action' => url('settings/useraccessaction'),
                    'nik'   => $nik,
                    'location'          => '',
                    'group_access_id'   => '',

                    'group_access_data' => modelgroup_access::all(),
                    'factory_data'      => modelfactory::all(),
                    'system_data'       => modelsystem::with('menu')
                        ->whereHas('menu', function ($q) {
                            $q->orderBy('menu_system_id', 'ASC');
                        })
                        ->where('system_id', '!=', '1')
                        ->get(),

                    'system_data'  => modelsystem::with('menu')
                        ->whereHas('menu', function ($q) {
                            $q->orderBy('menu_system_id', 'ASC');
                        })
                        ->where('system_id', '!=', '1')
                        ->get(),
                    'tools_data'        => modelmenu::where('menu_system_id', '=', '1')->get(),
                );
            }
            // }else{
            //     Session::flash('toast', 'toast("error", "NIK not found in API")');
            //     return redirect(url('settings/useraccess'));
            // }
        } else {
            $data = array(
                'title' => 'Manage User Access',
                'menu'  => '',

                'action' => url('settings/useraccess'),
                'nik'   => '',
                'location'          => '',
                'group_access_id'   => '',
            );
        }

        return view('system::settings/user_access', $data);
    }

    public function useraccessaction(Request $post)
    {
        $nik = $post->privilege_user_nik;
        $data = array(
            'privilege_user_nik'        => $nik,
            'privilege_user_location'   => implode(',', $post->privilege_user_location),
            'privilege_group_access_id' => $post->privilege_group_access_id,

        );

        $privilege = modelprivilege::where('privilege_user_nik', '=', $nik)->first();

        if ($privilege) {
            modelprivilege::find($privilege->privilege_id)->update($data);
            \LogActivity::update('privilege', $privilege->privilege_id, $this->micro);
            Session::flash('toast', 'toast("success", "Access changed")');
        } else {
            modelprivilege::create($data);
            $privilege = modelprivilege::where($data)->first();
            \LogActivity::create('privilege', $privilege->privilege_id, $this->micro);
            Session::flash('toast', 'toast("success", "New Access created")');
        }

        return redirect(url('settings/useraccess/' . $nik));
    }

    public function systemdata($group_access_id)
    {
        return modelrole_access::where('role_access_group_access_id', '=', $group_access_id)
            ->get();
    }
}
