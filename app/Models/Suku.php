<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Suku extends Model
{
    protected $table = 'suku';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama'
    ];

    public $timestamps = false;
}
