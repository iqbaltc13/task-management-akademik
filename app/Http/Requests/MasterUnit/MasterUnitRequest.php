<?php

namespace App\Http\Requests\MasterUnit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MasterUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $current = $this->route('master_unit');

        return [
            'code' => [
                'required', 'string', 'max:255',
                Rule::unique('master_units', 'code')->ignore($current?->id),
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