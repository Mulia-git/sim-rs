import Swal from 'sweetalert2';
import $ from 'jquery';

function validate(class_name) {

    var fields = document.getElementsByClassName(class_name);
    var invalid = 0;

    for (var i = 0; i < fields.length; i++) {

        if ($(fields[i]).val() == '' || !$(fields[i]).val()) {
            $(fields[i]).addClass('is-invalid');
            invalid++;
        } else {
            $(fields[i]).removeClass('is-invalid');
        }
    }

    return invalid === 0;
}



// ==========================================
// GET 90 DAYS AGO DATE
// ==========================================

function getNinetyDaysAgoDate() {

    var today = new Date();
    var ninetyDaysAgo = new Date(today);
    ninetyDaysAgo.setDate(today.getDate() - 90);

    return ninetyDaysAgo.toISOString().split('T')[0];
}



// ==========================================
// KIRIM JAWABAN
// ==========================================

function kirim_jawaban(param) {

    $.LoadingOverlay('show');
    $('#modalQuestion').modal('show');

    var noKartu = $('#noKartu').val();
    var tgl_pelayanan = $('#tgl_sep').val();
    var jenis_faskes = $('#jenis_faskes').val();
    var ppkRujukan = $('#ppkRujukan').val();
    var tglLahir = $('#tglLahir').val();

    $.ajax({
        type: "GET",
        url: "/bpjs/kirimJawaban?noka=" + noKartu +
            "&tglPelayanan=" + tgl_pelayanan +
            "&jawaban=" + param +
            "&ppkRujukan=" + ppkRujukan +
            "&jenis_faskes=" + jenis_faskes +
            "&tglLahir=" + tglLahir,
        success: function(response) {

            var response = $.parseJSON(response);

            if (response.message == 'True') {

                swal({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "Jawaban Anda benar silahkan melanjutkan pembuatan sep",
                });

                $.LoadingOverlay('hide');

            } else if (response.message == 'false') {

                $.LoadingOverlay('hide');

                swal({
                    icon: 'error',
                    title: 'Gagal',
                    text: "Jawaban Anda Salah",
                });
            }
        }
    });
}



// ==========================================
// CARI SURKON
// ==========================================

function cari_surkon(nomor) {

    $.ajax({
        type: "GET",
        url: "/bpjs/rencanaCariNomorSurat?kode=" + nomor,
        success: function(response) {

            var response = $.parseJSON(response);

            if (response.code == '200') {

                var data = response.respon;
                var diagnosa = data.sep.diagnosa;
                var kode_dx = diagnosa.split(" - ");

                $('#diagAwal').attr('readonly', false);
                $('#diagAwal').val(kode_dx[0]);
            }
        }
    });
}



// ==========================================
// INIT DPJP DROPDOWN
// ==========================================

function init_dpjp(jnsLayanan, tglLayanan, poli_bpjs) {

    var dropdown = $('#dpjpLayan');

    dropdown.empty();
    dropdown.append('<option selected="true" value="">Pilih DPJP</option>');
    dropdown.prop('selectedIndex', 0);

    $.ajax({
        url: "/bpjs/getDpjp?jenisPelayanan=" +
            jnsLayanan +
            "&tglPelayanan=" + tglLayanan +
            "&kodeSpesialis=" + poli_bpjs,
        type: 'get',
        dataType: 'json',
        success: function(rspns) {

            var rspn = rspns.respon;

            $.each(rspn.list, function(key, item) {
                dropdown.append(
                    $('<option></option>')
                        .attr('value', item.kode)
                        .text(item.nama)
                );
            });
        }
    });
}



// ==========================================
// GET PROPINSI
// ==========================================

