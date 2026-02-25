<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class ProcedureController extends Controller
{
    public function icd9Select2(Request $request)
    {
        $term = $request->term;

        $data = DB::table('ref_icd9cm')
            ->where('code','like',"%$term%")
            ->orWhere('display','like',"%$term%")
            ->limit(20)->get();

        $results=[];
        foreach($data as $r){
            $results[]=[
                'id'=>$r->code,
                'text'=>$r->code.' - '.$r->display
            ];
        }

        return response()->json(['results'=>$results]);
    }

    public function simpan(Request $request)
    {
        $data = [
            'id_registrasi'=>$request->id_registrasi,
            'no_rawat'=>$request->no_rawat,
            'icd9_code'=>$request->icd9_code,
            'icd9_display'=>$request->icd9_display,
            'performed_start'=>$request->performed_start,
            'performed_end'=>$request->performed_end,
            'note'=>$request->note,
            'created_at'=>now(),
            'updated_at'=>now()
        ];

        DB::table('pcare_procedure')->insert($data);

        return response()->json(['status'=>'success']);
    }
}