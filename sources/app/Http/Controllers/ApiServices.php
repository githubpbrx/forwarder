<?php

namespace App\Http\Controllers;

use App\Models\API;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Routing\Controller;
use Crypt;
use Modules\Guestbook\Models\detailguestbook_model as DetailGuestbook;
use Modules\Guestbook\Models\guestbook_model as Guestbook;
use Modules\Guestbook\Models\model_mapping_gate as MappingGate;
use Modules\Guestbook\Models\model_master_gate as MasterGate;
use Modules\Guestbook\Models\modelaccesscard as AccessCard;
use Modules\System\Models\modelfactory as Factory;
use Modules\System\Models\modelprivilege as Privilege;

class ApiServices extends Controller
{
    public function authtoken($api_key){
        if($api_key){
            $grant = Privilege::whereRaw("privilege_api_key = '$api_key' AND privilege_api_key IS NOT NULL")->first();

            if(!$grant){
                return abort(401);
            }
        } else {
            return abort(401);
        }
    }

    public function apitokenguestbook($nik){
        $api = API::where('id', 9)->first();

        header("Access-Control-Allow-Origin: $api->cors_origin");
        header("Access-Control-Allow-Headers: $api->cors_headers");
        header("Access-Control-Allow-Methods: $api->cors_methods");

        $query = Privilege::where('privilege_user_nik', $nik)->first();

        if ($query) {
            $data['api_key'] = $query->privilege_api_key;
            $data['status'] = true;
            return response()->json($data, Response::HTTP_OK);
        } else {
            $data['status'] = false;
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
    }

    public function listguestbook($api_key, $nik)
    {
        $this->authtoken($api_key);
        $api = API::where('id', 2)->first();

        header("Access-Control-Allow-Origin: $api->cors_origin");
        header("Access-Control-Allow-Headers: $api->cors_headers");
        header("Access-Control-Allow-Methods: $api->cors_methods");

        $query = Guestbook::leftjoin('factory','guestbook.visit_plan','=','factory.factory_id')
            ->whereRaw("isactive = 1 AND (pic = '$nik' OR created_by = '$nik')")
            ->orderBy('id', 'DESC')
            ->get();

        if (count($query) > 0) {
            $data['data'] = $query;
            $data['status'] = true;
            return response()->json($data, Response::HTTP_OK);
        } else {
            $data['status'] = false;
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
    }

    public function createguestbook(Request $post, $api_key)
    {
        $this->authtoken($api_key);
        $api = API::where('id', 3)->first();

        header("Access-Control-Allow-Origin: $api->cors_origin");
        header("Access-Control-Allow-Headers: $api->cors_headers");
        header("Access-Control-Allow-Methods: $api->cors_methods");

        if ($post) {
            $params['name'] = $post->name;
            $params['institution'] = $post->institution;
            $params['neccesity'] = $post->neccesity;
            $params['visit_plan'] = $post->visit_plan;
            $params['pic'] = $post->pic;
            $params['pic_name'] = $post->pic_name;
            $params['pic_departement'] = $post->pic_departement;
            $params['pic_ext'] = $post->pic_ext;
            $params['visitor_type'] = $post->visitor_type;
            $params['date_plan'] = $post->date_plan;
            $params['time_plan'] = $post->time_plan;
            $params['created_by'] = $post->created_by;

            $create = Guestbook::insert($params);

            if ($create) {
                $data['status'] = true;
                return response()->json($data, Response::HTTP_OK);
            } else {
                $data['status'] = false;
                return response()->json($data, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            $data['status'] = false;
            return response()->json($data, Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function listfactory($api_key)
    {
        $this->authtoken($api_key);
        $api = API::where('id', 4)->first();

        header("Access-Control-Allow-Origin: $api->cors_origin");
        header("Access-Control-Allow-Headers: $api->cors_headers");
        header("Access-Control-Allow-Methods: $api->cors_methods");

        $query = Factory::get();

        if (count($query) > 0) {
            $data['data'] = $query;
            $data['status'] = true;
            return response()->json($data, Response::HTTP_OK);
        } else {
            $data['status'] = false;
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
    }

    public function getguestbook($api_key, $id)
    {
        $this->authtoken($api_key);
        $api = API::where('id', 5)->first();

        header("Access-Control-Allow-Origin: $api->cors_origin");
        header("Access-Control-Allow-Headers: $api->cors_headers");
        header("Access-Control-Allow-Methods: $api->cors_methods");

        $query = Guestbook::where('id', $id)->first();

        if ($query) {
            $data['data'] = $query;
            $data['status'] = true;
            return response()->json($data, Response::HTTP_OK);
        } else {
            $data['status'] = false;
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
    }

    public function updateguestbook(Request $post, $api_key, $id)
    {
        $this->authtoken($api_key);
        $api = API::where('id', 6)->first();

        header("Access-Control-Allow-Origin: $api->cors_origin");
        header("Access-Control-Allow-Headers: $api->cors_headers");
        header("Access-Control-Allow-Methods: $api->cors_methods");

        if ($post) {
            $params['name'] = $post->name;
            $params['institution'] = $post->institution;
            $params['neccesity'] = $post->neccesity;
            $params['visit_plan'] = $post->visit_plan;
            $params['pic'] = $post->pic;
            $params['pic_name'] = $post->pic_name;
            $params['pic_departement'] = $post->pic_departement;
            $params['pic_ext'] = $post->pic_ext;
            $params['visitor_type'] = $post->visitor_type;
            $params['date_plan'] = $post->date_plan;
            $params['time_plan'] = $post->time_plan;
            $params['created_by'] = $post->created_by;

            $update = Guestbook::where('id', $id)->update($params);

            if ($update) {
                $data['status'] = true;
                return response()->json($data, Response::HTTP_OK);
            } else {
                $data['status'] = false;
                return response()->json($data, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            $data['status'] = false;
            return response()->json($data, Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function deleteguestbook($api_key, $id)
    {
        $this->authtoken($api_key);
        $api = API::where('id', 7)->first();

        header("Access-Control-Allow-Origin: $api->cors_origin");
        header("Access-Control-Allow-Headers: $api->cors_headers");
        header("Access-Control-Allow-Methods: $api->cors_methods");

        if ($id) {
            $params['isactive'] = 0;

            $update = Guestbook::where('id', $id)->update($params);

            if ($update) {
                $data['status'] = true;
                return response()->json($data, Response::HTTP_OK);
            } else {
                $data['status'] = false;
                return response()->json($data, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            $data['status'] = false;
            return response()->json($data, Response::HTTP_NOT_ACCEPTABLE);
        }
    }

    public function getdetailguestbook($api_key, $id)
    {
        $this->authtoken($api_key);
        $api = API::where('id', 8)->first();

        header("Access-Control-Allow-Origin: $api->cors_origin");
        header("Access-Control-Allow-Headers: $api->cors_headers");
        header("Access-Control-Allow-Methods: $api->cors_methods");

        // $query = Guestbook::leftjoin('detail_guestbook','guestbook.id','=','detail_guestbook.guestbook_id')
        //     ->leftjoin('factory', 'detail_guestbook.visitation', '=', 'factory.factory_id')
        //     ->selectRaw('guestbook.guestbook_id, guestbook.name, guestbook.institution, guestbook.neccesity, guestbook.visit_plan, guestbook.visitor_type, guestbook.pic,
        //     guestbook.pic_name, guestbook.pic_departement, guestbook.pic_ext, guestbook.date_plan, guestbook.time_plan, guestbook.identity_number,
        //     master_maingate.nama maingate, guestbook.access_number, guestbook.gate_date_in, guestbook.gate_time_in, guestbook.gate_pic_in,
        //     guestbook.gate_pic_name_in, guestbook.gate_date_out, guestbook.gate_time_out, guestbook.gate_pic_out, gate_pic_name_out')
        //     ->where('guestbook.id', $id)->first();
            $data1 = Guestbook::leftjoin('factory', 'guestbook.visit_plan', '=', 'factory.factory_id')
            ->where('id', $id)->first();
            $data2 = DetailGuestbook::leftjoin('factory', 'detail_guestbook.visitation', '=', 'factory.factory_id')
            ->where('guestbook_id', $id)->get();

        if ($data1) {
            $data['tamu'] = $data1;
            $data['kunjungan'] = $data2;
            $data['status'] = true;
            return response()->json($data, Response::HTTP_OK);
        } else {
            $data['status'] = false;
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
    }
}
