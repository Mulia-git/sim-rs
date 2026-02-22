// public/js/bpjs/sep.js

const SEPModule = (() => {

    function cekPeserta() {
        $("#cekNoPeserta").on("click", function () {

            let noPeserta = $("#nomor_peserta").val();
            let tglSep = $("#tgl_sep").val();

            BPJSUtils.loading(true);

            BPJSApi.get("/administrator/pendaftaran/cekBpjsNomorPeserta", {
                no_peserta: noPeserta,
                tgl_sep: tglSep
            })
            .done(res => {

                if (res.code != 200) {
                    BPJSUtils.showError(res.message);
                    return;
                }

                let data = res.respon.peserta;

                $("#noKartu").val(data.noKartu);
                $("#nama_peserta").val(data.nama);
                $("#nik").val(data.nik);
                $("#noMr").val(data.mr.noMR);
                $("#noTelp").val(data.mr.noTelepon);
                $("#tglLahir").val(data.tglLahir);

                BPJSUtils.showSuccess("Peserta ditemukan");
            })
            .always(() => BPJSUtils.loading(false));
        });
    }


    function saveSEP() {
        $("#save_sep").on("click", function () {

            let data = BPJSUtils.serialize("#form-create-sep");

            BPJSUtils.loading(true);

            BPJSApi.post("/bpjs/sepInsertDua", data)
                .done(res => {

                    if (res.code != 200) {
                        BPJSUtils.showError(res.message);
                        return;
                    }

                    let sep = res.respon.sep;

                    $("#res-noSep").val(sep.noSep);
                    $("#modalResponse").modal({
                        backdrop: "static",
                        keyboard: false
                    });

                    BPJSUtils.showSuccess("SEP berhasil dibuat");
                })
                .always(() => BPJSUtils.loading(false));
        });
    }

    function init() {
        cekPeserta();
        saveSEP();
    }

    return { init };

})();