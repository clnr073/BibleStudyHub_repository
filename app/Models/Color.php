<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    
    /**
     * Tagモデルとのリレーションシップ
     */
     public function tags()
     {
         return $this->hasMany(Tag::class);
     }
}