function getPropinsi() {

    $.ajax({
        type: "GET",
        url: "/bpjs/referensi-propinsi",
        dataType: "json",
        success: function(response) {

            console.log(response);

            // Support format CI lama
            if (response.code == '200') {

                var data = response.respon;

            }
            // Support format VClaim asli
            else if (response.metaData && response.metaData.code == 200) {

                var data = response.response;

            }
            else {
                console.log("Format response tidak dikenali");
                return;
            }

            var html = '<option value="">Choose</option>';

            for (var i = 0; i < data.list.length; i++) {

                html += '<option value="' +
                    data.list[i].kode + '">' +
                    data.list[i].nama +
                    '</option>';
            }

            $('#kdPropinsi').html(html);
        }
    });
}


// ==========================================
// DOCUMENT READY INIT
// ==========================================
// ==========================================
// CEK NOMOR PESERTA
// ==========================================

$(document).on('click', '#cekNoPeserta', function() {

    let noPeserta = $("#nomor_peserta").val();
    let tgl_sep = $("#tgl_sep").val();

    $.ajax({
        type: "GET",
        url: "/bpjs/cekBpjsNomorPeserta?no_peserta=" +
            noPeserta + '&tgl_sep=' + tgl_sep,
        success: function(response) {

            var obj = $.parseJSON(response);

            if (obj.code != '200') {

                swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: obj.message,
                });

            } else {

                let data = obj.respon.peserta;

                if (data.statusPeserta.keterangan == 'AKTIF') {
                    $('.alert_status').addClass('alert-success')
                        .removeClass('alert-warning');
                } else {
                    $('.alert_status').addClass('alert-warning')
                        .removeClass('alert-success');
                }

                $('#klsRawatHak').val(data.hakKelas.kode);
                $('#noKartu').val(data.noKartu);
                $('#nama_peserta').val(data.nama);
                $('#nik').val(data.nik);
                $('#noMr').val(data.mr.noMR);
                $('#no_hp').val(data.mr.noTelepon);
                $('#kelas').val(data.hakKelas.keterangan);
                $('#kd_faskes').val(data.provUmum.kdProvider);
                $('#nm_faskes').val(data.provUmum.nmProvider);
                $('#dinsos').val(data.informasi.dinsos);
                $('#noSKTM').val(data.informasi.noSKTM);
                $('#prolanisPRB').val(data.informasi.prolanisPRB);
                $('#statusPeserta').text('Status Peserta : ' + data.statusPeserta.keterangan);
                $('#tglLahir').val(data.tglLahir);
                $('#noTelp').val(data.mr.noTelepon);
                $('#sex').val(data.sex);
                $('#umur').val(data.umur.umurSekarang);
                $('#jenisPeserta').val(data.jenisPeserta.keterangan);
                $('#asalRujukan').val("2");
                $('#ppkRujukan').val("0440R001");
                $('#ppkRujukan_txt').val("RSUD Lebong");

                // AUTO LOAD HISTORY 90 HARI
                var no_kartu_txt = $('#noKartu').val();
                var search_tanggal_awal = getNinetyDaysAgoDate();
                var search_tanggal_akhir = new Date().toISOString().split('T')[0];

                $.ajax({
                    type: "GET",
                    url: "/Bpjs/carihistory?no_kartu_txt=" +
                        no_kartu_txt +
                        '&search_tanggal_awal=' + search_tanggal_awal +
                        '&search_tanggal_akhir=' + search_tanggal_akhir
                });
            }
        }
    });
});



// ==========================================
// SEARCH SKDP
// ==========================================

