<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }
    
    /**
     * Connectionモデルとのリレーションシップ
     */
     public function following()
     {
         return $this->hasMany(Connection::class, 'follow_id');
     }
     
     public function followers()
     {
         return $this->hasMany(Connection::class, 'followed_id');
     }
    
    /**
     * Noteモデルとのリレーションシップ
     */
     public function notes()
     {
         return $this->hasMany(Note::class);
     }
     
     /**
     * Commentモデルとのリレーションシップ
     */
     public function comments()
     {
         return $this->hasMany(Comment::class);
     }
     
     /**
     * Answerモデルとのリレーションシップ
     */
     public function answers()
     {
         return $this->hasMany(Answer::class);
     }
     
     /**
     * Questionモデルとのリレーションシップ
     */
     public function questions()
     {
         return $this->hasMany(Question::class);
     }
}
