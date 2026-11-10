<?php

namespace App\Actions\Task;

use App\Enums\PricingType;
use App\Events\Task\TaskUpdated;
use App\Models\MasterJenisPelayanan;
use App\Models\Task;
use App\Models\TaskGroup;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class UpdateTask
{
    public function update(Task $task, array $data): void
    {
        $updateField = key($data);

        DB::beginTransaction();

        try {
            $oldGroupId = $task->group_id; // sebelum diubah, untuk mendeteksi perpindahan ke/dari DONE

            if ($updateField === 'pricing_type' && $data['pricing_type'] === PricingType::HOURLY->value) {
                $task->update([
                    'pricing_type' => PricingType::HOURLY,
                    'fixed_price' => null,
                ]);
            }

            if ($updateField === 'fixed_price' && isset($data['fixed_price'])) {
                $data['fixed_price'] = (int) $data['fixed_price'];
            }

            if (! in_array($updateField, ['subscribed_users', 'labels'])) {
                $task->update($data);

                if ($updateField === 'group_id') {
                    $task->update(['order_column' => 0]);

                    $wasDone = TaskGroup::isDoneGroup($oldGroupId);
                    $isDone = TaskGroup::isDoneGroup($data['group_id']);

                    if ($isDone && ! $wasDone) {
                        // pindah ke DONE: tandai selesai (waktu lama dipertahankan bila sudah terisi)
                        $task->update(['completed_at' => $task->completed_at ?? now()]);
                    } elseif ($wasDone && ! $isDone) {
                        // keluar dari DONE: pelayanan kembali berjalan
                        $task->update(['completed_at' => null]);
                    }
                }

                if ($updateField === 'code_jenis_pelayanan') {
                    $jenis = MasterJenisPelayanan::where('code', $data['code_jenis_pelayanan'])->first();

                    if ($jenis && $jenis->jumlah_hari !== null) {
                        $task->update([
                            'due_on' => $task->created_at->copy()->addDays($jenis->jumlah_hari)->toDateString(),
                        ]);
                    }
                }
            }

            // if ($updateField === 'subscribed_users') {
            //     $task->subscribedUsers()->sync($data['subscribed_users']);
            // }

            if ($updateField === 'labels') {
                $task->labels()->sync($data['labels']);
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();

            throw $e;
        }

        TaskUpdated::dispatch($task, $updateField);
    }
}
