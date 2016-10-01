<?php
/**
 * Created by PhpStorm.
 * User: soulg
 * Date: 1/10/2016
 * Time: 19:55
 */

namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * User search based on name
     * @param $name
     * @return string
     */
    public function getUser($name)
    {
        $users = User::where('type','1')->where('name', 'like', '%' . $name . '%')->get();
        return json_encode($users);
    }

    public function addFarmerToList(Request $request)
    {
        $farmerId = $request->input('farmerId');

        $agriculturists_to_users = DB::select('SELECT * FROM agriculturists_to_users WHERE agriculturist_id=:agricalturistId AND farmer_id=:farmerId',
            ['agricalturistId' => Auth::user()->id,'farmerId' => $farmerId]);

        if(empty($agriculturists_to_users)){
            DB::statement('INSERT INTO agriculturists_to_users (agriculturist_id,farmer_id) VALUES (:agricalturistId, :farmerId)',
                ['agricalturistId' => Auth::user()->id,'farmerId' => $farmerId]);
            return redirect('/home');
        } else {
            return redirect('/home');
        }


    }
}