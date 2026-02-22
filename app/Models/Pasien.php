<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'no_rm', 'nama', 'tgl_lahir', 'jenis_kelamin',
        'nama_ibu', 'gol_darah', 'status_nikah', 'agama',
        'pekerjaan', 'pendidikan', 'pnd', 'penjamin',
        'no_kartu', 'suku', 'alamat_lengkap',
        'status_keluarga', 'nama_keluarga',
        'no_ktp', 'no_hp_keluarga', 'email',
        'alamat_keluarga',
        'id_provinsi', 'id_kota', 'id_kecamatan', 'id_desa',
        'nama_penanggung_jawab', 'hubungan_penanggung_jawab',
        'no_identitas', 'no_identitas_pj',
        'no_hp', 'no_hp_pj',
        'tempat_lahir','umur',
        'patien_id',
        'no_bpjs',
        'ihs_number',
       'updated_at',
        'nik'
    ];
    protected $casts = [
    'tgl_lahir' => 'date',
];

}
