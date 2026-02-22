// public/js/bpjs/referensi.js

const ReferensiModule = (() => {

    function searchDiagnosa() {

        $("#search_diagnosa").on("click", function () {

            let kode = $("#kode_diagnosa").val();

            BPJSApi.get("/administrator/pendaftaran/getReferensiDiagnosa/" + kode)
                .done(res => {

                    let html = "";

                    if (res.code != 200) {
                        html = `<tr>
                            <td colspan="4" class="text-center">
                                Data tidak ditemukan
                            </td>
                        </tr>`;
                    } else {
                        res.respon.diagnosa.forEach((item, i) => {
                            html += `
                                <tr>
                                    <td>${i + 1}</td>
                                    <td>${item.kode}</td>
                                    <td>${item.nama}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info pilih_diagnosa"
                                            data-kode="${item.kode}">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>`;
                        });
                    }

                    BPJSUtils.setHTML("#show_data_diagnosa", html);
                });
        });
    }

    function pilihDiagnosa() {
        $(document).on("click", ".pilih_diagnosa", function () {
            $("#diagAwal").val($(this).data("kode"));
            $("#modalDiagnosa").modal("hide");
        });
    }

    function init() {
        searchDiagnosa();
        pilihDiagnosa();
    }

    return { init };

})();