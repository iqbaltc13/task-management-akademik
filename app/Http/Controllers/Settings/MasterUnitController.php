<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterUnit\MasterUnitRequest;
use App\Http\Resources\MasterUnit\MasterUnitResource;
use App\Models\MasterUnit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MasterUnitController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MasterUnit::class, 'master_unit');
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Settings/MasterUnits/Index', [
            'items' => MasterUnitResource::collection(
                MasterUnit::query()
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
        return Inertia::render('Settings/MasterUnits/Create');
    }

    public function store(MasterUnitRequest $request)
    {
        MasterUnit::create($request->validated());

        return redirect()->route('settings.master-units.index')
            ->success('Unit ditambahkan', 'Unit baru berhasil ditambahkan.');
    }

    public function edit(MasterUnit $masterUnit): Response
    {
        return Inertia::render('Settings/MasterUnits/Edit', [
            'item' => new MasterUnitResource($masterUnit),
        ]);
    }

    public function update(MasterUnit $masterUnit, MasterUnitRequest $request)
    {
        $masterUnit->update($request->validated());

        return redirect()->route('settings.master-units.index')
            ->success('Unit diperbarui', 'Unit berhasil diperbarui.');
    }

    public function destroy(MasterUnit $masterUnit)
    {
        if ($masterUnit->subUnits()->exists() || $masterUnit->stos()->exists()) {
            return redirect()->back()->error(
                'Unit tidak dapat diarsipkan',
                'Unit masih dipakai oleh sub unit atau struktur organisasi yang aktif.'
            );
        }

        $masterUnit->archive();

        return redirect()->back()->success('Unit diarsipkan', 'Unit berhasil diarsipkan.');
    }

    public function restore(int $id)
    {
        $model = MasterUnit::withArchived()->findOrFail($id);

        $this->authorize('restore', $model);

        $model->unArchive();

        return redirect()->back()->success('Unit dikembalikan', 'Unit berhasil dikembalikan.');
    }
}
