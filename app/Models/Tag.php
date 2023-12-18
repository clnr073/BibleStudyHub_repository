<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
