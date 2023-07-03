<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('user')->select("idUser")->get();

        foreach ($user as $user) {
            for ($i = 0; $i < 10; $i++) {
                DB::table('news')->insert([
                    'idNews' => Uuid::uuid4()->toString(),
                    'title' => Str::random(24),
                    'content' => Str::random(480),
                    'image' => Str::random(120),
                    'releaseDate' => Carbon::now()->subDays(mt_rand(1, 30)),
                    'user_id' => $user->idUser,
                ]);
            }
        }
    }
}
