<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MasterPasienController extends Controller
{
public function index(Request $request)
{
    $search = $request->search;
    $page   = $request->page ?? 1;

    $cacheKey = 'pasien_'
        . md5($search)
        . '_page_' . $page;

    $pasien = Cache::remember($cacheKey, 60, function () use ($request) {

        $query = Pasien::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('no_rm', 'like', "%{$request->search}%")
                  ->orWhere('nama', 'like', "%{$request->search}%")
                  ->orWhere('no_identitas', 'like', "%{$request->search}%");
            });
        }
if ($request->ajax()) {
    return view('master_pasien.partials.table', compact('pasien'))->render();
}
        return $query
            ->orderBy('no_rm', 'desc')
            ->paginate(10)
            ->withQueryString();
    });

    if ($request->ajax()) {
        return view('master_pasien.partials.table', compact('pasien'))->render();
    }

    return view('master_pasien.index', compact('pasien'));
}

    public function datatable(Request $request)
    {
        $query = Pasien::query();

        // Filter
        if ($request->no_rm) {
            $query->where('no_rm', 'like', "%{$request->no_rm}%");
        }

        if ($request->nama) {
            $query->where('nama', 'like', "%{$request->nama}%");
        }

        if ($request->no_identitas) {
            $query->where('no_identitas', 'like', "%{$request->no_identitas}%");
        }

        if ($request->no_kartu) {
            $query->where('no_kartu', 'like', "%{$request->no_kartu}%");
        }
if ($request->search_global) {
    $query->where(function ($q) use ($request) {
        $q->where('no_rm', 'like', "%{$request->search_global}%")
          ->orWhere('nama', 'like', "%{$request->search_global}%")
          ->orWhere('no_identitas', 'like', "%{$request->search_global}%");
    });
}

        $total = $query->count();

        $data = $query
            ->orderBy('no_rm', 'desc')
            ->offset($request->start)
            ->limit($request->length)
            ->get();

        $result = [];

        foreach ($data as $row) {

            $result[] = [
                'id' => $row->id,
                'no_rm' => $row->no_rm,
                'nama' => $row->nama,
                'tgl_lahir' => $row->tgl_lahir?->format('d-m-Y'),
                'jenis_kelamin' => $row->jenis_kelamin,
                'no_identitas' => $row->no_identitas,
                'no_kartu' => $row->no_kartu,
                'alamat_lengkap' => $row->alamat_lengkap,
                'no_hp' => $row->no_hp,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => Pasien::count(),
            'recordsFiltered' => $total,
            'data' => $result
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_rm' => 'required',
            'nama' => 'required|min:3'
        ]);

        $pasien = Pasien::create($request->all());
Cache::tags(['pasien'])->flush();

        return response()->json([
            'status' => 'success',
            'id' => $pasien->id
        ]);
    }

    public function show($id)
    {
        return Pasien::findOrFail($id);
    }

    public function destroy($id)
    {
        Pasien::where('id', $id)->update(['status_aktif' => 0]);
Cache::tags(['pasien'])->flush();

        return response()->json([
            'status' => 'success',
            'message' => 'Pasien berhasil dinonaktifkan'
        ]);
    }
}
