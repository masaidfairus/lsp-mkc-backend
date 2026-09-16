<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Enums\AssessmentStatus;
use App\Models\AssessmentRegistration;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PdfReportController extends Controller
{
    /**
     * Generate APL.01 registration PDF report with matching dynamic filters
     *
     * @param Request $request
     * @return mixed
     */
    public function asesiTypeReport(Request $request)
    {
        // 1. Authorize Admin / Superadmin / Asesor
        $user = $request->user();
        if (!$user || !($user->hasRole('admin') || $user->hasRole('superadmin') || $user->hasRole('asesor') || in_array($user->role, ['admin', 'superadmin', 'asesor']))) {
            abort(403, 'Unauthorized access.');
        }

        // Get inputs from query string
        $filterAsesiType = $request->query('asesi_type', 'all');
        $filterPeriod = $request->query('period', 'all');
        $filterStatus = $request->query('status', 'all');

        // Compile filtered query
        $query = AssessmentRegistration::with(['candidate.profile', 'scheme'])
            ->latest();

        // Restrict to assessor's own assigned assessments if the logged-in user is an assessor
        if ($user->hasRole('asesor') || $user->role === 'asesor') {
            $query->where('assessor_id', $user->id);
        }

        // Apply Tipe Asesi Filter
        if ($filterAsesiType !== 'all') {
            $query->where('asesi_type', $filterAsesiType);
        }

        // Apply Period Filter & Label
        $periodLabel = 'Semua Waktu';
        if ($filterPeriod !== 'all') {
            if ($filterPeriod === 'today') {
                $query->whereDate('created_at', today());
                $periodLabel = 'Hari Ini (' . now()->translatedFormat('d F Y') . ')';
            } elseif ($filterPeriod === 'month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                $periodLabel = 'Bulan Ini (' . now()->translatedFormat('F Y') . ')';
            } elseif ($filterPeriod === 'year') {
                $query->whereYear('created_at', now()->year);
                $periodLabel = 'Tahun Ini (' . now()->translatedFormat('Y') . ')';
            }
        }

        // Apply Status Filter
        if ($filterStatus !== 'all') {
            if ($filterStatus === 'permohonan') {
                $query->whereIn('status', [
                    AssessmentStatus::APL01_DRAFT,
                    AssessmentStatus::APL01_PENDING,
                    AssessmentStatus::APL01_PAID
                ]);
            } elseif ($filterStatus === 'proses') {
                $query->whereIn('status', [
                    AssessmentStatus::APL01_APPROVED,
                    AssessmentStatus::APL02_SUBMITTED,
                    AssessmentStatus::APL02_REJECTED
                ]);
            } elseif ($filterStatus === 'selesai') {
                $query->where('status', AssessmentStatus::APL02_APPROVED);
            }
        }

        $registrations = $query->get();

        // Calculate summary statistics
        $total = $registrations->count();
        $pribadi = $registrations->where('asesi_type', 'pribadi')->count();
        $institusi = $registrations->where('asesi_type', 'institusi')->count();
        
        $selesai = $registrations->where('status', AssessmentStatus::APL02_APPROVED)->count();
        $proses = $registrations->whereIn('status', [
            AssessmentStatus::APL01_APPROVED,
            AssessmentStatus::APL02_SUBMITTED,
            AssessmentStatus::APL02_REJECTED
        ])->count();
        $permohonan = $registrations->whereIn('status', [
            AssessmentStatus::APL01_DRAFT,
            AssessmentStatus::APL01_PENDING,
            AssessmentStatus::APL01_PAID
        ])->count();

        // Data array for PDF Blade template
        $data = [
            'registrations' => $registrations,
            'total' => $total,
            'pribadi' => $pribadi,
            'institusi' => $institusi,
            'permohonan' => $permohonan,
            'proses' => $proses,
            'selesai' => $selesai,
            'filterAsesiType' => $filterAsesiType,
            'filterPeriod' => $filterPeriod,
            'filterStatus' => $filterStatus,
            'periodLabel' => $periodLabel,
            'generatedAt' => now()->translatedFormat('d F Y H:i:s'),
            'adminName' => $user->name,
        ];

        // Load premium PDF view
        $pdf = Pdf::loadView('forms.reports.asesi_type', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $filename = 'Laporan_Permohonan_Sertifikasi_' . date('Ymd_His') . '.pdf';

        return $pdf->stream($filename);
    }
}
