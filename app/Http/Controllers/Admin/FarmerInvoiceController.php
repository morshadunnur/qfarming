<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\Farmer;
use App\Models\FarmerInvoice;
use App\Models\FarmerInvoiceItem;
use App\Models\Inventory;
use App\Models\Payment;
use App\Models\ProductPrice;
use App\Models\PurposeHead;
use App\Models\SaleTempItem;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use \DB;
use Illuminate\Support\Facades\Validator;

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
        $data['banks']          = Bank::get(['id','bank_name']);
        $data['purposeheads']   = PurposeHead::get(['id','name']);
        return view('admin.farmerinvoice.create',$data);
    }


    public function payment(Request $request,$id)
    {
        $request->validate([
           'payment_amount'     => 'required',
        ]);
        $payment = Payment::create([
            'bank_id'        =>      1,
            'purposehead_id' =>      $request->input('purposehead_id'),
            'company_id'     =>      null,
            'farmer_id'      =>      $id,
            'user_id'        =>      null,
            'payee_type'     =>      'farmer',
            'payment_amount' =>      $request->input('payment_amount'),
            'payment_type'   =>      'cash',
            'bank_name'      =>      null,
            'reference'      =>      $request->input('reference'),
            'received_by'    =>      $request->input('received_by'),
            'remarks'        =>      'Advance Payment',
            'payment_date'   =>      Carbon::parse($request->input('payment_date'))->format('Y-m-d H:i'),
        ]);
        /* Check Payment insertion  and Toastr */
        if($payment){
            Toastr::success('Farmer Payment Successfully', 'Success');
            return redirect()->to('farmer/'.$id);
        }

        Toastr::error('Farmer Payment Successfully', 'Error');
        return redirect()->to('farmer/'.$id);


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
        if (auth()->user()->can('create_sale'))
        {
            DB::beginTransaction();

            try
            {
                $invoice = FarmerInvoice::create([
                    'farmer_id'         => $request->input('farmer_id'),
                    'user_id'           => auth()->user()->id,
                    'batch_number'      => $request->input('batch_number'),
                    'date'              => Carbon::parse($request->input('sale_date'))->format('Y-m-d H:i'),
                    'invoice_number'    => 'Farmer-'.$this->getInvoiceNo(),
                    'total_amount'      => (int) $request->input('grand_total'),
                    'status'            => 1,
                    'remarks'           => $request->input('remarks'),
                    'receipt_no'           => $request->input('memo'),
                    'created_at'        => Carbon::now('+6'),
                    'updated_at'        => Carbon::now('+6'),
                ]);
            }catch (\Exception $e)
            {
                dd($e);
                DB::rollback();
                Toastr::error('Error Found in Farmer Invoice','Error');
                return redirect()->back();
            }

            /*
             * Process Products
             * */

            try
            {
                $saleProducts =SaleTempItem::where('user_id',auth()->user()->id)->get();
                /*
                 * Check the empty product
                 * @return farmer invoice
                 * */
                if ($saleProducts->isEmpty())
                {
                    DB::rollback();
                    Toastr::error('No Product Selected!','error');

                    return redirect()->route('admin.farmer.show',$request->input('farmer_id'));
                }

                if (!$saleProducts->isEmpty())
                {
                    /*
                     * Each Sale Products creates
                     * */
                    foreach ($saleProducts as $saleProduct)
                    {
                        $invoiceItems = FarmerInvoiceItem::create([
                            'farmerinvoice_id'      => $invoice->id,
                            'product_id'            => $saleProduct->product_id,
                            'unit_id'               => $saleProduct->unit_id,
                            'batch_number'          => $request->input('batch_number'),
                            'cost_price'            => $saleProduct->cost_price,
                            'selling_price'         => $saleProduct->selling_price,
                            'quantity'              => $saleProduct->quantity,
                            'total_cost'            => $saleProduct->total_cost,
                            'total_selling'         => $saleProduct->total_selling,
                            'created_at'            => Carbon::now('+6'),
                            'updated_at'            => Carbon::now('+6'),
                        ]);

                        /*
                         * Process Inventory
                         * */

                        $product = ProductPrice::where('product_id',$saleProduct->product_id)->where('batch_no',$saleProduct->batch_no)->first();

                        if ($saleProduct->quantity > ($product->quantity - $product->sold))
                        {
                            $productAllBatch = DB::table('product_prices')->selectRaw('id,product_id,batch_no,quantity,sold,sum(quantity - sold) stock')->whereRaw('quantity - sold > 0')->where('product_id',$saleProduct->product_id)->groupBy('batch_no')->orderBy('created_at')->get();
                            $updateQuantity = 0;
                            $totalUpdate = 0;
                            foreach ($productAllBatch as $value)
                            {
                                $updateQuantity += $value->stock;

                                if ($updateQuantity <= ($saleProduct->quantity - $totalUpdate))
                                {
                                    $singleBatch = ProductPrice::find($value->id);
                                    $singleBatch->update(array('sold' => $value->sold + $value->stock));
                                    $totalUpdate += $value->stock;

                                }


                            }

                            if (($saleProduct->quantity - $totalUpdate) !== 0)
                            {
                                $singleBatch = ProductPrice::where('product_id',$saleProduct->product_id)->whereRaw('quantity - sold > 0')->first();
                                $singleBatch->update(array('sold' => $value->sold + ($saleProduct->quantity - $totalUpdate)));

                            }

                            /*
                             * Track Inventory
                             * */

                            $inventory = Inventory::create([
                                'product_id'        => $saleProduct->product_id,
                                'branch_id'         => auth()->user()->id,
                                'user_id'           => auth()->user()->id,
                                'unit_id'           => $saleProduct->unit_id,
                                'in_out_qty'        => -$saleProduct->quantity,
                                'remarks'           => 'FarmerInv-'.$invoice->invoice_number,
                                'created_at'        => Carbon::now('+6'),
                                'updated_at'        => Carbon::now('+6'),
                            ]);


                        }
                        else
                        {
                            $product->sold += $saleProduct->quantity;
                            $product->save();

                            $inventory = Inventory::create([
                                'product_id'        => $saleProduct->product_id,
                                'branch_id'         => auth()->user()->id,
                                'user_id'           => auth()->user()->id,
                                'unit_id'           => $saleProduct->unit_id,
                                'in_out_qty'        => -$saleProduct->quantity,
                                'remarks'           => 'FarmerInv-'.$invoice->invoice_number,
                                'created_at'        => Carbon::now('+6'),
                                'updated_at'        => Carbon::now('+6'),
                            ]);
                        }
                    }

                }
            }catch (\Exception $e)
            {
                dd($e);
                DB::rollback();
                Toastr::error('Error in Invoice Product!','Error');
                return  redirect()->back();
            }

            DB::commit();

            SaleTempItem::where('user_id',auth()->user()->id)->delete();

            Toastr::success('Farmer Invoice Complete','Success');

            return redirect()->route('admin.farmer.show',$request->input('farmer_id'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['invoice'] = FarmerInvoice::with('farmerinvoiceitems')->findOrFail($id);
        return view('admin.farmerinvoice.show',$data);
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
