<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunRekening extends Model
{
    protected $table = 'akun_rekening';

    protected $fillable = [
        'kode',
        'nama_rekening',
        'tipe',
        'saldo_awal',
        'balance'
    ];
}
