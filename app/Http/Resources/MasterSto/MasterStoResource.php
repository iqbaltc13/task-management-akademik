<?php

namespace App\Http\Resources\MasterSto;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterStoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'code_parent' => $this->code_parent,
            'name' => $this->name,
            'id_unit' => $this->id_unit,
            'id_sub_unit' => $this->id_sub_unit,
            'is_pimpinan' => (bool) $this->is_pimpinan,
            'level' => $this->level,
            'id_jabatan' => $this->id_jabatan,
            'parent_name' => $this->whenLoaded('parent', fn () => $this->parent?->name),
            'unit_name' => $this->whenLoaded('unit', fn () => $this->unit?->name),
            'sub_unit_name' => $this->whenLoaded('subUnit', fn () => $this->subUnit?->name),
            'jabatan_name' => $this->whenLoaded('jabatan', fn () => $this->jabatan?->name),
        ];
    }
}
