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
}
