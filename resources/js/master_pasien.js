

$(function () {

    /* ======================================================
       OPEN MODAL CEK BPJS (FIX BOOTSTRAP 5)
    ====================================================== */
    $(document).on('click', '.btn-cek-bpjs', function () {

        let jenis = $(this).data('jenis')

        $('#jenis_pencarian').val(jenis)
        $('#no_peserta').val('')
        $('#hasilBpjs').html('')

        const modal = new bootstrap.Modal(
            document.getElementById('modalCekBpjs')
        )
        modal.show()
    })


    /* ======================================================
       PROSES CEK BPJS
    ====================================================== */
    $(document).on('click', '#btnProsesCek', function () {

        $('#hasilBpjs').html(`
            <div class="text-center">
                <div class="spinner-border text-primary"></div>
            </div>
        `)

        $.post('/bpjs/cek-peserta', {
            _token: $('meta[name="csrf-token"]').attr('content'),
            jenis: $('#jenis_pencarian').val(),
            no_peserta: $('#no_peserta').val(),
            tgl_sep: $('#tgl_sep').val()
        })
        .done(function (res) {

            if (!res.metaData || res.metaData.code != 200) {
                $('#hasilBpjs').html(`
                    <div class="alert alert-danger">
                        ${res.metaData?.message ?? 'Terjadi kesalahan'}
                    </div>
                `)
                return
            }

            let p = res.response.peserta

            let statusColor =
                p.statusPeserta.keterangan === 'AKTIF'
                    ? 'success'
                    : 'danger'

            $('#hasilBpjs').html(`
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold">${p.nama}</h5>
                        <hr>

                        <div class="row mb-2">
                            <div class="col-6 text-muted">No Kartu</div>
                            <div class="col-6 fw-bold">${p.noKartu}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 text-muted">NIK</div>
                            <div class="col-6">${p.nik}</div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 text-muted">Kelas</div>
                            <div class="col-6">
                                <span class="badge bg-primary">
                                    ${p.hakKelas.keterangan}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 text-muted">Jenis Peserta</div>
                            <div class="col-6">
                                ${p.jenisPeserta.keterangan}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 text-muted">Faskes</div>
                            <div class="col-6">
                                ${p.provUmum.nmProvider}
                            </div>
                        </div>

                        <div class="mt-3">
                            <span class="badge bg-${statusColor}">
                                ${p.statusPeserta.keterangan}
                            </span>
                        </div>

                    </div>
                </div>
            `)
        })
        .fail(function () {
            $('#hasilBpjs').html(`
                <div class="alert alert-danger">
                    Gagal terhubung ke server.
                </div>
            `)
        })
    })


    /* ======================================================
       RIWAYAT KUNJUNGAN
    ====================================================== */
    $(document).on('click', '.btn-history', function () {

        let id = $(this).data('id')

        $('#modal-registrasi-body').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary"></div>
                <div class="mt-2 text-muted">Loading riwayat...</div>
            </div>
        `)

        const modal = new bootstrap.Modal(
            document.getElementById('modalRegistrasi')
        )
        modal.show()

        $.get('/registrasi/history/' + id)
            .done(function (data) {

                if (!data || data.length === 0) {
                    $('#modal-registrasi-body').html(
                        '<div class="alert alert-light text-center">Tidak ada riwayat pemeriksaan.</div>'
                    )
                    return
                }

                let html = `
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tgl Registrasi</th>
                                    <th>Tgl Pulang</th>
                                    <th>Poli</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `

                data.forEach(item => {

                    let tglReg = new Date(item.tanggal_registrasi)
                        .toLocaleDateString('id-ID')

                    let tglPulang = item.tanggal_pulang
                        ? new Date(item.tanggal_pulang).toLocaleDateString('id-ID')
                        : '-'

                    html += `
                        <tr>
                            <td>${tglReg}</td>
                            <td>${tglPulang}</td>
                            <td><span class="badge bg-primary">${item.poli ?? '-'}</span></td>
                            <td>
                                ${
                                    item.tanggal_pulang
                                        ? '<span class="badge bg-success">Selesai</span>'
                                        : '<span class="badge bg-warning text-dark">Masih Dirawat</span>'
                                }
                            </td>
                        </tr>
                    `
                })

                html += `</tbody></table></div>`

                $('#modal-registrasi-body').html(html)
            })
            .fail(function () {
                $('#modal-registrasi-body').html(
                    '<div class="alert alert-danger text-center">Gagal mengambil data.</div>'
                )
            })
    })


    /* ======================================================
       AJAX SEARCH + PAGINATION
    ====================================================== */
    function loadData(url = null) {

        $('#searchLoading').removeClass('d-none')

        $.ajax({
            url: url ?? '/master-pasien',
            data: { search: $('#searchGlobal').val() },
            success: function (data) {
                $('#tableContainer').html(data)
            },
            complete: function () {
                $('#searchLoading').addClass('d-none')
            }
        })
    }

    $('#btnSearch').on('click', loadData)

    $('#searchGlobal').on('keyup', function (e) {
        if (e.key === 'Enter') loadData()
    })

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault()
        loadData($(this).attr('href'))
    })


    /* ======================================================
       NONAKTIF PASIEN
    ====================================================== */
    $(document).on('click', '.btn-nonaktif', function () {

        let id = $(this).data('id')

        Swal.fire({
            title: 'Yakin nonaktifkan pasien?',
            icon: 'warning',
            showCancelButton: true
        }).then(result => {

            if (!result.isConfirmed) return

            $.post('/master-pasien/nonaktif/' + id, {
                _token: $('meta[name="csrf-token"]').attr('content')
            })
            .done(function (res) {

                if (res.status === 'success') {
                    Swal.fire('Berhasil', res.message, 'success')
                    loadData()
                }
            })
        })
    })

})