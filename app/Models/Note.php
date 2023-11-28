<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'text',
        'user_id',
        'public',
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
}
