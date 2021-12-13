<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
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
        $user = User::create($input);

        if ($user) {
            UserGroup::create([
                'group_id' => $request->group_id,
                'user_id' => $request->user_id,
                'hak_akses' => 'anggota',
                'status' => 'terdaftar'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        unset($input['group_id']);
        unset($input['hak_akses']);

        if (strpos($request->nomorhp, '62') !== false) {
            $input['nomorhp'] = $request->nomorhp;
        } else {
            $input['nomorhp'] = '62' . $request->nomorhp;
        }

        $user = User::with('userGroup')->findOrFail($id);
        $user->fill($input)->save();

        $userGroup = UserGroup::where('group_id', $request->group_id)
            ->where('user_id', $id)
            ->first();

        if ($userGroup) {
            $userGroup->update([
                'hak_akses' => $request->hak_akses
            ]);
        }

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

    public function updateProfilePhoto(Request $request, $id)
    {
        $user = User::with('userGroup')->findOrFail($id);
        if($user->foto_profil) {
            unlink(public_path($user->foto_profil));    
        }
        
        $request->file->move(public_path('users'), $request->file->getClientOriginalName());
        $user->update([
            'foto_profil' => 'users/' . $request->file->getClientOriginalName()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diubah',
            'result' => $user
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

    public function deleteProfilePhoto($id)
    {
        $user = User::with('userGroup')->findOrFail($id);
        if($user->foto_profil) {
            unlink(public_path($user->foto_profil));    
        }
        
        $user->update([
            'foto_profil' => ""
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil dihapus',
            'result' => $user
        ], 200);
    }
}
