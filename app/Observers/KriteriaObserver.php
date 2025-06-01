<?php

namespace App\Observers;

use App\Models\Kriteria;

class KriteriaObserver
{
    /**
     * Handle the Kriteria "created" event.
     */
    public function created(Kriteria $kriteria): void
    {
        $this->recalculateWeights();
    }

    /**
     * Handle the Kriteria "deleted" event.
     */
    public function deleted(Kriteria $kriteria): void
    {
        $this->recalculateWeights();
    }

    /**
     * Handle the Kriteria "updated" event.
     */
    public function updated(Kriteria $kriteria): void
    {
        // Jika yang diubah adalah bobot, recalculate
        if ($kriteria->isDirty('bobot')) {
            $this->recalculateWeights();
        }
    }

    /**
     * Recalculate weights to ensure the sum is always 1
     */
    private function recalculateWeights(): void
    {
        // Gunakan method dari model Kriteria
        Kriteria::redistributeWeights();
    }
}
