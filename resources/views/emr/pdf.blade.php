<h3>Elektronik Rekam Medis</h3>

<p>
Nama: {{ $pasien->nama }} <br>
No RM: {{ $pasien->no_rm }} <br>
NIK: {{ $pasien->no_identitas }}
</p>

<hr>

<h4>History Pemeriksaan</h4>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <tr>
        <th>Tgl Registrasi</th>
        <th>Tgl Pulang</th>
        <th>Poli</th>
    </tr>

    @foreach($history as $h)
        <tr>
            <td>{{ $h->tanggal_registrasi }}</td>
            <td>{{ $h->tanggal_pulang ?? '-' }}</td>
            <td>{{ $h->poli }}</td>
        </tr>
    @endforeach
</table>