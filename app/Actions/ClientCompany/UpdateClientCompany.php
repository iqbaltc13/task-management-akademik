<?php

namespace App\Actions\ClientCompany;

use App\Models\ClientCompany;
use Illuminate\Support\Arr;

class UpdateClientCompany
{
    public function update(ClientCompany $clientCompany, array $data): bool
    {
        if (! empty($data['clients'])) {
            $clientCompany->clients()->sync($data['clients']);
        }

        return $clientCompany->update(Arr::except($data, ['clients']));
    }
}
