@extends('layouts.app')

@section('content')

<h2>Overview</h2>

<div class="card-grid">
    <div class="card">
        <div>Pasien Masuk Hari ini</div>
        <h1>{{ $todayTotal }}</h1>
    </div>

    <div class="card">
        <div>Pasien UGD</div>
        <h1>{{ $igd }}</h1>
    </div>

    <div class="card">
        <div>Rawat Inap</div>
        <h1>{{ $ranap }}</h1>
    </div>

    <div class="card">
        <div>Rawat Jalan</div>
        <h1>{{ $rajal }}</h1>
    </div>
</div>

<div class="chart-card">
    <div class="chart-header">
        <h4>Grafik Kunjungan Tahun {{ date('Y') }}</h4>
    </div>
    <canvas id="kunjunganChart"></canvas>
</div>


<h3>Daftar Pasien</h3>

<div class="table-box">
    <table>
        <thead>
            <tr>
                <th>No RM</th>
                <th>Nama</th>
                <th>Tgl Lahir</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $p)
            <tr>
                <td>{{ $p->no_rm }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->tgl_lahir }}</td>
                <td>{{ $p->jenis_kelamin }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<script>
const chartData = @json($chartData);
</script>

@endsection
