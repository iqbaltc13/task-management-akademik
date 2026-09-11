<?php

namespace App\Support;

class TaskStatusMapper
{
    public static function map(?string $groupName): ?string
    {
        if (! $groupName) {
            return null;
        }

        return config('task_status.map.'.strtolower(trim($groupName)));
    }
}