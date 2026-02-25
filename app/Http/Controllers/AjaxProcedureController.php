<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class AjaxProcedureController extends Controller
{
    public function icd9Select2(Request $request)
{
    $term = $request->term;

    $data = DB::table('ref_icd9cm')
        ->where('code','like',"%$term%")
        ->orWhere('display','like',"%$term%")
        ->limit(20)
        ->get();

    $results = [];

    foreach ($data as $r) {
        $results[] = [
            'id' => $r->code,
            'text' => $r->code.' - '.$r->display,
            'name' => $r->display
        ];
    }

    return response()->json([
        'results' => $results,
        'pagination' => ['more' => false]
    ]);
}

}
