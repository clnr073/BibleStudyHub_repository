<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    
    /**
     * Answerモデルとのリレーションシップ
     */
     public function answers()
     {
         return $this->hasMany(Answer::class);
     }
     
     /**
      * Tagモデルとのリレーションシップ
      */
      public function tags()
      {
          return $this->belongsToMany(Tag::class);
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
