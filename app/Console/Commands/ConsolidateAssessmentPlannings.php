<?php

namespace App\Console\Commands;

use App\Models\AssessmentPlanning;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ConsolidateAssessmentPlannings extends Command
{
    protected $signature = 'app:consolidate-plannings';
    protected $description = 'Consolidate multiple assessment_plannings rows for the same registration_id';

    /**
     * Menangani.
     *
     * @return mixed
     */
    public function handle()
    {
        $duplicateIds = DB::table('assessment_plannings')
            ->select('registration_id')
            ->groupBy('registration_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('registration_id');

        $this->info('Found ' . $duplicateIds->count() . ' registrations with duplicate planning rows.');

        foreach ($duplicateIds as $regId) {
            $this->info("Consolidating for Registration ID: {$regId}");

            $rows = AssessmentPlanning::where('registration_id', $regId)->orderBy('id')->get();
            $master = $rows->shift();

            foreach ($rows as $row) {
                // Merge data if master doesn't have it yet
                if ($row->mapa01_data && !$master->mapa01_data) $master->mapa01_data = $row->mapa01_data;
                if ($row->mapa02_data && !$master->mapa02_data) $master->mapa02_data = $row->mapa02_data;
                if ($row->ak01_data && !$master->ak01_data) $master->ak01_data = $row->ak01_data;
                if ($row->ak07_data && !$master->ak07_data) $master->ak07_data = $row->ak07_data;
                
                $row->delete();
            }

            $master->save();
        }

        $this->info('Consolidation complete.');
    }
}
