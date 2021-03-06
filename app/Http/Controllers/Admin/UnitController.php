<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\UnitStoreRequest;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Unit\UnitUpdateRequest;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* Unit list */
        if (auth()->user()->can('view_unit')) {
            $units = Unit::latest()->get();
            return view('admin.unit.index', compact('units'));
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
        /* Unit CREATE Form */
        if (auth()->user()->can('create_unit')) {
            return view('admin.unit.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitStoreRequest $request)
    {
        /* STORE Unit */
        if (auth()->user()->can('create_unit')) {
            
            $unit = Unit::create([
                'name'  => $request->unit
            ]);

            // check Unit and toast message
            if($unit){
                Toastr::success('Unit Successfully Inserted', 'Success');
                return redirect()->route('admin.unit.index');
            }
            abort(404);
        }
     abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        /* Unit Edit form */
        if (auth()->user()->can('edit_unit')) {
            return view('admin.unit.edit', compact('unit'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(UnitUpdateRequest $request, Unit $unit)
    {
        /* update Unit */
        if (auth()->user()->can('edit_unit')) {
                
            $unitUpdate = $unit->update([
                'name' => $request->unit
            ]);

            //check and toast message
            if($unitUpdate){
                Toastr::success('Unit Successfully Updated', 'Success');
                return redirect()->route('admin.unit.index');
            }
            abort(404);
         }
         abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        if (auth()->user()->can('delete_unit')) {
                
            $unitDelete = $unit->delete();
            if($unitDelete){
                Toastr::success('Unit Successfully Deleted', 'Success');
                return redirect()->route('admin.unit.index');
            }
            abort(404);
        }
        abort(403); 
    }
}
