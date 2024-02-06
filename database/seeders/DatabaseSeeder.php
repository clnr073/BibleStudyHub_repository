<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // 外部キー制約の無効化
        Schema::disableForeignKeyConstraints();
        
        // 既存データの削除
        $this->truncateTables();
        
        // 外部キー制約の有効化
        Schema::enableForeignKeyConstraints();
        
        $this->call([
            VolumeTableSeeder::class,
            TestamentTableSeeder::class,
        ]);
        
    }
    
    /**
     * テーブルのデータを削除するメソッド
     */
    private function truncateTables()
    {
        // テーブルのリスト
        $tables = [
            'testaments',
            'volumes',
            'notes',
            'comments',
            'tags',
            ];
        
        // テーブルごとにtruncate
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
    }
}
