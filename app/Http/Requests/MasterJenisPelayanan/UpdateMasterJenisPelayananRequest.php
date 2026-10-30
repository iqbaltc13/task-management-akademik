<?php

namespace App\Http\Requests\MasterJenisPelayanan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMasterJenisPelayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 'code' sengaja tidak bisa diubah: tasks.code_jenis_pelayanan memiliki
     * foreign key ke master_jenis_pelayanans.code.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'jumlah_hari' => ['nullable', 'integer', 'min:0', 'max:3650'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'jumlah_hari' => 'jumlah hari',
        ];
    }
}
