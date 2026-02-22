<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\MasterPasienController;
use App\Http\Controllers\BpjsController;

Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

Route::middleware('auth')->group(function () {
 Route::get('/dashboard', [DashboardController::class,'index'])
        ->name('dashboard');
        Route::prefix('registrasi')->group(function(){

    Route::get('/',[RegistrasiController::class,'index']);
  
    Route::get('/history/{id}',[RegistrasiController::class,'history']);
    Route::get('/datatable-rajal',[RegistrasiController::class,'datatableRajal']);
    Route::get('/cetak/{no_rawat}',[RegistrasiController::class,'cetakEtiket']);
   Route::get('/create/{id}', [RegistrasiController::class,'create'])->name('registrasi.create');
    Route::post('/store', [RegistrasiController::class,'store'])->name('registrasi.store');



      Route::get('/api/dokter-igd', [RegistrasiController::class, 'dokterIgd']);
    Route::post('/api/jadwal-dokter', [RegistrasiController::class, 'jadwalDokter']);

});

Route::prefix('master-pasien')->group(function () {
    Route::get('/', [MasterPasienController::class, 'index']);
    Route::get('/datatable', [MasterPasienController::class, 'datatable']);
    Route::post('/store', [MasterPasienController::class, 'store']);
    Route::get('/{id}', [MasterPasienController::class, 'show']);
    Route::post('/nonaktif/{id}', [MasterPasienController::class, 'destroy']);
});

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/registrasi/cek-ihs', [RegistrasiController::class, 'cekIhs']);
    Route::post('/registrasi/cek-bpjs', [RegistrasiController::class, 'cekBpjs']);
    Route::get('/registrasi/cetak-etiket/{no_rawat}', 
    [RegistrasiController::class, 'cetakEtiket']
);
Route::prefix('bpjs')->group(function () {

    Route::get('/cek-peserta', [BpjsController::class, 'cekPeserta']);
    Route::get('/history', [BpjsController::class, 'history']);

    Route::get('/rujukan-list', [BpjsController::class, 'rujukanList']);
    Route::get('/rujukan-detail', [BpjsController::class, 'rujukanDetail']);

    Route::get('/referensi-diagnosa/{kode}', [BpjsController::class, 'referensiDiagnosa']);
    Route::get('/referensi-poli/{kode}', [BpjsController::class, 'referensiPoli']);

    Route::get('/dpjp', [BpjsController::class, 'getDpjp']);

    Route::get('/cari-spri', [BpjsController::class, 'cariSpriNoka']);
    Route::get('/surat-kontrol', [BpjsController::class, 'rencanaCariNomorSurat']);

    Route::get('/question', [BpjsController::class, 'getRandomQuestion']);
    Route::get('/kirim-jawaban', [BpjsController::class, 'kirimJawaban']);

    Route::post('/insert-sep', [BpjsController::class, 'sepInsertDua']);
    Route::put('/update-tgl-pulang', [BpjsController::class, 'sepUpdateTglPulangDua']);

    Route::get('/print/{noSep}', [BpjsController::class, 'printSep']);
    Route::get('/referensi-propinsi', [BpjsController::class, 'referensiPropinsi']);
});
});


Route::get('/', function () {
    return redirect()->route('login');
});
