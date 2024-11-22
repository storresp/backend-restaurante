<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name'  => 'JuandiPro',
            'email'     => 'Juangamer@gmail.com',
            'password'  => bcrypt('password'),
            'remember_token' => Str::random(60)
        ]);

        User::factory(10) -> create();
    }
}
