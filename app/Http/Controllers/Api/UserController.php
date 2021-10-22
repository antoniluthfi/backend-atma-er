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
            'result' => User::all()
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
        return response()->json([
            'success' => true,
            'result' => Auth::user()
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
        
        $user = User::findOrFail($id);
        $user->fill($input)->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diubah',
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
