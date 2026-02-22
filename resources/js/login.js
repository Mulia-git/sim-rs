import Swal from 'sweetalert2';
import $ from 'jquery';

$(document).ready(function () {

    $('#form-login').on('submit', function (e) {
        e.preventDefault();

        let username = $('#username').val();
        let password = $('#password').val();

        if (!username || !password) {
            Swal.fire('Oops...', 'Username dan Password wajib diisi!', 'warning');
            return;
        }

        Swal.fire({
            title: 'Sedang login...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: '/login',
            method: 'POST',
            data: {
                username,
                password,
                _token: $('input[name="_token"]').val()
            },
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.redirect;
                    });
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            },
            error: function () {
                Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
            }
        });
    });

});
