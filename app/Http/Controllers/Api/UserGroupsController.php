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
}
