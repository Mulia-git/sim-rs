import 'bootstrap/dist/css/bootstrap.min.css'
import '../css/master_pasien.css'

import $ from 'jquery'
import * as bootstrap from 'bootstrap'
import Swal from 'sweetalert2'

window.$ = window.jQuery = $
window.bootstrap = bootstrap

$(document).ready(function () {
    // 📜 Riwayat Kunjungan
    
// ==============================
// OPEN MODAL CEK BPJS
// ==============================
$(document).on('click', '.btn-cek-bpjs', function () {

  
    let jenis = $(this).data('jenis')

    $('#jenis_pencarian').val(jenis)
    $('#no_peserta').val('')
    $('#hasilBpjs').html('')

    $('#modalCekBpjs').modal('show')
})


// ==============================
// PROSES CEK
// ==============================
$('#btnProsesCek').on('click', function () {

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
    }, function (res) {

        if (res.metaData.code != 200) {
            $('#hasilBpjs').html(`
                <div class="alert alert-danger">
                    ${res.metaData.message}
                </div>
            `)
            return
        }

        let p = res.response.peserta

        let statusColor = p.statusPeserta.keterangan == 'AKTIF'
            ? 'success'
            : 'danger'

        let html = `
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
                        <div class="col-6">${p.jenisPeserta.keterangan}</div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6 text-muted">Faskes</div>
                        <div class="col-6">${p.provUmum.nmProvider}</div>
                    </div>

                    <div class="mt-3">
                        <span class="badge bg-${statusColor}">
                            ${p.statusPeserta.keterangan}
                        </span>
                    </div>

                </div>
            </div>
        `

        $('#hasilBpjs').html(html)

    }).fail(function () {
        $('#hasilBpjs').html(`
            <div class="alert alert-danger">
                Gagal terhubung ke server.
            </div>
        `)
    })
})

$(document).on('click', '.btn-history', function () {

    let id = $(this).data('id')

    $('#modal-registrasi-body').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary"></div>
            <div class="mt-2 text-muted">Loading riwayat...</div>
        </div>
    `)

    let modal = new bootstrap.Modal(document.getElementById('modalRegistrasi'))
    modal.show()

    $.get('/registrasi/history/' + id, function (data) {

        if (data.length === 0) {
            $('#modal-registrasi-body').html(
                '<div class="alert alert-light text-center">Tidak ada riwayat pemeriksaan.</div>'
            )
            return
        }

        function badgePoli(poli) {
            if (!poli) return '<span class="badge bg-secondary">-</span>'

            let map = {
                'Umum': 'bg-primary',
                'Anak': 'bg-success',
                'Bedah': 'bg-danger',
                'Kandungan': 'bg-warning text-dark',
                'Gigi': 'bg-info text-dark'
            }

            let color = map[poli] ?? 'bg-dark'

            return `<span class="badge ${color}">${poli}</span>`
        }

        function badgeStatus(tglPulang) {
            if (!tglPulang) {
                return `<span class="badge bg-warning text-dark">Masih Dirawat</span>`
            }
            return `<span class="badge bg-success">Selesai</span>`
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
                    <td>${badgePoli(item.poli)}</td>
                    <td>${badgeStatus(item.tanggal_pulang)}</td>
                </tr>
            `
        })

        html += `
                    </tbody>
                </table>
            </div>
        `

        $('#modal-registrasi-body').html(html)

    }).fail(function () {
        $('#modal-registrasi-body').html(
            '<div class="alert alert-danger text-center">Gagal mengambil data.</div>'
        )
    })
})

    function loadData(url = null) {

        $('#searchLoading').removeClass('d-none')

        $.ajax({
            url: url ?? '/master-pasien',
            data: {
                search: $('#searchGlobal').val()
            },
            success: function (data) {
                $('#tableContainer').html(data)
            },
            complete: function () {
                $('#searchLoading').addClass('d-none')
            }
        })
    }

    // 🔎 Search button
    $('#btnSearch').on('click', function () {
        loadData()
    })

    // 🔎 Enter key
    $('#searchGlobal').on('keyup', function (e) {
        if (e.key === 'Enter') {
            loadData()
        }
    })

    // 📄 Pagination click (AJAX)
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault()
        loadData($(this).attr('href'))
    })

    // ❌ Nonaktif
    $(document).on('click', '.btn-nonaktif', function () {

        let id = $(this).data('id')

        Swal.fire({
            title: 'Yakin nonaktifkan pasien?',
            icon: 'warning',
            showCancelButton: true
        }).then((result) => {

            if (result.isConfirmed) {

                $.post('/master-pasien/nonaktif/' + id, {
                    _token: document.querySelector('meta[name="csrf-token"]').content
                }, function (res) {

                    if (res.status === 'success') {
                        Swal.fire('Berhasil', res.message, 'success')
                        loadData()
                    }

                })
            }
        })
    })

$(document).on('click', '.pagination a', function (e) {
    e.preventDefault()
    loadData($(this).attr('href'))
})

})
