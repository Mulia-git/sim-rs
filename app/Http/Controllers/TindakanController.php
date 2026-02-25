<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class TindakanController extends Controller
{
    public function simpan(Request $request)
    {
        $data = [
            'id_registrasi'=>$request->id_registrasi,
            'no_rawat'=>$request->no_rawat,
            'kdTindakan'=>$request->tindakan_id,
            'jumlah'=>$request->jumlah,
            'tarif'=>$request->tarif,
            'total'=>$request->total,
            'created_at'=>now(),
            'updated_at'=>now()
        ];

        DB::table('pcare_tindakan')->insert($data);

        return response()->json(['status'=>'success']);
    }

    public function list($id)
    {
        return response()->json(
            DB::table('pcare_tindakan')
                ->where('id_registrasi',$id)
                ->get()
        );
    }
}