<?php

namespace App\Http\Controllers\NoLogin;

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
use App\Models\JobTitle;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NoLoginController extends Controller
{

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
       
        $this->authorize('create', [Task::class, $project]);
        
        (new CreateTask)->create($project, $request->validated());

        return redirect()->route('projects.tasks', $project)->success('Pelayanan ditambahkan', 'Pelayanan baru berhasil ditambahkan.');
    }
    //
}
