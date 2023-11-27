<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;


class SampleNote_TestamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('note_testament')->insert([
            'note_id' => 2,
            'testament_id' => 1,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            ]);
            
        DB::table('note_testament')->insert([
            'note_id' => 2,
            'testament_id' => 2,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            ]);
    }
}
