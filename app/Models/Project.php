<?php

namespace App\Models;

use Fjord\Crud\Models\Traits\Sluggable;
use Fjord\Crud\Models\Traits\TrackEdits;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use TrackEdits, Sluggable;

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = ['title', 'name', 'token', 'path'];

    /**
     * Hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['token'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function branches()
    {
        return $this->hasMany(ProjectBranch::class);
    }

    public function access()
    {
        return $this->hasMany(Access::class);
    }

    public function getCloneUrlAttribute()
    {
        if (! $this->token) {
            return "https://github.com/{$this->name}";
        }

        return "https://{$this->token}@github.com/{$this->name}";
    }
}