$(document).on('click', '#search_skdp', function() {

    if (validate('validate_skdp')) {

        var bulantahun = $('#skdp_bulantahun').val();
        var nokartu = $('#skdp_nokartu').val();
        var formatfilter = $('#skdp_formatfilter').val();
        var tahun = bulantahun.slice(0, 4);
        var bulan = bulantahun.slice(5);

        $.ajax({
            type: "GET",
            url: "/bpjs/cariSpriNoka?bulan=" +
                bulan + '&tahun=' + tahun +
                '&noKartu=' + nokartu +
                '&filter=' + formatfilter,
            success: function(response) {

                var response = $.parseJSON(response);

                var html = '';

                if (response.code != '200') {

                    html =
                        '<tr><td style="text-align: center;" class="p-2" colspan="4">' +
                        response.message + '</td></tr>';

                } else {

                    var data = response.respon;

                    for (var i = 0; i < data.list.length; i++) {

                        html += '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + data.list[i].noSuratKontrol + '</td>' +
                            '<td>' + data.list[i].kodeDokter + '</td>' +
                            '<td>' + data.list[i].namaDokter + '</td>' +
                            '<td>' + data.list[i].namaJnsKontrol + '</td>' +
                            '<td>' + data.list[i].tglRencanaKontrol + '</td>' +
                            '<td>' + data.list[i].noSepAsalKontrol + '</td>' +
                            '<td><button class="btn btn-sm btn-info pilih_skdp text-center" data-noSuratKontrol="' +
                            data.list[i].noSuratKontrol +
                            '" data-kodeDokter="' +
                            data.list[i].kodeDokter +
                            '" data-namaDokter="' +
                            data.list[i].namaDokter +
                            '" data-poliTujuan="' +
                            data.list[i].poliTujuan +
                            '" data-sepasal="' +
                            data.list[i].noSepAsalKontrol +
                            '">Pilih</button></td>' +
                            '</td>';
                    }
                }

                $('#show_data_skdp').html(html);
            }
        });
    }
});



// ==========================================
// PILIH SKDP
// ==========================================

$(document).on('click', '.pilih_skdp', function() {

    $('#noSurat').val($(this).attr('data-noSuratKontrol'));
    $('#kodeDPJP').val($(this).attr('data-kodeDokter'));
    $('#kodeDPJP_txt').val($(this).attr('data-namaDokter'));
    $('#tujuan').val($(this).attr('data-poliTujuan'));

    if ($('#noRujukan').val() == "") {
        $('#noRujukan').val($(this).attr('data-sepasal'));
    }

    cari_surkon($(this).attr('data-noSuratKontrol'));

    $('#modalSKDP').modal('hide');
});



// ==========================================
// SEARCH DIAGNOSA
// ==========================================

$(document).on('click', '#search_diagnosa', function() {

    var kode_diagnosa = $('#kode_diagnosa').val();

    $.ajax({
        type: "GET",
        url: "/bpjs/getReferensiDiagnosa/" + kode_diagnosa,
        success: function(response) {

            var response = $.parseJSON(response);
            var html = '';

            if (response.code != '200') {

                html =
                    '<tr><td style="text-align: center;" class="p-2" colspan="4">' +
                    response.message + '</td></tr>';

            } else {

                var data = response.respon;

                for (var i = 0; i < data.diagnosa.length; i++) {

                    html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + data.diagnosa[i].kode + '</td>' +
                        '<td>' + data.diagnosa[i].nama + '</td>' +
                        '<td><button class="btn btn-sm btn-info pilih_diagnosa text-center" data-kode="' +
                        data.diagnosa[i].kode +
                        '">Pilih</button></td>' +
                        '</td>';
                }
            }

            $('#show_data_diagnosa').html(html);
        }
    });
});



// ==========================================
// PILIH DIAGNOSA
// ==========================================

$(document).on('click', '.pilih_diagnosa', function() {

    $('#diagAwal').val($(this).attr('data-kode'));
    $('#modalDiagnosa').modal('hide');
});



// ==========================================
// SEARCH POLI
// ==========================================

$(document).on('click', '#search_poli', function() {

    var kode_poli = $('#kode_poli').val();

    $.ajax({
        type: "GET",
        url: "/bpjs/getReferensiPoli/" + kode_poli,
        success: function(response) {

            var response = $.parseJSON(response);
            var html = '';

            if (response.code != '200') {

                html =
                    '<tr><td style="text-align: center;" class="p-2" colspan="4">' +
                    response.message + '</td></tr>';

            } else {

                var data = response.respon;

                for (var i = 0; i < data.poli.length; i++) {

                    html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + data.poli[i].kode + '</td>' +
                        '<td>' + data.poli[i].nama + '</td>' +
                        '<td><button class="btn btn-sm btn-info pilih_poli text-center" data-kode="' +
                        data.poli[i].kode +
                        '">Pilih</button></td>' +
                        '</td>';
                }
            }

            $('#show_data_poli').html(html);
        }
    });
});



