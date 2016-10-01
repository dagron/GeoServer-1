<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       //  $this->call(UsersTableSeeder::class);
        $user = new \App\User();
        $user->email = 'mail@mail.com';
        $user->name = 'nik';
        $user->type = '1';
        $user->password = Hash::make('pass');
        $user->save();

        $user = new \App\User();
        $user->email = 'mail2@mail.com';
        $user->name = 'geoponos';
        $user->type = '2';
        $user->password = Hash::make('geoponos');
        $user->save();

//        $field = new \App\Field();
//        $field->fieldName = 'To megalo xorafi';
//        $field->fieldFolder = 'path';
//        $field->user_id = 1;
//        $field->date = date('Y-m-d');
//        $field->x_min = 1;
//        $field->x_max = 1;
//        $field->y_min = 1;
//        $field->y_max = 1;
//
//        $field->save();
    }
}
