/**
 * =========================================
 * MODULE KEUANGAN - AKUN REKENING
 * =========================================
 */

const KEUANGAN_AKUN_REKENING_APP = {
    baseUrl: "/keuangan/akun-rekening",

    init() {
        this.loadDataRekening();

        this.registerEvents();
    },

    registerEvents() {
        $(document).on("click", "#btnTambahRekeningKeu", function () {
            $("#modalFormRekeningKeu").modal("show");
        });

        $(document).on("click", "#btnSimpanRekeningKeu", () => {
            this.simpanRekening();
        });
    },

    loadDataRekening() {
        $.ajax({
            url: this.baseUrl + "/data",

            type: "GET",

            dataType: "json",

            success: (res) => {
                let htmlRekeningTable = "";

                res.forEach(function (row) {
                    htmlRekeningTable += `
                        <tr>
                            <td>${row.kode}</td>
                            <td>${row.nama_rekening}</td>
                            <td>${row.tipe}</td>
                            <td>${KEUANGAN_AKUN_REKENING_APP.formatRupiah(row.saldo_awal)}</td>
                            <td>${KEUANGAN_AKUN_REKENING_APP.formatRupiah(row.balance)}</td>
                        </tr>
                    `;
                });

                $("#tblAkunRekeningKeu tbody").html(htmlRekeningTable);
            },
        });
    },

    simpanRekening() {
        let formDataRekening = $("#formRekeningKeu").serialize();

        $.ajax({
            url: this.baseUrl + "/store",

            method: "POST",

            data: formDataRekening,

            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },

            success: () => {
                $("#modalFormRekeningKeu").modal("hide");

                $("#formRekeningKeu")[0].reset();

                this.loadDataRekening();
            },
        });
    },

    formatRupiah(angka) {
        return new Intl.NumberFormat("id-ID").format(angka);
    },
};

export default KEUANGAN_AKUN_REKENING_APP;
