<div class="card">

    <div class="card-body">

        <div class="mb-4">

            <button class="btn btn-success tab-btn active">
                <i class="fa fa-wallet"></i> Akun Rekening
            </button>

            <button class="btn btn-light tab-btn">
                <i class="fa fa-calendar"></i> Rekening Tahun
            </button>

            <button class="btn btn-light tab-btn">
                <i class="fa fa-cog"></i> Pengaturan Rekening
            </button>

            <button class="btn btn-light tab-btn">
                <i class="fa fa-book"></i> Posting Jurnal
            </button>

        </div>

        <h5>Filter Pencarian</h5>

        <div class="row mb-3">

            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Kode">
            </div>

            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Nama Rekening">
            </div>

            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Tipe">
            </div>

            <div class="col-md-2">
                <button class="btn btn-success">
                    <i class="fa fa-search"></i>
                </button>
            </div>

        </div>


        <div class="d-flex justify-content-between mb-2">

            <h5>Daftar Rekening</h5>

            <button class="btn btn-success" id="btnTambah">

                <i class="fa fa-plus"></i> Form Rekening

            </button>

        </div>


        <table class="table table-bordered" id="table-rekening">

            <thead>

                <tr>
                    <th>Kode</th>
                    <th>Rekening</th>
                    <th>Tipe</th>
                    <th>Saldo Awal</th>
                    <th>Balance</th>
                </tr>

            </thead>

            <tbody></tbody>

        </table>

    </div>
</div>