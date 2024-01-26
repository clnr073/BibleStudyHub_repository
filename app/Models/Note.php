<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //論理削除

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
      
      public function getPaginateByLimit(int $limit_count = 10)
      {
          // updated_atで降順に並べたあと、limitで件数制限をかける
          return $this->orderBy('updated_at', 'DESC')->paginate($limit_count);
      }
      
      public function getByTag($tag_id, int $limit_count = 5)
      {
          return $this::whereHas('tags', function ($query) use ($tag_id) {
              $query->where('tags.id', $tag_id);
          })->orderBy('updated_at', 'DESC')->paginate($limit_count)
          ->appends(['tag' => $tag_id]); //$tag_idの値でクエリパラメータを生成
      }

}
