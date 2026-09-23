<?php

namespace App\Http\Controllers\MyWork;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MyWorkTaskController extends Controller
{
    public function index(): Response
    {
        /** @var \App\Models\User */
        $user = auth()->user();

        $projects = PermissionService::projectsThatUserCanAccess($user);

        return Inertia::render('MyWork/Tasks/Index', [
            'projects' => Project::whereIn('id', $projects->pluck('id'))
                ->with([
                    'clientCompany:id,name',
                    'tasks' => function ($query) use ($user) {
                        $query->when($user->hasRole('client'), fn ($query) => $query->where('hidden_from_clients', false))
                            ->where('assigned_to_user_id', $user->id)
                            ->whereNull('completed_at')
                            ->withoutGlobalScope('ordered')
                            ->orderByRaw('due_on DESC')
                            ->with([
                                'labels:id,name,color',
                                'assignedToUser:id,name',
                                'taskGroup:id,name',
                            ]);
                    },
                ])
                ->withExists('favoritedByAuthUser AS favorite')
                ->orderBy('favorite', 'desc')
                ->orderBy('name', 'asc')
                ->get(),
        ]);
    }

    public function list(): Response
    {
        $user = Auth::user();

        return Inertia::render('MyWork/Tasks/ListIndex', [
            'projects' => PermissionService::projectsThatUserCanAccess($user)
                ->map(fn ($p) => ['id' => $p->id, 'name' => $p->name])
                ->values(),
        ]);
    }

    private function scopedQuery(Request $request): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        $type = in_array($request->input('type'), ['active', 'completed', 'received', 'transferred'], true)
            ? $request->input('type')
            : 'active';

        $accessibleProjectIds = PermissionService::projectsThatUserCanAccess($user)->pluck('id');

        $query = Task::query()
            ->whereIn('project_id', $accessibleProjectIds)
            ->with([
                'createdByUser:id,name',
                'assignedToUser:id,name',
                'taskGroup:id,name',
                'project:id,name',
            ])
            ->searchByQueryString()
            ->when($request->filled('project_id'), fn ($q) => $q->where('project_id', $request->project_id))
            ->when($request->filled('created_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->created_from))
            ->when($request->filled('created_to'), fn ($q) => $q->whereDate('created_at', '<=', $request->created_to));

        return match ($type) {
            'active' => $query->where('assigned_to_user_id', $user->id)->whereNull('completed_at'),
            'completed' => $query->where('assigned_to_user_id', $user->id)->whereNotNull('completed_at'),
            'received' => $query->where('created_by_user_id', $user->id),
            'transferred' => $query
                ->whereHas('assignedUserUpdateLogs', fn ($q) => $q->where('new_assigned_to_user_id', $user->id))
                ->where('assigned_to_user_id', '!=', $user->id),
            default => $query,
        };
    }

    public function listData(Request $request): JsonResponse
    {
        $allowedSorts = ['number', 'name', 'code', 'created_at', 'due_on'];
        $sort = in_array($request->input('sort'), $allowedSorts, true) ? $request->input('sort') : 'created_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        $tasks = $this->scopedQuery($request)
            ->select('tasks.*')
            ->selectRaw("ROW_NUMBER() OVER (ORDER BY {$sort} {$direction}) as row_number")
            ->orderBy($sort, $direction)
            ->paginate($request->input('per_page', 15));

        return response()->json($tasks);
    }

    private function exportRows(Request $request): \Illuminate\Support\Collection
    {
        return $this->scopedQuery($request)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn (Task $task) => [
                'Nama Pemohon' => $task->name,
                'Nomor Pelayanan' => $task->code,
                'Penerima Pelayanan' => $task->createdByUser?->name ?? '-',
                'Penerima Tugas' => $task->assignedToUser?->name ?? '-',
                'Status' => $task->taskGroup?->name ?? '-',
                'Tanggal Masuk' => $task->created_at?->format('d-m-Y'),
                'Batas Waktu' => $task->due_on?->format('d-m-Y') ?? '-',
                'Periode Pelayanan' => $task->project?->name ?? '-',
            ]);
    }

    public function listExportXlsx(Request $request): StreamedResponse
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\TaskListExport($this->exportRows($request)),
            'pelayanan-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function listExportPdf(Request $request)
    {
        $rows = $this->exportRows($request);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.task-list-pdf', ['rows' => $rows])
            ->setPaper('a4', 'landscape');

        return $pdf->download('pelayanan-'.now()->format('Ymd-His').'.pdf');
    }
}
