<?php

namespace App\Http\Requests\MasterSubUnit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class MasterSubUnitRequest extends FormRequest
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
        $current = $this->route('master_sub_unit');

        return [
            'code' => [
                'required', 'string', 'max:255',
                Rule::unique('master_sub_units', 'code')->ignore($current?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'master_unit_code' => [
                'required', 'string',
                Rule::exists('master_units', 'code')->whereNull('archived_at'),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'kode',
            'name' => 'nama',
            'master_unit_code' => 'unit',
        ];
    }
}
