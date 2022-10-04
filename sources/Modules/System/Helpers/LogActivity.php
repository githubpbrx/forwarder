<?php

namespace Modules\System\Helpers;

use App\Http\Controllers\Controller, Session;
use Request;
use Modules\System\Models\modeltlog;

class LogActivity extends Controller
{
    public static function addToLog($activity, $start)
    {
        $session    = Session::get('session');

        modeltlog::create([
            'userid'    => $session['user_nik'],
            'activity'  => $activity,
            'date'      => date('Y-m-d'),
            'time'      => date('H:i:s'),
            'microtime' => (microtime(true) - $start),
            'ipcom'     => Request::ip(),
        ]);
    }

    public static function create($table, $data_id, $start)
    {
        $activity = 'Create Data ID : ' . $data_id . ' on Table : ' . $table;

        (new self)->addToLog($activity, $start);
    }

    public static function update($table, $data_id, $start)
    {
        $activity = 'Update Data ID : ' . $data_id . ' on Table : ' . $table;

        (new self)->addToLog($activity, $start);
    }

    public static function delete($table, $data_id, $start)
    {
        $activity = 'Delete Data ID : ' . $data_id . ' on Table : ' . $table;

        (new self)->addToLog($activity, $start);
    }
}