// ==========================================
// PILIH POLI
// ==========================================

$(document).on('click', '.pilih_poli', function() {

    $('#tujuan').val($(this).attr('data-kode'));
    $('#modalPoli').modal('hide');
});
// ==========================================
// SEARCH DPJP
// ==========================================

$(document).on('click', '#search_dpjp', function() {

    var jenis_pelayanan = $('#jenis_pelayanan').val();
    var tgl_pelayanan = $('#tgl_pelayanan').val();
    var kode_spesialis = $('#kode_spesialis').val();

    $.ajax({
        type: "GET",
        url: "/bpjs/getDpjp?jenisPelayanan=" +
            jenis_pelayanan +
            "&tglPelayanan=" + tgl_pelayanan +
            "&kodeSpesialis=" + kode_spesialis,
        success: function(response) {

            var response = $.parseJSON(response);
            var html = '';

            if (response.code != '200') {

                html =
                    '<tr><td style="text-align: center;" class="p-2" colspan="4">' +
                    response.message + '</td></tr>';

            } else {

                var data = response.respon;

                for (var i = 0; i < data.list.length; i++) {

                    html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + data.list[i].kode + '</td>' +
                        '<td>' + data.list[i].nama + '</td>' +
                        '<td><button class="btn btn-sm btn-info pilih_dpjp text-center" data-kode="' +
                        data.list[i].kode +
                        '" data-kodePoli="' +
                        kode_spesialis +
                        '">Pilih</button></td>' +
                        '</td>';
                }
            }

            $('#show_data_dpjp').html(html);
        }
    });
});


// ==========================================
// PILIH DPJP
// ==========================================

$(document).on('click', '.pilih_dpjp', function() {

    $('#dpjpLayan').val($(this).attr('data-kode'));
    $('#tujuan').val($(this).attr('data-kodePoli'));
    $('#modalDPJP').modal('hide');
});


// ==========================================
// SEARCH RUJUKAN
// ==========================================

$(document).on('click', '#search_rujukan', function() {

    $.LoadingOverlay('show');

    var no_kartu = $('#no_kartu_txt').val();
    var jenis_pelayanan = $('#jenis_faskes').val();

    $.ajax({
        type: "GET",
        url: "/bpjs/getListRujukan?no_kartu=" +
            no_kartu +
            "&jenis_pelayanan=" + jenis_pelayanan,
        success: function(response) {

            $.LoadingOverlay('hide');

            var response = $.parseJSON(response);
            var html = '';

            if (response.code != '200') {

                html =
                    '<tr><td style="text-align: center;" class="p-2" colspan="4">' +
                    response.message + '</td></tr>';

            } else {

                var data = response.respon;

                for (var i = 0; i < data.rujukan.length; i++) {

                    html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + data.rujukan[i].noKunjungan + '</td>' +
                        '<td>' + data.rujukan[i].peserta.nama + '</td>' +
                        '<td>' + data.rujukan[i].tglKunjungan + '</td>' +
                        '<td>' + data.rujukan[i].provPerujuk.nama + '</td>' +
                        '<td>' + data.rujukan[i].poliRujukan.nama + '</td>' +
                        '<td><button class="btn btn-sm btn-info pilih_rujukan text-center" data-rujukan="' +
                        data.rujukan[i].noKunjungan +
                        '" data-faskes="' +
                        jenis_pelayanan +
                        '">Pilih</button></td>' +
                        '</td>';
                }
            }

            $('#show_data_rujukan').html(html);
        }
    });
});


