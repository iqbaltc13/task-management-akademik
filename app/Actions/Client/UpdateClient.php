<?php

namespace App\Actions\Client;

use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateClient
{
    public function update($user, array $data): bool
    {
        $newData = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
        ];

        if ($user->avatar === null || $data['avatar']) {
            $newData['avatar'] = UserService::storeOrFetchAvatar($user, $data['avatar']);
        }

        if (! empty($data['password'])) {
            $newData['password'] = Hash::make($data['password']);
        }

        DB::beginTransaction();

        try {
            if (! empty($data['companies'])) {
                $user->clientCompanies()->sync($data['companies']);
            }

            $result = $user->update($newData);

            DB::commit();

            return $result;
        } catch (QueryException $e) {
            DB::rollback();

            throw $e;
        }
    }
}
