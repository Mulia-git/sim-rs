<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Registrasi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EmrController extends Controller
{
    public function show($id)
{
    $pasien = Pasien::findOrFail($id);

    $history = Registrasi::where('id_pasien', $id)
        ->orderBy('tanggal_registrasi', 'desc')
        ->get();

    $lastVisit = $history->first();

    $medicalRecords = [
        [
            'tanggal' => now()->subDays(10)->format('Y-m-d'),
            'dokter' => 'dr. Andi',
            'diagnosa' => 'Hipertensi',
            'tindakan' => 'Pemberian Amlodipine'
        ],
        [
            'tanggal' => now()->subDays(5)->format('Y-m-d'),
            'dokter' => 'dr. Budi',
            'diagnosa' => 'Diabetes Mellitus',
            'tindakan' => 'Kontrol gula darah'
        ]
    ];

    return view('emr.show', compact(
        'pasien',
        'history',
        'medicalRecords',
        'lastVisit'
    ));
}

    public function exportPdf($id)
    {
        $pasien = Pasien::findOrFail($id);

        $history = Registrasi::where('id_pasien', $id)
            ->orderBy('tanggal_registrasi', 'desc')
            ->get();

        $pdf = Pdf::loadView('emr.pdf', compact('pasien', 'history'));

        return $pdf->download('EMR_' . $pasien->nama . '.pdf');
    }
}