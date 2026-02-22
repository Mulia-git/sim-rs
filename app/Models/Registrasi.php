<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    protected $table = 'registrasi';
    protected $primaryKey = 'id_registrasi'; // sesuaikan dengan DB kamu
    public $timestamps = true;

    protected $fillable = [
        'id_pasien',
        'tanggal_registrasi',
        'cara_bayar',
        'cara_masuk',
        'no_rawat',
        'cara_pulang',
        'tanggal_pulang',
        'nama_penanggung_jawab',
        'hubungan_penanggung_jawab',
        'alamat_penanggung_jawab',
        'no_hp_penanggung_jawab',
        'suku_penanggung_jawab',
        'pendidikan_penanggung_jawab',
        'tgl_lahir_penanggung_jawab',
        'no_sep',
        'perusahaan_asuransi',
        'rujukan_dari',
        'faskes_perujuk',
        'sebab_dirujuk',
        'poli',
        'no_antrian',
        'pasien_lunas',
        'jadwal_dokter',
        'tipe_rawat',
        'jenis_kunjungan'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien');
    }
}
