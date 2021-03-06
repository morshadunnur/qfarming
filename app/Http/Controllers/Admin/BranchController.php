<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Http\Requests\Branch\BranchStoreRequest;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\Branch\BranchUpdateRequest;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* All Branch */
        if (auth()->user()->can('view_branch')) {
                
            $branches = Branch::with('users')->latest()->get();
            return view('admin.branch.index', compact('branches'));

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
        /* CREATE Branch */
        if (auth()->user()->can('create_branch')) {
                
            $data['areas'] = Area::get(['id','name']);
            return view('admin.branch.create', $data);

        }
        abort(403); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BranchStoreRequest $request)
    {
        /* create branch */
        if (auth()->user()->can('create_branch')) {
            
            $branch = Branch::create([
                'name' => $request->branch,
                'slug' => str_slug($request->branch),
                'area_id' => $request->area,
            ]);

            /* cheack and showing toastr message */
            if($branch){
                Toastr::success('Branch Successfully Added', 'Success');
                return redirect()->route('admin.branch.index');
            }
            abort(404);
            
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        /* Branch EDIT form */
       if (auth()->user()->can('edit_branch')) {
               
            $data['areas'] = Area::get(['id','name']);
            return view('admin.branch.edit', $data, compact('branch'));

        }
       abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(BranchUpdateRequest $request, Branch $branch)
    {
        /* UPDATE branch */
        if (auth()->user()->can('edit_branch')) {
                 
            $branchUpdate = $branch->update([
                'area_id'  =>   $request->area,
                'name'     =>   $request->branch,
                'slug'     =>   str_slug($request->branch),
            ]);

            /* cheack and showing toastr message */
            if($branchUpdate){
                Toastr::success('Branch Successfully Updated', 'Success');
                return redirect()->route('admin.branch.index');
            }
            abort(404);
        }
         abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        /* Branch Delete */
        if (auth()->user()->can('delete_branch')) {
                
            $branchDelete = $branch->delete();
            if($branchDelete){
                Toastr::success('Branch Successfully Deleted', 'Success');
                return redirect()->route('admin.branch.index');
            }
            abort(404);
        }
        abort(403);
    }
}
