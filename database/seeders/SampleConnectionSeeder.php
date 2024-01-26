<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class SampleConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('connections')->insert([
            'follow_id' => 3,
            'followed_id' => 1,
            'approval' => 0,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            ]);
            
        DB::table('connections')->insert([
            'follow_id' => 3,
            'followed_id' => 1,
            'approval' => 0,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            ]);
    }
}
