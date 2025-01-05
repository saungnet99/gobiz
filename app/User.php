<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ProtoneMedia\LaravelVerifyNewEmail\MustVerifyNewEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use MustVerifyNewEmail, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'profile_image', 'name', 'email', 'email_verified_at', 'auth_type', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsTo('App\Role');
    }

    // Send new email verification
    public function sendEmailVerificationNotification()
    {
        // Queries
        $config = DB::table('config')->get();

        // Check email verification system is enabled
        if ($config[43]->config_value == '1') {
            try {
                $this->newEmail($this->getEmailForVerification());
            } catch (\Throwable $th) {
                return false;
            }
        }
    }
}
