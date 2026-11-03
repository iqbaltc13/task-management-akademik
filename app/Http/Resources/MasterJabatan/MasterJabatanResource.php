<?php

namespace App\Http\Resources\MasterJabatan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterJabatanResource extends JsonResource
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
        ];
    }
}
