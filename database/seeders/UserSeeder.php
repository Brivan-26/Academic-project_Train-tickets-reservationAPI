<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $passengerRole = Role::where('name', 'passenger')->pluck('id')->first();
        for ($i = 0; $i <= 19; $i++) {
            DB::table('users')->insert([
                'phone_number' => '0000000' . $i,
                'first_name' => 'first name' . $i,
                'last_name' => 'last name' . $i,
                'password' => Hash::make('12345678'),
                'role_id' => $passengerRole,
                'account_confirmed' => false
            ]);
        }

        DB::table('users')->insert([
            'phone_number' => '1111111',
            'first_name' => 'admin',
            'last_name' => 'admin',
            'password' => Hash::make('12345678'),
            'role_id' => Role::where('name', 'admin')->pluck('id')->first(),
            'account_confirmed' => 0
        ]);

        $supportRole = Role::where('name', 'support')->pluck('id')->first();
        for ($i = 0; $i < 5; $i++) {
            DB::table('users')->insert([
                'phone_number' => '0000' . $i,
                'first_name' => 'support' . $i,
                'last_name' => 'support' . $i,
                'password' => Hash::make('12345678'),
                'role_id' => $supportRole,
                'account_confirmed' => 0
            ]);
        }


        DB::table('users')->insert([
            'phone_number' => '012345',
            'first_name' => 'validator',
            'last_name' => 'vali',
            'password' => Hash::make('12345678'),
            'role_id' => Role::where('name', 'validator')->pluck('id')->first(),
            'account_confirmed' => 0
        ]);
    }
}
