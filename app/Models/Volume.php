<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volume extends Model
{
    use HasFactory;
    
    /**
     * Testamentモデルとのリレーションシップ
     */
    public function testament()
    {
        return $this->hasOne(Testament::class);
    }
}
