<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ArusKas;
use App\Models\EventKas;
use App\Models\PjArusKas;
use App\Models\User;
use Illuminate\Http\Request;

class ArusKasController extends Controller
{
    public function index($id)
    {
        if ($id) {
            $eventKas = EventKas::findOrFail($id);
            $perUser = ArusKas::with('user')
                ->groupBy('event_kas_id', 'user_id')
                ->selectRaw('id, event_kas_id, user_id, sum(nominal) as total')
                ->whereMonth('created_at', date('m'))
                ->where('event_kas_id', $id)
                ->where('jenis', '1')
                ->where('user_id', '!=', '0')
                ->get();
            $bulan_ini = ArusKas::selectRaw('event_kas_id, user_id, jenis, sum(nominal) as total, YEAR(created_at) year, MONTH(created_at) month, created_at')
                ->groupBy('event_kas_id', 'month', 'year')
                ->where('event_kas_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'result' => [
                    'kas' => $eventKas,
                    'per_user' => $perUser,
                    'bulan_ini' => $bulan_ini
                ]
            ], 200);
        } else {
            $arusKas = ArusKas::with('eventKas', 'pjArusKas', 'user')->get();

            return response()->json([
                'success' => true,
                'result' => $arusKas
            ], 200);
        }
    }

    public function getDataPerBulan($id, $month, $year)
    {
        $arusKas = ArusKas::with('user')
            ->selectRaw('event_kas_id, user_id, jenis, sum(nominal) as total, YEAR(created_at) year, MONTH(created_at) month, created_at')
            ->groupBy('event_kas_id', 'user_id')
            ->where('event_kas_id', $id)
            ->whereMonth('created_at', '=', $month)
            ->whereYear('created_at', '=', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        $total = ArusKas::selectRaw('sum(nominal) as total')
            ->where('event_kas_id', $id)
            ->whereMonth('created_at', '=', $month)
            ->whereYear('created_at', '=', $year)
            ->first();

        return response()->json([
            'success' => true,
            'result' => [
                'total' => $total,
                'kas' => $arusKas
            ]
        ], 200);
    }

    public function getDataPerUser($id, $user_id)
    {
        $arusKas = ArusKas::with('user', 'eventKas', 'pjArusKas')
            ->where('event_kas_id', $id)
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return response()->json([
            'success' => true,
            'result' => $arusKas
        ], 200);
    }

    public function getDataPerEvent($id)
    {
        $pemasukan = ArusKas::with('user', 'pjArusKas')
            ->where('event_kas_id', $id)
            ->where('jenis', '1')
            ->orderBy('created_at', 'desc')
            ->get();

        $pengeluaran = ArusKas::with('pjArusKas')
            ->where('event_kas_id', $id)
            ->where('jenis', '0')
            ->orderBy('created_at', 'desc')
            ->get();


        return response()->json([
            'success' => true,
            'result' => [
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran
            ]
        ], 200);
    }

    public function getDataPerEventDanBulan($id)
    {
        $arusKas = ArusKas::with('user', 'pjArusKas')
            ->where('event_kas_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'result' => $arusKas
        ], 200);
    }

    public function getBelumBayarKas($id)
    {
        $arusKas = ArusKas::select('user_id')
            ->where('event_kas_id', $id)
            ->whereMonth('created_at', date('m'))
            ->get();
        $user = User::select('id', 'name')->get();

        return response()->json([
            'success' => true,
            'result' => [
                'arus_kas' => $arusKas,
                'user' => $user
            ]
        ], 200);
    }

    public function create(Request $request)
    {
        $arusKas = ArusKas::create([
            'event_kas_id' => $request->event_kas_id,
            'user_id' => $request->user_id,
            'jenis' => $request->jenis,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan
        ]);

        if ($arusKas) {
            PjArusKas::create([
                'arus_kas_id' => $arusKas->id,
                'user_id' => $request->id_pj,
            ]);

            $eventKas = EventKas::findOrFail($request->event_kas_id);
            if ($request->jenis == "1") {
                $pemasukan = (int) $request->nominal + (int) $eventKas->total_pemasukan;
                $pengeluaran = $eventKas->total_pengeluaran;
                $total = (int) $request->nominal + (int) $eventKas->total_kas;
            } else {
                $pemasukan = $eventKas->total_pemasukan;
                $pengeluaran = (int) $request->nominal + (int) $eventKas->total_pengeluaran;
                $total = (int) $eventKas->total_kas - (int) $request->nominal;
            }

            $eventKas->update([
                'total_pemasukan' => $pemasukan,
                'total_pengeluaran' => $pengeluaran,
                'total_kas' => $total
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $arusKas = ArusKas::findOrFail($id);
        $eventKas = EventKas::findOrFail($request->event_kas_id);

        // hitung pemasukan
        if ($request->jenis == "1") {
            $pengeluaran = (int) $eventKas->total_pengeluaran;

            // jika nominal sebelumnya lebih besar daripada nominal update
            if ((int) $arusKas->nominal > (int) $request->nominal) {
                $selisih_pemasukan = (int) $arusKas->nominal - (int) $request->nominal;
                $pemasukan = (int) $eventKas->total_pemasukan - (int) $selisih_pemasukan;
                $total = (int) $eventKas->total_kas - (int) $selisih_pemasukan;
            } else {
                $selisih_pemasukan = (int) $request->nominal - (int) $arusKas->nominal;
                $pemasukan = (int) $eventKas->total_pemasukan + (int) $selisih_pemasukan;
                $total = (int) $eventKas->total_kas + (int) $selisih_pemasukan;
            }
        } else { // hitung pengeluaran
            $pemasukan = (int) $eventKas->total_pemasukan;

            // jika nominal sebelumnya lebih besar daripada nominal update
            if ((int) $arusKas->nominal > (int) $request->nominal) {
                $selisih_pengeluaran = (int) $arusKas->nominal - (int) $request->nominal;
                $pengeluaran = (int) $eventKas->total_pengeluaran - (int) $selisih_pengeluaran;
                $total = (int) $eventKas->total_kas + (int) $selisih_pengeluaran;
            } else {
                $selisih_pengeluaran = (int) $request->nominal - (int) $arusKas->nominal;
                $pengeluaran = (int) $eventKas->total_pengeluaran + (int) $selisih_pengeluaran;
                $total = (int) $eventKas->total_kas - (int) $selisih_pengeluaran;
            }
        }

        // update event kas 
        $eventKas->update([
            'total_pemasukan' => $pemasukan,
            'total_pengeluaran' => $pengeluaran,
            'total_kas' => $total
        ]);

        // update arus kas
        $arusKas->update([
            'event_kas_id' => $request->event_kas_id,
            'user_id' => $request->user_id,
            'jenis' => $request->jenis,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan
        ]);

        if ($arusKas) {
            $pjArusKas = PjArusKas::where('arus_kas_id', $id)->first();
            $pjArusKas->update([
                'arus_kas_id' => $arusKas->id,
                'user_id' => $request->id_pj,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diupdate',
        ], 200);
    }

    public function delete($id)
    {
        $arusKas = ArusKas::findOrFail($id);
        $eventKas = EventKas::findOrFail($arusKas->event_kas_id);

        if ($arusKas->jenis == "1") {
            $pemasukan = (int) $eventKas->total_pemasukan - (int) $arusKas->nominal;
            $pengeluaran = $eventKas->total_pengeluaran;
            $total = (int) $eventKas->total_kas - (int) $arusKas->nominal;
        } else {
            $pemasukan = $eventKas->total_kas;
            $pengeluaran = (int) $eventKas->total_pengeluaran - (int) $arusKas->nominal;
            $total = (int) $eventKas->total_kas - (int) $arusKas->nominal;
        }

        $eventKas->update([
            'total_pemasukan' => $pemasukan,
            'total_pengeluaran' => $pengeluaran,
            'total_kas' => $total
        ]);

        if ($arusKas) {
            $pjArusKas = PjArusKas::where('arus_kas_id', $id)->get();
            if ($pjArusKas) {
                foreach ($pjArusKas as $value) {
                    $value->delete();
                }
            }

            $arusKas->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ], 200);
    }
}
