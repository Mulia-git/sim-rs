<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BpjsController extends Controller
{

    // =========================================
    // CEK PESERTA
    // =========================================
    public function cekPeserta(Request $r)
    {
        $endpoint = "Peserta/nokartu/{$r->no_peserta}/tglSEP/{$r->tgl_sep}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // HISTORY SEP
    // =========================================
    public function history(Request $r)
    {
        $endpoint = "SEP/Histori?noKartu={$r->no_kartu_txt}&tglAwal={$r->search_tanggal_awal}&tglAkhir={$r->search_tanggal_akhir}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // LIST RUJUKAN
    // =========================================
    public function rujukanList(Request $r)
    {
        $endpoint = "Rujukan/Peserta/{$r->no_kartu}?jenisPelayanan={$r->jenis_pelayanan}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // DETAIL RUJUKAN
    // =========================================
    public function rujukanDetail(Request $r)
    {
        $endpoint = "Rujukan/{$r->nomor_rujukan}?jenisPelayanan={$r->faskes_perujuk}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // REFERENSI DIAGNOSA
    // =========================================
    public function referensiDiagnosa($kode)
    {
        $endpoint = "referensi/diagnosa/{$kode}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // REFERENSI POLI
    // =========================================
    public function referensiPoli($kode)
    {
        $endpoint = "referensi/poli/{$kode}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // REFERENSI DPJP
    // =========================================
    public function getDpjp(Request $r)
    {
        $endpoint = "referensi/dokter/pelayanan/{$r->jenisPelayanan}/tglPelayanan/{$r->tglPelayanan}/Spesialis/{$r->kodeSpesialis}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // CARI SKDP (SPRI)
    // =========================================
    public function cariSpriNoka(Request $r)
    {
        $endpoint = "RencanaKontrol/ListRencanaKontrol/Bulan/{$r->bulan}/Tahun/{$r->tahun}/Nokartu/{$r->noKartu}/filter/{$r->filter}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // CARI SURAT KONTROL
    // =========================================
    public function rencanaCariNomorSurat(Request $r)
    {
        $endpoint = "RencanaKontrol/nosurkon/{$r->kode}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // RANDOM QUESTION
    // =========================================
    public function getRandomQuestion(Request $r)
    {
        $endpoint = "Rujukan/JumlahSEP/{$r->noka}/tglPelayanan/{$r->tglPelayanan}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // KIRIM JAWABAN
    // =========================================
    public function kirimJawaban(Request $r)
    {
        $endpoint = "Rujukan/JumlahSEP/{$r->noka}";
        return response()->json(vclaim_get($endpoint));
    }


    // =========================================
    // INSERT SEP
    // =========================================
    public function sepInsertDua(Request $r)
    {
        $endpoint = "SEP/2.0/insert";

        $payload = $r->all(); // sesuaikan jika perlu mapping ulang

        return response()->json(vclaim_post($endpoint, $payload));
    }


    // =========================================
    // UPDATE TGL PULANG
    // =========================================
    public function sepUpdateTglPulangDua(Request $r)
    {
        $endpoint = "SEP/2.0/updtglplg";

        $payload = $r->all();

        return response()->json(vclaim_put($endpoint, $payload));
    }


    // =========================================
    // PRINT SEP
    // =========================================
    public function printSep($noSep)
    {
        return view('bpjs.print', compact('noSep'));
    }
    public function referensiPropinsi()
{
    return response()->json(vclaim_get("referensi/propinsi"));
}
}