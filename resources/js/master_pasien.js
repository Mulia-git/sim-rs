import 'bootstrap/dist/css/bootstrap.min.css'
import '../css/master_pasien.css'

import $ from 'jquery'
import * as bootstrap from 'bootstrap'
import Swal from 'sweetalert2'

window.$ = window.jQuery = $
window.bootstrap = bootstrap

$(document).ready(function () {

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
