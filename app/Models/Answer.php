<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    
    /**
     * Questionモデルとのリレーションシップ
     */
     public function question()
     {
         return $this->belongsTo(Question::class);
     }
     
     /**
      * Testamentモデルとのリレーションシップ
      */
      public function testaments()
      {
          return $this->belongsToMany(Testament::class);
      }
      
      /**
      * Userモデルとのリレーションシップ
      */
      public function user()
      {
          return $this->belongsTo(User::class);
      }
}
