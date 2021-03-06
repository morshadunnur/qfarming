<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Brian2694\Toastr\Facades\Toastr;
use App\Http\Requests\User\UserStoreRequest;
use App\Models\Role;
use App\Http\Requests\User\UserUpdateRequest;

use Notification;
use App\Notifications\UserCreateNotification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* show all users */
        if(auth()->user()->can('view_user')){
            $users = User::with('branch','roles')->latest()->get();
            return view('admin.user.index', compact('users'));
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
        /* CREATE   USER */
        if(auth()->user()->can('create_user')){
            $data['branches'] = Branch::get(['id','name']);
            $data['roles'] = Role::get(['id','name']);
            return view('admin.user.create', $data);
        }
        abort(403);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        /* INSERT USER */
        if(auth()->user()->can('create_user')){
            $user = User::create([
                'branch_id'        =>      $request->branch,
                'name'             =>      $request->user,
                'username'         =>      $request->username,
                'phone1'           =>      $request->phone1,
                'phone2'           =>      $request->phone2,
                'email'            =>      $request->email,
                'password'         =>      bcrypt($request->password),
                'address'          =>      $request->address,
                'status'           =>      'active',
            ]);

            $details = [
                    'user_name' => $request->user,
                    'route'       => 'user'
                ];
            User::find(1)->notify(new UserCreateNotification($details));
            User::find(2)->notify(new UserCreateNotification($details));

            /* assigning roles to the user */
            if($user){
                $user->roles()->attach($request->roles);
            }

            /* Check famer insertion  and Toastr */
            if($user){
                Toastr::success('User Inserted Successfully', 'Success');
                return redirect()->route('admin.user.index');
            }
        }
        abort(403);

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        /* Single User Details */
        if(auth()->user()->can('view_user')){
            $user = User::findOrFail($user->id);
            return view('admin.user.show', compact('user'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        /* User EDIT form */
        if(auth()->user()->can('edit_user')){
            $data['branches'] = Branch::get(['id','name']);
            $data['roles'] = Role::where('id', '!=', 1)->get(['id','name']);
            $user = User::findOrFail($user->id);
            return view('admin.user.edit', $data,  compact('user'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        /* UPDATE USER */
        if(auth()->user()->can('edit_user')){
            $user = User::findOrFail($user->id);

            if (!auth()->user()->hasRole('superadmin') && $user->hasRole('superadmin'))
            {
                Toastr::success('You Can Not Change the SuperAdmin' , 'Success');
                return redirect()->to('user');
            }
            /* update user with password */
            if(!empty($request->password)){
                $user->update([
                    'branch_id'        =>      $request->branch,
                    'name'             =>      $request->user,
                    'username'         =>      $request->username,
                    'phone1'           =>      $request->phone1,
                    'phone2'           =>      $request->phone2,
                    'email'            =>      $request->email,
                    'password'         =>      bcrypt($request->password),
                    'address'          =>      $request->address,
                    'status'           =>      $request->status,
                ]);
            } 
            /* update user without password */
            else{
                $user->update([
                    'branch_id'        =>      $request->branch,
                    'name'             =>      $request->user,
                    'username'         =>      $request->username,
                    'phone1'           =>      $request->phone1,
                    'phone2'           =>      $request->phone2,
                    'email'            =>      $request->email,
                    'address'          =>      $request->address,
                    'status'           =>      $request->status,
                ]);
            }

            /* update roles to the user */
            if($user){
                $user->roles()->sync($request->roles);
            }

            /* Check famer insertion  and Toastr */
            if($user){
                Toastr::success('User Updated Successfully', 'Success');
                return redirect()->route('admin.user.index');
            }
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* User DELETE */
        if(auth()->user()->can('delete_user')){
            $userDelete = User::findOrFail($id)->delete();
        
            if($userDelete){
                Toastr::success('User Deleted Successfully', 'Success');
                return redirect()->route('admin.user.index');
            }
            abort(404);
        }
        abort(403);
    }
}
