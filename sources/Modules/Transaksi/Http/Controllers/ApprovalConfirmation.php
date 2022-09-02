<?php

namespace Modules\Transaksi\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use Session, Crypt, DB, Mail;
use Modules\Transaksi\Models\mastersupplier as supplier;
use Modules\Transaksi\Models\masterforwarder as forward;
use Modules\Transaksi\Models\modelpo as po;
use Modules\Transaksi\Models\modelforwarder as fwd;
use Modules\Transaksi\Models\masterbuyer as buyer;

class ApprovalConfirmation extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = array(
            'title' => 'Approval Confirmation',
            'menu'  => 'approvalconfirmation',
            'box'   => '',
        );
        return view('transaksi::approvalconfirmation', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('transaksi::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('transaksi::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('transaksi::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getsupplier(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = supplier::select('id', 'nama');
        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' nama like "%' . $search . '%" ');
        }

        $po = $po->where('aktif', '=', 'Y')->orderby('nama', 'asc')->get();

        return response()->json($po);
    }

    public function getbuyer(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: *");

        if (!$request->ajax()) return;
        $po = buyer::select('id_buyer', 'nama_buyer');
        if ($request->has('q')) {
            $search = $request->q;
            $po = $po->whereRaw(' nama_buyer like "%' . $search . '%" ');
        }

        $po = $po->where('aktif', '=', 'Y')->orderby('nama_buyer', 'asc')->get();

        return response()->json($po);
    }
}
