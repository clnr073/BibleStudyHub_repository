<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    
    /**
     * Testamentモデルとのリレーションシップ
     */
     public function testaments()
     {
         return $this->belongsToMany(Testament::class);
     }
}
