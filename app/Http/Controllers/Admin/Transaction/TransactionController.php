<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\DataTables\Bank\BanksDataTable;
use App\DataTables\Bank\TransactionsDataTable;
use App\Models\Bank;
use App\Models\Collection;
use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param TransactionsDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(TransactionsDataTable $dataTable)
    {
        $data['payments'] = Payment::where('status','active')->get();
        $data['collections'] = Collection::where('status','active')->get();
        $data['bank']       = Bank::all();
        $data['expenses']       = Expense::where('status','active')->get();
//        dd($dataTable->query());
        return $dataTable->render('admin.bank.transactions',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
