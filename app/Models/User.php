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
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Sluggable;
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

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => function ($model) {
                    return $model->isDirty('name');
                },
                'method' => function ($string, $separator) {
                    return Str::random(10);
                },
                'unique' => true,
                'slugEngineOptions' => [
                    'regexp' => '/([^A-Za-z0-9]|-)+/',
                    'separator' => '-',
                ],

            ]
        ];
    }

     public static function findBySlugOrFail($slug)
    {
        return static::where('slug', $slug)->firstOrFail();
    }

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
