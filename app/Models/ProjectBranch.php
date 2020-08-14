<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectBranch extends Model
{
    protected $fillable = [
        'branch', 'title', 'active', 'project_id', 'default',
    ];

    protected function project()
    {
        return $this->belongsTo(Project::class);
    }
}
