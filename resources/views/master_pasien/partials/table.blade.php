<div class="d-flex justify-content-between align-items-center mb-2">
    <div class="text-muted">
        Total Data: <strong>{{ $pasien->total() }}</strong>
    </div>

    <div>
        {{ $pasien->links() }}
    </div>
</div>

<table class="table table-hover align-middle">
    <thead>
        <tr>
            <th>No RM</th>
            <th>Nama</th>
            <th>Tgl Lahir</th>
            <th>JK</th>
            <th>NIK</th>
            <th>No Kartu</th>
            <th>No HP</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
        @forelse($pasien as $row)
        <tr>
            <td>
                <div class="dropdown">
                    <a class="dropdown-toggle fw-bold text-dark"
                       data-bs-toggle="dropdown">
                        {{ $row->no_rm }}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="javascript:void(0)"
                            class="dropdown-item btn-cek-bpjs"
                            data-jenis="kartu">
                                [Vclaim] Cek Nomor Kartu
                            </a>
                        </li>

                        <li>
                            <a href="javascript:void(0)"
                            class="dropdown-item btn-cek-bpjs"
                            data-jenis="nik">
                                [Vclaim] Cek Nomor KTP
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item">Elektronik Rekam Medis</a></li>
                    </ul>
                </div>
            </td>

            <td>{{ $row->nama }}</td>
            <td>{{ \Carbon\Carbon::parse($row->tgl_lahir)->format('d-m-Y') }}</td>
            <td>{{ $row->jenis_kelamin }}</td>
            <td>{{ $row->no_identitas }}</td>
            <td>{{ $row->no_kartu }}</td>
            <td>{{ $row->no_hp }}</td>

            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-secondary dropdown-toggle"
                            data-bs-toggle="dropdown">
                        Aksi
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                       <li>
                            <a href="{{ route('registrasi.create', $row->id) }}"
                            class="dropdown-item">
                                Registrasi
                            </a>
                        </li>

                        <li>
                             <li>
                                <a href="javascript:void(0)"
                                class="dropdown-item btn-history"
                                data-id="{{ $row->id }}">
                                    Riwayat Kunjungan
                                </a>
                            </li>
                        <li>
                        <li><a href="/master-pasien/edit/{{ $row->id }}"
                               class="dropdown-item">Edit</a></li>
                        <li>
                            <a href="javascript:void(0)"
                               class="dropdown-item text-danger btn-nonaktif"
                               data-id="{{ $row->id }}">
                                Nonaktif
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center text-muted">
                Tidak ada data
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between align-items-center mt-3">
    <div>
        Menampilkan {{ $pasien->firstItem() }} -
        {{ $pasien->lastItem() }}
        dari {{ $pasien->total() }} data
    </div>

    <div>
        {{ $pasien->links() }}
    </div>
</div>

<div class="modal fade" id="modalRegistrasi" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Riwayat Pemeriksaan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body" id="modal-registrasi-body">
        <p>Loading...</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">
          Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalCekBpjs" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Cek Kepesertaan BPJS</h5>
        <button type="button" class="btn-close btn-close-white"
                data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="jenis_pencarian">

        <div class="mb-3">
          <label class="form-label">Nomor Kartu / NIK</label>
          <input type="text" class="form-control" id="no_peserta">
        </div>

        <div class="mb-3">
          <label class="form-label">Tanggal Pelayanan</label>
          <input type="date" class="form-control" id="tgl_sep"
                 value="{{ date('Y-m-d') }}">
        </div>

        <button class="btn btn-success w-100" id="btnProsesCek">
          🔎 Cek Kepesertaan
        </button>

        <hr>

        <div id="hasilBpjs"></div>

      </div>
    </div>
  </div>
</div>