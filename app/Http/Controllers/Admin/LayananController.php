<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LayananRequest;
use App\Models\Kecamatan;
use App\Models\LayananKepegawaian;
use App\Models\Sekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LayananController extends Controller
{
    public function index(Request $request): View
    {
        $layananList = $this->filteredQuery($request)
            ->with('creator:id,name')
            ->latest('tanggal')
            ->latest('jam')
            ->paginate(10)
            ->withQueryString();
        
        // References for filters
        $kecamatans = Kecamatan::orderBy('nama')->get();
        $sekolahs = Sekolah::orderBy('nama')->get();

        return view('admin.layanan.index', compact('layananList', 'kecamatans', 'sekolahs'));
    }

    public function create(): View
    {
        $kecamatans = Kecamatan::with('sekolahs')->orderBy('nama')->get();
        return view('admin.layanan.create', compact('kecamatans'));
    }

    public function store(LayananRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $now = Carbon::now();
            $serviceDate = $now->toDateString();

            DB::table('service_number_sequences')->upsert([
                [
                    'service_date' => $serviceDate,
                    'last_number' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            ], ['service_date'], []);

            $sequence = DB::table('service_number_sequences')
                ->where('service_date', $serviceDate)
                ->lockForUpdate()
                ->first();

            $lastNumber = (int) ($sequence->last_number ?? 0);
            if ($lastNumber === 0) {
                $prefix = 'PENA-'.$now->format('Ymd').'-';
                $lastNumber = LayananKepegawaian::query()
                    ->where('nomor_layanan', 'like', $prefix.'%')
                    ->pluck('nomor_layanan')
                    ->map(fn (string $number): int => (int) substr($number, strrpos($number, '-') + 1))
                    ->max() ?? 0;
            }

            $nextNumber = $lastNumber + 1;
            DB::table('service_number_sequences')
                ->where('service_date', $serviceDate)
                ->update(['last_number' => $nextNumber, 'updated_at' => $now]);

            LayananKepegawaian::create(array_merge(
                $this->servicePayload($request),
                [
                    'nomor_layanan' => 'PENA-'.$now->format('Ymd').'-'.str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT),
                    'tanggal' => $serviceDate,
                    'jam' => $now->format('H:i:s'),
                    'created_by' => $request->user()->id,
                ]
            ));
        }, 5);

        return redirect()->route('admin.layanan.index')->with('status', 'Data layanan berhasil disimpan.');
    }

    public function show($id): View
    {
        $layanan = LayananKepegawaian::findOrFail($id);
        return view('admin.layanan.show', compact('layanan'));
    }

    public function edit($id): View
    {
        $layanan = LayananKepegawaian::findOrFail($id);
        $kecamatans = Kecamatan::with('sekolahs')->orderBy('nama')->get();
        return view('admin.layanan.edit', compact('layanan', 'kecamatans'));
    }

    public function update(LayananRequest $request, $id): RedirectResponse
    {
        $layanan = LayananKepegawaian::findOrFail($id);

        $layanan->update($this->servicePayload($request));

        return redirect()->route('admin.layanan.index')->with('status', 'Data layanan berhasil diupdate.');
    }

    public function destroy($id): RedirectResponse
    {
        $layanan = LayananKepegawaian::findOrFail($id);
        $layanan->delete();

        return redirect()->route('admin.layanan.index')->with('status', 'Data layanan berhasil dihapus.');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $fileName = 'Laporan-Layanan-Kepegawaian-' . now()->format('Ymd-His') . '.xls';

        $query = $this->filteredQuery($request)->orderBy('id');

        return response()->streamDownload(function () use ($query): void {
            echo $this->workbookHeader();

            foreach ($query->lazyById(250) as $record) {
                echo $this->workbookRow($record);
            }

            echo '</Table></Worksheet></Workbook>';
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    private function workbookHeader(): string
    {
        $headers = [
            'Nomor Layanan',
            'Tanggal & Jam',
            'Nama ASN',
            'NIP',
            'NIK',
            'No HP',
            'Email',
            'Kecamatan',
            'Jenjang',
            'Sekolah/Unit Kerja',
            'Layanan',
            'Sub Layanan',
            'Permasalahan',
            'Tindak Lanjut',
            'Hasil Layanan',
        ];

        return '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL
            . '<?mso-application progid="Excel.Sheet"?>' . PHP_EOL
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" '
            . 'xmlns:o="urn:schemas-microsoft-com:office:office" '
            . 'xmlns:x="urn:schemas-microsoft-com:office:excel" '
            . 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
            . '<Styles>'
            . '<Style ss:ID="Header"><Font ss:Bold="1"/><Interior ss:Color="#BAE6FD" ss:Pattern="Solid"/></Style>'
            . '</Styles>'
            . '<Worksheet ss:Name="Daftar Layanan"><Table>'
            . '<Row>'.collect($headers)->map(fn ($header) => $this->cell($header, 'Header'))->implode('').'</Row>';
    }

    private function workbookRow(LayananKepegawaian $record): string
    {
        return '<Row>'.collect([
            $record->nomor_layanan,
            $record->tanggal->format('d M Y').' '.$record->jam,
            $record->nama_asn,
            $record->nip,
            $record->nik ?? '-',
            $record->no_hp,
            $record->email,
            $record->kecamatan ?? '-',
            $record->jenjang ?? '-',
            $record->nama_sekolah ?? $record->unit_kerja ?? '-',
            $record->layanan,
            $record->sub_layanan,
            $record->permasalahan,
            $record->tindak_lanjut ?? '-',
            $record->hasil_layanan,
        ])->map(fn ($value) => $this->cell((string) $value))->implode('').'</Row>';
    }

    private function cell(string $value, ?string $styleId = null): string
    {
        $style = $styleId ? ' ss:StyleID="' . $styleId . '"' : '';
        $escaped = htmlspecialchars($value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
        return '<Cell' . $style . '><Data ss:Type="String">' . $escaped . '</Data></Cell>';
    }

    public function print($id): View
    {
        // For our local dummy print, we return a printable HTML view that automatically invokes window.print()
        $layanan = LayananKepegawaian::findOrFail($id);
        return view('admin.layanan.print', compact('layanan'));
    }

    private function filteredQuery(Request $request): Builder
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tanggal' => ['nullable', 'date_format:Y-m-d'],
            'kecamatan' => ['nullable', 'string', 'max:255'],
            'sekolah' => ['nullable', 'string', 'max:255'],
            'layanan' => ['nullable', 'string', 'in:'.implode(',', array_keys(LayananKepegawaian::LAYANAN_OPTIONS))],
            'hasil' => ['nullable', 'string', 'in:'.implode(',', LayananKepegawaian::HASIL_OPTIONS)],
        ]);

        return LayananKepegawaian::query()
            ->when($filters['q'] ?? null, function (Builder $query, string $keyword): void {
                $escaped = addcslashes($keyword, '%_\\');
                $query->where(function (Builder $subQuery) use ($escaped): void {
                    $subQuery->where('nama_asn', 'like', "%{$escaped}%")
                        ->orWhere('nip', 'like', "%{$escaped}%")
                        ->orWhere('nik', 'like', "%{$escaped}%")
                        ->orWhere('nomor_layanan', 'like', "%{$escaped}%");
                });
            })
            ->when($filters['tanggal'] ?? null, fn (Builder $query, string $date) => $query->where('tanggal', $date))
            ->when($filters['kecamatan'] ?? null, fn (Builder $query, string $value) => $query->where('kecamatan', $value))
            ->when($filters['sekolah'] ?? null, fn (Builder $query, string $value) => $query->where('nama_sekolah', $value))
            ->when($filters['layanan'] ?? null, fn (Builder $query, string $value) => $query->where('layanan', $value))
            ->when($filters['hasil'] ?? null, fn (Builder $query, string $value) => $query->where('hasil_layanan', $value));
    }

    /** @return array<string, mixed> */
    private function servicePayload(LayananRequest $request): array
    {
        $validated = $request->validated();
        $kecamatan = $request->filled('kecamatan_id')
            ? Kecamatan::findOrFail($request->integer('kecamatan_id'))
            : null;
        $sekolah = $request->filled('sekolah_id')
            ? Sekolah::findOrFail($request->integer('sekolah_id'))
            : null;

        return array_merge($validated, [
            'kecamatan' => $kecamatan?->nama,
            'nama_sekolah' => $sekolah?->nama,
            'unit_kerja' => $sekolah ? null : ($validated['unit_kerja'] ?? null),
        ]);
    }
}
