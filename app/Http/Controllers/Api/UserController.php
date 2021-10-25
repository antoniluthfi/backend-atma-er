<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'result' => User::with('userGroup')->get()
        ], 200);
    }

    public function getDataById($id)
    {
        return response()->json([
            'success' => true,
            'result' => User::findOrFail($id)
        ], 200);
    }

    public function getCurrentUser()
    {
        $user = User::with('userGroup')->findOrFail(Auth::user()->id);

        return response()->json([
            'success' => true,
            'result' => $user
        ], 200);
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $input['password'] = bcrypt(strtolower($input['name']));
        User::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $input['nomorhp'] = '62' . $request->nomorhp;

        $user = User::with('userGroup')->findOrFail($id);
        $user->fill($input)->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diubah',
            'result' => $user
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $user = User::where('id', $request->key)->first();
        $password = bcrypt($request->password);
        $user->update([
            'password' => $password
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah'
        ], 200);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
