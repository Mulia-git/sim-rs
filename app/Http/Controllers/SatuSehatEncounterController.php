<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SatusehatEncounterController extends Controller
{
    public function start(Request $request)
    {
        $idReg = $request->id_registrasi;

        $enc = DB::table('encounter')
            ->where('id_registrasi',$idReg)
            ->first();

        if(!$enc){
            return response()->json([
                'status'=>'error',
                'message'=>'Encounter tidak ditemukan'
            ]);
        }

        DB::table('encounter')
            ->where('id',$enc->id)
            ->update([
                'status'=>'in-progress',
                'in_progress_at'=>now(),
                'updated_at'=>now()
            ]);

        return response()->json([
            'status'=>'success',
            'message'=>'Encounter diubah ke in-progress'
        ]);
    }
}