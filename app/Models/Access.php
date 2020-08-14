<?php

namespace App\Models;

use Fjord\Crud\Models\Traits\TrackEdits;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use TrackEdits;

    public $table = 'access';

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = ['username', 'project_id', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
