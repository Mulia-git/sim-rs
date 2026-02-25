@extends('layouts.app')

@section('content')

<div class="container-fluid mt-4">

    {{-- HEADER PROFILE --}}
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">

            <div>
                <h4 class="fw-bold mb-1">{{ $pasien->nama }}</h4>
                <div class="text-muted">
                    No RM: {{ $pasien->no_rm }} |
                    NIK: {{ $pasien->no_identitas }}
                </div>
            </div>

            <div>
                <span class="badge bg-success fs-6">
                    AKTIF
                </span>
                <a href="{{ route('emr.pdf', $pasien->id) }}"
                   class="btn btn-danger ms-3">
                    ⬇ Export PDF
                </a>
            </div>

        </div>
    </div>


    {{-- LAST VISIT SUMMARY --}}
    @if($lastVisit)
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-primary text-white">
            Ringkasan Kunjungan Terakhir
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <strong>Tanggal:</strong><br>
                    {{ $lastVisit->tanggal_registrasi }}
                </div>
                <div class="col-md-3">
                    <strong>Poli:</strong><br>
                    {{ $lastVisit->poli }}
                </div>
                <div class="col-md-3">
                    <strong>Tgl Pulang:</strong><br>
                    {{ $lastVisit->tanggal_pulang ?? '-' }}
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong><br>
                    <span class="badge bg-info">
                        Selesai
                    </span>
                </div>
            </div>
        </div>
    </div>
    @endif


    <div class="row">

        {{-- TIMELINE HISTORY --}}
        <div class="col-md-6">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    Timeline History Pemeriksaan
                </div>

                <div class="card-body">

                    <div class="timeline">

                        @foreach($history as $h)
                        <div class="timeline-item mb-4">
                            <div class="timeline-dot bg-primary"></div>
                            <div class="timeline-content p-3 border rounded shadow-sm">

                                <div class="fw-bold">
                                    {{ $h->tanggal_registrasi }}
                                </div>

                                <div class="text-muted">
                                    Poli: {{ $h->poli }}
                                </div>

                                <div>
                                    Tgl Pulang:
                                    {{ $h->tanggal_pulang ?? '-' }}
                                </div>

                            </div>
                        </div>
                        @endforeach

                    </div>

                </div>
            </div>

        </div>


        {{-- TIMELINE MEDICAL RECORD --}}
        <div class="col-md-6">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    Timeline Rekam Medis
                </div>

                <div class="card-body">

                    @foreach($medicalRecords as $mr)

                    <div class="timeline-item mb-4">
                        <div class="timeline-dot bg-success"></div>

                        <div class="timeline-content p-3 border rounded shadow-sm">

                            <div class="fw-bold">
                                {{ $mr['tanggal'] }}
                            </div>

                            <div>
                                <strong>Dokter:</strong> {{ $mr['dokter'] }}
                            </div>

                            <div>
                                <strong>Diagnosa:</strong>
                                <span class="badge bg-warning text-dark">
                                    {{ $mr['diagnosa'] }}
                                </span>
                            </div>

                            <div>
                                <strong>Tindakan:</strong>
                                {{ $mr['tindakan'] }}
                            </div>

                        </div>
                    </div>

                    @endforeach

                </div>
            </div>

        </div>

    </div>

</div>


{{-- CUSTOM CSS --}}
<style>
.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
}

.timeline-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    position: absolute;
    left: -7px;
    top: 8px;
}

.timeline-content {
    margin-left: 20px;
}
</style>

@endsection