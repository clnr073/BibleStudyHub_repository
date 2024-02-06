<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\File;

class TestamentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // フォルダのパス
        $folder_path = database_path('seeders/csv_data/testaments_csv');
        
        // フォルダ内のcsvファイルを取得
        $csv_files = File::glob($folder_path . '/*.csv');
        
        // csvファイルのデータを取得
        foreach ($csv_files as $csv_file) {
            $csv_data = array_map('str_getcsv', file($csv_file));
            $headers = array_shift($csv_data);
            
            // データベースに挿入
            foreach ($csv_data as $row) {
                $data = array_combine($headers, $row);
                
                DB::table('testaments')->insert([
                    'new' => $data['new'],
                    'volume_id' => $data['volume_id'],
                    'chapter' => $data['chapter'],
                    'section' => $data['section'],
                    'text' => $data['text'],
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime(),
                    ]);
            }
        }
    }
}
