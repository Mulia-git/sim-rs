<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomorRMLog extends Model
{
    protected $table = 'nomor_rm_log';

    protected $fillable = [
        'tahun',
        'urutan',
        'no_rm'
    ];

    public $timestamps = true;
}
