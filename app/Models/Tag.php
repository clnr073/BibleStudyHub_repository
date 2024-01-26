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
       
       public function getPaginateByLimit(int $limit_count = 10)
       {
          // updated_atで降順に並べたあと、limitで件数制限をかける
          return $this->orderBy('updated_at', 'DESC')->paginate($limit_count);
       }
}
