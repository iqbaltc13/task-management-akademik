<?php

namespace App\Http\Resources\MasterJenisPelayanan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasterJenisPelayananResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'jumlah_hari' => $this->jumlah_hari,
            'keterangan' => $this->keterangan,
        ];
    }
}
