<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'follow_id',
        'followed_id',
        'approval',
        ];
    
    /**
     * Userモデルとのリレーションシップ
     */
     public function followedBy()
     {
         return $this->belongsTo(User::class, 'follow_id');
     }
     
     public function follows()
     {
         return $this->belongsTo(User::class, 'followed_id');
     }

}
