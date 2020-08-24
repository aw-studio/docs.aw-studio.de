<?php

namespace App\Models;

use Fjord\Crud\Models\Traits\TrackEdits;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use TrackEdits;

    /**
     * Databse table name.
     *
     * @var string
     */
    public $table = 'access';

    /**
     * Fillable attributes.
     *
     * @var array
     */
    protected $fillable = ['username', 'repo', 'active'];

    /**
     * Casted attributes.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];
}
