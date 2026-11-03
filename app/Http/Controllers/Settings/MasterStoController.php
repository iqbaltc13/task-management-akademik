<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterSto\MasterStoRequest;
use App\Http\Resources\MasterSto\MasterStoResource;
use App\Models\MasterJabatan;
use App\Models\MasterSubUnit;
use App\Models\MasterUnit;
use App\Models\MasterSto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MasterStoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MasterSto::class, 'master_sto');
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Settings/MasterStos/Index', [
            'items' => MasterStoResource::collection(
                MasterSto::query()->with(['parent', 'unit', 'subUnit', 'jabatan'])
                    ->searchByQueryString()
                    ->sortByQueryString()
                    ->when(! $request->filled('sort'), fn ($query) => $query->orderBy('level')->orderBy('code'))
                    ->when($request->has('archived'), fn ($query) => $query->onlyArchived())
                    ->paginate(12)
            ),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Settings/MasterStos/Create', [
            'options' => $this->options(),
        ]);
    }

    public function store(MasterStoRequest $request)
    {
        MasterSto::create($request->validated());

        return redirect()->route('settings.master-stos.index')
            ->success('Struktur Organisasi ditambahkan', 'Struktur Organisasi baru berhasil ditambahkan.');
    }

    public function edit(MasterSto $masterSto): Response
    {
        return Inertia::render('Settings/MasterStos/Edit', [
            'item' => new MasterStoResource($masterSto),
            'options' => $this->options($masterSto),
        ]);
    }

    public function update(MasterSto $masterSto, MasterStoRequest $request)
    {
        $masterSto->update($request->validated());

        return redirect()->route('settings.master-stos.index')
            ->success('Struktur Organisasi diperbarui', 'Struktur Organisasi berhasil diperbarui.');
    }

    public function destroy(MasterSto $masterSto)
    {
        if ($masterSto->children()->exists()) {
            return redirect()->back()->error(
                'Struktur Organisasi tidak dapat diarsipkan',
                'Masih ada struktur organisasi aktif di bawahnya (anak). Arsipkan atau pindahkan terlebih dahulu.'
            );
        }

        $masterSto->archive();

        return redirect()->back()->success('Struktur Organisasi diarsipkan', 'Struktur Organisasi berhasil diarsipkan.');
    }

    public function restore(int $id)
    {
        $model = MasterSto::withArchived()->findOrFail($id);

        $this->authorize('restore', $model);

        $model->unArchive();

        return redirect()->back()->success('Struktur Organisasi dikembalikan', 'Struktur Organisasi berhasil dikembalikan.');
    }

    /**
     * Opsi untuk select di form. Pada mode edit, diri sendiri dan turunannya
     * dikeluarkan dari daftar induk agar tidak terbentuk siklus.
     *
     * @return array<string, mixed>
     */
    private function options(?MasterSto $current = null): array
    {
        $exclude = $current ? array_merge([$current->code], $current->descendantCodes()) : [];

        $label = fn ($row) => $row->code.' - '.$row->name;

        return [
            'units' => MasterUnit::orderBy('code')->get(['id', 'code', 'name'])
                ->map(fn ($u) => ['value' => (string) $u->id, 'label' => $label($u)])->values(),
            'subUnits' => MasterSubUnit::with('unit:id,code')->orderBy('code')->get()
                ->map(fn ($s) => [
                    'value' => (string) $s->id,
                    'label' => $label($s),
                    'unit_id' => $s->unit?->id,
                ])->values(),
            'jabatans' => MasterJabatan::orderBy('code')->get(['id', 'code', 'name'])
                ->map(fn ($j) => ['value' => (string) $j->id, 'label' => $label($j)])->values(),
            'parents' => MasterSto::whereNotIn('code', $exclude)->orderBy('level')->orderBy('code')
                ->get(['code', 'name', 'level'])
                ->map(fn ($s) => ['value' => $s->code, 'label' => $label($s), 'level' => $s->level])->values(),
        ];
    }
}
