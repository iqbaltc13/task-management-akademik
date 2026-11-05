<?php

namespace App\Http\Controllers\MyWork;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ParafPelayananController extends Controller
{
    public function index(): Response
    {
        $this->pimpinan();

        return Inertia::render('MyWork/Paraf/Index');
    }

    public function data(Request $request): JsonResponse
    {
        $user = $this->pimpinan();

        $allowedSorts = ['name', 'code', 'created_at', 'due_on'];
        $sort = in_array($request->input('sort'), $allowedSorts, true) ? $request->input('sort') : 'created_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $tasks = $this->scopedQuery($user)
            ->searchByQueryString()
            ->when($request->input('status') === 'pending', fn ($q) => $q->where('is_approved', false))
            ->when($request->input('status') === 'approved', fn ($q) => $q->where('is_approved', true))
            ->withoutGlobalScope('ordered')
            ->orderBy($sort, $direction)
            ->paginate($request->input('per_page', 15));

        return response()->json($tasks);
    }

    public function show(Task $task): JsonResponse
    {
        $user = $this->pimpinan();
        $this->ensureSubordinateTask($user, $task);

        return response()->json($task->load([
            'createdByUser:id,name',
            'assignedToUser:id,name',
            'taskGroup:id,name',
            'project:id,name',
            'jenisPelayanan:code,name',
            'approvedByUser:id,name',
        ]));
    }

    public function approve(Task $task): JsonResponse
    {
        $user = $this->pimpinan();
        $this->ensureSubordinateTask($user, $task);

        if (! $task->is_approved) {
            $task->update([
                'is_approved' => true,
                'approved_by' => $user->id,
            ]);
        }

        return response()->json(['is_approved' => true]);
    }

    private function pimpinan(): User
    {
        /** @var User $user */
        $user = auth()->user();

        abort_unless($user->isPimpinan(), 403, 'Hanya pimpinan yang dapat mengakses paraf pelayanan.');

        return $user;
    }

    /**
     * Pelayanan yang diterima (created_by) atau ditugaskan (assigned_to)
     * kepada bawahan langsung pimpinan.
     */
    private function scopedQuery(User $user): Builder
    {
        $ids = $user->directSubordinateIds();

        return Task::query()
            ->where(fn ($q) => $q->whereIn('created_by_user_id', $ids)
                ->orWhereIn('assigned_to_user_id', $ids))
            ->with([
                'createdByUser:id,name',
                'assignedToUser:id,name',
                'taskGroup:id,name',
                'project:id,name',
            ]);
    }

    private function ensureSubordinateTask(User $user, Task $task): void
    {
        abort_unless(
            $this->scopedQuery($user)->whereKey($task->getKey())->exists(),
            403,
            'Pelayanan ini bukan milik bawahan langsung Anda.'
        );
    }
}