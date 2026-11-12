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
use App\Exports\ParafListExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

        $tasks = $this->filteredQuery($request, $user)
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

        public function exportXlsx(Request $request): BinaryFileResponse
    {
        $user = $this->pimpinan();

        return Excel::download(
            new ParafListExport($this->exportRows($request, $user)),
            'paraf-pelayanan-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $user = $this->pimpinan();

        $pdf = Pdf::loadView('exports.paraf-list-pdf', ['rows' => $this->exportRows($request, $user)])
            ->setPaper('a4', 'landscape');

        return $pdf->download('paraf-pelayanan-'.now()->format('Ymd-His').'.pdf');
    }

private function filteredQuery(Request $request, User $user): Builder
    {
        $groupCode = $request->input('group_code');

        return $this->scopedQuery($user)
            ->searchByQueryString()
            ->when($request->input('status') === 'pending', fn ($q) => $q->where('is_approved', false))
            ->when($request->input('status') === 'approved', fn ($q) => $q->where('is_approved', true))
            // Filter status pelayanan: task_groups.code (tasks.group_id = task_groups.id)
            ->when(
                filled($groupCode) && $groupCode !== 'OVERDUE',
                fn ($q) => $q->whereHas('taskGroup', fn ($g) => $g->where('code', $groupCode))
            )
            // Overdue: belum selesai tetapi lewat batas, atau selesai tetapi melewati batas
            ->when($groupCode === 'OVERDUE', function ($q) {
                $q->whereNotNull('tasks.due_on')->where(function ($w) {
                    $w->where(fn ($x) => $x->whereNull('tasks.completed_at')
                        ->whereDate('tasks.due_on', '<', now()->toDateString()))
                      ->orWhere(fn ($x) => $x->whereNotNull('tasks.completed_at')
                        ->whereRaw('DATE(tasks.completed_at) > tasks.due_on'));
                });
            })
            ->withoutGlobalScope('ordered');
    }

    private function exportRows(Request $request, User $user): Collection
    {
        return $this->filteredQuery($request, $user)
            ->with('approvedByUser:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn (Task $task) => [
                'Nama Pemohon' => $task->name,
                'Nomor Pelayanan' => $task->code,
                'Penerima Pelayanan' => $task->createdByUser?->name ?? '-',
                'Penerima Tugas' => $task->assignedToUser?->name ?? '-',
                'Status' => $task->taskGroup?->name ?? '-',
                'Tanggal Masuk' => $task->created_at?->format('d-m-Y'),
                'Status Paraf' => $task->is_approved ? 'Sudah diparaf' : 'Belum diparaf',
                'Diparaf Oleh' => $task->approvedByUser?->name ?? '-',
            ]);
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