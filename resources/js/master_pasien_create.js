import $ from 'jquery'
import Swal from 'sweetalert2'

window.$ = window.jQuery = $

function mp_hitungUmur(tanggal) {

    const parts = tanggal.split('-')
    if(parts.length !== 3) return ''

    const tgl = parseInt(parts[0])
    const bln = parseInt(parts[1]) - 1
    const thn = parseInt(parts[2])

    const lahir = new Date(thn, bln, tgl)
    const today = new Date()

    let tahun = today.getFullYear() - lahir.getFullYear()
    let bulan = today.getMonth() - lahir.getMonth()
    let hari = today.getDate() - lahir.getDate()

    if(hari < 0){
        bulan--
        hari += 30
    }

    if(bulan < 0){
        tahun--
        bulan += 12
    }

    return `${tahun}-${bulan}-${hari}`
}

$(document).ready(function(){

    $('#mp_tgl_lahir').on('change', function(){
        const umur = mp_hitungUmur($(this).val())
        $('#mp_umur').val(umur)
    })

    $('#mp_btn_save').on('click', function(){

        const formData = $('#mp-form-create').serialize()

        $.post('/master-pasien/store', formData, function(res){

            if(res.status === 'success'){

                Swal.fire({
                    title:'Berhasil',
                    text:'Lanjut ke registrasi?',
                    icon:'success',
                    showCancelButton:true
                }).then((result)=>{
                    if(result.isConfirmed){
                        window.location.href =
                        '/registrasi/create/' + res.id
                    }else{
                        window.location.href =
                        '/master-pasien'
                    }
                })

            }else{
                Swal.fire('Error',res.message,'error')
            }

        }).fail(function(err){
            Swal.fire('Error','Terjadi kesalahan','error')
        })
    })

})
