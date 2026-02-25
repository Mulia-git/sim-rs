<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiagnosaController extends Controller
{
    public function select2(Request $request)
    {
        $term = $request->term;

        $res = DB::table('ref_icd10')
            ->where('code','like',"%$term%")
            ->orWhere('display','like',"%$term%")
            ->limit(20)
            ->get();

        $results = [];
        foreach($res as $r){
            $results[] = [
                'id' => $r->code,
                'text' => $r->code.' - '.$r->display,
                'nmDiag' => $r->display
            ];
        }

        return response()->json(['results'=>$results]);
    }

    public function list($id)
    {
        return response()->json(
            DB::table('pcare_diagnosa')
                ->where('id_registrasi',$id)
                ->get()
        );
    }

    public function simpan(Request $request)
    {
        $data = [
            'id_registrasi' => $request->id_registrasi,
            'no_rawat' => $request->no_rawat,
            'kdDiag' => $request->kdDiag,
            'nmDiag' => $request->nmDiag,
            'jenis' => $request->jenis,
            'keterangan' => $request->keterangan,
            'created_at'=>now(),
            'updated_at'=>now()
        ];

        if($request->id){
            DB::table('pcare_diagnosa')
                ->where('id',$request->id)
                ->update($data);
        } else {
            DB::table('pcare_diagnosa')->insert($data);
        }

        return response()->json(['status'=>'success']);
    }

    public function hapus($id)
    {
        DB::table('pcare_diagnosa')->where('id',$id)->delete();
        return response()->json(['status'=>'success']);
    }
}