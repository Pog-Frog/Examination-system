<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\UserResetPassword;
use App\Notifications\UserVerifyEmail;
use App\Models\Email_verfication;

class User extends Authenticatable
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
        'gender',
        'photo',
        'phone',
        'degree',
        'institute',
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

    public function sendPasswordResetNotification($token){
        $this->notify(new UserResetPassword($token));
    }

    public function sendEmailVerificationNotification(){
        $token = $this->createToken($this->name)->plainTextToken;
        Email_verfication::create([
            'email' => $this->email,
            'token' => $token,
        ]);
        $this->notify(new UserVerifyEmail($token, $this->email));
    }

    public function getFirstname(){
        $name = $this->name;
        $name = explode(' ', $name);
        return $name[0];
    }
}
