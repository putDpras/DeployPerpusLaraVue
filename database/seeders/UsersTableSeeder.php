<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $admin_id = Roles::where('name', 'owner')->first();
        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'role_id' => $admin_id->id,
            'password' => Hash::make('password')

        ]);
    }
}
