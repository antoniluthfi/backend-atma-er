<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pengajar;
use Illuminate\Http\Request;

class PengajarController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'result' => Pengajar::all()
        ], 200);
    }

    public function getDataById($id)
    {
        return response()->json([
            'success' => true,
            'result' => Pengajar::with('eventPengajian')->where('id', $id)->first()
        ], 200);
    }

    public function create(Request $request)
    {
        $input = $request->all();
        Pengajar::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $pengajar = Pengajar::findOrFail($id);
        $input = $request->all();
        $pengajar->fill($input)->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diubah'
        ], 200);
    }

    public function delete($id)
    {
        $pengajar = Pengajar::findOrFail($id);
        $pengajar->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
