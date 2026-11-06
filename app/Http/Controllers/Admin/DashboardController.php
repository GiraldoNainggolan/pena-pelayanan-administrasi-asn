<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LayananKepegawaian;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();
        $tomorrow = $today->copy()->addDay();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfNextMonth = $startOfMonth->copy()->addMonth();
        $startOfYear = $today->copy()->startOfYear();
        $startOfNextYear = $startOfYear->copy()->addYear();

        // 1. Rekap Angka
        $todayCount = LayananKepegawaian::where('tanggal', '>=', $today->toDateString())
            ->where('tanggal', '<', $tomorrow->toDateString())
            ->count();
        $monthCount = LayananKepegawaian::where('tanggal', '>=', $startOfMonth->toDateString())
            ->where('tanggal', '<', $startOfNextMonth->toDateString())
            ->count();
        $yearCount = LayananKepegawaian::where('tanggal', '>=', $startOfYear->toDateString())
            ->where('tanggal', '<', $startOfNextYear->toDateString())
            ->count();

        // Status Counts
        $selesaiCount = LayananKepegawaian::where('hasil_layanan', 'Selesai')->count();
        
        $diprosesCount = LayananKepegawaian::whereIn('hasil_layanan', [
            'Sebagian', 
            'Menunggu BKPSDM', 
            'Menunggu BKN'
        ])->count();
        
        $menungguCount = LayananKepegawaian::whereIn('hasil_layanan', [
            'Pending',
            'Ditolak'
        ])->count();

        // 2. Data Grafik
        // A. Kecamatan
        $kecamatanChart = LayananKepegawaian::select('kecamatan', \DB::raw('count(*) as total'))
            ->groupBy('kecamatan')
            ->orderBy('total', 'desc')
            ->get();

        // B. Sekolah
        $sekolahChart = LayananKepegawaian::select('nama_sekolah', \DB::raw('count(*) as total'))
            ->whereNotNull('nama_sekolah')
            ->groupBy('nama_sekolah')
            ->orderBy('total', 'desc')
            ->get();

        // C. Jenis Layanan
        $layananChart = LayananKepegawaian::select('layanan', \DB::raw('count(*) as total'))
            ->groupBy('layanan')
            ->orderBy('total', 'desc')
            ->get();

        // D. Bulan tahun berjalan. Group per tanggal bersifat portable di SQLite/MySQL.
        $monthlyTotals = LayananKepegawaian::select('tanggal', \DB::raw('count(*) as total'))
            ->where('tanggal', '>=', $startOfYear->toDateString())
            ->where('tanggal', '<', $startOfNextYear->toDateString())
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $bulanChart = collect(range(1, 12))->map(function (int $month) use ($monthlyTotals, $monthNames) {
            return (object) [
                'bulan_num' => str_pad((string) $month, 2, '0', STR_PAD_LEFT),
                'bulan' => $monthNames[$month],
                'total' => $monthlyTotals
                    ->filter(fn ($total, $date): bool => Carbon::parse($date)->month === $month)
                    ->sum(),
            ];
        });

        // Recent consultations
        $recentLayanan = LayananKepegawaian::with('creator:id,name')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'todayCount', 'monthCount', 'yearCount',
            'selesaiCount', 'diprosesCount', 'menungguCount',
            'kecamatanChart', 'sekolahChart', 'layananChart', 'bulanChart',
            'recentLayanan'
        ));
    }
}
