<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ══════════════════════════════════════════════════════════════
        // 1. DOCUMENT LIST — Master semua jenis dokumen
        // ══════════════════════════════════════════════════════════════

        $documents = [
            // ── Bukti Administratif (berlaku semua skema) ─────────────
            ['id' => 1, 'title' => 'Kartu Tanda Penduduk (KTP) / Kartu Keluarga (KK)', 'description' => 'Photocopy KTP atau KK yang masih berlaku'],
            ['id' => 2, 'title' => 'Pas Foto Terbaru 3x4', 'description' => 'Pas foto terbaru ukuran 3x4 cm'],

            // ── Bukti Persyaratan Dasar & Portfolio (shared) ──────────
            ['id' => 3, 'title' => 'Ijazah SMA/SMK Sederajat', 'description' => 'Photocopy ijazah minimal SMA/SMK sederajat'],
            ['id' => 4, 'title' => 'Ijazah D3 Semua Jurusan', 'description' => 'Photocopy ijazah minimal D3 semua jurusan'],
            ['id' => 5, 'title' => 'Sertifikat Pelatihan Tenaga Administrasi Kewirausahaan', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Tenaga Administrasi Kewirausahaan'],
            ['id' => 6, 'title' => 'Sertifikat Pelatihan Tenaga Pelayanan Prima', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Tenaga Pelayanan Prima'],
            ['id' => 7, 'title' => 'Sertifikat Pelatihan Tenaga Penjualan Online', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Tenaga Penjualan Online'],
            ['id' => 8, 'title' => 'Sertifikat Pelatihan Pendamping Kelembagaan Usaha', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Pendamping Kelembagaan Usaha'],
            ['id' => 9, 'title' => 'Sertifikat Pelatihan Motivator Kinerja Tim Penjualan', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Motivator Kinerja Tim Penjualan'],
            ['id' => 10, 'title' => 'Sertifikat Pelatihan Pendamping Kewirausahaan', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Pendamping Kewirausahaan'],
            ['id' => 11, 'title' => 'Sertifikat Pelatihan Petugas Promosi Penjualan', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Petugas Promosi Penjualan'],
            ['id' => 12, 'title' => 'Sertifikat Pelatihan Staf Hubungan Masyarakat', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Staf Hubungan Masyarakat'],
            ['id' => 13, 'title' => 'Sertifikat Pelatihan Manajer Pengembangan Bisnis', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Manajer Pengembangan Bisnis'],
            ['id' => 14, 'title' => 'Sertifikat Pelatihan Jenjang IV Kewirausahaan Industri', 'description' => 'Photocopy sertifikat pelatihan berbasis Kompetensi Jenjang IV Bidang Kewirausahaan Industri'],
            ['id' => 15, 'title' => 'Sertifikat Kompetensi Staf Hubungan Masyarakat', 'description' => 'Photocopy sertifikat kompetensi Staf Hubungan Masyarakat'],
            ['id' => 16, 'title' => 'Surat Pengalaman Kerja Tenaga Administrasi (Min. 2 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 2 tahun di bidang Tenaga Administrasi'],
            ['id' => 17, 'title' => 'Surat Pengalaman Kerja Tenaga Pelayanan Prima (Min. 2 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 2 tahun di bidang Tenaga Pelayanan Prima'],
            ['id' => 18, 'title' => 'Surat Pengalaman Kerja Tenaga Penjualan Online (Min. 1 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 1 tahun di bidang Tenaga Penjualan Online'],
            ['id' => 19, 'title' => 'Surat Pengalaman Kerja Pendamping Kelembagaan Usaha (Min. 2 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 2 tahun di bidang Pendamping Kelembagaan Usaha'],
            ['id' => 20, 'title' => 'Surat Pengalaman Kerja Manajer Pengembangan Bisnis (Min. 3 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 3 tahun sebagai Manajer Pengembangan Bisnis'],
            ['id' => 21, 'title' => 'Surat Pengalaman Kerja Pendamping Kewirausahaan (Min. 2 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 2 tahun sebagai Pendamping Kewirausahaan'],
            ['id' => 22, 'title' => 'Surat Pengalaman Kerja Petugas Promosi Penjualan (Min. 2 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 2 tahun di bidang Petugas Promosi Penjualan'],
            ['id' => 23, 'title' => 'Surat Pengalaman Kerja Staf Hubungan Masyarakat (Min. 2 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 2 tahun di bidang Staf Hubungan Masyarakat'],
            ['id' => 24, 'title' => 'Surat Pengalaman Kerja Manajer Pengembangan Bisnis (Min. 2 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 2 tahun sebagai Manajer Pengembangan Bisnis'],
            ['id' => 25, 'title' => 'Surat Pengalaman Kerja Kewirausahaan Industri (Min. 3 Tahun)', 'description' => 'Surat keterangan pengalaman kerja minimal 3 tahun di bidang Kewirausahaan Industri'],

            // ── Portfolio only ─────────────────────────────────────────
            // Skema 1 - Tenaga Administrasi Kewirausahaan
            ['id' => 101, 'title' => 'Laporan Analisis HPP', 'description' => 'Laporan analisis Harga Pokok Produksi'],
            ['id' => 102, 'title' => 'RAB Usaha', 'description' => 'Rencana Anggaran Biaya usaha'],
            ['id' => 103, 'title' => 'Dokumen Perizinan Usaha', 'description' => 'Dokumen perizinan usaha yang dimiliki'],
            ['id' => 104, 'title' => 'Buku Kas Masuk & Keluar', 'description' => 'Buku pencatatan kas masuk dan keluar'],
            ['id' => 105, 'title' => 'SOP Penyimpanan Bahan & Produk', 'description' => 'Standar Operasional Prosedur penyimpanan bahan dan produk'],
            ['id' => 106, 'title' => 'Jadwal Kerja Mingguan/Bulanan', 'description' => 'Jadwal kerja mingguan atau bulanan'],
            ['id' => 107, 'title' => 'Buku Agenda Surat Masuk & Keluar', 'description' => 'Buku agenda pencatatan surat masuk dan keluar'],
            ['id' => 108, 'title' => 'Rekap Keluhan Pelanggan', 'description' => 'Rekap atau dokumentasi keluhan pelanggan'],
            ['id' => 109, 'title' => 'Rekap Transaksi Bank', 'description' => 'Rekap transaksi perbankan'],

            // Skema 2 - Tenaga Pelayanan Prima
            ['id' => 201, 'title' => 'Materi Promosi (Brosur/Flyer/Katalog/Konten Media Sosial)', 'description' => 'Materi promosi dalam berbagai format'],
            ['id' => 202, 'title' => 'Program Customer Relationship', 'description' => 'Program pengelolaan hubungan pelanggan'],
            ['id' => 203, 'title' => 'SOP Komunikasi Pelanggan', 'description' => 'Standar Operasional Prosedur komunikasi pelanggan'],
            ['id' => 204, 'title' => 'SOP Pelayanan Via Telepon', 'description' => 'Standar Operasional Prosedur pelayanan via telepon'],
            ['id' => 205, 'title' => 'SOP Pelayanan Pelanggan', 'description' => 'Standar Operasional Prosedur pelayanan pelanggan'],
            ['id' => 206, 'title' => 'Product Knowledge Sheet', 'description' => 'Lembar pengetahuan produk'],

            // Skema 3 - Tenaga Penjualan Online
            ['id' => 301, 'title' => 'Konten Promosi Media Sosial', 'description' => 'Konten promosi yang dibuat di media sosial'],
            ['id' => 302, 'title' => 'Laporan Analisis STP', 'description' => 'Laporan analisis Segmentasi, Targeting, Positioning'],
            ['id' => 303, 'title' => 'SOP Komunikasi Online', 'description' => 'Standar Operasional Prosedur komunikasi online'],
            ['id' => 304, 'title' => 'Akun Bisnis Media Sosial', 'description' => 'Screenshot atau dokumentasi akun bisnis media sosial'],
            ['id' => 305, 'title' => 'Insight Media Sosial', 'description' => 'Data insight/analitik media sosial'],
            ['id' => 306, 'title' => 'Kalender Konten', 'description' => 'Perencanaan kalender konten media sosial'],
            ['id' => 307, 'title' => 'Jadwal Posting', 'description' => 'Jadwal posting konten media sosial'],
            ['id' => 308, 'title' => 'Alur Pelayanan Pelanggan Online', 'description' => 'Dokumen alur pelayanan pelanggan secara online'],
            ['id' => 309, 'title' => 'Template Jawaban Pelanggan', 'description' => 'Template standar jawaban untuk pelanggan'],
            ['id' => 310, 'title' => 'Dokumentasi Pencarian Informasi Produk', 'description' => 'Dokumentasi proses pencarian informasi produk'],
            ['id' => 311, 'title' => 'Kuesioner Survey Online', 'description' => 'Kuesioner atau instrumen survey yang dilakukan online'],

            // Skema 4 - Pendamping Kelembagaan Usaha
            ['id' => 401, 'title' => 'Contoh Komunikasi Profesional (Email/Surat)', 'description' => 'Contoh komunikasi profesional dalam bentuk email atau surat resmi'],
            ['id' => 402, 'title' => 'Minutes of Meeting (MoM) / Notulen Rapat', 'description' => 'Notulen atau minutes of meeting kegiatan rapat'],
            ['id' => 403, 'title' => 'Laporan Monitoring Produksi', 'description' => 'Laporan hasil monitoring proses produksi'],
            ['id' => 404, 'title' => 'Analisis Kebutuhan Tenaga Kerja', 'description' => 'Dokumen analisis kebutuhan tenaga kerja'],
            ['id' => 405, 'title' => 'SOP Operasional Usaha / Work Instruction', 'description' => 'SOP operasional usaha atau instruksi kerja'],
            ['id' => 406, 'title' => 'Format Kontrak Kerja', 'description' => 'Format atau template kontrak kerja'],
            ['id' => 407, 'title' => 'Rencana Anggaran Investasi', 'description' => 'Dokumen rencana anggaran investasi'],
            ['id' => 408, 'title' => 'Laporan Kegiatan Pendampingan', 'description' => 'Laporan hasil kegiatan pendampingan'],

            // Skema 5 - Motivator Kinerja Tim Penjualan
            ['id' => 501, 'title' => 'Dokumentasi Rapat/Briefing', 'description' => 'Dokumentasi pelaksanaan rapat atau briefing'],
            ['id' => 502, 'title' => 'Program Pengembangan Tim Sales', 'description' => 'Dokumen program pengembangan tim penjualan'],
            ['id' => 503, 'title' => 'Laporan Program Coaching & Mentoring', 'description' => 'Laporan hasil program coaching dan mentoring'],
            ['id' => 504, 'title' => 'Program Manajemen Stres Kerja', 'description' => 'Program pengelolaan stres kerja'],
            ['id' => 505, 'title' => 'Program Transformasi Layanan Pelanggan', 'description' => 'Program transformasi dan peningkatan layanan pelanggan'],

            // Skema 6 - Pendamping Kewirausahaan
            ['id' => 601, 'title' => 'Company Profile Tenant', 'description' => 'Company profile dari tenant yang didampingi'],
            ['id' => 602, 'title' => 'Dokumen Rencana Usaha Tenant', 'description' => 'Dokumen rencana usaha dari tenant'],
            ['id' => 603, 'title' => 'Analisis Peluang Usaha', 'description' => 'Dokumen analisis peluang usaha'],
            ['id' => 604, 'title' => 'Analisis Kebutuhan SDM', 'description' => 'Dokumen analisis kebutuhan sumber daya manusia'],
            ['id' => 605, 'title' => 'Portofolio Produk', 'description' => 'Portofolio produk yang dihasilkan'],
            ['id' => 606, 'title' => 'Dokumen Hak Kekayaan Intelektual (HKI)', 'description' => 'Dokumen kepemilikan HKI'],
            ['id' => 607, 'title' => 'Dokumentasi Sesi Mentoring', 'description' => 'Dokumentasi pelaksanaan sesi mentoring'],
            ['id' => 608, 'title' => 'Program Pengembangan Tim Tenant', 'description' => 'Program pengembangan tim dari tenant'],

            // Skema 7 - Petugas Promosi Penjualan
            ['id' => 701, 'title' => 'Laporan Kinerja Penjualan', 'description' => 'Laporan hasil kinerja penjualan'],
            ['id' => 702, 'title' => 'Form Evaluasi Penjualan', 'description' => 'Form atau instrumen evaluasi penjualan'],
            ['id' => 703, 'title' => 'Laporan Inovasi Promosi', 'description' => 'Laporan inovasi yang dilakukan dalam promosi'],
            ['id' => 704, 'title' => 'Materi Promosi (Poster/Brosur/Digital)', 'description' => 'Materi promosi dalam format poster, brosur, atau digital'],
            ['id' => 705, 'title' => 'Sales Activity Plan (Harian/Mingguan)', 'description' => 'Rencana aktivitas penjualan harian atau mingguan'],

            // Skema 8 - Staf Hubungan Masyarakat
            ['id' => 801, 'title' => 'Notulen Rapat Internal/Eksternal', 'description' => 'Notulen hasil rapat internal maupun eksternal'],
            ['id' => 802, 'title' => 'Akun Media Sosial Perusahaan', 'description' => 'Screenshot atau dokumentasi akun media sosial perusahaan'],
            ['id' => 803, 'title' => 'Dokumen Business Writing', 'description' => 'Press release, surat resmi, artikel perusahaan, proposal kegiatan, atau laporan PR'],
            ['id' => 804, 'title' => 'Dokumentasi Pelaksanaan Event', 'description' => 'Dokumentasi foto atau laporan pelaksanaan event'],
            ['id' => 805, 'title' => 'Desain Konten Digital', 'description' => 'Hasil desain konten digital untuk keperluan humas'],

            // Skema 9 - Manajer Pengembangan Bisnis
            ['id' => 901, 'title' => 'Laporan Analisis Lingkungan Bisnis', 'description' => 'Laporan hasil analisis lingkungan bisnis'],
            ['id' => 902, 'title' => 'Laporan Implementasi Strategi', 'description' => 'Laporan implementasi strategi bisnis'],
            ['id' => 903, 'title' => 'SOP Layanan', 'description' => 'Standar Operasional Prosedur layanan'],
            ['id' => 904, 'title' => 'Dokumen Rencana Pemasaran Organisasi', 'description' => 'Dokumen rencana pemasaran tingkat organisasi'],
            ['id' => 905, 'title' => 'Laporan Program Loyalitas', 'description' => 'Laporan program loyalitas pelanggan'],

            // Skema 10 - Jenjang IV Kewirausahaan Industri
            ['id' => 1001, 'title' => 'Instrumen Kuesioner Survey Pasar', 'description' => 'Kuesioner atau instrumen untuk survey pasar'],
            ['id' => 1002, 'title' => 'Flowchart Proses Produksi', 'description' => 'Diagram alur proses produksi'],
            ['id' => 1003, 'title' => 'Data Bahan Baku & Bahan Pembantu', 'description' => 'Data dan spesifikasi bahan baku serta bahan pembantu'],
            ['id' => 1004, 'title' => 'Analisis Perhitungan HPP', 'description' => 'Dokumen analisis perhitungan Harga Pokok Produksi'],
            ['id' => 1005, 'title' => 'Laporan Keputusan Produk', 'description' => 'Laporan hasil pengambilan keputusan terkait produk'],
            ['id' => 1006, 'title' => 'Data Mesin & Peralatan Produksi', 'description' => 'Data inventaris mesin dan peralatan produksi'],
            ['id' => 1007, 'title' => 'Dokumen Rencana Pembelian Bahan Baku', 'description' => 'Perencanaan pembelian bahan baku dan bahan pembantu'],
            ['id' => 1008, 'title' => 'Form Pemeriksaan Barang Masuk (QC Checklist)', 'description' => 'Form quality control untuk pemeriksaan barang masuk'],
            ['id' => 1009, 'title' => 'Data Survey Pedagang Eceran', 'description' => 'Data hasil survey pedagang eceran'],
            ['id' => 1010, 'title' => 'Laporan Hasil Pemasaran', 'description' => 'Laporan hasil kegiatan pemasaran'],
        ];

        foreach ($documents as $doc) {
            DB::table('document_lists')->updateOrInsert(
                ['id' => $doc['id']],
                array_merge($doc, ['created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('  [+] document_lists: ' . count($documents) . ' record');

        // ══════════════════════════════════════════════════════════════
        // 2. BUKTI ADMINISTRATIF — KTP & Pas Foto (semua skema)
        // ══════════════════════════════════════════════════════════════

        $buktiAdmin = [
            ['list_id' => 1], // KTP/KK
            ['list_id' => 2], // Pas Foto 3x4
        ];

        DB::table('administrative_proofs')->delete();
        foreach ($buktiAdmin as $row) {
            DB::table('administrative_proofs')->insert(
                array_merge($row, ['created_at' => $now, 'updated_at' => $now])
            );
        }
        $this->command->info('  [+] administrative_proofs: ' . count($buktiAdmin) . ' record');

        // ══════════════════════════════════════════════════════════════
        // 3. BUKTI PERSYARATAN DASAR — per skema
        //    Pola: Ijazah + Sertifikat Pelatihan (opsional ATAU)
        //          Surat Pengalaman Kerja (wajib ATAU)
        //    is_mandatory = true untuk kedua opsi (logika OR ada di frontend)
        // ══════════════════════════════════════════════════════════════

        DB::table('basic_requirement_proofs')->delete();

        $buktiDasar = [
            // Skema 1 — Tenaga Administrasi Kewirausahaan
            1 => [
                ['list_id' => 3, 'is_mandatory' => true, 'or_group' => null],   // Ijazah SMA
                ['list_id' => 5, 'is_mandatory' => false, 'or_group' => 'A'],  // Sertifikat Pelatihan TAK
                ['list_id' => 16, 'is_mandatory' => false, 'or_group' => 'A'],   // Surat Kerja min 2 thn
            ],
            // Skema 2 — Tenaga Pelayanan Prima
            2 => [
                ['list_id' => 3, 'is_mandatory' => true, 'or_group' => null],   // Ijazah SMA
                ['list_id' => 6, 'is_mandatory' => false, 'or_group' => 'B'],  // Sertifikat Pelatihan TPP
                ['list_id' => 17, 'is_mandatory' => false, 'or_group' => 'B'],   // Surat Kerja min 2 thn
            ],
            // Skema 3 — Tenaga Penjualan Online
            3 => [
                ['list_id' => 3, 'is_mandatory' => true, 'or_group' => null],   // Ijazah SMA
                ['list_id' => 7, 'is_mandatory' => false, 'or_group' => 'C'],  // Sertifikat Pelatihan TPO
                ['list_id' => 18, 'is_mandatory' => false, 'or_group' => 'C'],   // Surat Kerja min 1 thn
            ],
            // Skema 4 — Pendamping Kelembagaan Usaha
            4 => [
                ['list_id' => 3, 'is_mandatory' => true, 'or_group' => null],   // Ijazah SMA
                ['list_id' => 8, 'is_mandatory' => false, 'or_group' => 'D'],  // Sertifikat Pelatihan PKU
                ['list_id' => 19, 'is_mandatory' => false, 'or_group' => 'D'],   // Surat Kerja min 2 thn
            ],
            // Skema 5 — Motivator Kinerja Tim Penjualan
            5 => [
                ['list_id' => 4, 'is_mandatory' => true, 'or_group' => null],   // Ijazah D3
                ['list_id' => 9, 'is_mandatory' => false, 'or_group' => 'E'],  // Sertifikat Pelatihan MKTP
                ['list_id' => 20, 'is_mandatory' => false, 'or_group' => 'E'],   // Surat Kerja min 3 thn
            ],
            // Skema 6 — Pendamping Kewirausahaan
            6 => [
                ['list_id' => 4, 'is_mandatory' => true, 'or_group' => null],   // Ijazah D3
                ['list_id' => 10, 'is_mandatory' => false, 'or_group' => 'F'],  // Sertifikat Pelatihan PK
                ['list_id' => 21, 'is_mandatory' => false, 'or_group' => 'F'],   // Surat Kerja min 2 thn
            ],
            // Skema 7 — Petugas Promosi Penjualan
            7 => [
                ['list_id' => 3, 'is_mandatory' => true, 'or_group' => null],   // Ijazah SMA
                ['list_id' => 11, 'is_mandatory' => false, 'or_group' => 'G'],  // Sertifikat Pelatihan PPP
                ['list_id' => 22, 'is_mandatory' => false, 'or_group' => 'G'],   // Surat Kerja min 2 thn
            ],
            // Skema 8 — Staf Hubungan Masyarakat
            8 => [
                ['list_id' => 3, 'is_mandatory' => true, 'or_group' => null],   // Ijazah SMA
                ['list_id' => 12, 'is_mandatory' => false, 'or_group' => 'H'],  // Sertifikat Pelatihan SHM
                ['list_id' => 23, 'is_mandatory' => false, 'or_group' => 'H'],   // Surat Kerja min 2 thn
            ],
            // Skema 9 — Manajer Pengembangan Bisnis
            9 => [
                ['list_id' => 4, 'is_mandatory' => true, 'or_group' => null],   // Ijazah D3
                ['list_id' => 13, 'is_mandatory' => false, 'or_group' => 'I'],  // Sertifikat Pelatihan MPB
                ['list_id' => 15, 'is_mandatory' => false, 'or_group' => 'I'],  // Sertifikat Kompetensi SHM
                ['list_id' => 24, 'is_mandatory' => false, 'or_group' => 'I'],   // Surat Kerja min 2 thn
            ],
            // Skema 10 — Jenjang IV Kewirausahaan Industri
            10 => [
                ['list_id' => 3, 'is_mandatory' => true, 'or_group' => null],   // Ijazah SMA
                ['list_id' => 14, 'is_mandatory' => false, 'or_group' => 'J'],  // Sertifikat Pelatihan JIV
                ['list_id' => 25, 'is_mandatory' => false, 'or_group' => 'J'],   // Surat Kerja min 3 thn
            ],
        ];

        $totalBuktiDasar = 0;
        foreach ($buktiDasar as $schemeId => $items) {
            foreach ($items as $item) {
                DB::table('basic_requirement_proofs')->insert([
                    'scheme_id' => $schemeId,
                    'list_id' => $item['list_id'],
                    'is_mandatory' => $item['is_mandatory'],
                    'or_group' => $item['or_group'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $totalBuktiDasar++;
            }
        }
        $this->command->info('  [+] basic_requirement_proofs: ' . $totalBuktiDasar . ' record');

        // ══════════════════════════════════════════════════════════════
        // 4. PORTFOLIO SCHEME — dokumen portfolio per skema
        //    Sertifikat Pelatihan & Surat Pengalaman Kerja JUGA masuk
        //    di sini karena bisa dipakai sebagai portfolio (shared doc)
        // ══════════════════════════════════════════════════════════════

        DB::table('portfolio_schemes')->delete();

        $portfolioScheme = [
            // Skema 1 — Tenaga Administrasi Kewirausahaan
            1 => [101, 102, 103, 104, 105, 106, 107, 108, 109, 5, 16],
            // Skema 2 — Tenaga Pelayanan Prima
            2 => [201, 202, 203, 204, 205, 108, 206, 6, 17],
            // Skema 3 — Tenaga Penjualan Online
            3 => [301, 302, 303, 304, 305, 306, 307, 308, 309, 310, 311, 7, 18],
            // Skema 4 — Pendamping Kelembagaan Usaha
            4 => [401, 402, 403, 404, 405, 406, 407, 103, 408, 8, 19],
            // Skema 5 — Motivator Kinerja Tim Penjualan
            5 => [501, 502, 503, 504, 505, 9, 20],
            // Skema 6 — Pendamping Kewirausahaan
            6 => [103, 601, 602, 102, 603, 604, 605, 606, 607, 608, 10, 21],
            // Skema 7 — Petugas Promosi Penjualan
            7 => [701, 206, 702, 703, 704, 108, 705, 11, 22],
            // Skema 8 — Staf Hubungan Masyarakat
            8 => [801, 302, 802, 803, 804, 805, 12, 23],
            // Skema 9 — Manajer Pengembangan Bisnis
            9 => [901, 902, 903, 302, 904, 905, 13, 15, 24],
            // Skema 10 — Jenjang IV Kewirausahaan Industri
            10 => [1001, 1002, 1003, 1004, 1005, 1006, 1007, 105, 1008, 704, 1009, 1010, 14, 25],
        ];

        $totalPortfolio = 0;
        foreach ($portfolioScheme as $schemeId => $listIds) {
            foreach ($listIds as $listId) {
                DB::table('portfolio_schemes')->insert([
                    'scheme_id' => $schemeId,
                    'list_id' => $listId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $totalPortfolio++;
            }
        }
        $this->command->info('  [+] portfolio_schemes: ' . $totalPortfolio . ' record');

        $this->command->info('');
        $this->command->info('  DocumentSeeder selesai.');
    }
}