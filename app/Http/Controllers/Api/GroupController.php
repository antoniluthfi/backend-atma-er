<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\UserGroup;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'result' => Group::all()
        ], 200);
    }

    public function getDataByUserId($user_id)
    {
        $group = Group::all();

        foreach ($group as $key => $val) {
            $terdaftar = UserGroup::select('status')
                ->where('group_id', $val['id'])
                ->where('user_id', $user_id)
                ->first();
            $admin_pembuat = UserGroup::with('user')
                ->where('group_id', $val['id'])
                ->where('hak_akses', 'admin pembuat')
                ->first();
            $group[$key]->nomorhp = $admin_pembuat->user->nomorhp;
            $group[$key]->status_daftar = $terdaftar ? $terdaftar->status : "tidak terdaftar";
        }

        return response()->json([
            'success' => true,
            'result' => $group,
        ], 200);
    }

    public function create(Request $request, $name, $description, $status, $user_id)
    {
        if ($request->file) {
            $request->file->move(public_path('groups'), $request->file->getClientOriginalName());
        }

        $group = Group::create([
            'nama' => $name,
            'deskripsi' => $description,
            'jumlah_anggota' => 1,
            'foto_profil' => $request->file ? "groups/" . $request->file->getClientOriginalName() : "",
            'status' => $status
        ]);

        if ($group) {
            UserGroup::create([
                'group_id' => $group->id,
                'user_id' => $user_id,
                'hak_akses' => 'admin pembuat',
                'status' => 'terdaftar'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    public function updateProfilePhoto(Request $request, $id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'result' => $request
        ], 200);
    }
}
