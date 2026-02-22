<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    protected $table = 'master_pendidikan';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama'
    ];

    public $timestamps = false;
}
