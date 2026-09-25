<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilSekolahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_sekolah'      => ['required', 'string', 'max:255'],
            'npsn'              => ['required', 'string', 'max:20'],
            'tahun_anggaran'    => ['required', 'digits:4', 'integer'],
            'sumber_dana'       => ['required', 'string', 'max:100'],
            'nama_kepsek'       => ['required', 'string', 'max:150'],
            'nip_kepsek'        => ['required', 'string', 'max:30'],
            'nama_waka_sarpras' => ['required', 'string', 'max:150'],
            'nip_waka_sarpras'  => ['required', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_sekolah.required'      => 'Nama sekolah wajib diisi.',
            'npsn.required'              => 'NPSN sekolah wajib diisi.',
            'tahun_anggaran.required'    => 'Tahun anggaran wajib diisi.',
            'nama_kepsek.required'       => 'Nama Kepala Sekolah wajib diisi.',
            'nip_kepsek.required'        => 'NIP Kepala Sekolah wajib diisi.',
            'nama_waka_sarpras.required' => 'Nama Waka Sarpras wajib diisi.',
            'nip_waka_sarpras.required'  => 'NIP Waka Sarpras wajib diisi.',
        ];
    }
}
