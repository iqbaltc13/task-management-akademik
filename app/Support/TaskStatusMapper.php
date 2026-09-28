<?php

namespace App\Support;

class TaskStatusMapper
{
    public static function map(?string $name): ?string
    {
        $key = strtolower(trim($name ?? ''));

        return config('task.map')[$key] ?? null;
    }
}