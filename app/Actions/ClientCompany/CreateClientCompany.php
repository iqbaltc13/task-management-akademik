<?php

namespace App\Actions\ClientCompany;

use App\Models\ClientCompany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class CreateClientCompany
{
    public function create(array $data): ClientCompany
    {

        return DB::transaction(function () use ($data) {
            $clientCompany = ClientCompany::create(Arr::except($data, ['clients']));

            if (! empty($data['clients'])) {
                $clientCompany->clients()->attach($data['clients']);
            }

            return $clientCompany;
        });
    }
}
