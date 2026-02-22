<?php
namespace App\Services;

use App\Models\NomorRMLog;
use Illuminate\Support\Facades\DB;

class NomorRMService
{
    public function reserveNoRM()
    {
        return DB::transaction(function () {

            $tahun = date('y');

            // Lock baris terakhir untuk mencegah race condition
            $last = NomorRMLog::where('tahun', $tahun)
                ->lockForUpdate()
                ->orderByDesc('urutan')
                ->first();

            $urutan = $last ? $last->urutan + 1 : 1;

            $no_rm = $tahun . str_pad($urutan, 6, '0', STR_PAD_LEFT);

            NomorRMLog::create([
                'tahun'  => $tahun,
                'urutan' => $urutan,
                'no_rm'  => $no_rm
            ]);

            return $no_rm;
        });
    }
}
