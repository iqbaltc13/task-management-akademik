<?php

namespace App\Actions\User;

use App\Services\UserService;
use App\Models\MasterSto;
use Illuminate\Support\Facades\Hash;

class UpdateUser
{
    public function update($user, array $data): bool
    {
        $newData = [
            'name' => $data['name'],
            'job_title' => $data['job_title'],
            'code_sto' => $data['code_sto'],
            'job_title' => MasterSto::where('code', $data['code_sto'])->value('name'),
            'phone' => $data['phone'],
            'rate' => $data['rate'] * 100,
            'nip' => $data['nip'] ?? null,
            'email' => $data['email'],
        ];

        $user->syncRoles($data['roles']);

        if ($user->avatar === null || $data['avatar']) {
            $newData['avatar'] = UserService::storeOrFetchAvatar($user, $data['avatar']);
        }

        if (! empty($data['password'])) {
            $newData['password'] = Hash::make($data['password']);
        }

        return $user->update($newData);
    }
}
