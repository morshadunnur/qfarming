<?php

namespace App\Http\Controllers\Admin;

use App\Models\Farmer;
use App\Models\FarmerInvoice;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FarmerInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.farmerinvoice.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $data['farmer'] = Farmer::find($id);
        $data['invoice'] = FarmerInvoice::orderBy('id','DESC')->first();
        return view('admin.farmerinvoice.create',$data);
    }

    /**
     * Get Sale No with Prefix 00
     * @return int
     */
    protected function getInvoiceNo()
    {


        $invoiceId = FarmerInvoice::orderBy('id','DESC')->first();
        if ($invoiceId)
        {
            $id = $invoiceId->id +1;
            return sprintf('%1$03d',$id);
        }else{
            return sprintf('%1$03d',1);
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
