<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

// DB::table('テーブル名')->insert(['カラム名' => 'データ' ] );
// use Illuminate\Support\Facades\DB;　を追記
// use DateTime;　を追記

class SampleTestamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('testaments')->insert([
            'new' => 1,
            'chapter' => 1,
            'section' => 1,
            'text' => 'はじめに神は天と地とを創造された。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 1,
            ]);
    }
}