// ==========================================
// PILIH RUJUKAN
// ==========================================

$(document).on('click', '.pilih_rujukan', function() {

    $('#no_rujukan').val($(this).attr('data-rujukan'));
    $('#faskes_perujuk').val($(this).attr('data-faskes'));

    cariNoRujukan(
        $(this).attr('data-rujukan'),
        $(this).attr('data-faskes')
    );

    $('#modalRujukan').modal('hide');
});


// ==========================================
// GET RANDOM QUESTION
// ==========================================

$(document).on('click', '#getQuestion', function() {

    $.LoadingOverlay('show');
    $('#modalQuestion').modal('show');

    var noKartu = $('#noKartu').val();
    var tgl_pelayanan = $('#tgl_sep').val();

    $.ajax({
        type: "GET",
        url: "/bpjs/getRandomQuestion?noka=" +
            noKartu +
            "&tglPelayanan=" + tgl_pelayanan,
        success: function(response) {

            $.LoadingOverlay('hide');

            var response = $.parseJSON(response);
            var html = '';

            if (response.code != '200') {

                swal({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message,
                });

            } else {

                var data = response.respon;

                html += 'di mana faskes tingkat pertama anda ?';

                for (var i = 0; i < data.faskes.length; i++) {

                    html += '<button class="btn btn-sm btn-info pilih_jawaban text-center" style="margin:5px" data-kode="' +
                        data.faskes[i].kode + '">' +
                        data.faskes[i].nama +
                        '</button>';
                }
            }

            $('#show_data_question').html(html);
        }
    });
});


// ==========================================
// PILIH JAWABAN
// ==========================================

$(document).on('click', '.pilih_jawaban', function() {

    kirim_jawaban($(this).attr('data-kode'));
});


// ==========================================
// SAVE SEP
// ==========================================

$(document).on('click', '#save_sep', function() {

    $.LoadingOverlay('show');
    $('#save_sep').attr('disabled', true);

    var data = $('#form-create-sep').serialize();

    if (validate('validate')) {

        $.ajax({
            type: 'post',
            url: '/Bpjs/sepInsertDua',
            data: data,
            success: function(response) {

                $.LoadingOverlay('hide');

                var response = $.parseJSON(response);

                if (response.code != '200') {

                    swal({
                        icon: 'error',
                        title: 'Gagal',
                        text: response.message,
                    });

                } else {

                    var data = response.respon.sep;

                    var dpjp = $('#kodeDPJP').val() != ""
                        ? $('#kodeDPJP').val()
                        : $('#dpjpLayan').val();

                    $('#res-noSep').val(data.noSep);

                    swal({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Berhasil didaftarkan dengan No. Sep : ' + data.noSep,
                    });

                    window.open("/bpjs/printSep/" + data.noSep);
                }

                $('#save_sep').removeAttr('disabled');
            }
        });

    } else {

        $.LoadingOverlay('hide');
        $('#save_sep').removeAttr('disabled');

        swal({
            icon: 'error',
            title: 'Gagal',
            text: 'Mohon isi data yang diperlukan!',
        });
    }
});


// ==========================================
// SEARCH HISTORY
// ==========================================

