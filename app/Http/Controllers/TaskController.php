<?php

namespace App\Http\Controllers;

use App\Actions\Task\CreateTask;
use App\Actions\Task\UpdateTask;
use App\Events\Task\TaskDeleted;
use App\Events\Task\TaskGroupChanged;
use App\Events\Task\TaskOrderChanged;
use App\Events\Task\TaskRestored;
use App\Events\Task\TaskUpdated;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Label;
use App\Models\OwnerCompany;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskGroup;
use App\Models\TaskGroupUpdateLog;   // ← tambahkan ini
use App\Models\JobTitle;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function index(Request $request, Project $project, ?Task $task = null): Response
    {
        $this->authorize('viewAny', [Task::class, $project]);

        $jobTitles = JobTitle::select('code', 'name')
        ->orderBy('name')
        ->get();

        $groups = $project
            ->taskGroups()
            ->when($request->has('archived'), fn ($query) => $query->onlyArchived())
            ->get();

        $groupedTasks = $project
            ->taskGroups()
            ->with(['project' => fn ($query) => $query->withArchived()])
            ->get()
            ->mapWithKeys(function (TaskGroup $group) use ($request, $project) {
                return [
                    $group->id => Task::where('project_id', $project->id)
                        ->where('group_id', $group->id)
                        ->searchByQueryString()
                        ->filterByQueryString()
                        ->when($request->user()->hasRole('client'), fn ($query) => $query->where('hidden_from_clients', false))
                        ->when($request->has('archived'), fn ($query) => $query->onlyArchived())
                        ->when(! $request->has('status'), fn ($query) => $query->whereNull('completed_at'))
                        ->withDefault()
                        ->when($project->isArchived(), fn ($query) => $query->with(['project' => fn ($query) => $query->withArchived()]))
                        ->get(),
                ];
            });
        $ownerCompany = OwnerCompany::with('currency')->first();
        return Inertia::render('Projects/Tasks/Index', [
            'project' => $project,
            'usersWithAccessToProject' => PermissionService::usersWithAccessToProject($project),
            'labels' => Label::get(['id', 'name', 'color']),
            'taskGroups' => $groups,
            'jobTitles' => $jobTitles,
            'groupedTasks' => $groupedTasks,
            'openedTask' => $task ? $task->loadDefault() : null,
            'currency' => [
                'symbol' => $ownerCompany? $ownerCompany->currency->symbol : "",
            ],
        ]);
    }
    public function table(Request $request, Project $project): Response
    {
        
    $this->authorize('viewAny', [Task::class, $project]);

        $jobTitles = JobTitle::select('code', 'name')->orderBy('name')->get();
        $ownerCompany = OwnerCompany::with('currency')->first();

        return Inertia::render('Projects/Tasks/TableIndex', [
            'project' => $project,
            'usersWithAccessToProject' => PermissionService::usersWithAccessToProject($project),
            'labels' => Label::get(['id', 'name', 'color']),
            'taskGroups' => $project->taskGroups()->get(),
            'jobTitles' => $jobTitles,
            'currency' => [
                'symbol' => $ownerCompany ? $ownerCompany->currency->symbol : "",
            ],
            'filters' => $request->only([
                'search', 'sort', 'direction', 'created_by_user_id', 'assigned_to_user_id', 'created_from', 'created_to', 'page', 'per_page',
            ]),
        ]);
    }
 
    /**
     * Endpoint JSON server-side: sort, search, filter, dan pagination semuanya
     * diproses di backend. Dipanggil lewat axios, bukan Inertia page visit.
     */
    public function tableData(Request $request, Project $project): JsonResponse
    {
        $this->authorize('viewAny', [Task::class, $project]);
 
        $allowedSorts = ['number', 'name', 'code', 'created_at', 'due_on'];
        $sort = in_array($request->input('sort'), $allowedSorts, true) ? $request->input('sort') : 'created_at';
        $direction = $request->input('direction') === 'asc' ? 'asc' : 'desc';
 
        $tasks = Task::query()
            ->select('tasks.*')
            ->selectRaw("ROW_NUMBER() OVER (ORDER BY {$sort} {$direction}) as row_number")
            ->where('project_id', $project->id)
            ->with([
                'createdByUser:id,name',
                'assignedToUser:id,name',
                'taskGroup:id,name',
            ])
            ->searchByQueryString()
            ->when($request->filled('created_by_user_id'), fn ($query) => $query->where('created_by_user_id', $request->created_by_user_id))
            ->when($request->filled('assigned_to_user_id'), fn ($query) => $query->where('assigned_to_user_id', $request->assigned_to_user_id))
            ->when($request->filled('created_from'), fn ($query) => $query->whereDate('created_at', '>=', $request->created_from))
            ->when($request->filled('created_to'), fn ($query) => $query->whereDate('created_at', '<=', $request->created_to))
            ->when($request->has('archived'), fn ($query) => $query->onlyArchived())
            ->orderBy($sort, $direction)
            ->paginate($request->input('per_page', 15));
 
        return response()->json($tasks);
    }

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
       
        $this->authorize('create', [Task::class, $project]);
        
        (new CreateTask)->create($project, $request->validated());

        return redirect()->route('projects.tasks', $project)->success('Pelayanan ditambahkan', 'Pelayanan baru berhasil ditambahkan.');
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): JsonResponse
    {
        $this->authorize('update', [$task, $project]);

        (new UpdateTask)->update($task, $request->validated());

        return response()->json([
            'assigned_user_update_logs' => $task->assignedUserUpdateLogs()
                ->with(['newAssignedUser:id,name', 'oldAssignedUser:id,name'])
                ->get(),
        ]);
    }


    public function reorder(Request $request, Project $project): JsonResponse
    {
        $this->authorize('reorder', [Task::class, $project]);

        Task::setNewOrder($request->ids);

        TaskOrderChanged::dispatch(
            $project->id,
            $request->group_id,
            $request->from_index,
            $request->to_index,
        );

        return response()->json();
    }

    public function move(Request $request, Project $project): JsonResponse
    {
        $this->authorize('reorder', [Task::class, $project]);
        $tasks = Task::whereIn('id', $request->ids)->get();
        Task::setNewOrder($request->ids);
        Task::whereIn('id', $request->ids)->update(['group_id' => $request->to_group_id]);

        $oldGroup = TaskGroup::find($request->from_group_id);
        $newGroup = TaskGroup::find($request->to_group_id);

        foreach ($tasks as $task) {
            $task->activities()->create([
                'project_id' => $task->project_id,
                'user_id' => auth()->id(),
                'title' => 'Grup permintaan diperbarui',
                'subtitle' => $oldGroup
                    ? "Dari \"{$oldGroup->name}\" menjadi \"{$newGroup->name}\" oleh ".auth()->user()->name
                    : "Diatur ke \"{$newGroup->name}\" oleh ".auth()->user()->name,
            ]);

            TaskGroupUpdateLog::create([
                'task_id'      => $task->id,
                'old_group_id' => $request->from_group_id,
                'new_group_id' => $request->to_group_id,
                'user_id'      => auth()->id(),
            ]);
        }
        TaskGroupChanged::dispatch(
            $project->id,
            $request->from_group_id,
            $request->to_group_id,
            $request->from_index,
            $request->to_index,
        );

        return response()->json();
    }

    public function complete(Request $request, Project $project, Task $task): JsonResponse
    {
        $this->authorize('complete', [Task::class, $project]);

        $task->update([
            'completed_at' => ($request->completed === true) ? now() : null,
        ]);
        TaskUpdated::dispatch($task, 'completed_at');

        return response()->json();
    }

    public function destroy(Project $project, Task $task): RedirectResponse
    {
        $this->authorize('archive task', [$task, $project]);

        $task->archive();
        TaskDeleted::dispatch($task->id, $task->project_id);

        return redirect()->back()->success('Tugas dihapus', 'Tugas berhasil dihapus.');
    }

    public function restore(Project $project, Task $task)
    {

        $this->authorize('restore', [$task, $project]);

        $task->unArchive();
        TaskRestored::dispatch($task);

        return redirect()->back()->success('Permintaan dipulihkan', 'pemulihan permintaan berhasil.');
    }

    public function showJson(Project $project, Task $task): JsonResponse
    {
        $this->authorize('update', [$task, $project]);

        return response()->json($task->loadDefault());
    }

    public function updateAssigneeFeedback(Request $request, Project $project, Task $task): JsonResponse
    {
        $request->validate(['feedback' => 'nullable|string']);

        $latestLog = $task->assignedUserUpdateLogs()->reorder('id', 'desc')->first();

       

        if (! $latestLog || (string) $latestLog->new_assigned_to_user_id !== (string) auth()->id()) {
            abort(403, 'Hanya penerima tugas saat ini yang bisa mengisi feedback ini.');
        }

        $latestLog->update(['feedback' => $request->feedback]);

        return response()->json(['feedback' => $latestLog->feedback]);
    }
}
