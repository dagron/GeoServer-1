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
        $user->password = Hash::make('pass');
        $user->save();

        $field = new \App\Field();
        $field->fieldName = 'To megalo xorafi';
        $field->fieldFolder = 'path';
        $field->user_id = 1;
        $field->date = date('Y-m-d');
        $field->field_coordinations_id = null;
        $field->save();
    }
}
