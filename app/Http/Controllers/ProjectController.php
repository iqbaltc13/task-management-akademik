<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\ClientCompany;
use App\Models\Currency;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Arr;
use DB;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(Request $request)
    {
        return Inertia::render('Projects/Index', [
            'items' => ProjectResource::collection(
                Project::searchByQueryString()
                    ->when($request->user()->isNotAdmin(), function ($query) {
                        $query->whereHas('clientCompany.clients', fn ($query) => $query->where('users.id', auth()->id()))
                            ->orWhereHas('users', fn ($query) => $query->where('id', auth()->id()));
                    })
                    ->when($request->has('archived'), fn ($query) => $query->onlyArchived())
                    ->with([
                        'clientCompany:id,name',
                        'clientCompany.clients:id,name,avatar',
                        'users:id,name,avatar',
                    ])
                    ->withCount([
                        'tasks AS all_tasks_count',
                        'tasks AS completed_tasks_count' => fn ($query) => $query->whereNotNull('completed_at'),
                        'tasks AS overdue_tasks_count' => fn ($query) => $query->whereNull('completed_at')->whereDate('due_on', '<', now()),
                    ])
                    ->withExists('favoritedByAuthUser AS favorite')
                    ->orderBy('favorite', 'desc')
                    ->orderBy('name', 'asc')
                    ->get()
            ),
        ]);
    }

    public function create()
    {
        return Inertia::render('Projects/Create', [
            'dropdowns' => [
                'companies' => ClientCompany::dropdownValues(),
                'users' => User::userDropdownValues(),
                'currencies' => Currency::dropdownValues(['with' => ['clientCompanies:id,currency_id']]),
            ],
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();

        $data['rate'] *= 100;

        DB::beginTransaction();

        try {
            // 2. Perform your database operations
            $project = Project::create(Arr::except($data, ['users']));
            $data['users'] = User::withoutRole(['client'])
            ->pluck('id')->toArray();
           
            $project->users()->attach($data['users']);

            $project->taskGroups()->createMany([
                ['name' => 'Diajukan'],
                ['name' => 'Diproses'],
                ['name' => 'Tidak Dilanjutakan'],
                ['name' => 'Ditolak'],
                ['name' => 'Selesai'],
                
            ]);

            // 3. Commit changes if everything succeeds
            DB::commit();

            return redirect()->route('projects.index')->success('Grup Permintaan Ditambahkan', 'Grup permintaan berhasil ditambahkan.');

        } catch (Exception $e) {
            // 4. Roll back changes if any query fails
            DB::rollBack();

            // 5. Handle or log the error
            Log::error('Transaction failed: ' . $e->getMessage());
            
            return redirect()->route('projects.index')->error('Grup Permintaan Gagal Ditambahkan', 'Grup permintaan gagal ditambahkan.');
        }
       

       
    }

    public function edit(Project $project)
    {
        return Inertia::render('Projects/Edit', [
            'item' => $project,
            'dropdowns' => [
                'companies' => ClientCompany::dropdownValues(),
                'users' => User::userDropdownValues(),
                'currencies' => Currency::dropdownValues(['with' => ['clientCompanies:id,currency_id']]),
            ],
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = $request->validated();

        $data['rate'] *= 100;

        DB::beginTransaction();
        try {
            $project->update(Arr::except($data, ['users']));

            //$project->users()->sync($data['users']);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return redirect()->route('projects.index')->error('Grup Permintaan Gagal Diperbarui', 'Grup permintaan gagal diperbarui.');
        }

        return redirect()->route('projects.index')->success('Grup Permintaan Diperbarui', 'Grup permintaan berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        DB::beginTransaction();
        try {
            $project->archive();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return redirect()->back()->error('Grup Permintaan Gagal Dihapus', 'Grup permintaan gagal dihapus.');
        }

        return redirect()->back()->success('Grup Permintaan Dihapus', 'Grup permintaan berhasil dihapus.');
    }



    public function restore(string $projectId)
    {
        DB::beginTransaction();
        try {
            $project = Project::withArchived()->findOrFail($projectId);

            $this->authorize('restore', $project);

            $project->unArchive();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return redirect()->back()->error('Grup Permintaan Gagal Direstorasi', 'Grup permintaan gagal direstorasi.');
        }

        return redirect()->back()->success('Grup Permintaan Direstorasi', 'Grup permintaan berhasil direstorasi.');
    }

    public function favoriteToggle(Project $project)
    {
        DB::beginTransaction();
        try {
            request()->user()->toggleFavorite($project);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return redirect()->back()->error('Grup Permintaan Gagal Ditambahkan', 'Grup permintaan gagal ditambahkan.');
        }

        return redirect()->back();
    }

    public function userAccess(Request $request, Project $project)
    {
        
        DB::beginTransaction();

        try {
              $userIds = array_merge(
                    $request->get('users', []),
                    $request->get('clients', [])
                );

            (new ProjectService($project))->updateUserAccess($userIds);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Transaction failed: ' . $e->getMessage());
            return redirect()->back()->error('Grup Permintaan Gagal Ditambahkan Akses', 'Grup permintaan gagal ditambahkan akses.');
        }
    
      

        return redirect()->back();
    }
}
