<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\UserGroup;
use Illuminate\Http\Request;

class UserGroupsController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'result' => UserGroup::with('user', 'group')->get()
        ], 200);
    }

    public function getListPending($group_id)
    {
        if ($group_id === "all") {
            $data = UserGroup::with('group', 'user')->where('status', 'pending')->get();
        } else {
            $data = UserGroup::with('group', 'user')->where('status', 'pending')
                ->where('group_id', $group_id)
                ->get();
        }

        return response()->json([
            'success' => true,
            'result' => [
                'data' => $data,
                'total' => $data->count()
            ]
        ], 200);
    }

    public function getListUser($group_id)
    {
        $list = UserGroup::with('user')
            ->where('group_id', $group_id)
            ->where('status', 'terdaftar')
            ->get();

        return response()->json([
            'success' => true,
            'result' => $list
        ], 200);
    }

    public function create(Request $request)
    {
        date_default_timezone_set('Asia/Makassar');

        UserGroup::create([
            'group_id' => $request->group_id,
            'user_id' => $request->user_id,
            'hak_akses' => $request->hak_akses,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    public function update(Request $request, $group_id, $user_id)
    {
        $userGroup = UserGroup::where('group_id', $group_id)
            ->where('user_id', $user_id)
            ->first();
        $userGroup->update($request->all());

        if ($request->status === "terdaftar") {
            $group = Group::where('id', $group_id)->first();
            $group->update([
                'jumlah_anggota' => (int) $group->jumlah_anggota + 1
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diubah'
        ], 200);
    }

    public function delete($group_id, $user_id)
    {
        $userGroup = UserGroup::where('group_id', $group_id)
            ->where('user_id', $user_id)
            ->first();
        $userGroup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
