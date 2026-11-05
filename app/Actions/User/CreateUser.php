<?php

namespace App\Actions\User;

use App\Events\UserCreated;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\MasterSto;

class CreateUser
{
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'code_sto' => $data['code_sto'],
                'job_title' => MasterSto::where('code', $data['code_sto'])->value('name'),
                'phone' => $data['phone'],
                'rate' => $data['rate'] * 100,
                'email' => $data['email'],
                'nip' => $data['nip'] ?? null,
                'password' => Hash::make($data['password']),
            ]);

            $user->update(['avatar' => UserService::storeOrFetchAvatar($user, $data['avatar'])]);

            $user->assignRole($data['roles']);

            UserCreated::dispatch($user, $data['password']);

            return $user;
        });
    }
}
