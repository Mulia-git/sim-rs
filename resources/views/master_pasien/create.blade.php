@extends('layouts.app')

@section('content')

@vite([
'resources/css/master_pasien_form.css',
'resources/js/master_pasien_form.js'
])

<div class="mp-container">

    <div class="mp-card">

        <h2 class="mp-title">Form Pendaftaran Pasien</h2>
        <p class="mp-sub">Khusus Pasien Baru</p>

        <form id="mp-form">
            @csrf

            @if($pasien)
                <input type="hidden" name="id" value="{{ $pasien->id }}">
            @endif

            <div class="mp-grid">

                {{-- ================= LEFT ================= --}}
                <div class="mp-box">

                    <h5>Biodata Pasien</h5>

                    <label>No RM</label>
                    <input type="text" name="no_rm"
                           value="{{ $pasien->no_rm ?? $no_rm }}"
                           class="mp-input">

                    <label>Nama Lengkap</label>
                    <input type="text" name="nama"
                           value="{{ $pasien->nama ?? '' }}"
                           class="mp-input">

                    <label>No Identitas</label>
                    <input type="text" name="no_identitas"
                           value="{{ $pasien->no_identitas ?? '' }}"
                           class="mp-input">

                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir"
                           value="{{ $pasien->tempat_lahir ?? '' }}"
                           class="mp-input">

                    <label>Tanggal Lahir</label>
                    <input type="text"
                           name="tgl_lahir"
                           id="mp_tgl_lahir"
                           value="{{ isset($pasien) ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') : '' }}"
                           class="mp-input">

                    <label>Umur (Tahun-Bulan-Hari)</label>
                    <input type="text"
                           id="mp_umur"
                           name="umur"
                           value="{{ $pasien->umur ?? '' }}"
                           class="mp-input mp-readonly">

                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="mp-input">
                        <option value="">Pilih</option>
                        <option value="Laki-laki"
                            {{ ($pasien->jenis_kelamin ?? '')=='Laki-laki'?'selected':'' }}>
                            Laki-laki
                        </option>
                        <option value="Perempuan"
                            {{ ($pasien->jenis_kelamin ?? '')=='Perempuan'?'selected':'' }}>
                            Perempuan
                        </option>
                    </select>

                    <label>Nama Ibu</label>
                    <input type="text" name="nama_ibu"
                           value="{{ $pasien->nama_ibu ?? '' }}"
                           class="mp-input">

                    <label>Golongan Darah</label>
                    <select name="gol_darah" class="mp-input">
                        <option value="">Pilih</option>
                        @foreach(['A','B','AB','O'] as $gd)
                        <option value="{{ $gd }}"
                          {{ ($pasien->gol_darah ?? '')==$gd?'selected':'' }}>
                          {{ $gd }}
                        </option>
                        @endforeach
                    </select>

                    <label>Status Nikah</label>
                    <select name="status_nikah" class="mp-input">
                        <option value="">Pilih</option>
                        <option value="Kawin"
                          {{ ($pasien->status_nikah ?? '')=='Kawin'?'selected':'' }}>
                          Kawin
                        </option>
                        <option value="Belum Kawin"
                          {{ ($pasien->status_nikah ?? '')=='Belum Kawin'?'selected':'' }}>
                          Belum Kawin
                        </option>
                    </select>

                    <label>Agama</label>
                    <select name="agama" class="mp-input">
                        <option value="">Pilih</option>
                        @foreach(['Islam','Kristen','Hindu','Budha'] as $a)
                        <option value="{{ $a }}"
                          {{ ($pasien->agama ?? '')==$a?'selected':'' }}>
                          {{ $a }}
                        </option>
                        @endforeach
                    </select>

                    <label>Pekerjaan</label>
                    <select name="pekerjaan" class="mp-input">
                        <option value="">Pilih</option>
                        @foreach($pekerjaanList as $p)
                        <option value="{{ $p->nama }}"
                          {{ ($pasien->pekerjaan ?? '')==$p->nama?'selected':'' }}>
                          {{ $p->nama }}
                        </option>
                        @endforeach
                    </select>

                    <label>Pendidikan</label>
                    <select name="pendidikan" class="mp-input">
                        <option value="">Pilih</option>
                        @foreach($pendidikanList as $p)
                        <option value="{{ $p->nama }}"
                          {{ ($pasien->pendidikan ?? '')==$p->nama?'selected':'' }}>
                          {{ $p->nama }}
                        </option>
                        @endforeach
                    </select>

                    <label>PND</label>
                    <input type="text" name="pnd"
                           value="{{ $pasien->pnd ?? '' }}"
                           class="mp-input">

                    <label>Penjamin</label>
                    <select name="penjamin" class="mp-input">
                        <option value="">Pilih</option>
                        @foreach($penjaminList as $p)
                        <option value="{{ $p->nama }}"
                          {{ ($pasien->penjamin ?? '')==$p->nama?'selected':'' }}>
                          {{ $p->nama }}
                        </option>
                        @endforeach
                    </select>

                    <label>No Kartu</label>
                    <input type="text" name="no_kartu"
                           value="{{ $pasien->no_kartu ?? '' }}"
                           class="mp-input">

                    <label>No HP</label>
                    <input type="text" name="no_hp"
                           value="{{ $pasien->no_hp ?? '' }}"
                           class="mp-input">

                    <label>Suku</label>
                    <select name="suku" class="mp-input">
                        <option value="">Pilih</option>
                        @foreach($sukuList as $s)
                        <option value="{{ $s->nama }}"
                          {{ ($pasien->suku ?? '')==$s->nama?'selected':'' }}>
                          {{ $s->nama }}
                        </option>
                        @endforeach
                    </select>

                    <label>Alamat Lengkap</label>
                    <textarea name="alamat_lengkap"
                        class="mp-input mp-textarea">{{ $pasien->alamat_lengkap ?? '' }}</textarea>

                </div>

                {{-- ================= RIGHT ================= --}}
                <div class="mp-box">

                    <h5>Biodata Keluarga</h5>

                    <label>Status Keluarga</label>
                    <select name="status_keluarga" class="mp-input">
                        <option value="">Pilih</option>
                        @foreach(['Suami/Istri','Orang Tua','Anak','Lainnya'] as $sk)
                        <option value="{{ $sk }}"
                          {{ ($pasien->status_keluarga ?? '')==$sk?'selected':'' }}>
                          {{ $sk }}
                        </option>
                        @endforeach
                    </select>

                    <label>Nama Keluarga</label>
                    <input type="text" name="nama_keluarga"
                           value="{{ $pasien->nama_keluarga ?? '' }}"
                           class="mp-input">

                    <label>Email</label>
                    <input type="email" name="email"
                           value="{{ $pasien->email ?? '' }}"
                           class="mp-input">

                    <label>Nama Penanggung Jawab</label>
                    <input type="text" name="nama_penanggung_jawab"
                           value="{{ $pasien->nama_penanggung_jawab ?? '' }}"
                           class="mp-input">

                    <label>Hubungan Penanggung Jawab</label>
                    <input type="text" name="hubungan_penanggung_jawab"
                           value="{{ $pasien->hubungan_penanggung_jawab ?? '' }}"
                           class="mp-input">

                    <label>No Identitas PJ</label>
                    <input type="text" name="no_identitas_pj"
                           value="{{ $pasien->no_identitas_pj ?? '' }}"
                           class="mp-input">

                    <label>No HP PJ</label>
                    <input type="text" name="no_hp_pj"
                           value="{{ $pasien->no_hp_pj ?? '' }}"
                           class="mp-input">

                    <label>Alamat PJ</label>
                    <textarea name="alamat_keluarga"
                        class="mp-input mp-textarea">{{ $pasien->alamat_keluarga ?? '' }}</textarea>

                </div>

            </div>

            <div class="mp-actions">
                <button type="button"
                        id="mp_btn_save"
                        class="mp-btn-primary">
                    Simpan
                </button>

                <a href="{{ route('master-pasien.index') }}"
                   class="mp-btn-danger">
                   Batal
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
