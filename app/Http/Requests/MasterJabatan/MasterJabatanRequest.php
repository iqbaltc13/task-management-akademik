<?php

namespace App\Http\Requests\MasterJabatan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class MasterJabatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $current = $this->route('master_jabatan');

        return [
            'code' => [
                'required', 'string', 'max:255',
                Rule::unique('master_jabatans', 'code')->ignore($current?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode',
            'name' => 'nama',
        ];
    }
}
