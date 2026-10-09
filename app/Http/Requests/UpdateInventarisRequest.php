<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventarisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Mendapatkan ID inventaris dari parameter route
        $inventarisId = $this->route('inventari')?->id 
            ?? $this->route('inventaris')?->id 
            ?? $this->route('inventaris');

        return [
            'kode_barang'        => ['required', 'string', 'max:100'],
            'tanggal_perolehan'  => ['nullable', 'date'],
            'tanggal_pencatatan' => ['nullable', 'date'],
            'nama_barang'        => ['required', 'string', 'max:255'],
            'merk_spesifikasi'   => ['nullable', 'string'],
            'kategori'           => ['nullable', 'string', 'max:100'],
            'jumlah'             => ['required', 'integer', 'min:1'],
            'satuan'             => ['nullable', 'string', 'max:50'],
            'harga_satuan'       => ['required', 'numeric', 'min:0'],
            'no_bast'            => ['nullable', 'string', 'max:100'],
            'sumber_dana'        => ['nullable', 'string', 'max:100'],
            'tahun_anggaran'     => ['nullable', 'digits:4', 'integer'],
            'lokasi_ruang'       => ['nullable', 'string', 'max:100'],
            'kondisi'            => ['nullable', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'penanggung_jawab'   => ['nullable', 'string', 'max:150'],
            'nomor_register'     => ['nullable', 'string', 'max:100'],
            'keterangan'         => ['nullable', 'string'],
            'tautan_dokumen'     => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.required'   => 'Kode barang wajib diisi.',
            'nama_barang.required'   => 'Nama barang wajib diisi.',
            'jumlah.required'        => 'Jumlah barang wajib diisi.',
            'jumlah.min'             => 'Jumlah barang minimal 1 unit.',
            'harga_satuan.required'  => 'Harga satuan wajib diisi.',
            'harga_satuan.min'       => 'Harga satuan tidak boleh negatif.',
            'kondisi.in'             => 'Kondisi barang harus Baik, Rusak Ringan, atau Rusak Berat.',
            'tahun_anggaran.digits'  => 'Tahun anggaran harus 4 digit tahun (contoh: 2026).',
        ];
    }
}
