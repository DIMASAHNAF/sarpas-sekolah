<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventarisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_barang'        => ['required', 'string', 'max:100', 'unique:inventaris_barangs,kode_barang'],
            'tanggal_perolehan'  => ['required', 'date'],
            'tanggal_pencatatan' => ['required', 'date'],
            'nama_barang'        => ['required', 'string', 'max:255'],
            'merk_spesifikasi'   => ['required', 'string'],
            'kategori'           => ['required', 'string', 'max:100'],
            'jumlah'             => ['required', 'integer', 'min:1'],
            'satuan'             => ['required', 'string', 'max:50'],
            'harga_satuan'       => ['required', 'numeric', 'min:0'],
            'no_bast'            => ['required', 'string', 'max:100'],
            'sumber_dana'        => ['required', 'string', 'max:100'],
            'tahun_anggaran'     => ['required', 'digits:4', 'integer'],
            'lokasi_ruang'       => ['required', 'string', 'max:100'],
            'kondisi'            => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
            'penanggung_jawab'   => ['required', 'string', 'max:150'],
            'nomor_register'     => ['required', 'string', 'max:100'],
            'keterangan'         => ['nullable', 'string'],
            'tautan_dokumen'     => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_barang.required'   => 'Kode barang wajib diisi.',
            'kode_barang.unique'     => 'Kode barang sudah terdaftar, gunakan kode lain.',
            'tanggal_perolehan.date' => 'Format tanggal perolehan tidak valid.',
            'nama_barang.required'   => 'Nama barang wajib diisi.',
            'jumlah.min'             => 'Jumlah barang minimal 1 unit.',
            'harga_satuan.min'       => 'Harga satuan tidak boleh negatif.',
            'kondisi.in'             => 'Kondisi barang harus Baik, Rusak Ringan, atau Rusak Berat.',
            'tahun_anggaran.digits'  => 'Tahun anggaran harus 4 digit tahun (contoh: 2024).',
            'tautan_dokumen.url'     => 'Tautan dokumen harus berupa URL valid (http:// atau https://).',
        ];
    }
}
