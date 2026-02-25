<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RawatJalanController extends Controller
{
    public function index()
    {
        return view('rawat_jalan.index', [
            'title' => 'Pemeriksaan Poli Rawat Jalan'
        ]);
    }

    // =========================
    // CARI PASIEN
    // =========================
    public function cariPasien(Request $request)
    {
        $no_rm = $request->no_rm;
        $nama  = $request->nama;
        $poli  = $request->id_poli;

        $now = now()->format('Y-m-d');

        $query = DB::table('registrasi')
            ->select(
                'registrasi.*',
                'pasien.nama',
                'pasien.no_rm',
                'pasien.jenis_kelamin',
                'pasien.alamat_lengkap',
                'pcare_poli_fktp.nm_poli as nama_poli',
                'pcare_dokter.nm_dokter as nama_dokter'
            )
            ->join('pasien', 'registrasi.id_pasien', '=', 'pasien.id')
            ->leftJoin('pcare_poli_fktp', 'pcare_poli_fktp.kd_poli', '=', 'registrasi.poli')
            ->leftJoin('pcare_dokter', 'pcare_dokter.kd_dokter', '=', 'registrasi.kd_dokter_pcare')
            ->whereDate('registrasi.tanggal_registrasi', $now)
            ->where('registrasi.pasien_lunas', 'N')
            ->where('registrasi.tipe_rawat', 'J');

        if ($no_rm) {
            $query->where('pasien.no_rm', 'like', "%$no_rm%");
        }

        if ($nama) {
            $query->where('pasien.nama', 'like', "%$nama%");
        }

        if ($poli) {
            $query->where('registrasi.poli', 'like', "%$poli%");
        }

        $data = $query->orderBy('registrasi.no_antrian')
            ->get();

        return response()->json($data);
    }

    // =========================
    // LOAD PCARE PENDAFTARAN
    // =========================
    public function ByRegistrasi($id)
    {
        $row = DB::table('pcare_pendaftaran')
            ->where('id_registrasi', $id)
            ->first();

        if ($row) {
            return response()->json([
                'status' => 'success',
                'data'   => $row
            ]);
        }

        return response()->json([
            'status' => 'empty',
            'data'   => null
        ]);
    }

    // =========================
    // SIMPAN PCARE
    // =========================
    public function Save(Request $request)
    {
        $idReg = $request->id_registrasi;

        if (!$idReg) {
            return response()->json([
                'status' => 'error',
                'message'=> 'ID Registrasi wajib'
            ], 422);
        }

        $data = [
            'keluhan'       => $request->keluhan,
            'sistole'       => $request->sistole,
            'diastole'      => $request->diastole,
            'berat_badan'   => $request->berat_badan,
            'tinggi_badan'  => $request->tinggi_badan,
            'resp_rate'     => $request->resp_rate,
            'lingkar_perut' => $request->lingkar_perut,
            'heart_rate'    => $request->heart_rate,
            'rujuk_balik'   => $request->rujuk_balik,
            'updated_at'    => now()
        ];

        $exists = DB::table('pcare_pendaftaran')
            ->where('id_registrasi', $idReg)
            ->first();

        if ($exists) {
            DB::table('pcare_pendaftaran')
                ->where('id', $exists->id)
                ->update($data);
        } else {
            $data['id_registrasi'] = $idReg;
            $data['created_at'] = now();
            DB::table('pcare_pendaftaran')->insert($data);
        }

        return response()->json(['status' => 'success']);
    }
}