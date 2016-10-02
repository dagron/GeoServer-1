<?php
/**
 * Created by PhpStorm.
 * User: soulg
 * Date: 1/10/2016
 * Time: 19:55
 */

namespace App\Http\Controllers;


use App\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    protected $hashids;

    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * User search based on name
     * @param $name
     * @return string
     */
    public function getUser($name)
    {
        $users = DB::select('SELECT id,name FROM users WHERE type=:type AND name LIKE :name',
                        ['type' => '1', 'name' => '%'.$name.'%']);
        foreach($users as $user ) {
            $user->id = $this->hashids->encode($user->id);
        }
        return json_encode($users);
    }

    /**
     * Add farmer to agriculturists list
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function addFarmerToList(Request $request)
    {
        $farmerIds = $this->hashids->decode($request->input('farmerId'));
        $farmerId = array_shift($farmerIds);

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

    public function removeFarmerFromList(Request $request)
    {
        $farmerIds = $this->hashids->decode($request->input('userId'));
        $farmerId = array_shift($farmerIds);
        DB::statement('DELETE FROM agriculturists_to_users WHERE agriculturist_id=:agricalturistId AND farmer_id=:farmerId',
            ['agricalturistId' => Auth::user()->id,'farmerId' => $farmerId]);

        return redirect('/home');
    }
}