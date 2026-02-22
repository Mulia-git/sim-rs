<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BpjsConfig extends Model
{
    protected $table = 'bpjs_config';

    protected $fillable = [
        'base_url',
        'cons_id',
        'user_key',
        'secret_key',
        'kode_ppk',
        'nama_ppk',
        'is_active'
    ];
}