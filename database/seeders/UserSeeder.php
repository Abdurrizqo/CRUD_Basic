<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user')->insert([[
            'idUser' => "9147281d-c094-4a03-b574-6f134fd1c204",
            'email' => Str::random(10) . '@gmail.com',
            'username' => Str::random(12),
            'password' => Hash::make('password'),
            'description' => Str::random(180),
            'birthday' => Carbon::now()->subDays(mt_rand(1, 30)),
            'photoProfile' => Str::random(120),
        ], [
            'idUser' => "9147281d-c094-4a03-b574-6f134fd1c205",
            'email' => Str::random(10) . '@gmail.com',
            'username' => Str::random(12),
            'password' => Hash::make('password'),
            'description' => Str::random(180),
            'birthday' => Carbon::now()->subDays(mt_rand(1, 30)),
            'photoProfile' => Str::random(120),
        ], [
            'idUser' => "9147281d-c094-4a03-b574-6f134fd1c206",
            'email' => Str::random(10) . '@gmail.com',
            'username' => Str::random(12),
            'password' => Hash::make('password'),
            'description' => Str::random(180),
            'birthday' => Carbon::now()->subDays(mt_rand(1, 30)),
            'photoProfile' => Str::random(120),
        ], [
            'idUser' => "9147281d-c094-4a03-b574-6f134fd1c207",
            'email' => Str::random(10) . '@gmail.com',
            'username' => Str::random(12),
            'password' => Hash::make('password'),
            'description' => Str::random(180),
            'birthday' => Carbon::now()->subDays(mt_rand(1, 30)),
            'photoProfile' => Str::random(120),
        ]]);
    }
}
