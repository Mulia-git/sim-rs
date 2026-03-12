<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AkunRekening;

class AkunRekeningController extends Controller
{

    public function index()
    {
        return view('keuangan.akun_rekening.index');
    }

    public function data()
    {
        $data = AkunRekening::orderBy('kode')->get();

        return response()->json($data);
    }

    public function store(Request $request)
    {

        $data = AkunRekening::create([
            'kode' => $request->kode,
            'nama_rekening' => $request->nama_rekening,
            'tipe' => $request->tipe,
            'saldo_awal' => $request->saldo_awal,
            'balance' => $request->saldo_awal
        ]);

        return response()->json([
            'status' => true
        ]);
    }
}
