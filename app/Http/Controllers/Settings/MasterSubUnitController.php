<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterSubUnit\MasterSubUnitRequest;
use App\Http\Resources\MasterSubUnit\MasterSubUnitResource;
use App\Models\MasterUnit;
use App\Models\MasterSubUnit;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MasterSubUnitController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MasterSubUnit::class, 'master_sub_unit');
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Settings/MasterSubUnits/Index', [
            'items' => MasterSubUnitResource::collection(
                MasterSubUnit::query()->with('unit')
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
        return Inertia::render('Settings/MasterSubUnits/Create', [
            'options' => $this->options(),
        ]);
    }

    public function store(MasterSubUnitRequest $request)
    {
        MasterSubUnit::create($request->validated());

        return redirect()->route('settings.master-sub-units.index')
            ->success('Sub Unit ditambahkan', 'Sub Unit baru berhasil ditambahkan.');
    }

    public function edit(MasterSubUnit $masterSubUnit): Response
    {
        return Inertia::render('Settings/MasterSubUnits/Edit', [
            'item' => new MasterSubUnitResource($masterSubUnit),
            'options' => $this->options(),
        ]);
    }

    public function update(MasterSubUnit $masterSubUnit, MasterSubUnitRequest $request)
    {
        $masterSubUnit->update($request->validated());

        return redirect()->route('settings.master-sub-units.index')
            ->success('Sub Unit diperbarui', 'Sub Unit berhasil diperbarui.');
    }

    public function destroy(MasterSubUnit $masterSubUnit)
    {
        if ($masterSubUnit->stos()->exists()) {
            return redirect()->back()->error(
                'Sub Unit tidak dapat diarsipkan',
                'Sub unit masih dipakai oleh struktur organisasi yang aktif.'
            );
        }

        $masterSubUnit->archive();

        return redirect()->back()->success('Sub Unit diarsipkan', 'Sub Unit berhasil diarsipkan.');
    }

    public function restore(int $id)
    {
        $model = MasterSubUnit::withArchived()->findOrFail($id);

        $this->authorize('restore', $model);

        $model->unArchive();

        return redirect()->back()->success('Sub Unit dikembalikan', 'Sub Unit berhasil dikembalikan.');
    }

    /**
     * @return array<string, mixed>
     */
    private function options(): array
    {
        return [
            'units' => MasterUnit::orderBy('code')->get(['code', 'name'])
                ->map(fn ($u) => ['value' => $u->code, 'label' => $u->code.' - '.$u->name])->values(),
        ];
    }
}
