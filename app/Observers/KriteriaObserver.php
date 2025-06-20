<?php

namespace App\Observers;

use App\Models\Kriteria;
use Filament\Notifications\Notification;

class KriteriaObserver
{
    public function created(Kriteria $kriteria): void
    {
        $this->recalculateWeights();
    }

    public function updated(Kriteria $kriteria): void
    {
        if ($kriteria->isDirty('bobot') || $kriteria->wasChanged('bobot')) {
            if (!session()->has('skip_weight_recalculation')) {
                $this->recalculateWeights();
            }
        }
    }

    public function deleting(Kriteria $kriteria): void
    {
        session()->put('deleted_kriteria_id', $kriteria->id);
    }

    public function deleted(Kriteria $kriteria): void
    {
        $deletedId = session()->get('deleted_kriteria_id');
        $this->recalculateWeights($deletedId);
        session()->forget('deleted_kriteria_id');
    }

    private function recalculateWeights(?int $excludeId = null): void
    {
        Kriteria::redistributeWeights($excludeId);

        $totalWeight = Kriteria::sum('bobot');
    }
}
