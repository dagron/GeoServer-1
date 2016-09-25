<?php

namespace App\Http\Controllers;

use App\Field;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_fields = Field::where('user_id',1)->get();
        return view('home',['fields'=> $user_fields]);
    }
}
