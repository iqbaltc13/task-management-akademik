<?php

namespace App\Http\Controllers\NoLogin;

use App\Http\Controllers\Controller;
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
use App\Support\TaskStatusMapper;

class NoLoginController extends Controller
{

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
       
        
    }

    public function createPermintaan(Request $request)
    {
       return Inertia::render('NoLogin/CreateTask', []);  
    }
     public function lacakPelayanan(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'code' => 'required|string',
            ]);
 
            $task = Task::where('code', $request->code)->first();
 
            if (! $task) {
                return response()->json([
                    'message' => 'Kode permintaan tidak ditemukan.',
                ], 404);
            }
 
            $task->load(['taskGroup', 'groupUpdateLogs' => function ($query) {
                $query->with('newGroup')->orderBy('created_at');
            }]);
 
            $dates = [
                'diterima' => $task->created_at,
                'diproses' => null,
                'selesai' => null,
                'ditolak' => null,
            ];
 
            foreach ($task->groupUpdateLogs as $log) {
                $status = TaskStatusMapper::map($log->newGroup?->name);
 
                if (array_key_exists($status, $dates) && $dates[$status] === null) {
                    $dates[$status] = $log->created_at;
                }
            }
 
            return response()->json([
                'code' => $task->code,
                'status' => TaskStatusMapper::map($task->taskGroup?->name),
                'final_feedback' => $task->final_feedback,
                'link_file_result' => $task->link_file_result,
                'dates' => $dates,
            ]);
        }
 
        if ($request->isMethod('get')) {
            return Inertia::render('NoLogin/LacakPelayanan', []);
        }
    }

    
     
}