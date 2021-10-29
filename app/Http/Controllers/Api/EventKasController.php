<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventKas;
use Illuminate\Http\Request;

class EventKasController extends Controller
{
    public function index($group_id)
    {
        return response()->json([
            'success' => true,
            'result' => [
                'event' => EventKas::where('group_id', $group_id)->get(),
                'pemasukan' => EventKas::where('status', '1')->sum("total_pemasukan"),
                'pengeluaran' => EventKas::where('status', '1')->sum("total_pengeluaran"),
                'total' => EventKas::where('status', '1')->sum("total_kas")
            ]
        ], 200);
    }

    public function getDataById($id)
    {
        return response()->json([
            'success' => true,
            'result' => EventKas::with('arusKas')->where('id', $id)->first()
        ], 200);
    }

    public function create(Request $request)
    {
        $input = $request->all();
        EventKas::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $eventKas = EventKas::findOrFail($id);
        $input = $request->all();
        $eventKas->fill($input)->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    public function delete($id)
    {
        $eventKas = EventKas::findOrFail($id);
        $eventKas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
