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
        'token', 'refresh_token', 'docs_access',
    ];

    /**
     * Hidden attributes.
     *
     * @var array
     */
    protected $hidden = [
        'token', 'refresh_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'payload'     => 'json',
        'docs_access' => 'boolean',
    ];

    /**
     * Appended attributes.
     *
     * @var array
     */
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

    /**
     * Determines if the user has access to the give repository.
     *
     * @param  string $repo
     * @return bool
     */
    public function hasAccessTo($repo)
    {
        return Access::where('active', true)
            ->where('username', $this->nickname)
            ->where('repo', $repo)
            ->exists();
    }
}
