<?php

namespace App\Http\Resources\MasterSubUnit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterSubUnitResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'master_unit_code' => $this->master_unit_code,
            'unit_name' => $this->whenLoaded('unit', fn () => $this->unit?->name),
        ];
    }
}
