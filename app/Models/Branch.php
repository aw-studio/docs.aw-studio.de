<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name', 'title', 'active', 'project_id', 'default',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected function project()
    {
        return $this->belongsTo(Project::class);
    }
}
