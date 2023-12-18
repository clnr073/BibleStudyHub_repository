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
      
      public function recordSectionInfoByVolumeAndChapter()
      {
          //section番号を取得するメソッドを書く
      }
}
