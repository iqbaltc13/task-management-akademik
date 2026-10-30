<?php

namespace App\Http\Requests\MasterJenisPelayanan;

use Illuminate\Foundation\Http\FormRequest;

class StoreMasterJenisPelayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:255', 'unique:master_jenis_pelayanans,code'],
            'name' => ['required', 'string', 'max:255'],
            'jumlah_hari' => ['nullable', 'integer', 'min:0', 'max:3650'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode',
            'name' => 'nama',
            'jumlah_hari' => 'jumlah hari',
        ];
    }
}
