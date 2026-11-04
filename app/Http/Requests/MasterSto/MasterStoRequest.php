<?php

namespace App\Http\Requests\MasterSto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\MasterSto;
use App\Models\MasterSubUnit;
use Illuminate\Validation\Validator;


class MasterStoRequest extends FormRequest
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
        $current = $this->route('master_sto');

        return [
            'code' => [
                'required', 'string', 'max:255',
                Rule::unique('master_stos', 'code')->ignore($current?->id),
            ],
            'code_parent' => [
                'nullable', 'string', 'different:code',
                Rule::exists('master_stos', 'code')->whereNull('archived_at'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'id_unit' => [
                'nullable', 'integer',
                Rule::exists('master_units', 'id')->whereNull('archived_at'),
            ],
            'id_sub_unit' => [
                'nullable', 'integer',
                Rule::exists('master_sub_units', 'id')->whereNull('archived_at'),
            ],
            'is_pimpinan' => ['required', 'boolean'],
            'level' => ['required', 'integer', 'min:1'],
            'id_jabatan' => [
                'nullable', 'integer',
                Rule::exists('master_jabatans', 'id')->whereNull('archived_at'),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode',
            'code_parent' => 'induk',
            'name' => 'nama',
            'id_unit' => 'unit',
            'id_sub_unit' => 'sub unit',
            'is_pimpinan' => 'pimpinan',
            'level' => 'level',
            'id_jabatan' => 'jabatan',
        ];
    }

    /**
     * Validasi lintas-kolom: sub unit harus milik unit yang dipilih,
     * dan induk tidak boleh berupa turunan dari dirinya sendiri.
     *
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                if ($this->filled('id_sub_unit')) {
                    $subUnit = MasterSubUnit::with('unit')->find($this->input('id_sub_unit'));

                    if (! $this->filled('id_unit') || $subUnit?->unit?->id != $this->input('id_unit')) {
                        $validator->errors()->add('id_sub_unit', 'Sub unit tidak termasuk dalam unit yang dipilih.');
                    }
                }

                $current = $this->route('master_sto');

                if ($current && $this->filled('code_parent')) {
                    $invalid = array_merge([$current->code], $current->descendantCodes());

                    if (in_array($this->input('code_parent'), $invalid, true)) {
                        $validator->errors()->add('code_parent', 'Induk tidak boleh berupa dirinya sendiri atau turunannya.');
                    }
                }
            },
        ];
    }
}
