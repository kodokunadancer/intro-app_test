<?php

declare(strict_types=1);

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    /**
     * リレーションシップ - groupsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function groups()
    {
        return $this->hasMany('App\Group', 'author_id', 'id', 'groups');
    }

    /**
     * リレーションシップ - groupsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function groupUser()
    {
        return $this->belongsToMany('App\Group');
    }

    /**
     * リレーションシップ - profilesテーブル
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function profiles()
    {
        return $this->hasMany('App\Profile');
    }
}
