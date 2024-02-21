<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //論理削除
use App\Models\Connection;

class Note extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
        'title',
        'text',
        'user_id',
        'public',
        'image_url',
        ];
    
    /**
     * Testamentモデルとのリレーションシップ
     */
    public function testaments()
    {
        return $this->belongsToMany(Testament::class);
    }
    
    /**
     * Tagモデルとのリレーションシップ
     */
     public function tags()
     {
         return $this->belongsToMany(Tag::class);
     }
     
     /**
      * Commentモデルとのリレーションシップ
      */
      public function comments()
     {
         return $this->hasMany(Comment::class);
     }
     
     /**
      * Userモデルとのリレーションシップ
      */
      public function user()
      {
          return $this->belongsTo(User::class);
      }
      
      public function getPaginateByLimit(int $user_id, int $limit_count = 10)
      {
          // ログインユーザーのノートと友達のノートを取得するクエリを構築
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
        
          // $friend_idsを使って、友達の公開ノートも含める
          $query->orWhere(function ($query) use ($friend_ids) {
              $query->whereIn('user_id', $friend_ids)
                    ->where('public', 1);
          });
          
          // updated_atで降順に並べたあと、limitで件数制限をかける
          return $query->orderBy('updated_at', 'DESC')->paginate($limit_count);
      }
      
      public function getByTag($user_id, $tag_id)
      {
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
          
          $query = $this::whereHas('tags', function ($query) use ($tag_id) {
                          $query->where('tags.id', $tag_id);
                      })
                      ->where(function ($query) use ($user_id, $friend_ids) {
                          $query->where('user_id', $user_id)
                                ->orWhere(function ($query) use ($friend_ids) {
                                    $query->whereIn('user_id', $friend_ids)
                                          ->where('public', 1);
                                });
                      })
                      ->orderBy('updated_at', 'DESC');
          
          return $query->paginate(5)->appends(['tag' => $tag_id]);
      }
}
