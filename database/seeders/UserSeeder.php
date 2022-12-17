<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user=User::where('email','super.admin@gmail.com')->first();
        if(is_null($user)){
            $user=new User();
            $user->name="Super Admin";
            $user->email="super.admin@gmail.com";
            $user->password=Hash::make('12345678');
            $user->role='Super Admin';
            $user->save();
            $user->assignRole('Super Admin');
        }else{
            return 0;
        }
    }
}
