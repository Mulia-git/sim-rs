<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjamin extends Model
{
    protected $table = 'penjamin';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama'
    ];

    public $timestamps = false;
}
