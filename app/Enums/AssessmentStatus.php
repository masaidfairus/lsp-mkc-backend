<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * Unified state machine for AssessmentRegistration.
 *
 * Flow:
 *   apl01_draft
 *     → apl01_pending    (asesi submits APL-01)
 *     → apl01_paid       (payment confirmed)
 *     → apl01_approved   (admin approves APL-01)
 *     → apl01_rejected   (admin rejects APL-01)
 *   apl01_approved
 *     → apl02_submitted  (asesi submits APL-02)
 *     → apl02_approved   (admin approves APL-02)
 *     → apl02_rejected   (admin rejects APL-02)
 */
enum AssessmentStatus: string implements HasLabel, HasColor, HasIcon
{
    // ── APL-01 states ────────────────────────────────────────────────
    case APL01_DRAFT    = 'apl01_draft';
    case APL01_PENDING  = 'apl01_pending';
    case APL01_PAID     = 'apl01_paid';
    case APL01_APPROVED = 'apl01_approved';
    case APL01_REJECTED = 'apl01_rejected';

    // ── APL-02 states ────────────────────────────────────────────────
    case APL02_SUBMITTED = 'apl02_submitted';
    case APL02_APPROVED  = 'apl02_approved';
    case APL02_REJECTED  = 'apl02_rejected';

    // ── Filament: label ──────────────────────────────────────────────
    public function getLabel(): ?string
    {
        return match ($this) {
            self::APL01_DRAFT     => 'Belum Dimulai',
            self::APL01_PENDING   => 'APL-01 Terkirim',
            self::APL01_PAID      => 'APL-01 Sudah Bayar',
            self::APL01_APPROVED  => 'APL-01 Diterima',
            self::APL01_REJECTED  => 'APL-01 Ditolak',
            self::APL02_SUBMITTED => 'APL-02 Terkirim',
            self::APL02_APPROVED  => 'APL-02 Diterima',
            self::APL02_REJECTED  => 'APL-02 Ditolak',
        };
    }

    // ── Filament: badge color ────────────────────────────────────────
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::APL01_DRAFT     => 'gray',
            self::APL01_PENDING   => 'warning',
            self::APL01_PAID      => 'info',
            self::APL01_APPROVED  => 'success',
            self::APL01_REJECTED  => 'danger',
            self::APL02_SUBMITTED => 'warning',
            self::APL02_APPROVED  => 'success',
            self::APL02_REJECTED  => 'danger',
        };
    }

    // ── Filament: icon ───────────────────────────────────────────────
    public function getIcon(): ?string
    {
        return match ($this) {
            self::APL01_DRAFT     => 'heroicon-o-clock',
            self::APL01_PENDING   => 'heroicon-o-paper-airplane',
            self::APL01_PAID      => 'heroicon-o-banknotes',
            self::APL01_APPROVED  => 'heroicon-o-check-circle',
            self::APL01_REJECTED  => 'heroicon-o-x-circle',
            self::APL02_SUBMITTED => 'heroicon-o-paper-airplane',
            self::APL02_APPROVED  => 'heroicon-o-check-badge',
            self::APL02_REJECTED  => 'heroicon-o-x-circle',
        };
    }

    // ── Domain helpers ────────────────────────────────────────────────

    /**
     * APL-01 has been fully approved (asesi may proceed to APL-02 stage).
     */
    public function isApl01Complete(): bool
    {
        return in_array($this, [
            self::APL01_APPROVED,
            self::APL02_SUBMITTED,
            self::APL02_APPROVED,
            self::APL02_REJECTED,
        ]);
    }

    /**
     * APL-02 has been fully approved (asesi may access AK documents).
     */
    public function isApl02Complete(): bool
    {
        return $this === self::APL02_APPROVED;
    }

    /**
     * APL-01 is awaiting admin review (pending or paid).
     */
    public function isApl01Pending(): bool
    {
        return in_array($this, [self::APL01_PENDING, self::APL01_PAID]);
    }

    /**
     * Returns the APL-01 portion of the status as a legacy-compatible string.
     * Used by RegistrationController to expose status to mobile clients.
     */
    public function apl01Value(): string
    {
        return match ($this) {
            self::APL01_DRAFT     => 'not_started',
            self::APL01_PENDING   => 'pending',
            self::APL01_PAID      => 'paid',
            self::APL01_APPROVED,
            self::APL02_SUBMITTED,
            self::APL02_APPROVED,
            self::APL02_REJECTED  => 'approved',
            self::APL01_REJECTED  => 'rejected',
        };
    }

    /**
     * Returns the APL-02 portion of the status as a legacy-compatible string.
     * Used by RegistrationController to expose status to mobile clients.
     */
    public function apl02Value(): string
    {
        return match ($this) {
            self::APL01_DRAFT,
            self::APL01_PENDING,
            self::APL01_PAID,
            self::APL01_APPROVED,
            self::APL01_REJECTED  => 'not_started',
            self::APL02_SUBMITTED => 'submitted',
            self::APL02_APPROVED  => 'approved',
            self::APL02_REJECTED  => 'rejected',
        };
    }
}
