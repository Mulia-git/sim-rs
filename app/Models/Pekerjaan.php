<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    protected $table = 'master_pekerjaan';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nama'
    ];

    public $timestamps = false;
}
