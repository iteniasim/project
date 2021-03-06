<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id', 'name', 'username', 'email', 'password', 'avatar_path', 'user_type', 'blocked',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_type'         => 'boolean',
        'blocked'           => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'username';
    }

    public function threads()
    {
        return $this->hasMany('App\Thread')->latest();
    }

    public function activity()
    {
        return $this->hasMany('App\Activity');
    }

    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function read($thread)
    {
        cache()->forever(
            $this->visitedThreadCacheKey($thread),
            Carbon::now()
        );
    }

    public function lastReply()
    {
        return $this->hasOne('App\Reply')->latest();
    }

    public function getAvatarPathAttribute($avatar)
    {
        return asset($avatar ? 'storage/' . $avatar : 'images/avatars/default.png');
    }

    public function isAdmin()
    {
        return in_array($this->username, ['john_doe']);
    }
}
