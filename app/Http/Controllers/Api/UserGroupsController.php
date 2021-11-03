<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
