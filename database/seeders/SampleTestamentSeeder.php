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
            'chapter' => 1,
            'section' => 3,
            'text' => '神は「光あれ」と言われた。すると光があった。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 1,
            ]);
            
        DB::table('testaments')->insert([
            'new' => 0,
            'chapter' => 1,
            'section' => 4,
            'text' => '神はその光を見て、良しとされた。神はその光とやみとを分けられた。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 1,
            ]);
            
        DB::table('testaments')->insert([
            'new' => 0,
            'chapter' => 1,
            'section' => 5,
            'text' => '神は光を昼と名づけ、やみを夜と名づけられた。夕となり、また朝となった。第一日である。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 1,
            ]);
            
        DB::table('testaments')->insert([
            'new' => 0,
            'chapter' => 1,
            'section' => 6,
            'text' => '神はまた言われた、「水の間におおぞらがあって、水と水とを分けよ」。',
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
            'new' => 0,
            'chapter' => 2,
            'section' => 2,
            'text' => '神は第七日にその作業を終えられた。すなわち、そのすべての作業を終って第七日に休まれた。',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 1,
            ]);
            
        DB::table('testaments')->insert([
            'new' => 0,
            'chapter' => 2,
            'section' => 3,
            'text' => '神はその第七日を祝福して、これを聖別された。神がこの日に、そのすべての創造のわざを終って休まれたからである。',
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
            
        DB::table('testaments')->insert([
            'new' => 1,
            'chapter' => 1,
            'section' => 3,
            'text' => 'ユダはタマルによるパレスとザラとの父、パレスはエスロンの父、エスロンはアラムの父、',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 2,
            ]);
            
        DB::table('testaments')->insert([
            'new' => 1,
            'chapter' => 1,
            'section' => 1,
            'text' => 'わたしたちの間に成就された出来事を、最初から親しく見た人々であって、',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'volume_id' => 3,
            ]);
    }
}
