<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testament extends Model
{
    use HasFactory;
    
    /**
     * Volumeモデルとのリレーションシップ
     */
    public function volume()
    {
        return $this->belongsTo(Volume::class);
    }
    
    /**
     * Noteモデルとのリレーションシップ
     */
    public function notes()
    {
        return $this->belongsToMany(Note::class);
    }
    
    /**
     * Questionモデルとのリレーションシップ
     */
    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }
     
     /**
      * Answerモデルとのリレーションシップ
      */
     public function answers()
     {
         return $this->belongsToMany(Answer::class);
     }
     
     /**
      * モデルインスタンスの持つvolme_idと同じvolume_idの中で、
      * 最大 or 最小のchapterを取得
      * 
      */
     public function getChapter($volume_id, $is_max = true)
     {
         $operation = $is_max ? 'MAX' : 'MIN'; //変数の値に基づいて集計関数を$operationに格納
    
         $chapter = $this->where('volume_id', $volume_id) // インスタンスのvolume_idと等しいレコードを追加
                            ->select('volume_id', \DB::raw($operation . '(chapter) as chapter_id')) // 指定された操作を適用したchapterカラムを選択
                            ->groupBy('volume_id') // volume_idカラムでグループ化
                            ->first(); // クエリの結果から最初のレコードを取得

         return $chapter;
     }
     
     /**
      * モデルインスタンスの持つvolme_idより小さいvolume_idの中で、
      * 最新のchapterを取得
      */
     public function getPreviousVolumeLatestChapter($volume_id)
     {
         $chapter = $this->where('volume_id', '<', $volume_id)
             ->select('volume_id', \DB::raw('MAX(chapter) as chapter_id'))
             ->groupBy('volume_id')
             ->orderBy('volume_id', 'desc')
             ->first();

         return $chapter;
     }
}
