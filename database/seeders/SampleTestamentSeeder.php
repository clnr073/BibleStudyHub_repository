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
            'new' => 0,
            'chapter' => 1,
            'section' => 1,
            'text' => 'はじめに神は天と地とを創造された。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 1,
            ]);
        
        DB::table('testaments')->insert([
            'new' => 0,
            'chapter' => 1,
            'section' => 2,
            'text' => '地は形なく、むなしく、やみが淵のおもてにあり、神の霊が水のおもてをおおっていた。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 1,
            ]);
            
        DB::table('testaments')->insert([
            'new' => 0,
            'chapter' => 2,
            'section' => 1,
            'text' => 'こうして天と地と、その万象とが完成した。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 1,
            ]);
            
        DB::table('testaments')->insert([
            'new' => 1,
            'chapter' => 1,
            'section' => 1,
            'text' => 'アブラハムの子であるダビデの子、イエス・キリストの系図。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 2,
            ]);
            
        DB::table('testaments')->insert([
            'new' => 1,
            'chapter' => 1,
            'section' => 2,
            'text' => 'アブラハムはイサクの父であり、イサクはヤコブの父、ヤコブはユダとその兄弟たちとの父、 ',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 2,
            ]);
    }
}
