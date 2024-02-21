<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Connection;

class Tag extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'tag',
        'user_id',
        ];
    
    /**
     * Testamentモデルとのリレーションシップ
     */
     public function testaments()
     {
         return $this->belongsToMany(Testament::class);
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
       * Colorモデルとのリレーションシップ
       */
       public function color()
       {
          return $this->belongsTo(Color::class);
       }
       
       public function getPaginateByLimit(int $user_id, int $limit_count = 10)
       {
          // ログインユーザーのタグと友達のタグを取得するクエリを構築
          $query = $this->query()->where('user_id', $user_id);
          
          // 友達のidを取得する
          $friend_ids = Connection::where(function ($query) use ($user_id) {
              $query->where('approval', 1)
                    ->where(function ($query) use ($user_id) {
                        $query->orWhere('follow_id', $user_id)
                              ->orWhere('followed_id', $user_id);
                    });
          })->get()->flatMap(function ($record) use ($user_id) {
              return [$record->follow_id, $record->followed_id];
          })->reject(function ($friend_id) use ($user_id) {
              return $friend_id == $user_id;
          })->unique()->values()->toArray();
          
          // 友達のタグも含める
          $query->orWhereIn('user_id', $friend_ids);
          
          // updated_atで降順に並べたあと、limitで件数制限をかける
          return $query->orderBy('updated_at', 'DESC')->paginate($limit_count);
       }
}
