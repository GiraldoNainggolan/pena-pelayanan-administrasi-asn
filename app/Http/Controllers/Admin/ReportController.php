<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LayananKepegawaian;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'periode' => ['nullable', 'string', 'in:harian,mingguan,bulanan,triwulan,semester,tahunan'],
            'aplikasi' => ['nullable', 'string', 'in:'.implode(',', array_keys(LayananKepegawaian::LAYANAN_OPTIONS))],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'hasil' => ['nullable', 'string', 'in:'.implode(',', LayananKepegawaian::HASIL_OPTIONS)],
        ]);

        $query = LayananKepegawaian::query();

        // 1. Filter Periode
        $periode = $validated['periode'] ?? 'bulanan';
        $now = Carbon::now();

        if ($periode === 'harian') {
            $start = $now->copy()->startOfDay();
            $endExclusive = $start->copy()->addDay();
        } elseif ($periode === 'mingguan') {
            $start = $now->copy()->startOfWeek(Carbon::MONDAY);
            $endExclusive = $start->copy()->addWeek();
        } elseif ($periode === 'bulanan') {
            $start = $now->copy()->startOfMonth();
            $endExclusive = $start->copy()->addMonth();
        } elseif ($periode === 'triwulan') {
            $quarterStartMonth = intdiv($now->month - 1, 3) * 3 + 1;
            $start = Carbon::create($now->year, $quarterStartMonth, 1)->startOfDay();
            $endExclusive = $start->copy()->addMonths(3);
        } elseif ($periode === 'semester') {
            $start = Carbon::create($now->year, $now->month <= 6 ? 1 : 7, 1)->startOfDay();
            $endExclusive = $start->copy()->addMonths(6);
        } else {
            $start = $now->copy()->startOfYear();
            $endExclusive = $start->copy()->addYear();
        }

        $query->where('tanggal', '>=', $start->toDateString())
            ->where('tanggal', '<', $endExclusive->toDateString());

        // 2. Filter Aplikasi
        if (! empty($validated['aplikasi'])) {
            $query->where('layanan', $validated['aplikasi']);
        }

        // 3. Filter Kecamatan
        if (! empty($validated['kecamatan'])) {
            $query->where('kecamatan', $validated['kecamatan']);
        }

        // 4. Filter Hasil Layanan
        if (! empty($validated['hasil'])) {
            $query->where('hasil_layanan', $validated['hasil']);
        }

        $recordCount = (clone $query)->count();
        $records = $query->latest('tanggal')->latest('jam')->limit(5000)->get();
        $truncated = $recordCount > $records->count();

        // Calculations for charts
        $byAplikasi = $records->groupBy('layanan')->map->count();
        $byKecamatan = $records->groupBy('kecamatan')->map->count();
        $byHasil = $records->groupBy('hasil_layanan')->map->count();

        // Chronological trend (Line chart simulation)
        $trend = $records->sortBy('tanggal')->groupBy(function ($record) {
            return $record->tanggal->format('d-M');
        })->map->count();

        $kecamatans = Kecamatan::orderBy('nama')->get();

        return view('admin.laporan', compact(
            'records', 'recordCount', 'truncated', 'periode', 'byAplikasi', 'byKecamatan', 'byHasil', 'trend', 'kecamatans'
        ));
    }
}