$(document).on('click', '#search_history', function() {

    var no_kartu_txt = $('#noKartu').val();
    var search_tanggal_awal = $('#search_tanggal_awal').val();
    var search_tanggal_akhir = $('#search_tanggal_akhir').val();

    $.ajax({
        type: "GET",
        url: "/Bpjs/carihistory?no_kartu_txt=" +
            no_kartu_txt +
            '&search_tanggal_awal=' + search_tanggal_awal +
            '&search_tanggal_akhir=' + search_tanggal_akhir,
        success: function(response) {

            var response = $.parseJSON(response);
            var html = '';

            if (response.code != '200') {

                html =
                    '<tr><td style="text-align: center;" class="p-2" colspan="4">' +
                    response.message + '</td></tr>';

            } else {

                var data = response.respon;

                for (var i = 0; i < data.histori.length; i++) {

                    var jnsPelayananBpjs =
                        data.histori[i].jnsPelayanan == "1"
                            ? "Rawat Inap"
                            : "Rawat Jalan";

                    var tglPulang =
                        data.histori[i].jnsPelayanan == "1"
                            ? '<button class="btn btn-sm btn-info" id="update_tgl_pulang" data-tgl="' +
                              data.histori[i].tglSep +
                              '" data-sep="' +
                              data.histori[i].noSep +
                              '">Update Tanggal Pulang</button>'
                            : '';

                    html += '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + data.histori[i].noSep + '</td>' +
                        '<td>' + data.histori[i].noRujukan + '</td>' +
                        '<td>' + data.histori[i].namaPeserta + '</td>' +
                        '<td>' + data.histori[i].tglSep + '</td>' +
                        '<td>' + data.histori[i].ppkPelayanan + '</td>' +
                        '<td>' + jnsPelayananBpjs + '</td>' +
                        '<td>' + data.histori[i].diagnosa + '</td>' +
                        '<td>' + data.histori[i].poli + '</td>' +
                        '<td>' + tglPulang + '</td>';
                }
            }

            $('#show_data_history').html(html);
        }
    });
});


// ==========================================
// UPDATE TGL PULANG
// ==========================================

$(document).on('click', '#update_tgl_pulang', function() {

    $('#noSep').val($(this).data('sep'));
    $('#tglPulang').val($(this).data('tgl'));
    $('#modalTglPulang').modal('show');
});

$(document).on('click', '#update_tgl_pulang_submit', function() {

    var data = $('#formUpdateTglPulang').serialize();

    $.post('/bpjs/sepUpdateTglPulangDua', data, function(response) {

        var response = $.parseJSON(response);

        if (response.code != '200') {

            swal({
                icon: 'error',
                title: 'Gagal',
                text: response.message,
            });

        } else {

            swal({
                icon: 'success',
                title: 'Berhasil',
                text: 'Update Tanggal Pulang!',
            });

            $('.modal').modal('hide');
        }
    });
});


// ==========================================
// STATUS PULANG
// ==========================================

$(document).on('change', '#statusPulang', function() {

    if (this.value == '4') {
        $('.status4').show();
    } else {
        $('.status4').hide();
    }
});
$(document).ready(function() {
   // DataTable
    $('#tableHistory').DataTable();
 

    

    // Load Propinsi
    getPropinsi();

});
document.querySelectorAll('.bpjs-tab').forEach(button => {
    button.addEventListener('click', function () {

        // remove active button
        document.querySelectorAll('.bpjs-tab')
            .forEach(btn => btn.classList.remove('active'));

        // hide all panes
        document.querySelectorAll('.bpjs-tab-pane')
            .forEach(pane => pane.classList.remove('active'));

        // activate clicked tab
        this.classList.add('active');

        let tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');

    });
});
document.getElementById('lakaLantas')
    .addEventListener('change', function () {

    const fields = document.querySelectorAll('.bpjs-kll-field');

    if (this.value === "0" || this.value === "") {
        fields.forEach(f => f.classList.add('d-none'));
    } else {
        fields.forEach(f => f.classList.remove('d-none'));
    }

});
document.getElementById('suplesi')
    .addEventListener('change', function () {

    const field = document.getElementById('suplesi_field');

    if (this.value === "1") {
        field.classList.remove('d-none');
    } else {
        field.classList.add('d-none');
    }

});
document.getElementById('isSKDP')
    .addEventListener('change', function () {

    const fields = document.querySelectorAll('.bpjs-skdp-field');

    if (this.value === "1") {
        fields.forEach(f => f.classList.remove('d-none'));
    } else {
        fields.forEach(f => f.classList.add('d-none'));
    }

});
$(document).on('click', '#getQuestion', function() {
    // logic buka pertanyaan
});

