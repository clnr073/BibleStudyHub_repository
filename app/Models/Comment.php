<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'text',
        'note_id',
        'user_id',
        ];
    
    /**
     * Noteモデルとのリレーションシップ
     */
     public function note()
     {
         return $this->belongsTo(Note::class);
     }
     
     /**
      * Userモデルとのリレーションシップ
      */
      public function user()
      {
         return $this->belongsTo(User::class);
      }
      
      /**
       * Testamentモデルとのリレーションシップ
       */
      public function testaments()
      {
         return $this->belongsToMany(Testament::class); 
      }
}
