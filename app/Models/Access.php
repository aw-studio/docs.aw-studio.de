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
    protected $fillable = ['username', 'repo', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];
}
