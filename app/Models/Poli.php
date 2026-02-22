<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    protected $table = 'master_poli';
    protected $primaryKey = 'poli_id';

    // Kalau primary key bukan auto increment integer
    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nama_poli',
        'poli_bpjs',
        'kode_bpjs',
        'kode_poli',
        'keterangan_awal',
        'keterangan_lanjutan',
        'kode_antrian',
        'status_poli',
        'tipe',
    ];

    public $timestamps = true; // karena ada created_at & updated_at
}
