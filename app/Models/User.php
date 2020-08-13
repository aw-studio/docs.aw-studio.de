<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'service', 'avatar_url', 'payload', 'nickname',
        'token', 'refresh_token', 'docs_access'
    ];

    protected $hidden = [
        'token', 'refresh_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payload' => 'json',
        'docs_access' => 'boolean'
    ];

    protected $appends = ['profile_url'];

    /**
     * Get profile url.
     *
     * @return string
     */
    public function getProfileUrlAttribute()
    {
        return [
            'github' => "https://www.github.com/{$this->nickname}",
        ][$this->service] ?? '';
    }
}
