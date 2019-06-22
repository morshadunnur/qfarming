<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Http\Requests\Product\ProductStoreRequest;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('view_product')){
            $products = Product::latest()->get();
            return view('admin.product.index', compact('products'));
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('create_product')){
            $subCategories = SubCategory::all();
            return view('admin.product.create', compact('subCategories'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        /* Product Store */
        if(auth()->user()->can('create_user')){
            $product = Product::create([
                'subcategory_id' => $request->sub_category,
                'product_name'   => $request->product_name,
                'sku'            => $request->sku,
                'barcode'        => $request->barcode,
                'base_unit_id'   => $request->unit_id,
                'description'    => $request->description,
                'size'           => $request->size,
                'quantity'       => $request->quantity,
            ]);
            /* Check Product store and toastr message */
            if($product){
                Toastr::success('Product Successfully Added', 'Success');
                return redirect()->route('admin.product.index');
            }
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if(auth()->user()->can('view_product')){
            return view('admin.product.show', compact('product'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        if(auth()->user()->can('edit_product')){
            $subCategories = SubCategory::all();
            return view('admin.product.edit', compact('product','subCategories'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        /* Product Update */
        if(auth()->user()->can('edit_product')){
            $productResult = $product->update([
                'subcategory_id' => $request->sub_category,
                'product_name'   => $request->product_name,
                'sku'            => $request->sku,
                'barcode'        => $request->barcode,
                'base_unit_id'   => $request->unit_id,
                'description'    => $request->description,
                'size'           => $request->size,
                'quantity'       => $request->quantity,
            ]);
            /* Check Product Update and toastr message */
            if($productResult){
                Toastr::success('Product Successfully Updated', 'Success');
                return redirect()->route('admin.product.index');
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        /* Product delete */
        if(auth()->user()->can('delete_product')){
            $deleteProduct = $product->delete();
            if($deleteProduct){
                Toastr::success('Product Successfully Deleted', 'Success');
                return redirect()->route('admin.product.index');
            }
            abort(404);
        }
        abort(403);
    }
}
