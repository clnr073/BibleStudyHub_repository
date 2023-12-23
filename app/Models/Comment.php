<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; //論理削除

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;
    
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
      
      public function getPaginateByLimit(int $limit_count = 2)
      {
          // updated_atで降順に並べたあと、limitで件数制限をかける
          return $this->orderBy('updated_at', 'DESC')->paginate($limit_count);
      }
}
