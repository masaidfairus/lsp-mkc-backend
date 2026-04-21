<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class WorkGroupSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        Schema::disableForeignKeyConstraints();
        DB::table('competency_units')->update(['work_group_id' => null]);
        DB::table('work_groups')->truncate();
        Schema::enableForeignKeyConstraints();

        $definitions = [
            '09-10/SSO/MKC.SKM.001/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Administrasi Usaha dan Perizinan',
                    'units' => [
                        'Melakukan analisis harga pulang pokok',
                        'Menghitung biaya investasi',
                        'Melakukan pengurusan perijinan usaha industri',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Administrasi dan Pembukuan Keuangan',
                    'units' => [
                        'Melakukan pembukuan keuangan untuk setiap transaksi',
                        'Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara dan produk akhir',
                        'Membuat jadwal kerja personil bagian produksi',
                        'Menangani Penerimaan dan Pengiriman Dokumen/Surat',
                        'Menjalin hubungan dengan pelanggan',
                        'Melakukan Transaksi Perbankan Sederhana',
                    ],
                ],
            ],

            '09-10/SSO/MKC.SKM.002/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Mengelola Proses Penjualan dan Promosi Produk',
                    'units' => [
                        'Melakukan promosi produk',
                        'Menjalin hubungan dengan pelanggan',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Menjalin Hubungan dengan Konsumen',
                    'units' => [
                        'Melaksanakan Komunikasi Efektif',
                        'Melakukan Komunikasi melalui Telepon',
                        'Mengelola Layanan Pelanggan Berkualitas',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 3. Menerapkan Pelayanan Prima',
                    'units' => [
                        'Memproses Keluhan Pelanggan',
                        'Mengembangkan Pengetahuan Produk (Barang/Jasa)',
                    ],
                ],
            ],

            '09-10/SSO/MKC.SKM.003/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Survei Pasar dan Membangun Strategi Pemasaran Online',
                    'units' => [
                        'Melakukan promosi produk',
                        'Mengidentifikasi Elemen Pemasaran Organisasi',
                        'Melaksanakan Komunikasi Efektif',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Menggunakan Media Digital Sosial dan Aplikasi Daring (Online Tools)',
                    'units' => [
                        'Menggunakan Media Sosial dan Aplikasi Daring (Online Tools)',
                        'Melaksanakan Kegiatan Analisis di Media Sosial dan Media Bisnis Digital',
                        'Mempersiapkan Konten Digital',
                        'Mengoptimalkan Pengelolaan Media Sosial dan Rencana Aplikasi Digital',
                        'Menciptakan Pengalaman Bagi Pengguna Media Digital',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 3. Melaksanakan Promosi dan Mengelola Pelanggan',
                    'units' => [
                        'Melayani Kebutuhan Informasi Bagi Pelanggan',
                        'Menggunakan Penelusur Situs Web (Web Browser)',
                        'Melaksanakan survey pasar atas suatu produk',
                    ],
                ],
            ],

            '09-10/SSO/MKC.SKM.004/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Membangun Hubungan dan Kepercayaan',
                    'units' => [
                        'Melaksanakan Komunikasi Efektif',
                        'Mengembangkan Pola Komunikasi Efektif kepada Pemangku Kepentingan',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Analisis Perencanaan Kebutuhan Kelembagaan dan Fasilitasi Akses Usaha',
                    'units' => [
                        'Melakukan pengawasan jalannya proses produksi',
                        'Menentukan jumlah dan keahlian tenaga kerja yang dibutuhkan',
                        'Membuat prosedur kerja untuk setiap tugas',
                        'Menyusun peraturan perusahaan dan/atau perjanjian kerja bersama',
                        'Menghitung biaya investasi',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 3. Administrasi dan Pelaporan',
                    'units' => [
                        'Melakukan pengurusan perijinan usaha industri',
                        'Membuat Laporan Tertulis',
                    ],
                ],
            ],

            '09-10/SSO/MKC.SKM.005/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Mengembangkan Keterampilan Penjualan Pribadi',
                    'units' => [
                        'Melaksanakan Komunikasi Efektif',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Melakukan Pembinaan dan Motivasi Tim Penjualan',
                    'units' => [
                        'Mengembangkan Keterampilan dan Kerjasama Anggota Tim Penjual',
                        'Memberikan Bimbingan dan Peningkatan Kualitas Hidup',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 3. Memaksimalkan Target Pencapaian Penjualan',
                    'units' => [
                        'Mengelola Stres Dalam Dinamika Tim Penjualan',
                        'Memimpin Program Transformasi dalam Penjualan dan Layanan',
                    ],
                ],
            ],

            '09-010/SSO/MKC.SKM.006/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Analisis Awal dan Merencanakan Kebutuhan yang Akan Diusahakan',
                    'units' => [
                        'Melakukan Pendampingan Pengurusan Legalitas Kelembagaan dan Usaha',
                        'Melakukan Pendampingan Pembuatan Profil Perusahaan (Company Profile)',
                        'Melakukan Pendampingan Penyusunan Rencana Usaha (Business Plan) Tenant',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Melakukan Pendampingan, Fasilitasi Akses, dan Jejaring',
                    'units' => [
                        'Menghitung biaya investasi',
                        'Menentukan Jenis Produk Yang Akan Diusahakan',
                        'Menentukan jumlah dan keahlian tenaga kerja yang dibutuhkan',
                        'Menyusun dan Mengelola Strategi Portofolio Produk dan/atau Merek',
                        'Melakukan Pendampingan Pengurusan Hak Kekayaan Intelektual (HKI)',
                        'Melaksanakan Komunikasi Efektif',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 3. Membangun Kerjasama Tim',
                    'units' => [
                        'Mengembangkan Kerjasama Tim dan Individu',
                    ],
                ],
            ],

            '09-10/SSO/MKC.SKM.007/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Persiapan dan Perencanaan Penjualan (Sales Activity)',
                    'units' => [
                        'Mencapai Target Penjualan',
                        'Mengembangkan Pengetahuan Produk (Barang/Jasa)',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Mengelola Proses Penjualan',
                    'units' => [
                        'Melaksanakan Keterampilan Penjualan',
                        'Mengembangkan Cara Berfikir Kritis dan Kreatif',
                        'Melaksanakan Kegiatan Promosi Merek',
                        'Menangani Keluhan Pelanggan',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 3. Menangani Keberatan (Handling Objection)',
                    'units' => [
                        'Menyusun Rencana Aktifitas Penjualan',
                    ],
                ],
            ],

            '09-10/SSO/MKC.SKM.008/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Membangun Hubungan Baik dengan Setiap Pemangku Kepentingan (Stakeholder)',
                    'units' => [
                        'Melaksanakan Komunikasi Efektif',
                        'Mengidentifikasi Elemen Pemasaran Organisasi',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Strategi Pemasaran dan Branding',
                    'units' => [
                        'Menggunakan Media Sosial dan Aplikasi Daring (Online Tools)',
                        'Melaksanakan Penulisan Bisnis (Business Writing)',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 3. Membangun Merek secara Digital',
                    'units' => [
                        'Melaksanakan Event Sebagai Bagian dari Komunikasi Merek',
                        'Mempersiapkan Konten Digital',
                    ],
                ],
            ],

            '09-10/SSO/MKC.SKM.009/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Melakukan Analisis Bisnis dan Menerapkan Strategi Pemasaran',
                    'units' => [
                        'Melaksanakan Analisis Lingkungan Bisnis',
                        'Mengembangkan dan Menerapkan Strategi Bisnis',
                        'Mengidentifikasi Elemen Pemasaran Organisasi',
                        'Menyusun Elemen Pemasaran Organisasi',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Mengembangkan dan Mengelola Program Loyalitas Pelanggan',
                    'units' => [
                        'Mengelola Program Loyalitas Pelanggan',
                    ],
                ],
            ],

            '09-10/SSO/MKC.SKM.010/XI/2023' => [
                [
                    'name' => 'Kelompok Pekerjaan 1. Menentukan Jenis Produk yang Akan Diusahakan',
                    'units' => [
                        'Melaksanakan survey pasar atas suatu produk',
                        'Melakukan studi proses produksi suatu produk',
                        'Melakukan survey sumber bahan baku dan bahan pembantu',
                        'Melakukan analisis harga pulang pokok',
                        'Menentukan jenis produk yang akan diusahakan',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 2. Melakukan Pengadaan Sarana dan Bahan Baku serta Bahan Pembantu',
                    'units' => [
                        'Melakukan pengadaan mesin dan peralatan yang dibutuhkan',
                        'Melakukan pengadaan bahan baku dan bahan Pembantu',
                        'Melakukan pengaturan penyimpanan bahan baku, bahan pembantu, produk antara dan produk akhir',
                        'Memeriksa jenis, spesifikasi, dan kondisi bahan baku dan bahan pembantu',
                    ],
                ],
                [
                    'name' => 'Kelompok Pekerjaan 3. Memasarkan Produk',
                    'units' => [
                        'Melakukan promosi produk',
                        'Melakukan survey atas pedagang eceran suatu Produk',
                        'Melakukan pemasaran produk',
                    ],
                ],
            ],
        ];

        foreach ($definitions as $schemeCode => $groups) {
            $this->seedSchemeWorkGroups($schemeCode, $groups, $now);
        }

        $this->command->info('  [+] WorkGroupSeeder selesai. Seluruh work group berhasil di-seed.');
    }

    private function seedSchemeWorkGroups(string $schemeCode, array $groups, Carbon $now): void
    {
        $scheme = DB::table('schemes')->where('code', $schemeCode)->first();

        if (! $scheme) {
            throw new RuntimeException("Skema dengan kode {$schemeCode} tidak ditemukan.");
        }

        $units = DB::table('competency_units')
            ->where('scheme_id', $scheme->id)
            ->get(['id', 'unit_title']);

        $unitsByTitle = [];
        foreach ($units as $unit) {
            $normalizedTitle = $this->normalizeText($unit->unit_title);
            $unitsByTitle[$normalizedTitle][] = $unit->id;
        }

        $assignedIds = [];

        foreach ($groups as $group) {
            $groupId = DB::table('work_groups')->insertGetId([
                'scheme_id' => $scheme->id,
                'name' => $group['name'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $unitIds = [];

            foreach ($group['units'] as $unitTitle) {
                $normalizedTitle = $this->normalizeText($unitTitle);

                if (! array_key_exists($normalizedTitle, $unitsByTitle)) {
                    throw new RuntimeException(
                        "Unit '{$unitTitle}' tidak ditemukan pada skema '{$scheme->name}' ({$schemeCode})."
                    );
                }

                foreach ($unitsByTitle[$normalizedTitle] as $unitId) {
                    $unitIds[] = $unitId;
                    $assignedIds[] = $unitId;
                }
            }

            DB::table('competency_units')
                ->whereIn('id', array_unique($unitIds))
                ->update([
                    'work_group_id' => $groupId,
                    'updated_at' => $now,
                ]);
        }

        $unassignedUnits = $units->filter(fn ($unit) => ! in_array($unit->id, $assignedIds, true));

        if ($unassignedUnits->isNotEmpty()) {
            $titles = $unassignedUnits->pluck('unit_title')->implode(', ');
            throw new RuntimeException(
                "Masih ada competency unit pada skema '{$scheme->name}' yang belum mendapat work group: {$titles}"
            );
        }

        $this->command->info("  [+] Work group untuk skema {$scheme->name} berhasil di-seed.");
    }

    private function normalizeText(string $value): string
    {
        $value = preg_replace('/\s+/u', ' ', trim($value));

        return mb_strtolower($value ?? '');
    }
}
