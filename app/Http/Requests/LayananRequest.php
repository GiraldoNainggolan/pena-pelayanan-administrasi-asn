<?php

namespace App\Http\Requests;

use App\Models\LayananKepegawaian;
use App\Models\Sekolah;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class LayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'operator'], true);
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'nama_asn' => ['required', 'string', 'max:255'],
            'nip' => ['required', 'string', 'regex:/^[0-9]{8,30}$/'],
            'nik' => ['nullable', 'string', 'regex:/^[0-9]{16}$/'],
            'no_hp' => ['required', 'string', 'regex:/^\+?[0-9]{9,15}$/'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'kecamatan_id' => ['nullable', 'integer', 'exists:kecamatans,id'],
            'sekolah_id' => ['nullable', 'integer', 'exists:sekolahs,id'],
            'jenjang' => ['nullable', 'string', 'in:'.implode(',', LayananKepegawaian::JENJANG)],
            'unit_kerja' => ['nullable', 'required_without:sekolah_id', 'string', 'max:500'],
            'layanan' => ['required', 'string', 'in:'.implode(',', array_keys(LayananKepegawaian::LAYANAN_OPTIONS))],
            'sub_layanan' => ['required', 'string', 'max:255'],
            'permasalahan' => ['required', 'string', 'max:5000'],
            'tindak_lanjut' => ['nullable', 'string', 'max:10000'],
            'hasil_layanan' => ['required', 'string', 'in:'.implode(',', LayananKepegawaian::HASIL_OPTIONS)],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $layanan = (string) $this->input('layanan');
                $subLayanan = (string) $this->input('sub_layanan');

                if ($layanan !== '' && $subLayanan !== ''
                    && ! in_array($subLayanan, LayananKepegawaian::LAYANAN_OPTIONS[$layanan] ?? [], true)) {
                    $validator->errors()->add('sub_layanan', 'Sub layanan tidak sesuai dengan jenis layanan yang dipilih.');
                }

                if (! $this->filled('sekolah_id')) {
                    return;
                }

                $sekolah = Sekolah::find($this->integer('sekolah_id'));
                if (! $sekolah) {
                    return;
                }

                if (! $this->filled('kecamatan_id') || $sekolah->kecamatan_id !== $this->integer('kecamatan_id')) {
                    $validator->errors()->add('sekolah_id', 'Sekolah tidak berada pada kecamatan yang dipilih.');
                }

                if ($this->filled('jenjang') && $sekolah->jenjang !== $this->string('jenjang')->toString()) {
                    $validator->errors()->add('sekolah_id', 'Sekolah tidak sesuai dengan jenjang yang dipilih.');
                }
            },
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nip' => preg_replace('/\D+/', '', (string) $this->input('nip')),
            'nik' => $this->filled('nik') ? preg_replace('/\D+/', '', (string) $this->input('nik')) : null,
            'no_hp' => preg_replace('/(?!^)\D+/', '', (string) $this->input('no_hp')),
            'email' => mb_strtolower(trim((string) $this->input('email'))),
            'unit_kerja' => $this->filled('unit_kerja') ? trim((string) $this->input('unit_kerja')) : null,
        ]);
    }
}