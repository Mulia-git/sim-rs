@extends('layouts.app')

@section('content')
@vite(['resources/js/master_pasien.js'])

<div class="container-fluid">

<div class="card shadow-sm">
<div class="card-body">

<div class="d-flex justify-content-between mb-3">

    <div class="d-flex gap-2 w-50">
        <input type="text"
               id="searchGlobal"
               class="form-control"
               placeholder="Cari No RM / NIK / Nama">

        <button class="btn btn-success" id="btnSearch">
            <i class="fas fa-search"></i>
        </button>

        <div id="searchLoading" class="d-none ms-2">
            <div class="spinner-border spinner-border-sm text-success"></div>
        </div>
    </div>

    <a href="/master-pasien/create" class="btn btn-success">
        <i class="fas fa-plus"></i> Tambah Pasien
    </a>

</div>

<div id="tableContainer">
    @include('master_pasien.partials.table')
</div>

</div>
</div>
</div>

@endsection
