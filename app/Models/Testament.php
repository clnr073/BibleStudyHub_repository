<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testament extends Model
{
    use HasFactory;
    
    /**
     * Volumeモデルとのリレーションシップ
     */
    public function volume()
    {
        return $this->belongsTo(Volume::class);
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
      * Answerモデルとのリレーションシップ
      */
      public function answers()
     {
         return $this->belongsToMany(Answer::class);
     }
}
