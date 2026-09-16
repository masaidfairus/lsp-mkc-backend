<?php

namespace App\Filament\Widgets;

use App\Enums\AssessmentStatus;
use App\Models\AssessmentRegistration;
use Filament\Widgets\Widget;
use Livewire\WithPagination;
use Filament\Notifications\Notification;
use App\Exports\AsesiBlankoExport;

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
    public ?string $filterDateFrom = null;
    public ?string $filterDateTo = null;

    // Export properties
    public array $selectedAsesi = [];
    public string $exportFilename = '';
    public bool $selectAll = false;

    /**
     * Only allow Admin and Superadmin to view this business intelligence report.
     *
     * @return bool
     */
    public static function canView(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasRole('superadmin') || $user->hasRole('asesor') || in_array($user->role, ['admin', 'superadmin', 'asesor']));
    }

    // Reset pagination to page 1 whenever any filter changes
    public function updatedFilterAsesiType()
    {
        $this->resetPage();
    }

    public function updatedFilterPeriod()
    {
        $this->resetPage();
        $this->selectedAsesi = [];
        $this->selectAll = false;

        // Reset date range when switching away from 'custom'
        if ($this->filterPeriod !== 'custom') {
            $this->filterDateFrom = null;
            $this->filterDateTo = null;
        }
    }

    public function updatedFilterDateFrom()
    {
        $this->resetPage();
        $this->selectedAsesi = [];
        $this->selectAll = false;
    }

    public function updatedFilterDateTo()
    {
        $this->resetPage();
        $this->selectedAsesi = [];
        $this->selectAll = false;
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
        $this->selectedAsesi = [];
        $this->selectAll = false;
    }

    public function updatedPage()
    {
        $this->selectedAsesi = [];
        $this->selectAll = false;
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedAsesi = [];
            $this->selectAll = false;
        } else {
            $data = $this->getReportData();
            $this->selectedAsesi = $data['registrations']->pluck('id')->map(fn($id) => (string) $id)->toArray();
            $this->selectAll = true;
        }
    }

    public function exportSelectedBlanko()
    {
        if (empty($this->selectedAsesi)) {
            Notification::make()
                ->warning()
                ->title('Pilih asesi')
                ->body('Silakan centang minimal 1 asesi untuk diexport.')
                ->send();
            return;
        }

        $filename = filled($this->exportFilename) 
            ? trim($this->exportFilename) 
            : 'PERMOHONAN BLANKO';

        return app(AsesiBlankoExport::class)->download(
            $this->selectedAsesi,
            $filename
        );
    }

    /**
     * Helper to apply active filters dynamically to queries
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    protected function applyFilters($query)
    {
        // User request: tunjukkan saja semua nama Asesi walaupun Asesor tidak dipilih atas asesi tersebut
        // The restriction has been removed to show all data in the report.

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
            } elseif ($this->filterPeriod === 'custom') {
                if ($this->filterDateFrom) {
                    $query->whereDate('created_at', '>=', $this->filterDateFrom);
                }
                if ($this->filterDateTo) {
                    $query->whereDate('created_at', '<=', $this->filterDateTo);
                }
            }
        }

        // 3. Status Sertifikasi Grouping
        if ($this->filterStatus !== 'all') {
            if ($this->filterStatus === 'permohonan') {
                $query->whereIn('status', [
                    AssessmentStatus::APL01_DRAFT,
                    AssessmentStatus::APL01_PENDING,
                    AssessmentStatus::APL01_PAID
                ]);
            } elseif ($this->filterStatus === 'proses') {
                $query->whereIn('status', [
                    AssessmentStatus::APL01_APPROVED,
                    AssessmentStatus::APL02_SUBMITTED,
                    AssessmentStatus::APL02_REJECTED
                ]);
            } elseif ($this->filterStatus === 'selesai') {
                $query->where('status', AssessmentStatus::APL02_APPROVED);
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
        
        $selesai = (clone $statsQuery)->where('status', AssessmentStatus::APL02_APPROVED)->count();
        
        $permohonan = (clone $statsQuery)->whereIn('status', [
            AssessmentStatus::APL01_DRAFT,
            AssessmentStatus::APL01_PENDING,
            AssessmentStatus::APL01_PAID
        ])->count();
            
        $proses = (clone $statsQuery)->whereIn('status', [
            AssessmentStatus::APL01_APPROVED,
            AssessmentStatus::APL02_SUBMITTED,
            AssessmentStatus::APL02_REJECTED
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
