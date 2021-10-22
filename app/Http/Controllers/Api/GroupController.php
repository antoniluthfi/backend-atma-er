<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'result' => Group::with('userGroups')->get()
        ], 200);
    }

    public function create(Request $request)
    {
        Group::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'jumlah_anggota' => $request->jumlah_anggota,
            'foto_profil' => $request->foto_profil
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        
    }
}
