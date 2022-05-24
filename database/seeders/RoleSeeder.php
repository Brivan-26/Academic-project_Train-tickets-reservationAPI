<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'passenger'
        ]);
        Role::create([
            'name' => 'admin'
        ]);
        Role::create([
            'name' => 'support'
        ]);
        Role::create([
            'name' => 'validator'
        ]);
    }
}
