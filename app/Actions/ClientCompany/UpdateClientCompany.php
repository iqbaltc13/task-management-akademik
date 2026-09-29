<?php

namespace App\Actions\ClientCompany;

use App\Models\ClientCompany;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UpdateClientCompany
{
    public function update(ClientCompany $clientCompany, array $data): bool
    {
        DB::beginTransaction();

        try {
            if (! empty($data['clients'])) {
                $clientCompany->clients()->sync($data['clients']);
            }

            $result = $clientCompany->update(Arr::except($data, ['clients']));

            DB::commit();

            return $result;
        } catch (QueryException $e) {
            DB::rollback();

            throw $e;
        }
    }
}
