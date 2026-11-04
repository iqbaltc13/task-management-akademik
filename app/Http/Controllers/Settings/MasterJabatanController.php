<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterJabatan\MasterJabatanRequest;
use App\Http\Resources\MasterJabatan\MasterJabatanResource;
use App\Models\MasterJabatan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MasterJabatanController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MasterJabatan::class, 'master_jabatan');
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Settings/MasterJabatans/Index', [
            'items' => MasterJabatanResource::collection(
                MasterJabatan::query()
                    ->searchByQueryString()
                    ->sortByQueryString()
                    ->when(! $request->filled('sort'), fn ($query) => $query->orderBy('code'))
                    ->when($request->has('archived'), fn ($query) => $query->onlyArchived())
                    ->paginate(12)
            ),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Settings/MasterJabatans/Create');
    }

    public function store(MasterJabatanRequest $request)
    {
        MasterJabatan::create($request->validated());

        return redirect()->route('settings.master-jabatans.index')
            ->success('Jabatan ditambahkan', 'Jabatan baru berhasil ditambahkan.');
    }

    public function edit(MasterJabatan $masterJabatan): Response
    {
        return Inertia::render('Settings/MasterJabatans/Edit', [
            'item' => new MasterJabatanResource($masterJabatan),
        ]);
    }

    public function update(MasterJabatan $masterJabatan, MasterJabatanRequest $request)
    {
        $masterJabatan->update($request->validated());

        return redirect()->route('settings.master-jabatans.index')
            ->success('Jabatan diperbarui', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(MasterJabatan $masterJabatan)
    {
        if ($masterJabatan->stos()->exists()) {
            return redirect()->back()->error(
                'Jabatan tidak dapat diarsipkan',
                'Jabatan masih dipakai oleh struktur organisasi yang aktif.'
            );
        }

        $masterJabatan->archive();

        return redirect()->back()->success('Jabatan diarsipkan', 'Jabatan berhasil diarsipkan.');
    }

    public function restore(int $id)
    {
        $model = MasterJabatan::withArchived()->findOrFail($id);

        $this->authorize('restore', $model);

        $model->unArchive();

        return redirect()->back()->success('Jabatan dikembalikan', 'Jabatan berhasil dikembalikan.');
    }
}
