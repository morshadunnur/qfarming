<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $branches = Branch::latest()->get();
        $users = User::latest()->get();
        /*
         * Check Roles
         * @return Related Views
         * */
        if (auth()->user()->hasRole('admin'))
        {
            return view('admin.dashboard.admin', compact('branches','users'));
        }elseif (auth()->user()->hasRole('manager'))
        {
            return view('admin.dashboard.manager', compact('branches','users'));
        }
        else{
            return view('admin.dashboard.admin', compact('branches','users'));
        }
    }
}
