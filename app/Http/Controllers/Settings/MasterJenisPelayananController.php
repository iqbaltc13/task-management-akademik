<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\MasterJenisPelayanan\StoreMasterJenisPelayananRequest;
use App\Http\Requests\MasterJenisPelayanan\UpdateMasterJenisPelayananRequest;
use App\Http\Resources\MasterJenisPelayanan\MasterJenisPelayananResource;
use App\Models\MasterJenisPelayanan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MasterJenisPelayananController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(MasterJenisPelayanan::class, 'jenisPelayanan');
    }

    public function index(Request $request): Response
    {
        return Inertia::render('Settings/JenisPelayanans/Index', [
            'items' => MasterJenisPelayananResource::collection(
                MasterJenisPelayanan::searchByQueryString()
                    ->sortByQueryString()
                    ->when($request->has('archived'), fn ($query) => $query->onlyArchived())
                    ->paginate(12)
            ),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Settings/JenisPelayanans/Create');
    }

    public function store(StoreMasterJenisPelayananRequest $request)
    {
        MasterJenisPelayanan::create($request->validated());

        return redirect()->route('settings.jenis-pelayanans.index')
            ->success('Jenis pelayanan ditambahkan', 'Jenis pelayanan baru berhasil ditambahkan.');
    }

    public function edit(MasterJenisPelayanan $jenisPelayanan): Response
    {
        return Inertia::render('Settings/JenisPelayanans/Edit', [
            'item' => new MasterJenisPelayananResource($jenisPelayanan),
        ]);
    }

    public function update(MasterJenisPelayanan $jenisPelayanan, UpdateMasterJenisPelayananRequest $request)
    {
        $jenisPelayanan->update($request->validated());

        return redirect()->route('settings.jenis-pelayanans.index')
            ->success('Jenis pelayanan diperbarui', 'Jenis pelayanan berhasil diperbarui.');
    }

    public function destroy(MasterJenisPelayanan $jenisPelayanan)
    {
        $jenisPelayanan->archive();

        return redirect()->back()
            ->success('Jenis pelayanan diarsipkan', 'Jenis pelayanan berhasil diarsipkan.');
    }

    public function restore(int $jenisPelayananId)
    {
        $jenisPelayanan = MasterJenisPelayanan::withArchived()->findOrFail($jenisPelayananId);

        $this->authorize('restore', $jenisPelayanan);

        $jenisPelayanan->unArchive();

        return redirect()->back()
            ->success('Jenis pelayanan dikembalikan', 'Jenis pelayanan berhasil dikembalikan.');
    }
}
