<?php

namespace App\Http\Resources\MasterUnit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterUnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
        ];
    }
}