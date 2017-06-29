<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function postlogin(Request $request) {
       
        if (Auth::attempt ( array (
                'email' => $request->email,
                'password' => $request->password
        ) )) {
             dd('success');
        } else {
            // Session::flash ( 'message', "Invalid Credentials , Please try again." );
           dd('failed');
        }
    }
    public function logout() {
        Auth::logout ();
        return Redirect::back ();
    }
}
