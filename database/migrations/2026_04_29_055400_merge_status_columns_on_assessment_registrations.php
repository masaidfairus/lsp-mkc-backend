<?php

use App\Enums\AssessmentStatus;
use App\Enums\Apl01Status;
use App\Enums\Apl02Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Merge status_apl01 + status_apl02 into a single status column.
     *
     * Priority mapping:
     *   1. APL-02 is submitted/approved/rejected → follow APL-02 state
     *   2. APL-02 is not_started but APL-01 is approved → 'apl01_approved'
     *   3. APL-01 is rejected                          → 'apl01_rejected'
     *   4. APL-01 is paid                             → 'apl01_paid'
     *   5. APL-01 is pending                          → 'apl01_pending'
     *   6. Default                                    → 'apl01_draft'
     */
    public function up(): void
    {
        // ── 1. Add nullable status column ────────────────────────────
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->string('status')->nullable()->after('status_apl02')->index();
        });

        // ── 2. Backfill using priority CASE logic ─────────────────────
        DB::statement("
            UPDATE assessment_registrations
            SET status = CASE
                WHEN status_apl02 = 'approved'  THEN 'apl02_approved'
                WHEN status_apl02 = 'rejected'  THEN 'apl02_rejected'
                WHEN status_apl02 = 'submitted' THEN 'apl02_submitted'
                WHEN status_apl01 = 'approved'  THEN 'apl01_approved'
                WHEN status_apl01 = 'rejected'  THEN 'apl01_rejected'
                WHEN status_apl01 = 'paid'      THEN 'apl01_paid'
                WHEN status_apl01 = 'pending'   THEN 'apl01_pending'
                ELSE 'apl01_draft'
            END
        ");

        // ── 3. Make status NOT NULL with default ─────────────────────
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->string('status')
                ->default(AssessmentStatus::APL01_DRAFT->value)
                ->nullable(false)
                ->change();
        });

        // ── 4. Drop old columns ───────────────────────────────────────
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->dropIndex(['status_apl01', 'status_apl02']);
        });
    }

    /**
     * Reverse the migration: restore status_apl01 and status_apl02 from status.
     */
    public function down(): void
    {
        // ── 1. Re-add old columns as nullable ────────────────────────
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->string('status_apl01')->nullable()->after('tuk')->index();
            $table->string('status_apl02')->nullable()->after('status_apl01')->index();
        });

        // ── 2. Backfill old columns from status ───────────────────────
        DB::statement("
            UPDATE assessment_registrations
            SET
                status_apl01 = CASE
                    WHEN status IN ('apl02_submitted','apl02_approved','apl02_rejected') THEN 'approved'
                    WHEN status = 'apl01_approved' THEN 'approved'
                    WHEN status = 'apl01_rejected' THEN 'rejected'
                    WHEN status = 'apl01_paid'     THEN 'paid'
                    WHEN status = 'apl01_pending'  THEN 'pending'
                    ELSE 'not_started'
                END,
                status_apl02 = CASE
                    WHEN status = 'apl02_approved'  THEN 'approved'
                    WHEN status = 'apl02_rejected'  THEN 'rejected'
                    WHEN status = 'apl02_submitted' THEN 'submitted'
                    ELSE 'not_started'
                END
        ");

        // ── 3. Make old columns NOT NULL with defaults ────────────────
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->string('status_apl01')
                ->default(Apl01Status::NOT_STARTED->value)
                ->nullable(false)
                ->change();
            $table->string('status_apl02')
                ->default(Apl02Status::NOT_STARTED->value)
                ->nullable(false)
                ->change();
        });

        // ── 4. Drop new status column ─────────────────────────────────
        Schema::table('assessment_registrations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
