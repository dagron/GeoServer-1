<?php

namespace App\Http\Controllers;

use App\Field;
use Hashids\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    protected $hashids;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Hashids $hashids)
    {
        $this->middleware('auth');
        $this->hashids = $hashids;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->type == '1'){
            $user_fields = Field::distinct()->select('fieldName')->where('user_id',Auth::user()->id)->get();
            return view('farmerHome',['fields'=> $user_fields]);

        } else {
            $users = DB::select('SELECT users.id,users.name
                                FROM agriculturists_to_users
                                INNER JOIN users
                                ON agriculturists_to_users.farmer_id=users.id
                                WHERE agriculturists_to_users.agriculturist_id='.Auth::user()->id.'
                                ');
            foreach($users as $user ) {
                $user->id = $this->hashids->encode($user->id);
            }
            return view('agriculturistHome',['users' => $users]);
        }
    }
}
