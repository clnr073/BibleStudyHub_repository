<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use DateTime;

class VolumeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // csvファイルのパス
        $csv_file_path = database_path('seeders/csv_data/volume_title.csv');
        
        // csvファイルのデータを取得
        $csv_data = array_map('str_getcsv', file($csv_file_path));
        $headers = array_shift($csv_data);
        
        // データベースに挿入
        foreach ($csv_data as $row) {
            $data = array_combine($headers, $row);
            
            DB::table('volumes')->insert([
                'title' => $data['title'],
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
                ]);
        }
    }
}
