<?php

namespace App\Filament\Widgets;

use App\Enums\AssessmentStatus;
use App\Models\AssessmentRegistration;
use Filament\Widgets\Widget;
use Livewire\WithPagination;

class AsesiTypeReportWidget extends Widget
{
    use WithPagination;

    protected static string $view = 'filament.widgets.asesi-type-report-widget';

    protected static ?int $sort = 10;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    // Real-time Filters
    public string $filterAsesiType = 'all';
    public string $filterPeriod = 'all';
    public string $filterStatus = 'all';

    /**
     * Only allow Admin and Superadmin to view this business intelligence report.
     *
     * @return bool
     */
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasRole('superadmin') || in_array($user->role, ['admin', 'superadmin']));
    }

    // Reset pagination to page 1 whenever any filter changes
    public function updatedFilterAsesiType()
    {
        $this->resetPage();
    }

    public function updatedFilterPeriod()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    /**
     * Helper to apply active filters dynamically to queries
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    protected function applyFilters($query)
    {
        // 1. Tipe Asesi
        if ($this->filterAsesiType !== 'all') {
            $query->where('asesi_type', $this->filterAsesiType);
        }

        // 2. Periode Waktu
        if ($this->filterPeriod !== 'all') {
            if ($this->filterPeriod === 'today') {
                $query->whereDate('created_at', today());
            } elseif ($this->filterPeriod === 'month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            } elseif ($this->filterPeriod === 'year') {
                $query->whereYear('created_at', now()->year);
            }
        }

        // 3. Status Sertifikasi Grouping
        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'permohonan') {
                $query->where(function($q) { $q->whereNull('certificate')->orWhere('certificate', '')->orWhere('certificate', '[]'); })
                      ->where(function($q) { $q->whereNull('final_result')->orWhere('final_result', ''); })
                      ->whereIn('status', [
                          AssessmentStatus::APL01_DRAFT,
                          AssessmentStatus::APL01_PENDING,
                          AssessmentStatus::APL01_PAID
                      ]);
            } elseif ($this->filterStatus === 'proses') {
                $query->where(function($q) { $q->whereNull('certificate')->orWhere('certificate', '')->orWhere('certificate', '[]'); })
                      ->where(function($q) { $q->whereNull('final_result')->orWhere('final_result', ''); })
                      ->whereNotIn('status', [
                          AssessmentStatus::APL01_DRAFT,
                          AssessmentStatus::APL01_PENDING,
                          AssessmentStatus::APL01_PAID
                      ]);
            } elseif ($this->filterStatus === 'selesai') {
                $query->where(function($q) {
                    $q->whereNotNull('certificate')->where('certificate', '!=', '')->where('certificate', '!=', '[]')
                      ->orWhere(function($sq) {
                          $sq->whereNotNull('final_result')->where('final_result', '!=', '');
                      });
                });
            }
        }
    }

    /**
     * Compute statistics and retrieve filtered dataset
     *
     * @return array
     */
    public function getReportData(): array
    {
        // Base query for counting statistics with current filters
        $statsQuery = AssessmentRegistration::query();
        $this->applyFilters($statsQuery);

        $total = $statsQuery->count();
        $pribadi = (clone $statsQuery)->where('asesi_type', 'pribadi')->count();
        $institusi = (clone $statsQuery)->where('asesi_type', 'institusi')->count();
        
        $selesai = (clone $statsQuery)->where(function($q) {
            $q->whereNotNull('certificate')->where('certificate', '!=', '')->where('certificate', '!=', '[]')
              ->orWhere(function($sq) {
                  $sq->whereNotNull('final_result')->where('final_result', '!=', '');
              });
        })->count();
        
        $permohonan = (clone $statsQuery)->where(function($q) { $q->whereNull('certificate')->orWhere('certificate', '')->orWhere('certificate', '[]'); })
            ->where(function($q) { $q->whereNull('final_result')->orWhere('final_result', ''); })
            ->whereIn('status', [
                AssessmentStatus::APL01_DRAFT,
                AssessmentStatus::APL01_PENDING,
                AssessmentStatus::APL01_PAID
            ])->count();
            
        $proses = (clone $statsQuery)->where(function($q) { $q->whereNull('certificate')->orWhere('certificate', '')->orWhere('certificate', '[]'); })
            ->where(function($q) { $q->whereNull('final_result')->orWhere('final_result', ''); })
            ->whereNotIn('status', [
                AssessmentStatus::APL01_DRAFT,
                AssessmentStatus::APL01_PENDING,
                AssessmentStatus::APL01_PAID
            ])->count();

        // Paginated list for table view
        $listQuery = AssessmentRegistration::with(['candidate.profile', 'scheme', 'signatureRequests'])
            ->latest();
        $this->applyFilters($listQuery);
        
        $registrations = $listQuery->paginate(10);

        return [
            'total' => $total,
            'pribadi' => $pribadi,
            'institusi' => $institusi,
            'permohonan' => $permohonan,
            'proses' => $proses,
            'selesai' => $selesai,
            'registrations' => $registrations,
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view, $this->getReportData());
    }
}
