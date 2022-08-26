<?php
namespace Modules\System\Http\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request, Crypt;
use Illuminate\Support\Facades\Hash;
use Config, Session;

class configuration extends Controller{
    // system panel
    public function authpanel(){
        if (Session::has('authenticationKeyPanel')){
            return redirect(url('app/settings/panel'));
        }else{
            return view('system::settings/system/system_key');
        }
    }

    public function authpanelaction(Request $post){
        $key        =  Hash::make('201931121142');
        $authkey    = $post->authkey;

        if(Hash::check($authkey, $key)){
            Session::put('authenticationKeyPanel', 'in');
            return redirect(url('app/settings/panel'));
        }else{
            Session::flash('alert', 'sweetAlert("error", "Wrong key")');
            return redirect(url('app/settings/panel/auth'));
        }
    }

    public function settingspanel(){
        if (Session::has('authenticationKeyPanel')){
            $data = array(
                'DB_HOST'       => Config::get('database.connections.mysql.host'),
                'DB_PORT'       => Config::get('database.connections.mysql.port'),
                'DB_USERNAME'   => Config::get('database.connections.mysql.username'),
                'DB_PASSWORD'   => Config::get('database.connections.mysql.password'),
                'DB_DATABASE'   => Config::get('database.connections.mysql.database'),
                'DB_DATABASE_LOG'=> Config::get('database.connections.mysql_2.database'),
                'IP_ADDRESS'    => Config::get('api.url.ip_address'),
            ); 
            
            return view('system::settings/system/system_panel', $data);
        }else{
            Session::flash('alert', 'sweetAlert("warning", "Insert authentication key")');
            return redirect(url('app/settings/panel/auth'));
        }
    }

    public function settingspanelaction(Request $post){
        $main_db = [
            'host' => $post->DB_HOST,
            'port' => $post->DB_PORT,
            'database' => $post->DB_DATABASE,
            'username' => $post->DB_USERNAME,
            'password' => $post->DB_PASSWORD,
        ];
        $this->setConfigValue('database', 'mysql', $main_db);

        $log_db = [
            'host' => $post->DB_HOST,
            'port' => $post->DB_PORT,
            'database' => $post->DB_DATABASE_LOG,
            'username' => $post->DB_USERNAME,
            'password' => $post->DB_PASSWORD,
        ];
        $this->setConfigValue('database', 'mysql_2', $log_db);

        $url_api = [
            'ip_address' => $post->IP_ADDRESS, 
        ];
        $this->setConfigValue('api', 'url', $url_api);
        
        Session::flash('alert', 'sweetAlert("success", "Database config value has changed")');
        return redirect(url('app/settings/panel'));
        // return redirect(url('app/settings/panel/auth'));
    }

    public function setConfigValue($config, $connection, array $values){
        // $envFile = app()->environmentFilePath();

        //env = config/database.php
        $envFile = config_path($config.'.php');
        $str_all = file_get_contents($envFile);

        
        $connectionPosition = strpos($str_all, "'{$connection}' => [");

        $str_intro = substr($str_all, 0, $connectionPosition);
        $str = substr($str_all, $connectionPosition);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $str .= "\n"; // In case the searched variable is in the last line without \n
                
                $keyPosition = strpos($str, "'{$envKey}' =>");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);

                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "'{$envKey}' => '{$envValue}',\n";
                } else {
                    $str = str_replace($oldLine, "'{$envKey}' => '{$envValue}',", $str);
                }
            }
        }

        $str = substr($str_intro.$str, 0, -1);
        if (!file_put_contents($envFile, $str)) return false;
        return true;
    }   

    public function exitpanel(){
        Session::forget('authenticationKeyPanel');
        Session::flash('alert', 'sweetAlert("success", "Exit")');
        return redirect(url('app/settings/panel/auth'));
    }
}