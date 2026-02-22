<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $year = date('Y');

        // Overview Box
        $todayTotal = DB::table('registrasi')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $igd = DB::table('registrasi')
            ->where('tipe_rawat', 'G')->count();

        $ranap = DB::table('registrasi')
            ->where('tipe_rawat', 'I')->count();

        $rajal = DB::table('registrasi')
            ->where('tipe_rawat', 'J')->count();

        // Top Pasien
        $patients = DB::table('registrasi as r')
            ->leftJoin('pasien as p','p.id','=','r.id_pasien')
            ->select('p.no_rm','p.nama','p.tgl_lahir','p.jenis_kelamin')
            ->orderByDesc('r.created_at')
            ->limit(10)
            ->get();

        // Data grafik per bulan
        $chartData = DB::table('registrasi')
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at',$year)
            ->groupBy('month')
            ->pluck('total','month');

    
        return view('dashboard.index', compact(
            'todayTotal','igd','ranap','rajal',
            'patients','chartData'
        ));
    }
}
