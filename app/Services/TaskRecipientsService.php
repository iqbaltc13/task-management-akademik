<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

class TaskRecipientsService
{
    /**
     * Penerima notifikasi sebuah pelayanan:
     * - pembuat layanan dan atasannya
     * - penerima tugas layanan dan atasannya
     * - semua user yang pernah menerima tugas layanan (jika layanan dialihkan)
     *
     * Atasan = user pada STO induk (code_parent) dari STO milik pembuat / penerima tugas.
     */
    public static function for(Task $task): Collection
    {
        $currentIds = array_values(array_filter([
            $task->created_by_user_id,
            $task->assigned_to_user_id,
        ]));

        $parentCodes = User::with('sto:code,code_parent')
            ->whereIn('id', $currentIds)
            ->get()
            ->map(fn (User $user) => $user->sto?->code_parent)
            ->filter()
            ->unique()
            ->values();

        $superiorIds = $parentCodes->isEmpty()
            ? collect()
            : User::whereIn('code_sto', $parentCodes)->pluck('id');

        $previousIds = $task->assignedUserUpdateLogs()
            ->get(['old_assigned_to_user_id', 'new_assigned_to_user_id'])
            ->flatMap(fn ($log) => [$log->old_assigned_to_user_id, $log->new_assigned_to_user_id]);

        $ids = collect($currentIds)
            ->merge($superiorIds)
            ->merge($previousIds)
            ->filter()
            ->unique()
            ->values();

        return User::whereIn('id', $ids)->get();
    }
}