<?php

namespace Modules\Master\Http\Controllers;

use Modules\System\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\Datatables\Datatables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Master\Models\masterhscode as hscode;
use Modules\System\Models\modelprivilege;
use Modules\System\Models\Privileges\modelgroup_access;

class MasterHscode extends Controller
{
    protected $ip_server;
    protected $micro;
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");
        $this->micro = microtime(true);
        $this->ip_server = config('api.url.ip_address');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = [
            'title' => 'List Master HS Code',
            'menu' => 'masterhscode'
        ];

        LogActivity::addToLog('Web Forwarder :: ' . $this->getprivilege() . ' : Access Menu Master HsCode', $this->micro);
        return view('master::masterhscode', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function listhscode()
    {
        // $data = modelpo::with(['hscodeku'])
        // leftjoin('masterhscode', 'masterhscode.matcontent', 'po.matcontents')
        //     ->where('masterhscode.aktif', 'Y')
        // ->selectRaw(' po.id, po.pono, po.matcontents')
        // ->get();
        $data = hscode::where('aktif', 'Y')->select('id_hscode', 'matcontent', 'hscode')->get();
        // dd($data);
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('matcontents', function ($data) {
                return $data->matcontent;
            })
            ->addColumn('hscode', function ($data) {
                return $data->hscode;
            })
            ->addColumn('action', function ($data) {
                $button = '';

                $button .= '<a href="#" data-tooltip="tooltip" data-id="' . encrypt($data->id_hscode) . '" id="editdata" title="Edit Data"><i class="fa fa-edit fa-lg text-orange actiona"></i></a>';
                $button .= '<a href="#" data-id="' . encrypt($data->id_hscode) . '" id="delbtn" data-tooltip="tooltip" title="Delete Data"><i class="fa fa-trash fa-lg text-red actiona"></i></a>';

                return $button;
            })
            ->make(true);

        // return view('master::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        // dd($request);
        if ($request->matcontents == '' || $request->hscode == '') {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data is required, please input data'];
            return response()->json($status, 200);
        }

        $cekfwd = hscode::where('matcontent', $request->matcontents)->where('aktif', 'Y')->first();
        if ($cekfwd != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data MatContent is available, please check again'];
            return response()->json($status, 200);
        }

        $cekfwd = hscode::where('hscode', $request->hscode)->where('aktif', 'Y')->first();
        if ($cekfwd != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data HS Code is available, please check again'];
            return response()->json($status, 200);
        }

        $saved = hscode::insert([
            'hscode'       => strtoupper($request->hscode),
            'matcontent'   => strtoupper($request->matcontents),
            'aktif'        => 'Y',
            'created_at'   => date('Y-m-d H:i:s'),
            'created_by' => Session::get('session')['user_nik']
        ]);

        if ($saved) {
            LogActivity::addToLog('Web Forwarder :: ' . $this->getprivilege() . ' : Save Master HSCode', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit(Request $request)
    {
        $id = decrypt($request->id);
        $datahscode = hscode::where('id_hscode', $id)->where('aktif', 'Y')->first();

        return response()->json(['status' => 200, 'data' => $datahscode, 'message' => 'Berhasil']);
        // return view('master::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        // dd($request);
        if ($request->matcontents == '' || $request->hscode == '') {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data is required, please input data'];
            return response()->json($status, 200);
        }

        $cekhscode = hscode::where('id_hscode', '!=', $request->id)->where('hscode', $request->hscode)->where('aktif', 'Y')->first();
        if ($cekhscode != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data HSCode is available, please check again'];
            return response()->json($status, 200);
        }

        $cekhmatcontent = hscode::where('id_hscode', '!=', $request->id)->where('matcontent', $request->matcontents)->where('aktif', 'Y')->first();
        if ($cekhmatcontent != null) {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Mat Content is available, please check again'];
            return response()->json($status, 200);
        }

        $updatehscode = hscode::where('id_hscode', $request->id)->update([
            'hscode'     => strtoupper($request->hscode),
            'matcontent' => strtoupper($request->matcontents),
            'aktif'      => 'Y',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        if ($updatehscode) {
            LogActivity::addToLog('Web Forwarder :: ' . $this->getprivilege() . ' : Update Master HSCode', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Updated'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Updated'];
            return response()->json($status, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $id = decrypt($id);

        $delete = hscode::where('id_hscode', $id)->update([
            'aktif' => 'N',
            'updated_at'   => date('Y-m-d H:i:s'),
            'updated_by' => Session::get('session')['user_nik']
        ]);

        if ($delete) {
            LogActivity::addToLog('Web Forwarder :: ' . $this->getprivilege() . ' : Delete Master HS Code', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Deleted'];
            return response()->json($status, 200);
        } else {
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Deleted'];
            return response()->json($status, 200);
        }
    }

    function uploadexcel(Request $request)
    {
        $file = $request->file('excel');
        $path = $file->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($path);
        $datarows = $spreadsheet->getActiveSheet()->toArray();
        unset($datarows[0]);

        foreach ($datarows as $key => $data) {
            $dud[] = array_filter($data, function ($value) {
                return !is_null($value) && $value !== '' && $value !== 'NULL';
            });
        }
        $rows = array_filter($dud, function ($value) {
            return count($value) != 0;
        });

        ini_set('max_execution_time', 0);
        DB::beginTransaction();
        // dd($rows);
        foreach ($rows as $key => $val) {
            // $rows = $key + 1;
            // dd($val);
            $matcontent = $val[0];
            $hscode = $val[1] ?? '';
            $check = hscode::where('matcontent', $matcontent)->where('hscode', $hscode)->where('aktif', 'Y')->exists();
            if (empty($check)) {
                $insert = hscode::insert([
                    'hscode' => $hscode,
                    'matcontent' => $matcontent,
                    'aktif' => 'Y',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Session::get('session')['user_nik']
                ]);
            } else {
                // DB::rollBack();
                // $status = ['title' => 'Information!', 'status' => 'error', 'message' => 'Data HS Code is Available. Please Check Your Data in Excel at row  : ' . $rows . ''];
                // return response()->json($status, 200);
                $insert = hscode::where('hscode', $hscode)->where('matcontent', $matcontent)->where('aktif', 'Y')->update([
                    'hscode' => $hscode,
                    'matcontent' => $matcontent,
                    'aktif' => 'Y',
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Session::get('session')['user_nik']
                ]);
            }
        }

        if ($insert) {
            DB::commit();
            LogActivity::addToLog('Web Forwarder :: ' . $this->getprivilege() . ' : Import Excel HS Code', $this->micro);
            $status = ['title' => 'Success', 'status' => 'success', 'message' => 'Data Successfully Saved'];
            return response()->json($status, 200);
        } else {
            DB::rollBack();
            $status = ['title' => 'Failed!', 'status' => 'error', 'message' => 'Data Failed Saved'];
            return response()->json($status, 200);
        }
    }

    function getprivilege()
    {
        $nik = Session::get('session')['user_nik'];

        $getpriv = modelprivilege::where('privilege_user_nik', $nik)->where('privilege_aktif', 'Y')->first(['privilege_group_access_id']);
        $getgroupacccess = modelgroup_access::where('group_access_id', $getpriv->privilege_group_access_id)->first(['group_access_name']);
        $getname = $getgroupacccess->group_access_name;

        return $getname;
    }
}
